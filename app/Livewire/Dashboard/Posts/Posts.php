<?php

namespace App\Livewire\Dashboard\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public int $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

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

    public function render()
    {
        $title = 'Lista de Posts';
        $searchableFields = ['title','content','slug','category'];
        $posts = Post::query()
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.posts.posts',[
            'title' => $title,
            'posts' => $posts,
        ]);
    }

    public function toggleStatus($id)
    {              
        $post = Post::findOrFail($id);
        $post->status = !$post->status;        
        $post->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Post?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deletePost',
            'confirmParams' => [$id],
        ]);        
    }

    #[On('deletePost')]
    public function deletePost($id): void
    {
        $post = Post::findOrFail($id);

        $post->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'O Post e todas as imagens foram removidas!',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }    
}
