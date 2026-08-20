<?php

namespace App\Livewire\Dashboard\Financial;

use App\Models\FinancialExpense;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Expenses extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $categoryId = '';
    public string $active = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingActive(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $expense = FinancialExpense::findOrFail($id);
        $expense->update(['active' => !$expense->active]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir despesa?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteExpense',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteExpense')]
    public function deleteExpense(int $id): void
    {
        $expense = FinancialExpense::findOrFail($id);

        if ($expense->transactions()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Esta despesa possui lançamentos vinculados.',
                'icon' => 'error',
            ]);
            return;
        }

        $expense->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Despesa removida com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $expenses = FinancialExpense::query()
            ->with('category')
            ->withCount('transactions')
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->active !== '', fn ($q) => $q->where('active', $this->active === '1'))
            ->orderBy('description')
            ->paginate(15);

        $stats = [
            'total' => FinancialExpense::count(),
            'active' => FinancialExpense::where('active', true)->count(),
            'inactive' => FinancialExpense::where('active', false)->count(),
        ];

        $categories = \App\Models\FinancialCategory::where('type', 'expense')->orderBy('name')->get();

        return view('livewire.dashboard.financial.expenses', [
            'expenses' => $expenses,
            'stats' => $stats,
            'categories' => $categories,
        ])->with('title', 'Despesas');
    }
}