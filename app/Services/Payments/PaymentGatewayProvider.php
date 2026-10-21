<?php

namespace App\Services\Payments;

use App\Models\Donation;
use Illuminate\Http\Request;

interface PaymentGatewayProvider
{
    public function createPayment(Donation $donation): PaymentIntent;

    public function verifyWebhook(Request $request): VerifiedPaymentEvent;
}
