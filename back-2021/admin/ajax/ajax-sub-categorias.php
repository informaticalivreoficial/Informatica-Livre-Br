<?php

require('../../vendor/autoload.php');
require('../../app/config.inc.php');

$Categoria = (int) strip_tags(trim($_POST['cat_pai']));
$SubCategoria = new Read;
$SubCategoria->ExeRead("cat_anuncios", "WHERE id_pai = :idpai", "idpai={$Categoria}");

sleep(1);

echo "<option value=\"\" disabled selected> Selecione a SubCategoria </option>";
foreach ($SubCategoria->getResult() as $catsub1):
    extract($catsub1);
    echo "<option value=\"{$id}\"> {$nome} </option>";
endforeach;