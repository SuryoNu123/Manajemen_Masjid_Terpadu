<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use App\Services\WhatsApp\WhatsAppProviderManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsAppPreferenceController extends Controller
{
    public function edit(Request $request): View
    {
        return view('whatsapp-preferences.edit', [
            'jamaah' => $request->user()->jamaah,
            'configured' => app(WhatsAppProviderManager::class)->isConfigured(),
        ]);
    }

    public function update(Request $request, WhatsAppNotificationService $phones): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_opt_in' => ['sometimes', 'boolean'],
        ]);
        $jamaah = $request->user()->jamaah;
        if (! $jamaah) {
            return back()->withErrors(['whatsapp_opt_in' => 'Profil Jamaah belum ditautkan. Hubungi pengurus masjid.']);
        }

        $optedIn = $request->boolean('whatsapp_opt_in');
        if ($optedIn && (! $jamaah->phone || ! $phones->normalizeIndonesianPhone($jamaah->phone))) {
            return back()->withErrors(['whatsapp_opt_in' => 'Tambahkan nomor telepon Indonesia yang valid sebelum menyetujui pesan WhatsApp.']);
        }

        $changed = $jamaah->whatsapp_opt_in !== $optedIn;
        $jamaah->update([
            'whatsapp_opt_in' => $optedIn,
            'whatsapp_opt_in_at' => $optedIn ? ($jamaah->whatsapp_opt_in_at ?? now()) : null,
        ]);

        if ($changed) {
            ActivityLogger::log(
                $optedIn ? 'jamaah_whatsapp_opt_in' : 'jamaah_whatsapp_opt_out',
                'Preferensi notifikasi WhatsApp diperbarui untuk akun Jamaah.',
                $jamaah,
                $request->user()->id
            );
        }

        return back()->with('success', $optedIn
            ? 'Persetujuan pesan WhatsApp berhasil disimpan.'
            : 'Anda tidak akan menerima pengumuman WhatsApp lagi.');
    }
}
