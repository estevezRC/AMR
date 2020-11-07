<?php

require_once 'ConsultasCron.php';

$fecha_actual = date("Y-m-d");
$fechaAnterior = date("Y-m-d", strtotime($fecha_actual . "- 1 days"));

reportesByTramo($fechaAnterior);
reportesByTipos($fechaAnterior);

function reportesByTipos($fechaAnterior) {
    $contadorReportesByReportes = ConsultasCron::contadorReportesByReporte($fechaAnterior);
    $texto = "";
    $totalReportes = 0;
    $cont = 0;
    $textoFinal = '';
    foreach ($contadorReportesByReportes as $contReportes){
        $nombreProyecto = $contReportes['nombre'];
        $total = $contReportes['total'];

        $reportes = $contReportes['Reportes'];
        $incidencias = $contReportes['Incidencias'];
        $ubicaciones = $contReportes['Ubicaciones'];
        $inventarios = $contReportes['Inventarios'];

        if($total > 0) {
            $texto .= "<strong>".$nombreProyecto."</strong>"." - Total = ".$total."\n Reportes = ". $reportes .
                "\n Incidencias = ". $incidencias ."\n Ubicaciones = " . $ubicaciones . "\n Inventarios = ". $inventarios ."\n \n";
            $totalReportes += $total;
            $cont++;
        }
    }

    if ($cont == 0) {
        $textoFinal = "El dia $fechaAnterior <strong>No se han registrado reportes</strong>";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    } else {
        $textoFinal = "El dia $fechaAnterior se registraron $totalReportes nuevos reportes \n $texto";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    }
    ConsultasCron::sendMessageTelegram(1186730512, $textoFinal);
    ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
}

function reportesByTramo($fechaAnterior) {
    $contadorReportesByTramo = ConsultasCron::contadorReportes($fechaAnterior);
    $texto = "";
    $totalReportes = 0;
    $cont = 0;
    $textoFinal = "";
    foreach ($contadorReportesByTramo as $contReportes){
        $nombreProyecto = $contReportes['nombreProyecto'];
        $total = $contReportes['total'];
        if($total > 0) {
            $texto .="<strong>".$nombreProyecto."</strong>"." = ".$total."\n ";
            $totalReportes += $total;
            $cont++;
        }
    }


    if ($cont == 0) {
        $textoFinal = "El dia $fechaAnterior <strong>No se han registrado reportes</strong>";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    } else {
        $textoFinal = "El dia $fechaAnterior se registraron $totalReportes nuevos reportes \n $texto";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    }
    ConsultasCron::sendMessageTelegram(1186730512, $textoFinal);
    ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
    ConsultasCron::sendMessageTelegram(298180244, $textoFinal);
    //ConsultasCron::sendMessageTelegram(1108981510, $textoFinal);
}








//ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
//ConsultasCron::sendMessageTelegram(298180244, $textoFinal);
//ConsultasCron::sendMessageTelegram(1186730512, $textoFinal);
