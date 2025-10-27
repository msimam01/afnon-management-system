<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'commodity_id',
        'agent_id',
        'id_card_photo',
        'returned_commodity_photo',
        'status',
        'expected_quantity',
        'returned_quantity',
        'variance',
        'shortfall_reason',
        'partial_return',
        'location_lat',
        'location_lng',
        'signature',
        'fraud_flag',
        'verification_notes',
        'approved_by'
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    
    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    }

    /**
     * Get the admin user who approved this verification
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
