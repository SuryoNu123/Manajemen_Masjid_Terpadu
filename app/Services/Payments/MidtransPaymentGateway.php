<?php

namespace App\Services\Payments;

use App\Models\Donation;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MidtransPaymentGateway implements PaymentGatewayProvider
{
    public function createPayment(Donation $donation): PaymentIntent
    {
        $this->configureSdk();

        Config::$paymentIdempotencyKey = $donation->donation_code;
        Config::$curlOptions = [];
        Config::$overrideNotifUrl = config('services.midtrans.notification_url') ?: null;

        $customerDetails = array_filter([
            'first_name' => $donation->donor_name,
            'email' => $donation->donor_email,
            'phone' => $donation->donor_phone,
        ], fn (?string $value): bool => $value !== null && $value !== '');

        $response = CoreApi::charge([
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $donation->donation_code,
                'gross_amount' => (int) $donation->amount,
            ],
            'item_details' => [[
                'id' => $donation->donation_code,
                'price' => (int) $donation->amount,
                'quantity' => 1,
                'name' => 'Donasi '.ucfirst($donation->type),
            ]],
            'customer_details' => $customerDetails,
            'qris' => [
                'acquirer' => config('services.midtrans.qris_acquirer', 'gopay'),
            ],
            'custom_expiry' => [
                'order_time' => now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
                'expiry_duration' => (int) config('services.midtrans.qris_expiry_minutes', 15),
                'unit' => 'minute',
            ],
        ]);

        $qrCodeUrl = collect($response->actions ?? [])
            ->first(fn (object $action): bool => ($action->name ?? null) === 'generate-qr-code-v2')?->url;

        if (! is_string($response->transaction_id ?? null) || ! is_string($qrCodeUrl)) {
            throw new RuntimeException('Midtrans did not return a transaction reference and QR code URL.');
        }

        return new PaymentIntent(
            reference: $response->transaction_id,
            qrCodeUrl: $qrCodeUrl,
            expiresAt: isset($response->expiry_time) ? CarbonImmutable::parse($response->expiry_time) : null,
        );
    }

    public function verifyWebhook(Request $request): VerifiedPaymentEvent
    {
        $payload = $request->json()->all();
        $serverKey = config('services.midtrans.server_key');

        if (! is_string($serverKey) || $serverKey === '') {
            throw new RuntimeException('Midtrans Server Key is not configured.');
        }

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signature = $payload['signature_key'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (! is_string($orderId) || ! is_string($statusCode) || ! is_string($grossAmount)
            || ! is_string($signature) || ! is_string($transactionId)) {
            throw new UnauthorizedHttpException('Midtrans', 'Invalid payment notification.');
        }

        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        if (! hash_equals($expectedSignature, $signature)) {
            throw new UnauthorizedHttpException('Midtrans', 'Invalid payment notification signature.');
        }

        if (($payload['payment_type'] ?? null) !== 'qris') {
            throw new UnauthorizedHttpException('Midtrans', 'Unexpected payment method.');
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $status = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'pending',
            'pending' => 'pending',
            'expire' => 'expired',
            'cancel', 'deny' => 'failed',
            default => throw new UnauthorizedHttpException('Midtrans', 'Unsupported transaction status.'),
        };

        if ($fraudStatus === 'challenge') {
            $status = 'pending';
        }

        $eventType = implode(':', [
            (string) $statusCode,
            (string) $transactionStatus,
            (string) ($fraudStatus ?? ''),
        ]);

        return new VerifiedPaymentEvent(
            providerEventId: implode(':', [$transactionId, $eventType]),
            gatewayReference: $transactionId,
            donationCode: $orderId,
            amount: $grossAmount,
            currency: $payload['currency'] ?? 'IDR',
            status: $status,
            eventType: $eventType,
        );
    }

    private function configureSdk(): void
    {
        $serverKey = config('services.midtrans.server_key');
        if (! is_string($serverKey) || $serverKey === '') {
            throw new RuntimeException('Midtrans Server Key is not configured.');
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
    }
}
