<?php

namespace App\Services\WhatsApp;

class WhatsAppSendResult
{
    public function __construct(public readonly string $providerReference) {}
}
