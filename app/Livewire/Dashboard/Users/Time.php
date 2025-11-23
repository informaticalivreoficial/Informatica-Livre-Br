<?php

namespace App\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Time extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'name';

    public $delete_id;

    public string $sortDirection = 'asc';

    public bool $active;

    public function render()
    {
        $title = 'Time de Usuários';

        $users = User::query()
            ->where(function($query) {
                $query->where('editor', 1)->orWhere('admin', 1);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.dashboard.users.time', [
            'users' => $users
        ])->with('title', $title);
    }

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
        $user = User::find($id);
        $user->status = !$this->active;        
        $user->save();
        $this->active = $user->status;
    }

    public function setDeleteId($id)
    {
        $this->delete_id = $id;
        $this->dispatch('delete-prompt');        
    }
    #[On('goOn-Delete')]
    public function delete()
    {
        $user = \App\Models\User::where('id', $this->delete_id)->first();
        if(!empty($user)){
            $user->delete();
            
            $this->dispatch('swal', [
                'title' =>  'Success!',
                'icon' => 'success',
                'text' => 'Cliente removido com sucesso!'
            ]);
        }
    }
}
