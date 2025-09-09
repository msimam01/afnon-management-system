<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Center extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'state',
        'lga',
        'address',
    ];
    // Removed UUID creation since the table doesn't have a uuid column
}
