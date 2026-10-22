<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Services\Payments\DonationPaymentService;
use App\Services\Payments\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentWebhookController extends Controller
{
    public function __invoke(Request $request, PaymentGatewayManager $gateways, DonationPaymentService $payments): Response
    {
        abort_unless($gateways->isConfigured(), 503, 'Payment gateway is not configured.');

        $event = $gateways->driver()->verifyWebhook($request);
        $processed = $payments->processVerifiedEvent($event, $request->getContent());

        Log::info('Payment gateway callback processed.', [
            'provider_event_id' => $event->providerEventId,
            'donation_code' => $event->donationCode,
            'status' => $event->status,
            'duplicate' => ! $processed,
        ]);

        return response()->noContent();
    }
}
