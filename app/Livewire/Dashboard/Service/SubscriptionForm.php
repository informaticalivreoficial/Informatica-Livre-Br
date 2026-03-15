<?php

namespace App\Livewire\Dashboard\Service;

use App\Enums\BillingInterval;
use App\Models\Company;
use App\Models\Service;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class SubscriptionForm extends Component
{
    public ?Subscription $subscription = null;

    public $company_id;
    public $service_id;
    public $interval;
    public $amount = 0;
    public $start_date;
    public $status = 'active';

    public $companies = [];
    public $services = [];

    public $next_billing_at = null;

    public function mount(?Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        $this->companies = Company::orderBy('alias_name')->get();
        $this->services  = Service::active()->orderBy('name')->get();

        if ($subscription) {
            $this->company_id = $subscription->company_id;
            $this->service_id = $subscription->service_id;
            $this->interval   = $subscription->interval?->value; // ✅
            $this->amount     = $subscription->amount;
            $this->start_date = $subscription->start_date?->toDateString();
            $this->status     = $subscription->status->value; 
            
            // 👈 Próxima cobrança já salva no banco
        $this->next_billing_at = $subscription->next_billing_at?->format('d/m/Y');

            $this->dispatch('reinitPlugins', amount: $this->amount ?? 0);
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
        
        $this->amount = $service->price;
        $this->interval = $service->interval?->value;

        // 👈 Calcula previsão se já tiver start_date
        if ($this->start_date && $this->interval) {
            $intervalEnum = BillingInterval::from($this->interval);
            $this->next_billing_at = Carbon::parse($this->start_date)
                ->addMonths($intervalEnum->months())
                ->format('d/m/Y');
        }

        $this->dispatch('reinitPlugins', amount: $this->amount);
    }

    public function updatedStartDate()
    {
        if ($this->start_date && $this->interval) {
            $intervalEnum = BillingInterval::from($this->interval);
            $this->next_billing_at = Carbon::parse($this->start_date)
                ->addMonths($intervalEnum->months())
                ->format('d/m/Y');
        }
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

        // 🧾 Gera invoices apenas na criação
        if (!$this->subscription) {
            $startDate = Carbon::parse($this->start_date);

            // 1ª invoice — vence 10 dias após o início
            $subscription->generateInvoice(due_date: $startDate->copy()->addDays(10));

            // 2ª invoice — próximo ciclo + 10 dias
            if ($nextBillingAt) {
                $subscription->generateInvoice(due_date: $nextBillingAt->copy());
            }
        }

        $this->dispatch('swal:success', [
            'title' => 'Sucesso!',
            'text'  => $subscription->wasRecentlyCreated
                ? 'Pedido cadastrado com sucesso!'
                : 'Pedido atualizado com sucesso!',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);        
    }

    public function render()
    {
        return view('livewire.dashboard.service.subscription-form');
    }
}
