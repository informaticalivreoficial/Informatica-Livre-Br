<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Livewire\Dashboard\Financial\AnnualView;
use App\Livewire\Dashboard\Financial\Categories;
use App\Livewire\Dashboard\Financial\Dashboard;
use App\Livewire\Dashboard\Financial\Expenses;
use App\Livewire\Dashboard\Financial\Incomes;
use App\Livewire\Dashboard\Financial\Transactions;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialIncome;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialRenderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->actingAs($this->user);

        $expenseCategory = FinancialCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Moradia',
            'type' => 'expense',
        ]);
        $incomeCategory = FinancialCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Salário',
            'type' => 'income',
        ]);

        $expense = FinancialExpense::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $expenseCategory->id,
            'description' => 'Água',
            'due_day' => 10,
        ]);

        $income = FinancialIncome::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $incomeCategory->id,
            'description' => 'Salário',
            'receive_day' => 5,
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expenseCategory->id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 125.00,
            'status' => 'pending',
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $incomeCategory->id,
            'income_id' => $income->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 5000.00,
            'status' => 'received',
            'received_at' => now(),
        ]);
    }

    public function test_dashboard_renders(): void
    {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('Dashboard Financeiro')
            ->assertSee('Saldo do mês');
    }

    public function test_categories_renders(): void
    {
        Livewire::test(Categories::class)
            ->assertOk()
            ->assertSee('Categorias Financeiras')
            ->assertSee('Moradia')
            ->assertSee('Salário');
    }

    public function test_expenses_renders(): void
    {
        Livewire::test(Expenses::class)
            ->assertOk()
            ->assertSee('Despesas')
            ->assertSee('Água');
    }

    public function test_incomes_renders(): void
    {
        Livewire::test(Incomes::class)
            ->assertOk()
            ->assertSee('Receitas')
            ->assertSee('Salário');
    }

    public function test_transactions_renders(): void
    {
        Livewire::test(Transactions::class)
            ->assertOk()
            ->assertSee('Lançamentos Financeiros')
            ->assertSee('Água')
            ->assertSee('Salário');
    }

    public function test_annual_view_renders(): void
    {
        Livewire::test(AnnualView::class)
            ->assertOk()
            ->assertSee('Visão Anual Financeira')
            ->assertSee('DESPESAS')
            ->assertSee('RECEITAS');
    }
}