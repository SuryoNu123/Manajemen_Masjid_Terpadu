<?php

namespace App\Services\Payments;

use RuntimeException;

class PaymentGatewayManager
{
    public function isConfigured(): bool
    {
        return is_string(config('services.payment_gateway.adapter'))
            && config('services.payment_gateway.adapter') !== '';
    }

    public function driver(): PaymentGatewayProvider
    {
        $adapter = config('services.payment_gateway.adapter');

        if (! is_string($adapter) || $adapter === '') {
            throw new RuntimeException('Payment gateway provider is not configured.');
        }

        $provider = app($adapter);
        if (! $provider instanceof PaymentGatewayProvider) {
            throw new RuntimeException('Configured payment gateway adapter is invalid.');
        }

        return $provider;
    }
}
