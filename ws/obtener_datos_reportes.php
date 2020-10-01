<?php

require 'Consultas.php';
//require_once '../core/FuncionesCompartidas.php';

$tabla=$_GET['tabla'];
$fecha = $_GET['fecha'];
$modulo = $_GET['modulo'];
$area = $_GET['area'];
$idGpoReporte = $_GET['idGpo'];
$idUsuario = $_GET['usuario'];

$palabra = $_GET['palabra'];
$modulo = $_GET['modulo'];
$idProyecto = $_GET['proyecto'];

$fechas = $_GET['fechas'];
$cadReportes = $_GET['cadReportes'];

$idNodo = $_GET['nodo'];
$idGantt = $_GET['gantt'];

$idEmpresa = $_GET['idEmpresa'];
session_start();
$_SESSION['id_empresa_movil'] = $idEmpresa;

$fecha_sinc = str_replace("_"," ",$fecha);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $idsReportes =  Consultas::obtenerUltimosReportes($area,$empresa,$idUsuario);
    switch ($tabla) {
        case "catReportes":
            $consulta = Consultas::getCatReportes($idUsuario);
            break;
        case "catCamposReportes":
            $consulta = Consultas::getCatCamposReportes();
            break;
        case "confCampos":
            $consulta = Consultas::getConfCamposReportes($idUsuario);
            break;
        case "repLlenados":
            $consulta = Consultas::getReportesLlenadosFechaEmpesa($fecha_sinc,$area,$idUsuario);
            break;
        case "valoresReportes":
            $consulta = Consultas::getValoresReportesFechaEmpresa($fecha_sinc,$area,$idsReportes,$idUsuario);
            break;
        case "seguimiento_reportes":
            $consulta = Consultas::getSeguimientoReportesFechaEmpresa($fecha_sinc,$area,$idsReportes);
            break;
        case "fotografias":
            $consulta = Consultas::getFotografiasFechaEmpresa($modulo,$fecha_sinc,$area,$idsReportes,$idUsuario);
            break;
        case "valoresReporte":
            //echo $idGpoReporte;
            $consulta = Consultas::getValoresReporteGpo($idGpoReporte);
            break;
        case "imagenesReporte":
            //echo $idGpoReporte;
            $consulta = Consultas::getImagenesReporteGpo($idGpoReporte);
            break;
        case "obtenerReportes":
            $consulta = Consultas::getReportesLllenadosBusqueda($palabra,$modulo,$area,$idProyecto,$fechas,$cadReportes);
            //Funciones::sendMessage(262453015,"Supervisor_DESARROLLO ReporteBusqueda: $idProyecto \n palabra: $palabra");
            break;
        case "detallesReporte":
            //echo $idGpoReporte;
            $consulta = Consultas::getDetallesReporteGpo($idGpoReporte);
            break;
        case "actividadAvances":
            $consulta = Consultas::getActividadAvance($fecha_sinc);
            //Funciones::sendMessage(262453015,"Supervisor_DESARROLLO AvanceCatividad: $idProyecto \n fecha: $fecha_sinc");
            break;
        case "obtenerGpoNodo":
            //echo "ok";
            $consulta = Consultas::getGpoActividadAvance($idNodo,$idProyecto);
            break;
    }

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