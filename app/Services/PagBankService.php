<?php

namespace App\Services;

use App\Models\Pedido;
use App\Support\Money;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PagBankService
{
    private string $token;

    private string $baseUrl;

    public function __construct()
    {
        $this->token = (string) config('services.pagbank.token');

        $url = (string) config('services.pagbank.url');

        if ($url === '') {
            $url = config('services.pagbank.sandbox')
                ? 'https://sandbox.api.pagseguro.com'
                : 'https://api.pagseguro.com';
        }

        $this->baseUrl = rtrim($url, '/');
    }

    public function createPedidoCheckout(Pedido $pedido): array
    {
        $pedido->loadMissing('itens');

        $payload = $this->payload($pedido);

        Log::info('PagBank create checkout request', [
            'pedido_id' => $pedido->id,
            'payload' => $payload,
        ]);

        $response = $this->client()->post($this->baseUrl . '/checkouts', $payload);

        if ($response->failed()) {
            $message = $this->errorMessage($response);

            Log::error('PagBank checkout error', [
                'pedido_id' => $pedido->id,
                'status' => $response->status(),
                'error' => $message,
                'response' => $response->json(),
            ]);

            throw new \Exception($message);
        }

        $data = $response->json();

        $url = $this->paymentLink($data);

        if (! $url) {
            throw new \Exception('O PagBank não retornou o link de pagamento do checkout.');
        }

        $pedido->update([
            'gateway_id' => $data['id'] ?? null,
            'payment_url' => $url,
            'gateway_response' => $data,
        ]);

        return [
            'checkout_id' => $data['id'] ?? null,
            'url' => $url,
            'response' => $data,
        ];
    }

    public function getCheckout(string $checkoutId): array
    {
        $response = $this->client()->get($this->baseUrl . '/checkouts/' . $checkoutId);

        if ($response->failed()) {
            throw new \Exception($this->errorMessage($response));
        }

        return $response->json();
    }

    public function getOrder(string $orderId): array
    {
        $response = $this->client()->get($this->baseUrl . '/orders/' . $orderId);

        if ($response->failed()) {
            throw new \Exception($this->errorMessage($response));
        }

        return $response->json();
    }

    public function verifySignature(Request $request): bool
    {
        $token = (string) config('services.pagbank.webhook_token');

        if ($token === '') {
            return true;
        }

        $signature = (string) $request->header('x-authenticity-token', '');

        if ($signature === '') {
            return false;
        }

        $expected = hash('sha256', $token . '-' . $request->getContent());

        return hash_equals($expected, $signature);
    }

    private function client()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->timeout(30);
    }

    private function payload(Pedido $pedido): array
    {
        $pedido->loadMissing('itens');

        $items = $pedido->itens->map(fn ($item) => array_filter([
            'reference_id' => (string) $item->id,
            'name' => mb_substr($item->produto_nome, 0, 100),
            'description' => mb_substr($item->produto_nome . ' — ' . $item->plano_nome, 0, 255),
            'quantity' => 1,
            'unit_amount' => Money::cents($item->preco),
        ]))->values()->all();

        $customer = array_filter([
            'name' => $pedido->nome,
            'email' => $pedido->email,
            'tax_id' => preg_replace('/\D/', '', (string) $pedido->cpf_cnpj),
            'phone' => $this->phone($pedido->telefone),
        ]);

        return array_filter([
            'reference_id' => $pedido->codigo,
            'expiration_date' => now()->addDays(3)->format('Y-m-d\TH:i:sP'),
            'customer' => $customer ?: null,
            'customer_modifiable' => true,
            'items' => $items,
            'payment_methods' => $this->paymentMethods($pedido->metodo_pagamento),
            'payment_methods_configs' => $this->paymentMethodsConfigs($pedido->metodo_pagamento),
            'soft_descriptor' => mb_substr(Str::ascii((string) config('app.name', 'Informatica Livre')), 0, 17),
            'redirect_url' => route('web.checkout.retorno', ['pedido' => $pedido->codigo]),
            'return_url' => route('web.checkout.retorno', ['pedido' => $pedido->codigo]),
            'redirect_waiting_time' => 5,
            'notification_urls' => [route('webhooks.pagbank')],
            'payment_notification_urls' => [route('webhooks.pagbank')],
        ]);
    }

    private function paymentMethods(?string $metodo): array
    {
        return match ($metodo) {
            'pix' => [['type' => 'PIX']],
            'boleto' => [['type' => 'BOLETO']],
            'cartao_credito' => [['type' => 'CREDIT_CARD']],
            default => [
                ['type' => 'CREDIT_CARD'],
                ['type' => 'PIX'],
                ['type' => 'BOLETO'],
            ],
        };
    }

    private function paymentMethodsConfigs(?string $metodo): array
    {
        if ($metodo !== 'cartao_credito') {
            return [];
        }

        return [
            [
                'type' => 'CREDIT_CARD',
                'config_options' => [
                    ['option' => 'INSTALLMENTS_LIMIT', 'value' => '12'],
                    ['option' => 'INTEREST_FREE_INSTALLMENTS', 'value' => '1'],
                ],
            ],
        ];
    }

    private function phone(?string $telefone): ?array
    {
        $digits = preg_replace('/\D/', '', (string) $telefone);

        if (strlen($digits) < 10) {
            return null;
        }

        $area = substr($digits, 0, 2);
        $number = substr($digits, 2);

        if (strlen($number) === 8) {
            $number = '9' . $number;
        }

        return [
            'country' => '+55',
            'area' => $area,
            'number' => substr($number, 0, 9),
        ];
    }

    private function paymentLink(array $data): ?string
    {
        $link = collect($data['links'] ?? [])->firstWhere('rel', 'PAY');

        return $link['href'] ?? null;
    }

    private function errorMessage(Response $response): string
    {
        $message = data_get($response->json(), 'error_messages.0.description');

        return $message ?: 'Não foi possível gerar o checkout no PagBank.';
    }
}
