<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class CommodityMarketPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'commodity_id',
        'season_id', // Add the new column to the fillable array
        'current_price'
    ];

    // You should also add the relationship to the Season model
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
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
