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
            'user' => User::factory(),
            'social_name' => fake()->company,
            'alias_name' => fake()->company,
            'document_company' => fake()->cnpj,
            'information' => fake()->text(200),
            'zipcode' => fake()->postcode,
            'street' => fake()->streetName,
            'number' => fake()->buildingNumber,
            'complement' => fake()->secondaryAddress,
            'neighborhood' => fake()->streetName,
            'state' => fake()->stateAbbr,
            'city' => fake()->city,
            'status' => $this->faker->boolean(),
            'phone' => '(12)' . fake()->phoneNumber,
            'cell_phone' => '(12)' . fake()->cellphone,
            'whatsapp' => '(12)' . fake()->cellphone,
            'email' => fake()->unique()->safeEmail(),
            'additional_email' => fake()->safeEmail()
        ];
    }
}
