<?php

namespace App\Services;

interface SmsServiceInterface
{
    public function sendMessage(string $from, string $to, string $messageText): void;
}
