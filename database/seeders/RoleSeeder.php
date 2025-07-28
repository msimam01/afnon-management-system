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

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this!
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

        // Create or get permissions safely
        $manageUsers = Permission::firstOrCreate(['name' => 'manage_users']);
        $manageSeasons = Permission::firstOrCreate(['name' => 'manage_seasons']);
        $verifyReturns = Permission::firstOrCreate(['name' => 'verify_returns']);
        $manageFarmers = Permission::firstOrCreate(['name' => 'manage_farmers']);
        $verifyCollection = Permission::firstOrCreate(['name' => 'verify_collection']);


        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo([$verifyReturns, $manageFarmers, $manageUsers]);
        $agent->givePermissionTo([$verifyCollection]);
        $farmer->givePermissionTo([]); // No specific permission
    }
    }

