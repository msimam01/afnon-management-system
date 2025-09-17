<?php

namespace App\Services;

use App\Services\FlutterwaveService;
use App\Services\PaystackService;
use InvalidArgumentException;

class PaymentServiceFactory
{
    /**
     * Create a payment service instance
     */
    public static function create(string $provider): PaymentServiceInterface
    {
        switch (strtolower($provider)) {
            case 'flutterwave':
                return new FlutterwaveService();

            case 'paystack':
                return new PaystackService();

            default:
                throw new InvalidArgumentException("Unsupported payment provider: {$provider}");
        }
    }

    /**
     * Get all available payment providers
     */
    public static function getAvailableProviders(): array
    {
        return [
            'flutterwave' => [
                'name' => 'Flutterwave',
                'description' => 'Pay with card, bank transfer, USSD, and more',
                'icon' => 'fas fa-credit-card',
                'enabled' => true
            ],
            'paystack' => [
                'name' => 'Paystack',
                'description' => 'Pay with card, bank transfer, USSD, and mobile money',
                'icon' => 'fas fa-university',
                'enabled' => true
            ]
        ];
    }

    /**
     * Get enabled payment providers only
     */
    public static function getEnabledProviders(): array
    {
        return array_filter(self::getAvailableProviders(), function($provider) {
            return $provider['enabled'];
        });
    }
}
