<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAdmin\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RefreshTenantPermissions extends Command
{
    protected $signature = 'tenant:refresh-permissions {tenant?}';
    protected $description = 'Refresh permissions for tenant(s)';

    public function handle()
    {
        $tenantId = $this->argument('tenant');

        if ($tenantId) {
            $tenants = [Tenant::findOrFail($tenantId)];
        } else {
            $tenants = Tenant::all();
        }

        foreach ($tenants as $tenant) {
            $this->info("Refreshing permissions for tenant: {$tenant->id}");

            // Switch to tenant context
            tenancy()->initialize($tenant);

            // Clear existing permissions and roles (handle foreign key constraints)
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Permission::truncate();
            Role::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Run the tenant seeder
            $this->call('db:seed', ['--class' => 'TenantSeeder']);

            $this->info("✅ Permissions refreshed for tenant: {$tenant->id}");
        }

        $this->info("🎉 All tenant permissions have been refreshed!");
    }
}
