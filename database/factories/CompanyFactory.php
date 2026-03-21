<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'responsable_name' => $this->faker->name(),
            'responsable_email' => $this->faker->email,
            'social_name' => $this->faker->company,
            'alias_name' => $this->faker->companySuffix,
            'document_company' => $this->faker->cnpj(false),
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => true,
        ];
    }
}
