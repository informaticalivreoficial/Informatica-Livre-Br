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
        <h3>Editar Página</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=paginas/paginas<?= $varPage;?>" title="Voltar e listar as Páginas" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar as Páginas</a>	
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
    $postId = filter_input(INPUT_GET, 'postid', FILTER_VALIDATE_INT);
    
    if(isset($post) && $post['SendPostForm']):
    
    $post['status'] = ($post['SendPostForm'] == 'Atualizar' ? '0' : '1' );
    $post['thumb'] = ($_FILES['thumb']['tmp_name'] ? $_FILES['thumb'] : 'null');
    unset($post['SendPostForm']);
    
    require('models/AdminPosts.class.php');
    $cadastra = new AdminPost;
    $cadastra->ExeUpdate($postId, $post);

    RMErro("A Página <b>{$post['titulo']}</b> foi atualizada com sucesso no sistema!", RM_ACCEPT);
    
    if (!empty($_FILES['gallery_covers']['tmp_name'])):
        $sendGallery = new AdminPost;
        $sendGallery->gbSend($_FILES['gallery_covers'], $postId, 'paginas/imagens');        
    endif;
    
    else: 
        $read = new Read;
        $read->ExeRead("posts","WHERE id = :id","id={$postId}");
        if(!$read->getResult()):
            header('Location: painel.php?exe=paginas/paginas&empty=true');
        else:
            $post = $read->getResult()[0];            
            $post['data'] = date('d/m/Y', strtotime($post['data']));
        endif;
    endif;
    
    if(!empty($_SESSION['errCapa'])):
        RMErro($_SESSION['errCapa'], E_USER_WARNING);
        unset($_SESSION['errCapa']);
    endif;
    
    $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
    if ($checkCreate && empty($cadastra)):
        RMErro("A Página <b>{$post['titulo']}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT);
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
                    <input type="hidden" name="tipo" value="pagina" />
                    <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="titulo" value="<?php if(isset($post['titulo'])) echo $post['titulo'];?>" />
                </div> 
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
                </div>
            </div>
             
             <div class="row">
                            <div class="col-md-6 form-group">
                                <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                                        <?php 
                                            $read1 = new Read;
                                            $read1->ExeRead("posts","WHERE id = :id","id={$postId}");
                                            if(!$read1->getResult()):
                                              echo '<img src="images/image.jpg">';  
                                            else:
                                            
                                             foreach($read1->getResult() as $capa1);
                                             if($capa1['thumb'] == ''):
                                                echo '<img src="images/image.jpg">';
                                             else:                                                
                                                echo '<img src="../uploads/'.$capa1['thumb'].'" />';
                                             endif; 
                                                                                           
                                            endif;                    
                        				  ?>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="thumb" class="default" value="" />
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
                                            <option value=""> Selecione a Categoria </option>
                                            <?php
                                            	$readCatPai = new Read;
                                                $readCatPai->ExeRead("cat_posts","WHERE tipo = 'pagina' AND id_pai IS NULL ORDER BY nome ASC");
                                                if($readCatPai->getRowCount() >= 1):
                                                    foreach($readCatPai->getResult() as $catpai):
                                                    echo "<option value=\"\" disabled=\"disabled\">{$catpai['nome']}</option>";
                                                        $readCat = new Read;
                                                        $readCat->ExeRead("cat_posts","WHERE tipo = 'pagina' AND id_pai = :catpai ORDER BY nome ASC","catpai={$catpai['id']}");
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
                                                endif;
                                            ?>	
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
    <?php
	$delGb = filter_input(INPUT_GET, 'gbdel', FILTER_VALIDATE_INT);
    if($delGb):
        require_once('models/AdminPosts.class.php');
        $DelGallery = new AdminPost;
        $DelGallery->gbRemove($delGb);

        RMErro($DelGallery->getError()[0], $DelGallery->getError()[1]);

    endif;
    
    $gbi = 0;
    $Gallery = new Read;
    $Gallery->ExeRead("posts_gb", "WHERE post_id = :post", "post={$postId}");
    if ($Gallery->getResult()):
        foreach ($Gallery->getResult() as $gb):
        $gbi++;
    ?>
        <div style="text-align: center;margin-bottom: 10px;" class="col-lg-3">		
    		<?= Check::Image('../uploads/' . $gb['img'], $post['titulo'], 150, 150); ?>
            <br />
            <a href="painel.php?exe=paginas/paginas-edit&postid=<?= $postId; ?>&gbdel=<?= $gb['id']; ?>#gbfoco" title="Exluir imagem"><button class="btn btn-danger btn-xs" style="margin-top: 5px;" type="button">Excluir</button></a>
            <a href="../uploads/<?= $gb['img'];?>" rel="ShadowBox" title="Imagem <?= $gbi; ?> da galeria de: <?= $post['titulo'];?>">
            <button class="btn btn-info btn-xs" style="margin-top: 5px;" type="button">Visualizar</button>
    		</a>
        </div>
    <?php
        endforeach;
	endif;
    ?>
    
    <div class="clear"></div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
            <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
        </div>
        <div class="col-md-3 form-group">
            <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
            <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
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