<?php

require 'Consultas.php';
require_once '../config/global.php';
require_once '../core/EntidadBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../model/Notificaciones.php';
require_once '../model/AvanceActividad.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    //print_r($body);

    $array=$body['Comentarios'];

    $statusComentarios = false;
    $statusFoto = false;

    for($i=0;$i<count($array);$i++){

        $arrayComentario = $array[$i]['Comentario'];

        $idEmpresa = $arrayComentario['idEmpresa'];
        $idGpo = $arrayComentario['gpoValores'];
        $comentariosRep = $arrayComentario['comentarioStr'];
        $idUsuario = $arrayComentario['usuario'];
        $fechaRegistro = $arrayComentario['fecha'];
        $estatus = $arrayComentario["status"];
        $imagen = $arrayComentario["imagen"];
        $idUsuarioReporte = $arrayComentario["idUsuarioReporte"];

        session_start();
        $_SESSION['id_empresa_movil'] = $idEmpresa;

        $insertaComentarios = Consultas::InsertaComentariosReportes(
            $idGpo,$comentariosRep,$idUsuario,$fechaRegistro,$estatus);

        if($insertaComentarios){
            $idComentario = Consultas::getMaxComentarios();

            //echo $idComentario;
        }

        if($imagen != "") {
            $statusFoto = Consultas::insertaFotografiasAlterno(
                $idUsuario,
                "7",
                $idComentario,
                "1",
                $imagen,
                0,
                0,
                0,
                $comentariosRep,
                1,
                "Agregar",
                $fechaRegistro,
                $fechaRegistro,
                0,
                1,
                '0.0',
                '');
        }else{
            $statusFoto = true;
            //echo "Foto ".$statusFoto;
        }

        //echo "Comentario ".$insertaComentarios;

        if($insertaComentarios && $statusFoto){
            $statusComentarios = true;

            $funcion = new FuncionesCompartidas();
            $funcion->guardarNotificacion($idUsuarioReporte, $idUsuario, $idGpo, 2);
        }else{
            $statusComentarios=false;
        }

    }//fin 1 for

    /* print json_encode(
         array(
             'estado' => '1',
             'mensaje' => 'Creación éxitosa')
     );*/


    if($statusComentarios){
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Comentario agregado')
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