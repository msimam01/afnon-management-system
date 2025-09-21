<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAdmin\Tenant;
use App\Models\User;

class AssignAdminRole extends Command
{
    protected $signature = 'tenant:assign-admin-role {tenant?}';
    protected $description = 'Assign admin role to users in tenant(s)';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        if ($tenantId) {
            $tenants = [Tenant::findOrFail($tenantId)];
        } else {
            $tenants = Tenant::all();
        }

        foreach ($tenants as $tenant) {
            $this->info("Assigning admin role for tenant: {$tenant->id}");

            // Switch to tenant context
            tenancy()->initialize($tenant);

            // Find users without roles and assign admin role
            $usersWithoutRoles = User::doesntHave('roles')->get();

            foreach ($usersWithoutRoles as $user) {
                $user->assignRole('admin');
                $this->info("✅ Assigned admin role to user: {$user->email}");
            }

            $this->info("✅ Admin role assignment completed for tenant: {$tenant->id}");
        }

        $this->info("🎉 All admin roles have been assigned!");
    }
}








