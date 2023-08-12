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
            $table->string('status')->nullable();
            $table->unsignedInteger('orcamento')->nullable();
            $table->unsignedInteger('produto')->nullable();
            $table->unsignedInteger('empresa');
            $table->string('uuid')->nullable();
            $table->date('form_sendat')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('gateway')->nullable();
            $table->text('url_slip')->nullable();
            $table->string('digitable_line')->nullable();
            $table->date('vencimento')->nullable();
            $table->integer('valor')->nullable();
            $table->string('notas_adicionais')->nullable();

            $table->timestamps();

            $table->foreign('empresa')->references('id')->on('empresas')->onDelete('CASCADE');
            $table->foreign('orcamento')->references('id')->on('orcamentos');
            $table->foreign('produto')->references('id')->on('produtos');
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
