<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SocialPostService
{

    private $webhookUrl;

    /**
     * Inicializa a classe
     */
    public function __construct()
    {
        $this->webhookUrl = config('services.social.webhook_url');
    }

    /**
     * Envia post para o Make (Facebook, Instagram, X, etc)
     */
    public function post(
        string $type,
        array $data = []
    ): array {
        $payload = $this->buildPayload($type, $data);

        $response = Http::timeout(10)
            ->post($this->webhookUrl, $payload);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'body'    => $response->json(),
        ];
    }

    /**
     * Monta payload padronizado para o Make
     */
    protected function buildPayload(string $type, array $data): array
    {
        return [
            'type'        => $type, // facebook | instagram | x
            'message'     => $data['message'] ?? '',
            'image'       => $data['image'] ?? null,
            'link'        => $data['link'] ?? null,
            'video'       => $data['video'] ?? null,
            'tags'        => $data['tags'] ?? [],
            'scheduled_at'=> $data['scheduled_at'] ?? null,

            // contexto útil (opcional mas recomendado)
            'meta' => [
                'source'      => config('app.name'),
                'environment' => app()->environment(),
                'sent_at'     => now()->toISOString(),
                'user_id'     => auth()->id(),
            ],
        ];
    }
    
}