<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ServiceIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function render()
    {
        $services = Service::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.dashboard.service.service-index', compact('services'))
            ->with('title', 'Serviços');
    }

    public function sortBy($field)
    {
        $this->sortDirection =
            $this->sortField === $field && $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';

        $this->sortField = $field;
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir serviço?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteService',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteService')]
    public function deleteService(int $id): void
    {
        $service = Service::findOrFail($id);

        if ($service->subscriptions()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text'  => 'Este serviço possui assinaturas vinculadas.',
                'icon'  => 'error',
            ]);
            return;
        }

        $service->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Serviço removido com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
}
