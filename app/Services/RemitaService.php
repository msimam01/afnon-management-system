<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RemitaService
{
    protected $baseUrl;
    protected $merchantId;
    protected $apiKey;
    protected $serviceTypeId;

    public function __construct()
    {
        $this->baseUrl = 'https://remitademo.net/remita/exapp/api/v1/send/api'; // First-gen sandbox base URL
        $this->merchantId = '2547916'; // Sandbox Merchant ID for invoice generation
        $this->apiKey = '1946';        // Sandbox API Key
        $this->serviceTypeId = '4430731'; // Sandbox Service Type ID
    }

    /**
     * Generate an invoice using first-generation sandbox API
     */
   public function generateInvoice($amount, $orderId, $payerName, $payerEmail, $payerPhone)
{
    $payload = [
        'merchantId' => $this->merchantId,
        'serviceTypeId' => $this->serviceTypeId,
        'orderId' => $orderId,
        'invoiceAmount' => $amount,
        'payerName' => $payerName,
        'payerEmail' => $payerEmail,
        'payerPhone' => $payerPhone,
    ];

    $response = Http::asForm()->withHeaders([
        'Authorization' => "remitaConsumerKey={$this->merchantId}, remitaConsumerToken={$this->apiKey}"
    ])->post("{$this->baseUrl}/invoice", $payload);

    if ($response->failed()) {
        return [
            'error' => true,
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }

    return $response->json();
}

    /**
     * Verify payment using RRR
     */
    public function verifyPayment($rrr)
    {
        $hash = hash('sha512', $rrr . $this->apiKey . $this->merchantId);

        $response = Http::get("{$this->baseUrl}/transaction/verify/{$rrr}/$hash");

        return $response->json();
    }
}
