<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Livewire\Dashboard\Financial\ExpenseForm;
use App\Livewire\Dashboard\Financial\Transactions;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialIsolationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_cannot_see_other_users_expenses(): void
    {
        $userA = User::factory()->create(['password' => 'password']);
        $userB = User::factory()->create(['password' => 'password']);

        $category = FinancialCategory::factory()->expense()->create(['user_id' => $userA->id]);
        FinancialExpense::factory()->create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'description' => 'Despesa secreta',
        ]);

        $this->actingAs($userB);

        $this->assertDatabaseCount('financial_expenses', 1);
        $this->assertEquals(0, FinancialExpense::count());
    }

    public function test_user_cannot_edit_other_users_expense(): void
    {
        $userA = User::factory()->create(['password' => 'password']);
        $userB = User::factory()->create(['password' => 'password']);

        $category = FinancialCategory::factory()->expense()->create(['user_id' => $userA->id]);
        $expense = FinancialExpense::factory()->create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'description' => 'Despesa do usuário A',
        ]);

        $this->actingAs($userB);

        Livewire::test(ExpenseForm::class, ['expense' => $expense])
            ->set('description', 'Hackeado')
            ->call('save');

        $this->assertDatabaseHas('financial_expenses', [
            'id' => $expense->id,
            'description' => 'Despesa do usuário A',
        ]);
    }

    public function test_user_cannot_see_other_users_transactions(): void
    {
        $userA = User::factory()->create(['password' => 'password']);
        $userB = User::factory()->create(['password' => 'password']);

        $category = FinancialCategory::factory()->expense()->create(['user_id' => $userA->id]);
        $expense = FinancialExpense::factory()->create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
        ]);

        FinancialTransaction::create([
            'user_id' => $userA->id,
            'type' => 'expense',
            'category_id' => $category->id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 999.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        $this->actingAs($userB);

        $this->assertEquals(0, FinancialTransaction::count());
    }

    public function test_user_cannot_pay_other_users_transaction(): void
    {
        $userA = User::factory()->create(['password' => 'password']);
        $userB = User::factory()->create(['password' => 'password']);

        $category = FinancialCategory::factory()->expense()->create(['user_id' => $userA->id]);
        $expense = FinancialExpense::factory()->create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
        ]);

        $transaction = FinancialTransaction::create([
            'user_id' => $userA->id,
            'type' => 'expense',
            'category_id' => $category->id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 1,
            'amount' => 999.00,
            'status' => FinancialStatus::PENDING->value,
        ]);

        $this->actingAs($userB);

        Livewire::test(Transactions::class)
            ->call('markPaid', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'status' => FinancialStatus::PENDING->value,
        ]);
    }
}