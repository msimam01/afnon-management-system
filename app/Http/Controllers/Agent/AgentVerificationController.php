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
use App\Models\CommodityMarketPrice;

class AgentVerificationController extends Controller
{
    public function assignedFarmers(Request $request)
    {
        $agent = optional(Auth::guard('tenant')->user())->agent;
        $filter = $request->get('filter');
        $season = $request->get('season');
        $status = $request->get('status');
        $seasons = Season::where('status', 'open')->get();

        // If agent profile or center is not set yet, avoid querying by center and show empty state
        if (!$agent || empty($agent->center_id)) {
            if ($request->ajax()) {
                return response()->json([
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0,
                ]);
            }
            return view('agent.verify-collection', compact('seasons'))
                ->with('agentSetupRequired', true);
        }

        $appsQuery = Application::with([
                'farmer:id,full_name,registration_number,phone,bvn,nin,address',
                'farm:id,size,location',
                'season:id,name,status,loan_type',
                'commodity_allocations',
                'applicationCenter',
                'collectionVerification',
                'monetaryReturn'
            ])
            ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'payment_status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
            ->whereHas('applicationCenter', fn($q) => $q->where('collection_center_id', $agent->center_id))
            ->where('status', 'approved')
            ->when($filter, fn($q) => $q->whereHas('farmer', fn($f) =>
                $f->where('full_name', 'like', "%$filter%")
                    ->orWhere('registration_number', 'like', "%$filter%"))
                    ->orWhere('reference_number', 'like', "%$filter%"))
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
            'photo' => 'required|image|max:4096',
        ]);

        $agent = optional(Auth::guard('tenant')->user())->agent;
        $application = Application::with(['season:id,loan_type', 'monetaryReturn'])->findOrFail($request->application_id);

        if (!$agent || empty($agent->center_id) || $application->applicationCenter->collection_center_id !== $agent->center_id) {
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        // Enforce payment before collection for co-funded seasons
        if ($application->season && $application->season->loan_type === 'co-funded') {
            if (($application->monetaryReturn?->status ?? null) !== 'paid' && ($application->payment_status ?? null) !== 'paid') {
                ToastMagic::error('Payment required before collection for co-funded seasons.');
                return response()->json(['message' => 'Payment required before collection'], 422);
            }
        }

        try {
            $photoPath = $request->file('photo')->store('collections/photos', 'public');

            CollectionVerification::create([
                'application_id' => $application->id,
                'agent_id' => $agent->id,
                'commodity_photo' => $photoPath,
            ]);
            
            ToastMagic::success('Collection submitted successfully');
            return response()->json(['message' => 'Collection submitted successfully']);
        } catch (\Exception $e) {
            \Log::error('Error submitting collection: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to submit collection. Please try again.'], 500);
        }
    }

public function assignedReturns(Request $request)
{
    $agent = optional(Auth::guard('tenant')->user())->agent;
    $filter = $request->get('filter');
    $season = $request->get('season');
    $status = $request->get('status');

    if (!$agent || empty($agent->center_id)) {
        if ($request->ajax()) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ]);
        }
        $seasons = Season::all();
        return view('agent.verify-return', compact('seasons'))
            ->with('agentSetupRequired', true);
    }

    $apps = Application::with([
            'farmer:id,full_name,registration_number,phone,bvn,nin,address',
            'farm:id,size,location',
            'season:id,name,status,loan_type',
            'applicationCenter',
            'commodity_allocations',
            'applicationCommodities.commodity:id,name,category,unit,price_per_unit',
            'collectionVerification' // eager load for checking collection
        ])
        ->select(['id', 'uuid', 'reference_number', 'farmer_id', 'farm_id', 'season_id', 'status', 'total_loan', 'insurance_rate', 'insurance_amount', 'equity', 'disbursed_amount'])
        ->whereHas('applicationCenter', fn($q) => $q->where('return_center_id', $agent->center_id))
        ->whereHas('season', fn($s) => $s->where('loan_type', 'complete-loan'))
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
            // Compute expected return for complete-loan seasons based on seed current market price
            $expected = [
                'commodity' => null,
                'unit' => null,
                'price_used' => null,
                'quantity' => null,
            ];
            // Identify seed commodity from application commodities
            $seedLine = optional($app->applicationCommodities)->first(function ($line) {
                return optional($line->commodity)->category === 'seed';
            });
            if ($seedLine && $seedLine->commodity) {
                $expected['commodity'] = $seedLine->commodity->name;
                $expected['unit'] = $seedLine->commodity->unit;
                // Get current market price for this seed in this season
                $market = CommodityMarketPrice::where('commodity_id', $seedLine->commodity->id)
                    ->where('season_id', $app->season_id)
                    ->first();
                $price = $market && $market->current_price ? $market->current_price : ($seedLine->commodity->price_per_unit ?? null);
                if ($price && $price > 0) {
                    $expected['price_used'] = $price;
                    $expected['quantity'] = round(($app->total_loan ?? 0) / $price, 2);
                }
            }
            $app->expected_return = $expected;
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

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'photo' => 'required|image|max:4096',
        ]);

        $agent = optional(Auth::guard('tenant')->user())->agent;
        $application = Application::findOrFail($request->application_id);

        if (!$agent || empty($agent->center_id) || $application->applicationCenter->return_center_id !== $agent->center_id) {
            ToastMagic::error('Not authorized for this application');
            return response()->json(['message' => 'Not authorized for this application'], 403);
        }

        $data = [
            'application_id' => $application->id,
            'agent_id' => $agent->id,
            'returned_commodity_photo' => $request->file('photo')->store('returns/photos', 'public'),
        ];

        $returnVerification = ReturnVerification::create($data);

        ToastMagic::success('Return submitted successfully');
        return response()->json(['message' => 'Return submitted successfully']);
    }
}
