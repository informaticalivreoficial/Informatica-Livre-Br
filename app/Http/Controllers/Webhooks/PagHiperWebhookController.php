<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PagHiperWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('PagHiper Webhook recebido', $request->all());

        // 🔐 Validação básica de segurança
        if ($request->apiKey !== config('services.paghiper.key')) {
            Log::warning('PagHiper webhook com apiKey inválida');
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // 🔎 Localiza a invoice pelo order_id
        // Ex: INV-42
        $invoiceId = str_replace('INV-', '', $request->order_id);

        $invoice = Invoice::find($invoiceId);

        if (!$invoice) {
            Log::error('Invoice não encontrada', [
                'order_id' => $request->order_id
            ]);

            return response()->json(['error' => 'Invoice not found'], Response::HTTP_NOT_FOUND);
        }

        // 🧠 Mapeia status PagHiper → sistema
        match ($request->status) {
            'paid'      => $this->markAsPaid($invoice, $request),
            'canceled'  => $this->markAsCanceled($invoice),
            'expired'   => $this->markAsExpired($invoice),
            default     => Log::warning('Status PagHiper desconhecido', [
                'status' => $request->status
            ])
        };

        return response()->json(['success' => true]);
    }

    protected function markAsPaid(Invoice $invoice, Request $request): void
    {
        if ($invoice->status === 'paid') {
            return; // evita duplicidade
        }

        $invoice->update([
            'status'             => 'paid',
            'paid_at'            => now(),
            'gateway'            => 'paghiper',
            'gateway_reference'  => $request->transaction_id,
        ]);

        Log::info('Invoice marcada como paga', [
            'invoice_id' => $invoice->id
        ]);
    }

    protected function markAsCanceled(Invoice $invoice): void
    {
        $invoice->update([
            'status' => 'canceled',
        ]);
    }

    protected function markAsExpired(Invoice $invoice): void
    {
        $invoice->update([
            'status' => 'expired',
        ]);
    }
}
