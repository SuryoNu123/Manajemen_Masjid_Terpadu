<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use Illuminate\View\View;

class TransparencyController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Hanya laporan yang sudah dipublikasikan
        |--------------------------------------------------------------------------
        |
        | Data draft, submitted, approved, atau rejected
        | tidak ditampilkan kepada publik.
        |
        */

        $latestMonthlyReport = FinancialReport::query()
            ->where('status', 'published')
            ->where('period_type', 'monthly')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->first();

        $latestYearlyReport = FinancialReport::query()
            ->where('status', 'published')
            ->where('period_type', 'yearly')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Riwayat laporan yang sudah dipublikasikan
        |--------------------------------------------------------------------------
        */

        $publishedReports = FinancialReport::query()
            ->where('status', 'published')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->paginate(12);

        /*
        |--------------------------------------------------------------------------
        | Ringkasan
        |--------------------------------------------------------------------------
        |
        | Untuk halaman utama transparansi, gunakan laporan bulanan
        | paling baru jika tersedia.
        |
        */

        $summaryReport =
            $latestMonthlyReport
            ?? $latestYearlyReport;

        return view(
            'transparency.index',
            compact(
                'latestMonthlyReport',
                'latestYearlyReport',
                'publishedReports',
                'summaryReport'
            )
        );
    }
}
