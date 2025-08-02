<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuotaAllocation;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commodity::query();

        if ($request->filled('season_id')) {
            $query->where('season_id', $request->season_id);
        }

        if ($request->filled('is_global')) {
            $query->where('is_global', $request->is_global);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        // Optional: Sorting
        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_dir', 'asc'));
        } else {
            $query->latest();
        }

        $commodities = $query->paginate(15)->withQueryString();
        $seasons = Season::all();

        // Inject allocated quantity for global commodities
        foreach ($commodities as $item) {
            if ($item->is_global && $item->season_id) {
                $allocation = \App\Models\QuotaAllocation::where('commodity_id', $item->id)
                    ->where('season_id', $item->season_id)
                    ->where('tenant', tenant('id'))
                    ->first();

                if ($allocation) {
                    $item->allocated_quantity = $allocation->allocated_quantity;
                }
            }
        }

        return view('admin.commodities.index', compact('commodities', 'seasons'));
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
    
        $openSeason = Season::where('status', 'open')->latest()->first();
    
        if (!$openSeason) {
            ToastMagic::error('No open season found. Cannot create commodity.');
            return redirect()->route('admin.commodities.index');
        }
    
        Commodity::create([
            'uuid' => Str::uuid(),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'unit' => $validated['unit'],
            'price_per_unit' => $validated['price'],
            'quantity_per_hectare' => $validated['qtyPerHectare'],
            'stock' => $validated['stock'],
            'season_id' => $openSeason->id,
            'is_global' => false,
        ]);
    
        ToastMagic::success('Commodity created successfully');
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
    public function update(Request $request, string $uuid)
    {
        $commodity = Commodity::whereUuid($uuid)->firstOrFail();

        // Optional: If commodity is associated with a season, prevent updates if season is closed
        if ($commodity->season && $commodity->season->status === 'closed') {
            ToastMagic::error('This commodity is linked to a closed season and cannot be modified.');
            return redirect()->route('admin.commodities.index');
        }

        // If global commodity, only allow price update
        if ($commodity->is_global) {
            $validated = $request->validate([
                'price_per_unit' => 'required|numeric|min:0',
            ]);

            $commodity->update([
                'price_per_unit' => $validated['price_per_unit'],
            ]);

            ToastMagic::success('Price updated for global commodity.');
            return redirect()->route('admin.commodities.index');
        }

        // Local commodity full update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'price_per_unit' => 'required|numeric|min:0',
            'quantity_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $commodity->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'unit' => $validated['unit'],
            'price_per_unit' => $validated['price_per_unit'],
            'quantity_per_hectare' => $validated['quantity_per_hectare'],
            'stock' => $validated['stock'],
        ]);

        ToastMagic::success('Commodity updated successfully.');
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
            'season_id' => Season::latest()->first()?->id,
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
                'season_id' => Season::latest()->first()?->id,
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
