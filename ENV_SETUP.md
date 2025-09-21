# Environment Variables Setup for Payment Integration

## Paystack Configuration

Add the following environment variables to your `.env` file:

```env
# Paystack Configuration
PAYSTACK_PUBLIC_KEY=pk_test_7535725a46ce856d82e5610ebe54963afc9d0dba
PAYSTACK_SECRET_KEY=sk_test_3f8ac7c196182528b32f9c024945292cb6e870fd
PAYSTACK_BASE_URL=https://api.paystack.co
```

## Flutterwave Configuration (if not already set)

```env
# Flutterwave Configuration
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
FLUTTERWAVE_BASE_URL=https://api.flutterwave.com/v3
```

## After adding the environment variables:

1. Clear the configuration cache:
   ```bash
   php artisan config:clear
   ```

2. Test the payment integration:
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   $paystack = new App\Services\PaystackService();
   echo $paystack->getPublicKey();
   ```

## Payment Provider Status

- ✅ Flutterwave: Implemented and working
- ✅ Paystack: Implemented and ready for testing
- ⏳ OPay: Ready for implementation (placeholder created)










