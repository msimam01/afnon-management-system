<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\ApplicationCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'farmer_id',
        'farm_id',
        'season_id',
        'insurance_rate',
        'insurance_amount',
        'total_loan',
        'equity',
        'disbursed_amount',
        'reference_number',
        'status'
    ];

    public function applicationCommodities()
    {
        return $this->hasMany(ApplicationCommodity::class);
    }
    // In Application.php model
    public function commodities()
    {
        return $this->belongsToMany(Commodity::class, 'application_commodities')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
    public function season()
    {
        return $this->belongsTo(Season::class);
    }
    public function commodity_allocations()
    {
        return $this->hasMany(CommodityAllocation::class);
    }

    public function applicationCenter()
    {
        return $this->hasOne(ApplicationCenter::class);
    }
    public function collectionVerification()
    {
        return $this->hasOne(CollectionVerification::class);
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
