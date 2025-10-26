<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlobalCommoditySeason extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'global_season_id',
        'global_commodity_id',
        'stock',
        'total_stock',
        'available_stock',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stock' => 'decimal:2',
        'total_stock' => 'decimal:2',
        'available_stock' => 'decimal:2',
    ];

    /**
     * Get the season that owns the commodity season.
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(GlobalSeason::class, 'global_season_id');
    }

    /**
     * Get the commodity that owns the commodity season.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(GlobalCommodity::class, 'global_commodity_id');
    }

    /**
     * Recalculate available stock based on current allocations.
     */
    public function recalculateAvailableStock()
    {
        $totalAllocated = GlobalTenantAllocation::where('global_commodity_id', $this->global_commodity_id)
            ->where('global_season_id', $this->global_season_id)
            ->sum('allocated_stock');
        $this->available_stock = $this->total_stock - $totalAllocated;
        $this->save();
    }
}
