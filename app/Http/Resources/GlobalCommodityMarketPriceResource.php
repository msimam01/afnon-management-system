<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlobalCommodityMarketPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'commodity_id' => $this->commodity_id,
            'commodity' => $this->whenLoaded('commodity', function () {
                return [
                    'id' => $this->commodity->id,
                    'name' => $this->commodity->name,
                    'unit' => $this->commodity->unit
                ];
            }),
            'season_id' => $this->season_id,
            'season' => $this->whenLoaded('season', function () {
                return $this->season ? [
                    'id' => $this->season->id,
                    'name' => $this->season->name
                ] : null;
            }),
            'current_price' => (float) $this->current_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
