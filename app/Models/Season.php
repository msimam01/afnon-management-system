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
        'is_global',
        'global_season_id'
    ];
    public function commodities()
    {
        return $this->hasMany(\App\Models\Commodity::class);
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
