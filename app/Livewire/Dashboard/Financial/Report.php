<?php

namespace App\Livewire\Dashboard\Financial;

use App\Models\FinancialTransaction;
use App\Services\FinancialReportService;
use Livewire\Component;

class Report extends Component
{
    public string $period = 'mensal';
    public int $year;
    public int $month;
    public int $quarter = 1;
    public int $half = 1;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->quarter = (int) ceil(now()->month / 3);
        $this->half = now()->month <= 6 ? 1 : 2;
    }

    public function rules()
    {
        return [
            'period' => 'required|in:mensal,trimestral,semestral,anual',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required_if:period,mensal|integer|between:1,12',
            'quarter' => 'required_if:period,trimestral|integer|between:1,4',
            'half' => 'required_if:period,semestral|integer|between:1,2',
        ];
    }

    public function render()
    {
        $data = app(FinancialReportService::class)->build(
            $this->year,
            $this->period,
            $this->period === 'mensal' ? $this->month : null,
            $this->period === 'trimestral' ? $this->quarter : null,
            $this->period === 'semestral' ? $this->half : null,
        );

        $years = FinancialTransaction::query()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('livewire.dashboard.financial.report', [
            'data' => $data,
            'years' => $years,
            'exportUrl' => route('financial.report.export', [
                'period' => $this->period,
                'year' => $this->year,
                'month' => $this->period === 'mensal' ? $this->month : null,
                'quarter' => $this->period === 'trimestral' ? $this->quarter : null,
                'half' => $this->period === 'semestral' ? $this->half : null,
            ]),
        ])->with('title', 'Relatório Financeiro');
    }
}