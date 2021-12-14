<script type="text/javascript">
    jQuery(document).ready(function(){
        
        //VARIÁVEIS GERAIS
        var ajaxbairro = 'ajax/bairros.php';
        var url = 'ajax/ajax.php';

        $('.j_loadstate').change(function() {
        var uf = $('.j_loadstate');
        var city = $('.j_loadcity');
        var patch = ($('#j_ajaxident').length ? $('#j_ajaxident').attr('class') + '/cidades.php' : 'ajax/cidades.php');

        city.attr('disabled', 'true');
        uf.attr('disabled', 'true');

        city.html('<option value=""> Carregando cidades... </option>');

        $.post(patch, {estado: $(this).val()}, function(cityes) {   
            city.html(cityes).removeAttr('disabled');
            uf.removeAttr('disabled');
        });
    });
    
    $('.j_loadcity').change(function() {
        var bairro = $('.j_loadbairros');
        var patch = ($('#j_ajaxident').length ? $('#j_ajaxident').attr('class') + '/bairros.php' : 'ajax/bairros.php');

        bairro.attr('disabled', 'true');
        
        bairro.html('<option value=""> Carregando bairros... </option>');

        $.post(patch, {bairro: $(this).val()}, function(cityes) {   
            bairro.html(cityes).removeAttr('disabled');
        });
    });
    
        
    $('.j_loadcity').change(function() {
        $('.j_loadbairros').removeAttr('disabled');
        $('.j_loadbairros').html('<option value=""> Carregando... </option>');
        $('.j_loadbairros').load(ajaxbairro + '?bairro=' + $(this).val());
    }); 
    
    // FUNÇÃO FILTRO CLIENTES
        $('.j_alfabeto').click(function() {
            var id_task = $(this).attr('data-id');
            var url = 'ajax/filtro-clientes.php';

            $.ajax({
                url: url,
                type: 'POST',
                data: {id:id_task},

                beforeSend: function(){
                    $('.hideR').remove();
                },

                success: function(callback){
                    $('.resultado').html(callback);
                }
           });        
        });
        
        // FUNÇÃO CARREGA SUBCATEGORIAS
        $('.j_loadcat').change(function() {
            var cat    = $('.j_loadcat');
            var subcat = $('.j_loadsubcat');
            var ajaxdata   = 'ajax/ajax-sub-categorias.php';

            subcat.attr('disabled', 'true');
            cat.attr('disabled', 'true');

            subcat.html('<option value=""> Carregando... </option>');

            $.post(ajaxdata, {cat_pai: $(this).val()}, function(subcats) {   
                subcat.html(subcats).removeAttr('disabled');
                cat.removeAttr('disabled');
            });
        });
        
        $('.j_modal_btn').click(function() {
            var extra = $(this).data('extra');
            var campo = $(this).data('campo');
            var option = $(this).data('option');
            var id = $(this).data('id');
            var email = $(this).data('email');
            var patch = $('#j_ajaxident').attr('class');

            $('.j_param_data').text(campo);
            $('a.delete-yes').attr('href', patch + id + extra + '&action='+ option + '<?= $varPage;?>');

            $('#ModalRemove').modal();
        });
        
        //MONITORA MODAL VISUALIZAR
        $('.j_modal_view').click(function() {
            var id = $(this).data('id');
            var email = $(this).data('email');
            var nome = $(this).data('nome');
            var cadastrado = $(this).data('cadastrado');
            var atualizado = $(this).data('atualizado');
            var lista = $(this).data('lista');
            var envios = $(this).data('envios');


            $('.j_param_view_email').text(email);
            $('.j_param_view_nome').text(nome);
            $('.j_param_view_cadastrado').text(cadastrado);
            $('.j_param_view_atualizado').text(atualizado);
            $('.j_param_view_lista').text(lista);
            $('.j_param_view_envios').text(envios);

            $('#ModalVisualiza').modal();
        });
        
        //SHADOWBOX
        Shadowbox.init();

    });
</script>

<?php
    $read = new Read;
    
    //VISITAS DO SITE
    
    //Mês Atual
    $MesAtual = date('m');
    $MesAtualPrint = date('m/Y');
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$MesAtual'");
    $VisitasAtual = $read->getResult()[0]['views'];
    $UsuariosAtual = $read->getResult()[0]['usuarios'];    
    
    // - 1 Mês
    $Mes1 = date('m', strtotime('-1months'));
    $Mes1Print = date('m/Y', strtotime('-1months'));
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$Mes1'");
    $Visitas1 = ($read->getResult()[0]['views'] ? $read->getResult()[0]['views'] : '0');
    $Usuarios1 = ($read->getResult()[0]['usuarios'] ? $read->getResult()[0]['usuarios'] : '0');
    
    // - 2 Mês
    $Mes2 = date('m', strtotime('-2months'));
    $Mes2Print = date('m/Y', strtotime('-2months'));
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$Mes2'");
    $Visitas2 = ($read->getResult()[0]['views'] ? $read->getResult()[0]['views'] : '0');
    $Usuarios2 = ($read->getResult()[0]['usuarios'] ? $read->getResult()[0]['usuarios'] : '0');
    
    // - 3 Mês
    $Mes3 = date('m', strtotime('-3months'));
    $Mes3Print = date('m/Y', strtotime('-3months'));
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$Mes3'");
    $Visitas3 = ($read->getResult()[0]['views'] ? $read->getResult()[0]['views'] : '0');
    $Usuarios3 = ($read->getResult()[0]['usuarios'] ? $read->getResult()[0]['usuarios'] : '0');
    
    // - 4 Mês
    $Mes4 = date('m', strtotime('-4months'));
    $Mes4Print = date('m/Y', strtotime('-4months'));
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$Mes4'");
    $Visitas4 = ($read->getResult()[0]['views'] ? $read->getResult()[0]['views'] : '0');
    $Usuarios4 = ($read->getResult()[0]['usuarios'] ? $read->getResult()[0]['usuarios'] : '0');
    
    // - 5 Mês
    $Mes5 = date('m', strtotime('-5months'));
    $Mes5Print = date('m/Y', strtotime('-5months'));
    $read->FullRead("SELECT SUM(views) AS views, SUM(usuarios) AS usuarios FROM siteviews  WHERE MONTH(data) = '$Mes5'");
    $Visitas5 = ($read->getResult()[0]['views'] ? $read->getResult()[0]['views'] : '0');
    $Usuarios5 = ($read->getResult()[0]['usuarios'] ? $read->getResult()[0]['usuarios'] : '0');
?>
<script type="text/javascript">
    Morris.Bar({
        element: 'visitantes',
        data: [
            {x: '<?= $Mes5Print;?>', y: <?= $Usuarios5;?>, z: <?= $Visitas5;?>},
            {x: '<?= $Mes4Print;?>', y: <?= $Usuarios4;?>, z: <?= $Visitas4;?>},
            {x: '<?= $Mes3Print;?>', y: <?= $Usuarios3;?>, z: <?= $Visitas3;?>},
            {x: '<?= $Mes2Print;?>', y: <?= $Usuarios2;?>, z: <?= $Visitas2;?>},
            {x: '<?= $Mes1Print;?>', y: <?= $Usuarios1;?>, z: <?= $Visitas1;?>},
            {x: '<?= $MesAtualPrint;?>', y: <?= $UsuariosAtual;?>, z: <?= $VisitasAtual;?>}
        ],
        xkey: 'x',
        ykeys: ['y', 'z'],
        labels: ['Visitantes', 'Visitas'],
        barColors:['#414e62','#788ba0']

    });
</script> 
