<?php

namespace App\Console\Commands;

use App\Enums\BillingType;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessRecurringInvoices extends Command
{
    protected $signature = 'invoices:process-recurring';
    protected $description = 'Gera invoices recorrentes para subscriptions ativas';

    public function handle()
    {
        $subscriptions = Subscription::query()
            ->with(['company', 'service', 'invoices'])
            ->active()
            ->whereHas('service', fn($q) => $q->where('billing_type', \App\Enums\BillingType::RECURRING->value))
            ->whereDate('next_billing_at', '<=', today())
            ->get();

        Log::info("Processando {$subscriptions->count()} subscriptions...");
        $this->info("Processando {$subscriptions->count()} subscriptions...");

        foreach ($subscriptions as $subscription) {
            try {
                $this->processSubscription($subscription);
            } catch (\Exception $e) {
                Log::error('Erro ao processar subscription', [
                    'subscription_id' => $subscription->id,
                    'error'           => $e->getMessage(),
                ]);
                $this->error("Erro na subscription #{$subscription->id}: {$e->getMessage()}");
            }
        }

        $this->info('Processamento concluído.');
    }

    private function processSubscription($subscription): void
{
    $exists = $subscription->invoices()
        ->whereIn('status', ['pending', 'paid'])
        ->whereMonth('due_date', $subscription->next_billing_at->month)
        ->whereYear('due_date', $subscription->next_billing_at->year)
        ->exists();

    if ($exists) {
        // Já tem invoice para este período — avança o ciclo
        $subscription->calculateNextBilling();
        $subscription->save();

        $dueDate = $subscription->next_billing_at->copy();
    } else {
        $dueDate = $subscription->next_billing_at->copy();
    }

    // Verifica se já tem invoice para o novo período
    $alreadyExists = $subscription->invoices()
        ->whereIn('status', ['pending', 'paid'])
        ->whereMonth('due_date', $dueDate->month)
        ->whereYear('due_date', $dueDate->year)
        ->exists();

    if ($alreadyExists) {
        $this->line("Subscription #{$subscription->id} já possui invoice para este período.");
        return;
    }

    // Cria invoice com a data correta
    $invoice = $subscription->generateInvoice(due_date: $dueDate);

    // 👈 NÃO avança o ciclo aqui — já foi avançado acima
    $this->line("✅ Invoice #{$invoice->id} criada para subscription #{$subscription->id}");

    Log::info('Invoice recorrente gerada', [
        'subscription_id' => $subscription->id,
        'invoice_id'      => $invoice->id,
        'due_date'        => $invoice->due_date,
        'next_billing_at' => $subscription->next_billing_at,
    ]);
}
}
