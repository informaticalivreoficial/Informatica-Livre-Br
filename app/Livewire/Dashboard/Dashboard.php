<?php

namespace App\Livewire\Dashboard;

use App\Models\Company;
use App\Models\Invoice;
use Livewire\Component;

class Dashboard extends Component
{
    public $topcompanies = [];

    public function render()
    {
        $companyCount = Company::count();
        $companyYearCount = Company::whereYear('created_at', now()->year)->count();   
        
        $invoicesCount = Invoice::count();
        $invoicesYearCount = Invoice::whereYear('created_at', now()->year)->count();

        $title = 'Painel de Controle';
        return view('livewire.dashboard.dashboard', [
            'title' => $title,
            'companyCount' => $companyCount,
            'companyYearCount' => $companyYearCount,
            'invoicesCount' => $invoicesCount,
            'invoicesYearCount' => $invoicesYearCount
        ]);
    }
}
