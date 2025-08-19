<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;

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
        $commodities = Commodity::latest()->get()->transform(fn($item) => [
            'id' => $item->id ?? null,
            'name' => $item->name ?? null,
        ]);
        return view('admin.seasons.create', compact('commodities'));
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
    public function edit(Season $season)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Season $season)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        //
    }
}
