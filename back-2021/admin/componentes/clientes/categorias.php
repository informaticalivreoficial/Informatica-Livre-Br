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
        <h3>Gerenciar Grupo de Clientes</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=clientes/categoria-create<?= $varPage;?>" title="Cadastrar Grupo" class="btn btn-default btn-lg" style="float:right;">Cadastrar Grupo</a>
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
  $empty = filter_input_array(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
  if($empty):
    RMErro("Você tentou editar um grupo que não existe no sistema!", RM_INFOR);
  endif;
  
  $delCat = filter_input(INPUT_GET, 'delcat', FILTER_VALIDATE_INT);
  if($delCat):
    require('./models/AdminCategorias.class.php');
    $excluir = new AdminCategorias; 
    $excluir->ExeDelete('cat_cliente','posts', $delCat);
    RMErro($excluir->getError()[0], $excluir->getError()[1]); 
  endif;
  
  $posti = 0;  
  $Pager = new Pager('painel.php?exe=clientes/categorias&page=');
  $Pager->ExePager($getPage, 10);  
  $readCat = new Read;
  $readCat->ExeRead("cat_cliente","WHERE id_pai IS NULL ORDER BY nome ASC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
  if(!$readCat->getResult()):
      RMErro("Não existe nenhum grupo cadastrado no sistema!", RM_INFOR);
  else:  
  
?>
<table class="display table table-bordered table-striped">
    <thead>
    <tr>
        <th>Grupo</th>
        <th>Criado em</th>
        <th>Tags</th>
        <th>Ações</th>
    </tr>
    </thead>	  
<tbody>
<?php
    foreach($readCat->getResult() as $categoria):
    extract($categoria);
    
    $tagsdosite = ($tags == '' ? 'images/no.png' : 'images/ok.png');
    //$exibirnosite = ($exibir == '1' ? '<span style="color:#339966;">Sim</span>' : '<span style="color:#FF0000;">Não</span>');
?>
<tr>
<td><img src="images/seta.png"/> <strong><?= $nome;?></strong></td>
<td style="text-align:center;"><?= date('d/m/Y', strtotime($data));?></td>
<td style="text-align:center;"><img src="<?= $tagsdosite;?>" alt="<?= $tagsdosite;?>" title="Tags da Categoria" /></td>
<td><a class="btn btn-info btn-sm" href="painel.php?exe=clientes/categoria-edit&catid=<?= $id;?><?= $varPage;?>" title="Editar Grupo <?= $nome;?>"><i class="fa fa-pencil"></i></a>
<a title="Excluir Grupo <?= $nome;?>" class="btn btn-danger btn-sm"  href="javascript:;" data-toggle="modal" data-target="#2<?= $id;?>"><i class="fa fa-times"></i></a></td>
</tr> 

<!-- Modal de exclusão da categoria --> 
<div class="modal fade" id="2<?= $id;?>">
	<div class="modal-dialog">
		<div class="modal-content">				
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><strong>Alerta!</strong></h4>
			</div>              
            <div class="modal-body">				
  			<blockquote class="blockquote-red">				
                <p>
    				<small>Você tem certeza que deseja excluir a Categoria <strong><?= $nome;?></strong>?<br /></small>
    			</p>              
	        </blockquote>
              </div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=artigos/categorias&delcat=<?= $id;?><?= $varPage;?>">
    			<button type="button" class="btn btn-info">Confirmar</button>
                </a>
    		</div>
	 </div>
  </div>
</div> 
<!-- FIM Modal de exclusão da categoria --> 
<?php    
    endforeach;
  endif;
?>		
</tbody>
</table>      

<?php
// Chama o Paginator    
$Pager->ExePaginator("cat_cliente","WHERE id_pai IS NULL ORDER BY nome ASC");       	
?>
<div class="row-fluid">
    <div class="span6">
        <?php
            $readPostsCount = new Read;
            $readPostsCount->ExeRead("cat_cliente","WHERE id_pai IS NULL ORDER BY nome ASC");
	        if($readPostsCount->getResult()):              
         ?> 
         <div class="dataTables_info" id="dynamic-table_info">Exibindo <?= $Pager->getPage();?> de <?= $Pager->getTotal("cat_cliente");?> de <?= $readPostsCount->getRowCount();?> Grupos(s)</div>
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