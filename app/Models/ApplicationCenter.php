<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCenter extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'application_id',
        'collection_center_id',
        'return_center_id',
        'collection_date',
        'return_date',
    ];

    public function application() {
        return $this->belongsTo(Application::class);
    }

    public function collectionCenter() {
        return $this->belongsTo(Center::class, 'collection_center_id');
    }

    public function returnCenter() {
        return $this->belongsTo(Center::class, 'return_center_id');
    }
}
