<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Subscription;
use Livewire\Component;

class SubscriptionShow extends Component
{
    public $subscription;

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription->load([
            'company',
            'service',
            'invoices'
        ]);
    }
    public function render()
    {
        return view('livewire.dashboard.service.subscription-show')->with('title', 'Detalhes do Pedido');
    }
}
