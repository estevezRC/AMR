<?php

require 'Consultas.php';

$tabla=$_GET['tabla'];
$idElemento = $_GET['idElemento'];


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //$idsReportes =  Consultas::obtenerUltimosReportes($area,$empresa);
    switch ($tabla) {
        case "elementos":
            $consulta = Consultas::getElementosById($idElemento);
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