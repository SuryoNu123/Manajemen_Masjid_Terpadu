<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\User;
use App\Notifications\ActivityReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendActivityReminders extends Command
{
    protected $signature = 'activities:send-reminders';

    protected $description = 'Mengirim pengingat kegiatan kepada jamaah dan pengurus';

    public function handle(): int
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Ambil kegiatan yang dimulai sekitar 1 jam dari sekarang
        |--------------------------------------------------------------------------
        */

        $startWindow = $now->copy()->addMinutes(59);
        $endWindow = $now->copy()->addMinutes(61);

        $activities = Activity::where('is_public', true)
            ->where('status', 'scheduled')
            ->whereRaw(
                "TIMESTAMP(activity_date, start_time) BETWEEN ? AND ?",
                [
                    $startWindow->format('Y-m-d H:i:s'),
                    $endWindow->format('Y-m-d H:i:s'),
                ]
            )
            ->get();

        if ($activities->isEmpty()) {

            $this->info('Tidak ada kegiatan yang membutuhkan pengingat.');

            return self::SUCCESS;
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil semua pengguna yang terdaftar
        |--------------------------------------------------------------------------
        */

        $users = User::with('role')->get();

        foreach ($activities as $activity) {

            foreach ($users as $user) {

                /*
                | Mencegah notifikasi yang sama dikirim berkali-kali.
                */

                $alreadyNotified = $user->notifications()
                    ->where('type', ActivityReminderNotification::class)
                    ->whereJsonContains(
                        'data->activity_id',
                        $activity->id
                    )
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                $user->notify(
                    new ActivityReminderNotification($activity)
                );
            }

            $this->info(
                "Pengingat dikirim untuk: {$activity->title}"
            );
        }

        return self::SUCCESS;
    }
}