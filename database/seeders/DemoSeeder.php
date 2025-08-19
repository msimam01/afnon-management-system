<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create 5 commodities
        Commodity::factory()->count(5)->create();

        // Create 2 seasons
        Season::factory()->count(2)->create();

        // Create 50 farmers with farms
        Farmer::factory()
            ->count(50)
            ->has(Farm::factory()->count(1))
            ->create();
    }
}
