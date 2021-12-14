<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('nome');
            $table->string('sobrenome')->nullable();
            $table->integer('status')->nullable();
            $table->integer('autorizacao')->nullable();
            $table->unsignedInteger('categoria');
            $table->string('whatsapp')->nullable();
            $table->bigInteger('count')->default(0);
            
            $table->timestamps();
            
            $table->foreign('categoria')->references('id')->on('newsletter_cat')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletter');
    }
}
