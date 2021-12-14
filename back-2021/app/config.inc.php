<?php
ini_set('error_reporting', E_ALL);
ini_set('log_errors' , TRUE);
ini_set('html_errors' , TRUE);
ini_set('display_errors' , TRUE);


// coloca o dysplay errors como OFF
//error_reporting('E_ALL & ~E_NOTICE');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
      
//DEFINE BANCO DE DADOS
if(!defined("HOST")){ define("HOST","localhost");}
if(!defined("USER")){ define("USER","informa9_infolivre");}
if(!defined("PASS")){ define("PASS","0BfE*DjKO5fv");}
if(!defined("DBSA")){ define("DBSA","informa9_infolivre");}

//INFORMAÇÕES DO SERVIDOR
const SERVERIP   = '67.23.238.20';
const SERVERPORT = '80';


$readConfigBase = new Read;
$readConfigBase->ExeRead("configuracoes","WHERE id = '1'");
foreach($readConfigBase->getResult() as $configBase);

//INFORMAÇÕES DO SITE E DA EMPRESA
if(!defined("BASE")){ define("BASE","https://informaticalivre.com.br");}
if(!defined("TEMPLATE")){ define("TEMPLATE","$configBase[template]");}
define('PATCH', BASE . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . TEMPLATE);
define('REQUIRE_PATCH', 'template' . DIRECTORY_SEPARATOR . TEMPLATE);

if(!defined("LOGOMARCAADMIN")){ define("LOGOMARCAADMIN","$configBase[logomarcaadmin]");}
if(!defined('SITENAME')){ define('SITENAME',"$configBase[nomedosite]");}
if(!defined("SITETAGS")){ define("SITETAGS","$configBase[tagsdosite]");}
if(!defined("FORMCONTATO")){ define("FORMCONTATO","Enviado pelo Site");}
if(!defined("SITEDESC")){ define("SITEDESC","$configBase[descricaodosite]");}
if(!defined("RUA")){ define("RUA","$configBase[rua]");}
if(!defined("NUMERO")){ define("NUMERO","$configBase[numero]");}
if(!defined("LATITUDE")){ define("LATITUDE","$configBase[latitude]");}
if(!defined("LONGITUDE")){ define("LONGITUDE","$configBase[longitude]");}
if(!defined("BAIRRO")){ define("BAIRRO","$configBase[bairro]");}
if(!defined("COMPLEMENTO")){ define("COMPLEMENTO","$configBase[complemento]");}
if(!defined("CIDADE")){ define("CIDADE","$configBase[cidade]");}
if(!defined("CEP")){ define("CEP","$configBase[cep]");}
if(!defined("UF")){ define("UF","$configBase[uf]");}
if(!defined("CPFCNPJ")){ define("CPFCNPJ","$configBase[cpf_cnpj]");}
if(!defined("ANODEINICIO")){define("ANODEINICIO","$configBase[AnoDeInicio]");}
if(!defined("LOGOMARCA")){ define("LOGOMARCA","$configBase[logomarca]");}
if(!defined("LOGOMARCAFOOTER")){ define("LOGOMARCAFOOTER","$configBase[logomarcafooter]");}
if(!defined("METAIMAGEM")){ define("METAIMAGEM","$configBase[metaimg]");}
if(!defined("FAVICON")){ define("FAVICON","$configBase[favicon]");}
if(!defined("MARCADAGUA")){ define("MARCADAGUA","$configBase[marca_dagua]");}
if(!defined("IMGTOPO")){ define("IMGTOPO","$configBase[imgtopo]");}

//SEO
if(!defined("SITEMAP")){ define("SITEMAP","$configBase[sitemap]");}	
if(!defined("SITEMAPDATA")){ define("SITEMAPDATA","$configBase[sitemapdata]");}   

//DEFINE O SERVIDOR DE E-MAIL
if(!defined("MAILUSER")){ define("MAILUSER","$configBase[SMTPUsername]");}
if(!defined("MAILPASS")){ define("MAILPASS","$configBase[SMTPPassword]");}
if(!defined("MAILPORT")){ define("MAILPORT","$configBase[SMTPPort]");}
if(!defined("MAILHOST")){ define("MAILHOST","$configBase[SMTPHost]");}

//MEUS DADOS
if(!defined("TELEFONE1")){ define("TELEFONE1","$configBase[tel1]");}
if(!defined("TELEFONE2")){ define("TELEFONE2","$configBase[tel2]");}
if(!defined("TELEFONE3")){ define("TELEFONE3","$configBase[tel3]");}
if(!defined("NEXTEL")){ define("NEXTEL","$configBase[nextel]");}
if(!defined("EMAIL")){ define("EMAIL","$configBase[email]");}
if(!defined("EMAIL1")){ define("EMAIL1","$configBase[email1]");}
if(!defined("SKYPE")){ define("SKYPE","$configBase[skype]");}
if(!defined("WATSAPP")){ define("WATSAPP","$configBase[watsapp]");}

//REDE SOCIAL
if(!defined("FACEBOOK")){ define("FACEBOOK","$configBase[facebook]");}
if(!defined("TWITTER")){ define("TWITTER","$configBase[twitter]");}
if(!defined("YOUTUBE")){ define("YOUTUBE","$configBase[youtube]");}
if(!defined("FLICCR")){ define("FLICCR","$configBase[fliccr]");}
if(!defined("INSTAGRAN")){ define("INSTAGRAN","$configBase[instagran]");}
if(!defined("VIMEO")){ define("VIMEO","$configBase[vimeo]");}
if(!defined("GOOGLE")){ define("GOOGLE","$configBase[google]");}
if(!defined("LINKEDIN")){ define("LINKEDIN","$configBase[linkedin]");}
if(!defined("SOUNDCLOUD")){ define("SOUNDCLOUD","$configBase[soundcloud]");}
if(!defined("SNAPCHAT")){ define("SNAPCHAT","$configBase[snapchat]");}

// MAPA DO GOOGLE
if(!defined("MAPADOGOOGLE")){ define("MAPADOGOOGLE","$configBase[mapadogoogle]");}

//DADOS DO DESENVOLVEDOR
define("DESENVOLVEDOR","$configBase[Desenvolvedor]");
define("DESENVOLVEDORURL","$configBase[DesenvolvedorUrl]");
define("DESENVOLVEDORLOGO","$configBase[DesenvolvedorLogo]");
define("DESENVOLVEDORTELEFONE","$configBase[DesenvolvedorTelefone]");
define("DESENVOLVEDOREMAIL","$configBase[DesenvolvedorEmail]");


// AUTO LOAD DE CLASSES ####################
//function __autoload($Class) {
//
//    $cDir = ['Conn', 'Helpers', 'Models'];
//    $iDir = null;
//
//    foreach ($cDir as $dirName):
//        if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php')):
//            include_once (__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php');
//            $iDir = true;
//        endif;
//    endforeach;
//
//    if (!$iDir):
//        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
//        die;
//    endif;
//}

// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
if(!defined("RM_ACCEPT")){ define("RM_ACCEPT","alert-success");}
if(!defined("RM_INFOR")){ define("RM_INFOR","alert-info");}
if(!defined("RM_ALERT")){ define("RM_ALERT","alert-warning");}
if(!defined("RM_ERROR")){ define("RM_ERROR","alert-danger");}

//RMErro :: Exibe erros lançados :: Front
function RMErro($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? RM_INFOR : ($ErrNo == E_USER_WARNING ? RM_ALERT : ($ErrNo == E_USER_ERROR ? RM_ERROR : $ErrNo)));
    echo "<div class=\"alert alert-block {$CssClass} fade in\"> 
            <button type=\"button\" class=\"close close-sm\" data-dismiss=\"alert\">
                <i class=\"fa fa-times\"></i>
            </button>           
            {$ErrMsg}
        </div>";

    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? RM_INFOR : ($ErrNo == E_USER_WARNING ? RM_ALERT : ($ErrNo == E_USER_ERROR ? RM_ERROR : $ErrNo)));
    echo "<div class=\"alert alert-block {$CssClass} fade in\"> 
            <button type=\"button\" class=\"close close-sm\" data-dismiss=\"alert\">
                <i class=\"fa fa-times\"></i>
            </button>           
            <b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>
            <small>{$ErrFile}</small>
        </div>";   

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
?>