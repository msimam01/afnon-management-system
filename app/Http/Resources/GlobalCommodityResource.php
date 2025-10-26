<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlobalCommodityResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name
                ];
            }),
            'type' => $this->type,
            'unit' => $this->unit,
            'price_per_unit' => (float) $this->price_per_unit,
            'quantity_per_hectare' => (float) $this->quantity_per_hectare,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
