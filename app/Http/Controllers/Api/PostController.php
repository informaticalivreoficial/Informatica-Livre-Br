<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostGb;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;

class PostController extends Controller
{
    private array $categoryMap = [
        'Gestão'                      => ['id' => 2, 'pai' => 1],
        'Segurança Digital'           => ['id' => 5, 'pai' => 1],
        'Desenvolvimento de Software' => ['id' => 6, 'pai' => 1],
        'Negócios'                    => ['id' => 7, 'pai' => 1],
        'Vendas Online'               => ['id' => 8, 'pai' => 1],
        'Marketing'                   => ['id' => 9, 'pai' => 1],
        'Geral'                       => ['id' => 10, 'pai' => 1],
    ];

    public function store(Request $request)
    {
        if (!hash_equals((string) config('app.api_token'), (string) $request->bearerToken())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 🔐 validação básica
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'type'            => 'required|string',
            'category'        => 'nullable|string',
            'metaDescription' => 'nullable|string|max:255',
            'excerpt'         => 'nullable|string',
            'tags'            => 'nullable|string',
            'readingTime'     => 'nullable|integer',
            'imageUrl'        => 'nullable|string',
        ]);

        $categoryName = $data['category'] ?? 'Geral';
        $categoryData = $this->categoryMap[$categoryName] ?? $this->categoryMap['Geral'];

        $payload = [
            'title'            => $data['title'],
            'content'          => $data['content'],
            'type'             => $data['type'],
            'autor'            => 1,
            'status'           => 0,
            'category'         => $categoryData['id'],
            'cat_pai'          => $categoryData['pai'],
            'metaDescription' => $data['metaDescription'] ?? Str::limit(strip_tags($data['content']), 160),
            'excerpt'          => $data['excerpt'] ?? null,
            'tags'             => $data['tags'] ?? null,
            'readingTime'     => $data['readingTime'] ?? null,
        ];        

        // 💾 cria post
        $post = Post::create($payload);

        $image = null;

        // 🖼️ baixa e salva a imagem do Stable Diffusion
        if (!empty($data['imageUrl'])) {

            $imageUrl = 'https://image.pollinations.ai/prompt/' .
                urlencode($data['imageUrl']);

            $image = $this->saveImageFromUrl($post, $imageUrl);
        }

        return response()->json([
            'success'         => true,
            'post_id'         => $post->id,
            'url'             => url('/blog/artigo/' . $post->slug),
            'image'           => $image,
            'category'        => $categoryName,
            'title'           => $post->title,
            'readingTime'     => $post->readingTime,
            'metaDescription' => $post->metaDescription,
            'excerpt'         => $post->excerpt,
            'slug'            => $post->slug,
            'tags'            => $post->tags
                ? collect(explode(',', $post->tags))
                    ->map(fn ($tag) => trim($tag))
                    ->filter()
                    ->map(fn ($tag) => '#' . Str::slug($tag))
                    ->values()
                : [],
        ]);
    }

    private function saveImageFromUrl(Post $post, string $imageUrl): ?string
    {
        try {
            // 📥 baixa a imagem com timeout generoso (SD pode demorar)
            $response = Http::timeout(30)->get($imageUrl);
 
            if (!$response->successful()) {
                logger()->warning('Falha ao baixar imagem do SD', [
                    'post_id' => $post->id,
                    'url'     => $imageUrl,
                    'status'  => $response->status(),
                ]);
                return null;
            }
 
            // 🔍 detecta extensão pelo Content-Type
            $contentType = $response->header('Content-Type');
            $extension   = $this->extensionFromMime($contentType);
 
            // 📁 define caminho e salva no disco público
            $dir  = 'posts/' . $post->id;
            $name = Str::slug($post->title) . '.' . $extension;
            $path = $dir . '/' . $name;
 
            Storage::makeDirectory($dir);
            
            //logger('1 - download ok');
            $manager = new ImageManager(new Driver());

            //logger('2 - manager ok');
            $image = $manager->read($response->body());

            //logger('3 - image read ok');
            // cria uma faixa escura
            $overlay = $manager->create($image->width(), $image->height())
                ->fill('rgba(0,0,0,0.45)');

            $image->place($overlay);

            //logger('4 - overlay ok');            

            // quebra o título em múltiplas linhas
            $lines = explode("\n", wordwrap($post->title, 25, "\n"));

            $totalHeight = count($lines) * 60;
            $startY = ($image->height() / 2) - ($totalHeight / 2);

            foreach ($lines as $line) {

                $image->text(
                    $line,
                    $image->width() / 2,
                    $startY,
                    function (FontFactory $font) {
                        $font->filename(public_path('fonts/Montserrat-Bold.ttf'));
                        $font->size(48);
                        $font->color('#ffffff');
                        $font->align('center');
                        $font->valign('top');
                    }
                );

                $startY += 60;
            }
            
            //logger('5 - text ok');
            // salva a imagem processada
            Storage::put(
                $path,
                (string) $image->toWebp(85)
            );
            //logger('6 - save ok');
            // 🗂️ registra no banco
            PostGb::create([
                'post'  => $post->id,
                'cover' => true,
                'path'  => $path,
            ]);
 
            return Storage::url($path);
 
        } catch (\Exception $e) {
            logger()->error('Erro ao salvar imagem do SD', [
                'post_id' => $post->id,
                'url'     => $imageUrl,
                'error'   => $e->getMessage(),
            ]);
 
            return null;
        }
    }
 
    /**
     * Mapeia Content-Type para extensão de arquivo.
     * Fallback para webp se não reconhecer.
     */
    private function extensionFromMime(string $contentType): string
    {
        return match (true) {
            str_contains($contentType, 'jpeg') => 'jpg',
            str_contains($contentType, 'png')  => 'png',
            str_contains($contentType, 'webp') => 'webp',
            str_contains($contentType, 'gif')  => 'gif',
            default                            => 'webp',
        };
    }
}
