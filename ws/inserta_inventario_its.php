<?php

require 'Consultas.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $body = json_decode(file_get_contents("php://input"), true);

    $retorno=false;

    $array=$body['Inventarios'];

    //echo count($array);
    //echo print_r($array);

    for($i=0;$i<count($array);$i++){
        $arrayInventario = $array[$i]['Inventario'];

        $id = $arrayInventario['idInventario'];
        $marca = $arrayInventario['cIts_Marca'];
        $modelo = $arrayInventario['cIts_Modelo'];
        $serie = $arrayInventario['cIts_Serie'];
        $parte = $arrayInventario['cIts_Parte'];
        $descripcion = $arrayInventario['cIts_Descipcion'];

        $inserta = Consultas::insertaInventarioIts($id,$marca,$modelo,$serie,$parte,$descripcion);

    }

    if($inserta){
        $retorno = true;
    }

    if($retorno==true){
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Creación éxitosa')
        );
    }else{
        print json_encode(
            array(
                'estado' => '2',
                'mensaje' => 'Creación fallida')
        );
    }
}

?>