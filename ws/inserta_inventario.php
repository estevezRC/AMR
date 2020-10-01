<?php

require 'Consultas.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    //print_r($body);

    $array=$body['Elementos'];

    $statusValores=false;
    $statusReportesLlenados=false;

    //print_r($array);

    //echo count($array);

    for($i=0;$i<count($array);$i++){
        $maxGpoValores=Consultas::getMaxGpoValoresInventario();

        //echo "maximo gpoValores ".$maxGpoValores."<br />";

        $arrayElemento = $array[$i]['Elemento'];

        $idGpo = $maxGpoValores;//$arrayReportes['id_Gpo_Valores_Reporte'];
        $comentariosRep = $arrayElemento['Comentarios'];
        $idRemplazo = $arrayElemento['id_Remplazo'];
        $statusElemento = $arrayElemento['id_Status_Elemento'];
        $fechaRegistro = $arrayElemento['fecha_registro'];
        $idUsuario = $arrayElemento['id_Usuario'];
        //$idReporte = $arrayElemento['id_Reporte'];

        /*echo $idGpo.", ";
        echo $comentariosRep.", ";
        echo $idRemplazo.",";
        echo $statusElemento.", ";
        echo $fechaRegistro.", ";
        echo $idUsuario.", "."<br />";
        //echo $idReporte."<br />";*/

        $arrayValores=$arrayElemento['Valores'];

        for ($j=0; $j < count($arrayValores); $j++) {

            $idval = $arrayValores[$j]['idValores'];
            $idProyecto = $arrayValores[$j]['id_Proyecto'];
            $idGpoVal = $maxGpoValores;//$arrayValores[$j]['id_Gpo_Valores_Reporte'];
            $configuracion_ele = $arrayValores[$j]['id_Configuracion_Elemento'];
            $val_texto = $arrayValores[$j]['valor_Texto_Elemento'];
            $val_entero = $arrayValores[$j]['valor_Entero_Elemento'];

            /*echo "___".$idval.",";
            echo $idProyecto.",";
            echo $idGpoVal.",";
            echo $val_texto.",";
            echo $val_entero.",";
            echo $configuracion_ele."<br/>";*/

            $insertaValores=Consultas::insertaValoresElementos(
                $idProyecto,
                $val_texto,
                $val_entero,
                $configuracion_ele,
                "Valores_Elementos_Caracteristicas",
                $idGpoVal
            );

            if($insertaValores){
                $statusValores=true;
            }else{
                $statusValores=false;
            }
        }

        $insertaReportes=Consultas::insertaElementosInventariados(
            $idGpo,
            "",
            $comentariosRep,
            $fechaRegistro,
            "Elementos_Inventariados",
            $idUsuario,
            "0",
            $idRemplazo
        );

        if($insertaReportes){
            $statusReportesLlenados=true;
        }else{
            $statusReportesLlenados=false;
        }

    }//fin 1 for

    /*print json_encode(
        array(
            'estado' => '1',
            'mensaje' => 'Creación éxitosa')
    );*/


    if($statusReportesLlenados && $statusValores){
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