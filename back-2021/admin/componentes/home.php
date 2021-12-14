
<?php
	//OBJETO READ
    $read = new Read;
    $Hoje = date('Y-m-d');
    $AnoAtual = date('Y');
    
    //VISITAS DO SITE TOTAL
    $read->FullRead("SELECT SUM(views) AS views FROM siteviews WHERE YEAR(data) = '$AnoAtual'");
    $ViewsTotal = $read->getResult()[0]['views'];
    
    //VISITAS DO SITE TOTAL HOJE
    $read->FullRead("SELECT SUM(views) AS views FROM siteviews WHERE data = '$Hoje'");
    $ViewsTotalH = $read->getResult()[0]['views'];
    
    //VISITAS ÚNICAS HOJE
    $read->FullRead("SELECT SUM(usuarios) AS usuarios FROM siteviews WHERE data = '$Hoje'");
    $ViewsUnH = $read->getResult()[0]['usuarios'];
    
    //VISITAS ÚNICAS HOJE
    $read->FullRead("SELECT SUM(usuarios) AS usuarios FROM siteviews WHERE YEAR(data) = '$AnoAtual'");
    $ViewsUn = $read->getResult()[0]['usuarios'];

    //MÉDIA DE PAGEVIEWS
    //$read->FullRead("SELECT SUM(siteviews_pages) AS pages FROM ws_siteviews");
    //$ResPages = $read->getResult()[0]['pages'];
    //$Pages = substr($ResPages / $Users, 0, 5);

    //PÁGINAS
    $read->ExeRead("posts","WHERE tipo = 'pagina'");
    $PostsPaginas = ($read->getRowCount() ? $read->getRowCount() : '0'); 
    
    //ARTIGOS
    $read->ExeRead("posts","WHERE tipo = 'artigo'");
    $PostsArtigos = ($read->getRowCount() ? $read->getRowCount() : '0');
    
    //NOTÍCIAS
    //$read->ExeRead("posts","WHERE tipo = 'noticia'");
    //$PostsNoticias = ($read->getRowCount() ? $read->getRowCount() : '0');
    
    //CLIENTES
    $read->ExeRead("clientes");
    $PostsClientes = ($read->getRowCount() ? $read->getRowCount() : '0');  
    
    //E-mails
    $read->ExeRead("newsletter");
    $PostsNwesletter = ($read->getRowCount() ? $read->getRowCount() : '0');    
    
    //CLIENTES
    $read->ExeRead("usuario","WHERE tipo = '2'");
    $ClientesCount = $read->getRowCount();     
?>
 <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-6">
                    <!--statistics start-->
                    <div class="row state-overview">
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="panel purple">
                                <div class="symbol">
                                    <a style="color:#ffffff;" href="painel.php?exe=paginas/paginas"><i class="fa fa-file-text"></i></a>
                                </div>
                                <div class="state-value">
                                    <div class="value"><?= $PostsPaginas;?></div>
                                    <div class="title">Página(s)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="panel green">
                                <div class="symbol">
                                    <a style="color:#ffffff;" href="painel.php?exe=artigos/artigos"><i class="fa fa-pencil-square-o"></i></a>
                                </div>
                                <div class="state-value">
                                    <div class="value"><?= $PostsArtigos;?></div>
                                    <div class="title">Artigo(s)</div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <!--statistics end-->
                </div>
                <div class="col-md-6">
                    <!--statistics start-->
                    <div class="row state-overview">
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="panel blue">
                                <div class="symbol">
                                    <a style="color:#ffffff;" href="painel.php?exe=clientes/clientes"><i class="fa fa-users"></i></a>
                                </div>
                                <div class="state-value">
                                    <div class="value"><?= $PostsClientes;?></div>
                                    <div class="title"> Clientes</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="panel verde">
                                <div class="symbol">
                                    <a style="color:#ffffff;" href="#"><i class="fa fa-suitcase"></i></a>
                                </div>
                                <div class="state-value">
                                    <div class="value"></div>
                                    <div class="title"></div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <!--statistics end-->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <!-- GRÁFICO DE VISITAS -->
                    <div class="panel">
                        <header class="panel-heading">
                            Visitas
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                        </header>
                        <div class="panel-body">
                            <div id="visitantes"></div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-4">
                    <div class="row state-overview">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="panel orange">
                            <div class="symbol">
                                <a style="color:#ffffff;" href="#"><i class="fa fa-envelope-o"></i></a>
                            </div>
                            <div class="state-value">
                                <div class="value"><?= $PostsNwesletter;?></div>
                                <div class="title">E-mail(s)</div>
                            </div>
                        </div> 
                        </div> 
                    </div>
                
                    <div class="panel">
                        <div class="panel-body extra-pad">
                            <div class="col-sm-6 col-xs-6">
                                <div class="v-title">Visitas/<?= $AnoAtual;?></div>
                                <div class="v-value"><?= $ViewsTotal;?></div>
                                <div id="visit-1"><?= $ViewsTotalH;?></div>
                                <div class="v-info">Hoje</div>                                
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <div class="v-title">Visitas Únicas/<?= $AnoAtual;?></div>
                                <div class="v-value"><?= $ViewsUn;?></div>
                                <div id="visit-2"><?= $ViewsUnH;?></div>
                                <div class="v-info">Hoje</div>                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="clear"></div>
                    
                    
                    <div class="panel">                        
                        <div class="panel-body">                            
                                <?php
                                $read->ExeRead("siteviews_agent", "ORDER BY views DESC LIMIT 3");
                                if (!$read->getResult()):
                                    RMErro("Oppsss, Ainda não existem estatísticas de navegadores!", RM_INFOR);
                                else:
                                    echo "<ul class=\"goal-progress\">";
                                    foreach ($read->getResult() as $nav):
                                        //LE O TOTAL DE VISITAS DOS NAVEGADORES
                                        $read->FullRead("SELECT SUM(views) AS TotalViews FROM siteviews_agent WHERE YEAR(lastview) = '$AnoAtual'");
                                        $TotalViews = $read->getResult()[0]['TotalViews'];
                                        extract($nav);
                    
                                        //REALIZA PORCENTAGEM DE VISITAS POR NAVEGADOR!
                                        $percent = substr(( $views / $TotalViews ) * 100, 0, 5);
                                        $percenttag = str_replace(",", ".", $percent);
                                        ?>
                                        <li>
                                            <div class="details">
                                                <div class="title">
                                                    <strong><?= $nome; ?> - <?= $views; ?> visitas</strong>
                                                </div>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percenttag; ?>%">
                                                        <span class=""><?= $percenttag; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </li>
                                        <?php
                                    endforeach;
                                    echo "</ul>";
                                endif;
                                ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">                
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Imóveis mais visitados em <?= date('Y');?>
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                        </header>
                        <div class="panel-body">
                            <table class="table  table-hover general-table">
                                <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Visitas</th>
                                    <th></th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $AnoI = date('Y');
                                    $read->ExeRead("imoveis", "WHERE status = '1' AND YEAR(data) = '$AnoI' ORDER BY visitas DESC LIMIT 6");
                                    if (!$read->getResult()):
                                        RMErro("Oppsss, Ainda não existem estatísticas de imóveis!", RM_INFOR);
                                    else:
                                        foreach ($read->getResult() as $re):                                            
                                            $read->FullRead("SELECT SUM(visitas) AS TotalI FROM imoveis WHERE status = '1' AND YEAR(data) = '$AnoI'");
                                            $TotalViewsI = $read->getResult()[0]['TotalI'];
                                            
                                            //REALIZA PORCENTAGEM DE VISITAS POR NAVEGADOR!
                                            $percentI = substr(( $re['visitas'] / $TotalViewsI ) * 100, 0, 5);
                                            $percentItag = str_replace(",", ".", $percentI);                                           
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if($re['img'] == null):
                                                        echo '<img src="images/image.jpg" width="30" height="30" />';
                                                    else:
                                                        echo '<a href="../uploads/'. $re['img'] .'" title="'. $re['nome'] .'" rel="ShadowBox">';
                                                        echo Check::Image('../uploads/' . $re['img'], $re['nome'], 40, 40);
                                                        echo '</a>';
                                                    endif;
                                                    ?>                                                    
                                                </td>
                                                <td><a target="_blank" href="../imoveis/imovel/<?= $re['url']; ?>" title="<?= $re['nome']; ?>"><?= Check::Words($re['nome'], 8); ?></a></td>
                                                <td><?= date('d/m/Y', strtotime($re['data'])); ?></td>                                                
                                                <td>                                                
                                                    <div class="progress progress-striped progress-xs">
                                                        <div style="width: <?= $percentItag; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-info">
                                                            <span class=""><?= $re['visitas']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><a style="color: #333 !important;" class="btn btn-default btn-xs" title="Editar" href="painel.php?exe=imoveis/imoveis-edit&postid=<?= $re['id'];?>"><i class="fa fa-pencil"></i></a></td>
                                            </tr>                                           
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">                
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Páginas mais visitadas
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                        </header>
                        <div class="panel-body">
                            <table class="table  table-hover general-table">
                                <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Visitas</th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $read->ExeRead("posts", "WHERE tipo = 'pagina' ORDER BY visitas DESC LIMIT 4");
                                    if (!$read->getResult()):
                                        RMErro("Oppsss, Ainda não existem estatísticas de páginas!", RM_INFOR);
                                    else:
                                        foreach ($read->getResult() as $re):
                                            $AnoP = date('Y', strtotime($re['data']));
                                            $read->FullRead("SELECT SUM(visitas) AS TotalP FROM posts WHERE tipo = 'pagina' AND YEAR(data) = '$AnoP'");
                                            $TotalViewsP = $read->getResult()[0]['TotalP'];
                                            
                                            //REALIZA PORCENTAGEM DE VISITAS POR NAVEGADOR!
                                            $percentP = substr(( $re['visitas'] / $TotalViewsP ) * 100, 0, 5);
                                            $percentPtag = str_replace(",", ".", $percentP);                                           
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if($re['thumb'] == null):
                                                        echo '<img src="images/image.jpg" width="30" height="30" />';
                                                    else:
                                                        echo Check::Image('../uploads/' . $re['thumb'], $re['titulo'], 30, 30);
                                                    endif;
                                                    ?>                                                    
                                                </td>
                                                <td><a target="_blank" href="../pagina/<?= $re['url']; ?>" title="Ver Página"><?= Check::Words($re['titulo'], 5); ?></a></td>
                                                <td><?= date('d/m/Y', strtotime($re['data'])); ?></td>                                                
                                                <td>                                                
                                                    <div class="progress progress-striped progress-xs">
                                                        <div style="width: <?= $percentPtag; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-info">
                                                            <span class=""><?= $re['visitas']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>                                           
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <section class="panel">
                        <header class="panel-heading">
                            Artigos mais visitados 2020
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                        </header>
                        <div class="panel-body">
                            <table class="table  table-hover general-table">
                                <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Visitas</th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php    
                                    $AnoA = date('Y');                                
                                    $read->ExeRead("posts", "WHERE status = '1' AND tipo = 'artigo' AND YEAR(data) = '$AnoA' ORDER BY visitas DESC LIMIT 4");
                                    if (!$read->getResult()):
                                        RMErro("Oppsss, Ainda não existem estatísticas de artigos!", RM_INFOR);
                                    else:
                                        foreach ($read->getResult() as $reA): 
                                            $read->FullRead("SELECT SUM(visitas) AS TotalA FROM posts WHERE status = '1' AND tipo = 'artigo' AND YEAR(data) = '$AnoA'");
                                            $TotalViewsA = $read->getResult()[0]['TotalA'];
                                            
                                            //REALIZA PORCENTAGEM DE VISITAS POR ARTIGO!
                                            $percentA = substr(( $reA['visitas'] / $TotalViewsA ) * 100, 0, 5);
                                            $percentAtag = str_replace(",", ".", $percentA);                                           
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if($reA['thumb'] == null):
                                                        echo '<img src="images/image.jpg" width="30" height="30" />';
                                                    else:
                                                        echo Check::Image('../uploads/' . $reA['thumb'], $reA['titulo'], 30, 30);
                                                    endif;
                                                    ?>                                                    
                                                </td>
                                                <td><a target="_blank" href="../blog/artigo/<?= $reA['url']; ?>" title="Ver Página"><?= Check::Words($reA['titulo'], 5); ?></a></td>
                                                <td><?= date('d/m/Y', strtotime($reA['data'])); ?></td>                                                
                                                <td>                                                
                                                    <div class="progress progress-striped progress-xs">
                                                        <div style="width: <?= $percentAtag; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-info">
                                                            <span class=""><?= $reA['visitas']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>                                           
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    
                 
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>            
            
        </div>
        <!--body wrapper end-->


    
 