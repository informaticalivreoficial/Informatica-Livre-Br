<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SubscriptionIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public ?int $subscriptionIdToDelete = null;
    
    // 🔍 filtros
    public $searchCompany = '';
    public $searchService = '';
    public $status = '';

    protected $queryString = [
        'searchCompany' => ['except' => ''],
        'searchService' => ['except' => ''],
        'status'        => ['except' => ''],
    ];

    public function render()
    {
        $subscriptions = Subscription::query()
            ->with(['company', 'service'])
            ->when($this->searchCompany, fn ($q) =>
                $q->whereHas('company', fn ($q) =>
                    $q->where('alias_name', 'like', "%{$this->searchCompany}%")
                )
            )
            ->when($this->searchService, fn ($q) =>
                $q->whereHas('service', fn ($q) =>
                    $q->where('name', 'like', "%{$this->searchService}%")
                )
            )
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.service.subscription-index', compact('subscriptions'))->with('title', 'Pedidos');
    }

    public function confirmDelete(int $id): void
    {
        $subscription = Subscription::with(['company', 'service'])->findOrFail($id);

        $this->authorize('delete', $subscription);

        // Verificar ANTES de pedir confirmação
        if ($subscription->invoices()->exists()) {
            $this->dispatch('swal:error', [
                'title' => 'Ação não permitida',
                'text'  => 'Este pedido possui faturas e não pode ser excluído.',
            ]);
            return;
        }

        $this->subscriptionIdToDelete = $subscription->id;

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Pedido?',
            'text'  => "Empresa: {$subscription->company->alias_name}\nServiço: {$subscription->service->name}",
            'icon'  => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText'  => 'Cancelar',
            'confirmEvent' => 'deleteConfirmed',
        ]);
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed(): void
    {
        if (!$this->subscriptionIdToDelete) {
            return;
        }

        $subscription = Subscription::findOrFail($this->subscriptionIdToDelete);

        try {
            $subscription->delete();

            $this->dispatch('swal:success', [
                'title' => 'Excluído!',
                'text'  => 'Pedido excluído com sucesso.',
            ]);
        } catch (\Throwable $e) {
            $this->dispatch('swal:error', [
                'title' => 'Erro',
                'text'  => $e->getMessage(), // Agora vai mostrar a mensagem correta
            ]);
        }

        $this->reset('subscriptionIdToDelete');
    }
}
