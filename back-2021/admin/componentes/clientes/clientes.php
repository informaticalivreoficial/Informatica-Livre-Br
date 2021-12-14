<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'apage', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&apage='.$getPage.''; else: $varPage = ''; endif;
?>
<div class="page-heading">
    <div class="row">
        <div class="col-sm-6">
            <h3>Gerenciar Clientes</h3>
        </div>
        <div class="col-sm-6">
            <a href="painel.php?exe=clientes/clientes-create" title="Cadastrar Cliente" class="btn btn-default btn-lg" style="float:right;">Cadastrar Cliente</a>
        </div>
    </div>
</div>

<!--body wrapper start-->
<div class="wrapper">
<?php
    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if ($empty):
        RMErro("Oppsss: Você tentou editar um Cliente que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminClientes.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminClientes;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O Cliente foi excluído com sucesso no sistema!", RM_ACCEPT);
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=clientes/clientes&page=');
    $Pager->ExePager($getPage, 16);
    
    $readUsuarios = new Read;
    $readUsuarios->ExeRead("clientes","WHERE id  ORDER BY data DESC, status ASC  LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readUsuarios->getResult()):		    
?>
    <ul class="directory-list">
        <?php
        foreach(range( 'A', 'Z' ) as $letra):
            echo '<li><a class="j_alfabeto" data-id="'.$letra.'" href="#">'.$letra.'</a></li>';
        endforeach;        
        ?>        
    </ul>
    
    <div class="directory-info-row">
        <div class="resultado"></div>
        <div class="row hideR">
            <?php
            foreach($readUsuarios->getResult() as $usuario):
            extract($usuario);
            
            $status = ($status == '0' ? '<span class="label label-warning">Inativo</span>' : '<span class="label label-success">Ativo</span>');
            $views = (!$visitas ? '0' : $visitas);
            ?>
            <div class="col-md-6 col-sm-6">
                <div class="panel">
                    <div class="panel-body" style="min-height:290px;">
                        <h4><?= $nome;?> <span class="text-muted small"> - <?= $status;?>
                            <a style="color: #333 !important;" class="btn btn-default btn-xs" title="Editar" href="painel.php?exe=clientes/clientes-edit&clienteId=<?= $id;?>"> <i class="fa fa-pencil"></i> </a>
                            <a style="color: #fff !important;" title="Visualizar" href="painel.php?exe=clientes/cliente-perfil&clienteId=<?= $id;?>" class="btn btn-info btn-xs"> <i class="fa fa-search"></i> </a>
                            <a style="color: #fff !important;" class="btn btn-danger btn-xs" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>"> <i class="fa fa-times"></i> </a>
                            </span></h4>
                        <div class="media">
                            <a class="pull-left" href="../uploads/<?= $logomarca;?>" title="<?= $nome;?>" rel="ShadowBox">
                                <?php if($logomarca == null): ?>
                                    <img class="thumb media-object" src="<?= BASE;?>/tim.php?src=<?= BASE;?>/admin/images/image.jpg&w=103&h=103&zc=1&q=100"/>
                                <?php else: ?>                                    
                                    <?= Check::Image('../uploads/' . $logomarca, $nome, 103, 103); ?>                                    
                                <?php endif; ?>
                            </a>
                            <div class="media-body">
                                <address>
                                    <strong>Criado:</strong> <?= date('d/m/Y', strtotime($data));?><br>
                                    <strong>Faturas:</strong> <br>                                    
                                    <?php if($email): echo '<strong>E-mail:</strong> '.$email.' <a title="Enviar E-mail" href="painel.php?exe=email/envia&email='.$email.'"> <i class="fa fa-mail-forward"></i> </a><br>'; endif;?>
                                    <?php if($whatsapp): echo '<strong>WhatsApp:</strong> '.$whatsapp.' <a target="_blank" title="Enviar Mensagem" href="'.Check::getNumZap($whatsapp, Check::getSaudacao('Olá')).'"> <i class="fa fa-mail-forward"></i> </a>'; endif;?>                                    
                                </address>
                                <ul class="social-links">
                                    <?php
                                    if($facebook):
                                        echo '<li><a title="Facebook" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$facebook.'" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li> ';
                                    endif;
                                    if($twitter):
                                        echo '<li><a title="Twitter" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$twitter.'" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li> ';
                                    endif;
                                    if($instagram):
                                        echo '<li><a title="Instagram" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$instagram.'" data-original-title="Instagram"><i class="fa fa-instagram"></i></a></li> ';
                                    endif;
                                    if($linkedin):
                                        echo '<li><a title="LinkedIn" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$linkedin.'" data-original-title="LinkedIn"><i class="fa fa-linkedin"></i></a></li> ';
                                    endif;
                                    if($skype):
                                        echo '<li><a title="Skype" target="_blank" data-placement="top" data-toggle="tooltip" class="tooltips" href="'.$skype.'" data-original-title="Skype"><i class="fa fa-skype"></i></a></li> ';
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- MODAL DE EXCLUIR ATLETA -->
            <div class="modal fade" id="1<?= $id;?>">
                <div class="modal-dialog">
                    <div class="modal-content">				
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><strong>Alerta!</strong></h4>
                        </div>              
                        <div class="modal-body">
                            <blockquote class="blockquote-red">			
                                <p>
                                    <small>Você tem certeza que deseja excluir este Cliente?<br />
                                    <strong><?= $nome;?></strong></small>
                                </p>
                            </blockquote>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <a href="painel.php?exe=clientes/clientes&post=<?= $id;?>&action=delete">
                            <button type="button" class="btn btn-info">Confirmar</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            endforeach;
            ?>
        </div>
        
    </div>

    
<div class="row">
<div class="col-sm-12">  
    <section class="panel">
        
    <?php
        else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem Clientes cadastrados!", RM_INFOR);  
        endif;

    // Chama o Paginator    
    $Pager->ExePaginator("clientes","WHERE id  ORDER BY status ASC, data DESC",'admin');
    ?>
    
    <div class="row-fluid">
        <div class="span6">
            <?php
                $readPostsCount = new Read;
                $readPostsCount->ExeRead("clientes","WHERE id ORDER BY status ASC, data DESC");
                    if($readPostsCount->getResult()):              
             ?> 
             <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("clientes");?> de <?= $readPostsCount->getRowCount();?> Cliente(s)</div>
             <?php     
                endif;
              ?>

        </div>
        <div class="span6">
            <div class="dataTables_paginate paging_bootstrap pagination">
    <?= $Pager->getPaginator('admin');?>            
            </div>
        </div>
    </div>

    </section>
</div>
</div>
    
</div>
<!--body wrapper end-->