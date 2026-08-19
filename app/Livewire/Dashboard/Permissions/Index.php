<?php

namespace App\Livewire\Dashboard\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $permission_id;
    public $isEditing = false;

    public string $search = '';

    public function rules()
    {
        return [
            'name' => 'required|min:3|unique:permissions,name,' . $this->permission_id,
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $title = 'Permissões';

        $permissions = Permission::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        $stats = [
            'total' => Permission::count(),
            'roles' => Role::count(),
        ];

        return view('livewire.dashboard.permissions.index', [
            'permissions' => $permissions,
            'stats'       => $stats,
        ])->with('title', $title);
    }

    public function save()
    {
        $this->validate();

        Permission::create(['name' => $this->name]);

        $this->dispatch('swal', [
            'title' => 'Criada!',
            'text'  => 'Permissão criada com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
        $this->reset('name');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $permission = Permission::findOrFail($this->permission_id);
        $permission->update(['name' => $this->name]);

        $this->dispatch('swal', [
            'title' => 'Atualizada!',
            'text'  => 'Permissão atualizada com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir permissão?',
            'text'  => 'Essa ação não pode ser desfeita.',
            'icon'  => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deletePermission',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deletePermission')]
    public function delete(int $id): void
    {
        $permission = Permission::findOrFail($id);

        if ($permission->roles()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text'  => 'Esta permissão está vinculada a um ou mais cargos.',
                'icon'  => 'error',
            ]);
            return;
        }

        $permission->delete();

        $this->dispatch('swal', [
            'title' => 'Excluída!',
            'text'  => 'Permissão removida com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'permission_id', 'isEditing']);
    }
}