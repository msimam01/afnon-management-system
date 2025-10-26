<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GlobalCommodityMarketPrice extends Model
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
        'global_commodity_id',
        'global_season_id',
        'current_price',
        'effective_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_price' => 'decimal:2',
    ];

    /**
     * Get the commodity that owns the market price.
     */
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(GlobalCommodity::class, 'global_commodity_id');
    }

    /**
     * Get the season that owns the market price.
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(GlobalSeason::class, 'global_season_id');
    }

    /**
     * Get the route key name for Laravel's route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
