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
        'description',
        'payer_name',
        'payer_document',
        'payer_email',
        'payer_phone',
        'payer_zipcode',
        'payer_street',
        'payer_number',
        'payer_complement',
        'payer_neighborhood',
        'payer_city',
        'payer_state',
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

    public function isCustomPayer(): bool
    {
        return is_null($this->company_id);
    }

    public function payerLabel(): string
    {
        return $this->company?->alias_name ?? ($this->payer_name ?: 'Pagador não informado');
    }

    public function payerData(): array
    {
        $company = $this->company;

        $document = $company?->document_company ?? $this->payer_document;
        $zipcode = $company?->zipcode ?? $this->payer_zipcode;

        return [
            'name'         => $company?->alias_name ?? $this->payer_name,
            'document'     => $document ? preg_replace('/\D/', '', $document) : null,
            'email'        => $company?->email ?? $this->payer_email,
            'phone'        => $company ? ($company->phone ?: $company->cell_phone) : $this->payer_phone,
            'zipcode'      => $zipcode ? preg_replace('/\D/', '', $zipcode) : null,
            'street'       => $company?->street ?? $this->payer_street,
            'number'       => $company?->number ?? $this->payer_number,
            'complement'   => $company?->complement ?? $this->payer_complement,
            'neighborhood' => $company?->neighborhood ?? $this->payer_neighborhood,
            'city'         => $company?->city ?? $this->payer_city,
            'state'        => $company?->state ?? $this->payer_state,
        ];
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
