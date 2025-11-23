<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('autor');
            $table->string('type');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('slug')->nullable();
            $table->text('tags')->nullable();
            $table->bigInteger('views')->default(0);
            $table->unsignedInteger('category');
            $table->integer('cat_pai')->nullable();
            $table->integer('comments')->nullable();
            $table->integer('status')->nullable();
            $table->integer('highlight')->nullable()->default(0);
            $table->integer('menu')->nullable();
            $table->string('thumb_caption')->nullable(); 
            $table->date('publish_at')->nullable();

            $table->timestamps();

            $table->foreign('autor')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('category')->references('id')->on('cat_post')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
