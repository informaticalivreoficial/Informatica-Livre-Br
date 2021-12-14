<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
?>
<div class="page-heading">
<div class="row">
    <div class="col-sm-6">
        <h3>Cadastrar Cliente</h3>
    </div>
    <div class="col-sm-6">
        <a href="painel.php?exe=clientes/clientes" title="Voltar e listar os Clientes" class="btn btn-primary btn-lg right" style="float:right;"><i class="fa fa-mail-reply"></i> Voltar e listar os Clientes</a>	
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
    if($post && $post['SendPostForm']):
    
    $post['status'] = ($post['SendPostForm'] == 'Cadastrar' ? '0' : '1' );
    //AUTOR
    $post['autor']     = $userlogin['nome'];
    
    $post['logomarca'] = ($_FILES['logomarca']['tmp_name'] ? $_FILES['logomarca'] : null);
    unset($post['SendPostForm']);
    
    require('models/AdminClientes.class.php');
    $cadastra = new AdminClientes;
    $cadastra->ExeCreate($post);
    
    if ($cadastra->getResult()):
        header("Location: painel.php?exe=clientes/clientes-edit&create=true&clienteId={$cadastra->getResult()}");
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
                    <label><strong>Nome</strong></label>
                    <!-- DATA DO CADASTRO -->
                    <input type="hidden" name="data" value="<?php if(isset($post['data'])) echo date('d/m/Y H:i:s', strtotime($post['data']));?>" />
                    <input type="text" class="form-control input-lg" name="nome" value="<?php if(isset($post['nome'])) echo $post['nome'];?>" />
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Cadastrar">Cadastrar</button>
                </div>
                <div class="col-md-3 form-group">
                    <label><strong>&nbsp;</strong></label>
                    <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Cadastrar & Publicar">Cadastrar & Publicar</button>
                </div>
            </div>
                
            <div class="row">                               
                <div class="col-md-6 form-group">
                    <label><strong>Responsável</strong></label>                    
                    <input type="text" class="form-control input-lg" name="responsavel" value="<?php if(isset($post['responsavel'])) echo $post['responsavel'];?>" />
                </div>
                <div class="col-md-6 form-group">
                    <label><strong>CPF/CNPJ</strong></label>                    
                    <input type="text" class="form-control input-lg" name="cpfcnpj" value="<?php if(isset($post['cpfcnpj'])) echo $post['cpfcnpj'];?>" />
                </div>
            </div>
             
             <div class="row">
                    <div class="col-md-6 form-group">
                        <div style="margin-top: 10px;" class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 100%; height: 350px;">
                                <img src="images/300x250.jpg"/>                                         
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%; height: 350px; line-height: 20px;"></div>
                            <div>
                                   <span class="btn btn-default btn-file">
                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> Selecionar outra</span>
                                   <input type="file" name="logomarca" class="default" value="" />
                                   </span>
                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-6 form-group">
                                                       
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label><strong>Grupo</strong></label>
                                <select name="cat_cliente_id" class="form-control input-lg m-bot15">
                                    <?php
                                    $readGrupo = new Read;
                                    $readGrupo->ExeRead("cat_cliente","WHERE status = '1' ORDER BY nome ASC");
                                    if($readGrupo->getRowCount() >= 1):
                                        foreach($readGrupo->getResult() as $grupo):
                                            echo "<option ";
                                                 
                                             if(isset($post['cat_cliente_id']) && $post['cat_cliente_id'] == $grupo['id']):
                                             echo "selected=\"selected\" ";
                                             endif;
                                             
                                            echo "value=\"{$grupo['id']}\"> {$grupo['nome']} </option>";
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
                                <label><strong>Status</strong></label>
                                <select name="status" class="form-control input-lg m-bot15 j_loadsubcat">                                            
                                    <option value=""> Selecione </option>
                                    <option <?php if(isset($post['status']) && $post['status'] == '1') echo 'selected="selected"';?>  value="1">Ativo</option>
                                    <option <?php if(!isset($post['status']) || $post['status'] == '0') echo 'selected="selected"';?>  value="0">Inativo</option>	
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label><strong>E-mail</strong></label>
                                <input type="text" class="form-control input-lg" name="email" value="<?php if(isset($post['email'])) echo $post['email'];?>" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label><strong>Website</strong></label>
                                <input type="text" class="form-control input-lg" name="website" value="<?php if(isset($post['website'])) echo $post['website'];?>" />
                            </div>
                        </div>
                        
                    </div>
                </div> 
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Segmento</strong></label>                                    
                        <input type="text" class="form-control input-lg" placeholder="Pousada, Escola, Oficina etc..." name="segmento" value="<?php if(isset($post['segmento'])) echo $post['segmento'];?>" />                      
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Indicação</strong></label>                                    
                        <input type="text" class="form-control input-lg" name="indicacao" value="<?php if(isset($post['indicacao'])) echo $post['indicacao'];?>" />                      
                    </div>
                </div>                
                    
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Instagram</strong></label>
                        <input type="text" class="form-control input-lg" name="instagram" value="<?php if(!empty($post['instagram'])) echo $post['instagram'];?>" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Twitter</strong></label>
                        <input type="text" class="form-control input-lg" name="twitter" value="<?php if(!empty($post['twitter'])) echo $post['twitter'];?>" />
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>Linkedin</strong></label>
                        <input type="text" class="form-control input-lg" name="linkedin" value="<?php if(!empty($post['linkedin'])) echo $post['linkedin'];?>" />
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>Facebook</strong></label>
                        <input type="text" class="form-control input-lg" name="facebook" value="<?php if(!empty($post['facebook'])) echo $post['facebook'];?>" />
                    </div>
                </div>
                
                <hr />
                
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label><strong>Exibir endereço?</strong></label>
                        <select name="exibir_endereco" class="form-control input-lg m-bot15">
                            <option value=""> Selecione </option>
                            <option <?php if(isset($post['exibir_endereco']) && $post['exibir_endereco'] == '1') echo 'selected="selected"';?>  value="1">Sim</option>
                            <option <?php if(!isset($post['exibir_endereco']) || $post['exibir_endereco'] == '0') echo 'selected="selected"';?>  value="0">Não</option>	
                        </select>
                    </div>
                </div>
                
                <div class="row"> 
                    <div class="col-md-5 form-group">
                        <label><strong>Rua</strong></label>
                        <input type="text" class="form-control input-lg" id="exampleInputEmail1" name="rua" value="<?php if(!empty($post['rua'])) echo $post['rua'];?>" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label><strong>UF</strong></label>
                        <select name="uf" class="form-control input-lg j_loadstate">
                            <option value="" selected> Selecione </option>
                            <?php
                            $readState = new Read;
                            $readState->ExeRead("estados", "ORDER BY estado_nome ASC");
                            foreach ($readState->getResult() as $estado):
                                extract($estado);
                                echo "<option value=\"{$estado_id}\" ";
                                if (isset($post['uf']) && $post['uf'] == $estado_id): echo 'selected';
                                endif;
                                echo "> {$estado_uf} </option>";
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <label><strong>Cidade</strong></label>
                        <select class="form-control input-lg j_loadcity" name="cidade">
                                <?php if (!isset($post['cidade'])): ?>
                                <option value="" selected disabled> Selecione antes um estado </option>
                                    <?php
                                else:
                                    $City = new Read;
                                    $City->ExeRead("cidades", "WHERE estado_id = :uf ORDER BY cidade_nome ASC", "uf={$post['uf']}");
                                    if ($City->getRowCount()):
                                        foreach ($City->getResult() as $cidade1):
                                            extract($cidade1);
                                            echo "<option value=\"{$cidade_id}\" ";
                                            if (isset($post['cidade']) && $post['cidade'] == $cidade_id):
                                                echo "selected";
                                            endif;
                                            echo "> {$cidade_nome} </option>";
                                        endforeach;
                                    endif;
                                endif;
                                ?>
                        </select>
                    </div>
                </div>
                
                <div class="row"> 
                    <div class="col-md-5 form-group">
                        <label><strong>Bairro</strong></label>
                        <input type="text" class="form-control input-lg" name="bairro" value="<?php if(!empty($post['bairro'])) echo $post['bairro'];?>" />
                    </div>                        
                    <div class="col-md-2 form-group">
                        <label><strong>Número</strong></label>
                        <input type="text" class="form-control input-lg" name="num" value="<?php if(!empty($post['num'])) echo $post['num'];?>" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label><strong>Cep</strong></label>
                        <input type="text" class="form-control input-lg" data-mask="99.999-999" name="cep" value="<?php if(!empty($post['cep'])) echo $post['cep'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>Complemento</strong></label>
                        <input type="text" class="form-control input-lg" name="complemento" value="<?php if(!empty($post['complemento'])) echo $post['complemento'];?>" />
                    </div>                        
                </div>
                
                <hr />  
                        
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label><strong>Telefone fixo</strong></label>
                        <input type="text" class="form-control input-lg" data-mask="(99) 9999-9999" name="telefone1" value="<?php if(!empty($post['telefone1'])) echo $post['telefone1'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>Telefone móvel</strong></label>
                        <input type="text" class="form-control input-lg" data-mask="(99) 99999-9999" name="telefone2" value="<?php if(!empty($post['telefone2'])) echo $post['telefone2'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>WhatsApp</strong></label>
                        <input type="text" class="form-control input-lg" data-mask="(99) 99999-9999" name="whatsapp" value="<?php if(!empty($post['whatsapp'])) echo $post['whatsapp'];?>" />
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>Skype</strong></label>
                        <input type="text" class="form-control input-lg" name="skype" value="<?php if(!empty($post['skype'])) echo $post['skype'];?>" />
                    </div>                            
                </div>
                
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label><strong>Descrição</strong></label>
                        <textarea class="form-control editor" name="descricao" rows="6"><?php if(!empty($post['descricao'])) echo htmlspecialchars($post['descricao']);?></textarea>                	    
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label><strong>Notas</strong></label>
                        <textarea class="form-control" name="notas"><?php if(!empty($post['notas'])) echo htmlspecialchars($post['notas']);?></textarea>                	    
                    </div>
                </div>
                
                <div class="clear"></div>                
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label><strong>&nbsp;</strong></label>
                        <button type="submit" style="width:100%;" class="btn btn-info btn-lg" name="SendPostForm" value="Atualizar">Atualizar</button>
                    </div>
                    <div class="col-md-3 form-group">
                        <label><strong>&nbsp;</strong></label>
                        <button type="submit" style="width:100%;" class="btn btn-success btn-lg" name="SendPostForm" value="Atualizar & Publicar">Atualizar & Publicar</button>
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

