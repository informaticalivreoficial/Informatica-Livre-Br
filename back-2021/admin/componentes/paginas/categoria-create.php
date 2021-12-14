<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
    
    $catid = filter_input(INPUT_GET, 'catid', FILTER_VALIDATE_INT);
    // SE NÃO EXISTIR CATID(FOR CATEGORIA)
    if(!$catid):        
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Cadastrar Categoria</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=paginas/categorias<?= $varPage;?>" title="Voltar e Listar categorias" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e Listar categorias</a>	
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
    if(!empty($post && $post['SendPostForm'])):
    unset($post['SendPostForm']);
    
    require('./models/AdminCategorias.class.php');
    $cadastra = new AdminCategorias;
    $cadastra->ExeCreate('cat_posts', $post);
    
    if (!$cadastra->getResult()):
        RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        header('Location: painel.php?exe=paginas/categoria-edit&create=true&catid=' . $cadastra->getResult().$getPage);
    endif;
    
   
    endif;
?>  

<form role="form" name="formulario" action="" method="post">

<div class="row">
    <div class="col-md-12">

        <div class="panel"> 
            <div class="panel-body">
                <div class="row">                               
                    <div class="col-md-6 form-group">
                        <label for="exampleInputEmail1"><strong>Nome da Categoria</strong></label>
                        <input type="hidden" name="data" value="<?= date('d/m/Y H:i:s'); ?>" />
                        <input type="hidden" name="tipo" value="pagina" />
                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>Exibir no site?</strong></label>
                        <select name="exibir" class="form-control input-lg m-bot15">                
                            <option>Selecione</option>
                            <option <?php if(isset($post['exibir']) && $post['exibir'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                            <option <?php if(!isset($post['exibir']) || $post['exibir'] == '0') echo 'selected="selected"';?>  value="0">Não</option>               
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="exampleInputEmail1"><strong>Meta Tags</strong></label>                                    
                        <input id="tags_1" type="text" class="tags" name="tags" value="<?php if(isset($post['tags'])) echo $post['tags'];?>" />                      
                    </div>
                    
                    <div class="col-md-12 form-group">
                        <textarea class="form-control editor" rows="6" name="content" ><?php if(isset($post['content'])) echo htmlspecialchars($post['content']);?></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar</button>
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
    // SE EXISTIR CATID (FOR SUBCATEGORIA)
    else:
    
    $readCatPai = new Read;
    $readCatPai->ExeRead("cat_posts", "WHERE id = :id", "id={$catid}");
    if($readCatPai->getResult()):
        foreach($readCatPai->getResult() as $catPai);
        extract($catPai);
    endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Cadastrar Sub-Categoria para <small>(<?= $nome;?>)</small></h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=paginas/categorias<?= $varPage;?>" title="Voltar e Listar categorias" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e Listar categorias</a>	
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
    if(!empty($post && $post['SendPostForm'])):
    unset($post['SendPostForm']);
    
    require('./models/AdminCategorias.class.php');
    $cadastra = new AdminCategorias;
    $cadastra->ExeCreate('cat_posts', $post);
    
    if (!$cadastra->getResult()):
        RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        header('Location: painel.php?exe=paginas/categoria-edit&create=true&catid=' . $cadastra->getResult().$getPage);
    endif;
    
   
    endif;
?>  

<form role="form" name="formulario" action="" method="post">

<div class="row">
    <div class="col-md-12">

        <div class="panel"> 
            <div class="panel-body">
                <div class="row">                               
                    <div class="col-md-6 form-group">
                        <label for="exampleInputEmail1"><strong>Nome da Sub-Categoria</strong></label>
                        <input type="hidden" name="id_pai" value="<?= $catid;?>" />
                        <input type="hidden" name="data" value="<?= date('d/m/Y H:i:s'); ?>" />
                        <input type="hidden" name="tipo" value="pagina" />
                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>Exibir no site?</strong></label>
                        <select name="exibir" class="form-control input-lg m-bot15">                
                            <option>Selecione</option>
                            <option <?php if(isset($post['exibir']) && $post['exibir'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                            <option <?php if(!isset($post['exibir']) || $post['exibir'] == '0') echo 'selected="selected"';?>  value="0">Não</option>               
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="exampleInputEmail1"><strong>Meta Tags</strong></label>                                    
                        <input id="tags_1" type="text" class="tags" name="tags" value="<?php if(isset($post['tags'])) echo $post['tags'];?>" />                      
                    </div>
                    
                    <div class="col-md-12 form-group">
                        <textarea class="form-control editor" rows="6" name="content" ><?php if(isset($post['content'])) echo htmlspecialchars($post['content']);?></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar</button>
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
    endif;
?>