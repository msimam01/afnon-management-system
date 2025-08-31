<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\CommoditySeason;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\ValidationException;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::with('commodities')->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        $commodities = Commodity::all();

        if ($commodities->isEmpty()) {
            ToastMagic::success('You must create at least one commodity before creating a season.');
            return redirect()->route('admin.commodities.create');
        }
        return view('admin.seasons.create', compact('commodities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:dry,wet', // ✅ validate type
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'required|date|after:end_date',
            'budget' => 'nullable|numeric|min:0',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'status' => 'nullable|in:open,closed',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        $year = \Carbon\Carbon::parse($request->start_date)->year;

        $exists = Season::whereYear('start_date', $year)
            ->where('type', $data['type']) // ✅ use validated type
            ->exists();

        if ($exists) {
            ToastMagic::error("A {$data['type']} season already exists for the year {$year}.");
            return redirect()->back()->withInput(); // ✅ stop execution
        }

        // If creating an open season, close any existing open seasons
        if (($data['status'] ?? 'open') === 'open') {
            $openSeasons = Season::where('status', 'open')->get();
            if ($openSeasons->count() > 0) {
                Season::where('status', 'open')->update(['status' => 'closed']);
                $seasonNames = $openSeasons->pluck('name')->join(', ');
                ToastMagic::info("Closed existing open season(s): {$seasonNames}");
            }
        }

        // Set default status to 'open' if not provided
        $data['status'] = $data['status'] ?? 'open';

        $season = Season::create($data);

        try {
            $season->commodities()->sync($request->commodities);
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('An error occurred while assigning commodities. Please try again or contact support.');
        }

        ToastMagic::success('Season created.');
        return redirect()->route('admin.seasons.index');
    }


    public function show(Season $season)
    {
        // Application stats
        $applications = $season->applications();
        $totalApplications = $applications->count();
        $approvedApplications = $applications->clone()->where('status', 'approved')->count();
        $pendingApplications = $applications->clone()->where('status', 'pending')->count();
        $distributedApplications = $applications->clone()->where('status', 'distributed')->count();
        $rejectedApplications = $applications->clone()->where('status', 'rejected')->count();
        $totalFarmers = $applications->clone()->distinct('farmer_id')->count('farmer_id');

        // Commodity distribution stats
        $commodities = DB::table('commodities')
            ->join('commodity_allocations', 'commodities.name', '=', 'commodity_allocations.commodity_name')
            ->join('applications', 'commodity_allocations.application_id', '=', 'applications.id')
            ->leftJoin('collection_verifications', 'commodity_allocations.application_id', '=', 'collection_verifications.application_id')
            ->where('applications.season_id', $season->id)
            ->select(
                'commodities.id',
                'commodities.name',
                'commodities.category',
                'commodities.unit',
                DB::raw('SUM(commodity_allocations.allocated_quantity) as allocated'),
                DB::raw('SUM(CASE WHEN collection_verifications.id IS NOT NULL THEN commodity_allocations.allocated_quantity ELSE 0 END) as distributed')
            )
            ->groupBy('commodities.id', 'commodities.name', 'commodities.category', 'commodities.unit')
            ->get()
            ->map(function ($item) {
                $item->remaining = ($item->allocated ?? 0) - ($item->distributed ?? 0);
                return $item;
            });

        // Totals
        $totalAllocated = $commodities->sum('allocated');
        $totalDistributed = $commodities->sum('distributed');
        $totalRemaining = $commodities->sum('remaining');

        return view('admin.seasons.show', compact(
            'season',
            'commodities',
            'totalApplications',
            'approvedApplications',
            'pendingApplications',
            'distributedApplications',
            'rejectedApplications',
            'totalFarmers',
            'totalAllocated',
            'totalDistributed',
            'totalRemaining'
        ));
    }



    public function edit(Season $season)
    {
        $commodities = Commodity::all();
        $selected = $season->commodities()->pluck('commodities.id')->toArray();
        return view('admin.seasons.edit', compact('season', 'commodities', 'selected'));
    }

    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:dry,wet',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'required|date|after:end_date',
            'budget' => 'nullable|numeric|min:0',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'status' => 'nullable|in:open,closed',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        $year = \Carbon\Carbon::parse($data['start_date'])->year;

        // ✅ Ensure no duplicate season of same type + year, excluding current season
        $exists = Season::whereYear('start_date', $year)
            ->where('type', $data['type'])
            ->where('id', '!=', $season->id)
            ->exists();

        if ($exists) {
            ToastMagic::error("A {$data['type']} season already exists for the year {$year}.");
            return redirect()->back()->withInput();
        }

        // If updating to open status, close any other open seasons
        if (($data['status'] ?? $season->status) === 'open' && $season->status !== 'open') {
            $openSeasons = Season::where('status', 'open')->where('id', '!=', $season->id)->get();
            if ($openSeasons->count() > 0) {
                Season::where('status', 'open')->where('id', '!=', $season->id)->update(['status' => 'closed']);
                $seasonNames = $openSeasons->pluck('name')->join(', ');
                ToastMagic::info("Closed existing open season(s): {$seasonNames}");
            }
        }

        $season->update($data);

        try {
            $season->commodities()->sync($request->commodities);
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('An error occurred while updating commodities. Please try again.');
            return redirect()->back()->withInput();
        }

        ToastMagic::success('Season updated successfully.');
        return redirect()->route('admin.seasons.index');
    }



    public function destroy(Season $season)
    {
        $season->delete();
        return back()->with('success', 'Season deleted');
    }

    public function export($uuid)
    {
        // Placeholder for Excel export (if needed later)
    }
    public function close(Season $season)
    {
        $season->update(['status' => 'closed']);
        ToastMagic::success('Season closed.');
        return back();
    }

    public function reopen(Season $season)
    {
        // Check if there's already an open season
        $openSeason = Season::where('status', 'open')->where('id', '!=', $season->id)->first();

        if ($openSeason) {
            ToastMagic::error("Cannot reopen season. '{$openSeason->name}' is already open. Please close it first.");
            return back();
        }

        $season->update(['status' => 'open']);
        ToastMagic::success('Season reopened.');
        return back();
    }
}
