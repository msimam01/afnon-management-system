<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalSeason extends Model
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
        'uuid',
        'name',
        'type',
        'loan_type',
        'start_date',
        'end_date',
        'collection_start_date',
        'collection_end_date',
        'budget',
        'status',
        'return_deadline',
        'insurance_rate',
        'send_reminder_after_days',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'collection_start_date' => 'date',
        'collection_end_date' => 'date',
        'return_deadline' => 'date',
        'budget' => 'decimal:2',
        'insurance_rate' => 'decimal:2',
    ];

    /**
     * The commodities that belong to the season.
     */
    public function commodities(): BelongsToMany
    {
        return $this->belongsToMany(GlobalCommodity::class, 'global_commodity_seasons')
            ->withPivot('stock')
            ->withTimestamps();
    }

    /**
     * Get the market prices for the season.
     */
    public function marketPrices(): HasMany
    {
        return $this->hasMany(GlobalCommodityMarketPrice::class, 'season_id');
    }

    /**
     * Get the tenant allocations for the season.
     */
    public function tenantAllocations(): HasMany
    {
        return $this->hasMany(GlobalTenantAllocation::class, 'global_season_id');
    }
}
