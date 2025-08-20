<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTenantMigrations extends Command
{
    protected $signature = 'tenant:fix-migrations {tenant_id?} {--all : Fix all failed tenants}';
    protected $description = 'Fix tenant migration issues';

    public function handle()
    {
        if ($this->option('all')) {
            $this->fixAllFailedTenants();
        } else {
            $tenantId = $this->argument('tenant_id');
            if (!$tenantId) {
                $this->error('Please provide a tenant ID or use --all flag');
                return 1;
            }
            $this->fixSingleTenant($tenantId);
        }

        return 0;
    }

    private function fixAllFailedTenants()
    {
        $failedTenants = Tenant::where('status', Tenant::STATUS_FAILED)
            ->orWhere('status', Tenant::STATUS_PENDING)
            ->get();

        if ($failedTenants->isEmpty()) {
            $this->info('No failed tenants found');
            return;
        }

        $this->info("Found {$failedTenants->count()} failed/pending tenants");

        foreach ($failedTenants as $tenant) {
            $this->info("Fixing tenant: {$tenant->id}");
            $this->fixSingleTenant($tenant->id);
        }
    }

    private function fixSingleTenant(string $tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant {$tenantId} not found");
            return;
        }

        try {
            $this->info("🔧 Fixing tenant: {$tenant->id}");

            // Step 1: Ensure database exists
            $this->info("Checking database...");
            $this->ensureDatabaseExists($tenant);

            // Step 2: Check and fix migration issues
            $this->info("Checking migrations...");
            $this->fixMigrationIssues($tenant);

            // Step 3: Run migrations
            $this->info("Running migrations...");
            $this->runMigrations($tenant);

            // Step 4: Verify tables
            $this->info("Verifying tables...");
            $this->verifyTables($tenant);

            // Step 5: Update tenant status
            $tenant->update(['status' => Tenant::STATUS_ACTIVE]);

            $this->info("✅ Tenant {$tenant->id} fixed successfully");

        } catch (\Throwable $e) {
            $this->error("❌ Failed to fix tenant {$tenant->id}: " . $e->getMessage());
            $tenant->markAsFailed("Fix attempt failed: " . $e->getMessage());
        }
    }

    private function ensureDatabaseExists(Tenant $tenant)
    {
        try {
            $tenant->createDatabase();
            $this->info("✅ Database ensured for tenant: {$tenant->id}");
        } catch (\Exception $e) {
            $this->warn("Database creation warning: " . $e->getMessage());
        }
    }

    private function fixMigrationIssues(Tenant $tenant)
    {
        // Initialize tenant context
        tenancy()->initialize($tenant);

        try {
            // Check if migration table exists
            if (!Schema::hasTable('migrations')) {
                $this->info("Creating migrations table...");
                Artisan::call('migrate:install');
            }

            // Check for common migration issues
            $this->checkPermissionTables();

        } finally {
            tenancy()->end();
        }
    }

    private function checkPermissionTables()
    {
        // Check if permission tables have proper structure
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'tenant_id')) {
            $this->info("Adding tenant_id to roles table...");
            Schema::table('roles', function ($table) {
                $table->uuid('tenant_id')->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'tenant_id')) {
            $this->info("Adding tenant_id to permissions table...");
            Schema::table('permissions', function ($table) {
                $table->uuid('tenant_id')->nullable()->after('id')->index();
            });
        }
    }

    private function runMigrations(Tenant $tenant)
    {
        $exitCode = Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
            '--force' => true,
        ]);

        if ($exitCode !== 0) {
            $output = Artisan::output();
            throw new \Exception("Migration failed: " . $output);
        }

        $this->info("✅ Migrations completed");
    }

    private function verifyTables(Tenant $tenant)
    {
        tenancy()->initialize($tenant);

        try {
            $expectedTables = [
                'users', 'roles', 'permissions', 'seasons', 'centers',
                'farmers', 'farms', 'applications', 'commodities'
            ];

            $missingTables = [];
            foreach ($expectedTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }

            if (!empty($missingTables)) {
                $this->warn("Missing tables: " . implode(', ', $missingTables));
            } else {
                $this->info("✅ All essential tables verified");
            }

        } finally {
            tenancy()->end();
        }
    }
}
