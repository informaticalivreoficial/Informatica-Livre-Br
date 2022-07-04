<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Cidade
{
    // pega o nome da cidade a partir de um ID relacionado
    public static function getCidadeNome($id, $tabela)
    {
        if (empty($id) && empty($tabela)) {
            return null;
        }
        $cidade = DB::table(''.$tabela.'')->where('cidade_id', '=', $id)->get();
        if(!empty($cidade)){
            return $cidade[0]->cidade_nome.'/'.$cidade[0]->cidade_uf;
        }else{
            return null;
        }
    }
}