<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:seed {--central : Seed central domain permissions} {--tenant : Seed tenant permissions} {--all : Seed all permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permissions for central and tenant domains';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌱 Starting permission seeding...');

        if ($this->option('all') || $this->option('central')) {
            $this->info('📋 Seeding central domain permissions...');
            Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
            $this->info('✅ Central domain permissions seeded successfully');
        }

        if ($this->option('all') || $this->option('tenant')) {
            $this->info('📋 Seeding tenant permissions...');

            // Get all tenants
            $tenants = \App\Models\SuperAdmin\Tenant::where('status', 'active')->get();

            if ($tenants->isEmpty()) {
                $this->warn('⚠️  No active tenants found. Creating a test tenant...');

                // Create a test tenant for demonstration
                $tenant = \App\Models\SuperAdmin\Tenant::create([
                    'id' => 'test-tenant',
                    'data' => ['name' => 'Test Tenant'],
                ]);

                // Initialize tenancy and seed
                tenancy()->initialize($tenant);
                Artisan::call('db:seed', ['--class' => 'TenantSeeder', '--force' => true]);
                tenancy()->end();

                $this->info('✅ Test tenant created and permissions seeded');
            } else {
                foreach ($tenants as $tenant) {
                    $this->info("📋 Seeding permissions for tenant: {$tenant->id}");

                    // Initialize tenancy and seed
                    tenancy()->initialize($tenant);
                    Artisan::call('db:seed', ['--class' => 'TenantSeeder', '--force' => true]);
                    tenancy()->end();

                    $this->info("✅ Permissions seeded for tenant: {$tenant->id}");
                }
            }
        }

        $this->info('🎉 Permission seeding completed successfully!');

        // Display summary
        $this->newLine();
        $this->info('📊 Permission Summary:');
        $this->line('Central Domain: Super Admin role with full permissions');
        $this->line('Tenant Domain: Admin, Agent, Farmer roles with appropriate permissions');
        $this->line('Sidebar: Reorganized by importance for each user type');
    }
}
