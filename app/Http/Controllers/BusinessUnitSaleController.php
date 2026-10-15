<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\BusinessUnitSale;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BusinessUnitSaleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'reference_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'business_unit_id' => [
                'nullable',
                'integer',
                'exists:business_units,id',
            ],
        ]);

        $referenceDate = Carbon::parse(
            $filters['reference_date'] ?? today()->toDateString()
        );

        $query = BusinessUnitSale::with([
            'businessUnit',
            'recorder',
            'financialTransaction',
        ])
            ->whereBetween('sale_date', [
                $referenceDate->copy()->startOfMonth()->toDateString(),
                $referenceDate->copy()->endOfMonth()->toDateString(),
            ])
            ->when(
                $filters['business_unit_id'] ?? null,
                function ($query, $unitId) {
                    $query->where('business_unit_id', $unitId);
                }
            );

        // Hitung ringkasan sebelum pagination diterapkan.
        $totalAmount = (clone $query)->sum('amount');
        $totalQuantity = (clone $query)->sum('quantity');
        $totalSales = (clone $query)->count();

        // Gunakan paginate(), bukan get().
        $sales = $query
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $businessUnits = BusinessUnit::orderBy('name')->get();

        return view('business-unit-sales.index', compact(
            'sales',
            'businessUnits',
            'referenceDate',
            'totalAmount',
            'totalQuantity',
            'totalSales',
            'filters'
        ));
    }

    public function create()
    {
        $businessUnits = BusinessUnit::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('business-unit-sales.create', compact(
            'businessUnits'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_unit_id' => [
                'required',
                Rule::exists('business_units', 'id')
                    ->where('is_active', true),
            ],
            'sale_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'payment_method' => [
                'required',
                'in:tunai,qris,transfer,lainnya',
            ],
            'quantity' => ['required', 'integer', 'min:1'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string'],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $unit = BusinessUnit::where('is_active', true)
                ->findOrFail($validated['business_unit_id']);

            $category = FinancialCategory::firstOrCreate(
                ['name' => 'Unit Usaha'],
                [
                    'type' => 'income',
                    'description' => 'Pemasukan dari omzet unit usaha',
                    'is_active' => true,
                ]
            );

            if ($category->type !== 'income' || ! $category->is_active) {
                throw ValidationException::withMessages([
                    'business_unit_id' => 'Kategori keuangan Unit Usaha harus aktif dan bertipe income.',
                ]);
            }

            $transaction = FinancialTransaction::create([
                'category_id' => $category->id,
                'user_id' => Auth::id(),
                'transaction_code' => 'USH-'.now()->format('YmdHis').'-'
                    .Str::upper(Str::random(6)),
                'type' => 'income',
                'amount' => $validated['amount'],
                'transaction_date' => $validated['sale_date'],
                'source' => 'Unit Usaha: '.$unit->name,
                'description' => 'Omzet '.$unit->name
                    .(! empty($validated['reference_number'])
                        ? ' | Referensi: '.$validated['reference_number']
                        : '')
                    .(! empty($validated['description'])
                        ? ' | '.$validated['description']
                        : ''),
                'evidence_path' => null,
                'status' => 'draft',
            ]);

            return BusinessUnitSale::create([
                ...$validated,
                'financial_transaction_id' => $transaction->id,
                'recorded_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('business-unit-sales.index', [
                'reference_date' => $sale->sale_date->toDateString(),
                'business_unit_id' => $sale->business_unit_id,
            ])
            ->with(
                'success',
                'Penjualan dicatat dan transaksi keuangan dibuat sebagai draft.'
            );
    }

    public function report(Request $request)
    {
        $filters = $request->validate([
            'reference_date' => ['nullable', 'date', 'before_or_equal:today'],
            'business_unit_id' => ['nullable', 'integer', 'exists:business_units,id'],
        ]);

        $referenceDate = Carbon::parse(
            $filters['reference_date'] ?? today()->toDateString()
        );

        $startDate = $referenceDate->copy()->startOfMonth();
        $endDate = $referenceDate->copy()->endOfMonth();

        if ($endDate->greaterThan(today())) {
            $endDate = today();
        }

        $unitsQuery = DB::table('business_units as bu')
            ->leftJoin('business_unit_sales as bus', function ($join) use ($startDate, $endDate) {
                $join->on('bus.business_unit_id', '=', 'bu.id')
                    ->whereBetween('bus.sale_date', [
                        $startDate->toDateString(),
                        $endDate->toDateString(),
                    ]);
            })
            ->leftJoin(
                'financial_transactions as ft',
                'ft.id',
                '=',
                'bus.financial_transaction_id'
            )
            ->select(
                'bu.id',
                'bu.code',
                'bu.name',
                'bu.is_active'
            )
            ->selectRaw('COUNT(bus.id) as transaction_count')
            ->selectRaw('COALESCE(SUM(bus.amount), 0) as total_sales')
            ->selectRaw('
                COALESCE(SUM(
                    CASE WHEN bus.id IS NOT NULL AND ft.id IS NULL
                    THEN bus.amount ELSE 0 END
                ), 0) as unlinked_amount
            ')
            ->selectRaw("
                COALESCE(SUM(
                    CASE WHEN ft.type = 'income' AND ft.status = 'draft'
                    THEN bus.amount ELSE 0 END
                ), 0) as pending_amount
            ")
            ->selectRaw("
                COALESCE(SUM(
                    CASE WHEN ft.type = 'income' AND ft.status = 'approved'
                    THEN bus.amount ELSE 0 END
                ), 0) as approved_amount
            ")
            ->selectRaw("
                COALESCE(SUM(
                    CASE WHEN ft.type = 'income' AND ft.status = 'rejected'
                    THEN bus.amount ELSE 0 END
                ), 0) as rejected_amount
            ")
            ->selectRaw("
                COALESCE(SUM(
                    CASE WHEN ft.id IS NOT NULL
                        AND (
                            ft.type != 'income'
                            OR ft.status NOT IN ('draft', 'approved', 'rejected')
                        )
                    THEN bus.amount ELSE 0 END
                ), 0) as unmatched_amount
            ")
            ->when(
                $filters['business_unit_id'] ?? null,
                fn ($query, $unitId) => $query->where('bu.id', $unitId)
            )
            ->groupBy('bu.id', 'bu.code', 'bu.name', 'bu.is_active')
            ->orderBy('bu.name');

        $units = $unitsQuery->get();

        $totals = [
            'transactions' => 0,
            'sales' => 0,
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
            'unlinked' => 0,
            'unmatched' => 0,
        ];

        foreach ($units as $unit) {
            $totals['transactions'] += (int) $unit->transaction_count;
            $totals['sales'] += (float) $unit->total_sales;
            $totals['approved'] += (float) $unit->approved_amount;
            $totals['pending'] += (float) $unit->pending_amount;
            $totals['rejected'] += (float) $unit->rejected_amount;
            $totals['unlinked'] += (float) $unit->unlinked_amount;
            $totals['unmatched'] += (float) $unit->unmatched_amount;
        }

        $businessUnits = BusinessUnit::orderBy('name')->get();

        return view('business-unit-sales.report', compact(
            'units',
            'businessUnits',
            'referenceDate',
            'startDate',
            'endDate',
            'totals',
            'filters'
        ));
    }
}
