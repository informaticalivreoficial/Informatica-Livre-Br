<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default('0');
            $table->integer('ano_de_inicio')->nullable();
            //$table->unsignedInteger('user');
            $table->string('nomedosite');
            $table->string('cnpj')->nullable();
            $table->string('ie')->nullable();
            $table->string('dominio')->nullable();
            $table->string('template')->nullable();
            
            /** imagens */
            $table->string('logomarca')->nullable();
            $table->string('logomarca_admin')->nullable();
            $table->string('logomarca_footer')->nullable();
            $table->string('favicon')->nullable();
            $table->string('metaimg')->nullable();
            $table->string('imgheader')->nullable();
            $table->string('marcadagua')->nullable();
            
            /** smtp */
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_user')->nullable();
            $table->string('smtp_pass')->nullable();
            
            /** contact */
            $table->string('telefone1')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('telefone3')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('skype')->nullable();
            $table->string('email')->nullable();
            $table->string('email1')->nullable();
            
            /** address */
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('num')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->integer('uf')->nullable();
            $table->integer('cidade')->nullable();
            
            /** redes sociais */
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('vimeo')->nullable();
            $table->string('fliccr')->nullable();
            $table->string('soundclound')->nullable();
            $table->string('snapchat')->nullable(); 
            
            /** seo */
            $table->text('descricao')->nullable();
            $table->text('mapa_google')->nullable();
            $table->text('metatags')->nullable();
            $table->string('rss');
            $table->date('rss_data')->nullable();
            $table->string('sitemap');
            $table->date('sitemap_data')->nullable();
            
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
        Schema::dropIfExists('configuracoes');
    }
}
