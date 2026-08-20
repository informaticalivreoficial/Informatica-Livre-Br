<?php

namespace App\Models;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'company_id',
        'amount',
        'due_date',
        'status',
        'gateway',
        'gateway_reference',
        'payment_url',
        'pix_qrcode',
        'boleto_barcode',
        'paid_at',
        'gateway_fee',
        'gateway_net_amount',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'gateway_fee' => 'decimal:2',
        'gateway_net_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function financialTransaction(): HasOne
    {
        return $this->hasOne(FinancialTransaction::class, 'invoice_id');
    }

    public function registerFinancialIncome(): ?FinancialTransaction
    {
        if ($this->status !== 'paid') {
            return null;
        }

        $existing = FinancialTransaction::withoutGlobalScope('user')
            ->where('invoice_id', $this->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $user = User::role('super-admin')->first();

        if (!$user) {
            return null;
        }

        $category = FinancialCategory::withoutGlobalScope('user')
            ->where('user_id', $user->id)
            ->where('name', 'Faturas')
            ->where('type', FinancialType::INCOME->value)
            ->first();

        if (!$category) {
            $category = FinancialCategory::create([
                'user_id' => $user->id,
                'name' => 'Faturas',
                'type' => FinancialType::INCOME->value,
                'color' => '#00ff41',
                'active' => true,
            ]);
        }

        $paidAt = $this->paid_at ?? now();

        return FinancialTransaction::withoutGlobalScope('user')->create([
            'user_id' => $user->id,
            'type' => FinancialType::INCOME->value,
            'category_id' => $category->id,
            'invoice_id' => $this->id,
            'year' => $paidAt->year,
            'month' => $paidAt->month,
            'amount' => $this->gateway_net_amount ?? $this->amount,
            'status' => FinancialStatus::RECEIVED->value,
            'received_at' => $paidAt,
            'notes' => "Fatura #{$this->id}",
        ]);
    }
}
