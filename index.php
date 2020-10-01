<?php
//Sesion
session_start();
$controller = $_GET["controller"];
$action = $_GET["action"];

//Configuracion global
require_once 'config/global.php';

if ((!isset($_SESSION[AUTENTICADO_SUPERVISOR])) && ($controller != "Principal") && ($action != "logueo")) {
    header("Location: https://" . URL_DESARROLLO);
} else {
//Base para los controladores
    require_once 'core/ControladorBase.php';

//Funciones para el controlador frontal
    require_once 'core/ControladorFrontal.func.php';


    if (isset($_GET["controller"])) {
        $controllerObj = cargarControlador($_GET["controller"]);
        lanzarAccion($controllerObj);
    } else {
        $controllerObj = cargarControlador(CONTROLADOR_DEFECTO);
        lanzarAccion($controllerObj);
    }
}
?>
