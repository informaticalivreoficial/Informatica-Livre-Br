<?php

namespace App\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'name';

    public $delete_id;

    public string $sortDirection = 'asc';

    public bool $active;

    public bool $updateMode = false;

        
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

    #[Title('Clientes')]
    public function render()
    {
        $users = \App\Models\User::query()->when($this->search, function($query){
                        $query->orWhere('name', 'LIKE', "%{$this->search}%");
                        $query->orWhere('email', "%{$this->search}%");
                    })->where('client', 1)->orderBy($this->sortField, $this->sortDirection)->paginate(35);
        return view('livewire.dashboard.users.users',[
            'users' => $users
        ]);
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

    public function toggleStatus($id)
    {              
        $user = User::find($id);
        $user->status = !$this->active;        
        $user->save();
        $this->active = $user->status;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->dispatch('userId');
        $this->updateMode = true;
    }

}
