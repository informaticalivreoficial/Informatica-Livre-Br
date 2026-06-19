<?php

namespace App\Livewire\Dashboard\Portifolio;

use App\Models\Portifolio;
use App\Services\SocialPostService;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Traits\WithToastr;
use Illuminate\Support\Str;

class PortifolioIndex extends Component
{
    use WithPagination;
    use WithToastr;

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

    public function postar(int $id, string $rede): void
    {
        $portifolio = Portifolio::findOrFail($id);

        $response = app(SocialPostService::class)
            ->post($rede, [
                'message' => $portifolio->headline ?? $portifolio->name,
                'image'   => $portifolio->cover(),
                'link'    => route('web.portifolio.single', $portifolio->slug),
                'tags'    => $this->formatTagsAsHashtags($portifolio->tags),
            ]);

        if ($response['success']) { 
            $this->toastSuccess("Post enviado para {$rede}!");
        } else {
            $this->toastError("Falha ao postar no {$rede}.");
        }
    }

    private function formatTagsAsHashtags(?string $tags)
    {
        if (empty($tags)) {
            return [];
        }
 
        return collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->map(fn ($tag) => '#' . Str::lower(preg_replace('/[^\p{L}\p{N}]+/u', '', $tag)))
            ->values();
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
            ->with(['categoryRelation', 'images'])
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
