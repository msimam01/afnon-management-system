<?php

namespace App\Http\Controllers\Global;

use App\Models\GlobalCommodityMarketPrice;
use App\Models\GlobalCommodity;
use App\Models\GlobalSeason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GlobalCommodityMarketPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marketPrices = GlobalCommodityMarketPrice::with(['commodity', 'season'])
            ->when($request->has('commodity_id'), function ($query) use ($request) {
                return $query->where('global_commodity_id', $request->commodity_id);
            })
            ->when($request->has('season_id'), function ($query) use ($request) {
                return $query->where('global_season_id', $request->season_id);
            })
            ->latest()
            ->paginate(15);

        $commodities = GlobalCommodity::with('category')->orderBy('name')->get();
        $seasons = GlobalSeason::orderBy('start_date', 'desc')->get();

        return view('global.commodity-market-prices.index', compact('marketPrices', 'commodities', 'seasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commodities = GlobalCommodity::with('category')
            ->orderBy('name')
            ->get();

        $seasons = GlobalSeason::orderBy('start_date', 'desc')->get();

        return view('global.commodity-market-prices.create', compact('commodities', 'seasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'global_commodity_id' => 'required|exists:global_commodities,id',
            'global_season_id' => 'required|exists:global_seasons,id',
            'current_price' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'global_commodity_id.required' => 'Please select a commodity',
            'global_season_id.required' => 'Please select a season',
            'current_price.required' => 'Please enter a valid price',
            'effective_date.required' => 'Please select an effective date',
        ]);

        try {
            DB::beginTransaction();

            // Check if a price already exists for this commodity and season
            $existingPrice = GlobalCommodityMarketPrice::where('global_commodity_id', $validated['global_commodity_id'])
                ->where('global_season_id', $validated['global_season_id'])
                ->exists();

            if ($existingPrice) {
                return back()
                    ->with('error', 'A market price already exists for this commodity and season combination.')
                    ->withInput();
            }

            $price = GlobalCommodityMarketPrice::create($validated);

            DB::commit();

            return redirect()->route('global.commodity-market-prices.index')
                ->with('success', 'Market price created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating market price: ' . $e->getMessage());
            return back()->with('error', 'Error creating market price. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GlobalCommodityMarketPrice $commodityMarketPrice)
    {
        $commodities = GlobalCommodity::with('category')
            ->orderBy('name')
            ->get();

        $seasons = GlobalSeason::orderBy('start_date', 'desc')->get();

        return view('global.commodity-market-prices.edit', compact('commodityMarketPrice', 'commodities', 'seasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GlobalCommodityMarketPrice $commodityMarketPrice)
    {
        \Log::info('Update method called with UUID: ' . $commodityMarketPrice->uuid);

        $validated = $request->validate([
            'global_commodity_id' => 'required|exists:global_commodities,id',
            'global_season_id' => 'required|exists:global_seasons,id',
            'current_price' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'global_commodity_id.required' => 'Please select a commodity',
            'global_season_id.required' => 'Please select a season',
            'current_price.required' => 'Please enter a valid price',
            'effective_date.required' => 'Please select an effective date',
        ]);

        \Log::info('Validation passed, updating price', $validated);

        try {
            DB::beginTransaction();

            // Check if another price already exists for this commodity and season
            $existingPrice = GlobalCommodityMarketPrice::where('global_commodity_id', $validated['global_commodity_id'])
                ->where('global_season_id', $validated['global_season_id'])
                ->where('id', '!=', $commodityMarketPrice->id)
                ->exists();

            if ($existingPrice) {
                \Log::warning('Duplicate commodity-season combination detected');
                return back()
                    ->with('error', 'Another market price already exists for this commodity and season combination.')
                    ->withInput();
            }

            $result = $commodityMarketPrice->update($validated);
            \Log::info('Update result: ' . ($result ? 'success' : 'failed'));

            DB::commit();

            return redirect()->route('global.commodity-market-prices.index')
                ->with('success', 'Market price updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating market price: ' . $e->getMessage());
            return back()->with('error', 'Error updating market price. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GlobalCommodityMarketPrice $commodityMarketPrice)
    {
        $commodityMarketPrice->delete();

        return redirect()->route('global.commodity-market-prices.index')
            ->with('success', 'Market price deleted successfully.');
    }
}
