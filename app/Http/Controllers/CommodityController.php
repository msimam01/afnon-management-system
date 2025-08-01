<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::all();
        $commodities = Commodity::all();
        return view('admin.commodities.index', compact('seasons', 'commodities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.commodities.create');
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

        Commodity::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'unit' => $validated['unit'],
            'price_per_unit' => $validated['price'],
            'quantity_per_hectare' => $validated['qtyPerHectare'],
            'stock' => $validated['stock'],
            'is_global' => $validated['is_global'] = false
        ]);
        ToastMagic::success('Commodity created successfull');
        return redirect()->route('admin.commodities.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Commodity $commodity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $uuid)
    {

        $commodity = Commodity::whereUuid($uuid)->first();
        return view('admin.commodities.edit', compact('commodity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $uuid)
    {
        $commodity = Commodity::whereUuid($uuid)->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'price_per_unit' => 'required|numeric|min:0',
            'quantity_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $commodity->update([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'price_per_unit' => $request->price_per_unit,
            'quantity_per_hectare' => $request->quantity_per_hectare,
            'stock' => $request->stock,
        ]);
        ToastMagic::success('Commodity updated successfull');
        return redirect()->route('admin.commodities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $uuid)
    {
        $commodity = Commodity::whereUuid($uuid)->first();
        $commodity->delete();
        ToastMagic::success('Commodity deleted successfull');
        return redirect()->route('admin.commodities.index');
    }

    public function importForm()
    {
        // Already imported global IDs
        $importedIds = Commodity::whereNotNull('global_commodity_id')->pluck('global_commodity_id')->toArray();

        // Only show unimported ones
        $globalCommodities = \App\Models\Central\CentralCommodity::on('central')
            ->whereNotIn('id', $importedIds)
            ->get();

        return view('admin.commodities.import', compact('globalCommodities'));
    }



    public function import($id)
    {
        $global = \App\Models\Central\CentralCommodity::findOrFail($id);

        Commodity::create([
            'name' => $global->name,
            'category' => $global->category,
            'type' => $global->type,
            'unit' => $global->unit,
            'price_per_unit' => $global->price_per_unit,
            'quantity_per_hectare' => $global->quantity_per_hectare,
            'stock' => 0,
            'is_global' => true,
            'global_commodity_id' => $global->id,
        ]);
        ToastMagic::success('Imported from global commodities');
        return redirect()->route('admin.commodities.index');
    }
    public function importBulk(Request $request)
    {
        $request->validate([
            'commodity_ids' => 'required|array|min:1',
        ]);

        $ids = $request->commodity_ids;

        $globalCommodities = \App\Models\Central\CentralCommodity::on('central')
            ->whereIn('id', $ids)
            ->get();

        foreach ($globalCommodities as $global) {
            // Prevent duplicates (extra safety)
            $exists = Commodity::where('global_commodity_id', $global->id)->exists();
            if ($exists) continue;

            Commodity::create([
                'name' => $global->name,
                'category' => $global->category,
                'type' => $global->type,
                'unit' => $global->unit,
                'price_per_unit' => $global->price_per_unit,
                'quantity_per_hectare' => $global->quantity_per_hectare,
                'stock' => 0,
                'is_global' => true,
                'global_commodity_id' => $global->id,
            ]);
        }

        ToastMagic::success('Selected commodities imported successfully!');
        return redirect()->route('admin.commodities.index');
    }
    public function sync($uuid)
    {
        $local = Commodity::whereUuid($uuid)->firstOrFail();

        if (!$local->global_commodity_id) {
            ToastMagic::error('This item is not linked to any global commodity.');
            return back();
        }

        $global = \App\Models\Central\CentralCommodity::on('central')
            ->find($local->global_commodity_id);

        if (!$global) {
            ToastMagic::error('Global commodity no longer exists.');
            return back();
        }

        $local->update([
            'price_per_unit' => $global->price_per_unit,
            'unit' => $global->unit,
            'quantity_per_hectare' => $global->quantity_per_hectare,
        ]);

        ToastMagic::success('Commodity synced with global version.');
        return back();
    }
}
