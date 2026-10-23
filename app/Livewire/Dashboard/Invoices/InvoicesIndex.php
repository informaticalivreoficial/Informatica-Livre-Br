<?php

namespace App\Livewire\Dashboard\Invoices;

use App\Models\Invoice;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\PagHiperService;
use App\Traits\WithToastr;

class InvoicesIndex extends Component
{
    use WithPagination;
    use WithToastr;

    public string $search = '';
    public string $status = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function syncInvoice(int $invoiceId): void
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $response = app(PagHiperService::class)
            ->consultTransaction($invoice->gateway_reference);

        if (!$response) {
            $this->toastError('Não foi possível consultar a fatura.');
            return;
        }

        $status = data_get($response, 'status_request.status');

        if (!$status) {
            $this->toastError('Status não encontrado na resposta do PagHiper.');
            return;
        }

        $oldStatus = $invoice->status;

        $invoice->update([
            'status' => $status,
        ]);

        if (
            $oldStatus !== 'paid' &&
            $status === 'paid'
        ) {
            $payload = $response['status_request'] ?? [];
            $valueCents = (int) data_get($payload, 'value_cents', 0);
            $feeCents = (int) data_get($payload, 'value_fee_cents', 0);
            $paidCents = (int) data_get($payload, 'value_cents_paid', $valueCents);

            $invoice->update([
                'paid_at' => data_get($payload, 'status_date', now()),
                'gateway_fee' => $feeCents ? $feeCents / 100 : null,
                'gateway_net_amount' => $paidCents ? max(0, $paidCents - $feeCents) / 100 : null,
            ]);

            $invoice->registerFinancialIncome();
        }

        $this->toastSuccess(
            "Status atualizado para: {$status}"
        );
    }

    public function generateBoleto(int $invoiceId): void
    {
        $invoice = Invoice::with(['company', 'subscription.service'])
            ->findOrFail($invoiceId);

        if ($invoice->payment_url) {
            $this->dispatch('openUrl', url: $invoice->payment_url);
            return;
        }

        try {
            $pagHiper = new PagHiperService();
            $response = $pagHiper->createInvoice($invoice);

            $invoice->update([
                'gateway'           => 'paghiper',
                'gateway_reference' => $response['transaction_id'] ?? null,
                'payment_url'       => $response['bank_slip']['url_slip'] ?? null,
                'pix_qrcode'        => $response['pix']['qrcode_image'] ?? null,
            ]);

            $this->dispatch('openUrl', url: $invoice->payment_url);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Erro ao gerar boleto',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function markAsPaid(int $id): void
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status !== 'pending') {
            $this->dispatch('swal:error', [
                'title' => 'Erro',
                'text'  => 'Apenas faturas pendentes podem ser marcadas como pagas.',
            ]);
            return;
        }

        $invoice->update([
            'status'  => 'paid',
            'paid_at' => now(),
            'gateway_net_amount' => $invoice->amount,
        ]);

        $invoice->registerFinancialIncome();

        $this->dispatch('swal:success', [
            'title' => 'Pago!',
            'text'  => 'Fatura marcada como paga com sucesso.',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Fatura?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteInvoice',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteInvoice')]
    public function deleteInvoice(int $id): void
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Fatura removida com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->with(['company', 'subscription'])
            ->when($this->search, function ($query) {
                $term = $this->search;

                $query->where(function ($query) use ($term) {
                    $query->where('id', $term)
                        ->orWhereHas('company', function ($company) use ($term) {
                            $company->where('alias_name', 'like', "%{$term}%");
                        })
                        ->orWhere('payer_name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(15);

        $stats = [
            'total'    => Invoice::count(),
            'paid'     => Invoice::where('status', 'paid')->count(),
            'pending'  => Invoice::where('status', 'pending')->count(),
            'overdue'  => Invoice::where('status', 'pending')->whereDate('due_date', '<', today())->count(),
        ];

        return view('livewire.dashboard.invoices.invoices-index', [
            'invoices' => $invoices,
            'stats'    => $stats,
            'title'    => 'Faturas',
        ]);
    }
}
