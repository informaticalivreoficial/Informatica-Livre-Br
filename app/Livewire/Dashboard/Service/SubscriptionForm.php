<?php

namespace App\Livewire\Dashboard\Service;

use App\Enums\BillingInterval;
use App\Enums\BillingType;
use App\Models\Company;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
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

    public $items = [];

    public bool $isOneTime = false;

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
            $this->interval   = $subscription->interval?->value;
            $this->amount     = $subscription->amount;
            $this->start_date = $subscription->start_date?->toDateString();
            $this->status     = $subscription->status->value;

            $this->isOneTime = $subscription->service->billing_type === BillingType::ONE_TIME;

            $this->items = $subscription->items()
                ->get()
                ->map(fn ($item) => [
                    'description' => $item->description,
                    'amount'      => $item->amount,
                ])
                ->toArray();

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

        $this->isOneTime = $service->billing_type === BillingType::ONE_TIME;

        if ($service->billing_type === BillingType::ONE_TIME) {
            $this->interval = null;
        }

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

    public function addItem(): void
    {
        $this->items[] = [
            'description' => '',
            'amount'      => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    protected function rules()
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'service_id' => ['required', 'exists:services,id'],
            'amount'     => ['required', 'numeric', 'min:0'],
            'interval'   => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'status'     => ['required', 'in:active,paused,canceled'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.amount'      => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save()
    {
        $this->amount = $this->parsePrice($this->amount);

        foreach ($this->items as &$item) {
            $item['amount'] = $this->parsePrice($item['amount']);
        }
        unset($item);

        $this->validate();

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
            $intervalEnum = BillingInterval::from($this->interval);

            $nextBillingAt = Carbon::parse($this->start_date)
                ->addMonths($intervalEnum->months());
        }

        $subscription = Subscription::updateOrCreate(
            ['id' => $this->subscription?->id],
            [
                'company_id'      => $this->company_id,
                'service_id'      => $this->service_id,
                'interval'        => $this->interval,
                'amount'          => $this->amount,
                'start_date'      => $this->start_date,
                'next_billing_at' => $nextBillingAt,
                'status'          => $this->status,
            ]
        );

        $subscription->items()->delete();

        foreach ($this->items as $item) {
            if (empty($item['description'])) {
                continue;
            }

            SubscriptionItem::create([
                'subscription_id' => $subscription->id,
                'description'     => $item['description'],
                'amount'          => $item['amount'],
            ]);
        }

        if (!$this->subscription) {
            $startDate = Carbon::parse($this->start_date);

            $total = (float) $this->amount + collect($this->items)->sum('amount');

            $subscription->generateInvoice(
                due_date: $startDate->copy()->addDays(10),
                amount: $total
            );

            if ($nextBillingAt) {
                $subscription->generateInvoice(
                    due_date: $nextBillingAt->copy(),
                    amount: $total
                );
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

    private function parsePrice($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace('.', '', (string) $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        return view('livewire.dashboard.service.subscription-form');
    }
}