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
        Schema::create('cat_portifolios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pai')->unsigned()->nullable();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('slug')->nullable();
            $table->string('tags')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('type')->nullable();
            $table->integer('status')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_portifolios');
    }
};
