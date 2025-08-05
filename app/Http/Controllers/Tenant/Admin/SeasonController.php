<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\CommoditySeason;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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

    public function create()
    {
        $commodities = Commodity::all();
        return view('admin.seasons.create', compact('commodities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'return_deadline' => 'required|date|after:end_date',
            'budget' => 'nullable|numeric|min:0',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        $season = Season::create($data);
        $season->commodities()->sync($request->commodities);
        ToastMagic::success('Season created.');
        return redirect()->route('admin.seasons.index');
    }

    public function show(Season $season)
    {
        $commodities = Commodity::all();
        $selected = $season->commodities()->pluck('commodities.id')->toArray();
        return view('admin.seasons.show', compact('season', 'commodities', 'selected'));
    }

    public function edit(Season $season)
    {
        $commodities = Commodity::all();
        $selected = $season->commodities()->pluck('commodities.id')->toArray();
        return view('admin.seasons.edit', compact('season', 'commodities', 'selected'));
    }

    public function update(Request $request, Season $season)
    {
        if ($request->has('status')) {
            $season->update(['status' => $request->status]);
            return back()->with('success', 'Season status updated.');
        }

        $data = $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'return_deadline' => 'required|date|after:end_date',
            'budget' => 'nullable|numeric|min:0',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        $season->update($data);
        $season->commodities()->sync($request->commodities);
        ToastMagic::success('Season updated');
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
        $season->update(['status' => 'open']);
        ToastMagic::success('Season reopened.');
        return back();
    }
}
