<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialExpense;
use App\Models\FinancialTransaction;
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

    public function markCurrentPaid(int $id): void
    {
        $expense = FinancialExpense::findOrFail($id);

        $transaction = $expense->transactionFor(now()->year, now()->month);

        if (!$transaction) {
            $transaction = FinancialTransaction::create([
                'user_id' => auth()->id(),
                'type' => FinancialType::EXPENSE->value,
                'category_id' => $expense->category_id,
                'expense_id' => $expense->id,
                'year' => now()->year,
                'month' => now()->month,
                'amount' => $expense->amount,
                'status' => FinancialStatus::PENDING->value,
            ]);
        }

        try {
            $transaction->markAsPaid();
            $this->dispatch('swal', [
                'title' => 'Pago!',
                'text' => 'Despesa do mês atual marcada como paga.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoCurrentPayment(int $id): void
    {
        $expense = FinancialExpense::findOrFail($id);

        $transaction = $expense->transactionFor(now()->year, now()->month);

        if (!$transaction || $transaction->status !== FinancialStatus::PAID) {
            $this->dispatch('swal:error', [
                'title' => 'Erro',
                'text' => 'Não há pagamento para desfazer neste mês.',
            ]);
            return;
        }

        $transaction->undoPayment();

        $this->dispatch('swal', [
            'title' => 'Pagamento desfeito',
            'text' => 'A despesa do mês atual voltou a ficar pendente.',
            'icon' => 'info',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
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

        $currentTransactions = FinancialTransaction::query()
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->whereIn('expense_id', $expenses->pluck('id'))
            ->get()
            ->keyBy('expense_id');

        $stats = [
            'total' => FinancialExpense::count(),
            'active' => FinancialExpense::where('active', true)->count(),
            'inactive' => FinancialExpense::where('active', false)->count(),
        ];

        $categories = \App\Models\FinancialCategory::where('type', 'expense')->orderBy('name')->get();

        return view('livewire.dashboard.financial.expenses', [
            'expenses' => $expenses,
            'currentTransactions' => $currentTransactions,
            'stats' => $stats,
            'categories' => $categories,
        ])->with('title', 'Despesas');
    }
}