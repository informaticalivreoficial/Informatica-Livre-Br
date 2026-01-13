<?php

namespace App\Livewire\Dashboard\Permissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    public $name;
    public $permission_id;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|min:3|unique:permissions,name',
    ];

    public function render()
    {
        $title = 'Permissões';
        return view('livewire.dashboard.permissions.index', [
            'permissions' => Permission::all(),
        ])->with('title', $title);
    }

    public function save()
    {
        $this->validate();

        Permission::create(['name' => $this->name]);

        session()->flash('success', 'Permissão criada com sucesso!');
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
        $this->validate([
            'name' => 'required|min:3|unique:permissions,name,' . $this->permission_id,
        ]);

        $permission = Permission::findOrFail($this->permission_id);
        $permission->update(['name' => $this->name]);

        session()->flash('success', 'Permissão atualizada!');
        $this->reset(['name', 'permission_id', 'isEditing']);
    }

    public function delete($id)
    {
        Permission::findOrFail($id)->delete();
        session()->flash('success', 'Permissão excluída!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'permission_id', 'isEditing']);
    }
}
