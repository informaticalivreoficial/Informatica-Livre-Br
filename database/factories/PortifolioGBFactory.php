<?php

namespace Database\Factories;

use App\Models\Portifolio;
use App\Models\PortifolioGB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortifolioGB>
 */
class PortifolioGBFactory extends Factory
{
    protected $model = PortifolioGB::class;

    public function definition(): array
    {
        return [
            'portifolio' => Portifolio::inRandomOrder()->first()?->id ?? Portifolio::factory(),
            'path'       => 'portifolio/' . $this->faker->uuid() . '.jpg',
            'cover'      => false,
        ];
    }

    public function cover(): static
    {
        return $this->state(['cover' => true]);
    }
}
