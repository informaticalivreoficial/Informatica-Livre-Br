<?php

namespace App\Livewire\Dashboard\Portifolio;

use App\Models\Portifolio;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class PortifolioIndex extends Component
{
    use WithPagination;

    public int $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public bool $active = false;

    public ?int $delete_id = null;

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 12; // aumenta a quantidade de itens carregados
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function toggleStatus($id)
    {              
        $trabalhos = Portifolio::findOrFail($id);
        $trabalhos->status = !$trabalhos->status;        
        $trabalhos->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Trabalho',
            'text' => 'Essa ação não pode ser desfeita.!',
            'showConfirmButton' => false,
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteTrabalho',
            'confirmParams' => [$id],
        ]);      
    }

    #[On('deleteTrabalho')]
    public function deleteTrabalho($id): void
    {
        $trabalho = Portifolio::findOrFail($id);

        $trabalho->delete();

        $this->dispatch('swal:success', [
            'title' => 'Excluído!',
            'text' => 'Trabalho e todas as imagens foram removidas!',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);              
    } 

    public function render()
    {
        $title = 'Portifólio -Lista de Trabalhos';
        $searchableFields = ['name','content','slug'];
        $trabalhos = Portifolio::query()
            ->with(['categoryRelation', 'cover'])
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.dashboard.portifolio.portifolio-index', [
            'title' => $title,
            'trabalhos' => $trabalhos
        ]);
    }
}
