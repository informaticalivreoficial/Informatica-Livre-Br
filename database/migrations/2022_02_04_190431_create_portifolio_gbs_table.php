<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortifolioGbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portifolio_gb', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('portifolio');
            $table->string('path');
            $table->boolean('cover')->nullable();

            $table->timestamps();

            $table->foreign('portifolio')->references('id')->on('portifolio')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portifolio_gb');
    }
}
