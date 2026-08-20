<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use App\Models\Invoice;
use App\Models\User;
use App\Services\PagHiperService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\InvoiceStatusNotification;
use Illuminate\Support\Facades\Notification;

class PagHiperWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('PagHiper Webhook recebido', $request->all());

        if (!$this->isValidWebhook($request)) {
            Log::warning('PagHiper webhook com credenciais inválidas', [
                'api_key' => $request->input('api_key') ?? $request->input('apiKey'),
            ]);
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $notificationId = $request->input('notification_id');
            $transactionId = $request->input('transaction_id');

            if (!$notificationId || !$transactionId) {
                Log::warning('PagHiper webhook sem notification_id/transaction_id', $request->all());
                return response()->json(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            }

            $sourceApi = $request->input('source_api', '');
            $operation = str_contains($sourceApi, 'pix.paghiper.com') ? 'pix' : 'api';

            $payload = app(PagHiperService::class)
                ->getNotification($notificationId, $transactionId, $operation);

            if (!$payload) {
                Log::error('Falha ao obter notificação completa do PagHiper', [
                    'notification_id' => $notificationId,
                    'transaction_id' => $transactionId,
                ]);
                return response()->json(['error' => 'Notification not found'], Response::HTTP_NOT_FOUND);
            }

            $invoiceId = (int) str_replace('INV-', '', (string) ($payload['order_id'] ?? ''));

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                Log::error('Invoice não encontrada', [
                    'order_id' => $payload['order_id'] ?? null,
                    'invoice_id' => $invoiceId,
                ]);
                return response()->json(['error' => 'Invoice not found'], Response::HTTP_NOT_FOUND);
            }

            match ($payload['status'] ?? null) {
                'paid', 'completed' => $this->markAsPaid($invoice, $payload),
                'pending'           => $this->markAsPending($invoice, $payload),
                'canceled', 'cancelled' => $this->markAsCanceled($invoice),
                'refunded'          => $this->markAsRefunded($invoice),
                'processing'        => Log::info('Pagamento em processamento', ['invoice_id' => $invoice->id]),
                default             => Log::warning('Status PagHiper desconhecido', [
                    'status' => $payload['status'] ?? null,
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

        return $apiKey === config('services.paghiper.api_key');
    }

    protected function markAsPending(Invoice $invoice, array $payload): void
    {
        if ($invoice->status === 'paid') {
            return;
        }

        $invoice->update([
            'status'            => 'pending',
            'gateway'           => 'paghiper',
            'gateway_reference' => $payload['transaction_id'] ?? $invoice->gateway_reference,
        ]);

        Log::info('Invoice marcada como pendente', [
            'invoice_id' => $invoice->id,
            'transaction_id' => $payload['transaction_id'] ?? null,
        ]);
    }

    protected function markAsPaid(Invoice $invoice, array $payload): void
    {
        if ($invoice->status === 'paid') {
            Log::info('Invoice já estava paga, ignorando duplicidade', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        $valueCents = (int) ($payload['value_cents'] ?? 0);
        $feeCents = (int) ($payload['value_fee_cents'] ?? 0);
        $paidCents = (int) ($payload['value_cents_paid'] ?? $valueCents);
        $netCents = max(0, $paidCents - $feeCents);

        $invoice->update([
            'status'            => 'paid',
            'paid_at'           => now(),
            'gateway'           => 'paghiper',
            'gateway_reference' => $payload['transaction_id'] ?? $invoice->gateway_reference,
            'payment_method'    => isset($payload['pix_code']) ? 'pix' : 'boleto',
            'gateway_fee'       => $feeCents ? $feeCents / 100 : null,
            'gateway_net_amount' => $netCents ? $netCents / 100 : null,
        ]);

        $invoice->registerFinancialIncome();

        Log::info('Invoice marcada como paga', [
            'invoice_id' => $invoice->id,
            'transaction_id' => $payload['transaction_id'] ?? null,
            'paid_amount' => $valueCents / 100,
            'gateway_fee' => $invoice->gateway_fee,
            'gateway_net_amount' => $invoice->gateway_net_amount,
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

        $transaction = FinancialTransaction::withoutGlobalScope('user')
            ->where('invoice_id', $invoice->id)
            ->first();

        $transaction?->cancelTransaction();

        Notification::send(
            User::role('super-admin')->get(),
            new InvoiceStatusNotification($invoice, 'refunded')
        );
    }
}