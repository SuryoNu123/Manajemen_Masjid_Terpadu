<?php

namespace App\Services\Payments;

class VerifiedPaymentEvent
{
    public function __construct(
        public readonly string $providerEventId,
        public readonly string $gatewayReference,
        public readonly string $donationCode,
        public readonly string $amount,
        public readonly string $currency,
        public readonly string $status,
        public readonly string $eventType,
    ) {}
}
