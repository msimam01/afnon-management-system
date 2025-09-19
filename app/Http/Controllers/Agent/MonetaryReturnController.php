<?php

namespace App\Http\Controllers\Agent;

use App\Models\Season;
use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MonetaryReturn;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Services\FlutterwaveService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Http;

class MonetaryReturnController extends Controller
{
    public function index(Request $request)
{
    $seasons = Season::all();

    $query = Application::with(['farmer', 'commodity_allocations', 'season'])
        ->whereHas('collectionVerification', function ($q) {})
        ->whereDoesntHave('returnVerification'); // Exclude applications in return verifications

    if ($request->season) {
        $query->whereHas('season', fn($q) => $q->where('slug', $request->season));
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->filter) {
        $filter = $request->filter;
        $query->whereHas(
            'farmer',
            fn($q) =>
            $q->where('full_name', 'like', "%$filter%")
                ->orWhere('registration_number', 'like', "%$filter%")
        );
    }

    $applications = $query->latest()->paginate(15);

    return view('agent.monetary-return', compact('applications', 'seasons'));
}
    public function generatePayment(Application $application, FlutterwaveService $flutterwave)
{
    // 🚫 Prevent duplicate payments
    if ($application->payment_status === 'paid') {
        ToastMagic::error('This application has already been paid.');
        return back()->with('error', 'This application has already been paid.');
    }

    // 🚫 If there's already a pending payment, redirect to it instead of creating another
    if ($application->monetaryReturn && $application->monetaryReturn->status === 'pending') {
        ToastMagic::info('You already have a pending payment for this application.');
        return redirect($application->monetaryReturn->payment_link)
            ->with('info', 'You already have a pending payment for this application.');
    }

    $txRef = 'APP-' . $application->id . '-' . time();

    $customerEmail = $application->farmer->email
        ?? 'farmer' . $application->farmer->id . '@afnon.com';

    $response = $flutterwave->initiatePayment(
        $application->disbursed_amount,
        $application->farmer->full_name,
        $customerEmail,
        $application->farmer->phone ?? '08000000000',
        $txRef,
        route('agent.payment.callback'),
        paymentOptions: 'card,banktransfer,ussd,opay'
    );

    if (!empty($response['error'])) {
        ToastMagic::error('Payment initiation failed.');
        return back()->with('error', 'Payment initiation failed: ' . json_encode($response['body']));
    }

    $link = $response['data']['link'];

    $application->monetaryReturn()->create([
        'tx_ref'       => $txRef,
        'payment_link' => $link,
        'amount'       => $application->disbursed_amount,
        'status'       => 'pending',
    ]);

    return redirect($link);
}




    public function paymentCallback(Request $request, FlutterwaveService $fw)
    {
        $txRef = $request->query('tx_ref');
        $status = $request->query('status');

        $verification = $fw->verifyPayment($txRef);

        $return = \App\Models\MonetaryReturn::where('tx_ref', $txRef)->first();

        if (!$return) {
            ToastMagic::error('No matching payment record found.');
            return redirect()->route('agent.monetary-return')->with('error', 'No matching payment record found.');
        }

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
            // ✅ Update MonetaryReturn
            $return->update(['status' => 'paid']);

            // ✅ Update Application
            $return->application->update(['payment_status' => 'paid']);

            ToastMagic::success('Payment successful!');
            return redirect()->route('agent.monetary-return')->with('success', 'Payment successful!');
        }
        ToastMagic::error('Payment failed or cancelled.');
        return redirect()->route('agent.monetary-return')->with('error', 'Payment failed or cancelled.');
    }




    public function downloadInvoice($id)
    {
        $return = MonetaryReturn::with('application.farmer', 'application.commodities', 'application.farm')->findOrFail($id);

        $pdf = Pdf::loadView('agent.invoice-pdf', compact('return'));

        return $pdf->download('Invoice-' . $return->invoice_number . '.pdf');
    }


    public function show($uuid)
    {
        $return = MonetaryReturn::where('uuid', $uuid)->firstOrFail();
        $return->load(['application.farmer', 'application.commodity_allocations', 'application.season']);

        return view('agent.monetary-returns.show', compact('return'));
    }

    public function receipt($uuid)
    {
        $return = MonetaryReturn::where('uuid', $uuid)->firstOrFail();
        $return->load(['application.farmer', 'application.commodity_allocations', 'application.season']);

        $pdf = Pdf::loadView('agent.monetary-returns.receipt', compact('return'));

        return $pdf->download('receipt-' . $return->tx_ref . '.pdf');
    }
}
