<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlobalSeasonResource extends JsonResource
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
            'type' => $this->type,
            'loan_type' => $this->loan_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'collection_start_date' => $this->collection_start_date,
            'collection_end_date' => $this->collection_end_date,
            'budget' => (float) $this->budget,
            'status' => $this->status,
            'return_deadline' => $this->return_deadline,
            'insurance_rate' => (float) $this->insurance_rate,
            'send_reminder_after_days' => $this->send_reminder_after_days,
            'commodities' => $this->whenLoaded('commodities', function () {
                return $this->commodities->map(function ($commodity) {
                    return [
                        'id' => $commodity->id,
                        'name' => $commodity->name,
                        'pivot' => [
                            'stock' => (float) $commodity->pivot->stock
                        ]
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
