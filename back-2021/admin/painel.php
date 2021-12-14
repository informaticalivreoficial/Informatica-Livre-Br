<?php
ob_start();
session_start();

require('../vendor/autoload.php');
require('../app/config.inc.php');

$login = new Login(1);
$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
$getexe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

if (!$login->CheckLogin()):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=restrito');
    exit;
else:
    $userlogin = $_SESSION['userlogin'];
endif;

if ($logoff):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=logoff');
endif;
?>
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8"/>
        <title>Painel Administrativo - <?= SITENAME;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
        <meta name="title" content="Painel Administrativo - <?php echo SITENAME;?>" />
        <meta name="description" content="Área restrita aos administradores do site <?php echo SITENAME;?>" />
        <meta name="keywords" content="Login, Recuperar Senha, <?php echo SITENAME;?>" />
        <meta name="author" content="<?php echo DESENVOLVEDOR;?>" />   
        <meta name="url" content="<?php echo DESENVOLVEDORURL;?>" />
        <meta name="robots" content="NOINDEX,NOFOLLOW" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/chave.png" />        
            
        <link href="js/iCheck/skins/flat/green.css" rel="stylesheet"/>
        <link href="css/toastr.css" rel="stylesheet"/>
            
      <!--dashboard calendar-->
      <link href="css/clndr.css" rel="stylesheet"/>
    
      <!--Morris Chart CSS -->
      <link rel="stylesheet" href="js/morris-chart/morris.css"/>
      
      <!--file upload-->
      <link rel="stylesheet" type="text/css" href="css/bootstrap-fileupload.min.css" />
      
      <!--pickers css-->
      <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker-custom.css" />
      <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/css/timepicker.css" />
      
      <!--tags input-->
      <link rel="stylesheet" type="text/css" href="js/jquery-tags-input/jquery.tagsinput.css" />
      
      <!--dropzone css-->
      <link href="js/dropzone/css/dropzone.css" rel="stylesheet"/>
      
      <link rel="stylesheet" type="text/css" href="js/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
      
      <!--dynamic table-->
      <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
      <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
      <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
      
      <!--common-->
      <link href="css/style.css" rel="stylesheet"/>
      <link href="css/renato.css" rel="stylesheet"/>
      <link href="css/print.css" rel="stylesheet"/>
      <link href="css/style-responsive.css" rel="stylesheet"/>
           
    
      <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <![endif]-->

    </head>
<body class="sticky-header">

<!--MODAL DE CARREGAMENTO DO SISTEMA-->
<div class="dialog">
    <div class="loadsistem">
        <img src="images/loading.gif" width="32" height="32" alt="Carregando" title="Carregando" />
    	<p>Carregando Sistema!</p>
    </div>
</div>

<section>

<!-- Sidebar -->
<div class="left-side sticky-left-side hidden-print">
<?php require_once('include/menu.php');?>
</div>
<!-- Sidebar Fim -->

<!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section hidden-print">
        <?php require_once('include/header.php');?>
        </div>
        <!-- header section end-->
        
        <?php
            //QUERY STRING
            if (!empty($getexe)):
                $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'componentes' . DIRECTORY_SEPARATOR . strip_tags(trim($getexe) . '.php');
            else:
                $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'componentes' . DIRECTORY_SEPARATOR . 'home.php';
            endif;

            if (file_exists($includepatch)):
                require_once($includepatch);
            else:
                echo "<div class=\"content notfound\">";
                RMErro("<b>Erro ao incluir tela:</b> Erro ao incluir o controller /{$getexe}.php!", RM_ERROR);
                echo "</div>";
            endif;
            ?>
        
        <!--footer section start-->
        <footer>
            <?php require_once('include/footer.php');?> 
        </footer>
        <!--footer section end-->
    </div>
    <!-- main content end-->


</section>


  

<!-- Placed js at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>

<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>


<!--icheck -->
<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>



<!--file upload-->
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>

<!--Dashboard Charts-->
<script src="js/dashboard-chart-init.js"></script> 

<!--pickers plugins-->
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--pickers initialization-->
<script src="js/pickers-init.js"></script>

<!--tags input-->
<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>
<script src="js/tagsinput-init.js"></script>

<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<!--dropzone-->
<script src="js/dropzone/dropzone.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<!--bootstrap input mask EM USO-->
<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<!--Validator Passowrd-->
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script src="js/validation-init.js"></script>

<script src="js/toastr.js"></script>

<!--Morris Chart EM USO-->
<script src="js/morris-chart/morris.js"></script>
<script src="js/morris-chart/raphael-min.js"></script>
<script src="js/morris-chart/morris.init.js"></script>

<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

<!--common scripts for all pages-->
<script src="js/jquery.form.js"></script>
<script src="js/scripts.js"></script>
<script src="js/funcoes.js"></script>


<script>
    jQuery(document).ready(function(){
        $('.wysihtml5').wysihtml5();
    });
</script>


<!-- shadowbox EM USO -->
<script type="text/javascript" src="js/shadowbox/shadowbox.js"></script>
<link rel="stylesheet" type="text/css" href="js/shadowbox/shadowbox.css"/>


<?php 
    //require_once('js/funcoes.php');
    require_once('include/head-js.inc.php');
    require_once('include/funcoes.inc.php');
?>   
</body>
</html>
<?php
ob_end_flush();
