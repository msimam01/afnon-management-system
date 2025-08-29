<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommodityMarketPrice;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CommodityMarketPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'commodity_id' => 'required|exists:commodities,id',
        'season_id'    => 'nullable|exists:seasons,id',
        'current_price'=> 'numeric|required'
    ]);

    CommodityMarketPrice::updateOrCreate(
        [
            'commodity_id' => $request->commodity_id,
            'season_id'    => $request->season_id, // can be null
        ],
        [
            'current_price' => $request->current_price,
        ]
    );

    ToastMagic::success('Market price saved successfully');
    return redirect()->back();
}

    /**
     * Display the specified resource.
     */
    public function show(CommodityMarketPrice $commodityMarketPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommodityMarketPrice $commodityMarketPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommodityMarketPrice $commodityMarketPrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommodityMarketPrice $commodityMarketPrice)
    {
        //
    }
}
