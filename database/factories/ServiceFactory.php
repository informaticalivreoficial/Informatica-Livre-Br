<?php

namespace Database\Factories;

use App\Models\ServiceCategorie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $billingType = $this->faker->randomElement(['one_time', 'recurring']);

        return [
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'category_id' => ServiceCategorie::inRandomOrder()->value('id'),

            'name' => $this->faker->randomElement([
                'Site institucional',
                'Sistema sob medida',
                'Manutenção mensal',
                'Suporte técnico',
                'Landing Page',
            ]),

            'description' => $this->faker->sentence(10),

            'price' => $this->faker->randomFloat(2, 150, 5000),

            'billing_type' => $billingType,

            'interval' => $billingType === 'recurring'
                ? $this->faker->randomElement(['monthly', 'quarterly', 'semiannual', 'yearly'])
                : null,

            'is_public' => $this->faker->boolean(60),

            'status' => true,
        ];
    }
}
