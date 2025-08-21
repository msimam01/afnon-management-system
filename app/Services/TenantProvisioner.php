<?php

namespace App\Services;

use App\Models\SuperAdmin\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class TenantProvisioner
{
    /**
     * Provision a tenant synchronously. Throws on failure.
     */
    public static function provision(Tenant $tenant): void
    {
        Log::info("[TenantProvisioner] Starting provisioning for tenant {$tenant->id}");

        try {
            // Ensure tenant database exists
            self::ensureTenantDatabase($tenant);

            // Run tenant migrations
            self::runMigrations($tenant);

            // Initialize tenant context
            if (!tenancy()->initialized) {
                tenancy()->initialize($tenant);
            }

            // Create roles
            self::ensureRoles();

            // Create default admin
            self::ensureDefaultAdmin($tenant);

            // Optional seeds (tenant DB)
            self::runSeeders($tenant);

            // Activate tenant in central DB
            $tenant->activate();

            Log::info("[TenantProvisioner] Provisioning completed for tenant {$tenant->id}");
        } catch (\Throwable $e) {
            Log::error('[TenantProvisioner] Provisioning failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $tenant->markAsFailed($e->getMessage());
            throw $e;
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    private static function ensureTenantDatabase(Tenant $tenant): void
    {
        $prefix = config('tenancy.database.prefix', 'tenant_');
        $suffix = config('tenancy.database.suffix', '');
        $dbName = $prefix . $tenant->id . $suffix;
        $connection = config('tenancy.database.central_connection');

        Log::info("[TenantProvisioner] Ensuring database exists: {$dbName} (conn: {$connection})");
        try {
            DB::connection($connection)
                ->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (\Throwable $e) {
            throw new \RuntimeException("Failed to create tenant database '{$dbName}': " . $e->getMessage(), 0, $e);
        }
    }

    private static function runMigrations(Tenant $tenant): void
    {
        Log::info("[TenantProvisioner] Running tenant migrations for {$tenant->id}");
        $params = [
            '--tenants' => [$tenant->id],
            '--force' => true,
        ];
        $exitCode = Artisan::call('tenants:migrate', $params);

        $output = Artisan::output();
        Log::info("[TenantProvisioner] tenants:migrate exit={$exitCode} output=\n{$output}");
        if ($exitCode !== 0) {
            throw new \RuntimeException('Tenant migrations failed: ' . $output);
        }
        Log::info("[TenantProvisioner] Migrations completed for {$tenant->id}");

        // Verify critical tables exist in tenant DB
        tenancy()->initialize($tenant);
        try {
            $missing = [];
            foreach (['users', 'roles', 'permissions', 'model_has_roles', 'model_has_permissions'] as $tbl) {
                if (!Schema::hasTable($tbl)) {
                    $missing[] = $tbl;
                }
            }
            if ($missing) {
                Log::warning('[TenantProvisioner] Missing tables after tenants:migrate, attempting migrate:fresh', [
                    'tenant_id' => $tenant->id,
                    'missing' => $missing,
                ]);
                tenancy()->end();

                // Try migrate:fresh for this tenant
                $freshParams = [
                    '--tenants' => [$tenant->id],
                    '--force' => true,
                ];
                $freshExit = Artisan::call('tenants:migrate:fresh', $freshParams);
                $freshOut = Artisan::output();
                Log::info("[TenantProvisioner] tenants:migrate:fresh exit={$freshExit} output=\n{$freshOut}");
                if ($freshExit !== 0) {
                    throw new \RuntimeException('Tenant migrate:fresh failed: ' . $freshOut);
                }

                // Reinitialize and recheck
                tenancy()->initialize($tenant);
                $missing = [];
                foreach (['users', 'roles', 'permissions', 'model_has_roles', 'model_has_permissions'] as $tbl) {
                    if (!Schema::hasTable($tbl)) {
                        $missing[] = $tbl;
                    }
                }
                if ($missing) {
                    throw new \RuntimeException('Missing tenant tables even after migrate:fresh: ' . implode(', ', $missing));
                }
            }
        } finally {
            tenancy()->end();
        }
    }

    private static function ensureRoles(): void
    {
        $roles = ['admin', 'agent', 'farmer'];
        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create([
                    'name' => $role,
                    'guard_name' => 'web',
                ]);
                Log::info("[TenantProvisioner] Created role {$role}");
            }
        }
    }

    private static function ensureDefaultAdmin(Tenant $tenant): void
    {
        $email = "admin@{$tenant->id}.afnon.com";
        if (!User::where('email', $email)->exists()) {
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'System Administrator',
                'email' => $email,
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
                'status' => 'active',
            ]);

            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
            }

            Log::info("[TenantProvisioner] Created default admin {$email}");
        }
    }

    private static function runSeeders(Tenant $tenant): void
    {
        try {
            $exitCode = Artisan::call('tenants:seed', [
                '--tenants' => [$tenant->id],
                '--class' => 'DatabaseSeeder',
            ]);
            if ($exitCode !== 0) {
                Log::warning("[TenantProvisioner] Seeder returned non-zero for {$tenant->id}: " . Artisan::output());
            }
        } catch (\Throwable $e) {
            Log::warning("[TenantProvisioner] Seeder failed for {$tenant->id}: {$e->getMessage()}");
        }
    }
}
