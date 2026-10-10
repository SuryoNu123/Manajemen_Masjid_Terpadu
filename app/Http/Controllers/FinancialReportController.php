<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateFinancialReportRequest;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    /**
     * Daftar laporan keuangan.
     */
    public function index(): View
    {
        $reports = FinancialReport::with([
            'submittedBy',
            'reviewedBy',
        ])
            ->latest('year')
            ->latest('month')
            ->latest('id')
            ->paginate(10);

        return view(
            'financial-reports.index',
            compact('reports')
        );
    }


    /**
     * Form generate laporan.
     */
    public function create(): View
    {
        return view('financial-reports.create');
    }


    /**
     * Generate / membuat laporan otomatis.
     */
    public function generate(
        GenerateFinancialReportRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $periodType = $data['period_type'];
        $month = $periodType === 'monthly'
            ? $data['month']
            : null;

        $year = $data['year'];


        /*
        |--------------------------------------------------------------------------
        | Cegah duplikasi laporan
        |--------------------------------------------------------------------------
        */

        $existing = FinancialReport::query()
            ->where('period_type', $periodType)
            ->where('year', $year)
            ->when(
                $periodType === 'monthly',
                fn ($query) => $query->where('month', $month)
            )
            ->when(
                $periodType === 'yearly',
                fn ($query) => $query->whereNull('month')
            )
            ->first();


        if ($existing) {
            return back()
                ->withInput()
                ->withErrors([
                    'period_type' =>
                        'Laporan untuk periode tersebut sudah tersedia.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil transaksi sesuai periode
        |--------------------------------------------------------------------------
        */

        $transactions = FinancialTransaction::query()
            ->where('status', '!=', 'rejected')
            ->when(
                $periodType === 'monthly',
                function ($query) use ($year, $month) {

                    $query
                        ->whereYear(
                            'transaction_date',
                            $year
                        )
                        ->whereMonth(
                            'transaction_date',
                            $month
                        );

                }
            )
            ->when(
                $periodType === 'yearly',
                function ($query) use ($year) {

                    $query->whereYear(
                        'transaction_date',
                        $year
                    );

                }
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Hitung total
        |--------------------------------------------------------------------------
        */

        $totalIncome = $transactions
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = $transactions
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;


        /*
        |--------------------------------------------------------------------------
        | Simpan laporan
        |--------------------------------------------------------------------------
        */

        $report = FinancialReport::create([
            'period_type' => $periodType,
            'month' => $month,
            'year' => $year,

            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,

            'status' => 'draft',

            'submitted_by' => Auth::id(),
        ]);


        return redirect()
            ->route(
                'laporan-keuangan.index'
            )
            ->with(
                'success',
                'Laporan keuangan berhasil dibuat.'
            );
    }


    /**
     * Submit laporan ke Ketua.
     */
    public function submit(
        FinancialReport $laporan
    ): RedirectResponse {

        if ($laporan->status !== 'draft') {

            return back()->with(
                'error',
                'Hanya laporan draft yang dapat dikirim.'
            );
        }

        $laporan->update([
            'status' => 'submitted',
            'submitted_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Laporan berhasil dikirim kepada Ketua DKM.'
        );
    }


    /**
     * Ketua menyetujui laporan.
     */
    public function approve(
        FinancialReport $laporan
    ): RedirectResponse {

        if ($laporan->status !== 'submitted') {

            return back()->with(
                'error',
                'Hanya laporan yang sudah disubmit yang dapat disetujui.'
            );
        }

        $laporan->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Laporan keuangan berhasil disetujui.'
        );
    }


    /**
     * Ketua menolak laporan.
     */
    public function reject(
        FinancialReport $laporan
    ): RedirectResponse {

        if ($laporan->status !== 'submitted') {

            return back()->with(
                'error',
                'Hanya laporan submitted yang dapat ditolak.'
            );
        }

        $laporan->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Laporan keuangan ditolak.'
        );
    }


    /**
     * Publikasikan laporan.
     *
     * Hanya laporan approved yang dapat dipublikasikan.
     */
    public function publish(
        FinancialReport $laporan
    ): RedirectResponse {

        if ($laporan->status !== 'approved') {

            return back()->with(
                'error',
                'Laporan harus disetujui Ketua sebelum dipublikasikan.'
            );
        }

        $laporan->update([
            'status' => 'published',
        ]);

        return back()->with(
            'success',
            'Laporan berhasil dipublikasikan.'
        );
    }
}