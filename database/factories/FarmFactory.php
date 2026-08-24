<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farm>
 */
class FarmFactory extends Factory
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
            'farmer_id' => Farmer::factory(),
            'location' => $this->faker->city,
            'size' => $this->faker->randomFloat(1, 1, 10),
            // 'cluster' => $this->faker->word,
        ];
    }
}
