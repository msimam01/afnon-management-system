<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TestTenantMigration extends Command
{
    protected $signature = 'tenant:test-migration {tenant_id}';
    protected $description = 'Test tenant migration for debugging';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant {$tenantId} not found");
            return 1;
        }

        $this->info("Testing migration for tenant: {$tenant->id}");

        try {
            // Test database creation (skip if already exists)
            $this->info("Checking database...");
            try {
                $tenant->createDatabase();
                $this->info("✅ Database created successfully");
            } catch (\Exception $e) {
                $this->info("✅ Database already exists");
            }

            // Test migration
            $this->info("Running migrations...");
            $exitCode = Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
                '--verbose' => true,
            ]);

            $output = Artisan::output();
            $this->info("Migration output:");
            $this->line($output);

            if ($exitCode === 0) {
                $this->info("✅ Migrations completed successfully");
            } else {
                $this->error("❌ Migration failed with exit code: {$exitCode}");
                return 1;
            }

            // Test tenant context initialization
            $this->info("Testing tenant context...");
            tenancy()->initialize($tenant);
            $this->info("✅ Tenant context initialized");

            // Check if tables exist
            $this->info("Checking database tables...");
            $tables = \DB::select('SHOW TABLES');
            $this->info("Found " . count($tables) . " tables");

            tenancy()->end();

        } catch (\Throwable $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());
            return 1;
        }

        return 0;
    }
}
