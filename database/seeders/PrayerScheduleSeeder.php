<?php

namespace Database\Seeders;

use App\Models\PrayerSchedule;
use Illuminate\Database\Seeder;

class PrayerScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $date = now()->toDateString();

        $schedules = [
            [
                'prayer_name' => 'subuh',
                'prayer_time' => '04:30',
            ],
            [
                'prayer_name' => 'dzuhur',
                'prayer_time' => '12:00',
            ],
            [
                'prayer_name' => 'ashar',
                'prayer_time' => '15:15',
            ],
            [
                'prayer_name' => 'maghrib',
                'prayer_time' => '18:00',
            ],
            [
                'prayer_name' => 'isya',
                'prayer_time' => '19:15',
            ],
        ];

        foreach ($schedules as $schedule) {

            PrayerSchedule::updateOrCreate(
                [
                    'schedule_date' => $date,
                    'prayer_name' => $schedule['prayer_name'],
                ],
                [
                    'prayer_time' => $schedule['prayer_time'],
                    'source' => 'manual',
                    'is_active' => true,
                ]
            );
        }
    }
}
