<?php
    if(empty($login)):
        header('Location: painel.php');
        die;
    endif;
    
    $user = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $userId = filter_input(INPUT_GET, 'userid', FILTER_VALIDATE_INT);
    
    $ReadUser = new Read;
    $ReadUser->ExeRead("usuario", "WHERE id = :userid", "userid={$userId}");
    if ($ReadUser->getResult()):
    foreach ($ReadUser->getResult() as $user);
        extract($user);
    endif;    
?>
<div class="wrapper">
    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="profile-pic text-center">
                                <?php
                                if($avatar != null):
                                    echo Check::Image('../uploads/' . $avatar, $nome, '150', '150');
                                else:
                                    echo "<img src=\"images/image.jpg\" title=\"{$nome}\" />";
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-body">
                            <ul class="p-info">
                                <li>
                                    <div class="title">E-mail:</div>
                                    <div class="desk"><?= $email;?></div>
                                </li>
                                <li>
                                    <div class="title">WhatsApp:</div>
                                    <div class="desk"><?= $whatsapp;?></div>
                                </li>
                                <li>
                                    <div class="title">Celular:</div>
                                    <div class="desk"><?= $telefone2;?></div>
                                </li>
                                <li>
                                    <div class="title">Cidade/UF:</div>
                                    <div class="desk"><?= Check::getCidade($cidade, 'cidade_nome');?>/<?= Check::getUf($uf, 'estado_uf');?></div>
                                </li>
                                <li>
                                    <div class="title">Atualizado:</div>
                                    <div class="desk"><?= date('d/m/Y', strtotime($uppdate));?></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="profile-desk">
                                <h1><?= $nome;?></h1>
                                <span class="designation"><?= Check::getUser('usuario', $nivel, 'nivel');?></span>                                
                                    <?= $descricao;?>                                
                                <a class="btn p-follow-btn pull-left" href="painel.php?exe=usuarios/usuarios-edit&userid=<?= $id;?>"> <i class="fa fa-pencil"></i> Editar</a>

                                <ul class="p-social-link pull-right">
                                    <?php
                                    if($facebook):
                                        echo '<li><a target="_blank" href="'.$facebook.'"><i class="fa fa-facebook"></i></a></li> ';
                                    endif;
                                    if($twitter):
                                        echo '<li><a target="_blank" href="'.$twitter.'"><i class="fa fa-twitter"></i></a></li> ';
                                    endif;
                                    if($instagram):
                                        echo '<li><a target="_blank" href="'.$instagram.'"><i class="fa fa-instagram"></i></a></li> ';
                                    endif;
                                    if($youtube):
                                        echo '<li><a target="_blank" href="'.$youtube.'"><i class="fa fa-youtube"></i></a></li> ';
                                    endif;
                                    if($linkedin):
                                        echo '<li><a target="_blank" href="'.$linkedin.'"><i class="fa fa-linkedin"></i></a></li> ';
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
