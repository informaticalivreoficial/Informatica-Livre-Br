<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('butom_label')->nullable();
            $table->string('image')->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->boolean('target')->nullable();
            $table->string('slug')->nullable();
            $table->string('category')->nullable();
            $table->date('expired_at')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('view_title')->nullable();
            
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
        Schema::dropIfExists('slides');
    }
}
