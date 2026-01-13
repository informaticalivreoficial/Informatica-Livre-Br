<?php

namespace App\Livewire\Dashboard\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Form extends Component
{
    public $roleId;
    public $name;
    public $permissions = [];

    protected $listeners = ['editRole'];

    public function mount($id = null)
    {
        if ($id) {
            $role = Role::findOrFail($id);
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->permissions = $role->permissions()->pluck('name')->toArray();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.roles.form', [
            'allPermissions' => Permission::all()
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
            'permissions' => 'array',
        ]);

        $role = Role::updateOrCreate(['id' => $this->roleId], ['name' => $this->name]);
        $role->syncPermissions($this->permissions);

        session()->flash('message', 'Role salva com sucesso.');
        return redirect()->route('admin.roles.index');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions()->pluck('name')->toArray();
    }
}
