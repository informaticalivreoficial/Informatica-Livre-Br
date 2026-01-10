<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceCategorie>
 */
class ServiceCategorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Desenvolvimento',
            'Manutenção',
            'Hospedagem',
            'Consultoria',
            'Design',
        ]);

        return [
            'name'   => $name,
            'slug'   => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'status' => true,
        ];
    }
}
