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
        $document = $invoice->company->document_company 
            ? preg_replace('/\D/', '', $invoice->company->document_company)
            : null;

        $payload = [
            'apiKey'        => config('services.paghiper.api_key'), // 👈 Corrigido
            'token'          => config('services.paghiper.token'),    // 👈 Adicionado
            'order_id'       => 'INV-' . $invoice->id,
            'payer_email'    => $invoice->company->email ?? 'financeiro@empresa.com',
            'payer_name'     => $invoice->company->alias_name,
            'payer_cpf_cnpj' => $document, // 👈 Sem pontuação
            'days_due_date'  => $daysUntilDue, // 👈 Corrigido
            'type_bank_slip' => 'boletoA4', // ou 'boletoPix'
            'items' => [
                [
                    'item_id'     => (string) $invoice->id,
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

        $oldStatus = $invoice->status;

        $invoice->update([
            'status' => $status,
        ]);

        if (
            $oldStatus !== 'paid' &&
            $status === 'paid'
        ) {
            $invoice->update([
                'paid_at' => $payload['status_date'] ?? now(),
            ]);
        }

        Log::info('Invoice sincronizada com PagHiper', [
            'invoice_id' => $invoice->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        return $invoice->fresh();
    }
}
