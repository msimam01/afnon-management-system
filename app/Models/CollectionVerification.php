<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id', 'agent_id', 'commodity_id', 'id_card_photo', 'commodity_photo', 'status',
        'collected_quantity', 'collection_notes', 'location_lat', 'location_lng', 'signature', 'fraud_flag',
        'verification_notes', 'approved_by'
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
