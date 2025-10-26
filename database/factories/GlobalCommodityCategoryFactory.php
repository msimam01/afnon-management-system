<?php

namespace Database\Factories;

use App\Models\GlobalCommodityCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlobalCommodityCategoryFactory extends Factory
{
    protected $model = GlobalCommodityCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
