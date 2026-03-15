<?php

namespace Database\Factories;

use App\Models\CatPortifolio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CatPortifolio>
 */
class CatPortifolioFactory extends Factory
{
    protected $model = CatPortifolio::class;

    public function definition(): array
    {
        $title = $this->faker->words(3, true);

        return [
            'id_pai'  => null,
            'title'   => ucfirst($title),
            'content' => $this->faker->sentence(),
            'slug'    => Str::slug($title),
            'tags'    => implode(',', $this->faker->words(3)),
            'views'   => $this->faker->numberBetween(0, 1000),
            'type'    => $this->faker->randomElement(['project', 'service', 'product']),
            'status'  => 1,
        ];
    }
}
