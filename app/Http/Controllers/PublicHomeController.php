<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\DakwahContent;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use App\Models\PrayerSchedule;
use Carbon\Carbon;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $schedules = PrayerSchedule::whereDate('schedule_date', $today)
            ->where('is_active', true)
            ->orderByRaw("
                CASE prayer_name
                    WHEN 'subuh' THEN 1
                    WHEN 'dzuhur' THEN 2
                    WHEN 'ashar' THEN 3
                    WHEN 'maghrib' THEN 4
                    WHEN 'isya' THEN 5
                    ELSE 6
                END
            ")
            ->get();

        $now = Carbon::now()->format('H:i:s');

        $nextSchedule = $schedules
            ->filter(function ($schedule) use ($now) {
                return $schedule->prayer_time >= $now;
            })
            ->first();

        $prayerNames = [
            'subuh' => 'Subuh',
            'dzuhur' => 'Dzuhur',
            'ashar' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya',
        ];

        $nextPrayer = $nextSchedule
            ? [
                'name' => $prayerNames[$nextSchedule->prayer_name] ?? ucfirst($nextSchedule->prayer_name),
                'time' => Carbon::parse($nextSchedule->prayer_time)->format('H:i'),
            ]
            : null;

        $activities = Activity::where('is_public', true)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereDate('activity_date', '>=', $today)
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->take(4)
            ->get();

        // Pengumuman Darurat & Pengumuman Terbaru
        $emergencyAnnouncements = Announcement::published()
            ->emergency()
            ->latest('published_at')
            ->get();

        $latestAnnouncements = Announcement::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        // Artikel Dakwah Terbaru
        $latestArticles = DakwahContent::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        // Data Transparansi Keuangan Real
        $publishedReport = FinancialReport::where('status', 'published')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        if ($publishedReport) {
            $totalIncome = (float) $publishedReport->total_income;
            $totalExpense = (float) $publishedReport->total_expense;
            $balance = (float) $publishedReport->balance;
            $reportPeriodLabel = ($publishedReport->period_type === 'monthly' && $publishedReport->month)
                ? 'Laporan Bulan '.Carbon::create()->month($publishedReport->month)->translatedFormat('F').' '.$publishedReport->year
                : 'Laporan Tahun '.$publishedReport->year;
        } else {
            $totalIncome = (float) FinancialTransaction::where('type', 'income')
                ->where('status', '!=', 'rejected')
                ->sum('amount');
            $totalExpense = (float) FinancialTransaction::where('type', 'expense')
                ->where('status', '!=', 'rejected')
                ->sum('amount');
            $balance = $totalIncome - $totalExpense;
            $reportPeriodLabel = 'Rekapitulasi Keuangan Berjalan';
        }

        return view('welcome', compact(
            'schedules',
            'nextPrayer',
            'prayerNames',
            'activities',
            'emergencyAnnouncements',
            'latestAnnouncements',
            'latestArticles',
            'totalIncome',
            'totalExpense',
            'balance',
            'reportPeriodLabel'
        ));
    }
}
