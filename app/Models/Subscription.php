<?php

namespace App\Models;

use App\Enums\BillingInterval;
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
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_at' => 'date',
        'amount' => 'decimal:2'
    ];

    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    // public function service()
    // {
    //     return $this->belongsTo(Service::class);
    // }

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function calculateNextBilling(): void
    {
        $this->next_billing_at = 
            $this->next_billing_at
                ->addMonths($this->interval->months());
    }
}
