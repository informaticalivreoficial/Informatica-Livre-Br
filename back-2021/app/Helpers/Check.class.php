<?php

/**
 * Check.class [ HELPER ]
 * Classe responável por manipular e validade dados do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Check {

    private static $Data;
    private static $Format;

    /**
     * <b>Verifica E-mail:</b> Executa validação de formato de e-mail. Se for um email válido retorna true, ou retorna false.
     * @param STRING $Email = Uma conta de e-mail
     * @return BOOL = True para um email vÃ¡lido, ou false
     */
    public static function Email($Email) {
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if (preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * <b>Tranforma URL:</b> Tranforma uma string no formato de URL amigável e retorna o a string convertida!
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = Uma URL amigável válida
     */
    public static function Name($Name) {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }

    /**
     * <b>Tranforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP!
     * @param STRING $Name = Data em (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING = $Data = Data no formato timestamp!
     */
    public static function Data($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (empty(self::$Format[1])):
            self::$Format[1] = date('H:i:s');
        endif;

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
        return self::$Data;
    }
    
//    public static function Datapost($Data) {
//        $databd1 = date('Y-m-d-H-i-s', strtotime($Data)); 
//        $databd2 = date('Y-m-d-H-i-s');
//        
//        $data1 = explode('-', $databd1); 
//        $data2 = explode('-', $databd2);
//        
//        $ano = $data2[0] - $data1[0];  $mes = $data2[1] - $data1[1]; 
//        $dia = $data2[2] - $data1[2];  $hora = $data2[3] - $data1[3]; 
//        $min = $data2[4] - $data1[4];  $seg = $data2[5] - $data1[5];
//        
//        // configuração data  
//        if($mes < 0): $ano--; $mes = 12 + $mes; endif;  
//        if($dia < 0): $mes--; $dia = 30 + $dia; endif;  
//        if($ano > 0): $str_ano = $ano.' ano'; endif;  
//        if($ano > 1): $str_ano .= 's '; endif;  
//        if($mes > 0): $str_mes .= $mes.' mes'; endif;  
//        if($mes > 1): 	
//            if($ano > 0): 		
//                $str_ano .= ', ';                 
//            endif; 	
//            $str_mes .= 'es'; 
//        endif;  
//        if($dia > 0): 	$str_dia = $dia . ' dia'; endif;  
//        if($dia > 1): 	
//            if($mes > 0): 		
//                $str_mes .= ', ';                
//            endif;
//            $str_dia .= 's'; 
//        endif;  
//        // configuração hora  
//        if($min < 0): 	$hora--; $min = 60 + $min; endif;  
//        if($seg < 0): 	$min--;  $seg = 60 + $seg; endif;  
//        if($hora > 0): $str_hora = $hora . ' hora'; endif;  
//        if($hora > 1): $str_hora .= 's'; endif;  
//        if ($min > 0): $str_min .= $min . ' minuto'; endif;  
//        if($min > 1): 	
//            if($hora > 0): 		
//                $str_hora .= ', ';                
//            endif; 	
//            $str_min .= 's';            
//        endif;  
//        if($seg > 0): 	$str_seg = $seg . ' seg';endif;  
//        if ($seg > 1): 	
//            if($min > 0): 		
//                $str_min .= ' e ';            
//            endif; 	
//            $str_seg .= 's';        
//        endif;
//        //@$str_ano, @$str_mes, @$str_dia,', ', @$str_hora, @$str_min, ' atrás';
//        
//        self::$Data = $str_ano. $str_mes. $str_dia.', '. $str_hora. $str_min. ' atrás';
//        return self::$Data;
//    }
    
    
    
    /**
     * <b>Limita os Palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @return INT = $Limite = String limitada pelo $Limite
     */
    public static function Words($String, $Limite, $Pointer = null) {
        self::$Data = strip_tags(trim($String));
        self::$Format = (int) $Limite;

        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer );
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data );
        return $Result;
    }

    /**
     * <b>Obter categoria:</b> Informe o name (url) de uma categoria para obter o ID da mesma.
     * @param STRING $category_name = URL da categoria
     * @return INT $category_id = id da categoria informada
     */
    public static function CatByName($tabela, $CategoryName) {
        $read = new Read;
        $read->ExeRead($tabela, "WHERE url = :name", "name={$CategoryName}");
        if ($read->getRowCount()):
            return $read->getResult()[0]['id'];
        else:
            echo "A categoria {$nome} não foi encontrada!";
            die;
        endif;
    }
    
    /**
     * <b>Obter valor de datas:</b> Informe a data atual e a data de expiração para obter o resultado.
     * @param STRING $data_atual  = data atual
     * @param STRING $data_expira = data de expiração
     * @return INT $campo_id   = id do registro
     */
    public static function getDataExpira($tabela, $campo_id, $data_atual, $data_expira) {
        $read = new Read;
        $read->ExeRead($tabela, "WHERE id = :campoId", "campoId={$campo_id}");
        if ($read->getRowCount()):
            $dt_atual = date('Y-m-d'); // data atual
            $timestamp_dt_atual = strtotime($dt_atual); // converte para timestamp Unix
            // data atual é maior que a data de expiração
            if (!$timestamp_dt_atual > date('Y-m-d', strtotime($data_expira))): // true
                return  '<span class="label label-danger">Expirado</span>';
            else: // false

                // Gera o timestamp das duas datas:
                $partes = explode('-', date('Y-m-d'));
                $partes = mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
                
                $partes1 = explode('-', date('Y-m-d', strtotime($data_expira)));
                $partes1 = mktime(0, 0, 0, $partes1[1], $partes1[2], $partes1[0]);
                
                $time_inicial = $partes;
                $time_final = $partes1;

                // Calcula a diferença de segundos entre as duas datas:
                $diferenca = $time_final - $time_inicial; // xxxx segundos

                // Calcula a diferença de dias
                $dias = (int)floor( $diferenca / (60 * 60 * 24)); // xx dias     

               // Exibe uma mensagem de resultado:
                if($dias >= 29):
                    return date('d/m/Y',strtotime($data_expira));
                else:
                    if($time_final < $time_inicial):
                       return  '<span class="label label-danger">Expirado</span>'; 
                    else:
                       return '<span class="label label-warning">Expira em '.$dias.' dias</span>'; 
                    endif;
                endif; 
            endif;
        else:
            return "Erro!";
            die;
        endif;
    }
    
    /**
     * <b>Gera uma data de expiração:</b> Informe o valor de dias para obter converter para a expiração.
     * @param STRING $valor_dias  = valor em dias
     * * @param STRING $valor_data  = data inicial
     */
    public static function getDataExpiraDias($valor_dias, $valor_data = null) {        
        $dataInicial = (!$valor_data ? $valor_data = date('Y-m-d') : $valor_data = $valor_data);        
        // Gera o timestamp da data:
        $partes = explode('-', $dataInicial);
        $partes = mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
        
        $umDia  = 24 * 60 * 60;
        $dataSomada  =  ($valor_dias * $umDia + ($partes));

        if($dataSomada):
            return date('Y-m-d',$dataSomada);
        else:
            return "Erro!";
            die;
        endif;
    }
    
    /**
     * <b>Obter Nome da Categoria:</b> Informe a Tabela e o ID de uma categoria para obter o Nome da mesma.
     * @param INT $id = ID da categoria
     * @return STRING $nome = NOME da categoria informada
     */
    public static function CatById($tabela, $CategoriaId) {
        $readCat = new Read;
        $readCat->ExeRead($tabela, "WHERE id = :id", "id={$CategoriaId}");
        if ($readCat->getResult()):
            return $readCat->getResult()[0]['nome'];
        else:
            echo "A categoria {$nome} não foi encontrada!";
            die;
        endif;
    }
    
    public static function Categoria($tabela, $CategoriaId, $campo = null) {
        $readCat = new Read;
        $readCat->ExeRead($tabela, "WHERE id = :id", "id={$CategoriaId}");
        if($readCat->getResult()):
            foreach($readCat->getResult() as $cat):
                if(!$campo):
                    return $cat;
                else:
                    return $cat[$campo];
                endif;
            endforeach;
        else:
            echo "A categoria não foi encontrada!";
            die;
        endif;
    }
    
    /**
     * <b>Obter Período:</b> Retorna o período da assinatura cadastrada no sistema bastando informar o periodo.
     * Para verificar erros execute um getError();
     * @return string $Var = periodo
     */
    public static function getPeriodo($Periodo){
        $periodo = (int) $Periodo;
        if(!empty($periodo)):
            $assPeriodo = ($periodo == '1' ? 'Mensal' : 
                          ($periodo == '3' ? 'Trimestral' :
                          ($periodo == '6' ? 'Semestral' :
                          ($periodo == '12' ? 'Anual' : 'Erro'))));
            return $assPeriodo;
        else:
            echo "Erro";
            die;
        endif;
    }
    
    public static  function getExpiraData($Expira){
        // transforma data atual em timestamp
        $partes = explode('-',date('Y-m-d'));
        $timestampDataAtual = mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
        
        // transforma data de expiração em timestamp
        $partes1 = explode('-',date('Y-m-d', strtotime($Expira)));
        $timestampExpira = mktime(0, 0, 0, $partes1[1], $partes1[2], $partes1[0]);
        
        // Calcula a diferença de segundos entre as duas datas:
        $diferenca = $timestampExpira - $timestampDataAtual; // xxxxx segundos

        // Calcula a diferença de dias
        $dias = (int)floor( $diferenca / (60 * 60 * 24)); // xx dias
        
        return $dias;
    }

    /**
     * <b>UsuÃ¡rios Online:</b> Ao executar este HELPER, ele automaticamente deleta os usuÃ¡rios expirados. Logo depois
     * executa um READ para obter quantos usuÃ¡rios estÃ£o realmente online no momento!
     * @return INT = Qtd de usuÃ¡rios online
     */
    public static function UserOnline() {
        $now = date('Y-m-d H:i:s');
        $deleteUserOnline = new Delete;
        $deleteUserOnline->ExeDelete('siteviews_online', "WHERE endview < :now", "now={$now}");

        $readUserOnline = new Read;
        $readUserOnline->ExeRead('siteviews_online');
        return $readUserOnline->getRowCount();
    }

    /**
     * <b>Imagem Upload:</b> Ao executar este HELPER, ele automaticamente verifica a existencia da imagem na pasta
     * uploads. Se existir retorna a imagem redimensionada!
     * @return HTML = imagem redimencionada!
     */
    public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null, $ImageClass = null) {

        self::$Data = $ImageUrl;

        if (file_exists(self::$Data) && !is_dir(self::$Data)):
            $patch = BASE;
            $imagem = self::$Data;
            return "<img src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" class=\"{$ImageClass}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }
    
    /**
     * <b>Formata Numero WhatsApp:</b> Ao executar este HELPER, ele automaticamente converte o numero para o formato aceito
     * zap. retorna o link formatado!
     * @return HTML = numero formatado!
     */
    public static function getNumZap($nZap ,$textZap) {
        if(!empty($nZap)):
            $zap = '55' . preg_replace("/[^0-9]/", "", $nZap);
            return "https://api.whatsapp.com/send?l=pt_pt&phone={$zap}&text={$textZap}";
        endif;
        return false;
    }
    /**
     * <b>Saudação:</b> Ao executar este HELPER, dependendo do horário envia uma saudação
     * nome. retorna o texto informado + a saudação!
     * @return HTML = texto informado + a saudação!
     */
    public static function getSaudacao($nome = null) {
        $hora = date('H');		
        if($hora >= 6 && $hora <= 12):
            return (empty($nome) ? '' : $nome).' Bom dia';		
        elseif( $hora > 12 && $hora <=18  ):
            return (empty($nome) ? '' : $nome).' Boa tarde';		
        else:			
            return (empty($nome) ? '' : $nome).' Boa noite';	
        endif;
    }
     /*****************************
    FUNÇÃO PARA PEGAR SOMENTE O PRIMEIRO NOME DO USUÁRIO
     *****************************/
    public static function getPrimeiroNome($pNome) {
        if(!empty($pNome)):
            $pData = explode(" ",$pNome);
            return array_shift($pData);
        endif;
        return false;
    }
    /*****************************
    FUNÇÃO PARA PEGAR SOMENTE O SOBRENOME DO USUÁRIO
     *****************************/
    public static function getSobreNome($pNome) {
        if(!empty($pNome)):
            $pData = explode(" ",$pNome);
            if(isset($pData[1])):
                return $pData[1];
            else:
                return $pData[0];
            endif;
        endif;
        return false;
    }
    
    
    /**
     * <b>Pega o valor da tabela Cidades:</b> Ao executar este HELPER, ele pega o campo desejado na tabela Cidades a partir do ID
     * cidadadeCampo. retorna o campo desejado!
     * @return HTML = Campo!
     */
    public static function getCidade($cidadeId , $cidadeCampo = null) {
        if(!empty($cidadeId)):
            $readCidade = new Read;
            $readCidade->ExeRead("cidades", "WHERE cidade_id = :id","id={$cidadeId}");
            if($readCidade->getResult()):
                foreach($readCidade->getResult() as $cidadeNome);
                if(!$cidadeCampo):
                    return $cidadeNome['cidade_nome'];
                else:
                    return $cidadeNome[$cidadeCampo];
                endif;
            endif;
        else:
            return false;
        endif;        
    }
    
    /**
     * <b>Pega o valor da tabela Bairros:</b> Ao executar este HELPER, ele pega o campo desejado na tabela Bairros a partir do ID
     * BairroCampo. retorna o campo desejado!
     * @return HTML = Campo!
     */
    public static function getBairro($bairroId , $bairroCampo = null) {
        if(!empty($bairroId)):
            $readBairro = new Read;
            $readBairro->ExeRead("bairros", "WHERE id = :id","id={$bairroId}");
            if($readBairro->getResult()):
                foreach($readBairro->getResult() as $bairroNome);
                if(!$bairroCampo):
                    return $bairroNome['nome'];
                else:
                    return $bairroNome[$bairroCampo];
                endif;
            endif;
        else:
            return false;
        endif;        
    }
    
    /**
     * <b>Faz uma contagem de registros em qualquer tabela:</b> Ao executar este HELPER, ele retorna os registros a partir do ID
     * Tabela. retorna o registros!
     * @return HTML = registros Count!
     */
    public static function getCountRegister($Tabela, $CampoWhere = null, $CampoReferencia = null) {
        if(!empty($Tabela)):
            $readTabela = new Read;
            $readTabela->ExeRead("{$Tabela}", "WHERE {$CampoWhere} = :Campo","Campo={$CampoReferencia}");
            if($readTabela->getResult()):
                foreach($readTabela->getResult() as $result);
                if(!$CampoWhere):
                    return $readTabela->getRowCount();
                else:
                    return $readTabela->getRowCount();
                endif;
            endif;
        else:
            return false;
        endif;        
    }
    
    /**
     * <b>Pega o valor de medidas cadastradas e converte para a Sigla:</b> Ao executar este HELPER, ele pega sigla para a medida selecionada
     * siglaId. retorna a Sigla da medida!
     * @return HTML = Sigla!
     */
    public static function getSigla($siglaId) {
        if(!empty($siglaId)):
            $Sigla = ($siglaId == '1' ? 'm²' : 
                     ($siglaId == '2' ? 'hectare' : 
                     ($siglaId == '0' ? 'km²' :
                     ($siglaId == '3' ? 'alqueire' : 'm²'))));
            if($siglaId == '1' || $siglaId == '2' || $siglaId == '0' || $siglaId == '3'):
                return $Sigla;
            else:
                return false;
            endif;
        else:
            return false;
        endif;        
    }
    
    /**
     * <b>Pega a quantidade de comentarios do Post:</b> Ao executar este HELPER, ele pega a quantidade de comentarios do ID do post
     * PostId. retorna a quantidade de comentarios!
     * @return HTML = Campo!
     */
    public static function getComentariosCount($postId) {
        if(!empty($postId)):
            $readComentarios = new Read;
            $readComentarios->ExeRead("comentarios", "WHERE status = '1' AND post_id = :postId","postId={$postId}");
            if($readComentarios->getResult()):
                if($readComentarios->getRowCount() == '1'):
                    return $readComentarios->getRowCount().' comentário';
                elseif($readComentarios->getRowCount() > '1'):
                    return $readComentarios->getRowCount().' comentários';
                else:
                    return false;
                endif; 
            else:
                return '0';
            endif;
        else:
            return false;
        endif;        
    }
    
    /**
     * <b>Pega o valor da tabela Estados:</b> Ao executar este HELPER, ele pega o campo desejado na tabela Estados a partir do ID
     * ufCampo. retorna o campo desejado!
     * @return HTML = Campo!
     */
    public static function getUf($ufId , $ufCampo = null) {
        if(!empty($ufId)):
            $readUf = new Read;
            $readUf->ExeRead("estados", "WHERE estado_id = :id","id={$ufId}");
            if($readUf->getResult()):
                foreach($readUf->getResult() as $ufNome);
                if(!$ufCampo):
                    return $ufNome['estado_nome'];
                else:
                    return $ufNome[$ufCampo];
                endif;
            endif;
        else:
            return false;
        endif;        
    }   
    
    
    public static function getUser($tabela, $user ,$campo = null) {
        $readUser = new Read;
        $readUser->ExeRead("{$tabela}", "WHERE id = :user","user={$user}");
        if($readUser->getResult()):
                foreach($readUser->getResult() as $ruser):
                    if($ruser['avatar'] == null):
                        $gravatar  = 'https://gravatar.com/avatar/';
                        $ruser['avatar'] = $gravatar;
                    endif;
                    $nivelUser =($ruser['nivel'] == '1' ? 'Administrador' : 
                                ($ruser['nivel'] == '2' ? 'Editor' :
                                ($ruser['nivel'] == '3' ? 'Leitor' : 
                                ($ruser['nivel'] == '4' ? 'Público' : 'Público'))));
                    $ruser['nivel']  = $nivelUser;
                    if(!$campo):
                        return $ruser;
                    else:
                        return $ruser[$campo];
                    endif;
                endforeach;
        else:
                echo 'Erro ao ler autor';
                die;
        endif;
        
    }
    
    /**
     * <b>Obter Estrelas em Avaliação:</b> Informe o Número de 0 a 100 o sistema retorna as estrelas.
     * @param INT $Number = Número da nota
     * @return STRING $Stars = Estrelas
     */
    public static function getStars($Number) {
        $Number = (int) $Number;
        if($Number <= '20'):
            return '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif($Number >= '21' && $Number <= '40'):
            return '<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif($Number >= '41' && $Number <= '60'):
            return '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif($Number >= '61' && $Number <= '80'):
            return '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>';
        elseif($Number >= '81' && $Number <= '100'):
            return '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
        else:
            return false;
            die;
        endif;
    }

}