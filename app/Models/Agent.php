<?php

namespace App\Models;

use App\Models\Center;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'user_id', 'center_id', 'photo', 'status'
    ];

    protected static function booted()
    {
        static::creating(function ($agent) {
            $agent->uuid = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
