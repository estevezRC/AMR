<?php

require 'Consultas.php';

$gpoVal = $_GET['gpoVal'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $consulta = Consultas::obtenerDetallesReporte($gpoVal);

    $arr = array();
    if ($consulta) {

        foreach($consulta as $row){

            if($row['reactivo']!="file") {
                $arr1 = array(
                    "nombre" => $row['nombreC'],
                    "reactivo" => $row['reactivo'],
                    "valorTexto" => $row['valText'],
                    "valorEntero" => $row['valEnt'],
                    "idConf" => $row['idConf'],
                    "tipoValor" => $row['tipoValor'],
                    "valorDefault" => $row['valorDefault'],
                    "extra" => ""
                );
            }else{
                $imagenes = Consultas::obtenerImagenes($gpoVal);
                $arrImage1 = array();
                foreach ($imagenes as $imgen){
                    //$arrImage = array(
                        $cadenaImagen = ",".$imgen['nombre'].$cadenaImagen;
                    //);
                    $arrImage1[] = $arrImage;
                }

                $arr1 = array(
                    "nombre" => $row['nombreC'],
                    "reactivo" => $row['reactivo'],
                    "valorTexto" => substr($cadenaImagen,1),
                    "valorEntero" => $row['valEnt'],
                    "idConf" => $row['idConf'],
                    "tipoValor" => $row['tipoValor'],
                    "valorDefault" => $row['valorDefault'],
                    "extra" => substr($cadenaImagen,1)
                );
            }
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