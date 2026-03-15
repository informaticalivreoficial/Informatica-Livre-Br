<?php

namespace App\Models;

use App\Enums\BillingInterval;
use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'company_id',
        'interval',
        'amount',
        'start_date',
        'end_date',
        'next_billing_at',
        'status'
    ];

    protected $casts = [
        'interval' => BillingInterval::class,
        'status' => SubscriptionStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_at' => 'date',
        'amount' => 'decimal:2'
    ];

    protected static function booted()
    {
        static::saving(function ($subscription) {
            if ($subscription->service->billing_type === 'one_time') {
                $subscription->interval = null;
                $subscription->next_billing_at = null;
            }

            if (
                $subscription->service->billing_type === 'recurring'
                && !$subscription->interval
            ) {
                throw new \Exception('Serviço recorrente exige intervalo.');
            }
        });

        static::deleting(function ($subscription) {
            if ($subscription->invoices()->exists()) {
                throw new \Exception('Esta subscription possui faturas e não pode ser excluída.');
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', SubscriptionStatus::ACTIVE);
    }

    public function calculateNextBilling(): void
    {
        $this->next_billing_at = 
            $this->next_billing_at
                ->addMonths($this->interval->months());
    }

    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->status === SubscriptionStatus::PAUSED;
    }

    public function isCanceled(): bool
    {
        return $this->status === SubscriptionStatus::CANCELED;
    }

    public function pause(): void
    {
        $this->update([
            'status' => SubscriptionStatus::PAUSED,
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => SubscriptionStatus::CANCELED,
            'end_date' => now(),
        ]);
    }

    public function generateInvoice(?Carbon $due_date = null): Invoice
    {
        return $this->invoices()->create([
            'company_id' => $this->company_id,
            'amount'     => $this->amount,
            'due_date'   => $due_date ?? $this->next_billing_at ?? $this->start_date,
            'status'     => 'pending',
        ]);
    }

    public function billNext(): void
    {
        if ($this->status !== 'active') {
            return;
        }

        $this->generateInvoice();

        $this->calculateNextBilling();

        $this->save();
    }
}
