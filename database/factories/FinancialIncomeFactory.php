<?php

namespace Database\Factories;

use App\Models\FinancialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialIncome>
 */
class FinancialIncomeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->words(2, true),
            'receive_day' => $this->faker->numberBetween(1, 28),
            'amount' => $this->faker->randomFloat(2, 50, 5000),
            'notes' => null,
            'active' => true,
        ];
    }
}