<?php

namespace App\Services\WhatsApp;

use RuntimeException;

class WhatsAppProviderManager
{
    public function isConfigured(): bool
    {
        return config('services.whatsapp.mode') === 'provider'
            && is_string(config('services.whatsapp.adapter'))
            && config('services.whatsapp.adapter') !== '';
    }

    public function driver(): WhatsAppProvider
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('WhatsApp provider is not configured.');
        }

        $provider = app(config('services.whatsapp.adapter'));
        if (! $provider instanceof WhatsAppProvider) {
            throw new RuntimeException('Configured WhatsApp adapter is invalid.');
        }

        return $provider;
    }
}
