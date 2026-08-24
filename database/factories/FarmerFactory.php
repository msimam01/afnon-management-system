<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farmer>
 */
class FarmerFactory extends Factory
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
            'registration_number' => $this->faker->unique()->numerify('AD#######'),
            'full_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'nin' => $this->faker->numerify('###########'),
            'bvn' => $this->faker->numerify('###########'),
            'state' => 'Kaduna',
            'lga' => 'Igabi',
            'address' => $this->faker->address,
            'cluster' => $this->faker->word,
            // 'farm_size' => $this->faker->randomFloat(1, 0.5, 5),
            // 'farm_location' => $this->faker->city,
            'username' => null,
            'default_password' => null,
        ];
    }
}
