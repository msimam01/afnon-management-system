<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MonetaryReturn;
use App\Services\FlutterwaveService;
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
            'returnVerification'
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

        return view('farmer.payment.details', compact('application'));
    }

    /**
     * Initiate payment process
     */
    public function initiatePayment(Request $request, FlutterwaveService $flutterwave)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'farmer_phone' => 'required|string|max:15',
            'farmer_email' => 'nullable|email|max:255'
        ]);

        $application = Application::with([
            'farmer:id,full_name,phone,registration_number',
            'season:id,name',
            'monetaryReturn',
            'collectionVerification',
            'returnVerification'
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

            // Determine payment amount - use total_loan from application
            $paymentAmount = $application->total_loan;

            // Initialize payment with Flutterwave
            $response = $flutterwave->initiatePayment(
                $paymentAmount,
                $application->farmer->full_name,
                $customerEmail,
                $application->farmer->phone,
                $txRef,
                route('farmer.payment.callback'),
                'card,banktransfer,ussd,opay'
            );

            if (!empty($response['error'])) {
                throw new \Exception('Payment initiation failed: ' . json_encode($response['body']));
            }

            $paymentLink = $response['data']['link'];

            // Create or update monetary return
            if ($application->monetaryReturn) {
                $application->monetaryReturn->update([
                    'tx_ref' => $txRef,
                    'payment_link' => $paymentLink,
                    'status' => 'pending'
                ]);
            } else {
                // Create monetary return if it doesn't exist
                $application->monetaryReturn()->create([
                    'tx_ref' => $txRef,
                    'payment_link' => $paymentLink,
                    'amount' => $paymentAmount,
                    'status' => 'pending'
                ]);
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
    public function paymentCallback(Request $request, FlutterwaveService $flutterwave)
    {
        $txRef = $request->query('tx_ref') ?: $request->input('tx_ref');
        $status = $request->query('status');

        if (!$txRef) {
            ToastMagic::error('Invalid payment callback.');
            return redirect()->route('farmer.payment.index');
        }

        $monetaryReturn = MonetaryReturn::where('tx_ref', $txRef)->first();

        if (!$monetaryReturn) {
            ToastMagic::error('Payment record not found.');
            return redirect()->route('farmer.payment.index');
        }

        // Verify payment with Flutterwave
        $verification = $flutterwave->verifyPayment($txRef);

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
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
