<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Pedido;
use App\Support\Money;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagHiperService
{
    protected string $endpoint = 'https://api.paghiper.com/transaction/create/';

    public function createInvoice(Invoice $invoice): array
    {
        // Calcula dias até vencimento (mínimo 3 dias)
        $daysUntilDue = max(3, (int) now()->diffInDays($invoice->due_date, false));

        $payer = $invoice->payerData();

        if (!$payer['name'] || !$payer['document']) {
            throw new \Exception('Dados do pagador incompletos: informe nome e CPF/CNPJ para gerar a cobrança.');
        }

        $description = $invoice->description
            ?: $invoice->subscription?->service?->name
            ?: 'Serviços prestados';

        $payload = [
            'apiKey'        => config('services.paghiper.api_key'), // 👈 Corrigido
            'token'          => config('services.paghiper.token'),    // 👈 Adicionado
            'order_id'       => 'INV-' . $invoice->id,
            'payer_email'    => $payer['email'] ?: 'financeiro@empresa.com',
            'payer_name'     => $payer['name'],
            'payer_cpf_cnpj' => $payer['document'], // 👈 Sem pontuação
            'days_due_date'  => $daysUntilDue, // 👈 Corrigido
            'type_bank_slip' => 'boletoA4', // ou 'boletoPix'
            'items' => [
                [
                    'item_id'     => (string) $invoice->id,
                    'description' => $description,
                    'quantity'    => 1,
                    'price_cents' => Money::cents($invoice->amount),
                ]
            ],
            'notification_url' => route('webhooks.paghiper'),
        ];

        $payload = array_merge($payload, array_filter([
            'payer_phone'      => $payer['phone'],
            'payer_zip_code'   => $payer['zipcode'],
            'payer_street'     => $payer['street'],
            'payer_number'     => $payer['number'],
            'payer_complement' => $payer['complement'],
            'payer_district'   => $payer['neighborhood'],
            'payer_city'       => $payer['city'],
            'payer_state'      => $payer['state'] ? strtoupper(substr($payer['state'], 0, 2)) : null,
        ], fn ($value) => filled($value)));

        Log::info('PagHiper create invoice request', ['payload' => $payload]);

        $response = Http::post($this->endpoint, $payload);

        // Log da resposta completa
        Log::info('PagHiper response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        $data = $response->json();

        // Validação mais robusta
        if ($response->failed() || !($data['create_request']['result'] ?? false)) {
            $errorMessage = $data['create_request']['response_message'] ?? 'Erro ao criar cobrança no PagHiper';
            
            Log::error('PagHiper error', [
                'status' => $response->status(),
                'error' => $errorMessage,
                'response' => $data,
            ]);
            
            throw new \Exception($errorMessage);
        }

        return $data['create_request'];
    }

    public function createPedidoCharge(Pedido $pedido): array
    {
        $document = preg_replace('/\D/', '', (string) $pedido->cpf_cnpj);

        if (! $pedido->nome || ! $document) {
            throw new \Exception('Dados do comprador incompletos: informe nome e CPF/CNPJ para gerar a cobrança.');
        }

        $pedido->loadMissing('itens');

        $items = $pedido->itens->map(fn ($item) => [
            'item_id'     => (string) $item->id,
            'description' => $item->produto_nome . ' — ' . $item->plano_nome,
            'quantity'    => 1,
            'price_cents' => Money::cents($item->preco),
        ])->values()->all();

        $payload = [
            'apiKey'           => config('services.paghiper.api_key'),
            'token'            => config('services.paghiper.token'),
            'order_id'         => 'PED-' . $pedido->id,
            'payer_email'      => $pedido->email,
            'payer_name'       => $pedido->nome,
            'payer_cpf_cnpj'   => $document,
            'days_due_date'    => 3,
            'type_bank_slip'   => $pedido->metodo_pagamento === 'pix' ? 'boletoPix' : 'boletoA4',
            'items'            => $items,
            'notification_url' => route('webhooks.paghiper-pedidos'),
        ];

        $phone = preg_replace('/\D/', '', (string) $pedido->telefone);

        if ($phone !== '') {
            $payload['payer_phone'] = $phone;
        }

        Log::info('PagHiper create pedido charge request', [
            'pedido_id' => $pedido->id,
            'payload' => $payload,
        ]);

        $response = Http::post($this->endpoint, $payload);

        $data = $response->json();

        if ($response->failed() || ! ($data['create_request']['result'] ?? false)) {
            $errorMessage = $data['create_request']['response_message'] ?? 'Erro ao gerar cobrança no PagHiper';

            Log::error('PagHiper pedido error', [
                'pedido_id' => $pedido->id,
                'status' => $response->status(),
                'error' => $errorMessage,
                'response' => $data,
            ]);

            throw new \Exception($errorMessage);
        }

        return $data['create_request'];
    }

    public function consultTransaction(string $transactionId): ?array
    {
        $response = Http::post(
            'https://api.paghiper.com/transaction/status/',
            [
                'apiKey' => config('services.paghiper.api_key'),
                'token'  => config('services.paghiper.token'),
                'transaction_id' => $transactionId,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }

    public function getNotification(string $notificationId, string $transactionId, string $operation = 'api'): ?array
    {
        $base = $operation === 'pix' ? 'https://pix.paghiper.com' : 'https://api.paghiper.com';
        $endpoint = $operation === 'pix' ? '/invoice/notification/' : '/transaction/notification/';

        $response = Http::post(
            $base . $endpoint,
            [
                'apiKey' => config('services.paghiper.api_key'),
                'token' => config('services.paghiper.token'),
                'notification_id' => $notificationId,
                'transaction_id' => $transactionId,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $payload = $response->json('status_request');

        if (!$payload || ($payload['result'] ?? '') === 'reject') {
            return null;
        }

        return $payload;
    }

    public function syncInvoice(Invoice $invoice): Invoice
    {
        if (!$invoice->gateway_reference) {
            throw new \Exception('A fatura não possui transaction_id.');
        }

        $response = $this->consultTransaction(
            $invoice->gateway_reference
        );

        if (!$response) {
            throw new \Exception('Falha ao consultar PagHiper.');
        }

        $payload = $response['status_request'] ?? null;

        if (!$payload) {
            throw new \Exception('Resposta inválida do PagHiper.');
        }

        $status = $payload['status'] ?? null;

        if (!$status) {
            throw new \Exception('Status não informado pelo PagHiper.');
        }

        $status = match ($status) {
            'processing' => 'pending',
            'cancelled'  => 'canceled',
            default      => $status,
        };

        $oldStatus = $invoice->status;

        $invoice->update([
            'status' => $status,
        ]);

        if (
            $oldStatus !== 'paid' &&
            $status === 'paid'
        ) {
            $valueCents = (int) data_get($payload, 'value_cents', 0);
            $feeCents = (int) data_get($payload, 'value_fee_cents', 0);
            $paidCents = (int) data_get($payload, 'value_cents_paid', $valueCents);

            $invoice->update([
                'paid_at' => $payload['status_date'] ?? now(),
                'gateway_fee' => $feeCents ? $feeCents / 100 : null,
                'gateway_net_amount' => $paidCents ? max(0, $paidCents - $feeCents) / 100 : null,
            ]);

            $invoice->registerFinancialIncome();
        }

        Log::info('Invoice sincronizada com PagHiper', [
            'invoice_id' => $invoice->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        return $invoice->fresh();
    }
}
