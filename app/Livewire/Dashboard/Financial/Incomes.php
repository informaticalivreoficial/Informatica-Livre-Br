<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialIncome;
use App\Models\FinancialTransaction;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Incomes extends Component
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
        $income = FinancialIncome::findOrFail($id);
        $income->update(['active' => !$income->active]);
    }

    public function markCurrentReceived(int $id): void
    {
        $income = FinancialIncome::findOrFail($id);

        $transaction = $income->transactionFor(now()->year, now()->month);

        if (!$transaction) {
            $transaction = FinancialTransaction::create([
                'user_id' => auth()->id(),
                'type' => FinancialType::INCOME->value,
                'category_id' => $income->category_id,
                'income_id' => $income->id,
                'year' => now()->year,
                'month' => now()->month,
                'amount' => $income->amount,
                'status' => FinancialStatus::PENDING->value,
            ]);
        }

        try {
            $transaction->markAsReceived();
            $this->dispatch('swal', [
                'title' => 'Recebido!',
                'text' => 'Receita do mês atual marcada como recebida.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoCurrentReceipt(int $id): void
    {
        $income = FinancialIncome::findOrFail($id);

        $transaction = $income->transactionFor(now()->year, now()->month);

        if (!$transaction || $transaction->status !== FinancialStatus::RECEIVED) {
            $this->dispatch('swal:error', [
                'title' => 'Erro',
                'text' => 'Não há recebimento para desfazer neste mês.',
            ]);
            return;
        }

        $transaction->undoReceipt();

        $this->dispatch('swal', [
            'title' => 'Recebimento desfeito',
            'text' => 'A receita do mês atual voltou a ficar pendente.',
            'icon' => 'info',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir receita?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteIncome',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteIncome')]
    public function deleteIncome(int $id): void
    {
        $income = FinancialIncome::findOrFail($id);

        if ($income->transactions()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Esta receita possui lançamentos vinculados.',
                'icon' => 'error',
            ]);
            return;
        }

        $income->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Receita removida com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $incomes = FinancialIncome::query()
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
            ->whereIn('income_id', $incomes->pluck('id'))
            ->get()
            ->keyBy('income_id');

        $stats = [
            'total' => FinancialIncome::count(),
            'active' => FinancialIncome::where('active', true)->count(),
            'inactive' => FinancialIncome::where('active', false)->count(),
        ];

        $categories = \App\Models\FinancialCategory::where('type', 'income')->orderBy('name')->get();

        return view('livewire.dashboard.financial.incomes', [
            'incomes' => $incomes,
            'currentTransactions' => $currentTransactions,
            'stats' => $stats,
            'categories' => $categories,
        ])->with('title', 'Receitas');
    }
}