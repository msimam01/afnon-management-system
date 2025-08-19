<?php

namespace App\Models;

use App\Models\Central\CentralSeason;
use Illuminate\Database\Eloquent\Model;
use App\Models\Central\CentralCommodity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SyncLog extends Model
{
    use HasFactory;
    protected $appends = ['item_name'];
    protected $fillable = [
        'tenant_id',
        'type',
        'item_id',
        'synced_at',
    ];
    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function getItemNameAttribute()
    {
        if ($this->type === 'season') {
            return optional(CentralSeason::find($this->item_id))->name ?? 'Unknown';
        }

        if ($this->type === 'commodity') {
            return optional(CentralCommodity::find($this->item_id))->name ?? 'Unknown';
        }

        return 'Unknown';
    }
}
