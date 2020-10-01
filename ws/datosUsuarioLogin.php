<?php

require 'ConsultasGeneral.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    $array=$body['Usuarios'];

    //print_r($array);

    $statusDispositivos = false;

    for($i=0;$i<count($array);$i++){

        $arrayUsuario = $array[$i]['Usuario'];

        //print_r($arrayUsuario);

        $correoUsuario = $arrayUsuario['usuario'];
        $passwordUsuario = $arrayUsuario['password'];

        $datosUsuarios = ConsultasGeneral::compruebaDatosUsuario($correoUsuario,$passwordUsuario);

        //echo $datosUsuarios;
        if($datosUsuarios != ""){
            //echo $datosUsuarios;
            $usuarioStatus = true;
        }else{
            $usuarioStatus = false;
            //echo "no existe usuario";
        }

    }//fin 1 for

    /* print json_encode(
         array(
             'estado' => '1',
             'mensaje' => 'Creación éxitosa')
     );*/


    /*if($statusDispositivos){
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Dispositivo agregado')
        );
    }else{*/
    if ($usuarioStatus && $datosUsuarios != "0") {
        $datos["estado"] = 1;
        $datos["datos"] = $datosUsuarios;

        print json_encode($datos,JSON_UNESCAPED_UNICODE);
    } else {
        //print $usuarios;
        print json_encode(array(
            "estado" => 2,
            "mensaje" => "Datos incorrectos"
        ));
    }
}

?>