<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Permission extends SpatiePermission
{
    use BelongsToTenant;

    protected $fillable = ['name', 'guard_name', 'tenant_id'];
}
