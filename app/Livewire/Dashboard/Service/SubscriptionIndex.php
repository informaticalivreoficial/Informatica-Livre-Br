<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // 🔍 filtros
    public $searchCompany = '';
    public $searchService = '';
    public $status = '';

    protected $queryString = [
        'searchCompany' => ['except' => ''],
        'searchService' => ['except' => ''],
        'status'        => ['except' => ''],
    ];

    public function render()
    {
        $subscriptions = Subscription::query()
            ->with(['company', 'service'])
            ->when($this->searchCompany, fn ($q) =>
                $q->whereHas('company', fn ($q) =>
                    $q->where('alias_name', 'like', "%{$this->searchCompany}%")
                )
            )
            ->when($this->searchService, fn ($q) =>
                $q->whereHas('service', fn ($q) =>
                    $q->where('name', 'like', "%{$this->searchService}%")
                )
            )
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.service.subscription-index', compact('subscriptions'))->with('title', 'Pedidos');
    }
}
