<?php

require 'Consultas.php';

require_once '../config/global.php';
require_once '../core/EntidadBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/MatrizComunicacion.php';
require_once '../model/Fotografia.php';
require_once '../model/Campo.php';
require_once '../model/Notificaciones.php';
require_once '../vendor/autoload.php';
require_once '../model/Proyecto.php';
require_once '../model/ProcesosAvances.php';
require_once '../model/Procesos.php';
require_once '../model/AvanceActividad.php';
require_once '../model/Gantt.php';
require_once '../model/Asistencia.php';

$tabla=$_GET['tabla'];
$modulo=$_GET['modulo'];
$fecha = $_GET['fecha'];

$fecha_sinc = str_replace("_"," ",$fecha);

$usuario = $_GET['usuario'];
$idReporte = $_GET['idReporte'];
$version = $_GET['version'];

$idEmpresa = $_GET['idEmpresa'];

session_start();
$_SESSION['id_empresa_movil'] = $idEmpresa;

//echo $idEmpresa;

$fecha_sinc = str_replace("_"," ",$fecha);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $funcion = new FuncionesCompartidas();

    switch ($tabla) {
        case "areas_empresas":
            $consulta = Consultas::getAreasEmpresas();
            break;
        case "usuarios":
            $consulta = Consultas::getUsuarios();
            break;
        case "fotografias":
            $consulta = Consultas::getFotografias($modulo);
            break;
        case "dispositivos":
            $consulta = Consultas::getDispositivos();
            break;
        case "clasificaFotos":
            $consulta = Consultas::getCatClasificacionFotografias();
            break;
        case "versionApp":
            $usuarioActivo = Consultas::getUsuarioActivo($usuario);
            $arreglo = array('version' => '1.0.6','versionCode' => 7,'usuerActive' => $usuarioActivo, 'canceable' => false);
            $funcion->sendMessageTelegram(262453015,"SupervisorAmr version: $version \nidUsuario:$usuario");
            $consulta = array($arreglo);
            break;
        case "catMonitoreoDiario":
            $consulta = Consultas::getCatMonitoreoDiario();
            break;
        case "catalogoCategoria":
            $consulta = Consultas::getCatalogoCategoria();
            break;
        case "countNotificacionesUser":
            //echo "ok";
            $consulta = Consultas::getCountNotificacionesUser($usuario);
            break;
        case "notificacionesUser":
            //echo "ok";
            $consulta = Consultas::getNotificacionesUser($usuario);
            break;
        case "countComentariosReporte":
            //echo "ok...".$idReporte;
            $consulta = Consultas::getCountComentariosReporte($idReporte);
            break;
        case "proyectos":
            $consulta = Consultas::getProyectos();
            break;
        case "UsuariosProyectos":
            $consulta = Consultas::getUsuariosProyectos();
            break;
        case "procesos":
            $consulta = Consultas::getProcesos();
            break;
        case "gantt":
            $consulta = Consultas::getGantt();
            break;
        case "ganttValores":
            $consulta = Consultas::getGanttValores($fecha_sinc);
            break;
            case "empleados";
                $consulta = Consultas::getEmpleados();
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