<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialTransaction;
use Livewire\Component;

class Transactions extends Component
{
    public int $year;
    public int $month;

    public ?int $editingId = null;
    public string $editingAmount = '';

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function generateYear(): void
    {
        $result = FinancialTransaction::generateForYear($this->year);

        $this->dispatch('swal', [
            'title' => 'Lançamentos gerados',
            'text' => "{$result['created']} lançamentos criados e {$result['skipped']} já existentes.",
            'icon' => 'success',
            'timer' => 2500,
            'showConfirmButton' => false,
        ]);
    }

    public function startEdit(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);
        $this->editingId = $transaction->id;
        $this->editingAmount = number_format((float) $transaction->amount, 2, ',', '');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editingAmount = '';
    }

    public function saveAmount(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $amount = $this->parseAmount($this->editingAmount);

        if ($amount < 0) {
            $this->dispatch('swal:error', [
                'title' => 'Valor inválido',
                'text' => 'O valor deve ser maior ou igual a zero.',
            ]);
            return;
        }

        $transaction->update(['amount' => $amount]);

        $this->editingId = null;
        $this->editingAmount = '';

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Valor atualizado.',
            'icon' => 'success',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function markPaid(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->markAsPaid();
            $this->dispatch('swal', [
                'title' => 'Pago!',
                'text' => 'Despesa marcada como paga.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoPayment(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->undoPayment();
            $this->dispatch('swal', [
                'title' => 'Pagamento desfeito',
                'text' => 'A despesa voltou a ficar pendente.',
                'icon' => 'info',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function markReceived(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->markAsReceived();
            $this->dispatch('swal', [
                'title' => 'Recebido!',
                'text' => 'Receita marcada como recebida.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoReceipt(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->undoReceipt();
            $this->dispatch('swal', [
                'title' => 'Recebimento desfeito',
                'text' => 'A receita voltou a ficar pendente.',
                'icon' => 'info',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function confirmCancel(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Cancelar lançamento?',
            'text' => 'O lançamento será marcado como cancelado.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, cancelar',
            'cancelButtonText' => 'Voltar',
            'confirmEvent' => 'cancelTransaction',
            'confirmParams' => [$id],
        ]);
    }

    #[\Livewire\Attributes\On('cancelTransaction')]
    public function cancelTransaction(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        if ($transaction->status === FinancialStatus::CANCELLED) {
            $transaction->update([
                'status' => $transaction->isExpense() ? FinancialStatus::PENDING : FinancialStatus::PENDING,
                'paid_at' => null,
                'received_at' => null,
            ]);
        } else {
            $transaction->update([
                'status' => FinancialStatus::CANCELLED,
                'paid_at' => null,
                'received_at' => null,
            ]);
        }
    }

    private function parseAmount(string $value): float
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        $expenses = FinancialTransaction::expenses()
            ->forMonth($this->year, $this->month)
            ->with(['expense', 'category'])
            ->orderBy('expense_id')
            ->get();

        $incomes = FinancialTransaction::incomes()
            ->forMonth($this->year, $this->month)
            ->with(['income', 'category'])
            ->orderBy('income_id')
            ->get();

        $expenseTotal = $expenses->where('status', '!=', FinancialStatus::CANCELLED->value)->sum('amount');
        $incomeTotal = $incomes->where('status', '!=', FinancialStatus::CANCELLED->value)->sum('amount');

        $paidExpenses = $expenses->where('status', FinancialStatus::PAID->value)->sum('amount');
        $pendingExpenses = $expenses->where('status', FinancialStatus::PENDING->value)->sum('amount');
        $receivedIncomes = $incomes->where('status', FinancialStatus::RECEIVED->value)->sum('amount');
        $pendingIncomes = $incomes->where('status', FinancialStatus::PENDING->value)->sum('amount');

        $years = FinancialTransaction::query()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('livewire.dashboard.financial.transactions', [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'expenseTotal' => $expenseTotal,
            'incomeTotal' => $incomeTotal,
            'paidExpenses' => $paidExpenses,
            'pendingExpenses' => $pendingExpenses,
            'receivedIncomes' => $receivedIncomes,
            'pendingIncomes' => $pendingIncomes,
            'years' => $years,
            'months' => $this->months(),
        ])->with('title', 'Lançamentos Financeiros');
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