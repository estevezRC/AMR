<?php
require 'Consultas.php';

$obtenerReportes = Consultas::obtenerIdsTipoReporte(46);
//$obtenerValores = Consultas::obtenerValoresConfiguracion(189);


foreach ($obtenerReportes as $ids){
    //echo $ids['gpo']."<br/>";

    //Consultas::insertaReporteNuevo($ids['gpo'], '');
    $valor = Consultas::VerificaExisteCampo($ids['gpo'],440);
    echo $ids['gpo']."--".$valor;

    if($valor==0){
        echo "inserta<br/>";
        //Consultas::insertaReporteNuevo($ids['gpo'], 'NA',440);
    }else{
        echo "no inserta<br/>";
    }
}

?>

