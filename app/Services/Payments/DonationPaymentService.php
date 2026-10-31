<?php

namespace App\Services\Payments;

use App\Models\Donation;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\PaymentWebhookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DonationPaymentService
{
    public function recordOfflinePayment(Donation $donation, ?int $userId): void
    {
        if ($donation->payment_method === 'qris') {
            throw ValidationException::withMessages(['payment' => 'Pembayaran QRIS hanya dapat dikonfirmasi oleh payment gateway.']);
        }

        DB::transaction(function () use ($donation, $userId): void {
            $donation = Donation::whereKey($donation->id)->lockForUpdate()->firstOrFail();
            if ($donation->payment_status === 'paid') {
                return;
            }

            $this->markPaidAndRecord($donation, $userId);
        });
    }

    public function processVerifiedEvent(VerifiedPaymentEvent $event, string $payload): bool
    {
        if ($event->currency !== 'IDR'
            || ! in_array($event->status, ['pending', 'paid', 'failed', 'expired'], true)
            || trim($event->providerEventId) === ''
            || trim($event->gatewayReference) === ''
            || trim($event->donationCode) === ''
            || trim($event->eventType) === '') {
            throw ValidationException::withMessages(['payment' => 'Data callback pembayaran tidak memenuhi kontrak.']);
        }

        return DB::transaction(function () use ($event, $payload): bool {
            $inserted = PaymentWebhookEvent::query()->insertOrIgnore([
                'provider_event_id' => $event->providerEventId,
                'gateway_reference' => $event->gatewayReference,
                'event_type' => $event->eventType,
                'payload_hash' => hash('sha256', $payload),
                'processing_status' => 'received',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted === 0) {
                return false;
            }

            $donation = Donation::where('gateway_reference', $event->gatewayReference)
                ->where('donation_code', $event->donationCode)
                ->lockForUpdate()
                ->firstOrFail();

            if ($donation->payment_method !== 'qris') {
                throw ValidationException::withMessages(['payment' => 'Notifikasi Midtrans hanya dapat diproses untuk donasi QRIS.']);
            }

            $donationAmount = $this->normalizeAmount((string) $donation->amount);
            $eventAmount = $this->normalizeAmount($event->amount);
            if ($eventAmount === null || $donationAmount === null || $donationAmount !== $eventAmount) {
                throw ValidationException::withMessages(['payment' => 'Nominal callback tidak cocok dengan donasi.']);
            }

            if ($donation->payment_status === 'paid') {
                PaymentWebhookEvent::where('provider_event_id', $event->providerEventId)->update([
                    'processing_status' => 'ignored_terminal',
                    'processed_at' => now(),
                ]);

                return true;
            }

            if ($donation->payment_status !== 'pending' && $event->status !== 'paid') {
                PaymentWebhookEvent::where('provider_event_id', $event->providerEventId)->update([
                    'processing_status' => 'ignored_terminal',
                    'processed_at' => now(),
                ]);

                return true;
            }

            if ($event->status === 'paid') {
                $this->markPaidAndRecord($donation, null);
            } else {
                $donation->update(['payment_status' => $event->status]);
            }

            PaymentWebhookEvent::where('provider_event_id', $event->providerEventId)->update([
                'processing_status' => 'processed',
                'processed_at' => now(),
            ]);

            return true;
        });
    }

    private function markPaidAndRecord(Donation $donation, ?int $userId): void
    {
        if ($donation->financial_transaction_id !== null) {
            $donation->update(['payment_status' => 'paid', 'paid_at' => $donation->paid_at ?? now()]);

            return;
        }

        $categoryName = match ($donation->type) {
            'infaq' => 'Infaq',
            'sedekah' => 'Sedekah',
            'zakat' => 'Zakat',
            'qurban' => 'Qurban',
        };
        $category = FinancialCategory::where('name', $categoryName)->where('type', 'income')->firstOrFail();
        $transaction = FinancialTransaction::create([
            'category_id' => $category->id,
            'user_id' => $userId,
            'transaction_code' => 'DON-'.$donation->donation_code,
            'type' => 'income',
            'amount' => $donation->amount,
            'transaction_date' => today()->toDateString(),
            'source' => $donation->donor_name ?: 'Donasi Online',
            'description' => 'Donasi '.ucfirst($donation->type).' - '.$donation->donation_code,
            'status' => 'draft',
        ]);

        $donation->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'financial_transaction_id' => $transaction->id,
        ]);
    }

    private function normalizeAmount(string $amount): ?string
    {
        if (preg_match('/^(\d{1,13})(?:\.(\d{1,2}))?$/', $amount, $matches) !== 1) {
            return null;
        }

        return ltrim($matches[1], '0').'.'.str_pad($matches[2] ?? '', 2, '0');
    }
}
