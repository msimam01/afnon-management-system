<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Role extends SpatieRole
{
    use  BelongsToTenant;

    protected $fillable = ['name', 'guard_name', 'tenant_id'];
    // Always default to tenant guard
    protected $attributes = [
        'guard_name' => 'tenant',
    ];
}
