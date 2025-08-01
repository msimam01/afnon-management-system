<?php

namespace App\Models\Central;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CentralCommodity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category', 'unit', 'price_per_unit', 'quantity_per_hectare', 'stock'];
    protected $connection = 'central'; // ← THIS IS IMPORTANT

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
