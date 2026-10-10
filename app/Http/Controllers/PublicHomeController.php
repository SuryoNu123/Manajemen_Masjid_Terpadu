<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PrayerSchedule;
use Carbon\Carbon;

class PublicHomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $schedules = PrayerSchedule::whereDate('schedule_date', $today)
            ->where('is_active', true)
            ->orderBy('prayer_time')
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

        return view('welcome', compact(
            'schedules',
            'nextPrayer',
            'prayerNames',
            'activities'
        ));
    }
}