<?php

namespace App\Helpers;

class Renato
{
    /**
    * <b>Saudação:</b> Ao executar este HELPER, dependendo do horário envia uma saudação
    * nome. retorna o texto informado + a saudação!
    * @return HTML = texto informado + a saudação!
    */
    public static function getSaudacao($nome = null)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $hora = date('H');		
        if($hora >= 6 && $hora <= 12):
            return (empty($nome) ? '' : $nome).' bom dia';		
        elseif( $hora > 12 && $hora <=18  ):
            return (empty($nome) ? '' : $nome).' boa tarde';		
        else:			
            return (empty($nome) ? '' : $nome).' boa noite';	
        endif;
    }
}