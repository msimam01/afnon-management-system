<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsHelper
{
    public static function send($to, $message, $sender = null)
    {
        $apiKey = env('TERMII_API_KEY');
        $baseUrl = 'https://v3.api.termii.com/api/sms/send';

        // Convert number to international format
        $to = preg_replace('/^0/', '234', $to);

        // If no custom sender, use .env sender ID
        if (empty($sender)) {
            $sender = env('TERMII_SENDER_ID', 'Termii');
        }

        // 1️⃣ First attempt with custom sender
        $response = Http::post($baseUrl, [
            'to'      => $to,
            'from'    => $sender,
            'sms'     => $message,
            'type'    => 'plain',
            'channel' => 'generic',
            'api_key' => $apiKey
        ]);

        $result = $response->json();
        Log::info("Termii first attempt response", $result ?? []);

        // 2️⃣ If sender not approved, retry with Termii default sender
        if (
            isset($result['message']) &&
            str_contains(strtolower($result['message']), 'sender id not approved')
        ) {
            Log::warning("Sender ID not approved, retrying with Termii default sender");

            $fallbackResponse = Http::post($baseUrl, [
                'to'      => $to,
                'from'    => 'Termii', // fallback
                'sms'     => $message,
                'type'    => 'plain',
                'channel' => 'generic',
                'api_key' => $apiKey
            ]);

            $fallbackResult = $fallbackResponse->json();
            Log::info("Termii fallback response", $fallbackResult ?? []);

            return $fallbackResponse->successful();
        }

        return $response->successful();
    }
}
