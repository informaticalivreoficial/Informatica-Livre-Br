<?php

namespace App\Livewire\Dashboard;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Subscription;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $title = 'Painel de Controle';

        return view('livewire.dashboard.dashboard', [
            'title' => $title,

            // Cadastros
            'companyCount' => Company::count(),
            'companyYearCount' => Company::whereYear('created_at', now()->year)->count(),

            'invoicesCount' => Invoice::count(),
            'invoicesYearCount' => Invoice::whereYear('created_at', now()->year)->count(),

            'subscriptionsCount' => Subscription::count(),
            'subscriptionsYearCount' => Subscription::whereYear('created_at', now()->year)->count(),

            // Financeiro
            'receivedThisMonth' => Invoice::query()
                ->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),

            'pendingAmount' => Invoice::query()
                ->where('status', 'pending')
                ->sum('amount'),

            'overdueAmount' => Invoice::query()
                ->where('status', 'pending')
                ->whereDate('due_date', '<', today())
                ->sum('amount'),

            'activeSubscriptions' => Subscription::query()
                ->where('status', 'active')
                ->count(),

            'latestPayments' => Invoice::query()
                ->with('company')
                ->where('status', 'paid')
                ->latest('paid_at')
                ->take(5)
                ->get(),

            'upcomingInvoices' => Invoice::query()
                ->with('company')
                ->where('status', 'pending')
                ->whereBetween('due_date', [
                    now(),
                    now()->addDays(30)
                ])
                ->orderBy('due_date')
                ->take(5)
                ->get(),
        ]);
    }
}
