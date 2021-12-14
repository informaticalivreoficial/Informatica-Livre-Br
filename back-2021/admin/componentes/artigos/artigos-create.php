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
            <h3>Cadastrar Artigo</h3>
        </div>
        <div class="col-sm-6">
            <a href="painel.php?exe=artigos/artigos<?= $varPage;?>" title="Voltar e listar os Artigos" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Artigos</a>	
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
    
    $post['status'] = ($post['SendPostForm'] == 'Salvar' ? '0' : '1' );
    
    $post['thumb'] = ($_FILES['thumb']['tmp_name'] ? $_FILES['thumb'] : null);
    unset($post['SendPostForm']);
    
    require('models/AdminPosts.class.php');
    $cadastra = new AdminPost;
    $cadastra->ExeCreate($post);
    
    if ($cadastra->getResult()):
        if (!empty($_FILES['gallery_covers']['tmp_name'])):
            $sendGallery = new AdminPost;
            $sendGallery->gbSend($_FILES['gallery_covers'], $cadastra->getResult(), 'artigos/imagens');
        endif;

        header('Location: painel.php?exe=artigos/artigos-edit&artigos-create=true&postid=' . $cadastra->getResult());
    else:
        RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
    endif;
    
    endif;
?>

<form method="post" action="" enctype="multipart/form-data">

<div class="row">
    <div class="col-md-12">

        <div class="panel">                    

            <div class="panel-body">
            
            <div class="row">                               
                <div class="col-md-6 form-group">
                    <label for="exampleInputEmail1"><strong>Título</strong></label>
                    <input type="hidden" name="tipo" value="artigo" />
                    <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="titulo" value="<?php if(isset($post['titulo'])) echo $post['titulo'];?>" />
                </div>
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Salvar">Salvar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Salvar & Publicar">Salvar & Publicar</button>
                </div>
            </div>
             
             <div class="row">
                            <div class="col-md-6 form-group">
                                <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                                        <?= '<img src="images/image.jpg">';  ?>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="thumb" class="default" value="<?php if(isset($post['thumb'])) echo $post['thumb'];?>" />
                                           </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 form-group"> 
                                                               
                                <div class="row">
                                    <div class="col-md-7 form-group">
                                        <label for="exampleInputEmail1"><strong>Autor</strong></label>
                                        <select name="autor" class="form-control input-lg m-bot15">
                                            <option  value="<?= $userlogin['id']; ?>"><?= $userlogin['nome']; ?></option>
                                            <?php
                                            $readAutor = new Read;
                                            $readAutor->ExeRead("usuario","WHERE status = '1' AND id != :iduser AND nivel <= :niveluser ORDER BY nome ASC","iduser={$userlogin['id']}&niveluser=2");
                                            if($readAutor->getRowCount() >= 1):
                                                foreach($readAutor->getResult() as $autor):
                                                    echo "<option ";
                                                         
                                                     if($post['autor'] == $autor['id']):
                                                     echo "selected=\"selected\" ";
                                                     endif;
                                                     
                                                    echo "value=\"{$autor['id']}\"> {$autor['nome']} </option>";
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
        
                                    <div class="col-md-5 form-group">
                                        <label for="exampleInputEmail1"><strong>Permissão</strong></label>
                                        <select name="nivel" class="form-control input-lg m-bot15">
                                            <option  value="4">Público</option>
                                            <option  value="3">Assinante</option>
                                            <option  value="2">Editores</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Categoria</strong></label>
                                        <select name="categoria" class="form-control input-lg m-bot15">                                            
                                            <?php
                                            	$readCatPai = new Read;
                                                $readCatPai->ExeRead("cat_posts","WHERE tipo = 'artigo' AND id_pai IS NULL ORDER BY nome ASC");
                                                if($readCatPai->getRowCount() >= 1):
                                                    echo '<option value=""> Selecione a Categoria </option>';
                                                    foreach($readCatPai->getResult() as $catpai):
                                                    echo "<option value=\"\" disabled=\"disabled\">{$catpai['nome']}</option>";
                                                        $readCat = new Read;
                                                        $readCat->ExeRead("cat_posts","WHERE tipo = 'artigo' AND id_pai = :catpai ORDER BY nome ASC","catpai={$catpai['id']}");
                                                        if($readCat->getRowCount() >= 1):
                                                         foreach($readCat->getResult() as $cat):
                                                         echo "<option ";
                                                         
                                                         if($post['categoria'] == $cat['id']):
                                                         echo "selected=\"selected\" ";
                                                         endif;
                                                         
                                                         echo "value=\"{$cat['id']}\">&raquo;&raquo; {$cat['nome']}</option>";
                                                         endforeach;
                                                        endif;
                                                    endforeach;
                                                else: 
                                                    echo '<option value=""> Cadastre Uma Categoria </option>';
                                                endif;
                                            ?>	
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>Permitir Comentários</strong></label>
                                        <select name="comentarios" class="form-control input-lg m-bot15">
                                            <option value=""> Selecione </option>
                                            <option <?php if(isset($post['comentarios']) && $post['comentarios'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                                            <option <?php if(!isset($post['comentarios']) || $post['comentarios'] == '0') echo 'selected="selected"';?>  value="0">Não</option>	
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>Data da Publicação</strong></label>
                                        <input class="form-control form-control-inline input-lg default-date-picker" name="data" size="16" type="text" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>Vai exibir no slide?</strong></label>
                                        <div class="input-group">                
                                    	     <input type="radio" name="slide" id="rd-1" value="1" <?php if(isset($post['slide']) && $post['slide'] == '1') echo 'checked="checked"';?>/>&nbsp;&nbsp;Sim&nbsp;&nbsp;
                                             <input type="radio" name="slide" id="rd-1" value="0" <?php if(!isset($post['slide']) || $post['slide'] == '0') echo 'checked="checked"';?>/>&nbsp;&nbsp;Não&nbsp;&nbsp;                               
                                         </div>
                                    </div>                                    
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Legenda da Imagem</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="thumb_legenda" value="<?php if(isset($post['thumb_legenda'])) echo $post['thumb_legenda'];?>" />
                                    </div>
                                </div>
                                
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
                            
                        </div>
                        
                        <div class="clear"></div>        
                        <div class="row" id="gbfoco">
                        <div class="col-md-12 form-group">
                            <h3>Galeria</h3>
                                
                                <div class="upload-btn-wrapper">
                                <button class="btnup">Enviar Imagens da Galeria</button>
                                <input name="gallery_covers[]" type="file" multiple  />
                                </div>
                        </div>
                        </div> 
                        
                        <div class="clear"></div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Salvar">Salvar</button>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Salvar & Publicar">Salvar & Publicar</button>
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