<?php

namespace Database\Seeders;

use App\Models\CatPortifolio;
use App\Models\Portifolio;
use App\Models\PortifolioGB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortifolioSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 5 categorias
        $categories = CatPortifolio::factory(5)->create();

        // Para cada categoria cria 3 portfólios
        $categories->each(function ($category) {
            $portifolios = Portifolio::factory(3)->create([
                'category' => $category->id,
            ]);

            // Para cada portfólio cria imagens
            $portifolios->each(function ($portifolio) {
                // 1 imagem de capa
                PortifolioGB::factory()->cover()->create([
                    'portifolio' => $portifolio->id,
                ]);

                // 3 imagens normais
                PortifolioGb::factory(3)->create([
                    'portifolio' => $portifolio->id,
                ]);
            });
        });

        $this->command->info('✅ Portfólio seedado com sucesso!');
    }
}
