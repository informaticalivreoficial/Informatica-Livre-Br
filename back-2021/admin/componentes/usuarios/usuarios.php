<?php
	if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Gerenciar Usuários</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=usuarios/usuarios-create" title="Cadastrar Usuário" class="btn btn-default btn-lg" style="float:right;">Cadastrar Usuário</a>
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
        RMErro("Oppsss: Você tentou editar um usuário que não existe no sistema!", RM_INFOR);
    endif;
    
    $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
    if($action):
        require ('models/AdminUser.class.php');
        
        $postAction = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
        $postUpdate = new AdminUser;
            
        switch($action):
            case 'delete':
                $postUpdate->ExeDelete($postAction);
                RMErro("O Usuário foi excluído com sucesso no sistema!", RM_ACCEPT);
            break;
            
            default :
                RMErro("Ação não foi identificada pelo sistema, favor utilize os botões!", RM_ALERT);
        endswitch;
    endif;
    
    $posti = 0;
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Pager = new Pager('painel.php?exe=usuarios/usuarios&page=');
    $Pager->ExePager($getPage, 25);
    
    $readUsuarios = new Read;
    $readUsuarios->ExeRead("usuario","WHERE id != '$userlogin[id]' ORDER BY status ASC, data DESC LIMIT :limit OFFSET :offset","limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if($readUsuarios->getResult()):		    
?>
<table class="table  table-hover general-table">
<thead>
  <tr>
   <th>Imagem:</th>
   <th>Nome</th>
   <th>E-mail</th>
   <th>Nível</th>
   <th>Status:</th>
   <th>Ações</th>
  </tr>
</thead>   
						  
<tbody>	
<?php
	foreach($readUsuarios->getResult() as $usuario):
    extract($usuario);
    
    $nivel = ($nivel == '1' ? 'Administrador' : 
             ($nivel == '2' ? 'Editor' :
             ($nivel == '3' ? 'Leitor' : 
             ($nivel == '4' ? 'Público' : 'Público'))));
    
    $status = ($status == '0' ? '<span class="label label-warning">Inativo</span>' : '<span class="label label-success">Ativo</span>');
?>
<tr>

<?php if($avatar == null): ?>
    <td style="text-align:center;"><img src="tim.php?src=admin/images/avatar.jpg&w=60&h=60&zc=1&q=100"/></td>
<?php else: ?>
    <td style="text-align:center;"><a href="../uploads/<?= $avatar;?>" rel="ShadowBox"><?= Check::Image('../uploads/' . $avatar, $nome, 50, 50); ?></a></td>
<?php endif; ?>
           
<td><?= $nome;?></td>
<td><?= $email;?></td>
<td style="text-align:center;"><?= $nivel;?></td>
<td style="text-align:center;"><?= $status;?></td>           

<td>
    <a class="btn btn-primary btn-sm" title="Alterar Senha" href="javascript:;" data-toggle="modal" data-target="#2<?= $id;?>">
	  <i style="color:#F8C90D;" class="fa fa-lock"></i>
	</a>
    
   <a style="color: #333 !important;" class="btn btn-default btn-sm" title="Editar" href="painel.php?exe=usuarios/usuarios-edit&userid=<?= $id;?>">
	  <i class="fa fa-pencil"></i>
	</a>

    <a title="Visualizar" href="painel.php?exe=usuarios/usuarios-perfil&userid=<?= $id;?>" class="btn btn-info btn-sm">
        <i class="fa fa-search"></i>
    </a>
    
    <a class="btn btn-danger btn-sm" title="Excluir" href="javascript:;" data-toggle="modal" data-target="#1<?= $id;?>">
	  <i class="fa fa-times"></i>
	</a>               
</td>
</tr>


<!-- MODAL DE ALTERAÇÃO DE SENHA -->
<div class="modal fade" id="2<?= $id;?>">
		<div class="modal-dialog">
                <div class="modal-content">				
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Alterar Senha de <?= $nome;?></strong></h4>
                    </div>              
                <div class="modal-body">
                
                <div class="form">
                <form class="j_submit" method="post" action="">
                    <div class="alertas"></div>                
                        <div class="row form_hide">                	
                            <div class="col-md-6 form-group">
                                <label for="password"><strong>Senha</strong></label>
                                <input name="action" value="alterar_senha" type="hidden" />
                                <input type="password" class="form-control input-lg" name="senha" />
                                <input type="hidden" class="form-control input-lg" name="iduser" value="<?= $id;?>"/>
                                <input type="hidden" class="form-control input-lg" name="tipo" value="1"/>                        
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="confirm_password"><strong>Confirmar Senha</strong></label>
                                <input type="password" class="form-control input-lg" name="code"/>                        
                            </div>
                        </div>                
                </div>
                
                </div>
                    <div class="modal-footer form_hide">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>                    
                        <button type="submit" class="loginbox btn btn-info">
                            <span>Alterar Senha</span>
                            <img src="images/loading.gif" width="18" height="18" alt="Carregando" title="Carregando" />
                        </button>                    
                    </div>
                </form>
		</div>
	  </div>
   </div> 
<!--
// FIM MODAL DE ALTERAÇÃO DE SENHA
--> 

<!-- MODAL DE EXCLUIR USUÁRIO -->
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
				<small>Você tem certeza que deseja excluir este Usuário?<br />
                <strong><?= $nome;?></strong></small>
			</p>
	        </blockquote>
              </div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="painel.php?exe=usuarios/usuarios&post=<?= $id;?>&action=delete">
    			<button type="button" class="btn btn-info">Confirmar</button>
                </a>
    		</div>
	 </div>
  </div>
</div>

<?php    
    endforeach;
?> 
 

</tbody>
</table>
<?php
	else:
        $Pager->ReturnPage();
        RMErro("Desculpe, ainda não existem usuários cadastrados!", RM_INFOR);  
    endif;
?>

</div>
</div>
</section>
</div>
</div>
</div>