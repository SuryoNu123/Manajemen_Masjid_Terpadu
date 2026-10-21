<?php

namespace App\Services\WhatsApp;

interface WhatsAppProvider
{
    public function send(string $recipient, string $message, string $idempotencyKey): WhatsAppSendResult;
}
