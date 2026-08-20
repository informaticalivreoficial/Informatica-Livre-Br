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

    public string $sortDirection = 'asc';

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
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        $this->dispatch('swal', [
            'title' => $user->status ? 'Ativado!' : 'Desativado!',
            'text' => $user->status ? 'Usuário ativado com sucesso.' : 'Usuário desativado.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Usuário?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteUser',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteUser')]
    public function deleteUser($id): void
    {
        $user = User::where('id', $id)->first();
        if (!empty($user)) {
            $this->authorize('delete', $user);
            $user->delete();

            $this->dispatch('swal', [
                'title' => 'Excluído!',
                'text' => 'Usuário removido com sucesso!',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);
        }
    }

    public function render()
    {
        $title = 'Time de Usuários';

        $users = User::with('roles')
            ->role(['manager', 'super-admin'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'LIKE', "%{$this->search}%")
                        ->orWhere('email', 'LIKE', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $stats = [
            'total' => User::role(['manager', 'super-admin'])->count(),
            'active' => User::role(['manager', 'super-admin'])->where('status', true)->count(),
            'inactive' => User::role(['manager', 'super-admin'])->where('status', false)->count(),
        ];

        return view('livewire.dashboard.users.time', [
            'users' => $users,
            'stats' => $stats,
        ])->with('title', $title);
    }
}