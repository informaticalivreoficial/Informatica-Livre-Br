<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParceiroGbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiro_gbs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parceiro_id');
            $table->string('path');
            $table->boolean('cover')->nullable();
            
            $table->timestamps();

            $table->foreign('parceiro_id')->references('id')->on('parceiros')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parceiro_gbs');
    }
}
