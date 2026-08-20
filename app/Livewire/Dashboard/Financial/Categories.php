<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialType;
use App\Models\FinancialBudget;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
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

    public bool $showBudgetModal = false;
    public ?int $budgetCategoryId = null;
    public int $budgetYear;
    public int $budgetMonth;
    public string $budgetAmount = '';

    public function mount()
    {
        $this->budgetYear = now()->year;
        $this->budgetMonth = now()->month;
    }

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

    public function openBudget(int $id): void
    {
        $category = FinancialCategory::findOrFail($id);

        $budget = FinancialBudget::query()
            ->where('category_id', $category->id)
            ->where('year', $this->budgetYear)
            ->where('month', $this->budgetMonth)
            ->first();

        $this->budgetCategoryId = $category->id;
        $this->budgetAmount = $budget ? number_format((float) $budget->amount, 2, ',', '') : '';
        $this->showBudgetModal = true;
    }

    public function closeBudget(): void
    {
        $this->showBudgetModal = false;
        $this->budgetCategoryId = null;
        $this->budgetAmount = '';
        $this->resetValidation();
    }

    public function saveBudget(): void
    {
        $this->validate([
            'budgetCategoryId' => 'required|exists:financial_categories,id',
            'budgetYear' => 'required|integer|min:2000|max:2100',
            'budgetMonth' => 'required|integer|min:1|max:12',
            'budgetAmount' => 'required',
        ]);

        $amount = $this->parseAmount($this->budgetAmount);

        if ($amount < 0) {
            $this->dispatch('swal:error', [
                'title' => 'Valor inválido',
                'text' => 'O orçamento deve ser maior ou igual a zero.',
            ]);
            return;
        }

        FinancialBudget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category_id' => $this->budgetCategoryId,
                'year' => $this->budgetYear,
                'month' => $this->budgetMonth,
            ],
            ['amount' => $amount]
        );

        $this->closeBudget();

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Orçamento salvo com sucesso.',
            'icon' => 'success',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function removeBudget(int $id): void
    {
        FinancialBudget::query()
            ->where('category_id', $id)
            ->where('year', $this->budgetYear)
            ->where('month', $this->budgetMonth)
            ->delete();

        $this->dispatch('swal', [
            'title' => 'Orçamento removido',
            'text' => 'O orçamento desta categoria foi removido.',
            'icon' => 'info',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    private function parseAmount(string $value): float
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
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

        $budgets = FinancialBudget::query()
            ->where('year', $this->budgetYear)
            ->where('month', $this->budgetMonth)
            ->get()
            ->keyBy('category_id');

        $spent = FinancialTransaction::query()
            ->where('type', FinancialType::EXPENSE->value)
            ->where('year', $this->budgetYear)
            ->where('month', $this->budgetMonth)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->groupBy('category_id')
            ->map(fn ($items) => (float) $items->sum('amount'));

        $stats = [
            'total' => FinancialCategory::count(),
            'expenses' => FinancialCategory::where('type', FinancialType::EXPENSE->value)->count(),
            'incomes' => FinancialCategory::where('type', FinancialType::INCOME->value)->count(),
        ];

        return view('livewire.dashboard.financial.categories', [
            'categories' => $categories,
            'stats' => $stats,
            'budgets' => $budgets,
            'spent' => $spent,
            'months' => $this->months(),
        ])->with('title', 'Categorias Financeiras');
    }

    private function months(): array
    {
        return [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];
    }
}