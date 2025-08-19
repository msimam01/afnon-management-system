<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Support\Str;

class Activity extends SpatieActivity
{
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'properties' => 'collection',
        'uuid' => 'string',
    ];

    /**
     * Boot the model and add UUID generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Find model by UUID
     */
    public static function findByUuid($uuid)
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Scope to find by UUID
     */
    public function scopeByUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
