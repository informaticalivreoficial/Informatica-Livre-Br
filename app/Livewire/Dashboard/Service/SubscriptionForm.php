<?php

namespace App\Livewire\Dashboard\Service;

use App\Enums\BillingInterval;
use App\Models\Company;
use App\Models\Service;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SubscriptionForm extends Component
{
    public ?Subscription $subscription = null;

    public $company_id;
    public $service_id;
    public $interval;
    public $amount;
    public $start_date;
    public $status = 'active';

    public $companies = [];
    public $services = [];

    public function mount(?Subscription $subscription = null)
    {
        //$this->authorize('create', Subscription::class);

        $this->subscription = $subscription;

        $this->companies = Company::orderBy('alias_name')->get();
        $this->services  = Service::active()->orderBy('name')->get();

        if ($subscription) {
            $this->company_id = $subscription->company_id;
            $this->service_id = $subscription->service_id;
            $this->interval   = $subscription->interval?->value; // ✅
            $this->amount     = (float) $subscription->amount;
            $this->start_date = $subscription->start_date?->toDateString();
            $this->status     = $subscription->status->value;     // ✅
        } else {
            $this->start_date = now()->toDateString();
        }
    }

    public function updatedServiceId()
    {
        $service = Service::find($this->service_id);

        if (!$service) {
            return;
        }

        $this->amount   = (float) $service->price;
        $this->interval = $service->interval?->value; // ✅ string
    }

    protected function rules()
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'service_id' => ['required', 'exists:services,id'],
            'amount'     => ['required', 'numeric', 'min:0'],
            'interval'   => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'status'     => ['required', 'in:active,paused,canceled'], // ✅
        ];
    }

    public function save()
    {
        $this->validate();

        // ❌ Impede duplicidade
        $exists = Subscription::where('company_id', $this->company_id)
            ->where('service_id', $this->service_id)
            ->active()
            ->exists();

        if ($exists && !$this->subscription) {
            throw ValidationException::withMessages([
                'service_id' => 'Esta empresa já possui esse serviço ativo.'
            ]);
        }

        $service = Service::findOrFail($this->service_id);

        $nextBillingAt = null;

        if ($service->billing_type === 'recurring' && $this->interval) {
            $intervalEnum = BillingInterval::from($this->interval); // ✅

            $nextBillingAt = Carbon::parse($this->start_date)
                ->addMonths($intervalEnum->months());
        }

        $subscription = Subscription::updateOrCreate(
            ['id' => $this->subscription?->id],
            [
                'company_id'      => $this->company_id,
                'service_id'      => $this->service_id,
                'interval'        => $this->interval, // string → cast resolve
                'amount'          => $this->amount,
                'start_date'      => $this->start_date,
                'next_billing_at' => $nextBillingAt,
                'status'          => $this->status,   // string
            ]
        );

        // 🧾 Gera invoice apenas na criação
        if (!$this->subscription) {
            $subscription->generateInvoice();
        }

        return redirect()
            ->route('services.subscriptions.index')
            ->with('success', 'Subscription salva com sucesso.');
    }
    public function render()
    {
        return view('livewire.dashboard.service.subscription-form');
    }
}
