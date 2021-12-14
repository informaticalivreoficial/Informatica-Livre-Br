<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $catid    = filter_input(INPUT_GET, 'catid', FILTER_VALIDATE_INT);
    $catsubid = filter_input(INPUT_GET, 'catsubid', FILTER_VALIDATE_INT);
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
    
    // SE EXISTIR CATID (FOR CATEGORIA)
    if($catid && !$catsubid):
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Editar Categoria</h3>
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
    $post  = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    
    if(!empty($post && $post['SendPostForm'])):
    unset($post['SendPostForm']);
    
    require('./models/AdminCategorias.class.php');
    $cadastra = new AdminCategorias;
    $cadastra->ExeUpdate('cat_posts', $catid, $post);
    
    RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    
    else:
        $read = new Read;
        $read->ExeRead("cat_posts", "WHERE id = :id", "id={$catid}");
        if (!$read->getResult()):
            header('Location: painel.php?exe=paginas/categorias&empty=true');
        else:
            $post = $read->getResult()[0];
        endif; 
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if($checkCreate && empty($cadastra)):
        $tipo = ( empty($post['id_pai']) ? 'Categoria' : 'Sub-Categoria');
        RMErro("A {$tipo} <b>{$post['nome']}</b> foi cadastrada com sucesso no sistema! Continue atualizando a mesma!", RM_ACCEPT);
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
                        <input type="hidden" name="uppdate" value="<?= date('d/m/Y'); ?>" />
                        <input type="hidden" name="id_pai" value="null" />
                        <input type="hidden" name="tipo" value="pagina" />
                        <input type="hidden" name="data" value="<?= date('d/m/Y H:i:s', strtotime($post['data'])); ?>" />
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
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
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
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
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
    // SE FOR SUB-CATEGORIA)
    else:
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Editar Sub-Categoria</h3>
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
    $post  = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    
    if(!empty($post && $post['SendPostForm'])):
    unset($post['SendPostForm']);
    
    require('./models/AdminCategorias.class.php');
    $cadastra = new AdminCategorias;
    $cadastra->ExeUpdate('cat_posts', $catsubid, $post);
    
    RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    
    else:
        $read = new Read;
        $read->ExeRead("cat_posts", "WHERE id = :id", "id={$catsubid}");
        if (!$read->getResult()):
            header('Location: painel.php?exe=paginas/categorias&empty=true');
        else:
            $post = $read->getResult()[0];
        endif; 
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if($checkCreate && empty($cadastra)):
        $tipo = ( empty($post['id_pai']) ? 'Categoria' : 'Sub-Categoria');
        RMErro("A {$tipo} <b>{$post['nome']}</b> foi cadastrada com sucesso no sistema! Continue atualizando a mesma!", RM_ACCEPT);
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
                        <input type="hidden" name="uppdate" value="<?= date('d/m/Y'); ?>" />
                        <input type="hidden" name="id_pai" value="<?= $catid;?>" />
                        <input type="hidden" name="tipo" value="pagina" />
                        <input type="hidden" name="data" value="<?= date('d/m/Y H:i:s', strtotime($post['data'])); ?>" />
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
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
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
                        <button name="SendPostForm" type="submit" style="float: right;width:100%;" class="btn btn-success btn-lg" value="Atualizar">Atualizar</button>
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