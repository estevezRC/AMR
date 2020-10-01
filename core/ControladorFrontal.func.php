<?php
//FUNCIONES PARA CARGAR CONTROLADORES Y ACCIONES O FUNCIONES POR URL
//FUNCIONES PARA EL CONTROLADOR FRONTAL
function cargarControlador($controller){
	$controller2 = "Principal";
	$controlador=ucwords($controller).'Controller';
	//$controlador=ucwords('PrincipalController');
	$strFileController='controller/'.$controlador.'.php';
	//$strFileController='controller/UsuariosController.php';
	if(!is_file($strFileController)){
		$strFileController='controller/'.ucwords(CONTROLADOR_DEFECTO).'Controller.php';
	}
    require_once $strFileController;
    $controllerObj=new $controlador();
    return $controllerObj;
}

function cargarAccion($controllerObj, $action, $controller)
{
    $data = get_class($controllerObj) . ", " . $action;

    require_once 'core/FuncionesCompartidas.php';
    $funciones = new FuncionesCompartidas();

    $accion = $action;
    switch (get_class($controllerObj)) {
        /*case('EstandaresController'):
            error_reporting(0);
            //$bitacora->saveBitacora();
            $funciones->guardarBitacora('NULL', $_SESSION[ID_USUARIO_SUPERVISOR], 'NULL', 22, $data);
            break;*/
        case ('NotificacionesController'):
            break;
        /*case ('LlenadosReporteController'):
            if ($action != 'getChildren')
                $funciones->guardarBitacora('NULL', $_SESSION[ID_USUARIO_SUPERVISOR], 'NULL', 22, $data);
            break;
        case ('BitacorasController'):
            break;*/
        default:
            //$funciones->guardarBitacora('NULL', $_SESSION[ID_USUARIO_SUPERVISOR], 'NULL', 22, $data);
            break;
    }
    $controllerObj->$accion();
}

function lanzarAccion($controllerObj){
	if(isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])){

		cargarAccion($controllerObj, $_GET["action"],$controller);
	}else{
		cargarAccion($controllerObj, ACCION_DEFECTO,$controller);
	}

}
?>
