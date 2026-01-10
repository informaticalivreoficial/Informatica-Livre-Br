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
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            // mensal, trimestral, anual…
            $table->enum('interval', [
                'monthly',
                'quarterly',
                'semiannual',
                'annual',
                'biennial',
            ]);

            $table->decimal('amount', 10, 2);

            $table->timestamps();

            $table->unique(['service_id', 'interval']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
