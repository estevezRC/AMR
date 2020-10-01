<?php

require 'Consultas.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    $array = $body['Notificaciones'];

    //print_r($array);

    $statusUpdateNotificacion = false;

    for ($i = 0; $i < count($array); $i++) {

        $arrayNotificacion = $array[$i]['Notificacion'];

        //print_r($arrayDispositivo);

        $idEmpresa = $arrayNotificacion['idEmpresa'];
        $idNotificacion = $arrayNotificacion['idNotificacion'];

        session_start();
        $_SESSION['id_empresa_movil'] = $idEmpresa;

        /*$passwordUsuario = $arrayNotificacion['password'];
        $marca = $arrayNotificacion['marca'];
        $serie = $arrayNotificacion['serie'];*/

        $updateNotificacion = Consultas::modificaNotificacionEstatus($idNotificacion);


        if ($updateNotificacion) {
            $statusUpdateNotificacion = true;
        }

    }//fin 1 for

    if ($statusUpdateNotificacion) {
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Notificacion actualizada')
        );
    } else {
        print json_encode(
            array(
                'estado' => '2',
                'mensaje' => 'Creación fallida')
        );
    }
}