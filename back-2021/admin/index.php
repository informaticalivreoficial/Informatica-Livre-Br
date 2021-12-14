<?php
ob_start();
session_start();

require('../vendor/autoload.php');
require('../app/config.inc.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="description" content="Área restrita aos administradores do site <?= SITENAME;?>" />
    <meta name="author" content="<?= DESENVOLVEDOR;?>" />
    <meta name="url" content="<?= DESENVOLVEDORURL;?>" />
    <meta name="keywords" content="Login, Recuperar Senha, <?= SITENAME;?>" />

    <title>Painel Administrativo - <?= SITENAME;?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/chave.png" />

	<link href="css/style.css" rel="stylesheet"/>
    <link href="css/style-responsive.css" rel="stylesheet"/>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
    <body class="login-body">

<div class="container">
    
    <form class="form-signin" action="" method="post" name="AdminLoginForm">
        <div class="form-signin-heading text-center">
            <img src="../uploads/<?php echo LOGOMARCAADMIN;?>" alt="Área administrativa" title="Área administrativa"/>
        </div>

        <div class="login-wrap">
        <?php
            $login = new Login(1);

            if ($login->CheckLogin()):
                header('Location: painel.php');
            endif;

            $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if (!empty($dataLogin['AdminLogin'])):

                $login->ExeLogin($dataLogin);
                if (!$login->getResult()):
                    RMErro($login->getError()[0], $login->getError()[1]);
                else:
                    header('Location: painel.php');
                endif;

            endif;

            $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
            if (!empty($get)):
                if ($get == 'restrito'):
                    RMErro('<b>Acesso negado!</b>. Favor efetue login para acessar o painel!', RM_ALERT);
                elseif ($get == 'logoff'):
                    RMErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte sempre!', RM_ACCEPT);
                endif;
            endif;
         ?>
            <input type="text" name="user" class="form-control" placeholder="E-mail"/>
            <input type="password" name="pass" class="form-control" placeholder="Senha"/>

            <input class="btn btn-lg btn-login btn-block" type="submit" name="AdminLogin" value="Entrar" class="btn blue" />
            

            
            <label class="checkbox">
                <input type="checkbox" name="remember" value="1"/> Lembrar
                <span class="pull-right">
                    <a data-toggle="modal" href="javascript:;" data-target="#recuperarSenha"> Esqueceu sua Senha?</a>
                </span>
            </label>

        </div>
</form>
 

</div>

<!-- Modal -->
<div id="recuperarSenha" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Esqueceu a Senha?</h4>
            </div>
            <form method="post" action="" class="j_recuperasenha">
            <div class="modal-body">                
                <div class="alertas"></div>
                <p class="phidenn">Informe seu e-mail para recuperar sua senha.</p>
                <input name="action" value="recuperar_senha" type="hidden" class="noclear"/>
                <!-- HONEYPOT -->
                <input type="hidden" class="noclear" name="bairro" value="" />
                <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                <input type="text" name="email" placeholder="E-mail" autocomplete="off" class="form-control placeholder-no-fix form_hide">                
            </div>
            <div class="modal-footer form_hide">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
                <button class="btn btn-primary b_nome" type="submit">Recuperar Senha</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- modal -->        
    
<!-- JS -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/funcoes.js"></script>
</body>
</html>
<?php
ob_end_flush();
?>
