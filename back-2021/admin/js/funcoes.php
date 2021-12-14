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