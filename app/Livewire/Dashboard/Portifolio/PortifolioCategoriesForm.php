<?php

namespace App\Livewire\Dashboard\Portifolio;

use App\Models\CatPortifolio;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class PortifolioCategoriesForm extends Component
{
    public ?int $id = null;
    public ?string $title = null;
    public int $status = 1;
    public ?string $type = null;
    public ?int $parentId = null;

    #[On('loadCategory')]
    public function loadCategory($payload = [])
    {
        // Garante que pegamos a chave correta
        $data = $payload['payload'] ?? $payload;

        // Edição
        if (!empty($data['editId'])) {
            $category = CatPortifolio::find($data['editId']);
            if ($category) {
                $this->id       = $category->id;
                $this->title    = $category->title;
                $this->status   = $category->status;
                $this->type     = $category->type;
                $this->parentId = $category->id_pai;
            }
        }

        // Nova subcategoria
        if (!empty($data['categoryId'])) {
            $this->parentId = $data['categoryId'];
            $parent = CatPortifolio::find($this->parentId);
            if ($parent) {
                $this->type = $parent->type;
            }
        }
    }

    public function save(): void
    {        
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => Rule::requiredIf($this->parentId === null),
            'status' => 'required|boolean',
            'parentId' => 'nullable|exists:cat_portifolios,id',
        ]);

        CatPortifolio::updateOrCreate(
            ['id' => $this->id],
            [
                'title' => $this->title,
                'type' => $this->type,
                'status' => $this->status,
                'id_pai' => $this->parentId,
            ]
        );

        // Fecha modal
        $this->dispatch('category-saved');

        $this->resetForm();
    }

    #[On('resetForm')]
    public function resetForm()
    {
        $this->reset(['id', 'title', 'type', 'status', 'parentId']);
        $this->status = 1;
    }

    public function getModalTitleProperty()
    {
        if ($this->id && $this->parentId) {
            return 'Editar Subcategoria';
        }

        if ($this->id) {
            return 'Editar Categoria';
        }

        if ($this->parentId) {
            return 'Cadastrar Subcategoria';
        }

        return 'Cadastrar Nova Categoria';
    }

    public function render()
    {
        return view('livewire.dashboard.portifolio.portifolio-categories-form');
    }
}
