<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin\Tenant;
use Spatie\Permission\Models\Role;
use App\Models\User;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds for tenant-specific data.
     */
    public function run(): void
    {
        // This seeder runs inside tenant context
        // Create default roles for the tenant
        $roles = [
            'admin' => 'Administrator with full access',
            'agent' => 'Field agent for data collection', 
            'farmer' => 'Farmer user with limited access'
        ];

        foreach ($roles as $roleName => $description) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    'tenant_id' => tenant('id')
                ]);
            }
        }

        // Create default settings or other tenant-specific data here
        // Example: Create default commodities, seasons, etc.
    }
}
