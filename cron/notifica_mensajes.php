<?php

require_once 'ConsultasCron.php';

$fecha_actual = date("Y-m-d");
$fechaAnterior = date("Y-m-d", strtotime($fecha_actual . "- 1 days"));

$fecha = new DateTime($fechaAnterior);
$fechaFormat = $fecha->format('d/m/Y');
$fechaFormat = date("d/m/Y",strtotime($fechaAnterior));

$textoFinal = "<strong>AMERICAS RESOURCES</strong>";

reportesByTramo($fechaAnterior,$textoFinal,$fechaFormat);
//reportesByTipos($fechaAnterior);

function reportesByTipos($fechaAnterior,$textoFinal) {
    $contadorReportesByReportes = ConsultasCron::contadorReportesByReporte($fechaAnterior);
    $texto = "";
    $totalReportes = 0;
    $cont = 0;
    //$textoFinal = '';
    foreach ($contadorReportesByReportes as $contReportes){
        $nombreProyecto = $contReportes['nombre'];
        $total = $contReportes['total'];

        $reportes = $contReportes['Reportes'];
        $incidencias = $contReportes['Incidencias'];
        $ubicaciones = $contReportes['Ubicaciones'];
        $inventarios = $contReportes['Inventarios'];

        if($total > 0) {
            $texto .= "<strong>".$nombreProyecto."</strong>"." - Total = ".$total. "\n \n ";
            $totalReportes += $total;
            $cont++;
        }
    }

    $txtTotalregistro = "\n\n Se ingresaron " . $totalReportes . " nuevos registros \n";

    if ($cont == 0) {
        $textoFinal .= "\n $texto";//"El dia $fechaAnterior <strong>No se han registrado reportes</strong>";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    } else {
        $textoFinal .= "<strong>".$txtTotalregistro."</strong>" ."\n $texto";//El dia $fechaAnterior se registraron $totalReportes nuevos reportes \n $texto";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    }

    //echo $textoFinal;
    ConsultasCron::sendMessageTelegram(1186730512, $textoFinal); //ROBERTO TELEGRAM
    ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
    ConsultasCron::sendMessageTelegram(298180244, $textoFinal);
    ConsultasCron::sendMessageTelegram(1108981510, $textoFinal);
}

function reportesByTramo($fechaAnterior,$textoFinal,$fechaFormat) {
    $contadorReportesByTramo = ConsultasCron::contadorReportes($fechaAnterior);
    $texto = "";
    $totalReportes = 0;
    $cont = 0;
    //$textoFinal = "";
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
        $textoFinal .= "\nEl dia $fechaFormat <strong>No se insertaron nuevos registros</strong>";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    } else {
        $textoFinal .= "\nEl dia $fechaFormat se insertaron $totalReportes nuevos reportes \n $texto";
        //$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportesByReportes . ' nuevos reportes';
    }

    reportesByTipos($fechaAnterior,$textoFinal);

    //ConsultasCron::sendMessageTelegram(1186730512, $textoFinal);
    //ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
    //ConsultasCron::sendMessageTelegram(298180244, $textoFinal);
    //ConsultasCron::sendMessageTelegram(1108981510, $textoFinal);
}








//ConsultasCron::sendMessageTelegram(262453015, $textoFinal);
//ConsultasCron::sendMessageTelegram(298180244, $textoFinal);
//ConsultasCron::sendMessageTelegram(1186730512, $textoFinal);
