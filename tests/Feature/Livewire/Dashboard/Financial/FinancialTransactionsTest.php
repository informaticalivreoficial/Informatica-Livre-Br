<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Livewire\Dashboard\Financial\Categories;
use App\Livewire\Dashboard\Financial\Dashboard;
use App\Livewire\Dashboard\Financial\Transactions;
use App\Models\FinancialBudget;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Notifications\FinancialDueNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinancialTransactionsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Role::where('name', 'super-admin')->exists()) {
            Role::create(['name' => 'super-admin']);
        }

        $this->user = User::factory()->create(['password' => 'password']);
        $this->user->assignRole('super-admin');
        $this->actingAs($this->user);

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);
    }

    protected function makeCategory(string $type = 'expense'): FinancialCategory
    {
        return FinancialCategory::create([
            'user_id' => $this->user->id,
            'name' => 'Categoria ' . uniqid(),
            'type' => $type,
            'color' => '#00ff41',
            'active' => true,
        ]);
    }

    protected function makeRecurringExpense(): FinancialExpense
    {
        return FinancialExpense::create([
            'user_id' => $this->user->id,
            'category_id' => $this->makeCategory('expense')->id,
            'description' => 'Aluguel',
            'due_day' => 10,
            'amount' => 1200.00,
            'active' => true,
        ]);
    }

    public function test_create_ad_hoc_expense_transaction(): void
    {
        $category = $this->makeCategory('expense');

        Livewire::test(Transactions::class)
            ->call('openCreate', 'expense')
            ->set('formCategoryId', $category->id)
            ->set('formDescription', 'Compra de material')
            ->set('formAmount', '150,50')
            ->set('formYear', now()->year)
            ->set('formMonth', now()->month)
            ->call('saveForm')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financial_transactions', [
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'description' => 'Compra de material',
            'amount' => 150.50,
            'status' => FinancialStatus::PENDING->value,
            'expense_id' => null,
            'income_id' => null,
            'invoice_id' => null,
        ]);
    }

    public function test_create_ad_hoc_income_transaction_as_received_with_effective_date(): void
    {
        $category = $this->makeCategory('income');

        Livewire::test(Transactions::class)
            ->call('openCreate', 'income')
            ->set('formCategoryId', $category->id)
            ->set('formDescription', 'Venda avulsa')
            ->set('formAmount', '500,00')
            ->set('formYear', now()->year)
            ->set('formMonth', now()->month)
            ->set('formEffectiveDate', now()->toDateString())
            ->call('saveForm')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financial_transactions', [
            'user_id' => $this->user->id,
            'type' => FinancialType::INCOME->value,
            'description' => 'Venda avulsa',
            'amount' => 500.00,
            'status' => FinancialStatus::RECEIVED->value,
        ]);
    }

    public function test_edit_transaction_description_category_and_month(): void
    {
        $category = $this->makeCategory('expense');
        $newCategory = $this->makeCategory('expense');

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Original',
        ]);

        Livewire::test(Transactions::class)
            ->call('openEdit', $transaction->id)
            ->set('formDescription', 'Editada')
            ->set('formCategoryId', $newCategory->id)
            ->set('formMonth', 12)
            ->set('formAmount', '200,00')
            ->call('saveForm')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'description' => 'Editada',
            'category_id' => $newCategory->id,
            'month' => 12,
            'amount' => 200.00,
        ]);
    }

    public function test_delete_ad_hoc_transaction(): void
    {
        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $this->makeCategory('expense')->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Avulso',
        ]);

        Livewire::test(Transactions::class)
            ->call('deleteTransaction', $transaction->id);

        $this->assertDatabaseMissing('financial_transactions', ['id' => $transaction->id]);
    }

    public function test_cannot_delete_recurring_transaction(): void
    {
        $expense = $this->makeRecurringExpense();

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $expense->category_id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => $expense->amount,
            'status' => FinancialStatus::PENDING->value,
        ]);

        Livewire::test(Transactions::class)
            ->call('deleteTransaction', $transaction->id);

        $this->assertDatabaseHas('financial_transactions', ['id' => $transaction->id]);
    }

    public function test_status_filter_filters_transactions(): void
    {
        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PAID->value,
            'paid_at' => now(),
            'description' => 'Despesa paga unica',
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 200.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Despesa pendente unica',
        ]);

        Livewire::test(Transactions::class)
            ->set('statusFilter', FinancialStatus::PAID->value)
            ->assertSee('Despesa paga unica')
            ->assertDontSee('Despesa pendente unica');
    }

    public function test_generate_month_creates_transactions(): void
    {
        $expense = $this->makeRecurringExpense();

        Livewire::test(Transactions::class)
            ->call('generateMonth');

        $this->assertDatabaseHas('financial_transactions', [
            'user_id' => $this->user->id,
            'expense_id' => $expense->id,
            'year' => now()->year,
            'month' => now()->month,
        ]);
    }

    public function test_export_csv_returns_streamed_download(): void
    {
        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'CSV Teste',
        ]);

        $response = Livewire::test(Transactions::class)
            ->call('exportCsv');

        $response->assertFileDownloaded("lancamentos-" . now()->year . "-" . now()->month . ".csv");
    }

    public function test_save_budget_for_category(): void
    {
        $category = $this->makeCategory('expense');

        Livewire::test(Categories::class)
            ->call('openBudget', $category->id)
            ->set('budgetAmount', '800,00')
            ->call('saveBudget')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financial_budgets', [
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 800.00,
        ]);
    }

    public function test_update_budget_does_not_duplicate(): void
    {
        $category = $this->makeCategory('expense');

        FinancialBudget::create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 500.00,
        ]);

        Livewire::test(Categories::class)
            ->call('openBudget', $category->id)
            ->set('budgetAmount', '900,00')
            ->call('saveBudget')
            ->assertHasNoErrors();

        $this->assertEquals(1, FinancialBudget::where('category_id', $category->id)->count());
        $this->assertDatabaseHas('financial_budgets', [
            'category_id' => $category->id,
            'amount' => 900.00,
        ]);
    }

    public function test_due_date_notification_command_notifies_super_admins(): void
    {
        Notification::fake();

        $category = $this->makeCategory('expense');

        $transaction = FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Conta de luz',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $this->artisan('financial:check-due')->assertSuccessful();

        Notification::assertSentTo($this->user, FinancialDueNotification::class);

        $this->assertNotNull($transaction->fresh()->due_notified_at);
    }

    public function test_due_date_notification_command_does_not_notify_twice_same_day(): void
    {
        Notification::fake();

        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Conta de água',
            'due_date' => now()->addDays(1)->toDateString(),
        ]);

        $this->artisan('financial:check-due')->assertSuccessful();
        $this->artisan('financial:check-due')->assertSuccessful();

        Notification::assertSentTimes(FinancialDueNotification::class, 1);
    }

    public function test_due_date_notification_command_ignores_far_future_due_dates(): void
    {
        Notification::fake();

        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Conta futura',
            'due_date' => now()->addDays(30)->toDateString(),
        ]);

        $this->artisan('financial:check-due')->assertSuccessful();

        Notification::assertNothingSent();
    }

    public function test_due_date_notification_command_notifies_overdue(): void
    {
        Notification::fake();

        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Conta vencida',
            'due_date' => now()->subDays(2)->toDateString(),
        ]);

        $this->artisan('financial:check-due')->assertSuccessful();

        Notification::assertSentTo($this->user, FinancialDueNotification::class);
    }

    public function test_dashboard_renders_with_charts_and_projection(): void
    {
        $category = $this->makeCategory('expense');

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => FinancialType::EXPENSE->value,
            'category_id' => $category->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 100.00,
            'status' => FinancialStatus::PENDING->value,
            'description' => 'Despesa projetada',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        Livewire::test(Dashboard::class)
            ->assertSee('Fluxo de caixa projetado')
            ->assertSee('Receitas x Despesas')
            ->assertSee('Despesas por categoria');
    }
}