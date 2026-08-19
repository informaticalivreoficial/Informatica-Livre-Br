<?php

namespace App\Livewire\Dashboard\Roles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $selectedPermissions = [], $roleId, $isEdit = false;

    public string $search = '';

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $title = 'Cargos';

        $roles = Role::with('permissions')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        $permissions = Permission::orderBy('name')->get();

        $stats = [
            'total'       => Role::count(),
            'permissions' => Permission::count(),
        ];

        return view('livewire.dashboard.roles.index', [
            'roles'       => $roles,
            'permissions' => $permissions,
            'stats'       => $stats,
        ])->with('title', $title);
    }

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        $this->dispatch('swal', [
            'title' => 'Criado!',
            'text'  => 'Cargo criado com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEdit = true;
    }

    public function update()
    {
        $role = Role::findOrFail($this->roleId);

        $this->validate();

        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        $this->dispatch('swal', [
            'title' => 'Atualizado!',
            'text'  => 'Cargo atualizado com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir cargo?',
            'text'  => 'Essa ação não pode ser desfeita.',
            'icon'  => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteRole',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteRole')]
    public function delete(int $id): void
    {
        $role = Role::findOrFail($id);

        if ($role->users()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text'  => 'Este cargo possui usuários vinculados.',
                'icon'  => 'error',
            ]);
            return;
        }

        $role->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Cargo removido com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'selectedPermissions', 'roleId', 'isEdit']);
    }
}