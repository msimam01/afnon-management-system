<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Farmer extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration_number',
        'full_name',
        'phone',
        'nin',
        'bvn',
        'state',
        'lga',
        'address',
        'cluster'
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
