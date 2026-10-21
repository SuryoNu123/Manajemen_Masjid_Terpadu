<?php

namespace App\Services\Payments;

use Carbon\CarbonInterface;
use InvalidArgumentException;

class PaymentIntent
{
    public function __construct(
        public readonly string $reference,
        public readonly ?string $paymentUrl = null,
        public readonly ?string $qrCodeUrl = null,
        public readonly ?CarbonInterface $expiresAt = null,
    ) {
        if (trim($reference) === '') {
            throw new InvalidArgumentException('Payment provider returned an empty payment reference.');
        }

        foreach ([$paymentUrl, $qrCodeUrl] as $url) {
            if ($url !== null && (! filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https')) {
                throw new InvalidArgumentException('Payment provider returned an invalid payment URL.');
            }
        }
    }
}
