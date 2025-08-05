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
        'start_date',
        'end_date',
        'budget',
        'status',
        'return_deadline',
        'insurance_rate',
        'send_reminder_after_days',
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
