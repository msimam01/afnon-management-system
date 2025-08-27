<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\CommodityMarketPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commodity extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'name', 'category', 'unit', 'price_per_unit', 'quantity_per_hectare', 'stock'];

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'commodity_seasons')
            ->withTimestamps();
    }
    public function marketPrices()
    {
        return $this->hasMany(CommodityMarketPrice::class, 'commodity_id', 'id');
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
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
