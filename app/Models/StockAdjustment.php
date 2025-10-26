<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_id',
        'season_id',
        'quantity',
        'type',
        'reason',
        'verified_by'
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Agent::class, 'verified_by');
    }
}
