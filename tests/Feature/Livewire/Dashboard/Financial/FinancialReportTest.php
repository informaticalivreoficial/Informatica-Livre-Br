<?php

namespace Tests\Feature\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Livewire\Dashboard\Financial\Report;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use App\Models\FinancialIncome;
use App\Models\FinancialTransaction;
use App\Models\User;
use App\Services\FinancialReportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => 'password']);
        $this->actingAs($this->user);
    }

    protected function seedTransactions(): void
    {
        $expenseCategory = FinancialCategory::factory()->expense()->create(['user_id' => $this->user->id]);
        $incomeCategory = FinancialCategory::factory()->income()->create(['user_id' => $this->user->id]);

        $expense = FinancialExpense::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $expenseCategory->id,
            'description' => 'Energia',
            'amount' => 150.00,
        ]);

        $income = FinancialIncome::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $incomeCategory->id,
            'description' => 'Salário',
            'amount' => 5000.00,
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expenseCategory->id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 2,
            'amount' => 150.00,
            'status' => FinancialStatus::PAID->value,
            'paid_at' => now(),
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'income',
            'category_id' => $incomeCategory->id,
            'income_id' => $income->id,
            'year' => 2026,
            'month' => 2,
            'amount' => 5000.00,
            'status' => FinancialStatus::RECEIVED->value,
            'received_at' => now(),
        ]);

        FinancialTransaction::create([
            'user_id' => $this->user->id,
            'type' => 'expense',
            'category_id' => $expenseCategory->id,
            'expense_id' => $expense->id,
            'year' => 2026,
            'month' => 5,
            'amount' => 150.00,
            'status' => FinancialStatus::PENDING->value,
        ]);
    }

    public function test_builds_monthly_report(): void
    {
        $this->seedTransactions();

        $data = app(FinancialReportService::class)->build(2026, 'mensal', 2);

        $this->assertEquals('Fevereiro de 2026', $data['label']);
        $this->assertEquals(5000.00, $data['incomeTotal']);
        $this->assertEquals(150.00, $data['expenseTotal']);
        $this->assertEquals(4850.00, $data['balance']);
        $this->assertCount(2, $data['transactions']);
    }

    public function test_builds_quarterly_report(): void
    {
        $this->seedTransactions();

        $data = app(FinancialReportService::class)->build(2026, 'trimestral', null, 1);

        $this->assertEquals('1º Trimestre de 2026', $data['label']);
        $this->assertEquals(5000.00, $data['incomeTotal']);
        $this->assertEquals(150.00, $data['expenseTotal']);
    }

    public function test_builds_semiannual_report(): void
    {
        $this->seedTransactions();

        $data = app(FinancialReportService::class)->build(2026, 'semestral', null, null, 1);

        $this->assertEquals('1º Semestre de 2026', $data['label']);
        $this->assertEquals(5000.00, $data['incomeTotal']);
        $this->assertEquals(300.00, $data['expenseTotal']);
        $this->assertCount(3, $data['transactions']);
    }

    public function test_builds_annual_report(): void
    {
        $this->seedTransactions();

        $data = app(FinancialReportService::class)->build(2026, 'anual');

        $this->assertEquals('Ano de 2026', $data['label']);
        $this->assertEquals(5000.00, $data['incomeTotal']);
        $this->assertEquals(300.00, $data['expenseTotal']);
    }

    public function test_report_component_renders(): void
    {
        $this->seedTransactions();

        Livewire::test(Report::class)
            ->assertOk()
            ->assertSee('Relatório Financeiro')
            ->assertSee('Exportar PDF');
    }

    public function test_export_route_returns_pdf(): void
    {
        $this->seedTransactions();

        $response = $this->get(route('financial.report.export', [
            'period' => 'mensal',
            'year' => 2026,
            'month' => 2,
        ]));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_export_route_rejects_invalid_period(): void
    {
        $this->seedTransactions();

        $this->get(route('financial.report.export', [
            'period' => 'semanal',
            'year' => 2026,
        ]))->assertSessionHasErrors('period');
    }
}