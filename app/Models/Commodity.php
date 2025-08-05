<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Commodity extends Model
{
    use HasFactory;
    protected $fillable = ['uuid', 'name', 'category', 'unit', 'price_per_unit', 'quantity_per_hectare', 'stock'];

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'commodity_seasons')
            ->withTimestamps();
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
