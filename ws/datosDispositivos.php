<?php

require 'Consultas.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    $array=$body['Dispositivos'];

    //print_r($array);

    $statusDispositivos = false;

    for($i=0;$i<count($array);$i++){

        $arrayDispositivo = $array[$i]['Dispositivo'];

        //print_r($arrayDispositivo);

        $correoUsuario = $arrayDispositivo['usuario'];
        $passwordUsuario = $arrayDispositivo['password'];
        $modelo = $arrayDispositivo['modelo'];
        $marca = $arrayDispositivo['marca'];
        $serie = $arrayDispositivo['serie'];

        $datosUsuarios = Consultas::compruebaDatosUsuario($correoUsuario,$passwordUsuario);

        //echo $datosUsuarios;
        if($datosUsuarios != ""){
            //echo $datosUsuarios;
            $usuarioStatus = true;
        }else{
            $usuarioStatus = false;
            //echo "no existe usuario";
        }

        if($usuarioStatus){
            $insertaDispositivo = Consultas::insertaDispositivo($datosUsuarios,$marca,$serie);
        }

        //echo $insertaDispositivo;
        if($insertaDispositivo == "exito"){
            $statusDispositivos = true;
        }else if($insertaDispositivo == "existe"){
            $statusDispositivos = true;
        }else{
            $statusDispositivos = false;
        }
        /*$insertaComentarios = Consultas::InsertaComentariosReportes(
            $idGpo,$comentariosRep,$idUsuario,$fechaRegistro,$estatus);

        if($insertaComentarios){
            $idComentario = Consultas::getMaxComentarios();
        }

        if($insertaComentarios){
            $statusDispositivos = true;
        }else{
            $statusDispositivos=false;
        }*/

    }//fin 1 for

    /* print json_encode(
         array(
             'estado' => '1',
             'mensaje' => 'Creación éxitosa')
     );*/


    if($statusDispositivos){
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Dispositivo agregado')
        );
    }else{
        if(!$datosUsuarios){
            print json_encode(
                array(
                    'estado' => '3',
                    'mensaje' => 'Usuario no existe verifica datos!')
            );
        }else {
            print json_encode(
                array(
                    'estado' => '2',
                    'mensaje' => 'Creación fallida')
            );
        }
    }
}

?>