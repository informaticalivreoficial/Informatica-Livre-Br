<?php

namespace App\Livewire\Dashboard\Roles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Component;

class Index extends Component
{
    public $name, $selectedPermissions = [], $roleId, $isEdit = false;

    protected $rules = [
        'name' => 'required|string|unique:roles,name',
        'selectedPermissions' => 'array',
    ];

    public function render()
    {
        $title = 'Cargos';
        return view('livewire.dashboard.roles.index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ])->with('title', $title);
    }

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        session()->flash('message', 'Role criada com sucesso!');
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

        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->roleId,
        ]);

        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        session()->flash('message', 'Role atualizada com sucesso!');
    }

    public function delete($id)
    {
        Role::findOrFail($id)->delete();
        session()->flash('message', 'Role excluída com sucesso!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'selectedPermissions', 'roleId', 'isEdit']);
    }

}
