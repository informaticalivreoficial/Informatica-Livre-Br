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

        $annualComparison = $this->buildAnnualComparison();

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
            'annualComparison' => $annualComparison,
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
}