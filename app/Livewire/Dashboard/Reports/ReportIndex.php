<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Invoice;
use App\Models\Subscription;
use Livewire\Component;

class ReportIndex extends Component
{
    public array $stats = [];

    public array $chartSubscriptions = [];
    public array $chartRevenue = [];

    public string $title = 'Relatórios';

    public function mount()
    {
        $this->loadStats();
        $this->loadCharts();
    }

    public function loadStats(): void
    {
        $subscriptions = Subscription::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'active') as active,
                SUM(status = 'cancelled') as cancelled
            ")
            ->first();

        $invoices = Invoice::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'paid') as paid_count,
                SUM(status = 'pending') as pending_count,
                SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as paid_total,
                SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_total,
                SUM(CASE WHEN status = 'pending' AND due_date < NOW() THEN amount ELSE 0 END) as overdue_total
            ")
            ->first();

        $this->stats = [
            // Assinaturas
            'subscriptions_total' => $subscriptions->total,
            'subscriptions_active' => $subscriptions->active,
            'subscriptions_cancelled' => $subscriptions->cancelled,

            // Faturas
            'invoices_total' => $invoices->total,
            'invoices_paid' => $invoices->paid_count,
            'invoices_pending' => $invoices->pending_count,

            // Financeiro
            'revenue_paid' => $invoices->paid_total,
            'revenue_pending' => $invoices->pending_total,
            'revenue_overdue' => $invoices->overdue_total,
        ];
    }

    public function loadCharts(): void
    {
        $this->chartSubscriptions = [
            'labels' => ['Ativas', 'Canceladas'],
            'data' => [
                $this->stats['subscriptions_active'],
                $this->stats['subscriptions_cancelled'],
            ],
        ];

        $this->chartRevenue = [
            'labels' => ['Pagas', 'Pendentes', 'Atrasadas'],
            'data' => [
                $this->stats['revenue_paid'],
                $this->stats['revenue_pending'],
                $this->stats['revenue_overdue'],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.reports.report-index', [
            'title' => $this->title,
        ]);
    }
}
