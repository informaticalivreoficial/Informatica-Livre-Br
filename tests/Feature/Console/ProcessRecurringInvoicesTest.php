<?php

namespace Tests\Feature\Console;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceCategorie;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProcessRecurringInvoicesTest extends TestCase
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
    }

    protected function makeSubscription(array $attributes = []): Subscription
    {
        $company = Company::factory()->create();
        $category = ServiceCategorie::create(['name' => 'Teste', 'slug' => 'teste-' . uniqid()]);
        $service = Service::create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'name' => 'Serviço de teste',
            'price' => 199.90,
            'billing_type' => 'recurring',
            'interval' => 'monthly',
            'status' => true,
        ]);

        return Subscription::create(array_merge([
            'service_id' => $service->id,
            'company_id' => $company->id,
            'interval' => 'monthly',
            'amount' => 199.90,
            'start_date' => now()->toDateString(),
            'next_billing_at' => now()->toDateString(),
            'status' => 'active',
        ], $attributes));
    }

    public function test_running_command_twice_same_day_creates_only_one_invoice(): void
    {
        $subscription = $this->makeSubscription();

        $this->artisan('invoices:process-recurring')->assertSuccessful();
        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $invoices = Invoice::where('subscription_id', $subscription->id)->get();

        $this->assertCount(1, $invoices, 'Deveria criar apenas 1 invoice, criou ' . $invoices->count());
        $this->assertEquals(now()->toDateString(), $invoices->first()->due_date->toDateString());
    }

    public function test_creates_invoice_for_due_subscription(): void
    {
        $subscription = $this->makeSubscription();

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $this->assertDatabaseHas('invoices', [
            'subscription_id' => $subscription->id,
            'status' => 'pending',
        ]);
    }

    public function test_advances_cycle_and_creates_next_invoice_on_next_due_date(): void
    {
        $subscription = $this->makeSubscription();

        $this->artisan('invoices:process-recurring')->assertSuccessful();
        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $subscription->refresh();
        $this->assertEquals(now()->addMonth()->toDateString(), $subscription->next_billing_at->toDateString());

        $this->travelTo(now()->addMonth());

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $invoices = Invoice::where('subscription_id', $subscription->id)->get();

        $this->assertCount(2, $invoices);
        $this->assertEquals(now()->toDateString(), $invoices->last()->due_date->toDateString());
    }

    public function test_does_not_create_invoice_when_next_billing_is_in_future(): void
    {
        $subscription = $this->makeSubscription(['next_billing_at' => now()->addMonth()->toDateString()]);

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $this->assertDatabaseMissing('invoices', [
            'subscription_id' => $subscription->id,
        ]);
    }

    public function test_does_not_process_paused_subscription(): void
    {
        $subscription = $this->makeSubscription(['status' => 'paused']);

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $this->assertDatabaseMissing('invoices', [
            'subscription_id' => $subscription->id,
        ]);
    }

    public function test_does_not_process_canceled_subscription(): void
    {
        $subscription = $this->makeSubscription(['status' => 'canceled']);

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $this->assertDatabaseMissing('invoices', [
            'subscription_id' => $subscription->id,
        ]);
    }

    public function test_does_not_rebill_when_invoice_already_paid(): void
    {
        $subscription = $this->makeSubscription();

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        Invoice::where('subscription_id', $subscription->id)->update(['status' => 'paid']);

        $this->artisan('invoices:process-recurring')->assertSuccessful();

        $this->assertCount(1, Invoice::where('subscription_id', $subscription->id)->get());
    }
}