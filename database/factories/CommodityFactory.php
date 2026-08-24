<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commodity>
 */
class CommodityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Maize Seeds', 'Rice Seeds', 'Fertilizer']),
            'category' => $this->faker->randomElement(['Seed', 'Fertilizer']),
            'type' => 'Input',
            'unit' => $this->faker->randomElement(['bags', 'kg', 'litres']),
            'price_per_unit' => 5000, // ✅ Provide a valid value
            'quantity_per_hectare' => 2,
            'stock' => 20000,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
