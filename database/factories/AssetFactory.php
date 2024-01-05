<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'portfolio_id' => Portfolio::factory(),
            'name' => $this->faker->name,
            'value' => $this->faker->randomFloat(2, 0, 1000000),
            'acquisition_date' => $this->faker->date(),
        ];
    }
}
