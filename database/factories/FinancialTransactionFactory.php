<?php

namespace Database\Factories;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialTransaction>
 */
class FinancialTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => FinancialType::EXPENSE->value,
            'year' => now()->year,
            'month' => $this->faker->numberBetween(1, 12),
            'amount' => $this->faker->randomFloat(2, 50, 3000),
            'status' => FinancialStatus::PENDING->value,
        ];
    }

    public function expense(): static
    {
        return $this->state(fn () => ['type' => FinancialType::EXPENSE->value]);
    }

    public function income(): static
    {
        return $this->state(fn () => ['type' => FinancialType::INCOME->value]);
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => FinancialStatus::PAID->value,
            'paid_at' => now(),
        ]);
    }

    public function received(): static
    {
        return $this->state(fn () => [
            'status' => FinancialStatus::RECEIVED->value,
            'received_at' => now(),
        ]);
    }
}