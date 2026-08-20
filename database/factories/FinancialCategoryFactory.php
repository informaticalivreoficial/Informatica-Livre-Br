<?php

namespace Database\Factories;

use App\Enums\FinancialType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialCategory>
 */
class FinancialCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'type' => FinancialType::EXPENSE->value,
            'color' => '#00ff41',
            'active' => true,
        ];
    }

    public function income(): static
    {
        return $this->state(fn () => ['type' => FinancialType::INCOME->value]);
    }

    public function expense(): static
    {
        return $this->state(fn () => ['type' => FinancialType::EXPENSE->value]);
    }
}