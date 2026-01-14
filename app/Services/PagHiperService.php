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
        // Calcula dias até vencimento (mínimo 3 dias)
        $daysUntilDue = max(3, now()->diffInDays($invoice->due_date, false));
        
        // Remove pontuação do CPF/CNPJ
        $document = $invoice->company->document 
            ? preg_replace('/\D/', '', $invoice->company->document)
            : null;

        $payload = [
            'api_key'        => config('services.paghiper.api_key'), // 👈 Corrigido
            'token'          => config('services.paghiper.token'),    // 👈 Adicionado
            'order_id'       => 'INV-' . $invoice->id,
            'payer_email'    => $invoice->company->email ?? 'financeiro@empresa.com',
            'payer_name'     => $invoice->company->alias_name,
            'payer_cpf_cnpj' => $document, // 👈 Sem pontuação
            'days_due_date'  => $daysUntilDue, // 👈 Corrigido
            'type_bank_slip' => 'boletoA4', // ou 'boletoPix'
            'items' => [
                [
                    'description' => $invoice->subscription->service->name,
                    'quantity'    => 1,
                    'price_cents' => (int) bcmul($invoice->amount, '100', 0), // 👈 Corrigido
                ]
            ],
            'notification_url' => route('webhooks.paghiper'),
        ];

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
}
