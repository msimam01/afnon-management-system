<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

            public function run(): void
    {
        // Create default roles for the tenant
        $roles = [
            'super-admin' => 'Super Administrator with full access',
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

        // Create permissions
        $permissions = [
            'manage_central_users',
            'view_central_activity_logs',
            'manage_central_roles_permissions',
            'manage_tenants',
            'create_user',
            'read_user',
            'update_user',
            'delete_user',
            'change_user_status',
            'create_tenant',
            'read_tenant',
            'update_tenant',
            'delete_tenant',
            'change_tenant_status',
            'create_role',
                'read_role',
                'update_role',
                'delete_role',
                'manage_central_system_settings'
        ];


        foreach (array_unique($permissions) as $permission) {
            Permission::findOrCreate($permission, 'web');
        }


        // Assign permissions to roles
        if (isset($createdRoles['super-admin'])) {
            $createdRoles['super-admin']->syncPermissions($permissions);
        }

        
    }
    }

