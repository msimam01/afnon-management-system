<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaystackService implements PaymentServiceInterface
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('paystack.baseUrl');
        $this->secretKey = config('paystack.secretKey');
        $this->publicKey = config('paystack.publicKey');
    }

    /**
     * Initialize a payment using Paystack
     */
    public function initiatePayment($amount, $customerName, $customerEmail, $customerPhone, $txRef, $redirectUrl, $metadata = [])
    {
        // Validate required fields
        if (empty($this->secretKey)) {
            Log::error('Paystack secret key not configured');
            return [
                'error' => true,
                'status' => 500,
                'body' => ['message' => 'Paystack secret key not configured']
            ];
        }

        if (empty($customerEmail)) {
            Log::error('Customer email is required for Paystack payment');
            return [
                'error' => true,
                'status' => 400,
                'body' => ['message' => 'Customer email is required']
            ];
        }

        $payload = [
            'amount' => (int)($amount * 100), // Paystack expects amount in kobo (smallest currency unit)
            'email' => $customerEmail,
            'reference' => $txRef,
            'callback_url' => $redirectUrl,
            'currency' => 'NGN',
            'metadata' => array_merge([
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'payment_type' => 'monetary_return',
                'description' => "Payment for Commodity Return by {$customerName}",
            ], $metadata)
        ];

        // Log the request for debugging
        Log::info('Paystack payment initialization request', [
            'payload' => $payload,
            'base_url' => $this->baseUrl,
            'has_secret_key' => !empty($this->secretKey)
        ]);

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post("{$this->baseUrl}/transaction/initialize", $payload);

            // Log the response for debugging
            Log::info('Paystack payment initialization response', [
                'status_code' => $response->status(),
                'response' => $response->json(),
                'success' => $response->successful()
            ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                Log::error('Paystack API request failed', [
                    'status' => $response->status(),
                    'response' => $errorBody
                ]);

                return [
                    'error' => true,
                    'status' => $response->status(),
                    'body' => $errorBody,
                ];
            }

            $responseData = $response->json();

            if (!$responseData['status']) {
                Log::error('Paystack payment initialization failed', [
                    'response' => $responseData
                ]);

                return [
                    'error' => true,
                    'status' => 400,
                    'body' => $responseData,
                ];
            }

            // Validate required response fields
            if (empty($responseData['data']['authorization_url'])) {
                Log::error('Paystack response missing authorization_url', [
                    'response' => $responseData
                ]);

                return [
                    'error' => true,
                    'status' => 500,
                    'body' => ['message' => 'Invalid response from Paystack: missing authorization_url']
                ];
            }

            Log::info('Paystack payment initialization successful', [
                'reference' => $responseData['data']['reference'],
                'authorization_url' => $responseData['data']['authorization_url']
            ]);

            return [
                'error' => false,
                'status' => 'success',
                'data' => [
                    'link' => $responseData['data']['authorization_url'],
                    'reference' => $responseData['data']['reference'],
                    'access_code' => $responseData['data']['access_code'] ?? null
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Paystack payment initialization exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => true,
                'status' => 500,
                'body' => ['message' => 'Payment initialization failed: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Verify a payment using Paystack
     */
    public function verifyPayment($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json',
        ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

        // Log the verification request and response
        Log::info('Paystack payment verification request', ['reference' => $reference]);
        Log::info('Paystack payment verification response', $response->json());

        if ($response->failed()) {
            return [
                'status' => 'error',
                'message' => 'Payment verification failed',
                'data' => $response->json()
            ];
        }

        $responseData = $response->json();

        if (!$responseData['status']) {
            return [
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Payment verification failed',
                'data' => $responseData
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Payment verified successfully',
            'data' => $responseData['data']
        ];
    }

    /**
     * Get payment methods supported by Paystack
     */
    public function getSupportedPaymentMethods()
    {
        return [
            'card' => 'Card Payment',
            'bank' => 'Bank Transfer',
            'ussd' => 'USSD',
            'qr' => 'QR Code',
            'mobile_money' => 'Mobile Money'
        ];
    }

    /**
     * Get the public key for frontend integration
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Get provider name
     */
    public function getProviderName()
    {
        return 'Paystack';
    }
}
