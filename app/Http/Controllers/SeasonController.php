<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Support\Facades\Validator;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.seasons.index');
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'return_deadline' => 'required|date',
            'insurance_rate' => 'required|numeric',
            'send_reminder_after_days' => 'required|numeric',
            'budget' => 'required|numeric',
        ]);
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
        $season = Season::whereUuid($uuid)->firstOrFail();
        return view('admin.seasons.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $season = Season::whereUuid($uuid)->firstOrFail();

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'return_deadline' => 'required|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'required|integer|min:1',
            'status' => 'required|in:open,closed',
        ]);

        $season->update([
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        //
    }
}
