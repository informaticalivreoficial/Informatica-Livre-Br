<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert([
            [
                'id'=>1,
                'user'=>1,
                'social_name'=>'Informática Livre',
                'alias_name'=>'Informática Livre',
                'document_company'=>'11111111111111',
                'document_company_secondary'=>NULL,
                'status'=>1,
                'cep'=>'11111111',
                'rua'=>'ssssssss',
                'num'=>'111',
                'complemento'=>'casa',
                'bairro'=>'ssssssss',
                'uf'=>25,
                'cidade'=>5351,
                'telefone'=>'1111111111',
                'celular'=>'11111111111',
                'whatsapp'=>'11111111111',
                'email'=>'contato@teste.com.br',
                'facebook'=>'https://www.facebook.com/teste',
                'twitter'=>NULL,
                'vimeo'=>NULL,
                'youtube'=>NULL,
                'linkedin'=>NULL,
                'instagram'=>NULL,
                'fliccr'=>NULL,
                'soundclound'=>NULL,
                'snapchat'=>NULL,
                'created_at' => now(),//Data e hora Atual
                'publish_at' => now()//Data e hora Atual
            ]
        ]);        
    }
}
