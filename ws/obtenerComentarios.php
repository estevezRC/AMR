<?php

require 'Consultas.php';

$gpoVal = $_GET['gpoVal'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $consulta = Consultas::obtenerDatosReporte($gpoVal);

    $consultaComentarios = Consultas::ObtenerComentariosReportes($gpoVal);

    $arr = array();
    if ($consulta) {

        foreach($consulta as $row){

                $arr1 = array(
                    "nombreReporte" => $row['nombreReporte'],
                    "titulo" => $row['titulo'],
                    "fecha" => $row['fecha'],
                    "comentarios" => $consultaComentarios);

            $arr[] = $arr1;
        }
    }

    if ($consulta) {

        $datos["estado"] = 1;
        $datos["datos"] = $arr;

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