<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Listas</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=newsletter/listas-create" title="Cadastrar Lista" class="btn btn-default btn-lg" style="float:right;">Cadastrar Lista</a>
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
        RMErro("Oppsss: Você tentou editar uma lista que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminNewsletter.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminNewsletter;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDeleteLista($postAction);
                RMErro($postUpdate->getError()[0], $postUpdate->getError()[1]);
            break;
            
            case 'Rascunho':
                $postUpdate->ExeStatusLista($postAction, '0');
                RMErro("O status da lista foi atualizado para <b>Rascunho</b>. A lista agora é um rascunho!", RM_ALERT);                
            break;
            
            case 'Publicar':
                $postUpdate->ExeStatusLista($postAction, '1');
                RMErro("O status da lista foi atualizado para <b>Publicada</b>. A lista agora está publicada!", RM_ACCEPT);                
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=newsletter/listas&page=');
    $Pager->ExePager($getPage, 10);
    
    $readPosts = new Read;
    $readPosts->ExeRead("newsletter_cat","WHERE id ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readPosts->getResult()):  
    
?>
    
<table class="table  table-hover general-table">
    <thead>
    <tr>
        <th>Lista</th>
        <th>E-mails</th>
        <th>Criada em</th>
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
    ?>
    <tr class="gradeX">
      <td><img src="images/seta.png"/> <strong><?= $titulo;?></strong></td>
      <td><?=  $CountEmails = (Check::getCountRegister('newsletter', 'cat_id', $id) != '' ? Check::getCountRegister('newsletter', 'cat_id', $id) : '0');?></td>
      <td><?= date('d/m/Y',strtotime($data));?></td>
      <td>
        <a class="btn btn-primary btn-sm" href="painel.php?exe=newsletter/export-csv&lista=<?= $id;?>"><i style="color:#fff;" class="fa fa-cloud-download"></i> Exportar csv</a>  
        <a class="btn btn-primary btn-sm" href="painel.php?exe=newsletter/export-xls&lista=<?= $id;?>"><i style="color:#fff;" class="fa fa-cloud-download"></i> Exportar excel</a>  
        <a href="painel.php?exe=newsletter/listas&post=<?= $id;?>&action=<?= $stSta;?><?= $varPage;?>" title="<?= $stSta;?>"><?= $statusPost;?></a>
        <a class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=newsletter/listas-edit&postid=<?= $id;?>"><i class="fa fa-pencil"></i></a>
        <a class="btn btn-info btn-sm" title="Visualizar" href="painel.php?exe=newsletter/newsletter&lista=<?= $id;?>"><i class="fa fa-search"></i></a>
        <a class="btn btn-danger btn-sm" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>"><i class="fa fa-times"></i></a>
      </td>
    </tr>

    <!-- MODAL PARA EXCLUIR LISTA -->
    <div class="modal fade" id="1<?= $id;?>">
       <div class="modal-dialog">
           <div class="modal-content">				
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h4 class="modal-title">Remover Lista!</h4>
               </div>
               <div class="modal-body">
                   <?php                
                   if(Check::getCountRegister('newsletter', 'cat_id', $id) != ''):
                   ?>
                   <p>
                       <small>Você tem certeza que deseja excluir a Lista <strong><?= $titulo;?></strong>?
                       Ela possui <strong><?= Check::getCountRegister('newsletter', 'cat_id', $id);?></strong> E-mails e todos serão excluídos.</small>
                   </p>
                   <?php
                   else:   
                   ?>
                   <p>
                       <small>Você tem certeza que deseja excluir a Lista <strong><?= $titulo;?></strong>?</small>
                   </p>
                   <?php    
                   endif;
                   ?>                
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                   <a href="painel.php?exe=newsletter/listas&post=<?= $id;?>&action=delete">
                   <button type="button" class="btn btn-danger">Excluir</button>
                   </a>
               </div>
           </div>
       </div>
   </div>
    
    <?php endforeach;?>           
</tbody>
</table>
<?php  
    else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem listas cadastradas!", RM_INFOR);  
    endif; 


// Chama o Paginator    
$Pager->ExePaginator("newsletter_cat","WHERE id ORDER BY status ASC, data DESC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("newsletter_cat","WHERE id ORDER BY status ASC, data DESC");
            if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> páginas <?= $Pager->getTotal("newsletter_cat");?> de <?= $readPostsCount->getRowCount();?> Lista(s)</div>
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