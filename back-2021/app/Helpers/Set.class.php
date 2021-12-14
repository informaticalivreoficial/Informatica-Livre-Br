<?php

/**
 * Set.class [ HELPER ]
 * Classe responável por setar dados no sistema!
 * 
 * @copyright (c) 2019, Renato Montanari - Informática Livre
 */
class Set {
    
    private static $Dados;
    private static $Formato;
    
    /*****************************
    SOMA VISITAS NA TABELA ESTATÍSTICAS DE ACORDO COM A SEÇÃO
    *****************************/
    public static function SetEstatisticas($EstatisticaId) {
        self::$Dados = (int) $EstatisticaId;
        
        if (!empty(self::$Dados)):
            $sellMes = date('m');
            $sellAno = date('Y');
            
            $readEstatisticas = new Read;
            $readEstatisticas->ExeRead("estatisticas","WHERE mes = :mes AND ano = :ano AND secao = :secao","mes={$sellMes}&ano={$sellAno}&secao={$EstatisticaId}");
            if(!$readEstatisticas->getResult()):
                $createEstatisticas = array('mes' => $sellMes, 'ano' => $sellAno, 'secao' => $EstatisticaId, 'count' => '1');
                $createEstatistica = new Create;
                $createEstatistica->ExeCreate("estatisticas", $createEstatisticas);
            else:
                $extract = extract($readEstatisticas->getResult()[0]);
                $createEstatisticas = array('count' => $count + 1);
                $createEstatistica = new Update;
                $createEstatistica->ExeUpdate("estatisticas", $createEstatisticas, "WHERE mes = :mes AND ano = :ano AND secao = :secao","mes={$sellMes}&ano={$sellAno}&secao={$EstatisticaId}");
            endif;
        else:
            return false;
        endif;
    }
}
