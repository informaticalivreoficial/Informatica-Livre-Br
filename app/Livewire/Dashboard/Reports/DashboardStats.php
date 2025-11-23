<?php

namespace App\Livewire\Dashboard\Reports;

use Livewire\Component;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $barChartData = [];
    public $donutChartData = [];

    public function mount()
    {
        // ---------- BAR CHART (últimos 6 meses)
        $analyticsData = Analytics::get(
            Period::months(6),
            metrics: ['totalUsers', 'sessions'],
            dimensions: ['month']
        );

        $sorted = collect($analyticsData)->sortBy('month');

        $currentYear = now()->year;
        $currentMonth = now()->month;

        $labels = [];
        $users = [];
        $sessions = [];

        foreach ($sorted as $row) {
            $month = intval($row['month']);

            $year = $month > $currentMonth ? $currentYear - 1 : $currentYear;

            $labels[] = Carbon::createFromDate($year, $month)->translatedFormat('M/Y');
            $users[] = intval($row['totalUsers']);
            $sessions[] = intval($row['sessions']);
        }

        $this->barChartData = [
            'labels' => $labels,
            'users' => $users,
            'sessions' => $sessions,
        ];

        // ---------- DONUT (Top browsers últimos 5 meses)
        $topBrowser = Analytics::fetchTopBrowsers(Period::months(5), 5);

        $this->donutChartData = [
            'labels' => collect($topBrowser)->pluck('browser'),
            'values' => collect($topBrowser)->pluck('screenPageViews'),
        ];
    }
    
    public function render()
    {
        return view('livewire.dashboard.reports.dashboard-stats');
    }
}
