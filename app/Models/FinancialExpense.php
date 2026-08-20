<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialExpense extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'category_id',
        'description',
        'due_day',
        'amount',
        'notes',
        'active',
    ];

    protected $casts = [
        'due_day' => 'integer',
        'amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'expense_id');
    }

    public function transactionFor(int $year, int $month): ?FinancialTransaction
    {
        return $this->transactions()->where('year', $year)->where('month', $month)->first();
    }
}