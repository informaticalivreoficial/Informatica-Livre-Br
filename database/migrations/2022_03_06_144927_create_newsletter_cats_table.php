<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsletterCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_cat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->integer('status')->nullable();
            $table->string('servidor_smtp')->nullable();
            $table->integer('servidor_porta')->nullable();
            $table->integer('sistema')->nullable();
            $table->string('servidor_senha')->nullable();
            $table->string('servidor_email')->nullable();
            $table->text('content')->nullable();
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletter_cat');
    }
}
