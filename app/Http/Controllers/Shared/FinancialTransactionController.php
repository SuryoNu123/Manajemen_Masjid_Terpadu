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
        $query = FinancialTransaction::with('category')
            ->latest('transaction_date')
            ->latest('id');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->end_date
            );
        }

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
