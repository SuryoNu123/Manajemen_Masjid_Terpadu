<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinancialTransactionRequest;
use App\Http\Requests\UpdateFinancialTransactionRequest;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FinancialTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:income,expense'],
            'category_id' => ['nullable', 'integer', 'exists:financial_categories,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $query = FinancialTransaction::with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('transaction_code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->when($filters['category_id'] ?? null, fn ($query, int $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['start_date'] ?? null, fn ($query, string $date) => $query->whereDate('transaction_date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, string $date) => $query->whereDate('transaction_date', '<=', $date));

        $transactions = $query->paginate(10)->withQueryString();

        $categories = FinancialCategory::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $totalIncome = (clone $query)
            ->where('type', 'income')
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        $totalExpense = (clone $query)
            ->where('type', 'expense')
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        return view('financial-transactions.index', compact(
            'transactions',
            'categories',
            'totalIncome',
            'totalExpense'
        ));
    }

    public function create(Request $request): View
    {
        $categories = FinancialCategory::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $type = $request->get('type', 'income');

        return view(
            'financial-transactions.create',
            compact('categories', 'type')
        );
    }

    public function store(
        StoreFinancialTransactionRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $category = FinancialCategory::findOrFail(
            $validated['category_id']
        );

        if ($category->type !== $validated['type']) {
            return back()
                ->withInput()
                ->withErrors([
                    'category_id' => 'Kategori tidak sesuai dengan jenis transaksi.',
                ]);
        }

        $evidencePath = null;

        if ($request->hasFile('evidence')) {
            $evidencePath = $request
                ->file('evidence')
                ->store('financial-evidence', 'public');
        }

        $transaction = FinancialTransaction::create([
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'transaction_code' => $this->generateTransactionCode(),
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'source' => $validated['source'] ?? null,
            'description' => $validated['description'] ?? null,
            'evidence_path' => $evidencePath,

            // Transaksi baru menunggu approval.
            'status' => 'draft',
        ]);

        return redirect()
            ->route('transaksi.index')
            ->with(
                'success',
                "Transaksi {$transaction->transaction_code} berhasil ditambahkan."
            );
    }

    public function edit(
        FinancialTransaction $transaksi
    ): View {
        $categories = FinancialCategory::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'financial-transactions.edit',
            compact('transaksi', 'categories')
        );
    }

    public function update(
        UpdateFinancialTransactionRequest $request,
        FinancialTransaction $transaksi
    ): RedirectResponse {
        $validated = $request->validated();

        $category = FinancialCategory::findOrFail(
            $validated['category_id']
        );

        if ($category->type !== $validated['type']) {
            return back()
                ->withInput()
                ->withErrors([
                    'category_id' => 'Kategori tidak sesuai dengan jenis transaksi.',
                ]);
        }

        if ($request->hasFile('evidence')) {

            if (
                $transaksi->evidence_path
                && Storage::disk('public')->exists(
                    $transaksi->evidence_path
                )
            ) {
                Storage::disk('public')->delete(
                    $transaksi->evidence_path
                );
            }

            $transaksi->evidence_path = $request
                ->file('evidence')
                ->store('financial-evidence', 'public');
        }

        $transaksi->update([
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'source' => $validated['source'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('transaksi.index')
            ->with(
                'success',
                'Transaksi berhasil diperbarui.'
            );
    }

    public function destroy(
        FinancialTransaction $transaksi
    ): RedirectResponse {
        if (
            $transaksi->evidence_path
            && Storage::disk('public')->exists(
                $transaksi->evidence_path
            )
        ) {
            Storage::disk('public')->delete(
                $transaksi->evidence_path
            );
        }

        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with(
                'success',
                'Transaksi berhasil dihapus.'
            );
    }

    private function generateTransactionCode(): string
    {
        do {
            $code =
                'TRX-'.
                now()->format('YmdHis').
                '-'.
                random_int(100, 999);

        } while (
            FinancialTransaction::where(
                'transaction_code',
                $code
            )->exists()
        );

        return $code;
    }
}
