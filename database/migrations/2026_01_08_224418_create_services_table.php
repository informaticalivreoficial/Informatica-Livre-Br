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
        Schema::create('services', function (Blueprint $table) {
             $table->id();

            // Relacionamentos
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('service_categories')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Dados principais
            $table->string('name');
            $table->text('description')->nullable();

            // Valores
            $table->decimal('price', 10, 2)->nullable();

            // Tipo de cobrança
            $table->enum('billing_type', ['one_time', 'recurring'])
                ->default('one_time');

            // Intervalo de recorrência
            $table->enum('interval', [
                'monthly',
                'quarterly',
                'semiannual',
                'yearly'
            ])->nullable();

            // Controle
            $table->boolean('is_public')->default(false); // Exibir no frontend
            $table->boolean('status')->default(true);    // Ativo / Inativo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
