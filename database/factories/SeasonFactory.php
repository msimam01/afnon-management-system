<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Season>
 */
class SeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->randomElement(['2024 Dry Season', '2024 Wet Season']),
            'start_date' => now()->subDays(rand(10, 30)),
            'end_date' => now()->addDays(rand(30, 90)),
            'budget' => $this->faker->numberBetween(10000000, 50000000),
            'status' => 'open',
            'commodities' => json_encode(['maize', 'fertilizer']),
        ];
    }
}
