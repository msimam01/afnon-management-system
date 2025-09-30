<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsHelper
{
    public static function sendSms($to, $message)
    {

        $response = Http::withHeaders([
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
        ])->get('https://{base_url}.api.infobip.com/sms/1/reports');

        $logs = $response->json();
        dd($logs);

        try {
            $response = Http::withHeaders([
                'Authorization' => env('INFOBIP_API_KEY'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post(env('INFOBIP_BASE_URL') . '/sms/2/text/advanced', [
                'messages' => [
                    [
                        'from' => env('INFOBIP_SENDER', 'AFNEN'),
                        'destinations' => [
                            ['to' => $to]
                        ],
                        'text' => $message,
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Infobip SMS failed: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('SMS sending error: ' . $e->getMessage());
            return false;
        }
    }
}
