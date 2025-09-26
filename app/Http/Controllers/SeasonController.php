<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\CommodityAllocation;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Exports\SeasonCommodityDistributionExport;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::with('commodities')->latest()->get();
        return view('admin.seasons.index', compact('seasons'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $seasons = Season::latest()->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'commodities' => 'required',
            'type' => 'nullable|in:dry,wet',
            'loan_type' => 'required|in:co-funded,complete-loan',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'nullable|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'nullable|integer|min:1',
            'budget' => 'required|numeric',
        ]);
        if ($validated['loan_type'] === 'complete-loan') {
            $request->validate([
                'return_deadline' => 'required|date|after:end_date',
                'send_reminder_after_days' => 'required|integer|min:1',
            ]);
        }
        return $validated;
    }

    /**
     * Display the specified resource.
     */
    public function show(Season $season)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $season = \App\Models\Season::whereUuid($uuid)->firstOrFail();

        $commodities = $season->commodities()->withPivot('allocated_quantity')->get();

        foreach ($commodities as $item) {
            // Allocated from pivot
            $item->allocated = $item->pivot->allocated_quantity ?? 0;

            // Distributed to farmers from commodity_allocations table
            $item->distributed = \App\Models\CommodityAllocation::where('commodity_id', $item->id)
                ->where('status', 'collected')
                ->sum('allocated_quantity');

            // Remaining = allocated - distributed
            $item->remaining = $item->allocated - $item->distributed;
        }

        return view('admin.seasons.edit', compact('season', 'commodities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $season = Season::whereUuid($uuid)->firstOrFail();

        $request->validate([
            'type' => 'nullable|in:dry,wet',
            'loan_type' => 'required|in:co-funded,complete-loan',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'nullable|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'nullable|integer|min:1',
            'status' => 'required|in:open,closed',
        ]);
        if ($request->loan_type === 'complete-loan') {
            $request->validate([
                'return_deadline' => 'required|date|after:end_date',
                'send_reminder_after_days' => 'required|integer|min:1',
            ]);
        } else {
            $request->merge([
                'return_deadline' => null,
                'send_reminder_after_days' => null,
            ]);
        }

        $season->update([
            'type' => $request->type ?? $season->type,
            'loan_type' => $request->loan_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'return_deadline' => $request->return_deadline,
            'insurance_rate' => $request->insurance_rate,
            'send_reminder_after_days' => $request->send_reminder_after_days,
            'status' => $request->status,
        ]);

        ToastMagic::success('Season updated successfully.');
        return redirect()->route('admin.seasons.index');
    }
    public function export($uuid)
    {
        $season = Season::whereUuid($uuid)->firstOrFail();
        return Excel::download(new SeasonCommodityDistributionExport($season), $season->name . '_distribution.xlsx');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        //
    }
}
