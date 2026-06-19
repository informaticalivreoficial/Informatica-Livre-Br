<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostGb;
use App\Models\User;
use App\Notifications\MakePostArticle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
     /**
     * Paleta de cores que se destacam bem sobre o overlay escuro.
     * Uma é sorteada por imagem.
     */
    private const TEXT_COLOR_PALETTE = [
        '#FFFFFF', // branco clássico
        '#FFD60A', // amarelo vibrante
        '#00E5FF', // ciano elétrico
        '#7CFF6B', // verde lima
        '#FF6B6B', // vermelho coral
        '#C792FF', // lilás
    ];

    /**
     * Paleta de fontes (precisam existir em public/fonts/).
     * Uma é sorteada por imagem, junto com a cor.
     */
    private const FONT_PALETTE = [
        'fonts/Montserrat-Bold.ttf',
        'fonts/Poppins-Bold.ttf',
        'fonts/Inter-Bold.ttf',
        'fonts/Roboto-Bold.ttf',
    ];

    private const THUMB_WIDTH    = 1200;
    private const THUMB_HEIGHT   = 630;
    private const FONT_SIZE      = 48;
    private const LINE_HEIGHT    = 60;
    private const OVERLAY_PAD    = 30;
    private const OVERLAY_ALPHA  = 0.45;

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

        Notification::send(
            User::role('super-admin')->get(),
            new MakePostArticle($post)
        );

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
            'tags'            => $this->formatTagsAsHashtags($post->tags),
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
            
            $manager = new ImageManager(new Driver());

            $image = $manager->read($response->body());

            // 📐 padroniza tamanho para thumb de blog / Open Graph
            $image->cover(self::THUMB_WIDTH, self::THUMB_HEIGHT);

            // 📝 quebra o título em múltiplas linhas
            $lines = explode("\n", wordwrap(Str::limit($post->title, 90), 28, "\n"));

            $totalHeight = count($lines) * self::LINE_HEIGHT;
            $startY = ($image->height() / 2) - ($totalHeight / 2);

            // 🌑 faixa escura só na altura do texto, com uma margem de respiro
            $overlayY = (int) ($startY - self::OVERLAY_PAD);
            $overlayH = (int) ($totalHeight + (self::OVERLAY_PAD * 2));
 
            $overlay = $manager->create($image->width(), $overlayH)
                        ->fill('rgba(0,0,0,' . self::OVERLAY_ALPHA . ')');
 
            $image->place($overlay, 'top-left', 0, $overlayY);   

            // 🎨 sorteia uma cor da paleta para o texto
            $textColor = self::TEXT_COLOR_PALETTE[array_rand(self::TEXT_COLOR_PALETTE)];

            $fontFile  = self::FONT_PALETTE[array_rand(self::FONT_PALETTE)];

            $fontCallback = function (FontFactory $font) use ($textColor, $fontFile) {
                $font->filename(public_path($fontFile));
                $font->size(self::FONT_SIZE);
                $font->color($textColor);
                $font->align('center');
                $font->valign('top');
            };

            foreach ($lines as $line) {
                $image->text($line, $image->width() / 2, $startY, $fontCallback);
                $startY += self::LINE_HEIGHT;
            }            
            
            // salva a imagem processada
            Storage::put(
                $path,
                (string) $image->toWebp(85)
            );
            
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

    private function formatTagsAsHashtags(?string $tags)
    {
        if (empty($tags)) {
            return [];
        }
 
        return collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->map(fn ($tag) => '#' . Str::lower(preg_replace('/[^\p{L}\p{N}]+/u', '', $tag)))
            ->values();
    }
}
