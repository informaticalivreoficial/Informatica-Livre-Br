<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialType;
use App\Models\FinancialCategory;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $type = '';

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $categoryType = 'expense';
    public string $color = '#00ff41';
    public ?string $icon = null;
    public bool $active = true;

    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'categoryType' => 'required|in:income,expense',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|string|max:50',
            'active' => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $category = FinancialCategory::findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->categoryType = $category->type->value;
        $this->color = $category->color;
        $this->icon = $category->icon;
        $this->active = $category->active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        FinancialCategory::updateOrCreate(
            ['id' => $this->editingId],
            [
                'user_id' => auth()->id(),
                'name' => $this->name,
                'type' => $this->categoryType,
                'color' => $this->color,
                'icon' => $this->icon,
                'active' => $this->active,
            ]
        );

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Categoria salva com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $category = FinancialCategory::findOrFail($id);
        $category->update(['active' => !$category->active]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir categoria?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteCategory',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteCategory')]
    public function deleteCategory(int $id): void
    {
        $category = FinancialCategory::findOrFail($id);

        if ($category->transactions()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Esta categoria possui lançamentos vinculados.',
                'icon' => 'error',
            ]);
            return;
        }

        $category->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Categoria removida com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->categoryType = 'expense';
        $this->color = '#00ff41';
        $this->icon = null;
        $this->active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = FinancialCategory::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->withCount(['transactions'])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        $stats = [
            'total' => FinancialCategory::count(),
            'expenses' => FinancialCategory::where('type', FinancialType::EXPENSE->value)->count(),
            'incomes' => FinancialCategory::where('type', FinancialType::INCOME->value)->count(),
        ];

        return view('livewire.dashboard.financial.categories', [
            'categories' => $categories,
            'stats' => $stats,
        ])->with('title', 'Categorias Financeiras');
    }
}