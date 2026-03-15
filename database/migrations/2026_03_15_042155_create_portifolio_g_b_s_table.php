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
        Schema::create('portifolio_gbs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('portifolio');
            $table->string('path');
            $table->boolean('cover')->nullable();

            $table->timestamps();

            $table->foreign('portifolio')->references('id')->on('portifolios')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portifolio_gbs');
    }
};
