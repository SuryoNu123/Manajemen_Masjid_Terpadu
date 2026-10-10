<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /**
         * Memberi tahu Intelephense bahwa user adalah
         * App\Models\User.
         */
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $role = $user->role?->name;

        /*
        |--------------------------------------------------------------------------
        | Data Keuangan
        |--------------------------------------------------------------------------
        |
        | Transaksi yang berstatus rejected tidak dihitung.
        | Draft tetap dihitung karena belum ada workflow approval
        | transaksi individual. Approval nanti dilakukan pada laporan.
        |
        */

        $totalIncome = FinancialTransaction::where(
            'type',
            'income'
        )
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        $totalExpense = FinancialTransaction::where(
            'type',
            'expense'
        )
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;


        /*
        |--------------------------------------------------------------------------
        | Bulan Ini
        |--------------------------------------------------------------------------
        */

        $monthlyIncome = FinancialTransaction::where(
            'type',
            'income'
        )
            ->where('status', '!=', 'rejected')
            ->whereYear(
                'transaction_date',
                now()->year
            )
            ->whereMonth(
                'transaction_date',
                now()->month
            )
            ->sum('amount');

        $monthlyExpense = FinancialTransaction::where(
            'type',
            'expense'
        )
            ->where('status', '!=', 'rejected')
            ->whereYear(
                'transaction_date',
                now()->year
            )
            ->whereMonth(
                'transaction_date',
                now()->month
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Donasi
        |--------------------------------------------------------------------------
        */

        $totalDonation = Donation::where(
            'payment_status',
            'paid'
        )->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Laporan
        |--------------------------------------------------------------------------
        */

        $pendingReports = FinancialReport::whereIn(
            'status',
            [
                'draft',
                'submitted',
            ]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Transaksi Terbaru
        |--------------------------------------------------------------------------
        */

        $latestTransactions = FinancialTransaction::with(
            'category'
        )
            ->latest('transaction_date')
            ->latest('id')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard berdasarkan Role
        |--------------------------------------------------------------------------
        */

        return match ($role) {

            'ketua' => view(
                'dashboard.ketua',
                compact(
                    'totalIncome',
                    'totalExpense',
                    'balance',
                    'monthlyIncome',
                    'monthlyExpense',
                    'totalDonation',
                    'pendingReports',
                    'latestTransactions'
                )
            ),

            'bendahara' => view(
                'dashboard.bendahara',
                compact(
                    'totalIncome',
                    'totalExpense',
                    'balance',
                    'monthlyIncome',
                    'monthlyExpense',
                    'totalDonation',
                    'pendingReports',
                    'latestTransactions'
                )
            ),

            'sekretaris' => view(
                'dashboard.sekretaris'
            ),

            'marbot' => view(
                'dashboard.marbot'
            ),

            'jamaah' => view(
                'dashboard.jamaah'
            ),

            default => abort(
                403,
                'Role pengguna tidak dikenali.'
            ),
        };
    }
}