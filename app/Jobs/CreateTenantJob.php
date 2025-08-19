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
        // Use a dedicated queue for tenant creation to avoid blocking other jobs
        $this->onQueue('tenant-creation');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenantId = $this->tenant->id;

        try {
            Log::info("🚀 Starting tenant creation for: {$tenantId}");

            // Step 1: Run tenant migrations with memory optimization
            $this->runTenantMigrations();

            // Step 2: Initialize tenant context with error handling
            $this->initializeTenantContext();

            // Step 3: Create default roles and permissions
            $this->createDefaultRolesAndPermissions();

            // Step 4: Create default admin user
            $this->createDefaultAdminUser();

            // Step 5: Seed essential data (if needed)
            $this->seedEssentialData();

            // Step 6: Mark tenant as active
            $this->tenant->activate();

            Log::info("✅ Tenant {$tenantId} setup completed successfully");

        } catch (\Throwable $e) {
            $this->handleFailure($e);
        } finally {
            // Always end tenancy context to prevent memory leaks
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    /**
     * Run tenant migrations with memory optimization
     */
    private function runTenantMigrations(): void
    {
        Log::info("📦 Running migrations for tenant: {$this->tenant->id}");

        // Use Artisan command with memory optimization
        Artisan::call('tenants:migrate', [
            '--tenants' => [$this->tenant->id],
            '--force' => true,
        ]);

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
            Artisan::call('tenants:seed', [
                '--tenants' => [$this->tenant->id],
                '--class' => 'TenantSeeder', // Create this seeder if needed
            ]);
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
        $errorMessage = "Tenant creation failed: {$e->getMessage()}";

        Log::error("❌ {$errorMessage}", [
            'tenant_id' => $this->tenant->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
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
        Log::error("❌ Tenant creation permanently failed for: {$this->tenant->id}", [
            'error' => $exception->getMessage()
        ]);

        // Mark tenant as permanently failed
        $this->tenant->markAsFailed("Permanent failure after {$this->tries} attempts: {$exception->getMessage()}");
    }
}
