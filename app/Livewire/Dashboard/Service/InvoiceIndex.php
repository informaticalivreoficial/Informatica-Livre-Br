<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Invoice;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Services\PagHiperService;
use App\Enums\SubscriptionStatus;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceIndex extends Component
{
    public Subscription $subscription;

    public bool $showCreateModal = false;

    public $due_date;
    public $amount;

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription->load([
            'company',
            'service',
            'invoices' => fn ($q) => $q->orderByDesc('due_date'),
        ]);
    }
    public function render()
    {
        return view('livewire.dashboard.service.invoice-index')->with('title', 'Cobranças');
    }

    protected function rules()
    {
        return [
            'amount'   => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
        ];
    }

    public function openCreateModal()
    {
        if ($this->subscription->status !== SubscriptionStatus::ACTIVE) {
            throw ValidationException::withMessages([
                'subscription' => 'A subscription não está ativa.'
            ]);
        }

        $this->amount   = (float) $this->subscription->amount;
        $this->due_date = now()->addDays(7)->toDateString();

        $this->showCreateModal = true;
    }

    public function createInvoice()
    {
        $this->validate();

        // 🔒 Evita duplicidade no mesmo mês (recorrente)
        $exists = $this->subscription->invoices()
            ->where('status', 'pending')
            ->whereMonth('due_date', Carbon::parse($this->due_date)->month)
            ->whereYear('due_date', Carbon::parse($this->due_date)->year)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'due_date' => 'Já existe uma fatura pendente para este período.'
            ]);
        }

        // ✅ Cria invoice local
        $invoice = Invoice::create([
            'subscription_id' => $this->subscription->id,
            'company_id'      => $this->subscription->company_id,
            'amount'          => $this->amount,
            'due_date'        => $this->due_date,
            'status'          => 'pending',
        ]);

        // 🔌 Cria cobrança no PagHiper
        $pagHiper = new PagHiperService();
        $response = $pagHiper->createInvoice($invoice);

        // ✅ Atualiza dados do gateway
        $invoice->update([
            'gateway'           => 'paghiper',
            'gateway_reference' => $response['transaction_id'] ?? null,
            'payment_url'       => $response['bank_slip']['url_slip'] ?? null,
            'pix_qrcode'        => $response['pix']['qrcode_image'] ?? null,
        ]);

        $this->subscription->load('invoices');        

        $this->reset(['showCreateModal', 'amount', 'due_date']);

        $this->dispatch('swal:success', [
            'title' => 'Fatura criada',
            'text'  => 'A cobrança foi gerada com sucesso.',
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Cobrança?',
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
            'text'  => 'Cobrança foi removida com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
}
