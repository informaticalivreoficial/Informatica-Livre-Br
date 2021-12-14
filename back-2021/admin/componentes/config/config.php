<?php
	if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
?>
<div class="page-heading">
<div class="row">
<div class="col-sm-12"> 
<?php  

    $configPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $idConfig = '1';
       
    // ATUALIZA INFORMAÇÕES GERAIS //
    if(!empty($configPost) && isset($configPost['InfoGerais'])):       
        $configPost['metaimg'] = ($_FILES['metaimg']['tmp_name'] ? $_FILES['metaimg'] : 'null');
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);        
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);                    
    endif;
    
    if(!empty($configPost) && isset($configPost['submitxmlImgTopo'])):       
        $configPost['imgtopo'] = ($_FILES['imgtopo']['tmp_name'] ? $_FILES['imgtopo'] : 'null');                
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);        
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);                    
    endif;
        
    // ATUALIZA REDES SOCIAIS //
    if(!empty($configPost) && isset($configPost['InfoRedesSociais'])):
        unset($configPost['InfoRedesSociais']);
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);        
    endif;
    
    // ATUALIZA BD //
    //if():
    //endif;    
    
    // ATUALIZA INFORMAÇÕES DE CONTATO //
    if(!empty($configPost) && isset($configPost['InfoContato'])):
        unset($configPost['InfoContato']);
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
        
    // ATUALIZA MAPAS //
    if(!empty($configPost) && isset($configPost['MapaGoogle'])):
        unset($configPost['MapaGoogle']);
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    // ATUALIZA IMAGENS LOGOMARCA SITE //
    if(!empty($configPost) && isset($configPost['submitxmllogomarca'])):        
        $configPost['logomarca'] = ($_FILES['logomarca']['tmp_name'] ? $_FILES['logomarca'] : 'null');                
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    // ATUALIZA IMAGENS LOGOMARCA ADMIN //
    if(!empty($configPost) && isset($configPost['submitxmllogoadmin'])):
        $configPost['logomarcaadmin'] = ($_FILES['logomarcaadmin']['tmp_name'] ? $_FILES['logomarcaadmin'] : 'null');                
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    // ATUALIZA IMAGENS LOGOMARCA FAVICON //
    if(!empty($configPost) && isset($configPost['submitxmlfavicon'])):
        $configPost['favicon'] = ($_FILES['favicon']['tmp_name'] ? $_FILES['favicon'] : 'null');                
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    // ATUALIZA IMAGENS MARCADAGUA //
    if(!empty($configPost) && isset($configPost['submitxmlmarcadagua'])):
        $configPost['marca_dagua'] = ($_FILES['marca_dagua']['tmp_name'] ? $_FILES['marca_dagua'] : 'null');                
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    // ATUALIZA THEMA //
    if(!empty($configPost) && isset($configPost['submitthema'])):
        unset($configPost['submitthema']);
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]); 
    endif;
    
    // ATUALIZA SEO //
    if(!empty($configPost) && isset($configPost['submitseo'])):        
        //Abre o diretorio raiz
        $handle= @opendir(".");
        // abre ou cria o arquivo xml
        $xml = fopen("../sitemap.xml","w+");
        //Gravamos os dados iniciais do xml
        fwrite($xml,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n\n");
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>daily</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>1.0</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";           
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/pagina/simulador</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/pagina/atendimento</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/imoveis/index</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/imoveis/busca-por-referencia</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/imoveis/cadastrar</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        //Abre url
        $conteudo  = '  <url>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <loc>'.BASE.'/blog/artigos</loc>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <lastmod>'.date('Y-m-d').'</lastmod>'."\n";
        //informa a frequencia de atualização da pagina
        $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
        //informa a prioridade da pagina
        $conteudo .= '     <priority>0.2</priority>'."\n";
        //Fecha url
        $conteudo .= '  </url>'."\n";
        fwrite($xml,$conteudo);
        
        $readPaginas = new Read; 
        $readPaginas->ExeRead("posts","WHERE tipo = 'pagina' AND status = '1' AND secao = '0' ORDER BY data DESC");
        if($readPaginas->getResult()):
            foreach($readPaginas->getResult() as $pag):            	
        	//$categoria = $pag['categoria'];
        	$url       = $pag['url'];
        	$data      = $pag['data'];
        
            //Abre url
            $conteudo  = '  <url>'."\n";	 
            //pega o Dominio e o nome do arquivo
            $conteudo .='     <loc>'.BASE.'/pagina/'.$url.'</loc>'."\n";
            //pega a data atual e informa no xml
            $conteudo .= '     <lastmod>'.date('Y-m-d', strtotime($data)).'</lastmod>'."\n";
            //informa a frequencia de atualização da pagina
            $conteudo .= '     <changefreq>monthly</changefreq>'."\n";
            //informa a prioridade da pagina
            $conteudo .= '     <priority>0.2</priority>'."\n";
            //Fecha url
            $conteudo .= '  </url>'."\n";
            fwrite($xml,$conteudo);
            endforeach;
        endif;       	    
        
        closedir($handle);
        //Fechamos a estrutura do xml
        fwrite($xml,"\n</urlset>");
        //Fecha o arquivo aberto (para liberar memoria do servidor)
        fclose($xml); 
        
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);        
    endif;
    
    
    // ATUALIZA RSS //
    if(!empty($configPost) && isset($configPost['submitrss'])):
        //Abre o diretorio raiz
        $handle= @opendir(".");
        // abre ou cria o arquivo xml
        $xml = fopen("../rss.xml","w+");
        //Gravamos os dados iniciais do xml
        fwrite($xml,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n");
        //Gravamos os dados iniciais do xml
        fwrite($xml,"<rss version=\"2.0\">\n\n");
        //Abre canal
        fwrite($xml,"<channel>\n");
        // TÃ­tulo do site
        $conteudo  = '  <title>'.SITENAME.'</title>'."\n";
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <link>'.BASE.'</link>'."\n";	 
        //pega o Dominio e o nome do arquivo
        $conteudo .='     <description>'.SITETAGS.'</description>'."\n";
        //pega a data atual e informa no xml
        $conteudo .= '     <language>pt-br</language>'."\n";
        // FIM da DescriÃ§Ã£o
        fwrite($xml,$conteudo);

        $readNoticias = new Read; 
        $readNoticias->ExeRead("posts","WHERE tipo = 'noticia' AND status = '1' ORDER BY data DESC LIMIT 10");
        if($readNoticias->getResult()):
            foreach($readNoticias->getResult() as $noticia):
                //Abre url
                $conteudo  = '  <item>'."\n";	 
                //pega o Dominio e o nome do arquivo
                $conteudo .='     <title>'.$noticia['titulo'].'</title>'."\n";
                $conteudo .= '     <image>'.BASE.'/uploads/'.$noticia['thumb'].'</image>'."\n";
                //pega a data atual e informa no xml
                $conteudo .= '     <url>'.BASE.'/noticia/'.$noticia['url'].'</url>'."\n";
                //informa a frequencia de atualizaÃ§Ã£o da pagina
                $conteudo .= '     <pubDate>'.date('Y-m-d',strtotime($noticia['data'])).'</pubDate>'."\n";
                //informa a prioridade da pagina
                $conteudo .= '     <description>'.Check::Words(strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",html_entity_decode($noticia['content']))),50).'</description>'."\n";
                //Fecha url
                $conteudo .= '  </item>'."\n";
                fwrite($xml,$conteudo);
            endforeach;
        endif;
        
        $readArtigos = new Read; 
        $readArtigos->ExeRead("posts","WHERE tipo = 'artigo' AND status = '1' ORDER BY data DESC LIMIT 10");
        if($readArtigos->getResult()):
            foreach($readArtigos->getResult() as $artigo):
                //Abre url
                $conteudo  = '  <item>'."\n";	 
                //pega o Dominio e o nome do arquivo
                $conteudo .='     <title>'.$artigo['titulo'].'</title>'."\n";
                $conteudo .= '     <image>'.BASE.'/uploads/'.$artigo['thumb'].'</image>'."\n";
                //pega a data atual e informa no xml
                $conteudo .= '     <url>'.BASE.'/noticia/'.$artigo['url'].'</url>'."\n";
                //informa a frequencia de atualizaÃ§Ã£o da pagina
                $conteudo .= '     <pubDate>'.date('Y-m-d',strtotime($artigo['data'])).'</pubDate>'."\n";
                //informa a prioridade da pagina
                $conteudo .= '     <description>'.Check::Words(strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",html_entity_decode($artigo['content']))),50).'</description>'."\n";
                //Fecha url
                $conteudo .= '  </item>'."\n";
                fwrite($xml,$conteudo);
            endforeach;
        endif;
        
        closedir($handle);
        //Fechamos a estrutura do xml
        fwrite($xml,"</channel>");
        fwrite($xml,"\n</rss>");
        //Fecha o arquivo aberto (para liberar memoria do servidor)
        fclose($xml);
        
        require('models/AdminConfig.class.php');
        $atualiza = new AdminConfig;
        $atualiza->ExeUpdate($idConfig, $configPost);
        RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
    endif;
    
    
    // FORMULÁRIO DE SUPORTE //
    if(!empty($configPost) && isset($configPost['submitsuporte'])):    
        unset($configPost['submitsuporte']); 
        
        require('models/EmailAdmin.class.php');
        $SendMail = new Email;        
        
        //Destinatário
        $configPost['Assunto'] = '#Solicitção de Suporte enviada por '.SITENAME.'';
        $configPost['DestinoNome'] = DESENVOLVEDOR;
        $configPost['DestinoEmail'] = DESENVOLVEDOREMAIL;
        
        $configPost['DestinoCopia']    = null;
        $configPost['DestinoCCopia']    = null;
        $configPost['DestinoArquivo']    = null;
        $configPost['DestinoArquivoNome']    = null;
        
        $configPost['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                                    <div style="width:100%;">        
                                        <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                                #Suporte web
                                            </div>
                                            
                                            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                                Mensagem enviada '.date('d/m/Y').'
                                            </div>                        
                                        </div>
                                    
                                        <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Dados da Mensagem</h1>
                                            <p>
                                            <strong>Nome: </strong><strong style="color:#09F;">'.$configPost['RemetenteNome'].'</strong>
                                            <br />
                                            <strong>E-mail: </strong><strong style="color:#09F;">'.$configPost['RemetenteEmail'].'</strong>
                                            <br />
                                            <strong>Mensagem: </strong>
                                            <p style="font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">'.nl2br($configPost['Mensagem']).'</p>
                                            </p>
                                        </div> 
                                    </div>
                                    <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por '.DESENVOLVEDOR.' - <a href="mailto:'.DESENVOLVEDOREMAIL.'">'.DESENVOLVEDOREMAIL.'</a></pre></div>
                                </body>';               
        
        
        //REMETENTE        
        $Resposta['RemetenteNome']  = DESENVOLVEDOR;
        $Resposta['RemetenteEmail'] = DESENVOLVEDOREMAIL;
        $Resposta['Assunto']        = '#Suporte Informática Livre';
        $Resposta['DestinoNome'] = SITENAME;
        $Resposta['DestinoEmail'] = EMAIL;        
        $Resposta['DestinoCopia']    = null;
        $Resposta['DestinoCCopia']    = null;
        $Resposta['DestinoArquivo']    = null;
        $Resposta['DestinoArquivoNome']    = null;
        
        //Mensagem de Retorno
        $Resposta['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                                    <div style="width:100%;">        
                                        <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                                '.DESENVOLVEDOR.'
                                            </div>
                                            
                                            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                                Recebemos sua mensagem dia '.date('d/m/Y').'
                                            </div>                        
                                        </div>
                                    
                                        <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá <strong style="color:#09F;">'.$configPost['RemetenteNome'].'</strong>!</h1>
                                            <p>Recebemos sua mensagem pelo suporte do seu Painel de controle.</p>
                                            <p>Em alguns instantes nossa equipe responderá a sua solicitação.</p>
                                            <div style="background:#DFEFFF; padding:15px;">
                                                <p>Este e-mail foi enviado automaticamente pelo nosso sistema. Por favor, não responder.</p>
                                            </div>
                                        </div> 
                                    </div>
                                    <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por '.DESENVOLVEDOR.' - <a href="mailto:'.DESENVOLVEDOREMAIL.'">'.DESENVOLVEDOREMAIL.'</a></pre></div>
                                </body>  
                                '; 
                                
        $SendMail = new Email;
        $SendMail->Enviar($configPost);
        $SendMail1 = new Email;
        $SendMail1->Enviar($Resposta);
                
        if($SendMail->getError()):
            RMErro($SendMail->getError()[0], $SendMail->getError()[1]);            
        endif;
        
         
    endif;    
    
    
    if(!empty($_SESSION['errCapa'])):
        RMErro($_SESSION['errCapa'], E_USER_WARNING);
        unset($_SESSION['errCapa']);
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if ($checkCreate && empty($atualiza)):
        RMErro("A Configuração <b>{$nomedosite}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT);
    endif;   
     
    
    $configSite = new Read;
    $configSite->ExeRead("configuracoes","WHERE id = '$idConfig'");
    if($configSite->getResult()):
        foreach($configSite->getResult() as $configData);
        extract($configData);
    endif;  
?>
    </div>

    <div class="col-sm-12">
        <h3>Configurações do Site</h3>
    </div>    
</div>
</div>

<div class="wrapper">
    <div class="row">
       
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading custom-tab dark-tab">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Informações Gerais</a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab">Redes Sociais</a></li>
                        <li class=""><a href="#tab_3" data-toggle="tab">BD</a></li>
                        <li class=""><a href="#tab_4" data-toggle="tab">Informações de Contato</a></li>
                        <li class=""><a href="#tab_5" data-toggle="tab">Mapas</a></li>
                        <li class=""><a href="#tab_6" data-toggle="tab">Imagens</a></li>
                        <li class=""><a href="#tab_7" data-toggle="tab">Temas</a></li>
                        <li class=""><a href="#tab_8" data-toggle="tab">SEO</a></li>
                        <li class=""><a href="#tab_9" data-toggle="tab">Suporte</a></li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content">
<!---------------------------- ABA CONFIGURAÇÕES GERAIS ----------------------->
<div class="tab-pane active" id="tab_1">
    <h3>Informações Gerais</h3>
    <?= $userlogin['nome'];?> aqui você pode configurar as informações do site <b><?= $nomedosite; ?></b>.
    <hr />
    <form name="InfoGerais" action="" method="post" enctype="multipart/form-data"><!-- Início do formulario de configurações gerais -->
    <input type="hidden" name="metaimg_width" value="<?= $metaimg_width; ?>" />
	<div class="row">            
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Nome do site</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nomedosite" value="<?= $nomedosite; ?>" />
        </div>    		
		
        
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>URL do site</strong></label>
			<input type="text" class="form-control input-lg" disabled name="Domain" value="<?= BASE; ?>" />
        </div>
	</div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label for="exampleInputEmail1"><strong>Meta Tags</strong></label>                                    
            <input id="tags_1" type="text" class="tags" name="tagsdosite" value="<?= $tagsdosite; ?>" />                      
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label for="exampleInputEmail1"><strong>Meta Imagem: 800X418 pixels</strong></label>                                    
            <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
              <?php 
                if($metaimg == ''){
                  echo '<img src="images/image.jpg">';  
                }else{
                  echo '<a href="../uploads/'.$metaimg.'" title="'.$nomedosite.'" rel="ShadowBox">';  
                  echo '<img src="../uploads/'.$metaimg.'" style="height: 200px;"/>';
                  echo '</a>';   
                }                     
    		  ?>                                        
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
            <div>
                   <span class="btn btn-default btn-file">
                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                   <input type="file" name="metaimg" class="default" value="<?= $metaimg; ?>" />
                   </span>
                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>                
            </div>
        </div>                      
        </div>
    </div>
    
    <div class="row">
    	<div class="col-md-12 form-group">
            <label for="exampleInputEmail1"><strong>Descrição do site</strong></label>
    		<textarea class="form-control input-lg"  rows="5" name="descricaodosite"><?= $descricaodosite; ?></textarea>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-5 form-group">
            <label for="exampleInputEmail1"><strong>Rua</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="rua" value="<?= $rua;?>" />
        </div>
        <div class="col-md-2 form-group">
            <label for="exampleInputEmail1"><strong>UF</strong></label>
            <select name="uf" class="form-control input-lg j_loadstate">
                    <option value="" selected> Selecione o estado </option>
                    <?php
                    $readState = new Read;
                    $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                    foreach ($readState->getResult() as $estado):
                        extract($estado);
                        echo "<option value=\"{$estado_id}\" ";
                        if (isset($uf) && $uf == $estado_id): echo 'selected';
                        endif;
                        echo "> {$estado_uf} </option>";
                    endforeach;
                    ?>
            </select>
        </div>
        <div class="col-md-5 form-group">
            <label for="exampleInputEmail1"><strong>Cidade</strong></label>
            <select class="form-control input-lg j_loadcity" name="cidade">
                <?php if (!isset($cidade)): ?>
                    <option value="" selected disabled> Selecione antes um estado </option>
                    <?php
                else:
                    $City = new Read;
                    $City->ExeRead("cidades", "WHERE estado_id = :uf ORDER BY cidade_nome ASC", "uf={$uf}");
                    if ($City->getRowCount()):
                        foreach ($City->getResult() as $cidade1):
                            extract($cidade1);
                            echo "<option value=\"{$cidade_id}\" ";
                            if (isset($cidade) && $cidade == $cidade_id):
                                echo "selected";
                            endif;
                            echo "> {$cidade_nome} </option>";
                        endforeach;
                    endif;
                endif;
                ?>
            </select>
        </div>      
                                
    </div> 
    
    <div class="row"> 
        <div class="col-md-5 form-group">
            <label for="exampleInputEmail1"><strong>Bairro</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="bairro" value="<?= $bairro;?>" />
        </div>
        <div class="col-md-2 form-group">
            <label for="exampleInputEmail1"><strong>Número</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="numero" value="<?= $numero;?>" />
        </div>
        <div class="col-md-2 form-group">
            <label for="exampleInputEmail1"><strong>Cep</strong></label>
            <input type="text" class="form-control input-lg" data-mask="99.999-999" id="exampleInputEmail1" name="cep" value="<?= $cep;?>" />
        </div>
                
        <div class="col-md-3 form-group">
            <label for="exampleInputEmail1"><strong>Complemento</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="complemento" value="<?= $complemento;?>" />
        </div>
        
        </div>
        
        <div class="row"> 
            <div class="col-md-5 form-group">
                <label for="exampleInputEmail1"><strong>Latitude</strong></label>
                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="latitude" value="<?= $latitude;?>" />
            </div>
            <div class="col-md-2 form-group">
                <label for="exampleInputEmail1"><strong>Longitude</strong></label>
                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="longitude" value="<?= $longitude;?>" />
            </div>
            <div class="col-md-5 form-group">
                <label for="exampleInputEmail1"><strong>Ano de ínicio</strong></label>
                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="AnoDeInicio" value="<?= $AnoDeInicio;?>" />
            </div>                        
        </div>
        
        <div class="row"> 
            <div class="col-md-4 form-group">
                <label for="exampleInputEmail1"><strong>CPF/CNPJ</strong></label>
                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="cpf_cnpj" value="<?= $cpf_cnpj;?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="exampleInputEmail1"><strong>Inscrição Estadual</strong></label>
                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="ie" value="<?= $ie;?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="exampleInputEmail1">&nbsp;</label>
                <button name="InfoGerais" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Atualizar Agora">Atualizar Agora</button>
            </div>                        
        </div>
        
                 
    </form>

</div>
<!---------------------------- FIM ABA CONFIGURAÇÕES GERAIS ----------------------->

<!---------------------------- ABA REDES SOCIAIS ----------------------------->
<div class="tab-pane" id="tab_2">
    <h3>Redes Sociais</h3>
    <?= $userlogin['nome'];?> aqui você pode configurar os links das redes sociais do site <b><?= $nomedosite; ?></b>.
    <hr />    
    <form name="InfoRedesSociais" action="" method="post">
    <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Facebook</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
                <input type="text" class="form-control input-lg" name="facebook" value="<?= $facebook; ?>"/>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Twitter</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                <input type="text" class="form-control input-lg" name="twitter" value="<?= $twitter; ?>"/>
            </div>
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Youtube</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-youtube"></i></span>
                <input type="text" class="form-control input-lg" name="youtube" value="<?= $youtube; ?>"/>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Flickr</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-flickr"></i></span>
                <input type="text" class="form-control input-lg" name="fliccr" value="<?= $fliccr; ?>"/>
            </div>
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Instagram</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
                <input type="text" class="form-control input-lg" name="instagran" value="<?= $instagran; ?>"/>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Vimeo</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-vimeo-square"></i></span>
                <input type="text" class="form-control input-lg" name="vimeo" value="<?= $vimeo; ?>"/>
            </div>
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Google+</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-google-plus"></i></span>
                <input type="text" class="form-control input-lg" name="google" value="<?= $google; ?>"/>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>LinkedIn</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-linkedin"></i></span>
                <input type="text" class="form-control input-lg" name="linkedin" value="<?= $linkedin; ?>"/>
            </div>
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Sound Cloud</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"></span>
                <input type="text" class="form-control input-lg" name="soundcloud" value="<?= $soundcloud; ?>"/>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>SnapChat</strong></label>
            <div class="input-group m-bot15">
                <span class="input-group-addon"><i class="fa fa-snapchat"></i></span>
                <input type="text" class="form-control input-lg" name="snapchat" value="<?= $snapchat; ?>"/>
            </div>
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            
        </div>
        <div class="col-md-6 form-group">
        <button name="InfoRedesSociais" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Atualizar Agora">Atualizar Agora</button>    
        </div>                        
    </div>
    
    
</form>
</div>
<!---------------------------- FIM ABA REDES SOCIAIS ----------------------------->

<!---------------------------- ABA BANCO DE DADOS ----------------------------->
<div class="tab-pane" id="tab_3">
    <h3>Banco de dados</h3>
    <?= $userlogin['nome'];?> aqui você pode visualizar as informações do banco de dados do site <b><?= $nomedosite; ?></b>.
    <hr />    
    <form name="InfoDB" action="" method="post">
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Host</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" value="<?php echo HOST; ?>" disabled />
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Banco de Dados</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" value="<?php echo DBSA; ?>" disabled />
        </div>                        
    </div>
    
    <div class="row"> 
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Usuário</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" value="<?php echo USER; ?>" disabled />
        </div>
        <div class="col-md-6 form-group">
            <label for="exampleInputEmail1"><strong>Senha</strong></label>
            <input type="text" class="form-control input-lg" id="exampleInputEmail1" value="<?php echo PASS; ?>" disabled />
        </div>                        
    </div>    
   
    </form>
</div>
<!----------------------------- FIM ABA BANCO DE DADOS -------------------->

<!---------------------------- ABA INFORMAÇÕES DE CONTATO ----------------->
<div class="tab-pane" id="tab_4">
<h3>Informações de Contato</h3>
<?= $userlogin['nome'];?> aqui você pode configurar as informações de contato do site <b><?= $nomedosite; ?></b>.
<hr />
<form name="InfoContato" action="" method="post">
<input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
<?php if($userlogin['nivel'] == '1'): ?><!-- Se for administrador mostra os campos -->
<div class="row"> 
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>E-mail SMTP</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="SMTPUsername" value="<?= $SMTPUsername; ?>"/>
    </div>
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>Senha</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="SMTPPassword" value="<?= $SMTPPassword; ?>"/>
    </div>                        
</div>

<div class="row"> 
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>Porta SMTP</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="SMTPPort" value="<?= $SMTPPort; ?>"/>
    </div>
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>Servidor SMTP</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="SMTPHost" value="<?= $SMTPHost; ?>"/>
    </div>                        
</div>
<?php endif;?>

<div class="row"> 
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Telefone 1</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="tel1" value="<?= $tel1; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Telefone 2</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="tel2" value="<?= $tel2; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Telefone 3</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="tel3" value="<?= $tel3; ?>"/>
    </div>                        
</div>
<div class="row"> 
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Skype</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="skype" value="<?= $skype; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>Nextel</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nextel" value="<?= $nextel; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>WhatsApp</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="watsapp" data-mask="(99) 99999-9999" value="<?= $watsapp; ?>"/>
    </div>                        
</div>
<div class="row"> 
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>E-mail</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="email" value="<?= $email; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>E-mail 1</strong></label>
        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="email1" value="<?= $email1; ?>"/>
    </div>
    <div class="col-md-4 form-group">
        <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
        <button name="InfoContato" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Atualizar Agora">Atualizar Agora</button>
    </div>                        
</div>
   
</form>                        
</div>
<!---------------------------- FIM ABA INFORMAÇÕES DE CONTATO ----------------------------->

<!--------------------------- ABA MAPAS -------------------------------------->
<div class="tab-pane" id="tab_5">
<h3>Mapa do Google</h3>
<?= $userlogin['nome'];?> aqui você configurar o mapa do google para o site <b><?= $nomedosite; ?></b> <span class="label label-warning">edite com cuidado!</span>.
<hr />
<form name="MapaGoogle" action="" method="post">
<input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />    
<div class="panel panel-primary">
    <div class="panel-body">
        <div class="row" style="text-align: center;">
        <iframe src="<?= $mapadogoogle; ?>" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>    
        </div>
    </div>    
</div>                    

<div class="row">
	<div class="col-sm-12">
        <h4>Copie e cole somente o caminho do mapa como no exemplo abaixo:</h4>
        <p>iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"<br /> src="<span style="color:#FF0000;">http://maps.google.com.br/maps?q=padang+itamambuca&hl=pt&cd=5&ei=WKRATO_oD5qYyASLp8G8BA&sig2=<br />w3RAiseOdsO7C12FC844Wg&sll=-23.40367,-45.013046&sspn=3.598987,4.938354&ie=UTF8<br />&view=map&cid=3882811409949230167&ved=0CBgQpQY&hq=padang+itamambuca&hnear=&ll=<br />-23.391737,-45.009162&spn=0.006893,0.00912&z=16&iwloc=A&output=embed</span>">iframe</p>
		<textarea class="form-control" rows="5" name="mapadogoogle"><?= $mapadogoogle; ?></textarea>
    </div>
</div>

	<div class="row">		
		<div class="col-sm-4">            
		</div>        
        <div class="col-sm-4">           
		</div>        
        <div class="col-sm-4">
         <label style="margin-top:10px;" for="exampleInputEmail1">&nbsp;</label>
        <button name="MapaGoogle" type="submit" style="width: 100%;" class="btn btn-success input-lg" value="Atualizar">Atualizar</button>   
		</div>        
	</div>      
    
    </form>                        
    </div>
<!---------------------------- FIM ABA MAPAS --------------------------------->

<!--------------------------- ABA IMAGENS ----------------------------------->
<div class="tab-pane" id="tab_6">
<h3>Imagens do site</h3>
<?= $userlogin['nome'];?> aqui você configurar as imagens do site <b><?= $nomedosite; ?></b> <span class="label label-warning">Edite com cuidado!</span>.
<hr />     

<div class="row">
<form name="submitxmllogomarca" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
<input type="hidden" name="logomarca_width" value="<?= $logomarca_width; ?>" />
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>Logomarca do site</strong></label>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
              <?php 
                if($logomarca == ''){
                  echo '<img src="images/image.jpg">';  
                }else{
                  echo '<a href="../uploads/'.$logomarca.'" title="'.$nomedosite.'" rel="ShadowBox">';  
                  echo '<img src="../uploads/'.$logomarca.'"/>'; 
                  echo '</a>';                   
                }                     
    		  ?>                                        
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
            <div>
                   <span class="btn btn-default btn-file">
                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                   <input type="file" name="logomarca" class="default" value="<?= $logomarca; ?>" />
                   </span>
                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                <button type="submit" class="btn btn-success" name="submitxmllogomarca" value="Atualizar">Atualizar</button>
            </div>
        </div>
    </div>	
</form>

<form name="submitxmllogoadmin" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
<input type="hidden" name="logomarcaadmin_width" value="<?= $logomarcaadmin_width; ?>" />
    <div class="col-md-6 form-group">
        <label for="exampleInputEmail1"><strong>Logomarca do Gerenciador</strong></label>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
              <?php 
                if($logomarcaadmin == ''){
                  echo '<img src="imgages/image.jpg">';  
                }else{
                  echo '<a href="../uploads/'.$logomarcaadmin.'" title="'.$nomedosite.'" rel="ShadowBox">';  
                  echo '<img src="../uploads/'.$logomarcaadmin.'"/>'; 
                  echo '</a>';                   
                }                     
			  ?>                                        
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
            <div>
                   <span class="btn btn-default btn-file">
                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                   <input type="file" name="logomarcaadmin" class="default" value="<?= $logomarcaadmin; ?>" />
                   </span>
                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                <button type="submit" class="btn btn-success" name="submitxmllogoadmin" value="Atualizar">Atualizar</button>
            </div>
        </div>
    </div>	
</form>
</div>    
    
    
<div class="row">
    <div class="col-md-6 form-group">
        <form  name="submitxmlfavicon" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
        <input type="hidden" name="favicon_width" value="<?= $favicon_width; ?>" />
        <label><strong>Favicon (32X32)</strong></label>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
              <?php 
                if($favicon == ''){
                  echo '<img src="images/image.jpg">';  
                }else{
                  echo '<a href="../uploads/'.$favicon.'" title="'.$nomedosite.'" rel="ShadowBox">';  
                  echo '<img src="../uploads/'.$favicon.'"/>'; 
                  echo '</a>';
                }                     
			  ?>                                        
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
            <div>
                   <span class="btn btn-default btn-file">
                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                   <input type="file" name="favicon" class="default" value="<?= $favicon; ?>" />
                   </span>
                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                <button type="submit" class="btn btn-success" name="submitxmlfavicon" value="Atualizar">Atualizar</button>
            </div>
        </div>
        </form>
    </div>   
    
    <div class="col-md-6 form-group">
        <form  name="submitxmlmarcadagua" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
        <input type="hidden" name="marca_dagua_width" value="<?= $marca_dagua_width; ?>" />
        <label><strong>Marca D`agua (186X90)</strong></label>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
              <?php 
                if($marca_dagua == ''){
                  echo '<img src="images/image.jpg">';  
                }else{
                  echo '<a href="../uploads/'.$marca_dagua.'" title="'.$nomedosite.'" rel="ShadowBox">';  
                  echo '<img src="../uploads/'.$marca_dagua.'"/>'; 
                  echo '</a>';
                }                     
			  ?>                                        
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
            <div>
                   <span class="btn btn-default btn-file">
                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                   <input type="file" name="marca_dagua" class="default" value="<?= $marca_dagua; ?>" />
                   </span>
                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                <button type="submit" class="btn btn-success" name="submitxmlmarcadagua" value="Atualizar">Atualizar</button>
            </div>
        </div>
        </form>
    </div>
</div> 

<div class="row">
    <form  name="submitxmlImgTopo" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
    <input type="hidden" name="imgtopo_width" value="<?= $imgtopo_width; ?>" />    
    <div class="col-md-12 form-group">
        <label><strong>Topo do site: (1946X300)</strong></label>                                    
        <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-new thumbnail" style="width: 100%;height: 200px;">
          <?php 
            if($imgtopo == ''){
              echo '<img src="images/image.jpg">';  
            }else{
              echo '<a href="../uploads/'.$imgtopo.'" title="'.$nomedosite.'" rel="ShadowBox">';  
              echo '<img src="../uploads/'.$imgtopo.'" style="height: 200px;"/>';
              echo '</a>';   
            }                     
              ?>                                        
        </div>
        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; line-height: 20px;"></div>
        <div>
               <span class="btn btn-default btn-file">
               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
               <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
               <input type="file" name="imgtopo" class="default" value="<?= $imgtopo; ?>" />
               </span>
            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>                
            <button type="submit" class="btn btn-success" name="submitxmlImgTopo" value="Atualizar">Atualizar</button>
        </div>
    </div>                      
    </div>
    </form>
</div>

</div>
<!----------------------------- FIM ABA IMAGENS ------------------------------------>

<!--------------------------- ABA TEMAS -------------------------------------->
<div class="tab-pane" id="tab_7">
<h3>Temas</h3>
<?= $userlogin['nome'];?> aqui você pode configurar os temas do site <b><?= $nomedosite; ?></b>.
<hr />    
<div class="box-body">                
<form  name="submitthema" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
<table class="table icheck">
<thead>
	  <tr>
        <th>Tema</th>
        <th>Miniatura</th>
        <th>Descrição</th>
      </tr>
</thead>
    <tbody>
<?php    
    $baseThema = '../template/';
    $abreThema = (isset($_GET['dir']) != '' ? $_GET['dir'] : $baseThema);
    $openDir = dir($abreThema);
    
        while($arq = $openDir -> read()):
        
           if($arq != '.' && $arq != '..'):
              
              if(is_dir($abreThema.$arq)){
                //require('../template/'.$arq.'/thema.php');
                $data=simplexml_load_file('../template/'.$arq.'/thema.xml');
                $versao          = $data->item->versao;
                $autor           = $data->item->autor;
                $datacriacao     = $data->item->datacriacao;
                $dataatualizacao = $data->item->dataatualizacao;
                $nomedothema     = $data->item->nomedothema;
                echo '<tr>';
                  echo '<td style="vertical-align: middle !important;">';                    
                    ?>                    
                    <div class="flat-green single-row" style="float: left;">
                        <div class="radio " style="float: left;">
                            <input tabindex="3" type="radio" id="<?php echo ''.$arq.'';?>"  name="template" value="<?php echo ''.$arq.'';?>" <?php if($template == $arq) echo 'checked="checked"';?>/>
                            <label style="float: right;"><?php echo $arq;?> </label>
                        </div>
                    </div>                   
                    <?php 
                  echo '<td style="vertical-align: middle !important;">';                                        
                  echo '<img src="'.BASE.'/template/'.$arq.'/miniatura-do-thema.jpg"/></td>';               
                  echo '</td>';
                  echo '<td style="vertical-align: middle !important;"> 
                         <b>Nome do Tema:</b>  '.$nomedothema.'<br/>
                         <b>Autor:</b>  '.$autor.'<br/>
                         <b>Versão:</b> '.$versao.'<br/>
                         <b>Data de criação:</b>  '.$datacriacao.'<br/>
                         <b>Data de Atuaizao:</b>  '.$dataatualizacao.'<br/>                  
                       </td>';                  
                echo '</tr>';                                
                
              }else{
                echo 'Nenhum Thema foi encontrado!';
              }
             
           endif;          
        
        endwhile;
    
    $openDir->close();
?>               
    </tbody>
</table>             
</div><!-- /.box-body -->
 <div class="box-footer">
    <button type="submit" class="btn btn-success" name="submitthema">Atualizar Thema</button>
</div>
</form>                        
                        </div>
<!-------------------------- FIM ABA TEMAS ----------------------------->

<!--------------------------- ABA SEO ------------------------------------->
<div class="tab-pane" id="tab_8">
    <h3>Configurações SEO</h3>
    <p><?= $userlogin['nome'];?> aqui você pode configurar a otimização do site nas Buscas</p>
    <hr />

    <form name="submitseo" action="" method="post">
    <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
    <div class="row">
    	<div class="col-sm-4 form-group">
         <p style="text-align: right;"><strong>SiteMaps última atualização</strong> <span class="label label-warning"><?= date('d/m/Y', strtotime($sitemapdata)); ?></span></p> 
        </div>
        <div class="col-sm-4">
          <button name="submitseo" style="width: 100%;" type="submit" class="btn btn-success" value="Atualizar Sitemaps">Atualizar Sitemaps</button>   
        </div>
        <div class="col-sm-4">
            
        </div>
    </div>
    <hr />
    </form>
    
    <div class="row">
        <form name="submitrss" action="" method="post">
            <input type="hidden" name="nomedosite" value="<?= $nomedosite; ?>" />
            <div class="col-sm-4 form-group">
             <p style="text-align: right;"><strong>RSS última atualização</strong> <span class="label label-warning"><?= date('d/m/Y', strtotime($rssdata)); ?></span></p> 
            </div>
            <div class="col-sm-4">
              <button name="submitrss" style="width: 100%;" type="submit" class="btn btn-success" value="Atualizar RSS">Atualizar RSS</button>   
            </div>
            <div class="col-sm-4">

            </div>
        </form>
    </div>    
    
</div>
<!--------------------------- FIM ABA SEO --------------------------------->

<!---------------------------- ABA SUPORTE ------------------------------------->
<div class="tab-pane" id="tab_9">
<h3>Suporte ao Cliente</h3>
<p>Digite sua solicitação de suporte ou dúvida no campo abaixo. Iremos atender sua solicitação o mais breve possível.</p>
<hr />
<form name="formulario" action="" method="post">    
<div class="row">
    <div class="col-sm-12">
        <input type="hidden" name="RemetenteNome" value="<?= $nomedosite; ?>"/>
        <input type="hidden" name="RemetenteEmail" value="<?php echo MAILUSER; ?>"/>
        <textarea class="form-control input-lg" rows="5" name="Mensagem"></textarea>
    </div>
</div>   

<div class="row">    		
    <div class="col-sm-4"></div>            
    <div class="col-sm-4"></div>            
    <div class="col-sm-4">
    <label>&nbsp;</label>
    <button name="submitsuporte" type="submit" style="width: 100%;" class="btn btn-success input-lg" value="Enviar Mensagem">Enviar Mensagem</button>   
    </div>            
</div>    
</form>                        
</div>
<!---FIM ABA SUPORTE ----->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>