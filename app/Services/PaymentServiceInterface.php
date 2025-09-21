<?php

namespace App\Services;

interface PaymentServiceInterface
{
    /**
     * Initialize a payment
     */
    public function initiatePayment($amount, $customerName, $customerEmail, $customerPhone, $txRef, $redirectUrl, $options = []);

    /**
     * Verify a payment
     */
    public function verifyPayment($reference);

    /**
     * Get supported payment methods
     */
    public function getSupportedPaymentMethods();

    /**
     * Get provider name
     */
    public function getProviderName();
}










