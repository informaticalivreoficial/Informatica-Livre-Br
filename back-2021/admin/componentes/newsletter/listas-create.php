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
        <h3>Cadastrar Lista</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=newsletter/listas<?= $varPage;?>" title="Voltar" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar</a>	
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
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($post) && $post['SendPostForm']):    
        unset($post['SendPostForm']);    
        require('models/AdminNewsletter.class.php');
        $cadastra = new AdminNewsletter;
        $cadastra->ExeCreateLista($post);    
        if ($cadastra->getResult()):        
            header('Location: painel.php?exe=newsletter/listas-edit&create=true&postid=' . $cadastra->getResult());
        else:
            RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;    
    endif;
?> 
<form method="post" role="form" action="">
<input type="hidden" name="data" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="row">                               
                            <div class="col-md-6 form-group">
                                <label><strong>Nome Da Lista</strong></label>
                                <input type="text" class="form-control input-lg" name="titulo" value="<?php if(isset($post['titulo'])) echo $post['titulo'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label><strong>Status</strong></label>
                                <select name="status" class="form-control input-lg m-bot15 j_loadsubcat">                                            
                                    <option value=""> Selecione </option>
                                    <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Ativo</option>
                                    <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Inativo</option>	
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label><strong>&nbsp;</strong></label>
                                <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Cadastrar">Cadastrar</button>
                            </div>
                        </div>
                        
                        
                        <div class="row" style="margin-top: 10px;">                               
                            <div class="col-md-4 form-group">
                                <label><strong>Servidor SMTP</strong></label>
                                <input type="text" class="form-control input-lg" name="servidor_smtp" value="<?php if(isset($post['servidor_smtp'])) echo $post['servidor_smtp'];?>" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label><strong>E-mail</strong></label>
                                <input type="text" class="form-control input-lg" name="servidor_email" value="<?php if(isset($post['servidor_email'])) echo $post['servidor_email'];?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label><strong>Porta SMTP</strong></label>
                                <input type="text" class="form-control input-lg" name="servidor_porta" value="<?php if(isset($post['servidor_porta'])) echo $post['servidor_porta'];?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label><strong>Senha SMTP</strong></label>
                                <input type="password" class="form-control input-lg" name="servidor_senha" value="<?php if(isset($post['servidor_senha'])) echo $post['servidor_senha'];?>" />
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label><strong>Descrição Da Lista</strong></label>
                                <textarea class="form-control" name="content" rows="6"><?php if(!empty($post['content'])) echo htmlspecialchars($post['content']);?></textarea>
                            </div>
                        </div>
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