<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
    $listaId = filter_input(INPUT_GET, 'lista', FILTER_VALIDATE_INT);
    $readLista = new Read;
    $readLista->ExeRead("newsletter_cat", "WHERE id = :ListaId","ListaId={$listaId}");
    if($readLista->getResult()):
        $lista = $readLista->getResult()['0'];
    endif;
?>
<div id="j_ajaxident" class="painel.php?exe=newsletter/newsletter&lista=<?= $listaId;?>&post="></div>
<div class="page-heading">

<div class="row">
    <div class="col-sm-6">
        <h3><?= $lista['titulo'];?></h3>
    </div>
    <div class="col-sm-6">
        <a class="btn btn-primary btn-lg" href="painel.php?exe=newsletter/listas" style="float:right;margin-left: 10px;"><i class="fa fa-mail-reply"></i> Voltar</a>
        <a href="painel.php?exe=newsletter/newsletter-create&lista=<?= $listaId;?><?= $varPage;?>" title="Cadastrar E-mail" class="btn btn-default btn-lg" style="float:right;margin-left: 10px;">Cadastrar Novo E-mail</a>
    </div>
</div>
</div>

<div class="wrapper">
<div class="row">
<div class="col-sm-12">
<section class="panel">

<div class="panel-body">
<div class="adv-table">
<?php    
    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if ($empty):
        RMErro("Oppsss: Você tentou editar um e-mail que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminNewsletter.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminNewsletter;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatus($postAction, '0');
                RMErro("O status do E-mail foi atualizado para <b>Rascunho</b>. O E-mail agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatus($postAction, '1');
                RMErro("O status do E-mail foi atualizado para <b>Publicado</b>. O E-mail publicada!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=newsletter/newsletter&lista='.$listaId.'&page=');
    $Pager->ExePager($getPage, 50);
    
    $readPosts = new Read;
    $readPosts->ExeRead("newsletter","WHERE cat_id = :ListaId ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","ListaId={$listaId}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
<table class="table  table-hover general-table">
<thead>
  <tr>
   <th>Nome</th>
   <th>E-mail</th>
   <th>Data</th>
   <th>Autorizado</th>
   <th>Ações</th>
  </tr>
</thead>   
						  
<tbody>
<?php
    foreach($readPosts->getResult() as $post):
    extract($post);
    
    $statusPost = (!$status ? '<button type="button" class="btn btn-warning btn-sm"><i class="fa fa-warning"></i></button>' : '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>');
    $stSta = ($status == '0' ? 'Publicar' : 'Rascunho');
    $statusColor = (!$status ? 'style="background: #fffed8"' : '');
    $autorizacao = ($autorizacao == '0' ? '<span class="label label-danger">Não</span>' : '<span class="label label-success">Sim</span>');  
?>
  
<tr class="gradeX">
    <td><?= $nome;?></td>
    <td><?= $email;?></td>

    <td style="text-align:center;"><?= date('d/m/Y',strtotime($data));?></td>
    <td style="text-align:center;"><?= $autorizacao;?></td>
    <td>
        <a href="painel.php?exe=newsletter/newsletter&lista=<?= $listaId;?>&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>
        <a class="btn btn-primary btn-sm" title="Enviar E-mail" href="painel.php?exe=newsletter/envia&lista=<?= $listaId;?>&email=<?= $email;?>"><i style="color:#fff;" class="fa fa-envelope-o"></i></a>
        <a class="btn btn-info btn-sm j_modal_view" title="Visualizar" 
           data-nome="<?= $nome;?>"
           data-cadastrado="<?= utf8_encode(strftime('%A, %d de %B de %Y', strtotime($data)));?>"
            <?php
            if($uppdate != null):
            ?>
           data-atualizado="<?= utf8_encode(strftime('%A, %d de %B de %Y', strtotime($uppdate)));?>"
            <?php endif;?>
           data-lista="<?= Check::Categoria('newsletter_cat', $cat_id, 'titulo');?>"
           <?php
            if($count != null):
            ?>
            data-envios="<?= $count;?>"    
            <?php else: ?> data-envios="0" <?php endif;?>
           data-email="<?= $email;?>" data-id="<?= $id;?>"><i class="fa fa-search"></i></a>    
        <a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=newsletter/newsletter-edit&lista=<?= $listaId;?>&postid=<?= $id;?><?= $varPage;?>"><i class="fa fa-pencil"></i></a>        
        <a class="btn btn-danger btn-sm j_modal_btn" title="Excluir" data-email="<?= $email;?>" data-id="<?= $id;?>" data-option="delete" data-extra=""><i class="fa fa-times"></i></a>       
    </td>
</tr>
   
<?php endforeach;?>

</tbody>
</table>
    
<!-- MODAL PARA VISUALIZAR O E-MAIL -->
<div class="modal fade" id="ModalVisualiza">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cadastro</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">                         
                       
                            <p><strong>Cadastrado:</strong> <span class="j_param_view_cadastrado"></span><br />
                            <strong>Atualizado:</strong> <span class="j_param_view_atualizado"></span><br />                                                        
                            <strong>Nome:</strong> <span class="j_param_view_nome"></span><br />
                            <strong>E-mail:</strong> <span class="j_param_view_email"></span><br />
                            <strong>Lista:</strong> <span class="j_param_view_lista"></span><br />
                            <strong>Envios:</strong> <span class="j_param_view_envios"></span><br />                            
                        </p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
    
<!-- MODAL PARA EXCLUIR E-MAIL -->
<div class="modal fade" id="ModalRemove">
    <div class="modal-dialog">
        <div class="modal-content">				
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Remover E-mail!</h4>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja excluir o email: <strong><span class="j_param_email"></span></strong>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="#" class="delete-yes">
                <button type="button" class="btn btn-danger">Excluir</button>
                </a>
            </div>
        </div>
    </div>
</div> 
    
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem E-mails cadastrados!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("newsletter","WHERE cat_id = :ListaId ORDER BY status ASC, data DESC","ListaId={$listaId}");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("newsletter","WHERE cat_id = :ListaId ORDER BY status ASC, data DESC","ListaId={$listaId}");
            if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("newsletter");?> de <?= $readPostsCount->getRowCount();?> E-mail(s)</div>
         <?php     
            endif;
          ?>
        
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
            <?= $Pager->getPaginator();?>            
        </div>
    </div>
</div>    
    
</div>
</div>
</section>
</div>
</div>
</div>