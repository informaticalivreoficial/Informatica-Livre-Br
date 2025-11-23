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

    public function toggleStatus($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $post->status = !$post->status;
            $post->save();

            // $this->dispatch('swal', [
            //     'icon' => 'success',
            //     'title' => 'Status atualizado!',
            //     'text' => $post->status ? 'Post publicado' : 'Post despublicado',
            //     'timer' => 2000,
            //     'showConfirmButton' => false,
            // ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Erro ao atualizar status',
                'text' => $e->getMessage(),
            ]);
        }
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
            $post = Post::findOrFail($this->delete_id);

            $post->delete(); // já dispara o hook no model

            $this->delete_id = null;

            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'icon'  => 'success',
                'text'  => 'O Post e todas as imagens foram removidas!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não foi possível excluir o post.',
            ]);
        }
    }    
}
