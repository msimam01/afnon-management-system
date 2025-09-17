<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MonetaryReturn;
use App\Services\FlutterwaveService;
use App\Services\PaystackService;
use App\Services\PaymentServiceFactory;
use App\Services\FarmerPaymentCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class FarmerPaymentController extends Controller
{
    /**
     * Show the farmer payment portal landing page
     */
    public function index()
    {
        return view('farmer.payment.index');
    }

    /**
     * Look up application by reference number
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:50'
        ]);

        $referenceNumber = strtoupper(trim($request->reference_number));

        // Find application with all necessary relationships
        $application = Application::with([
            'farmer:id,full_name,phone,registration_number',
            'season:id,name,return_deadline',
            'monetaryReturn',
            'collectionVerification',
            'returnVerification',
            'commodity_allocations'
        ])
        ->where('reference_number', $referenceNumber)
        ->where('status', 'approved') // Only approved applications can make payments
        ->first();

        if (!$application) {
            ToastMagic::error('Application not found or not approved for payment. Please check your reference number.');
            return back()->withInput();
        }

        // Check if application has been collected (must have approved collection verification)
        if (!$application->collectionVerification) {
            ToastMagic::error('This application has not been collected yet. Payment is only required after commodity collection.');
            return back()->withInput();
        }

        // Check if farmer has already returned the commodity (approved return verification)
        if ($application->returnVerification && $application->returnVerification->status === 'approved') {
            ToastMagic::info('You have already returned the commodity for this application. No payment is required.');
            return view('farmer.payment.already-paid', compact('application'));
        }

        // Check if payment is already completed
        if ($application->monetaryReturn && $application->monetaryReturn->status === 'paid') {
            ToastMagic::info('Payment for this application has already been completed.');
            return view('farmer.payment.already-paid', compact('application'));
        }

        // Calculate payment details for display
        $paymentCalculation = FarmerPaymentCalculationService::calculateTotalPaymentAmount($application);

        // Get available payment providers
        $paymentProviders = PaymentServiceFactory::getEnabledProviders();

        return view('farmer.payment.details', compact('application', 'paymentCalculation', 'paymentProviders'));
    }

    /**
     * Initiate payment process
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'farmer_phone' => 'required|string|max:15',
            'farmer_email' => 'nullable|email|max:255',
            'payment_provider' => 'required|string|in:flutterwave,paystack'
        ]);

        $application = Application::with([
            'farmer:id,full_name,phone,registration_number',
            'season:id,name',
            'monetaryReturn',
            'collectionVerification',
            'returnVerification',
            'commodity_allocations'
        ])->findOrFail($request->application_id);

        // Verify the phone number matches
        if ($application->farmer->phone !== $request->farmer_phone) {
            ToastMagic::error('Phone number does not match our records.');
            return back()->withInput();
        }

        // Re-validate payment eligibility (in case someone bypassed the lookup)
        if (!$application->collectionVerification ) {
            ToastMagic::error('This application has not been collected yet. Payment is only required after commodity collection.');
            return redirect()->route('farmer.payment.index');
        }

        // Check if farmer has already returned the commodity
        if ($application->returnVerification && $application->returnVerification->status === 'approved') {
            ToastMagic::error('You have already returned the commodity for this application. No payment is required.');
            return redirect()->route('farmer.payment.index');
        }

        // Check if payment is already completed
        if ($application->monetaryReturn && $application->monetaryReturn->status === 'paid') {
            ToastMagic::error('Payment for this application has already been completed.');
            return redirect()->route('farmer.payment.index');
        }

        // Check if there's already a pending payment
        if ($application->monetaryReturn && $application->monetaryReturn->status === 'pending') {
            ToastMagic::info('You already have a pending payment for this application.');
            return redirect($application->monetaryReturn->payment_link);
        }

        DB::beginTransaction();
        try {
            // Generate unique transaction reference
            $txRef = 'FARMER-' . $application->id . '-' . time();

            $customerEmail = $request->farmer_email ?: $application->farmer->phone . '@afnon.com';

            // Calculate payment amount based on commodity allocations with current market prices
            $paymentCalculation = FarmerPaymentCalculationService::calculateTotalPaymentAmount($application);
            $paymentAmount = $paymentCalculation['total_amount'];

            // Validate payment calculation
            $validation = FarmerPaymentCalculationService::validatePaymentCalculation($application);
            if (!$validation['is_valid']) {
                $errorMessage = 'Payment calculation error: ' . implode(', ', $validation['issues']);
                Log::error('Farmer payment calculation failed', [
                    'application_id' => $application->id,
                    'issues' => $validation['issues'],
                    'calculation' => $paymentCalculation
                ]);
                throw new \Exception($errorMessage);
            }

            // Get the selected payment provider
            $paymentProvider = $request->payment_provider;
            $paymentService = PaymentServiceFactory::create($paymentProvider);

            // Initialize payment with selected provider
            $metadata = [];
            if ($paymentProvider === 'flutterwave') {
                $metadata = 'card,banktransfer,ussd,opay';
            }

            $response = $paymentService->initiatePayment(
                $paymentAmount,
                $application->farmer->full_name,
                $customerEmail,
                $application->farmer->phone,
                $txRef,
                route('farmer.payment.callback', ['provider' => $paymentProvider]),
                $metadata
            );

            if (isset($response['error']) && $response['error'] !== 0 && $response['error'] !== false) {
                $errorMessage = 'Payment initiation failed';
                if (isset($response['body']['message'])) {
                    $errorMessage .= ': ' . $response['body']['message'];
                } else {
                    $errorMessage .= ': ' . json_encode($response['body']);
                }

                // Special handling for OPay authentication issues
                if ($paymentProvider === 'opay' && isset($response['body']['code']) && $response['body']['code'] === '10096') {
                    $errorMessage = 'OPay payment is temporarily unavailable. Please try Flutterwave or Paystack instead.';
                }

                Log::error('Payment initiation failed', [
                    'provider' => $paymentProvider,
                    'application_id' => $application->id,
                    'response' => $response
                ]);

                throw new \Exception($errorMessage);
            }

            // Handle different response formats from different payment providers
            $paymentLink = '';
            $orderNo = null;

            if ($paymentProvider === 'opay') {
                $paymentLink = $response['body']['data']['authorization_url'];
                $orderNo = $response['body']['data']['access_code'];
            } else {
            $paymentLink = $response['data']['link'];
            }

            // Create or update monetary return
            if ($application->monetaryReturn) {
                $updateData = [
                    'tx_ref' => $txRef,
                    'payment_link' => $paymentLink,
                    'status' => 'pending'
                ];

                if ($orderNo) {
                    $updateData['order_no'] = $orderNo;
                }

                $application->monetaryReturn->update($updateData);
            } else {
                // Create monetary return if it doesn't exist
                $createData = [
                    'tx_ref' => $txRef,
                    'payment_link' => $paymentLink,
                    'amount' => $paymentAmount,
                    'status' => 'pending',
                    'calculation_method' => $paymentCalculation['calculation_method'],
                    'calculation_details' => json_encode($paymentCalculation),
                    'payment_provider' => $paymentProvider
                ];

                if ($orderNo) {
                    $createData['order_no'] = $orderNo;
                }

                $application->monetaryReturn()->create($createData);
            }

            DB::commit();

            // Redirect to payment gateway
            return redirect($paymentLink);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Farmer payment initialization failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);

            ToastMagic::error('Failed to initialize payment. Please try again.');
            return back();
        }
    }

    /**
     * Handle payment callback from gateway
     */
    public function paymentCallback(Request $request)
    {
        // Log all incoming parameters for debugging
        Log::info('Payment callback received', [
            'all_params' => $request->all(),
            'query_params' => $request->query(),
            'input_params' => $request->input(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'method' => $request->method()
        ]);

        $txRef = $request->query('tx_ref') ?: $request->input('tx_ref');
        $reference = $request->query('reference') ?: $request->input('reference');
        $status = $request->query('status');
        $provider = $request->query('provider') ?: 'flutterwave'; // Default to flutterwave for backward compatibility

        // Use the appropriate reference based on provider
        $referenceToUse = $provider === 'paystack' ? $reference : $txRef;

        if (!$referenceToUse) {
            ToastMagic::error('Invalid payment callback.');
            return redirect()->route('farmer.payment.index');
        }

        $monetaryReturn = MonetaryReturn::where('tx_ref', $referenceToUse)->first();

        if (!$monetaryReturn) {
            ToastMagic::error('Payment record not found.');
            return redirect()->route('farmer.payment.index');
        }

        // Get the payment service and verify payment
        $paymentService = PaymentServiceFactory::create($provider);
        $verification = $paymentService->verifyPayment($referenceToUse);

        // Log verification result for debugging
        Log::info('Payment verification result', [
            'provider' => $provider,
            'reference' => $referenceToUse,
            'verification' => $verification
        ]);

        // Check verification status based on provider
        $isPaymentSuccessful = false;
        if ($provider === 'flutterwave') {
            $isPaymentSuccessful = $verification['status'] === 'success' && $verification['data']['status'] === 'successful';
        } elseif ($provider === 'paystack') {
            $isPaymentSuccessful = $verification['status'] === 'success' && $verification['data']['status'] === 'success';
        }

        if ($isPaymentSuccessful) {
            DB::beginTransaction();
            try {
                // Update monetary return status
                $monetaryReturn->update([
                    'status' => 'paid',
                    'verified_at' => now()
                ]);

                // Update application payment status
                $monetaryReturn->application->update([
                    'payment_status' => 'paid'
                ]);

                DB::commit();

                ToastMagic::success('Payment completed successfully!');
                return view('farmer.payment.success', [
                    'application' => $monetaryReturn->application->load('farmer', 'season'),
                    'monetaryReturn' => $monetaryReturn
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Farmer payment verification update failed', [
                    'tx_ref' => $txRef,
                    'error' => $e->getMessage()
                ]);

                ToastMagic::error('Payment verification failed. Please contact support.');
                return redirect()->route('farmer.payment.index');
            }
        } else {
            Log::warning('Farmer payment verification failed', [
                'tx_ref' => $txRef,
                'verification_response' => $verification
            ]);

            ToastMagic::error('Payment verification failed. Please try again or contact support.');
            return view('farmer.payment.failed', [
                'application' => $monetaryReturn->application->load('farmer', 'season'),
                'error' => $verification['message'] ?? 'Payment verification failed'
            ]);
        }
    }

    /**
     * Show payment receipt
     */
    public function receipt($txRef)
    {
        $monetaryReturn = MonetaryReturn::with([
            'application.farmer',
            'application.season'
        ])
        ->where('tx_ref', $txRef)
        ->where('status', 'paid')
        ->firstOrFail();

        return view('farmer.payment.receipt', compact('monetaryReturn'));
    }

}
