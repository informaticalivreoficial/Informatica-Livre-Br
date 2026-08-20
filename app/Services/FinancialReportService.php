<?php

namespace App\Services;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialTransaction;
use Illuminate\Support\Collection;

class FinancialReportService
{
    public function build(int $year, string $period, ?int $month = null, ?int $quarter = null, ?int $half = null): array
    {
        [$startMonth, $endMonth, $label] = $this->periodRange($year, $period, $month, $quarter, $half);

        $transactions = FinancialTransaction::query()
            ->where('year', $year)
            ->whereBetween('month', [$startMonth, $endMonth])
            ->with(['category', 'expense', 'income'])
            ->orderBy('month')
            ->orderBy('type')
            ->get();

        $active = $transactions->filter(fn ($t) => $t->status !== FinancialStatus::CANCELLED);

        $expenses = $active->filter(fn ($t) => $t->type === FinancialType::EXPENSE);
        $incomes = $active->filter(fn ($t) => $t->type === FinancialType::INCOME);

        $expenseTotal = (float) $expenses->sum('amount');
        $incomeTotal = (float) $incomes->sum('amount');

        return [
            'period' => $period,
            'year' => $year,
            'month' => $month,
            'quarter' => $quarter,
            'half' => $half,
            'label' => $label,
            'startMonth' => $startMonth,
            'endMonth' => $endMonth,
            'months' => $this->months(),
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $incomeTotal - $expenseTotal,
            'expenseByCategory' => $this->groupByCategory($expenses),
            'incomeByCategory' => $this->groupByCategory($incomes),
            'transactions' => $transactions,
        ];
    }

    private function months(): array
    {
        return [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];
    }

    private function periodRange(int $year, string $period, ?int $month, ?int $quarter, ?int $half): array
    {
        $months = $this->months();

        if ($period === 'mensal') {
            return [$month, $month, "{$months[$month]} de {$year}"];
        }

        if ($period === 'trimestral') {
            $start = ($quarter - 1) * 3 + 1;

            return [$start, $quarter * 3, "{$quarter}º Trimestre de {$year}"];
        }

        if ($period === 'semestral') {
            $start = ($half - 1) * 6 + 1;

            return [$start, $half * 6, "{$half}º Semestre de {$year}"];
        }

        return [1, 12, "Ano de {$year}"];
    }

    private function groupByCategory(Collection $transactions): Collection
    {
        return $transactions
            ->groupBy(fn ($t) => $t->category_id)
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'category' => $first->category?->name ?? 'Sem categoria',
                    'amount' => (float) $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->values()
            ->sortByDesc('amount')
            ->values();
    }
}