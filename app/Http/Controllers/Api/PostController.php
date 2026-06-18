<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostGb;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
            'tags'            => 'nullable|array',
            'tags.*'          => 'string',
            'readingTime'     => 'nullable|integer',
            'imageUrl'        => 'nullable|url',   // URL temporária do Stable Diffusion
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
            'meta_description' => $data['metaDescription'] ?? Str::limit(strip_tags($data['content']), 160),
            'excerpt'          => $data['excerpt'] ?? null,
            'tags'             => !empty($data['tags']) ? implode(',', $data['tags']) : null,
            'reading_time'     => $data['readingTime'] ?? null,
        ];        

        // 💾 cria post
        $post = Post::create($payload);

        // 🖼️ baixa e salva a imagem do Stable Diffusion
        $image = !empty($data['imageUrl'])
            ? $this->saveImageFromUrl($post, $data['imageUrl'])
            : null;

        return response()->json([
            'success'      => true,
            'post_id'      => $post->id,
            'url'          => url('/blog/artigo/' . $post->slug),
            'image'        => $image,
            'category'     => $categoryName,
            'reading_time' => $post->reading_time,
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
 
            Storage::disk('public')->makeDirectory($dir);
            Storage::disk('public')->put($path, $response->body());
 
            // 🗂️ registra no banco
            PostGb::create([
                'post'  => $post->id,
                'cover' => true,
                'path'  => $path,
            ]);
 
            return Storage::disk('public')->url($path);
 
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
