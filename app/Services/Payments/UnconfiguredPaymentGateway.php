<?php

namespace App\Services\Payments;

use App\Models\Donation;
use Illuminate\Http\Request;
use RuntimeException;

class UnconfiguredPaymentGateway implements PaymentGatewayProvider
{
    public function createPayment(Donation $donation): PaymentIntent
    {
        throw new RuntimeException('Payment gateway is not configured.');
    }

    public function verifyWebhook(Request $request): VerifiedPaymentEvent
    {
        throw new RuntimeException('Payment gateway is not configured.');
    }
}
