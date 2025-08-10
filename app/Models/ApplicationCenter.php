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
}
