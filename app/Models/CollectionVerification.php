<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'agent_id', 'commodity_id', 'id_card_photo', 'commodity_photo', 'status',
        'collected_quantities', 'collection_notes', 'location_lat', 'location_lng', 'signature', 'fraud_flag',
        'verification_notes', 'approved_by'
    ];

    protected $casts = [
        'collected_quantities' => 'array',
        'location_lat' => 'decimal:7',
        'location_lng' => 'decimal:7',
        'fraud_flag' => 'boolean',
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    /**
     * Get the admin user who approved this verification
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
