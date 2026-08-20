<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Models\FinancialTransaction;
use Livewire\Component;

class AnnualView extends Component
{
    public int $year;

    public function mount()
    {
        $this->year = now()->year;
    }

    public function render()
    {
        $expenseRows = $this->buildRows(FinancialTransaction::expenses()->forYear($this->year)->with(['expense', 'category'])->get());
        $incomeRows = $this->buildRows(FinancialTransaction::incomes()->forYear($this->year)->with(['income', 'category'])->get());

        $years = FinancialTransaction::query()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('livewire.dashboard.financial.annual-view', [
            'expenseRows' => $expenseRows,
            'incomeRows' => $incomeRows,
            'years' => $years,
            'months' => $this->months(),
        ])->with('title', 'Visão Anual Financeira');
    }

    private function buildRows($transactions): array
    {
        $grouped = $transactions->groupBy(fn ($t) => $t->expense_id ?? $t->income_id);

        $rows = [];

        foreach ($grouped as $sourceId => $items) {
            $first = $items->first();
            $source = $first->expense ?? $first->income;

            $monthly = array_fill(1, 12, 0.0);

            foreach ($items as $item) {
                if ($item->status === FinancialStatus::CANCELLED->value) {
                    continue;
                }
                $monthly[$item->month] = (float) $item->amount;
            }

            $rows[] = [
                'description' => $source?->description ?? '—',
                'category' => $first->category?->name ?? '—',
                'monthly' => $monthly,
                'total' => array_sum($monthly),
            ];
        }

        usort($rows, fn ($a, $b) => strcmp($a['description'], $b['description']));

        return $rows;
    }

    private function months(): array
    {
        return [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez',
        ];
    }
}