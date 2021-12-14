<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Cadastrar Usuário</strong></h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=usuarios/usuarios" title="Voltar e listar Usuários" class="btn btn-primary btn-lg" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar Usuários</a>	
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
    $user = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if($user && $user['sendFormUser']):
    
    $user['avatar'] = ($_FILES['avatar']['tmp_name'] ? $_FILES['avatar'] : null);
    unset($user['sendFormUser']);
    
    require('models/AdminUser.class.php');
    $cadastra = new AdminUser;
    $cadastra->ExeCreate($user);
    
    if ($cadastra->getResult()):
        header("Location: painel.php?exe=usuarios/usuarios-edit&create=true&userid={$cadastra->getResult()}");
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
                            <div class="col-md-2 form-group" style="text-align: center;">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 160px; height: 160px;">
                                        <img src="images/200x200.gif"/>                                        
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 160px; height: 160px; line-height: 20px;"></div>
                                    <div>
                                           <span class="btn btn-default btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                           <input type="file" name="avatar" class="default" value="" />
                                           </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 form-group"> 
                                <div class="row">                               
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Nome</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="nome" value="<?php if(!empty($user['nome'])) echo $user['nome'];?>" />
                                    </div>                                    
                                </div>                               
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>E-mail</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="email" value="<?php if(!empty($user['email'])) echo $user['email'];?>" />
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>Facebook</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="facebook" value="<?php if(!empty($user['facebook'])) echo $user['facebook'];?>" />
                                    </div>
                                </div>
                                
                               
                            </div>
                            
                            <div class="col-md-5 form-group"> 
                                <div class="row">                               
                                    <div class="col-md-6 form-group">
                                        <?php if($userlogin['nivel'] == '1'):?>
                                        <label for="exampleInputEmail1"><strong>Permissão</strong></label>
                                        <select name="nivel" class="form-control input-lg">
                                        <option <?php if(isset($user['nivel']) && $user['nivel'] == '4') echo 'selected="selected"';?>  value="4">Público</option>
                                        <option <?php if(isset($user['nivel']) && $user['nivel'] == '3') echo 'selected="selected"';?>  value="3">Leitor</option>
                                        <option <?php if(isset($user['nivel']) && $user['nivel'] == '2') echo 'selected="selected"';?>  value="2">Editor</option>
                                        <option <?php if(isset($user['nivel']) && $user['nivel'] == '1') echo 'selected="selected"';?>  value="1">Administrador</option>                                        
                                        </select>
                                        <?php endif;?>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>&nbsp;</strong></label>
                                        <button name="sendFormUser" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar</button>
                                    </div>
                                </div>  
                                                             
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <?php if($userlogin['nivel'] == '1'):?>
                                        <label for="exampleInputEmail1"><strong>Status</strong></label>
                                        <select name="status" class="form-control input-lg">
                                            <option>Selecione</option>
                                            <option <?php if(isset($user['status']) && $user['status'] == '1') echo 'selected="selected"';?>  value="1">Ativo</option>
                                            <option <?php if(!isset($user['status']) || $user['status'] == '0') echo 'selected="selected"';?>  value="0">Inativo</option>
                                        </select>    
                                        <?php endif;?>                                        
                                    </div>
        
                                    <div class="col-md-6 form-group">
                                        <label for="exampleInputEmail1"><strong>Data de Nascimento</strong></label>
                                        <input class="form-control form-control-inline input-lg default-date-picker" size="16" type="text" name="nasc" value="<?php if(!empty($user['nasc'])): echo $user['nasc']; else: echo date('d/m/Y'); endif;?>"/>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="exampleInputEmail1"><strong>WebSite</strong></label>
                                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="site" value="<?php if(!empty($user['site'])) echo $user['site'];?>" />
                                    </div>
                                </div>
                               
                            </div>
                            
                         
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label><strong>Instagram</strong></label>
                                <input type="text" class="form-control input-lg" name="instagram" value="<?php if(!empty($user['instagram'])) echo $user['instagram'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                            <label><strong>Twitter</strong></label>
                            <input type="text" class="form-control input-lg" name="twitter" value="<?php if(!empty($user['twitter'])) echo $user['twitter'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                            <label><strong>Youtube</strong></label>
                            <input type="text" class="form-control input-lg" name="youtube" value="<?php if(!empty($user['youtube'])) echo $user['youtube'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                            <label><strong>Linkedin</strong></label>
                            <input type="text" class="form-control input-lg" name="linkedin" value="<?php if(!empty($user['linkedin'])) echo $user['linkedin'];?>" />
                            </div>
                        </div>
                        
                         <hr />
                        
                        <div class="row"> 
                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1"><strong>Rua</strong></label>
                            <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="rua" value="<?php if(!empty($user['rua'])) echo $user['rua'];?>" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="exampleInputEmail1"><strong>UF</strong></label>
                            <select name="uf" class="form-control input-lg j_loadstate">
                                    <option value="" selected> Selecione o estado </option>
                                    <?php
                                    $readState = new Read;
                                    $readState->ExeRead("estados", "ORDER BY nome ASC");
                                    foreach ($readState->getResult() as $estado):
                                        extract($estado);
                                        echo "<option value=\"{$id}\" ";
                                        if (isset($user['uf']) && $user['uf'] == $id): echo 'selected'; endif;
                                        echo "> {$uf} / {$nome} </option>";
                                    endforeach;
                                    ?>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="exampleInputEmail1"><strong>Cidade</strong></label>
                            <select class="form-control input-lg j_loadcity" name="cidade">
                                    <?php if (!isset($user['cidade'])): ?>
                                        <option value="" selected disabled> Selecione antes um estado </option>
                                        <?php
                                    else:
                                        $City = new Read;
                                        $City->ExeRead("cidades", "WHERE id_estado = :uf ORDER BY nome ASC", "uf={$user['uf']}");
                                        if ($City->getRowCount()):
                                            foreach ($City->getResult() as $cidade):
                                                extract($cidade);
                                                echo "<option value=\"{$id}\" ";
                                                if (isset($user['cidade']) && $user['cidade'] == $id):
                                                    echo "selected";
                                                endif;
                                                echo "> {$nome} </option>";
                                            endforeach;
                                        endif;
                                    endif;
                                    ?>
                            </select>
                        </div>                        
                        
                        
                        </div> 
                        
                        <div class="row"> 
                            <div class="col-md-5 form-group">
                                <label for="exampleInputEmail1"><strong>Bairro</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="bairro" value="<?php if(!empty($user['bairro'])) echo $user['bairro'];?>" />
                            </div>                        
                            <div class="col-md-2 form-group">
                                <label for="exampleInputEmail1"><strong>Número</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="num" value="<?php if(!empty($user['num'])) echo $user['num'];?>" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="exampleInputEmail1"><strong>Cep</strong></label>
                                <input type="text" class="form-control input-lg" data-mask="99.999-999" id="exampleInputEmail1" name="cep" value="<?php if(!empty($user['cep'])) echo $user['cep'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>Complemento</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="complemento" value="<?php if(!empty($user['complemento'])) echo $user['complemento'];?>" />
                            </div>                        
                        </div> 
                        
                        <hr />  
                        
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>Telefone fixo</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" data-mask="(99) 9999-9999" name="telefone1" value="<?php if(!empty($user['telefone1'])) echo $user['telefone1'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>Telefone móvel</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" data-mask="(99) 99999-9999" name="telefone2" value="<?php if(!empty($user['telefone2'])) echo $user['telefone2'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>WhatsApp</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" data-mask="(99) 99999-9999" name="whatsapp" value="<?php if(!empty($user['whatsapp'])) echo $user['whatsapp'];?>" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="exampleInputEmail1"><strong>Skype</strong></label>
                                <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="skype" value="<?php if(!empty($user['skype'])) echo $user['skype'];?>" />
                            </div>                            
                        </div>
                        
                        <hr />  
                        
                        <div class="row">                            
                            <div class="col-md-6 form-group">
                                <label for="exampleInputEmail1"><strong>Senha</strong></label>
                                <input type="password" class="form-control input-lg" id="exampleInputEmail1" name="code" value="<?php if(!empty($user['code'])) echo $user['code'];?>" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="exampleInputEmail1"><strong>Repetir Senha</strong></label>
                                <input type="password" class="form-control input-lg" id="exampleInputEmail1" name="code1" value="<?php if(!empty($user['code1'])) echo $user['code1'];?>" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="exampleInputEmail1"><strong>Apresentação</strong></label>
                        		<textarea class="form-control editor" name="descricao" rows="6"><?php if(!empty($user['descricao'])) echo htmlspecialchars($user['descricao']);?></textarea>
                        	    
                            </div>
                            
                            <div class="col-md-12 form-group">
                                <button name="sendFormUser" type="submit" style="width:100%;" class="btn btn-success btn-lg" value="Salvar">Salvar Agora</button>
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