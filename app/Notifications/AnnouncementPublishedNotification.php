<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Announcement $announcement
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'type' => $this->announcement->type,
            'is_emergency' => $this->announcement->type === 'emergency',
            'message' => ($this->announcement->type === 'emergency' ? '[DARURAT] ' : 'Pengumuman Baru: ').$this->announcement->title,
            'url' => route('announcements.show', $this->announcement),
        ];
    }
}
