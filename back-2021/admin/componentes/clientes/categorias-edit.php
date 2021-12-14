<div class="page-heading">
<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		echo '<div class="alert alert-info">
               <strong>Atenção!</strong> Você não tem permissão para gerenciar os Grupos!
              </div>';
	}else{
		$urledit  = $_GET['edit'];
        $readEdit = read('cat_cliente',"WHERE id = '$urledit'");
		if(!$readEdit){
			header('Location: index2.php?exe=componentes/clientes/categorias');
		}else
			foreach($readEdit as $catedit);	
?>
<div class="row">
    <div class="col-sm-8">
        <h3>Editar Grupo - <?php echo $catedit['nome']; ?></h3>
    </div>
    <div class="col-sm-4">
        <a href="index2.php?exe=componentes/clientes/categorias" title="Voltar e Listar Grupos" class="btn btn-info btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e Listar Grupos</a>
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
if(isset($_POST['sendForm'])){
    $conn = connect();  
	$f['nome'] 		= htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nome']));
    $f['status'] 	= htmlspecialchars(mysqli_real_escape_string($conn,$_POST['status']));				
	$f['content'] = $_POST['content'];
    $f['tags'] 	  = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['tags']));
    
    $f['uppdate'] 		= date('Y-m-d H:i:s');
	
	if($f['nome'] == ''){
		echo '<div class="alert alert-info fade in">
				<button type="button" class="close close-sm" data-dismiss="alert">
                    <i class="fa fa-times"></i>
                </button>
				<strong>Atenção!</strong> Por favor, preencha pelo menos o campo <strong>Nome do Grupo</strong>!
              </div>';
	}else{
		
        
		if($catedit['nome'] != $f['nome']){
			if($prefix){
				$f['url']  = $prefix.'-'.setUri($f['nome']);
				$readCatUri = read('cat_cliente',"WHERE url LIKE '%$f[url]%' AND id_pai IS NOT null AND id != '$urledit'");
				foreach($readCatUri as $caturi);
                if($caturi['url'] != $f['url'] && $caturi['id_pai'] != '' && $caturi['id'] != $urledit){
					$f['url']  = $f['url'].'-'.mysqli_num_rows($readCatUri);
					$readCatUri = read('cat_cliente',"WHERE url = '$f[url]' AND id_pai IS NOT null AND id != '$urledit'");
					if($readCatUri){
						$f['url']  = $f['url'].'_'.time();
					}
				}
			}else{
				$f['url']  = setUri($f['nome']);
				$readCatUri = read('cat_cliente',"WHERE url LIKE '%$f[url]%' AND id_pai IS null AND id != '$urledit'");
				if($caturi['url'] != $f['url'] && $caturi['id_pai'] != '' && $caturi['id'] != $urledit){
					$f['url']  = $f['url'].'-'.mysqli_num_rows($readCatUri);
					$readCatUri = read('cat_cliente',"WHERE url = '$f[url]' AND id_pai IS null AND id != '$urledit'");
					if($readCatUri){
						$f['url']  = $f['url'].'_'.time();
					}
				}
			}
		}else{
			$f['url']  = $catedit['url'];
		}
        				
		update('cat_cliente',$f,"id = '$urledit'");
		$_SESSION['return'] = '<div class="alert alert-success fade in">
                                <i class="fa fa-check"></i>
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Grupo atualizado com sucesso!
                            </div>';
		header('Location: index2.php?exe=componentes/clientes/categorias-edit&edit='.$urledit);
	}
}elseif(!empty($_SESSION['return'])){
	echo $_SESSION['return'];
	unset($_SESSION['return']);
}
		
?>   
<form method="post" role="form" action="" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
                <div class="row">
                    <div class="col-md-12 form-group"> 
                        <div class="row">                               
                            <div class="col-md-7 form-group">
                                <label for="exampleInputEmail1"><strong>Nome do Grupo</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php echo $catedit['nome']; ?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="exampleInputEmail1"><strong>Status</strong></label>
                                <select name="status" class="form-control input-lg m-bot15">
                                    <?php
                                        $publicado = 'Ativo';
                                        $rascunho  = 'Inativo';	
                                 
                                         if($catedit['status'] == '1'){
                                            echo '<option selected="selected" value="1">'.$publicado.'</option>';
                                            echo '<option value="0">'.$rascunho.'</option>';
                                         }elseif($catedit['status'] == '0'){
                                            echo '<option selected="selected" value="0">'.$rascunho.'</option>';
                                            echo '<option value="1">'.$publicado.'</option>';
                                         }else{
                                            echo '<option selected="selected">Selecione</option>';
                                            echo '<option value="1">'.$publicado.'</option>';
                                            echo '<option value="0">'.$rascunho.'</option>';
                                         }
                                     ?>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                <button name="sendForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" type="button">Atualizar</button>
                            </div>
                        </div>                               
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="exampleInputEmail1"><strong>Meta Tags</strong></label>                                    
                                <input id="tags_1" type="text" class="tags" name="tags" value="<?php echo $catedit['tags']; ?>" />                      
                            </div>
                        </div>                        
                        
                    </div>
                </div>

                <div class="row">
                                        
                    <div class="col-md-12 form-group">
                        <textarea class="form-control" rows="6" name="content" ><?php echo htmlspecialchars(stripslashes($catedit['content'])); ?></textarea>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>

</form>   

</div>
</div>
</section>
</div>
</div>
</div>
<?php
	}
}else{
	header('Location: ../index2.php');
}
?>