<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this!
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Zone Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create Farmer
        $farmer = User::firstOrCreate(
            ['email' => 'farmer@example.com'],
            [
                'name' => 'John Farmer',
                'password' => Hash::make('password'),
            ]
        );
        $farmer->assignRole('farmer');

        // Create Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Agent Musa',
                'password' => Hash::make('password'),
            ]
        );
        $agent->assignRole('agent');

        // Permission::create(['name' => 'approve application']);
        // Permission::create(['name' => 'verify return']);
        // Permission::create(['name' => 'create collection center']);

        // $superAdmin->givePermissionTo(Permission::all());
        // $admin->givePermissionTo(['approve application', 'verify return']);
        // $agent->givePermissionTo(['verify return']);
    }
    }

