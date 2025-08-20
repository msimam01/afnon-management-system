<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\SuperAdmin\Tenant;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CreateTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;
    public $timeout = 300; // 5 minutes timeout
    public $tries = 3; // Retry 3 times on failure
    public $backoff = [30, 60, 120]; // Backoff delays in seconds

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("🚀 Starting tenant setup for: {$this->tenant->id}");

            // Step 1: Create tenant database
            $this->createTenantDatabase();

            // Step 2: Run tenant migrations
            $this->runTenantMigrations();

            // Step 3: Initialize tenant context
            $this->initializeTenantContext();

            // Step 4: Create default roles and permissions
            $this->createDefaultRolesAndPermissions();

            // Step 5: Create default admin user
            $this->createDefaultAdminUser();

            // Step 6: Seed essential data
            $this->seedEssentialData();

            // Step 7: Mark tenant as active
            $this->tenant->activate();

            Log::info("✅ Tenant setup completed successfully for: {$this->tenant->id}");

        } catch (\Throwable $e) {
            $this->handleFailure($e);
        } finally {
            // Always end tenancy context
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    /**
     * Create tenant database
     */
    private function createTenantDatabase(): void
    {
        Log::info("📦 Creating database for tenant: {$this->tenant->id}");

        $dbName = 'tenant_' . $this->tenant->id;
        $connection = config('tenancy.database.central_connection');
        
        try {
            // Try to create database manually
            \DB::connection($connection)->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            Log::info("✅ Database created/verified for tenant: {$this->tenant->id}");
        } catch (\Exception $e) {
            Log::error("❌ Failed to create database for tenant: {$this->tenant->id}", [
                'error' => $e->getMessage()
            ]);
            throw new \Exception("Failed to create database: " . $e->getMessage());
        }
    }

    /**
     * Run tenant migrations
     */
    private function runTenantMigrations(): void
    {
        Log::info("📦 Running migrations for tenant: {$this->tenant->id}");

        $exitCode = Artisan::call('tenants:migrate', [
            '--tenants' => [$this->tenant->id],
            '--force' => true,
        ]);

        if ($exitCode !== 0) {
            $output = Artisan::output();
            Log::error("❌ Migration failed for tenant: {$this->tenant->id}", [
                'exit_code' => $exitCode,
                'output' => $output
            ]);
            throw new \Exception("Migration failed with exit code: {$exitCode}. Output: {$output}");
        }

        Log::info("✅ Migrations completed for tenant: {$this->tenant->id}");
    }


    /**
     * Initialize tenant context safely
     */
    private function initializeTenantContext(): void
    {
        if (!tenancy()->initialized) {
            tenancy()->initialize($this->tenant);
            Log::info("🔐 Initialized tenant context for: {$this->tenant->id}");
        }
    }

    /**
     * Create default roles and permissions
     */
    private function createDefaultRolesAndPermissions(): void
    {
        Log::info("👥 Creating default roles for tenant: {$this->tenant->id}");

        $defaultRoles = [
            'admin' => 'Administrator with full access',
            'agent' => 'Field agent for data collection',
            'farmer' => 'Farmer user with limited access'
        ];

        foreach ($defaultRoles as $roleName => $description) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    'tenant_id' => tenant('id')
                ]);
                Log::info("✅ Created role: {$roleName}");
            }
        }
    }

    /**
     * Create default admin user
     */
    private function createDefaultAdminUser(): void
    {
        Log::info("👤 Creating default admin user for tenant: {$this->tenant->id}");

        $adminEmail = "admin@{$this->tenant->id}.afnon.com";

        // Check if admin user already exists
        if (!User::where('email', $adminEmail)->exists()) {
            $user = User::create([
                'name' => 'System Administrator',
                'email' => $adminEmail,
                'password' => bcrypt('admin123'), // Should be changed on first login
                'status' => 'active',
            ]);

            $user->assignRole('admin');
            Log::info("✅ Created admin user: {$adminEmail}");
        }
    }

    /**
     * Seed essential data for the tenant
     */
    private function seedEssentialData(): void
    {
        Log::info("🌱 Seeding essential data for tenant: {$this->tenant->id}");

        // Run tenant-specific seeders if they exist
        try {
            $exitCode = Artisan::call('tenants:seed', [
                '--tenants' => [$this->tenant->id],
                '--class' => 'DatabaseSeeder',
            ]);

            if ($exitCode === 0) {
                Log::info("✅ Seeding completed for tenant: {$this->tenant->id}");
            } else {
                Log::warning("⚠️ Seeding returned non-zero exit code for tenant {$this->tenant->id}");
            }
        } catch (\Exception $e) {
            // Seeding is optional, log but don't fail
            Log::warning("⚠️ Seeding failed for tenant {$this->tenant->id}: {$e->getMessage()}");
        }
    }

    /**
     * Handle job failure
     */
    private function handleFailure(\Throwable $e): void
    {
        $errorMessage = "Tenant setup failed: {$e->getMessage()}";

        Log::error("❌ {$errorMessage}", [
            'tenant_id' => $this->tenant->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        // Mark tenant as failed
        $this->tenant->markAsFailed($errorMessage);

        // Re-throw to trigger job retry mechanism
        throw $e;
    }

    /**
     * Handle job failure after all retries
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("❌ Tenant setup permanently failed for: {$this->tenant->id}", [
            'error' => $exception->getMessage(),
            'attempts' => $this->tries
        ]);

        // Mark tenant as permanently failed
        $this->tenant->markAsFailed("Permanent failure after {$this->tries} attempts: {$exception->getMessage()}");
    }
}
