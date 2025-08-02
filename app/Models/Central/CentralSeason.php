<?php

namespace App\Models\Central;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CentralSeason extends Model
{
    use HasFactory;
    protected $connection = 'central'; // ← THIS IS IMPORTANT
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'return_deadline',
        'budget',
        'insurance_rate',
        'send_reminder_after_days',
        'commodities',
        'status'
    ];
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
