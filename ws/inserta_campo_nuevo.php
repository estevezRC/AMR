<?php

require 'Consultas.php';

$obtenerReportes = Consultas::obtenerIdsTipoReporte(45);

foreach ($obtenerReportes as $ids){
    $valor = Consultas::VerificaExisteCampo($ids['gpo'],551);
    echo $ids['gpo']."--".$valor."---".$ids['fecha']."   ";

    if($valor==0){
        echo "inserta<br/>";
        //Consultas::insertaReporteNuevo($ids['gpo'], 'NA',440);
    }else{
        echo "no inserta<br/>";
    }
}
?>