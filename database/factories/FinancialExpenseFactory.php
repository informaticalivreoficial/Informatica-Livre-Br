<?php

namespace Database\Factories;

use App\Models\FinancialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialExpense>
 */
class FinancialExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->words(2, true),
            'due_day' => $this->faker->numberBetween(1, 28),
            'notes' => null,
            'active' => true,
        ];
    }
}