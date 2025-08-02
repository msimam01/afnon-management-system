<?php

namespace App\Exports;

use App\Models\Commodity;
use App\Models\CommodityAllocation;
use Maatwebsite\Excel\Concerns\FromCollection;

class SeasonCommodityDistributionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $season;

    public function __construct($season)
    {
        $this->season = $season;
    }

    public function view()
    {
        $commodities = Commodity::where('season_id', $this->season->id)->get();

        $commodities = $commodities->map(function ($item) {
            $allocations = CommodityAllocation::where('commodity_id', $item->id)->get();
            $item->allocated = $allocations->sum('allocated_quantity');
            $item->distributed = $allocations->where('status', 'collected')->sum('allocated_quantity');
            $item->remaining = $item->allocated - $item->distributed;
            return $item;
        });

        return view('exports.season-commodity-report', [
            'season' => $this->season,
            'commodities' => $commodities
        ]);
    }
    public function collection()
    {
        return Commodity::all();
    }
}
