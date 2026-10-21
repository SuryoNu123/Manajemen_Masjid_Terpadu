<?php

namespace App\Jobs;

use App\Models\WhatsAppDelivery;
use App\Services\WhatsApp\WhatsAppProviderManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendWhatsAppDelivery implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $deliveryId) {}

    public function uniqueId(): string
    {
        return (string) $this->deliveryId;
    }

    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(WhatsAppProviderManager $providers, WhatsAppNotificationService $messages): void
    {
        $delivery = WhatsAppDelivery::with(['announcement', 'jamaah'])->findOrFail($this->deliveryId);
        if ($delivery->status === 'sent') {
            return;
        }

        if (! $delivery->jamaah?->whatsapp_opt_in
            || $delivery->jamaah->status !== 'aktif'
            || $messages->normalizeIndonesianPhone((string) $delivery->jamaah->phone) !== $delivery->recipient_phone) {
            $delivery->update(['status' => 'cancelled_opt_out']);

            return;
        }

        if (! $providers->isConfigured()) {
            $delivery->update(['status' => 'not_configured']);

            return;
        }

        $delivery->update(['status' => 'sending', 'failure_reason' => null]);
        try {
            $result = $providers->driver()->send(
                $delivery->recipient_phone,
                $messages->messageFor($delivery->announcement),
                'whatsapp-delivery-'.$delivery->id
            );
            $delivery->update([
                'status' => 'sent',
                'provider_reference' => $result->providerReference,
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $delivery->update([
                'status' => 'retrying',
                'failure_reason' => 'Provider delivery failed: '.$exception::class,
            ]);
            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        WhatsAppDelivery::whereKey($this->deliveryId)->update([
            'status' => 'failed',
            'failure_reason' => $exception ? 'Provider delivery failed: '.$exception::class : 'Provider delivery failed after retries.',
        ]);
    }
}
