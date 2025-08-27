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
        $agent = Auth::guard('tenant')->user()->agent;
        $filter = $request->get('filter');
        $season = $request->get('season');
        $status = $request->get('status');
        $seasons = Season::where('status', 'open')->get();

        $appsQuery = Application::with([
                'farmer:id,full_name,registration_number,phone,bvn,nin,address',
                'farm:id,size,location',
                'season:id,name,status',
                'commodity_allocations',
                'applicationCenter',
                'collectionVerification'
            ])
            ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
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

        $agent = Auth::guard('tenant')->user()->agent;
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
    $agent = Auth::guard('tenant')->user()->agent;
    $filter = $request->get('filter');
    $season = $request->get('season');
    $status = $request->get('status');

    $apps = Application::with([
            'farmer:id,full_name,registration_number,phone,bvn,nin,address',
            'farm:id,size,location',
            'season:id,name,status',
            'applicationCenter',
            'commodity_allocations',
            'collectionVerification' // eager load for checking collection
        ])
        ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
        ->whereHas('applicationCenter', fn($q) => $q->where('return_center_id', $agent->center_id))
        ->whereHas('collectionVerification') // <--- Only include if farmer collected commodity
        ->whereDoesntHave('monetaryReturn')
        ->when($filter, fn($q) => $q->whereHas('farmer', fn($f) =>
            $f->where('full_name', 'like', "%$filter%")
              ->orWhere('registration_number', 'like', "%$filter%")
        ))
        ->when($season, fn($q) => $q->whereHas('season', fn($s) => $s->where('slug', $season)))
        ->get()
        ->map(function ($app) {
            $app->return_status = $app->returnVerification()->exists() ? 'verified' : 'pending';
            return $app;
        })
        ->filter(fn($app) => !$status || $app->return_status === $status)
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

        $rules = [
            'application_id' => 'required|exists:applications,id',
            'idCard' => 'nullable|image|max:2048',
        ];
        $rules['returnedCommodityPhoto'] = 'required|image|max:4096';

        $request->validate($rules);

        $agent = Auth::guard('tenant')->user()->agent;
        $application = Application::findOrFail($request->application_id);

        if ($application->applicationCenter->return_center_id !== $agent->center_id) {
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        $data = [
            'application_id' => $application->id,
            'agent_id' => $agent->id,
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

        ToastMagic::success('Return submitted successfully');
        return response()->json(['message' => 'Return submitted successfully']);
    }
}
