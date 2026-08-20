<?php

namespace App\Models;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'type',
        'category_id',
        'expense_id',
        'income_id',
        'year',
        'month',
        'amount',
        'status',
        'paid_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'type' => FinancialType::class,
        'status' => FinancialStatus::class,
        'year' => 'integer',
        'month' => 'integer',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(FinancialExpense::class, 'expense_id');
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(FinancialIncome::class, 'income_id');
    }

    public function scopeExpenses(Builder $query): Builder
    {
        return $query->where('type', FinancialType::EXPENSE->value);
    }

    public function scopeIncomes(Builder $query): Builder
    {
        return $query->where('type', FinancialType::INCOME->value);
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', FinancialStatus::PENDING->value);
    }

    public function isExpense(): bool
    {
        return $this->type === FinancialType::EXPENSE;
    }

    public function isIncome(): bool
    {
        return $this->type === FinancialType::INCOME;
    }

    public function isOverdue(): bool
    {
        if (!$this->isExpense() || $this->status !== FinancialStatus::PENDING) {
            return false;
        }

        $dueDate = $this->dueDate();

        return $dueDate && $dueDate->isBefore(today());
    }

    public function dueDate(): ?\Carbon\Carbon
    {
        if (!$this->isExpense() || !$this->expense) {
            return null;
        }

        return \Carbon\Carbon::create($this->year, $this->month, min($this->expense->due_day, 28));
    }

    public function receiveDate(): ?\Carbon\Carbon
    {
        if (!$this->isIncome() || !$this->income) {
            return null;
        }

        return \Carbon\Carbon::create($this->year, $this->month, min($this->income->receive_day, 28));
    }

    public function markAsPaid(): void
    {
        if (!$this->isExpense()) {
            throw new \Exception('Apenas despesas podem ser marcadas como pagas.');
        }

        if ($this->status === FinancialStatus::CANCELLED) {
            throw new \Exception('Não é possível pagar um lançamento cancelado.');
        }

        $this->update([
            'status' => FinancialStatus::PAID,
            'paid_at' => now(),
        ]);
    }

    public function undoPayment(): void
    {
        if (!$this->isExpense()) {
            throw new \Exception('Apenas despesas podem ter o pagamento desfeito.');
        }

        $this->update([
            'status' => FinancialStatus::PENDING,
            'paid_at' => null,
        ]);
    }

    public function markAsReceived(): void
    {
        if (!$this->isIncome()) {
            throw new \Exception('Apenas receitas podem ser marcadas como recebidas.');
        }

        if ($this->status === FinancialStatus::CANCELLED) {
            throw new \Exception('Não é possível receber um lançamento cancelado.');
        }

        $this->update([
            'status' => FinancialStatus::RECEIVED,
            'received_at' => now(),
        ]);
    }

    public function undoReceipt(): void
    {
        if (!$this->isIncome()) {
            throw new \Exception('Apenas receitas podem ter o recebimento desfeito.');
        }

        $this->update([
            'status' => FinancialStatus::PENDING,
            'received_at' => null,
        ]);
    }

    public static function generateForYear(int $year): array
    {
        $userId = auth()->id();

        $expenses = FinancialExpense::query()
            ->where('active', true)
            ->with('category')
            ->get();

        $incomes = FinancialIncome::query()
            ->where('active', true)
            ->with('category')
            ->get();

        $created = 0;
        $skipped = 0;

        foreach ($expenses as $expense) {
            for ($month = 1; $month <= 12; $month++) {
                $exists = self::query()
                    ->where('type', FinancialType::EXPENSE->value)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('expense_id', $expense->id)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                self::create([
                    'user_id' => $userId,
                    'type' => FinancialType::EXPENSE->value,
                    'category_id' => $expense->category_id,
                    'expense_id' => $expense->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => 0,
                    'status' => FinancialStatus::PENDING->value,
                ]);

                $created++;
            }
        }

        foreach ($incomes as $income) {
            for ($month = 1; $month <= 12; $month++) {
                $exists = self::query()
                    ->where('type', FinancialType::INCOME->value)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('income_id', $income->id)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                self::create([
                    'user_id' => $userId,
                    'type' => FinancialType::INCOME->value,
                    'category_id' => $income->category_id,
                    'income_id' => $income->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => 0,
                    'status' => FinancialStatus::PENDING->value,
                ]);

                $created++;
            }
        }

        return ['created' => $created, 'skipped' => $skipped];
    }
}