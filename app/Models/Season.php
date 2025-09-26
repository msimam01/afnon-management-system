<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Season extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'type',
        'loan_type',
        'start_date',
        'end_date',
        'budget',
        'status',
        'return_deadline',
        'insurance_rate',
        'send_reminder_after_days',
        'collection_start_date',
        'collection_end_date',
    ];

    protected static function booted()
    {
        static::creating(fn($season) => $season->uuid = (string) Str::uuid());
    }

    public function commodities()
    {
        return $this->belongsToMany(Commodity::class, 'commodity_seasons')
            ->withTimestamps();
    }

    // Helper to check season scenario
    public function isCoFunded(): bool
    {
        return $this->loan_type === 'co-funded';
    }

    public function isCompleteLoan(): bool
    {
        return $this->loan_type === 'complete-loan';
    }
    // App\Models\Season.php

    public function applications()
    {
        return $this->hasMany(Application::class, 'season_id');
    }


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
