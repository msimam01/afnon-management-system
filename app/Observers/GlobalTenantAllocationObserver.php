<?php

namespace App\Observers;

use App\Models\GlobalTenantAllocation;
use App\Models\GlobalSeason;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GlobalTenantAllocationObserver
{
    /**
     * Handle the GlobalTenantAllocation "creating" event.
     */
    public function creating(GlobalTenantAllocation $allocation)
    {
        DB::transaction(function () use ($allocation) {
            $season = GlobalSeason::find($allocation->global_season_id);

            if (!$season) {
                throw ValidationException::withMessages(['allocation' => 'Season not found.']);
            }

            $pivot = $season->commodities()->where('global_commodity_id', $allocation->global_commodity_id)->first();

            if (!$pivot) {
                throw ValidationException::withMessages(['allocation' => 'No stock defined for this commodity and season.']);
            }

            $availableStock = $pivot->pivot->stock;

            if ($allocation->allocated_stock > $availableStock) {
                throw ValidationException::withMessages(['allocation' => 'Allocation exceeds available stock. Available: ' . $availableStock]);
            }

            // Deduct stock from the pivot table
            $season->commodities()->updateExistingPivot($allocation->global_commodity_id, [
                'stock' => DB::raw('stock - ' . $allocation->allocated_stock)
            ]);

            Log::info("Allocation created: Tenant {$allocation->tenant_id}, Season {$allocation->global_season_id}, Commodity {$allocation->global_commodity_id}, Allocated {$allocation->allocated_stock}, Remaining stock: " . ($availableStock - $allocation->allocated_stock));
        });
    }

    /**
     * Handle the GlobalTenantAllocation "updating" event.
     */
    public function updating(GlobalTenantAllocation $allocation)
    {
        DB::transaction(function () use ($allocation) {
            $originalAllocated = $allocation->getOriginal('allocated_stock');
            $newAllocated = $allocation->allocated_stock;
            $diff = $newAllocated - $originalAllocated;

            $season = GlobalSeason::find($allocation->global_season_id);

            if (!$season) {
                throw ValidationException::withMessages(['allocation' => 'Season not found.']);
            }

            $pivot = $season->commodities()->where('global_commodity_id', $allocation->global_commodity_id)->first();

            if (!$pivot) {
                throw ValidationException::withMessages(['allocation' => 'No stock defined for this commodity and season.']);
            }

            $currentStock = $pivot->pivot->stock;

            if ($diff > 0 && $diff > $currentStock) {
                throw ValidationException::withMessages(['allocation' => 'Updated allocation exceeds available stock. Available: ' . $currentStock]);
            }

            // Adjust stock in the pivot table
            if ($diff != 0) {
                $season->commodities()->updateExistingPivot($allocation->global_commodity_id, [
                    'stock' => DB::raw('stock - ' . $diff)
                ]);
            }

            Log::info("Allocation updated: Tenant {$allocation->tenant_id}, Season {$allocation->global_season_id}, Commodity {$allocation->global_commodity_id}, Changed from {$originalAllocated} to {$newAllocated}, Remaining stock: " . ($currentStock - $diff));
        });
    }

    /**
     * Handle the GlobalTenantAllocation "deleting" event.
     */
    public function deleting(GlobalTenantAllocation $allocation)
    {
        DB::transaction(function () use ($allocation) {
            $season = GlobalSeason::find($allocation->global_season_id);

            if (!$season) {
                throw ValidationException::withMessages(['allocation' => 'Season not found.']);
            }

            // Restore stock to the pivot table
            $season->commodities()->updateExistingPivot($allocation->global_commodity_id, [
                'stock' => DB::raw('stock + ' . $allocation->allocated_stock)
            ]);

            Log::info("Allocation deleted: Tenant {$allocation->tenant_id}, Season {$allocation->global_season_id}, Commodity {$allocation->global_commodity_id}, Returned {$allocation->allocated_stock}");
        });
    }
}
