<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'agent_id',
        'id_card_photo',
        'returned_commodity_photo',
        'status'
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
}
