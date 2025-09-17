<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['super-admin', 'admin', 'farmer', 'agent'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Super Admin (System Admin for Central Domain)
        $superAdmin = User::firstOrCreate(
            ['email' => 'system-admin@afnon.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'System Administrator',
                'password' => Hash::make('system-admin123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Zone Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create Farmer
        $farmer = User::firstOrCreate(
            ['email' => 'farmer@example.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'John Farmer',
                'password' => Hash::make('password'),
            ]
        );
        $farmer->assignRole('farmer');

        // Create Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@example.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Agent Musa',
                'password' => Hash::make('password'),
            ]
        );
        $agent->assignRole('agent');

        // Run the PermissionSeeder to create all central domain permissions
        $this->call(PermissionSeeder::class);

        // Super Admin gets all permissions (already assigned in PermissionSeeder)
        // No need to manually assign permissions as PermissionSeeder handles this

        // Create or get additional permissions for other roles
        $manageUsers = Permission::firstOrCreate(['name' => 'manage_users']);
        $manageSeasons = Permission::firstOrCreate(['name' => 'manage_seasons']);
        $verifyReturns = Permission::firstOrCreate(['name' => 'verify_returns']);
        $manageFarmers = Permission::firstOrCreate(['name' => 'manage_farmers']);
        $verifyCollection = Permission::firstOrCreate(['name' => 'verify_collection']);

        // Assign specific permissions to other roles
        $admin->givePermissionTo([$verifyReturns, $manageFarmers, $manageUsers]);
        $agent->givePermissionTo([$verifyCollection]);
        $farmer->givePermissionTo([]); // No specific permission
    }
}
