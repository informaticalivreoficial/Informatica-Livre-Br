<?php

namespace App\Livewire\Dashboard\Posts;

use App\Models\CatPost;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class CatPosts extends Component
{
    use WithPagination;

    public int $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $listeners = ['category-saved' => '$refresh'];

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
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
        $category = CatPost::with('children')->findOrFail($id);

        $newStatus = ! (bool) $category->status;

        // atualiza a categoria clicada
        $category->update([
            'status' => $newStatus,
        ]);

        // se for PAI, replica para as filhas
        if ($category->children->isNotEmpty()) {
            $category->children()->update([
                'status' => $newStatus,
            ]);
        }
    }

    public function setDeleteId($id)
    {        
        $category = CatPost::findOrFail($id);

        if($category->children()->count() > 0){
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não é possível excluir uma categoria que possui subcategorias.',
            ]);
            return;
        }

        if($category->countposts() > 0){
            $text = 'Essa categoria possui posts cadastrados e todos serão removidos. Deseja excluir mesmo assim?';
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir ' . ($category->children()->count() > 0 ? 'SubCategoria' : 'Categoria'),
            'text' => (isset($text) ? $text : 'Essa ação não pode ser desfeita.'),
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteCategory',
            'confirmParams' => [$id],
        ]);       
    }

    #[On('deleteCategory')]
    public function deleteCategory($id): void
    {
        $category = CatPost::findOrFail($id);

        $category->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => ($category->children()->count() > 0 ? 'SubCategoria' : 'Categoria') . ' excluída com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $title = 'Categorias de Posts';
        $searchableFields = ['title','content','slug'];
        $categories = CatPost::query()
            ->whereNull('id_pai')
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.posts.cat-posts',[
            'title' => $title,
            'categories' => $categories,
        ]);
    }
}
