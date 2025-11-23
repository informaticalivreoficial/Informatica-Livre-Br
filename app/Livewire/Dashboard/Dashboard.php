<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $title = 'Painel de Controle';
        return view('livewire.dashboard.dashboard', [
            'title' => $title,
        ]);
    }
}
