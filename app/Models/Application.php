<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\ApplicationCenter;
use App\Services\ApplicationCacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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
        'status',
        'payment_status'
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
        return $this->belongsTo(Farmer::class)->select(['id', 'full_name', 'registration_number', 'phone', 'bvn']);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class)->select(['id', 'size', 'location']);
    }

    public function season()
    {
        return $this->belongsTo(Season::class)->select(['id', 'name', 'status', 'loan_type', 'collection_start_date', 'collection_end_date', 'return_deadline']);
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
    public function returnVerification()
    {
        return $this->hasOne(\App\Models\ReturnVerification::class, 'application_id');
    }
    public function monetaryReturn()
    {
        return $this->hasOne(\App\Models\MonetaryReturn::class, 'application_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        // Cache management
        static::saved(function ($model) {
            ApplicationCacheService::cacheApplication($model);
        });

        static::deleted(function ($model) {
            ApplicationCacheService::clearCache($model);
        });
    }

    /**
     * Performance-optimized scopes
     */
    public function scopeWithOptimizedRelations(Builder $query)
    {
        return $query->with([
            'farmer:id,full_name,registration_number,phone',
            'farm:id,size,location',
            'season:id,name,status',
            'commodities:id,name,unit,price_per_unit'
        ]);
    }

    public function scopeForListing(Builder $query)
    {
        return $query->select([
            'id',
            'uuid',
            'reference_number',
            'status',
            'total_loan',
            'disbursed_amount',
            'farmer_id',
            'season_id',
            'created_at'
        ])->withOptimizedRelations();
    }

    public function scopeForDetails(Builder $query)
    {
        return $query->with([
            'farmer',
            'farm',
            'season',
            'commodities',
            'commodity_allocations',
            'applicationCenter.collectionCenter:id,name',
            'applicationCenter.returnCenter:id,name'
        ]);
    }

    /**
     * Fast lookup methods
     */
    public static function findByUuidCached(string $uuid)
    {
        return ApplicationCacheService::getByUuid($uuid, [
            'farmer',
            'farm',
            'season',
            'applicationCommodities.commodity',
            'commodity_allocations'
        ]);
    }

    public static function findByReferenceCached(string $reference)
    {
        return ApplicationCacheService::getByReference($reference, [
            'farmer',
            'farm',
            'season',
            'applicationCommodities.commodity',
            'commodity_allocations'
        ]);
    }

    /**
     * Bulk operations for performance
     */
    public static function bulkUpdateStatus(array $ids, string $status)
    {
        return static::whereIn('id', $ids)->update(['status' => $status]);
    }

    /**
     * Get computed attributes efficiently
     */
    public function getTotalCommodityValueAttribute()
    {
        return $this->commodities->sum(function ($commodity) {
            return $commodity->pivot->quantity * $commodity->price_per_unit;
        });
    }

    public function getFormattedReferenceAttribute()
    {
        return strtoupper($this->reference_number);
    }
}
