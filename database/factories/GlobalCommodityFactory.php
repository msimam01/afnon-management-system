<?php

namespace Database\Factories;

use App\Models\GlobalCommodity;
use App\Models\GlobalCommodityCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlobalCommodityFactory extends Factory
{
    protected $model = GlobalCommodity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'category_id' => GlobalCommodityCategory::factory(),
            'type' => $this->faker->randomElement(['seed', 'fertilizer', 'equipment']),
            'unit' => $this->faker->randomElement(['kg', 'liters', 'pieces']),
            'price_per_unit' => $this->faker->randomFloat(2, 10, 1000),
            'quantity_per_hectare' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
