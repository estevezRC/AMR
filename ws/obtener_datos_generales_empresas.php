<?php

require 'ConsultasGeneral.php';

$tabla=$_GET['tabla'];
$modulo=$_GET['modulo'];
$fecha = $_GET['fecha'];

$usuario = $_GET['usuario'];
$idReporte = $_GET['idReporte'];
$serie = $_GET['serie'];

$idUsuario = $_GET['idUsuario'];
$idEmpresa = $_GET['idEmpresa'];

$fecha_sinc = str_replace("_"," ",$fecha);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    switch ($tabla) {
        case "usuarios":
            $consulta = ConsultasGeneral::getUsuarios();
            break;
        case "dispositivos":
            $consulta = ConsultasGeneral::buscaDispositivoSerie($serie,true);
            break;
        case "datos":
            $consulta = ConsultasGeneral::compruebaDatosUsuario('ljuarez@getitcompany.com','Pandita#2');
            break;
        case "datosUsuario":
            $consulta = ConsultasGeneral::datosUsuario($idUsuario,$idEmpresa);
            break;
    }

    //print_r($consulta);
        // Manejar peticion GET
      //  $comando = Consultas::getCatReportes();

        if ($consulta) {
            $datos["estado"] = 1;
            $datos["datos"] = $consulta;
            print json_encode($datos,JSON_UNESCAPED_UNICODE);
        } else {
            //print $usuarios;
            print json_encode(array(
                "estado" => 2,
                "mensaje" => "Ha ocurrido un error  ".$tabla
            ));
        }
}

?>