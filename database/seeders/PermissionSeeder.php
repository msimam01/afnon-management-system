<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds for central domain permissions.
     */
    public function run(): void
    {
        // Create super admin role for central domain
        $roles = [
            'super-admin' => 'Super Administrator with full access to central domain',
        ];

        $createdRoles = [];
        foreach ($roles as $roleName => $description) {
            if (!Role::where('name', $roleName)->where('guard_name', 'web')->exists()) {
                $createdRoles[$roleName] = Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]);
            } else {
                $createdRoles[$roleName] = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            }
        }

        // Comprehensive permissions for central domain
        $permissions = [
            // Dashboard permissions
            'view_superadmin_dashboard',
            
            // User management permissions
            'manage_central_users',
            'create_central_user',
            'read_central_user',
            'update_central_user',
            'delete_central_user',
            'change_central_user_status',
            'view_central_user_profile',
            
            // Tenant management permissions
            'manage_tenants',
            'create_tenant',
            'read_tenant',
            'update_tenant',
            'delete_tenant',
            'change_tenant_status',
            'suspend_tenant',
            'activate_tenant',
            'view_tenant_details',
            'manage_tenant_settings',
            
            // Role and permission management
            'manage_central_roles_permissions',
            'create_central_role',
            'read_central_role',
            'update_central_role',
            'delete_central_role',
            'create_central_permission',
            'read_central_permission',
            'update_central_permission',
            'delete_central_permission',
            'assign_central_permissions',
            
            // Activity logs and monitoring
            'view_central_activity_logs',
            'export_central_activity_logs',
            'view_central_system_statistics',
            'monitor_tenant_activity',
            
            // System settings and configuration
            'manage_central_system_settings',
            'update_system_configuration',
            'manage_central_notifications',
            'view_system_health',
            
            // Reports and analytics
            'view_central_reports',
            'export_central_reports',
            'view_tenant_analytics',
            'view_system_analytics',
            
            // Profile management
            'view_own_profile',
            'update_own_profile',
            'change_own_password',
        ];

        // Create permissions
        foreach (array_unique($permissions) as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Assign all permissions to super admin role
        if (isset($createdRoles['super-admin'])) {
            $createdRoles['super-admin']->syncPermissions($permissions);
        }
    }
}

