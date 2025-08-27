<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.flutterwave.com/v3';
        $this->secretKey = config('flutterwave.secretKey');
    }

    /**
     * Initialize a payment using Flutterwave Standard (multiple methods supported)
     */
    public function initiatePayment($amount, $customerName, $customerEmail, $customerPhone, $txRef, $redirectUrl, $paymentOptions = null)
    {
        $payload = [
            "tx_ref"      => $txRef,
            "amount"      => '500000',
            // "amount"      => $amount,
            "currency"    => "NGN",
            "redirect_url"=> $redirectUrl,
            "customer"    => [
                "email"       => $customerEmail,
                "phonenumber" => $customerPhone,
                "name"        => $customerName,
            ],
            "customizations" => [
                "title"       => "Monetary Return Payment",
                "description" => "Payment for Commodity Return by {$customerName}",
            ],
        ];

        if ($paymentOptions) {
            $payload["payment_options"] = $paymentOptions;
        }

        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $payload);
$response = Http::withToken($this->secretKey)
    ->post("{$this->baseUrl}/payments", $payload);

// Log the JSON so you see Flutterwave's actual reply
Log::info('Flutterwave payload', $payload);
Log::info('Flutterwave response', $response->json());

if ($response->failed()) {
    return [
        "error"   => true,
        "status"  => $response->status(),
        "body"    => $response->json(),
    ];
}

return $response->json(); // Contains data.link


    }

    public function verifyPayment($txRef)
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/verify_by_reference?tx_ref={$txRef}");

        return $response->json();
    }
}
