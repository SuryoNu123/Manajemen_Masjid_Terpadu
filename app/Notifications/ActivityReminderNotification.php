<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Activity $activity
    ) {
    }

    /**
     * Channel notifikasi.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'title' => $this->activity->title,
            'type' => $this->activity->type,
            'activity_date' => $this->activity->activity_date?->format('Y-m-d'),
            'start_time' => $this->activity->start_time,
            'end_time' => $this->activity->end_time,
            'location' => $this->activity->location,
            'message' => 'Pengingat kegiatan: ' . $this->activity->title,
        ];
    }
}