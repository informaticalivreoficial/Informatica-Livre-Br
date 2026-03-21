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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();             // ex: PED-2026-00001
 
            // Cliente
            $table->string('nome');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cpf_cnpj')->nullable();
 
            // Valores
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
 
            // Pagamento
            $table->enum('metodo_pagamento', ['pix', 'cartao_credito', 'boleto'])->nullable();
            $table->enum('status_pagamento', ['pendente', 'pago', 'cancelado', 'estornado'])
                  ->default('pendente');
            $table->string('gateway_id')->nullable();       // ID da transação no gateway
            $table->json('gateway_response')->nullable();   // resposta completa do gateway
            $table->timestamp('pago_em')->nullable();
 
            // Status geral
            $table->enum('status', ['pendente', 'confirmado', 'cancelado', 'reembolsado'])
                  ->default('pendente');
 
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
