<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GlobalCommodity extends Model
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
        'name',
        'category_id',
        'type',
        'unit',
        'price_per_unit',
        'quantity_per_hectare',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'quantity_per_hectare' => 'decimal:2',
    ];

    /**
     * Get the category that owns the commodity.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(GlobalCommodityCategory::class, 'category_id');
    }

    /**
     * The seasons that belong to the commodity.
     */
    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(GlobalSeason::class, 'global_commodity_seasons')
            ->withPivot('stock')
            ->withTimestamps();
    }

    /**
     * Get the market prices for the commodity.
     */
    public function marketPrices()
    {
        return $this->hasMany(GlobalCommodityMarketPrice::class, 'commodity_id');
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
