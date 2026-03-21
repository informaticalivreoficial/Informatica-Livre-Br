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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->text('descricao')->nullable();
            $table->longText('conteudo')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('features')->nullable();       // lista de funcionalidades
            $table->json('screenshots')->nullable();    // imagens do sistema
            $table->boolean('destaque')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
