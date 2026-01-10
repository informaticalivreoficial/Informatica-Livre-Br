<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subscription_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('company_id')
                    ->constrained('companies')
                    ->cascadeOnDelete();

            $table->decimal('amount', 10, 2);

            $table->date('due_date');

            $table->string('status')->default('pending'); // pending | paid | failed

            $table->string('gateway')->nullable();
            $table->string('gateway_reference')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
