<?php

require 'ConsultasGeneral.php';
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
        $marca = $arrayDispositivo['marca'];
        $modelo = $arrayDispositivo['modelo'];
        $serie = $arrayDispositivo['serie'];

        $datosUsuarios = ConsultasGeneral::compruebaDatosUsuario($correoUsuario,$passwordUsuario);

        print_r($datosUsuarios);
        //echo $datosUsuarios;
        if($datosUsuarios != ""){
            //echo $datosUsuarios;
            $usuarioStatus = true;
        }else{
            $usuarioStatus = false;
            //echo "no existe usuario";
        }

        //echo "insertaDispositivo(".$datosUsuarios['id_Usuario'].",".$datosUsuarios['id_Empresa'].",".$marca,$modelo,$serie;
        if($usuarioStatus){
            //echo "insertaDispositivo(".$datosUsuarios[0]['id_Usuario'].",".$datosUsuarios[0]['id_Empresa'].",".$marca,$modelo,$serie;
            $insertaDispositivo = ConsultasGeneral::insertaDispositivo($datosUsuarios[0]['id_Usuario_Empresa'],$datosUsuarios[0]['id_Empresa'],$marca,$modelo,$serie);
            
            /*session_start();
            $_SESSION['id_empresa_movil'] = $datosUsuarios[0]['id_Empresa'];
            $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR] = $datosUsuarios[0]['id_Empresa'];*/

            $insertaDispositivoEspecifica = Consultas::insertaDispositivo($datosUsuarios[0]['id_Usuario_Empresa'],$marca,$serie);
        }

        //$insertaDispositivo = "exito";
        //echo $insertaDispositivo;
        if($insertaDispositivo == "exito"){
            $statusDispositivos = true;
        }else if($insertaDispositivo == "existe"){
            $statusDispositivos = true;
        }else{
            $statusDispositivos = false;
        }

    }//fin 1 for

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
