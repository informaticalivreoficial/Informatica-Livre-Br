<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\InvoiceStatusNotification;
use Illuminate\Support\Facades\Notification;

class PagHiperWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('PagHiper Webhook recebido', $request->all());

        // 🔐 Validação de segurança aprimorada
        if (!$this->isValidWebhook($request)) {
            Log::warning('PagHiper webhook com credenciais inválidas', [
                'api_key' => $request->input('api_key'),
                'token' => $request->input('token'),
            ]);
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            // 🔎 Localiza a invoice pelo order_id (ex: INV-42)
            $invoiceId = (int) str_replace('INV-', '', $request->input('order_id'));

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                Log::error('Invoice não encontrada', [
                    'order_id' => $request->input('order_id'),
                    'invoice_id' => $invoiceId,
                ]);

                return response()->json(['error' => 'Invoice not found'], Response::HTTP_NOT_FOUND);
            }

            // 🧠 Mapeia status PagHiper → sistema
            match ($request->input('status')) {
                'paid', 'completed'     => $this->markAsPaid($invoice, $request),
                'pending'               => $this->markAsPending($invoice, $request),
                'canceled', 'cancelled' => $this->markAsCanceled($invoice),
                'refunded'              => $this->markAsRefunded($invoice),
                'processing'            => Log::info('Pagamento em processamento', ['invoice_id' => $invoice->id]),
                default                 => Log::warning('Status PagHiper desconhecido', [
                    'status' => $request->input('status'),
                    'invoice_id' => $invoice->id,
                ])
            };

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook PagHiper', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function isValidWebhook(Request $request): bool
    {
        $apiKey = $request->input('api_key') ?? $request->input('apiKey');
        $token = $request->input('token');

        return $apiKey === config('services.paghiper.api_key')
            && $token === config('services.paghiper.token');
    }

    protected function markAsPending(Invoice $invoice, Request $request): void
    {
        if ($invoice->status === 'paid') {
            return; // Não permite voltar de pago para pendente
        }

        $invoice->update([
            'status'            => 'pending',
            'gateway'           => 'paghiper',
            'gateway_reference' => $request->input('transaction_id'),
        ]);

        Log::info('Invoice marcada como pendente', [
            'invoice_id' => $invoice->id,
            'transaction_id' => $request->input('transaction_id'),
        ]);
    }

    protected function markAsPaid(Invoice $invoice, Request $request): void
    {
        if ($invoice->status === 'paid') {
            Log::info('Invoice já estava paga, ignorando duplicidade', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        $invoice->update([
            'status'            => 'paid',
            'paid_at'           => now(),
            'gateway'           => 'paghiper',
            'gateway_reference' => $request->input('transaction_id'),
            'payment_method'    => $request->input('type_bank_slip') ?? 'boleto',
            'gateway_fee'       => $request->input('value_fee_cents') 
                ? $request->input('value_fee_cents') / 100 
                : null,
        ]);

        Log::info('Invoice marcada como paga', [
            'invoice_id' => $invoice->id,
            'transaction_id' => $request->input('transaction_id'),
            'paid_amount' => $request->input('value_cents') / 100,
        ]);

        Notification::send(
            User::role('super-admin')->get(),
            new InvoiceStatusNotification($invoice, 'paid')
        );
    }

    protected function markAsCanceled(Invoice $invoice): void
    {
        if ($invoice->status === 'paid') {
            Log::warning('Tentativa de cancelar invoice já paga', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        $invoice->update([
            'status' => 'canceled',
        ]);

        // Log::info('Invoice cancelada', [
        //     'invoice_id' => $invoice->id
        // ]);
        Notification::send(
            User::role('super-admin')->get(),
            new InvoiceStatusNotification($invoice, 'canceled')
        );
    }

    protected function markAsRefunded(Invoice $invoice): void
    {
        $invoice->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);

        // Log::info('Invoice estornada', [
        //     'invoice_id' => $invoice->id
        // ]);
        Notification::send(
            User::role('super-admin')->get(),
            new InvoiceStatusNotification($invoice, 'refunded')
        );
    }
}
