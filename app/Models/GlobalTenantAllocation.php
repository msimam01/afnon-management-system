<?php

namespace App\Models;

use App\Models\SuperAdmin\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlobalTenantAllocation extends Model
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
        'tenant_id',
        'global_season_id',
        'global_commodity_id',
        'allocated_stock',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'allocated_stock' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the allocation.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * Get the commodity that owns the allocation.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(GlobalCommodity::class, 'global_commodity_id');
    }

    /**
     * Get the season that owns the allocation.
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(GlobalSeason::class, 'global_season_id');
    }

    /**
     * Update or create an allocation for a tenant and season.
     *
     * @param string $tenantId
     * @param int $seasonId
     * @param array $data
     * @return static
     */
    public static function updateOrCreateAllocation(string $tenantId, int $seasonId, array $data): self
    {
        return static::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'global_season_id' => $seasonId,
            ],
            $data
        );
    }
}
