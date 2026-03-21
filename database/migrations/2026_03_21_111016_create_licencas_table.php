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
        Schema::create('licencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('pedido_item_id')->constrained('pedido_items');
            $table->foreignId('produto_id')->constrained('produtos');
 
            // Cliente
            $table->string('nome');
            $table->string('email');
 
            // Acesso
            $table->string('chave')->unique();              // chave de licença
            $table->string('url_sistema')->nullable();      // URL onde foi instalado
            $table->string('url_painel')->nullable();       // URL do painel admin
            $table->text('credenciais')->nullable();        // dados de acesso criptografados
 
            // Controle
            $table->enum('status', ['aguardando', 'ativa', 'suspensa', 'cancelada'])
                  ->default('aguardando');
            $table->timestamp('ativada_em')->nullable();
            $table->text('notas')->nullable();              // notas internas do admin
 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencas');
    }
};
