<?php

namespace App\Services;

use App\Jobs\SendWhatsAppDelivery;
use App\Models\Announcement;
use App\Models\Jamaah;
use App\Models\WhatsAppDelivery;
use App\Services\WhatsApp\WhatsAppProviderManager;
use Illuminate\Support\Str;

class WhatsAppNotificationService
{
    public function sendAnnouncement(Announcement $announcement, WhatsAppProviderManager $providers): int
    {
        $recipients = Jamaah::query()
            ->where('status', 'aktif')
            ->where('whatsapp_opt_in', true)
            ->whereNotNull('phone')
            ->get(['id', 'phone']);
        $messageCanBeSent = $providers->isConfigured();
        $created = 0;

        foreach ($recipients as $recipient) {
            $phone = $this->normalizeIndonesianPhone($recipient->phone);
            $mode = config('services.whatsapp.mode');
            $status = $phone === null
                ? 'invalid_number'
                : ($mode === 'simulated' ? 'simulated' : ($messageCanBeSent ? 'pending' : 'not_configured'));
            $delivery = WhatsAppDelivery::firstOrCreate(
                ['announcement_id' => $announcement->id, 'jamaah_id' => $recipient->id],
                ['recipient_phone' => $phone ?? $recipient->phone, 'status' => $status]
            );

            if ($delivery->wasRecentlyCreated) {
                $created++;
            }

            if ($delivery->wasRecentlyCreated && $status === 'pending') {
                SendWhatsAppDelivery::dispatch($delivery->id);
            }
        }

        return $created;
    }

    public function messageFor(Announcement $announcement): string
    {
        $header = $announcement->type === 'emergency'
            ? 'PENGUMUMAN DARURAT MASJID TERPADU'
            : 'PENGUMUMAN MASJID TERPADU';

        return $header."\n\n"
            .'Judul: '.$announcement->title."\n"
            .'Isi: '.Str::limit(trim(strip_tags($announcement->content)), 2000)."\n\n"
            .'Informasi: '.route('announcements.show', $announcement, absolute: true);
    }

    public function normalizeIndonesianPhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return preg_match('/^628[0-9]{8,11}$/', $digits) === 1 ? '+'.$digits : null;
    }
}
