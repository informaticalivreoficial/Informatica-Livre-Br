<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Livewire\Dashboard\Financial\Categories;
use App\Livewire\Dashboard\Financial\ExpenseForm;
use App\Livewire\Dashboard\Financial\IncomeForm;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialIncome;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->actingAs($this->user);
    }

    public function test_creates_expense_via_livewire(): void
    {
        $category = FinancialCategory::factory()->expense()->create(['user_id' => $this->user->id]);

        Livewire::test(ExpenseForm::class)
            ->set('description', 'Energia elétrica')
            ->set('category_id', $category->id)
            ->set('due_day', 15)
            ->call('save');

        $this->assertDatabaseHas('financial_expenses', [
            'user_id' => $this->user->id,
            'description' => 'Energia elétrica',
            'category_id' => $category->id,
            'due_day' => 15,
            'active' => true,
        ]);
    }

    public function test_expense_requires_category(): void
    {
        Livewire::test(ExpenseForm::class)
            ->set('description', 'Água')
            ->set('due_day', 10)
            ->call('save')
            ->assertHasErrors(['category_id' => 'required']);

        $this->assertDatabaseCount('financial_expenses', 0);
    }

    public function test_creates_income_via_livewire(): void
    {
        $category = FinancialCategory::factory()->income()->create(['user_id' => $this->user->id]);

        Livewire::test(IncomeForm::class)
            ->set('description', 'Salário')
            ->set('category_id', $category->id)
            ->set('receive_day', 5)
            ->call('save');

        $this->assertDatabaseHas('financial_incomes', [
            'user_id' => $this->user->id,
            'description' => 'Salário',
            'category_id' => $category->id,
            'receive_day' => 5,
            'active' => true,
        ]);
    }

    public function test_creates_category_via_livewire(): void
    {
        Livewire::test(Categories::class)
            ->call('openCreate')
            ->set('name', 'Moradia')
            ->set('categoryType', 'expense')
            ->call('save');

        $this->assertDatabaseHas('financial_categories', [
            'user_id' => $this->user->id,
            'name' => 'Moradia',
            'type' => 'expense',
            'active' => true,
        ]);
    }

    public function test_edits_expense_via_livewire(): void
    {
        $category = FinancialCategory::factory()->expense()->create(['user_id' => $this->user->id]);
        $expense = FinancialExpense::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'description' => 'Internet',
            'due_day' => 20,
        ]);

        Livewire::test(ExpenseForm::class, ['expense' => $expense])
            ->set('description', 'Internet Fibra')
            ->set('due_day', 22)
            ->call('save');

        $this->assertDatabaseHas('financial_expenses', [
            'id' => $expense->id,
            'description' => 'Internet Fibra',
            'due_day' => 22,
        ]);
    }
}