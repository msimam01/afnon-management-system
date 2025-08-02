<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Central\CentralSeason;
use Illuminate\Database\Eloquent\Model;
use App\Models\Central\CentralCommodity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotaAllocation extends Model
{
    use HasFactory;
    // protected $connection = 'tenant';

    protected $fillable = [
        'season_id',
        'tenant',
        'commodity_id',
        'allocated_quantity',
    ];

    public function season()
    {
        return $this->belongsTo(CentralSeason::class, 'season_id');
    }

    public function commodity()
    {
        return $this->belongsTo(CentralCommodity::class, 'commodity_id');
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
