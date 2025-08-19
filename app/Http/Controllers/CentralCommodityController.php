<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Models\Central\CentralCommodity;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CentralCommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::all();
        $commodities = CentralCommodity::all();
        return view('super-admin.commodities.index', compact('seasons', 'commodities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.commodities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric',
            'qtyPerHectare' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        CentralCommodity::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'unit' => $validated['unit'],
            'price_per_unit' => $validated['price'],
            'quantity_per_hectare' => $validated['qtyPerHectare'],
            'stock' => $validated['stock'],
        ]);
        ToastMagic::success('Commodity created successfull');
        return redirect()->route('superadmin.commodities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CentralCommodity $centralCommodity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $uuid)
    {
        try {
            $commodity = CentralCommodity::whereUuid($uuid)->first();
        } catch (\Throwable $th) {
            ToastMagic::error('Not record found for requested commodity');
            return back()->route('superadmin.commodities.index');
        }
        return view('super-admin.commodities.edit', compact('commodity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $uuid)
    {
        $centralCommodity = CentralCommodity::whereUuid($uuid)->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'price_per_unit' => 'required|numeric|min:0',
            'quantity_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $centralCommodity->update([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'price_per_unit' => $request->price_per_unit,
            'quantity_per_hectare' => $request->quantity_per_hectare,
            'stock' => $request->stock,
        ]);
        ToastMagic::success('Commodity updated successfull');
        return redirect()->route('superadmin.commodities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $uuid)
    {
        $centralCommodity = CentralCommodity::whereUuid($uuid)->first();
        $centralCommodity->delete();
        ToastMagic::success('Commodity deleted successfull');
        return redirect()->route('superadmin.commodities.index');
    }
}
