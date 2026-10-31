<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Announcement;
use App\Models\FinancialReport;
use App\Models\FinancialTransaction;
use App\Models\PrayerSchedule;
use Carbon\Carbon;
use Illuminate\View\View;

class DigitalSignageController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        // Jadwal Sholat Hari Ini
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
        $nextSchedule = $schedules->first(fn ($s) => $s->prayer_time >= $now);

        $prayerNames = [
            'subuh' => 'Subuh',
            'dzuhur' => 'Dzuhur',
            'ashar' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya',
        ];

        // Running text dari pengumuman aktif
        $announcements = Announcement::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        $runningTexts = [];
        foreach ($announcements as $a) {
            $prefix = $a->type === 'emergency' ? '[DARURAT] ' : '';
            $runningTexts[] = $prefix.$a->title.': '.Str($a->content)->limit(120);
        }

        if (empty($runningTexts)) {
            $runningTexts[] = 'Selamat Datang di Masjid Terpadu • Lurus dan Rapatkan Shaf • Matikan/Senyapkan Nada Dering Telepon Seluler Anda Saat Berada di Dalam Masjid.';
        }

        // Kajian & Agenda Ibadah Mendatang
        $activities = Activity::where('is_public', true)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereDate('activity_date', '>=', $today)
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Ringkasan Keuangan
        $publishedReport = FinancialReport::where('status', 'published')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        if ($publishedReport) {
            $totalIncome = (float) $publishedReport->total_income;
            $totalExpense = (float) $publishedReport->total_expense;
            $balance = (float) $publishedReport->balance;
        } else {
            $totalIncome = (float) FinancialTransaction::where('type', 'income')
                ->where('status', '!=', 'rejected')
                ->sum('amount');
            $totalExpense = (float) FinancialTransaction::where('type', 'expense')
                ->where('status', '!=', 'rejected')
                ->sum('amount');
            $balance = $totalIncome - $totalExpense;
        }

        return view('digital-signage.index', compact(
            'schedules',
            'prayerNames',
            'nextSchedule',
            'runningTexts',
            'activities',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }
}
