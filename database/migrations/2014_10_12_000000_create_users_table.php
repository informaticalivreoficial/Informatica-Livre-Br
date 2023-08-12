<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('senha');
            $table->rememberToken();            
            $table->timestamps();

            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            /** data */
            $table->string('genero')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('rg_expedicao')->nullable();
            $table->date('nasc')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email1')->nullable();

            /** address */
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('num')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->integer('uf')->nullable();
            $table->integer('cidade')->nullable();

            /** contact */
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('skype')->nullable();
            $table->string('telegram')->nullable();

            /** Redes Sociais */
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('vimeo')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('fliccr')->nullable();

            /** access */
            $table->boolean('admin')->nullable();
            $table->boolean('client')->nullable();
            $table->boolean('editor')->nullable();
            $table->boolean('superadmin')->nullable();

            $table->integer('status')->default('0');
            $table->text('notasadicionais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
