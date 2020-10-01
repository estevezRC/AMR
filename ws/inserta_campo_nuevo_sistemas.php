<?php

//require 'ConsultasGeos.php';
require 'Consultas.php';

/*$idSistema = 9;
$idConf = 1315;

$gpoSistemas = ConsultasGeos::obtenerIdsTipoSistema($idSistema);

foreach ($gpoSistemas as $gpo){

    $idGpo = $gpo['Id_Gpo'];
    $existe = ConsultasGeos::verificaExisteCampo($idGpo,$idConf);


    if($existe=="0"){
        $inserta = "inserta";
        ConsultasGeos::insertaValorCampoNuevo($idGpo,"0",$idConf);
    }else{
        $inserta = "no inserta";
    }

    echo $gpo['Id_Gpo']."---".$existe."---".$inserta."</br>";
}*/

//$gpoSeguimientos = Consultas::obtenerIdsTipoReporte(27);
$gpoSeguimientos = Consultas::obtenerIdsTipoReportesSeguimiento(3,2);

foreach ($gpoSeguimientos as $idsGpos){
    $idGpo = $idsGpos['gpo'];
    $fecha = $idsGpos['fecha'];

    $existeSeg = Consultas::getExisteSeguimiento($idGpo,73);

    if($existeSeg){
        echo $idGpo." ".$fecha." no inserta<br/>";
    }else{
        echo $idGpo." ".$fecha." inserta<br/>";
        Consultas::insertaSeguimientoReportes($idGpo,73,6,$fecha);
    }
}

?>