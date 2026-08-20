<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Livewire\Dashboard\Financial\Transactions;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialIncome;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialTransactionTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->actingAs($this->user);
    }

    protected function makeExpense(): FinancialExpense
    {
        $category = FinancialCategory::factory()->expense()->create(['user_id' => $this->user->id]);

        return FinancialExpense::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'description' => 'Água',
            'due_day' => 10,
        ]);
    }

    protected function makeIncome(): FinancialIncome
    {
        $category = FinancialCategory::factory()->income()->create(['user_id' => $this->user->id]);

        return FinancialIncome::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'description' => 'Salário',
            'receive_day' => 5,
        ]);
    }

    public function test_generates_year_creates_transactions(): void
    {
        $this->makeExpense();
        $this->makeIncome();

        $result = FinancialTransaction::generateForYear(2026);

        $this->assertEquals(24, $result['created']);
        $this->assertEquals(0, $result['skipped']);
        $this->assertDatabaseCount('financial_transactions', 24);
    }

    public function test_generate_year_is_idempotent(): void
    {
        $this->makeExpense();

        FinancialTransaction::generateForYear(2026);
        $result = FinancialTransaction::generateForYear(2026);

        $this->assertEquals(0, $result['created']);
        $this->assertEquals(12, $result['skipped']);
        $this->assertDatabaseCount('financial_transactions', 12);
    }

    public function test_generate_year_ignores_inactive_sources(): void
    {
        $expense = $this->makeExpense();
        $expense->update(['active' => false]);

        FinancialTransaction::generateForYear(2026);

        $this->assertDatabaseCount('financial_transactions', 0);
    }

    public function test_monthly_calculation(): void
    {
        $expense = $this->makeExpense();
        $income = $this->makeIncome();

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 120.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $income->category_id,
            'income_id' => $income->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 5000.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        $expenseTotal = FinancialTransaction::expenses()->forMonth(2026, 1)->sum('amount');
        $incomeTotal = FinancialTransaction::incomes()->forMonth(2026, 1)->sum('amount');

        $this->assertEquals(120.00, (float) $expenseTotal);
        $this->assertEquals(5000.00, (float) $incomeTotal);
    }

    public function test_annual_calculation(): void
    {
        $expense = $this->makeExpense();

        for ($month = 1; $month <= 12; $month++) {
            FinancialTransaction::create([
                'user_id' => $this->user->id,
                'type' => 'expense',
                'category_id' => $expense->category_id,
                'expense_id' => $expense->id,
                'year' => 2026,
                'month' => $month,
                'amount' => 100.00,
                'status' => FinancialStatus::PENDING->value,
            ]);
        }

        $annualTotal = FinancialTransaction::expenses()->forYear(2026)->sum('amount');

        $this->assertEquals(1200.00, (float) $annualTotal);
    }

    public function test_pays_expense(): void
    {
        $expense = $this->makeExpense();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 150.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Transactions::class)
            ->call('markPaid', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::PAID->value,
        ]);
        $this->assertNotNull($transaction->fresh()->paid_at);
    }

    public function test_undoes_payment(): void
    {
        $expense = $this->makeExpense();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 150.00,
            'status' => FinancialStatus::PAID->value,
            'paid_at' => now(),
        ]);

        Livewire::test(Transactions::class)
            ->call('undoPayment', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::PENDING->value,
            'paid_at' => null,
        ]);
    }

    public function test_receives_income(): void
    {
        $income = $this->makeIncome();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $income->category_id,
            'income_id' => $income->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 5000.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Transactions::class)
            ->call('markReceived', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::RECEIVED->value,
        ]);
        $this->assertNotNull($transaction->fresh()->received_at);
    }

    public function test_cannot_pay_income(): void
    {
        $income = $this->makeIncome();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $income->category_id,
            'income_id' => $income->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 5000.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Transactions::class)
            ->call('markPaid', $transaction->id)
            ->assertDispatched('swal:error');

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::PENDING->value,
        ]);
    }

    public function test_updates_amount_via_livewire(): void
    {
        $expense = $this->makeExpense();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 0,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Transactions::class)
            ->call('startEdit', $transaction->id)
            ->set('editingAmount', '135,50')
            ->call('saveAmount', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'amount' => 135.50,
        ]);
    }
}