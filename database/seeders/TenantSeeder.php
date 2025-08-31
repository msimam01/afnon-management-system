<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds for tenant-specific data.
     */
    public function run(): void
    {
        // Create default roles for the tenant
        $roles = [
            'system-admin' => 'System Administrator with full access',
            'admin' => 'Administrator with full access',
            'agent' => 'Field agent for data collection',
            'farmer' => 'Farmer user with limited access'
        ];

        $createdRoles = [];
        foreach ($roles as $roleName => $description) {
            if (!Role::where('name', $roleName)->where('guard_name', 'tenant')->exists()) {
                $createdRoles[$roleName] = Role::create([
                    'name' => $roleName,
                    'guard_name' => 'tenant',
                    'tenant_id' => tenant('id')
                ]);
            } else {
                $createdRoles[$roleName] = Role::where('name', $roleName)->where('guard_name', 'tenant')->first();
            }
        }

        // Create permissions
        $permissions = [
            'manage_users',
            'view_activity_logs',
            'manage_roles_permissions',
            'view_admin_dashboard',
            'manage_applications',
            'manage_seasons',
            'manage_commodities',
            'manage_centers',
            'view_reports',
            'manage_verifications',
            'manage_monetary_returns',
            'manage_settings',
            'manage_agents',
            'view_agent_dashboard',
            'verify_collection',
            'verify_return',
            'manage_monetary_return',
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            'change_user_status',
            'create_application',
            'read_application',
            'update_application',
            'delete_application',
            'create_season',
            'read_season',
            'update_season',
            'delete_season',
            'create_commodity',
            'read_commodity',
            'update_commodity',
            'delete_commodity',
            'create_center',
            'read_center',
            'update_center',
            'delete_center',
            'create_role',
            'read_role',
            'update_role',
            'delete_role',
            'create_verification',
            'read_verification',
            'update_verification',
            'delete_verification',
            'create_monetary_return',
            'read_monetary_return',
            'update_monetary_return',
            'delete_monetary_return',
            'create_agent',
            'read_agent',
            'update_agent',
            'delete_agent',
        ];


        foreach (array_unique($permissions) as $permission) {
            Permission::findOrCreate($permission, 'tenant', ['tenant_id' => tenant('id')]);
        }


        // Assign permissions to roles
        if (isset($createdRoles['system-admin'])) {
            $createdRoles['system-admin']->syncPermissions($permissions);
        }

        if (isset($createdRoles['admin'])) {
            $createdRoles['admin']->syncPermissions([
                'view_admin_dashboard',
                'manage_users',
                'manage_applications',
                'manage_seasons',
                'manage_commodities',
                'manage_centers',
                'manage_roles_permissions',
                'view_reports',
                'manage_verifications',
                'manage_monetary_returns',
                'view_activity_logs',
                'manage_settings',
                'manage_agents',
                'create_user',
                'read_user',
                'update_user',
                'delete_user',
                'change_user_status',
                'create_application',
                'read_application',
                'update_application',
                'delete_application',
                'create_season',
                'read_season',
                'update_season',
                'delete_season',
                'create_commodity',
                'read_commodity',
                'update_commodity',
                'delete_commodity',
                'create_center',
                'read_center',
                'update_center',
                'delete_center',
                'create_role',
                'read_role',
                'update_role',
                'delete_role',
                'create_verification',
                'read_verification',
                'update_verification',
                'delete_verification',
                'create_monetary_return',
                'read_monetary_return',
                'update_monetary_return',
                'delete_monetary_return',
                'create_agent',
                'read_agent',
                'update_agent',
                'delete_agent',
                'manage_reports'
            ]);
        }

        if (isset($createdRoles['agent'])) {
            $createdRoles['agent']->syncPermissions([
                'view_agent_dashboard',
                'verify_collection',
                'verify_return',
                'manage_monetary_return',
                'read_monetary_return',
                'create_monetary_return',
                'update_monetary_return',
            ]);
        }

        // Farmer role doesn't need specific permissions as it has limited access

        // Create default settings or other tenant-specific data here
        // Example: Create default commodities, seasons, etc.
    }
}
