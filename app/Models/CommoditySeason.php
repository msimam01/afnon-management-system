<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class CommoditySeason extends Model
{
    use HasFactory;
    protected $fillable = ['commodity_id', 'season_id'];
    protected static function booted()
    {
        static::creating(fn ($model) => $model->uuid = (string) Str::uuid());
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
