<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagHiperService
{
    protected string $endpoint = 'https://api.paghiper.com/transaction/create/';

    public function createInvoice(Invoice $invoice): array
    {
        $payload = [
            'apiKey'      => config('services.paghiper.key'),
            'order_id'    => 'INV-' . $invoice->id,
            'payer_email' => $invoice->company->email ?? 'financeiro@empresa.com',
            'payer_name'  => $invoice->company->alias_name,
            'payer_cpf_cnpj' => $invoice->company->document ?? null,
            'days_due_date' => now()->diffInDays($invoice->due_date),
            'items' => [
                [
                    'description' => $invoice->subscription->service->name,
                    'quantity'    => 1,
                    'price_cents' => (int) bcmul($invoice->amount, 100),
                ]
            ],
            'notification_url' => route('webhooks.paghiper'),
        ];

        $response = Http::post($this->endpoint, $payload)->json();

        Log::info('PagHiper create invoice response', $response);

        if (!($response['create_request']['result'] ?? false)) {
            throw new \Exception('Erro ao criar cobrança no PagHiper');
        }

        return $response['create_request'];
    }
}
