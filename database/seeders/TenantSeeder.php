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
            'admin' => 'Administrator with full access',
            'agent' => 'Field agent for data collection',
            'farmer' => 'Farmer user with limited access'
        ];

        $createdRoles = [];
        foreach ($roles as $roleName => $description) {
            if (!Role::where('name', $roleName)->where('guard_name', 'tenant')->exists()) {
                $createdRoles[$roleName] = Role::create([
                    'tenant_id' => tenant('id'),
                    'name' => $roleName,
                    'guard_name' => 'tenant'
                ]);
            } else {
                $createdRoles[$roleName] = Role::where('name', $roleName)->where('guard_name', 'tenant')->first();
            }
        }

        // Create comprehensive permissions for tenant domain
        $permissions = [
            // Dashboard permissions
            'view_admin_dashboard',
            'view_agent_dashboard',
            'view_farmer_dashboard',

            // User management permissions
            'manage_users',
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            'change_user_status',
            'view_user_profile',
            'bulk_user_actions',

            // Agent management permissions
            'manage_agents',
            'create_agent',
            'read_agent',
            'update_agent',
            'delete_agent',
            'assign_agent_tasks',
            'view_agent_performance',

            // Application management permissions
            'manage_applications',
            'create_application',
            'read_application',
            'update_application',
            'delete_application',
            'approve_application',
            'reject_application',
            'bulk_approve_applications',
            'bulk_reject_applications',
            'view_application_details',
            'export_applications',

            // Season management permissions
            'manage_seasons',
            'create_season',
            'read_season',
            'update_season',
            'delete_season',
            'close_season',
            'reopen_season',
            'export_season_data',

            // Commodity management permissions
            'manage_commodities',
            'create_commodity',
            'read_commodity',
            'update_commodity',
            'delete_commodity',
            'manage_commodity_categories',
            'manage_market_prices',
            'add_commodity_market_price',
            'update_commodity_market_price',
            'view_commodity_market_prices',

            // Center management permissions
            'manage_centers',
            'create_center',
            'read_center',
            'update_center',
            'delete_center',
            'manage_collection_centers',
            'manage_returning_centers',

            // Verification permissions
            'manage_verifications',
            'create_verification',
            'read_verification',
            'update_verification',
            'delete_verification',
            'verify_collection',
            'verify_return',
            'bulk_verify_items',

            // Admin verification review permissions
            'review_collection_verifications',
            'review_return_verifications',
            'approve_verification',
            'reject_verification',
            'bulk_approve_verifications',
            'bulk_reject_verifications',

            // Monetary return permissions
            'manage_monetary_returns',
            'create_monetary_return',
            'read_monetary_return',
            'update_monetary_return',
            'delete_monetary_return',
            'verify_monetary_return',
            'reject_monetary_return',
            'manage_monetary_return',

            // Role and permission management
            'manage_roles_permissions',
            'create_role',
            'read_role',
            'update_role',
            'delete_role',
            'create_permission',
            'read_permission',
            'update_permission',
            'delete_permission',
            'assign_permissions',

            // Reports and analytics
            'manage_reports',
            'view_reports',
            'export_reports',
            'view_analytics',
            'export_analytics',
            'view_application_reports',
            'view_verification_reports',
            'view_financial_reports',

            // Activity logs
            'view_activity_logs',
            'export_activity_logs',
            'view_system_statistics',

            // Settings management
            'manage_settings',
            'update_system_settings',
            'manage_notifications',
            'view_system_health',

            // Profile management
            'view_own_profile',
            'update_own_profile',
            'change_own_password',
        ];


        foreach (array_unique($permissions) as $permission) {
            Permission::firstOrCreate([
                'tenant_id' => tenant('id'),
                'name' => $permission,
                'guard_name' => 'tenant'
            ]);
        }


        // Assign permissions to roles based on importance and functionality

        // Admin gets comprehensive permissions for tenant management
        if (isset($createdRoles['admin'])) {
            $adminPermissions = [
                // Core dashboard and profile
                'view_admin_dashboard',
                'view_own_profile',
                'update_own_profile',
                'change_own_password',

                // User management (full access)
                'manage_users',
                'create_user',
                'read_user',
                'update_user',
                'delete_user',
                'change_user_status',
                'view_user_profile',
                'bulk_user_actions',

                // Agent management (full access)
                'manage_agents',
                'create_agent',
                'read_agent',
                'update_agent',
                'delete_agent',
                'assign_agent_tasks',
                'view_agent_performance',

                // Application management (full access)
                'manage_applications',
                'create_application',
                'read_application',
                'update_application',
                'delete_application',
                'approve_application',
                'reject_application',
                'bulk_approve_applications',
                'bulk_reject_applications',
                'view_application_details',
                'export_applications',

                // Season management (full access)
                'manage_seasons',
                'create_season',
                'read_season',
                'update_season',
                'delete_season',
                'close_season',
                'reopen_season',
                'export_season_data',

                // Commodity management (full access)
                'manage_commodities',
                'create_commodity',
                'read_commodity',
                'update_commodity',
                'delete_commodity',
                'manage_commodity_categories',
                'manage_market_prices',
                'add_commodity_market_price',
                'update_commodity_market_price',
                'view_commodity_market_prices',

                // Center management (full access)
                'manage_centers',
                'create_center',
                'read_center',
                'update_center',
                'delete_center',
                'manage_collection_centers',
                'manage_returning_centers',

                // Verification management (admin can manage and review verifications)
                'manage_verifications',
                'create_verification',
                'read_verification',
                'update_verification',
                'delete_verification',
                'bulk_verify_items',

                // Admin verification review permissions
                'review_collection_verifications',
                'review_return_verifications',
                'approve_verification',
                'reject_verification',
                'bulk_approve_verifications',
                'bulk_reject_verifications',

                // Monetary return management (full access)
                'manage_monetary_returns',
                'create_monetary_return',
                'read_monetary_return',
                'update_monetary_return',
                'delete_monetary_return',
                'verify_monetary_return',
                'reject_monetary_return',
                'manage_monetary_return',

                // Role and permission management (full access)
                'manage_roles_permissions',
                'create_role',
                'read_role',
                'update_role',
                'delete_role',
                'create_permission',
                'read_permission',
                'update_permission',
                'delete_permission',
                'assign_permissions',

                // Reports and analytics (full access)
                'manage_reports',
                'view_reports',
                'export_reports',
                'view_analytics',
                'export_analytics',
                'view_application_reports',
                'view_verification_reports',
                'view_financial_reports',

                // Activity logs (full access)
                'view_activity_logs',
                'export_activity_logs',
                'view_system_statistics',

                // Settings management (full access)
                'manage_settings',
                'update_system_settings',
                'manage_notifications',
                'view_system_health',
            ];

            $createdRoles['admin']->syncPermissions($adminPermissions);
        }

        // Agent gets limited permissions for field operations
        if (isset($createdRoles['agent'])) {
            $agentPermissions = [
                // Core dashboard and profile
                'view_agent_dashboard',
                'view_own_profile',
                'update_own_profile',
                'change_own_password',

                // Verification tasks (primary function)
                'verify_collection',
                'verify_return',
                'read_verification',
                'update_verification',

                // Monetary return tasks
                'manage_monetary_return',
                'create_monetary_return',
                'read_monetary_return',
                'update_monetary_return',

                // Limited application viewing
                'read_application',
                'view_application_details',

                // Limited reports for own activities
                'view_reports',
                'export_reports',
            ];

            $createdRoles['agent']->syncPermissions($agentPermissions);
        }

        // Farmer gets minimal permissions for application submission
        if (isset($createdRoles['farmer'])) {
            $farmerPermissions = [
                // Core dashboard and profile
                'view_farmer_dashboard',
                'view_own_profile',
                'update_own_profile',
                'change_own_password',

                // Application submission only
                'create_application',
                'read_application',
                'view_application_details',
            ];

            $createdRoles['farmer']->syncPermissions($farmerPermissions);
        }

        // Create default settings or other tenant-specific data here
        // Example: Create default commodities, seasons, etc.
    }
}
