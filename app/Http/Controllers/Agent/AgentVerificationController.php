<?php

namespace App\Http\Controllers\Agent;

use App\Models\Season;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ReturnVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionVerification;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class AgentVerificationController extends Controller
{
    public function assignedFarmers(Request $request)
    {
        $agent = Auth::user()->agent;
        $filter = $request->get('filter');
        $season = $request->get('season');
        $status = $request->get('status');
        $seasons = Season::where('status', 'open')->get();

        $appsQuery = Application::with(['farmer', 'farm', 'season', 'commodity_allocations', 'applicationCenter', 'collectionVerification'])
            ->whereHas('applicationCenter', fn($q) => $q->where('collection_center_id', $agent->center_id))
            ->where('status', 'approved')
            ->when($filter, fn($q) => $q->whereHas('farmer', fn($f) =>
                $f->where('full_name', 'like', "%$filter%")
                    ->orWhere('registration_number', 'like', "%$filter%")))
            ->when($season, fn($q) => $q->whereHas('season', fn($s) => $s->where('slug', $season)))
            ->get()
            ->map(function ($app) use ($status) {
                $app->collection_status = $app->collectionVerification()->exists() ? 'verified' : 'pending';
                return $app;
            })
            ->filter(function ($app) use ($status) {
                return !$status || $app->collection_status === $status;
            });

        // Paginate the collection manually for demonstration purposes
        $perPage = 3;
        $page = $request->get('page', 1);
        $pagedApps = $appsQuery->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedResult = [
            'data' => $pagedApps,
            'current_page' => $page,
            'last_page' => (int) ceil($appsQuery->count() / $perPage),
            'total' => $appsQuery->count(),
        ];

        return $request->ajax()
            ? response()->json($paginatedResult)
            : view('agent.verify-collection', compact('seasons'));
    }

    public function storeCollection(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'idCard' => 'required|image|max:2048',
            'commodityPhoto' => 'required|image|max:4096',
        ]);

        $agent = Auth::user()->agent;
        $application = Application::findOrFail($request->application_id);

        if ($application->applicationCenter->collection_center_id !== $agent->center_id) {
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        $idCardPath = $request->file('idCard')->store('collections/idcards', 'public');
        $commodityPath = $request->file('commodityPhoto')->store('collections/commodities', 'public');

        CollectionVerification::create([
            'application_id' => $application->id,
            'agent_id' => $agent->id,
            'id_card_photo' => $idCardPath,
            'commodity_photo' => $commodityPath,
        ]);
        ToastMagic::success('Collection submitted successfully');
        return response()->json(['message' => 'Collection submitted successfully']);
    }

    public function assignedReturns(Request $request)
    {
        $agent = Auth::user()->agent;
        $filter = $request->get('filter');
        $season = $request->get('season');
        $status = $request->get('status');

        $apps = Application::with(['farmer', 'farm', 'season', 'applicationCenter', 'commodity_allocations'])
            ->whereHas('applicationCenter', fn($q) => $q->where('return_center_id', $agent->center_id))
            ->when($filter, function ($q) use ($filter) {
                $q->whereHas(
                    'farmer',
                    fn($f) =>
                    $f->where('full_name', 'like', "%$filter%")
                        ->orWhere('registration_number', 'like', "%$filter%")
                );
            })
            ->when(
                $season,
                fn($q) =>
                $q->whereHas('season', fn($s) => $s->where('slug', $season))
            )
            ->get()
            ->map(function ($app) {
                $app->return_status = $app->returnVerification()->exists() ? 'verified' : 'pending';
                return $app;
            })
            ->filter(function ($app) use ($status) {
                return !$status || $app->return_status === $status;
            })
            ->values();

        if ($request->ajax()) {
            $perPage = 10;
            $page = $request->get('page', 1);
            $paged = $apps->slice(($page - 1) * $perPage, $perPage)->values();
            return response()->json([
                'data' => $paged,
                'current_page' => $page,
                'last_page' => ceil($apps->count() / $perPage),
                'total' => $apps->count(),
            ]);
        }

        $seasons = Season::all();
        return view('agent.verify-return', compact('seasons'));
    }

    public function storeReturn(Request $request)
    {
        $isMonetary = $request->has('cashPayment') && $request->get('cashPayment') > 0;
        
        $rules = [
            'application_id' => 'required|exists:applications,id',
            'idCard' => 'nullable|image|max:2048',
        ];

        if ($isMonetary) {
            $rules['cashPayment'] = 'required|numeric|min:0';
            $rules['paymentReceipt'] = 'required|image|max:4096';
            $rules['returnedCommodityPhoto'] = 'nullable';
        } else {
            $rules['returnedCommodityPhoto'] = 'required|image|max:4096';
            $rules['cashPayment'] = 'nullable';
            $rules['paymentReceipt'] = 'nullable';
        }

        $request->validate($rules);

        $agent = Auth::user()->agent;
        $application = Application::findOrFail($request->application_id);

        if ($application->applicationCenter->return_center_id !== $agent->center_id) {
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        $data = [
            'application_id' => $application->id,
            'agent_id' => $agent->id,
            'cash_payment' => $request->cashPayment,
            'invoice_path' => null, // Initialize invoice path
        ];

        if ($request->hasFile('idCard')) {
            $data['id_card_photo'] = $request->file('idCard')->store('returns/idcards', 'public');
        }
        if ($request->hasFile('returnedCommodityPhoto')) {
            $data['returned_commodity_photo'] = $request->file('returnedCommodityPhoto')->store('returns/commodities', 'public');
        }
        if ($request->hasFile('paymentReceipt')) {
            $data['payment_receipt'] = $request->file('paymentReceipt')->store('returns/receipts', 'public');
        }

        $returnVerification = ReturnVerification::create($data);

        if ($isMonetary) {
            // Generate and save the PDF invoice
            $invoiceData = [
                'invoice_number' => 'INV-' . Str::uuid(),
                'date' => now()->format('Y-m-d'),
                'farmer' => $application->farmer,
                'application' => $application,
                'amount' => $request->cashPayment,
                'agent' => $agent,
            ];

            $pdf = PDF::loadView('invoices.monetary_return', $invoiceData);
            $invoicePath = 'invoices/returns/' . $invoiceData['invoice_number'] . '.pdf';
            Storage::disk('public')->put($invoicePath, $pdf->output());

            $returnVerification->update(['invoice_path' => $invoicePath]);
        }
        
        ToastMagic::success('Return submitted successfully');
        return response()->json(['message' => 'Return submitted successfully']);
    }
}