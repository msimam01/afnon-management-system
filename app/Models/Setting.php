<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_name', 'email', 'phone', 'address', 'logo',
        'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
    ];
}
