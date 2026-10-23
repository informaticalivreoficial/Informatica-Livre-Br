<?php

namespace App\Livewire\Dashboard\Invoices;

use App\Models\Invoice;
use App\Services\PagHiperService;
use Livewire\Component;

class InvoicesShow extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load([
            'company',
            'subscription.service',
        ]);
    }

    public function generateCharge(): void
    {
        if ($this->invoice->gateway_reference) {
            return;
        }

        try {
            $response = app(PagHiperService::class)->createInvoice($this->invoice);

            $this->invoice->update([
                'gateway'           => 'paghiper',
                'gateway_reference' => $response['transaction_id'] ?? null,
                'payment_url'       => $response['bank_slip']['url_slip'] ?? null,
                'pix_qrcode'        => $response['pix']['qrcode_image'] ?? null,
                'payment_method'    => 'boleto',
            ]);

            $this->invoice->refresh();

            $this->dispatch('swal:success', [
                'title' => 'Cobrança gerada',
                'text'  => 'A cobrança foi gerada com sucesso no PagHiper.',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Erro ao gerar cobrança',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function syncInvoice(): void
    {
        if (!$this->invoice->gateway_reference) {
            $this->dispatch('swal:error', [
                'title' => 'Sem cobrança no gateway',
                'text'  => 'Esta fatura ainda não foi gerada no PagHiper.',
            ]);
            return;
        }

        try {
            $pagHiper = new PagHiperService();
            $this->invoice = $pagHiper->syncInvoice($this->invoice);

            $this->dispatch('swal:success', [
                'title' => 'Status atualizado',
                'text'  => 'Status atual: ' . ucfirst($this->invoice->status) . '.',
                'timer' => 2500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Erro ao consultar PagHiper',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.invoices.invoices-show')->with('title', 'Fatura #' . $this->invoice->id);
    }
}