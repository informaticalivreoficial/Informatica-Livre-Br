<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Models\FinancialTransaction;
use Livewire\Component;

class Dashboard extends Component
{
    public int $year;

    public function mount()
    {
        $this->year = now()->year;
    }

    public function markExpensePaid(int $id): void
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

    public function markIncomeReceived(int $id): void
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

    public function markAllExpensesPaid(): void
    {
        $count = FinancialTransaction::expenses()
            ->forMonth($this->year, now()->month)
            ->where('status', FinancialStatus::PENDING->value)
            ->get()
            ->each(fn ($t) => $t->markAsPaid())
            ->count();

        $this->dispatch('swal', [
            'title' => 'Concluído',
            'text' => "{$count} despesa(s) marcada(s) como paga(s).",
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function markAllIncomesReceived(): void
    {
        $count = FinancialTransaction::incomes()
            ->forMonth($this->year, now()->month)
            ->where('status', FinancialStatus::PENDING->value)
            ->get()
            ->each(fn ($t) => $t->markAsReceived())
            ->count();

        $this->dispatch('swal', [
            'title' => 'Concluído',
            'text' => "{$count} receita(s) marcada(s) como recebida(s).",
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $month = now()->month;

        $incomeTotal = FinancialTransaction::incomes()
            ->forMonth($this->year, $month)
            ->where('status', '!=', FinancialStatus::CANCELLED->value)
            ->sum('amount');

        $expenseTotal = FinancialTransaction::expenses()
            ->forMonth($this->year, $month)
            ->where('status', '!=', FinancialStatus::CANCELLED->value)
            ->sum('amount');

        $receivedInMonth = FinancialTransaction::incomes()
            ->forMonth($this->year, $month)
            ->where('status', FinancialStatus::RECEIVED->value)
            ->sum('amount');

        $paidInMonth = FinancialTransaction::expenses()
            ->forMonth($this->year, $month)
            ->where('status', FinancialStatus::PAID->value)
            ->sum('amount');

        $indicators = [
            'pendingExpenses' => FinancialTransaction::expenses()
                ->forMonth($this->year, $month)
                ->where('status', FinancialStatus::PENDING->value)
                ->count(),
            'overdueExpenses' => $this->countOverdue($month),
            'paidExpenses' => FinancialTransaction::expenses()
                ->forMonth($this->year, $month)
                ->where('status', FinancialStatus::PAID->value)
                ->count(),
            'expectedIncomes' => FinancialTransaction::incomes()
                ->forMonth($this->year, $month)
                ->where('status', FinancialStatus::PENDING->value)
                ->count(),
            'receivedIncomes' => FinancialTransaction::incomes()
                ->forMonth($this->year, $month)
                ->where('status', FinancialStatus::RECEIVED->value)
                ->count(),
        ];

        $pendingExpenses = FinancialTransaction::expenses()
            ->forMonth($this->year, $month)
            ->where('status', FinancialStatus::PENDING->value)
            ->with(['expense', 'category'])
            ->orderBy('expense_id')
            ->get();

        $pendingIncomes = FinancialTransaction::incomes()
            ->forMonth($this->year, $month)
            ->where('status', FinancialStatus::PENDING->value)
            ->with(['income', 'category'])
            ->orderBy('income_id')
            ->get();

        $annualComparison = $this->buildAnnualComparison();

        $categoryChart = $this->buildCategoryChart($month);

        $projection = $this->buildProjection();

        $years = FinancialTransaction::query()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('livewire.dashboard.financial.dashboard', [
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'receivedInMonth' => $receivedInMonth,
            'paidInMonth' => $paidInMonth,
            'balance' => $incomeTotal - $expenseTotal,
            'indicators' => $indicators,
            'pendingExpenses' => $pendingExpenses,
            'pendingIncomes' => $pendingIncomes,
            'annualComparison' => $annualComparison,
            'categoryChart' => $categoryChart,
            'projection' => $projection,
            'years' => $years,
        ])->with('title', 'Dashboard Financeiro');
    }

    private function countOverdue(int $month): int
    {
        $expenses = FinancialTransaction::expenses()
            ->forMonth($this->year, $month)
            ->where('status', FinancialStatus::PENDING->value)
            ->with('expense')
            ->get();

        return $expenses->filter(fn ($t) => $t->isOverdue())->count();
    }

    private function buildAnnualComparison(): array
    {
        $transactions = FinancialTransaction::query()
            ->forYear($this->year)
            ->where('status', '!=', FinancialStatus::CANCELLED->value)
            ->get();

        $months = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = [
                'income' => 0.0,
                'expense' => 0.0,
            ];
        }

        foreach ($transactions as $transaction) {
            if ($transaction->isIncome()) {
                $months[$transaction->month]['income'] += (float) $transaction->amount;
            } else {
                $months[$transaction->month]['expense'] += (float) $transaction->amount;
            }
        }

        return $months;
    }

    private function buildCategoryChart(int $month): array
    {
        $expenses = FinancialTransaction::expenses()
            ->forMonth($this->year, $month)
            ->where('status', '!=', FinancialStatus::CANCELLED->value)
            ->with('category')
            ->get();

        return $expenses
            ->groupBy(fn ($t) => $t->category_id)
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'label' => $first->category?->name ?? 'Sem categoria',
                    'value' => (float) $items->sum('amount'),
                    'color' => $first->category?->color ?? '#6b7280',
                ];
            })
            ->values()
            ->sortByDesc('value')
            ->values()
            ->all();
    }

    private function buildProjection(): array
    {
        $start = now()->startOfMonth();
        $months = [];

        for ($i = 0; $i < 6; $i++) {
            $date = $start->copy()->addMonths($i);
            $year = $date->year;
            $month = $date->month;

            $income = FinancialTransaction::incomes()
                ->forMonth($year, $month)
                ->where('status', FinancialStatus::PENDING->value)
                ->sum('amount');

            $expense = FinancialTransaction::expenses()
                ->forMonth($year, $month)
                ->where('status', FinancialStatus::PENDING->value)
                ->sum('amount');

            $months[] = [
                'label' => $date->translatedFormat('M/Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'balance' => (float) $income - (float) $expense,
            ];
        }

        return $months;
    }
}