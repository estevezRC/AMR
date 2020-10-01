<?php

require_once 'consultasModifica.php';

$generaJson = ConsultasModifica::getJsonReportesGpoValores(1572);

if ($generaJson) {

    $datos["estado"] = 1;
    $datos["datos"] = $consulta;

    print json_encode($generaJson,JSON_UNESCAPED_UNICODE);
} else {
    //print $usuarios;
    print json_encode(array(
        "estado" => 2,
        "mensaje" => "Ha ocurrido un error  "
    ));
}