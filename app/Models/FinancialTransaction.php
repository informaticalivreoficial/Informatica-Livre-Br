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
        'invoice_id',
        'year',
        'month',
        'amount',
        'status',
        'paid_at',
        'received_at',
        'notes',
        'description',
        'payment_method',
        'due_date',
        'attachment_path',
        'due_notified_at',
    ];

    protected $casts = [
        'type' => FinancialType::class,
        'status' => FinancialStatus::class,
        'year' => 'integer',
        'month' => 'integer',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'received_at' => 'datetime',
        'due_date' => 'date',
        'due_notified_at' => 'datetime',
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

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
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
        if (!$this->isExpense()) {
            return null;
        }

        if ($this->due_date) {
            return $this->due_date;
        }

        if (!$this->expense) {
            return null;
        }

        return \Carbon\Carbon::create($this->year, $this->month, min($this->expense->due_day, 28));
    }

    public function receiveDate(): ?\Carbon\Carbon
    {
        if (!$this->isIncome()) {
            return null;
        }

        if ($this->due_date) {
            return $this->due_date;
        }

        if (!$this->income) {
            return null;
        }

        return \Carbon\Carbon::create($this->year, $this->month, min($this->income->receive_day, 28));
    }

    public function descriptionText(): string
    {
        return $this->description
            ?? $this->expense?->description
            ?? $this->income?->description
            ?? '—';
    }

    public function isAdHoc(): bool
    {
        return !$this->expense_id && !$this->income_id && !$this->invoice_id;
    }

    public function daysUntilDue(): ?int
    {
        $dueDate = $this->dueDate();

        if (!$dueDate) {
            return null;
        }

        return (int) today()->diffInDays($dueDate, false);
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'pix' => 'Pix',
            'boleto' => 'Boleto',
            'cartao' => 'Cartão',
            'transferencia' => 'Transferência',
            'dinheiro' => 'Dinheiro',
            default => '—',
        };
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

    public function cancelTransaction(): void
    {
        if ($this->status === FinancialStatus::CANCELLED) {
            return;
        }

        $this->update([
            'status' => FinancialStatus::CANCELLED,
            'paid_at' => null,
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
                    'amount' => $expense->amount,
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
                    'amount' => $income->amount,
                    'status' => FinancialStatus::PENDING->value,
                ]);

                $created++;
            }
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    public static function generateForMonth(int $year, int $month): array
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
                'amount' => $expense->amount,
                'status' => FinancialStatus::PENDING->value,
            ]);

            $created++;
        }

        foreach ($incomes as $income) {
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
                'amount' => $income->amount,
                'status' => FinancialStatus::PENDING->value,
            ]);

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }
}