<?php
$listaId = filter_input(INPUT_GET, 'lista', FILTER_VALIDATE_INT);
$readLista = new Read;
$readLista->ExeRead("newsletter_cat", "WHERE id = :ListaId","ListaId={$listaId}");
if($readLista->getResult()):
    $lista = $readLista->getResult()['0'];
endif;

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="lista-'.Check::Name($lista['titulo']).'-'.Check::Name(SITENAME).'.xls"');
header("Pragma: no-cache");
header("Expires: 0");

$export = new Read;   
$export->FullRead("SELECT email, nome, sobrenome FROM newsletter WHERE cat_id = '$listaId' AND status = '1'");
if($export->getResult()):
    ob_get_clean();
    $output = fopen('php://output', 'w');
    $header = true;    
    foreach($export->getResult() as $row):
        $row['sobrenome'] = check::getSobreNome($row['nome']);
        $row['nome'] = check::getPrimeiroNome($row['nome']);        
        if($header):
            fputcsv($output, $row);
            $header = false;
        endif;
        fputcsv($output, $row);        
    endforeach;    
    fclose($output);
    die;
endif;