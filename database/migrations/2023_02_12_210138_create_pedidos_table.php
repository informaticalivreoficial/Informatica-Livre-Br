<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable();
            $table->unsignedInteger('orcamento');
            $table->unsignedInteger('empresa');

            $table->timestamps();

            $table->foreign('empresa')->references('id')->on('empresas')->onDelete('CASCADE');
            $table->foreign('orcamento')->references('id')->on('orcamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
