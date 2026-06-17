<?php

namespace App\Livewire\Dashboard\Invoices;

use App\Models\Invoice;
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
            $invoice->update([
                'paid_at' => data_get($response, 'status_request.status_date', now()),
            ]);
        }

        $this->toastSuccess(
            "Status atualizado para: {$status}"
        );
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->with(['company', 'subscription'])
            ->when($this->search, function ($query) {
                $query->where('id', $this->search)
                    ->orWhereHas('company', function ($company) {
                        $company->where('alias_name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.dashboard.invoices.invoices-index', [
            'invoices' => $invoices,
            'title'    => 'Faturas',
        ]);
    }
}
