<?php

namespace App\Livewire\Dashboard\Service;

use App\Models\Company;
use App\Models\CompanyService;
use App\Models\Service;
use Livewire\Component;

class CompaniServiceForm extends Component
{
    public ?CompanyService $contract = null;

    public $company_id;
    public $service_id;
    public $interval = 'monthly';
    public $amount;
    public $starts_at;
    public $ends_at;
    public bool $active = true;

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'service_id' => 'required|exists:services,id',
            'interval'   => 'required',
            'amount'     => 'required|numeric|min:0',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            'active'     => 'boolean',
        ];
    }

    public function mount()
    {
        $this->starts_at = now()->toDateString();
    }

    public function save()
    {
        $this->validate();

        CompanyService::updateOrCreate(
            ['id' => $this->contract?->id],
            [
                'company_id' => $this->company_id,
                'service_id' => $this->service_id,
                'interval'   => $this->interval,
                'amount'     => $this->amount,
                'starts_at'  => $this->starts_at,
                'ends_at'    => $this->ends_at,
                'active'     => $this->active,
            ]
        );

        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => 'Serviço vinculado à empresa.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
    public function render()
    {
        return view('livewire.dashboard.service.compani-service-form', [
            'companies' => Company::where('status', true)->orderBy('social_name')->get(),
            'services'  => Service::where('status', true)->orderBy('name')->get(),
        ]);
    }
}
