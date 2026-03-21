<?php

namespace App\Console\Commands;

use App\Models\Portifolio;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Property;
use App\Models\Post;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Gera o sitemap.xml do site';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gerando sitemap...');

        $sitemap = Sitemap::create();

        // Página inicial
        $sitemap->add(
            Url::create(route('web.home'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // Páginas estáticas
        $staticPages = [
            ['url' => route('web.contact'), 'priority' => 0.8],
            ['url' => route('web.portifolio'), 'priority' => 0.9],
            ['url' => route('web.terms'), 'priority' => 0.9],
            ['url' => route('web.privacy'), 'priority' => 0.9],
            //['url' => route('web.privacy'), 'priority' => 0.9],
            // Adicione outras páginas estáticas aqui
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority($page['priority'])
            );
        }

        // Portifólio (Trabalhos)
        Portifolio::active()
            ->orderBy('updated_at', 'desc')
            ->chunk(100, function ($trabalhos) use ($sitemap) {
                foreach ($trabalhos as $trabalho) {
                    $sitemap->add(
                        Url::create(route('web.portifolio.single', $trabalho->slug))
                            ->setLastModificationDate($trabalho->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                }
            });

        // Posts (se tiver blog)
        if (class_exists(Post::class)) {
        // Artigos
        Post::where('status', 1)
            ->where('type', 'artigo')
            ->orderBy('created_at', 'desc')
            ->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create(route('web.blog.artigo', $post->slug))
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.6)
                    );
                }
            });

        // Páginas
        Post::where('status', 1)
            ->where('type', 'pagina')
            ->orderBy('created_at', 'desc')
            ->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create(route('web.page', $post->slug))
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                            ->setPriority(0.8) // Páginas têm prioridade maior
                    );
                }
            });

        // Notícias
        // Post::where('status', 1)
        //     ->where('type', 'noticia')
        //     ->orderBy('created_at', 'desc')
        //     ->chunk(100, function ($posts) use ($sitemap) {
        //         foreach ($posts as $post) {
        //             $sitemap->add(
        //                 Url::create(route('web.blog.noticia', $post->slug))
        //                     ->setLastModificationDate($post->updated_at)
        //                     ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY) // Notícias mudam mais
        //                     ->setPriority(0.7)
        //             );
        //         }
        //     });
    }

        // Salva o sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap gerado com sucesso em: ' . public_path('sitemap.xml'));
        
        return Command::SUCCESS;
    }
}
