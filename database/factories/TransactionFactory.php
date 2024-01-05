<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use App\Constants\TransactionConstants;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'asset_id' => Asset::factory(),
            'description' => $this->faker->text(50),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement([
                TransactionConstants::DEBIT,
                TransactionConstants::CREDIT
            ]),
            'value' => $this->faker->randomFloat(2, 0, 1000000),
            'asset_total_value' => $this->faker->randomFloat(2, 0, 1000000),
        ];
    }
}
