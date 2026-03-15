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
        Schema::create('portifolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category'); 
            $table->unsignedBigInteger('company');  
            $table->string('name');
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('slug')->nullable();
            $table->string('headline')->nullable();
            $table->string('tags')->nullable();
            $table->bigInteger('views')->default(0);            
            $table->integer('cat_pai')->nullable();
            $table->integer('status')->nullable();
            $table->integer('exibir')->nullable();
            $table->string('thumb_legenda')->nullable(); 
            $table->decimal('value', 10, 2)->nullable();
            
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();

            $table->timestamps();

            $table->foreign('company')->references('id')->on('companies')->onDelete('CASCADE');
            $table->foreign('category')->references('id')->on('cat_portifolios')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portifolios');
    }
};
