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
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
 
            // Token único para acesso via link
            $table->string('token')->unique();
 
            // Dados pessoais
            $table->string('name');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cpf')->nullable();
 
            // Dados da empresa
            $table->string('empresa')->nullable();
            $table->string('email_empresa')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('telefone_fixo')->nullable();
            $table->string('celular')->nullable();
            $table->string('whatsapp')->nullable();
 
            // Endereço
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
 
            // Informações adicionais
            $table->text('notas_adicionais')->nullable();
 
            // Controle
            $table->enum('status', ['pendente', 'respondido', 'em_andamento', 'finalizado', 'cancelado'])
                  ->default('pendente');
 
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
