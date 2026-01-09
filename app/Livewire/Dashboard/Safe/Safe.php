<?php

namespace App\Livewire\Dashboard\Safe;

use App\Models\Safe as ModelsSafe;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Safe extends Component
{
    use WithPagination;

    public int $perPage = 24;

    public string $search = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $delete_id = null;

    public $showSafeModal = false;

    public bool $active = true;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $title = 'Cofre - Itens do Cofre';
        $searchableFields = ['title','email','login','content'];
        $safes = ModelsSafe::query()
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.safe.safe', [
            'safes' => $safes
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
        $safe = ModelsSafe::findOrFail($id);
        $safe->status = ! $safe->status;
        $safe->save();
    }

    public function revealPassword(int $id): void
    {
        $safe = ModelsSafe::findOrFail($id);

        try {
            $password = Crypt::decryptString($safe->password);
        } catch (\Throwable $e) {
            $password = $safe->password;
        }

        $this->dispatch('reveal-password', [
            'id' => $id,
            'password' => $password,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir acesso?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'onConfirmEvent' => 'deleteSafe',
            'onConfirmParams' => [$id], // 👈 PASSA O ID
        ]);
    }

    #[On('deleteSafe')]
    public function deleteSafe(int $id): void
    {
        $safe = ModelsSafe::find($id);

        if (! $safe) {
            return;
        }

        if ($safe->logo && Storage::disk('public')->exists($safe->logo)) {
            Storage::disk('public')->delete($safe->logo);
        }

        $safe->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'O acesso foi removido com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        $this->resetPage();
    }
}
