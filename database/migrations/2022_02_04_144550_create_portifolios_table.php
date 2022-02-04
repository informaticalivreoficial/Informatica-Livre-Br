<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortifoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portifolio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('slug')->nullable();
            $table->string('headline')->nullable();
            $table->string('tags')->nullable();
            $table->bigInteger('views')->default(0);
            $table->unsignedInteger('categoria');
            $table->integer('cat_pai')->nullable();
            $table->integer('status')->nullable();
            $table->integer('exibir')->nullable();
            $table->string('thumb_legenda')->nullable(); 
            $table->date('publish_at')->nullable();

            $table->timestamps();

            $table->foreign('categoria')->references('id')->on('cat_portifolio')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portifolio');
    }
}
