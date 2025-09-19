# OPay Integration Setup Instructions

## Current Status
OPay has been integrated into the payment system but is currently experiencing authentication issues. The integration is complete but needs proper API credentials to work.

## What's Working
✅ OPay service implementation  
✅ Payment factory integration  
✅ Controller callback handling  
✅ UI integration (temporarily disabled)  

## What Needs to be Fixed
❌ API authentication (Error: 10096 - Merchant authentication failed)  
❌ Correct API endpoint verification  
❌ Credential validation  

## Steps to Enable OPay

### 1. Verify API Credentials
The current credentials provided:
- **Merchant ID**: 256625081821035
- **Public Key**: OPAYPUB17574120010010.8912394980334613
- **Secret Key**: OPAYPRV17574120010010.30679051257574896

### 2. Check with OPay Support
Contact OPay support to verify:
- Are these credentials active?
- What is the correct API endpoint?
- Is there a sandbox environment for testing?

### 3. Test Different Endpoints
Try these API endpoints:
- `https://api.opayweb.com` (Production)
- `https://sandboxapi.opaycheckout.com` (Sandbox)
- `https://cashierapi.opayweb.com` (Current)

### 4. Enable OPay
Once authentication is working, enable OPay by changing in `app/Services/PaymentServiceFactory.php`:

```php
'opay' => [
    'name' => 'OPay',
    'description' => 'Pay with OPay wallet, card, bank transfer, USSD, and QR code',
    'icon' => 'fas fa-mobile-alt',
    'enabled' => true // Change from false to true
]
```

## Current Payment Options
Farmers can currently use:
1. **Flutterwave** ✅ (Working)
2. **Paystack** ✅ (Working)  
3. **OPay** ⚠️ (Ready but needs authentication fix)

## Testing
To test OPay once enabled:
1. Visit payment page
2. Select OPay as payment method
3. Complete payment flow

The system will automatically handle OPay callbacks and verification once the API authentication is resolved.







