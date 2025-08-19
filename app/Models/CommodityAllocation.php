<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommodityAllocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'commodity_name',
        'qty_per_hectare',
        'allocated_quantity',
        'unit_price',
        'total_value',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
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
