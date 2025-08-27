<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class MonetaryReturn extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'tx_ref', 'application_id', 'amount', 'payment_proof', 'payment_link', 'verified_by', 'verified_at', 'invoice_number', 'status'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
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
