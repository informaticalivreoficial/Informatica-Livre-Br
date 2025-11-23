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

    public bool $active;

    public ?int $delete_id = null;

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

    public function setDeleteId($id)
    {
        $this->delete_id = $id;
        $this->dispatch('delete-prompt');        
    }

    #[On('goOn-Delete')]
    public function delete(): void
    {
        try {
            $category = CatPost::with('children')->findOrFail($this->delete_id);

            // Verifica se possui subcategorias
            if ($category->children->count() > 0) {
                $this->dispatch('swal', [
                    'title' => 'Erro!',
                    'icon'  => 'error',
                    'text'  => 'Não é possível excluir uma categoria que possui subcategorias.',
                ]);
                return;
            }

            $category->delete(); // já dispara o hook no model

            $this->delete_id = null;

            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'icon'  => 'success',
                'text'  => 'Categoria Removida!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não foi possível excluir a categoria.',
            ]);
        }
    }
}
