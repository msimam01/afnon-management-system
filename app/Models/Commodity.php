<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Commodity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category', 'unit', 'price_per_unit', 'quantity_per_hectare', 'stock', 'is_global', 'global_commodity_id', 'season_id'];

    public function season()
    {
        return $this->belongsTo(\App\Models\Season::class);
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
