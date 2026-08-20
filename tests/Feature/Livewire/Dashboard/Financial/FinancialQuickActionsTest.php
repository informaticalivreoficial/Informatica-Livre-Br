<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Livewire\Dashboard\Financial\Dashboard;
use App\Livewire\Dashboard\Financial\ExpenseForm;
use App\Livewire\Dashboard\Financial\Expenses;
use App\Livewire\Dashboard\Financial\IncomeForm;
use App\Livewire\Dashboard\Financial\Incomes;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialIncome;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialQuickActionsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->actingAs($this->user);
    }

    protected function makeExpense(float $amount = 150.00): FinancialExpense
    {
        $category = FinancialCategory::factory()->expense()->create(['user_id' => $this->user->id]);

        return FinancialExpense::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'description' => 'Água',
            'due_day' => 10,
            'amount' => $amount,
        ]);
    }

    protected function makeIncome(float $amount = 5000.00): FinancialIncome
    {
        $category = FinancialCategory::factory()->income()->create(['user_id' => $this->user->id]);

        return FinancialIncome::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'description' => 'Salário',
            'receive_day' => 5,
            'amount' => $amount,
        ]);
    }

    public function test_generate_year_uses_fixed_amount(): void
    {
        $this->makeExpense(250.00);
        $this->makeIncome(4000.00);

        FinancialTransaction::generateForYear(2026);

        $this->assertDatabaseHas('financial_transactions', [
            'type' => 'expense',
            'year' => 2026,
            'month' => 1,
            'amount' => 250.00,
        ]);

        $this->assertDatabaseHas('financial_transactions', [
            'type' => 'income',
            'year' => 2026,
            'month' => 1,
            'amount' => 4000.00,
        ]);
    }

    public function test_marks_current_expense_as_paid_from_listing(): void
    {
        $expense = $this->makeExpense();

        Livewire::test(Expenses::class)
            ->call('markCurrentPaid', $expense->id);

        $transaction = FinancialTransaction::where('expense_id', $expense->id)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(FinancialStatus::PAID->value, $transaction->status->value);
        $this->assertEquals(150.00, (float) $transaction->amount);
    }

    public function test_undoes_current_expense_payment_from_listing(): void
    {
        $expense = $this->makeExpense();

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 150.00,
            'status' => FinancialStatus::PAID->value,
            'paid_at' => now(),
        ]);

        Livewire::test(Expenses::class)
            ->call('undoCurrentPayment', $expense->id);

        $this->assertDatabaseHas('financial_transactions', [
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'status' => FinancialStatus::PENDING->value,
            'paid_at' => null,
        ]);
    }

    public function test_marks_current_income_as_received_from_listing(): void
    {
        $income = $this->makeIncome();

        Livewire::test(Incomes::class)
            ->call('markCurrentReceived', $income->id);

        $transaction = FinancialTransaction::where('income_id', $income->id)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(FinancialStatus::RECEIVED->value, $transaction->status->value);
        $this->assertEquals(5000.00, (float) $transaction->amount);
    }

    public function test_dashboard_marks_all_pending_expenses_as_paid(): void
    {
        $expense = $this->makeExpense();

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 150.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Dashboard::class)
            ->call('markAllExpensesPaid');

        $this->assertDatabaseHas('financial_transactions', [
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'status' => FinancialStatus::PAID->value,
        ]);
    }

    public function test_dashboard_marks_all_pending_incomes_as_received(): void
    {
        $income = $this->makeIncome();

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $income->category_id,
            'income_id' => $income->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 5000.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Dashboard::class)
            ->call('markAllIncomesReceived');

        $this->assertDatabaseHas('financial_transactions', [
            'income_id' => $income->id,
            'year' => now()->year,
            'month' => now()->month,
            'status' => FinancialStatus::RECEIVED->value,
        ]);
    }

    public function test_saving_expense_backfills_zero_pending_transactions(): void
    {
        $expense = $this->makeExpense(0);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 0,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(ExpenseForm::class, ['expense' => $expense])
            ->set('amount', '180,00')
            ->call('save');

        $this->assertDatabaseHas('financial_transactions', [
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 180.00,
        ]);
    }

    public function test_saving_income_backfills_zero_pending_transactions(): void
    {
        $income = $this->makeIncome(0);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $income->category_id,
            'income_id' => $income->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 0,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(IncomeForm::class, ['income' => $income])
            ->set('amount', '6.500,00')
            ->call('save');

        $this->assertDatabaseHas('financial_transactions', [
            'income_id' => $income->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 6500.00,
        ]);
    }
}