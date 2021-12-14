<div class="page-heading">
<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		echo '<div class="alert alert-info fade in">
				<button type="button" class="close close-sm" data-dismiss="alert">
                    <i class="fa fa-times"></i>
                </button>
				<strong>Atenção!</strong>
				Você não tem permissão para gerenciar os Grupos!</p>
			  </div>';
	}else{
?>
<div class="row">
    <div class="col-sm-8">
        <h3>Cadastrar Grupo</h3>
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
		$f['date'] 		= htmlspecialchars(mysqli_real_escape_string($conn,$_POST['data']));
        $f['status'] 	= htmlspecialchars(mysqli_real_escape_string($conn,$_POST['status']));
        $f['data']      = formDate($f['date']); 
        $f['tags'] 		= htmlspecialchars(mysqli_real_escape_string($conn,$_POST['tags']));
        $f['content']   = $_POST['content'];
		
		if($f['nome'] == ''){
    		echo '<div class="alert alert-block alert-danger fade in">
    				<button type="button" class="close close-sm" data-dismiss="alert">
                        <i class="fa fa-times"></i>
                    </button>
    				<strong>Atenção!</strong> Por favor, preencha pelo menos o campo <strong>Nome do Grupo</strong>!
                  </div>';
    	}else{
			
			$f['url']       = setUri($f['nome']);
			$readCatUri     = read('cat_cliente',"WHERE url LIKE '%$f[url]%'");
            foreach($readCatUri as $ruri1);
			if($f['url'] == $ruri1['url']){
				$f['url']  = $f['url'].'-'.mysqli_num_rows($readCatUri);
				$readCatUri = read('cat_cliente',"WHERE url = '$f[url]'");
                foreach($readCatUri as $ruri2);
				if($ruri2['url'] == $f['url']){
					$f['url']  = $f['url'].'_'.time();
				}
			}
            unset($f['date']);
            $idlast = create('cat_cliente',$f);
			$_SESSION['return'] = '<div class="alert alert-success">
                                        Grupo cadastrado com sucesso! você pode continuar a editar ele aqui.
						             </div>';
			header('Location: index2.php?exe=componentes/clientes/categorias-edit&edit='.$idlast);
		}
	}
?> 

<form role="form" name="formulario" action="" method="post">
<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
                <div class="row">
                    <div class="col-md-12 form-group"> 
                        <div class="row">                               
                            <div class="col-md-7 form-group">
                                <label for="exampleInputEmail1"><strong>Categoria Nome</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if($f['nome']) echo $f['nome'];?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="exampleInputEmail1"><strong>Status</strong></label>
                                <select name="status" class="form-control input-lg m-bot15">
                                    <option>Selecione</option>
                                    <option <?php if($f['status'] && $f['status'] == '1') echo 'selected="selected"';?>  value="1">Ativo</option>
                                    <option <?php if(!$f['status'] || $f['status'] == '0') echo 'selected="selected"';?>  value="0">Inativo</option>
                                 </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                <button name="sendForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" type="button">Cadastrar</button>
                            </div>
                        </div>                               
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="exampleInputEmail1"><strong>Meta Tags</strong></label>                                    
                                <input id="tags_1" type="text" class="tags" name="tags" value="<?php if($f['tags']) echo $f['tags'];?>" />                      
                            </div>
                        </div>                        
                        
                    </div>
                </div>

                <div class="row">
                                        
                    <div class="col-md-12 form-group">
                        <textarea class="form-control" rows="6" name="content" ><?php if($f['content']) echo $f['content'];?></textarea>
                    </div>
                   <input type="hidden" name="data" value="<?php if($f['date']) echo $f['date']; else echo date('d/m/Y');?>"/>
                    
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