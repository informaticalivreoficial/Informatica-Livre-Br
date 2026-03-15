<?php

namespace Database\Factories;

use App\Models\CatPortifolio;
use App\Models\Company;
use App\Models\Portifolio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portifolio>
 */
class PortifolioFactory extends Factory
{
    protected $model = Portifolio::class;

    public function definition(): array
    {
        $name = $this->faker->bs();

        return [
            'category'      => CatPortifolio::inRandomOrder()->first()?->id ?? CatPortifolio::factory(),
            'company'       => Company::inRandomOrder()->first()?->id ?? 1,
            'name'          => ucfirst($name),
            'content'       => $this->faker->paragraphs(3, true),
            'link'          => $this->faker->url(),
            'slug'          => Str::slug($name),
            'headline'      => $this->faker->sentence(),
            'tags'          => implode(',', $this->faker->words(4)),
            'views'         => $this->faker->numberBetween(0, 5000),
            'cat_pai'       => null,
            'status'        => 1,
            'exibir'        => 1,
            'thumb_legenda' => $this->faker->sentence(4),
            'value'         => $this->faker->randomFloat(2, 500, 10000),
            'data_inicio'   => $this->faker->dateTimeBetween('-2 years', '-6 months'),
            'data_termino'  => $this->faker->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
