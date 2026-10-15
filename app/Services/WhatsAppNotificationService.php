<?php

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Kirim notifikasi pengumuman via WhatsApp (atau log saat gateway belum dikonfigurasi).
     */
    public function sendAnnouncement(Announcement $announcement, ?string $targetPhone = null): bool
    {
        $header = $announcement->type === 'emergency'
            ? "⚠️ *PENGUMUMAN DARURAT MASJID TERPADU* ⚠️\n"
            : "📢 *PENGUMUMAN MASJID TERPADU*\n";

        $message = $header."\n"
            ."*Judul:* {$announcement->title}\n"
            .'*Isi:* '.strip_tags($announcement->content)."\n\n"
            .'Waktu: '.($announcement->published_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'))."\n"
            .'Informasi selengkapnya: '.url('/pengumuman/'.$announcement->id);

        // Simulasi atau integrasi gateway WhatsApp pihak ketiga
        Log::channel('single')->info("WhatsApp Notification Dispatched for Announcement [{$announcement->id}]:", [
            'type' => $announcement->type,
            'title' => $announcement->title,
            'message' => $message,
            'target' => $targetPhone ?? 'Broadcast Jamaah',
        ]);

        return true;
    }
}
