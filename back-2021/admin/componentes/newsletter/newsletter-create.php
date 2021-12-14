<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);    
    // SE TIVER PAGINAÇÃO ENVIA O PAGE
    if($getPage): $varPage = '&page='.$getPage.''; else: $varPage = ''; endif;
    $listaId = filter_input(INPUT_GET, 'lista', FILTER_VALIDATE_INT);
    // ID DA LISTA
    if($listaId): $listaIdPage = 'newsletter&lista='.$listaId.''; else: $listaIdPage = 'listas'; endif;
    $readLista = new Read;
    $readLista->ExeRead("newsletter_cat", "WHERE id = :ListaId","ListaId={$listaId}");
    if($readLista->getResult()):
        $lista = $readLista->getResult()['0'];
    endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Cadastrar E-mail</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=newsletter/<?= $listaIdPage;?><?= $varPage;?>" title="Voltar" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar</a>	
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
        $cadastra->ExeCreate($post);    
        if ($cadastra->getResult()):        
            header('Location: painel.php?exe=newsletter/newsletter-edit&lista='.$post['cat_id'].'&create=true&postid=' . $cadastra->getResult());
        else:
            RMErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;    
    endif;
?>
<form method="post" role="form" action="">
<div class="row">
    <div class="col-md-5 form-group">
        <label><strong>Nome</strong></label>
        <input type="text" class="form-control input-lg" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
    </div> 	
	<div class="col-md-4 form-group">
        <label><strong>E-mail</strong></label>
        <input type="text" class="form-control input-lg" name="email" value="<?php if(isset($post['email'])) echo $post['email'];?>" />
    </div>
    <div class="col-md-3 form-group">
        <label><strong>Data da Publicação</strong></label>
        <input class="form-control form-control-inline input-lg default-date-picker" name="data" size="16" type="text" value="<?php if(isset($post['data'])): echo $post['data']; else: echo date('d/m/Y'); endif;?>"/>
    </div>        
</div>

<div class="row">
    <div class="col-md-5 form-group">
        <label><strong>Selecione a Lista</strong></label>        
            <?php
                $readListas = new Read;
                $readListas->ExeRead("newsletter_cat", "WHERE status = '1'");
                if($readListas->getResult()):
                    echo '<select name="cat_id" class="form-control input-lg m-bot15">';
                        echo '<option value="0"> Selecione </option>';
                    foreach($readListas->getResult() as $listas):
                        echo '<option value="'.$listas['id'].'"';
                    if(isset($post['cat_id']) && $listas['id'] == $post['cat_id'] || $listas['id'] == $listaId):
                        echo ' selected="selected" ';
                    endif;  
                        echo '> '.$listas['titulo'].'</option>';                  
                    endforeach;
                else:
                    echo '<select name="cat_id" class="form-control input-lg m-bot15" disabled>';
                    echo '<option value="0">Não existem Listas Cadastradas</option>';
                endif;
             ?>
        </select>
    </div>
    <div class="col-md-2 form-group">
        <label><strong>Autorizado?</strong></label>
        <select name="autorizacao" class="form-control input-lg m-bot15">
            <option value=""> Selecione </option>
            <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
            <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Não</option>
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label><strong>WhatsApp</strong></label>
        <input type="text" class="form-control input-lg" data-mask="(99) 99999-9999" name="whatsapp" value="<?php if(!empty($post['whatsapp'])) echo $post['whatsapp'];?>" />
    </div>
    <div class="col-md-2 form-group">
        <label><strong>&nbsp;</strong></label>
        <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Cadastrar">Cadastrar</button>
    </div>
</div>
</form>        

</div>
</div>
</section>
</div>
</div>
</div>
