<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido');
            $table->string('uuid')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_date')->nullable();
            $table->date('vencimento')->nullable();
            $table->date('form_sendat')->nullable();
            $table->decimal('valor', 10, 2)->nullable(); 
            $table->text('url_slip')->nullable();
            $table->string('digitable_line')->nullable();
            $table->integer('gateway')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();

            $table->foreign('pedido')->references('id')->on('pedidos')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faturas');
    }
}
