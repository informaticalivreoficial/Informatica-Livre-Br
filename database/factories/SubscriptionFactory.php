<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::now()->subMonths(rand(0, 6));

        return [
            'company_id' => Company::inRandomOrder()->value('id'),
            'service_id' => Service::where('billing_type', 'recurring')->inRandomOrder()->value('id'),

            'interval' => $this->faker->randomElement([
                'monthly',
                'quarterly',
                'semiannual',
                'yearly',
            ]),

            'amount' => $this->faker->randomFloat(2, 100, 3000),

            'start_date' => $start,

            'next_billing_at' => $start->copy()->addMonth(),

            'status' => 'active',
        ];
    }
}
