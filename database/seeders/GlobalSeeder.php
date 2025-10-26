<?php

namespace Database\Seeders;

use App\Models\GlobalCommodity;
use App\Models\GlobalCommodityCategory;
use App\Models\GlobalSeason;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GlobalSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = GlobalCommodityCategory::factory()->count(3)->create();

        // Create commodities
        GlobalCommodity::factory()->count(5)->create();

        // Create a super-admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'uuid' => Str::uuid(),
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ])->assignRole('super-admin');
        }
    }
}
