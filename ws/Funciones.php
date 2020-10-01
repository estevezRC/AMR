<?php

define( 'API_ACCESS_KEY', 'AAAAuTBlSCA:APA91bFMyEoMYHIwEaAY-hJspXVfN-uKjKIT3xCiW-V2kU8_guwRIv6LrqSxkFZOliuoj94bQtZSIxBHrsArRTkA4ouyLGvEW0JfNwORoLZHVpYxpibNEPrEvMkA4KvVL6ZhWEUdK3Dk');
//require 'Consultas.php';

class Funciones
{

    public function __construct()
    {
    }

    public static function formatear_fecha($fecha)
    {
        $fechaFormat = date("d-m-Y", strtotime($fecha));

        return $fechaFormat;
    }

    public static function EnviaCorreo($id_reporte, $gpoValores, $fecha, $idUser, $tituloReporte, $idUbicacion, $estatusReporte,$notificaReporte)
    {

        //$datos_reporte = Consultas::getReportesById($id_reporte);
        $areaUsuario = Consultas::getAreaUsuario($idUser);
        $empresaUsuario = Consultas::getEmpresaUsuario($idUser);

        /*foreach ($datos_reporte as $row) {
            $areas = $row["Areas"];
            if ($areas != null) {
                $reporte_areas = explode(",", $areas);
            } else {
                $reporte_areas = null;
            }
            $nombre_Reporte = $row["nombre_Reporte"];
        }

        if ($reporte_areas == null) {
            //echo "no envia";
        } else {

        }//fin else*/

        /*$existeSeguimiento = Consultas::getExisteSeguimiento($gpoValores, $idUser);
        if (!$existeSeguimiento) {
            //echo 'Existe seg';
            Consultas::insertaSeguimientoReportes($gpoValores, $idUser, $areaUsuario, $fecha);
        }*/

        if($estatusReporte == "1") {
            //echo "estatus 1";
            if (Consultas::obtenerReporteIncidencia($id_reporte)) {
                if($notificaReporte == "0") {
                    self::EnviaCorreoIncidentesPruebas($id_reporte, $gpoValores, $tituloReporte);
                }else{
                    //echo "No notifica";
                }
            } else {
                //echo 'no incidencia';
            }
        }//if estatus = 1

    }//EnviaCorreo

    public static function EnviaCorreoMinuta($id_reporte, $gpoValores, $fecha, $notificar, $tituloReporte, $idUbicacion)
    {
        $datos_reporte = Consultas::getReportesById($id_reporte);
        $nombresDestinatarios = "";
        $destinatarios = "";

        foreach ($datos_reporte as $row) {
            /*$areas = $row["Areas"];
            if($areas != null) {
                $reporte_areas = explode(",",$areas);
            }else{
                $reporte_areas = null;
            }*/
            $nombre_Reporte = $row["nombre_Reporte"];
        }

        if ($nombre_Reporte == null) {
            //echo "no envia";
        } else {
            //echo "envia";
            //foreach ($reporte_areas as $area) {
            $correos = Consultas::getAllParticipantesByIds($notificar);
            foreach ($correos as $correo) {
                $idUsuario = $correo["id_Usuario"];
                $destinatario = $correo["correo_Participante"];
                $nombres = $correo["nombre_Participante"] . ' ' . $correo["apellido_Participante"];

                if ($correo["empresa"] == 2) {
                    $prosis = true;
                }

                //echo $destinatario."...".$nombre_Reporte."<br/>";
                //$destinatario = "luisenjzh@gmail.com";

                $destinatarios = $destinatarios . "," . $destinatario;
                $nombresDestinatarios = $nombresDestinatarios . "," . $nombres;
                //$reportecorreo = "" . $nombre_Reporte . ": https://supervisor.technology/condor/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=" . $gpoValores . "";
                //$asunto = "Notificacion de nuevo reporte: " . $nombre_Reporte . "";
                //  mail($destinatario, $asunto, $reportecorreo);
                //Consultas::insertaSeguimientoReportes($gpoValores,$idUsuario,$idArea,$fecha);

            }//segundo foreach
            $destinatarios = substr($destinatarios, 1);
            $nombresDestinatarios = substr($nombresDestinatarios, 1);
            $ccp = "";
            if ($id_reporte == "27" && $idUbicacion != "1877") {
                //$ccp = "jtrigueiros@grupo-prosis.com";
                if ($prosis) {
                    //$ccp = "soporte_ljh@supervisor.uno,luisenjzh@icloud.com";
                    $ccp = "jtrigueiros@grupo-prosis.com,rmanzanera@grupo-prosis.com,hviana@grupo-prosis.com,soporte_ljh@supervisor.uno";
                } else {
                    $ccp = "soporte_ljh@supervisor.uno";
                }
            } else {
                $ccp = "soporte_ljh@supervisor.uno";
                //echo "envia ".$ccp;
            }

            //jtrigueiros@grupo-prosis.com

            //echo $destinatarios;
            if ($id_reporte == "27" || $id_reporte == "7" || $id_reporte == "33" || $id_reporte == "42") {
                self::enviarCorreoPhpMailer($nombre_Reporte, $destinatarios, $gpoValores, "Borrador:", $tituloReporte,
                    $nombresDestinatarios, $ccp, $id_reporte);
            }
            //}//primer foreach
        }//fin else

    }//EnviaCorreo

    public static function EnviaCorreoIncidentes($id_reporte, $gpoValores, $tituloReporte, $idUbicacion, $conip)
    {
        $datos_reporte = Consultas::getReportesById($id_reporte);
        $nombresDestinatarios = "";
        $destinatarios = "";
        $usuarios = ",2";

        foreach ($datos_reporte as $row) {
            /*$areas = $row["Areas"];
            if($areas != null) {
                $reporte_areas = explode(",",$areas);
            }else{
                $reporte_areas = null;
            }*/
            $nombre_Reporte = $row["nombre_Reporte"];
            $areas = $row['Areas'];
        }

        if ($nombre_Reporte == null) {
            //echo "no envia";
        } else {

            if ($idUbicacion != "1877") {
                $idPadre = Consultas::VerificaSiTieneIdPadre($idUbicacion);
                if ($idPadre == 0) {

                    //self::EnviaCorreoIncidmentes($id_reporte, $gpoValores, $tituloReporte);
                    $infoUb = Consultas::obtenerInformacionUbicacion($idUbicacion, $areas);
                    $correos = Consultas::getAllParticipantesCoordinadores($areas, $infoUb, $conip);
                } else if ($idPadre == 1) {
                    $correos = Consultas::getCoordinadoresCNC("22,41,36,24,21,3");
                }
            } else {
                $correos = Consultas::getCoordinadoresCNC("22,41,36,24,21,3");
            }

            //print_r($correos);
            if (count($correos) > 0) {
                foreach ($correos as $correo) {
                    //$idUsuario = $correo["id_Usuario"];
                    $destinatario = $correo["correo_Participante"];
                    $nombres = $correo["nombre_Participante"] . ' ' . $correo["apellido_Participante"];

                    $usuario = $correo["id_Usuario"];

                    //echo "user: ".$usuario;
                    if (!$usuario == null || !$usuario == "") {
                        $usuarios = $usuarios . "," . $usuario;
                    }

                    $destinatarios = $destinatarios . "," . $destinatario;
                    $nombresDestinatarios = $nombresDestinatarios . "," . $nombres;
                }//segundo foreach

                $destinatarios = substr($destinatarios, 1);
                $nombresDestinatarios = substr($nombresDestinatarios, 1);
                $usuarios1 = substr($usuarios, 1);

                //echo $destinatarios;
                //echo  $usuarios;

                $tokens = Consultas::ObtenerTokens($usuarios1);
                //print_r($tokens);

                /*self::notifica($tokens,$nombre_Reporte,$tituloReporte,$gpoValores,$id_reporte,$idUbicacion);*/

                //self::notificaComentario($tokens,"Nuevo comentario",$tituloReporte,$gpoValores,$id_reporte,$idUbicacion);

                self::enviarCorreoPhpMailer($nombre_Reporte, $destinatarios, $gpoValores, "", $tituloReporte,
                    $nombresDestinatarios, "soporte_ljh@supervisor.uno", $id_reporte);

            }
            //}//primer foreach
        }//fin else

    }//EnviaCorreo


    public static function enviarCorreoPhpMailer($nombre_reporte, $email, $gpoValores, $titulo, $tituloReporte,
                                                 $nombresDestinatarios, $ccp, $idReporte)
    {

        $mail = new PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP

        $mail->Host = 'supervisor.uno';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'soporte@supervisor.uno';//'abasso@supervisor.technology';
        $mail->Password = 'Gb!7Z-^@f}4}';//'q_WF1+{zF%UR';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'supervisor@supervisor.uno';
        $mail->FromName = 'supervisor.uno';

        $destinatarios = explode(",", $email);
        $nombres = explode(",", $nombresDestinatarios);
        for ($i = 0; $i < count($destinatarios); $i++) {
            //echo 'destinatario: '.$destinatarios[$i]."<br/>";
            $mail->addAddress($destinatarios[$i], $nombres[$i]);  // Add a recipient
        }

        if ($ccp != "") {
            $conCopia = explode(",", $ccp);
            if (count($conCopia) > 1) {
                for ($j = 0; $j < count($conCopia); $j++) {
                    $mail->AddCC($conCopia[$j]);
                }
            } else if (count($conCopia) == 1) {
                $mail->AddCC($ccp, "");
            }
        }

        $datos_reporte = Consultas::obtenerValoresReportes($gpoValores);

        $reportecorreo = "";
        $textoParticipantes = "";

        if ($idReporte == "2" || $idReporte == "34" || $idReporte == "43") {
            $datosReporte = Consultas::obtenerDatosReporteAlterno($gpoValores);
            $incidencias = Consultas::obtenerDatosValores($gpoValores);


            $fechaReporte = date("d-m-Y", strtotime($incidencias[0]));
            $tituloReporte = $datosReporte[1];
            $fechaIncidencia = date("d-m-Y", strtotime($incidencias[0]));
            $horaIncidencia = $incidencias[1];
            $areaIncidencia = $incidencias[2];
            $tipoIncidencia = $incidencias[3];
            $idIncidencia = $datosReporte[2];
            $usuarioIncidencia = $datosReporte[3];
            $estatusIncidencia = $incidencias[8];
            $descripcionIncidencia = $incidencias[4];
            $accionesRealizadas = $incidencias[5];
            $notificoIncidecia = $incidencias[6];
            $medioIncidencia = $incidencias[7];

            if ($datosReporte[4] == "2") {
                $clasificacionIncidencia = $incidencias[9];
                $segumientoIncidencia = $incidencias[10];
            } else {
                $segumientoIncidencia = $incidencias[9];
            }

            $reportecorreo = '<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style type="text/css">
        .tabla-destacada{width: 70%; border-spacing: 1px; font-family: arial, sans-serif;}

        .tabla-destacada td,
        .tabla-destacada th{padding: 10px;}

        .tabla-destacada thead th{border-bottom: 1px groove #FFF;
            border-radius: 10px 10px 0 0}

        .tabla-destacada tbody tr{border: 1px groove #FFF;}
        .tabla-destacada tbody td{border: 1px groove #FFF;}

        .tabla-destacada tfoot th{border-radius: 0 0 10px 10px;}

        #tdtitulo{
            background: #BDBDBD;
        }
    </style>
</head>';

            $reportecorreo .= '<body>  
<h4>' . $nombre_reporte . ' ' . $tituloReporte . '</h4>
<table class="tabla-destacada">
    <tr>
        <td id="tdtitulo">Título del reporte:</td>
        <td colspan="3">' . $tituloReporte . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Fecha:</td><td>' . $fechaIncidencia . '</td>
        <td id="tdtitulo">Hora:</td><td>' . $horaIncidencia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Área</td>
        <td>' . $areaIncidencia . '</td>
        <td id="tdtitulo">Tipo incidente</td>
        <td>' . $tipoIncidencia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Id Reporte:</td>
        <td>' . $gpoValores . '</td>
        <td id="tdtitulo">Reportado por:</td>
        <td>' . $usuarioIncidencia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Estatus</td>
        <td colspan="3">' . $estatusIncidencia . '</td>
    </tr>';

            if ($idReporte == '2') {
                $reportecorreo .= '<tr>
                    <td id="tdtitulo">Clasificación</td>
                    <td colspan="3">' . $clasificacionIncidencia . '</td>
         </tr>';
            }

            $reportecorreo .= '<tr>
        <td id="tdtitulo">Seguimiento incidencia</td>
        <td colspan="3">' . $segumientoIncidencia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Descripción de lo ocurrido</td>
        <td colspan="3">' . $descripcionIncidencia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Acciones realizadas</td>
        <td colspan="3">' . $accionesRealizadas . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Se le notifico</td>
        <td colspan="3">' . $notificoIncidecia . '</td>
    </tr>
    <tr>
        <td id="tdtitulo">Medio</td>
        <td colspan="3">' . $medioIncidencia . '</td>
    </tr>
</table>';

            $reportecorreo .= '</body>
</html>';

        } else {
            foreach ($datos_reporte as $reporte) {
                if ($reporte['tipo_Reactivo_Campo'] == "file") {
                    //$mail->addAttachment("../".$reporte['valor_Texto_Reporte'],$reporte['valor_Texto_Reporte']);
                    //$reportecorreo .= "<img src='http://getitcompany.com/condor/".$reporte['valor_Texto_Reporte']."'>";
                } else if ($reporte['tipo_Reactivo_Campo'] != "file") {
                    if ($reporte['tipo_Reactivo_Campo'] == "select-choice") {
                        $correos = Consultas::getAllParticipantesByIds($reporte['valor_Texto_Reporte']);
                        foreach ($correos as $correo) {
                            $idUsuario = $correo["id_Usuario"];
                            $nombre = $correo["nombre_Participante"];
                            $apellido = $correo["apellido_Participante"];
                            $cargo = $correo["cargo_Participante"];
                            $nombreEmpresa = $correo["nombre_Empresa"];

                            $textoParticipantes = $textoParticipantes . " " . $nombre . " " . $apellido . " " . $cargo . " " . $nombreEmpresa . "<br/>";
                        }
                        $reportecorreo .= "<h3><b>" . $reporte['nombre_Campo'] . ": </b></h3><br>" . $textoParticipantes . $reporte['valor_Entero_Reporte'] . " <br><br>";
                    } else {
                        $reportecorreo .= "<h3><b>" . $reporte['nombre_Campo'] . ": </b></h3><br>" . $reporte['valor_Texto_Reporte'] . $reporte['valor_Entero_Reporte'] . " <br><br>";
                    }
                }

            }
        }

        $fotografias = Consultas::obtenerFotografiasReportes($gpoValores);

        foreach ($fotografias as $foto) {
            $mail->addAttachment("../img/reportes/" . $foto['nombre'], $foto['nombre']);
        }

        $reportecorreo .= "<br/><a href='https://supervisor.uno/condor/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=" . $gpoValores . "&Id_Reporte=" . $idReporte . "'>Ver mas detalles...</a>";

        $mail->Subject = $titulo . '' . $nombre_reporte . ' ' . $tituloReporte;
        $mail->Body = ($reportecorreo);
        $mail->AltBody = 'titulo';
        $mail->CharSet = 'UTF-8';
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo . $email;
            exit;
        }

    }//fin EnviaCorreo


    public static function enviarCorreoPhpMailerPrueba($nombre_reporte, $email, $gpoValores, $titulo, $tituloReporte,
                                                       $nombresDestinatarios, $incidenciaTrue)
    {

        $mail = new PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP

        $mail->Host = 'aasapp.mx';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'soporte@aasapp.mx';
        $mail->Password = 'Gb!7Z-^@f}4}';                     // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'ssoporte@aasapp.mx';
        $mail->FromName = 'Notificaciones aasapp.mx';

        $destinatarios = explode(",", $email);
        $nombres = explode(",", $nombresDestinatarios);
        for ($i = 0; $i < count($destinatarios); $i++) {
            //echo 'destinatario: '.$destinatarios[$i]."<br/>";
            $mail->addAddress($destinatarios[$i], $nombres[$i]);  // Add a recipient
        }

        $datos_reporte = Consultas::obtenerValoresReportes($gpoValores);

        $reportecorreo = "";

        if ($incidenciaTrue = true) {
            $datosReporte = Consultas::obtenerDatosReporteAlterno($gpoValores);
            $incidencias = Consultas::obtenerValoresReportesAlterno($gpoValores);

            $tituloReporte = $datosReporte[1];
            $usuarioIncidencia = $datosReporte[3];
            $fechaReporte = $datosReporte[5];

            $fechaIncidencia = date("d-m-Y", strtotime($fechaReporte));
            $horaIncidencia = date('H:i:s', strtotime($fechaReporte));

            foreach ($incidencias as $datos){
                $datosIncidencia[$i][0] = $datos['campo'];
                $datosIncidencia[$i][1] = $datos['valor'];
            }

            $nReporte = strtoupper($nombre_reporte);
            $nReporteFinal = strtr($nReporte, "áéíóú", "ÁÉÍÓÚ");

            $reportecorreo = '<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style type="text/css">
        .tabla-destacada{width: 70%; border-spacing: 1px; font-family: arial, sans-serif;}

        .tabla-destacada td,
        .tabla-destacada th{padding: 10px;}

        .tabla-destacada thead th{border-bottom: 1px groove #FFF;
            border-radius: 10px 10px 0 0}

        .tabla-destacada tbody tr{border: 1px groove #FFF;}
        .tabla-destacada tbody td{border: 1px groove #FFF;}

        .tabla-destacada tfoot th{border-radius: 0 0 10px 10px;}

        #tdtitulo{
            background: #BDBDBD;
        }
    </style>
</head>';

//<!--<h3>' . $nombre_reporte . ' ' . $tituloReporte . '</h3>-->
            $reportecorreo .= '<body>  
<table class="tabla-destacada">
    <tr>
        <td id="tdtitulo" colspan="4">' . $nReporteFinal . '</td>
    </tr>
    <tr>
        <td><b>Título del reporte:</b></td>
        <td colspan="3">' . $tituloReporte . '</td>
    </tr>
    <tr>
        <td><b>Id Reporte:</b></td>
        <td>' . $gpoValores . '</td>
        <td><b>Reportado por:</b></td>
        <td>' . $usuarioIncidencia . '</td>
    </tr>
    <tr>
        <td><b>Fecha:</b></td><td>' . $fechaIncidencia . '</td>
        <td><b>Hora:</b></td><td>' . $horaIncidencia . '</td>
    </tr>';

            foreach ($incidencias AS $datos){
                if($datos['reactivo'] == 'date' || $datos['reactivo'] == 'time' || $datos['reactivo'] == 'file'){
                    //echo 'ok';
                }else{
                    $reportecorreo .= '<tr>
        <td><b>'.$datos['campo'].':</b></td>
        <td colspan="3">'.$datos['valor'].'</td>
        </tr>';
                }
            }
            $reportecorreo .= '
</table>
<br />';

            $reportecorreo .= '</body>
</html>';


        }//fin if

        $fotografias = Consultas::obtenerFotografiasReportes($gpoValores);

        foreach ($fotografias as $foto) {
            $mail->addAttachment("../img/reportes/" . $foto['nombre'], $foto['nombre']);
        }
        $mail->Subject = $titulo . '' . $nombre_reporte . ' ' . $tituloReporte;
        $mail->Body = ($reportecorreo);//file_get_contents("http://supervisor.uno/condor/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=24402&Id_Reporte=2");
        $mail->AltBody = 'titulo';
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo . $email;
            exit;
        }

    }//fin EnviaCorreo

    public static function EnviaCorreoIncidentesPruebas($id_reporte, $gpoValores, $tituloReporte)
    {
        $datos_reporte = Consultas::getReportesById($id_reporte);
        $nombresDestinatarios = "";
        $destinatarios = "";
        $telegramIds = "";
        $usuarios = ",2";

        foreach ($datos_reporte as $row) {
            $nombre_Reporte = $row["nombre_Reporte"];
        }

        if ($nombre_Reporte == null) {
            //echo "no envia";
        } else {

            $correos = Consultas::obtenerCorreosUsuarios($id_reporte);

            if (count($correos) > 0) {
                foreach ($correos as $correo) {
                    $destinatario = $correo["correo_Usuario"];
                    $nombres = $correo["nombre_Usuario"] . ' ' . $correo["apellido_Usuario"];


                    $destinatarios = $destinatarios . "," . $destinatario;
                    $nombresDestinatarios = $nombresDestinatarios . "," . $nombres;
                }//segundo foreach

                $destinatarios = substr($destinatarios, 1);
                $nombresDestinatarios = substr($nombresDestinatarios, 1);

                //echo $destinatarios;

                /*self::enviarCorreoPhpMailerPrueba($nombre_Reporte, $destinatarios, $gpoValores, "", $tituloReporte,
                    $nombresDestinatarios, true);*/

            }//if $correos

            $idsChats = Consultas::obtenerIdChatUsuarios($id_reporte);

            if (count($idsChats) > 0) {
                foreach ($idsChats as $idsChat) {
                    //$idUsuario = $correo["id_Usuario"];
                    $telegramId = $idsChat["id_telegram"];

                    $telegramIds = $telegramIds . "," . $telegramId;
                }//segundo foreach

                $telegramIds = substr($telegramIds, 1);

                //echo $telegramIds;

                self::enviaNotificacionTelegram($telegramIds,$gpoValores);
            }
            //}//primer foreach
        }//fin else

    }//EnviaCorreo

    public static function enviaNotificacionTelegram($chatId,$gpoValores){

        $chatsIds = explode(",",$chatId);

        $datosReporte = Consultas::obtenerDatosReporteAlterno($gpoValores);
        $incidencias = Consultas::obtenerDatosValores($gpoValores);

        $nombreReporte = $datosReporte[0];
        $tituloReporte = $datosReporte[1];
        $usuarioReporte = $datosReporte[3];
        $idReporte = $datosReporte[4];
        $fechaReporte = $datosReporte[5];


        $textoIncidencia = '<b>NUEVA INCIDENCIA ('.$nombreReporte.')</b>
<b>Titulo: </b>'.$tituloReporte.'
<b>Creado por: </b>'.$usuarioReporte.'
<b>Fecha: </b>'.date("d-m-Y", strtotime($fechaReporte)).'
<a href="http://aasapp.mx/condor/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte='.$gpoValores.'&Id_Reporte='.$idReporte.'">Ver detalles</a>';

        /*print_r($datosReporte);
        echo '<br />';
        print_r($incidencias);

        echo $textoIncidencia;*/

        if(count($chatsIds) > 0){
            foreach ($chatsIds as $idChat){
                //echo '<br />'.$idChat;
                self::sendMessage($idChat,$textoIncidencia);
            }
        }
    }//fin

    public static function sendMessage($chatId, $text)
    {

        $TOKEN = "733560471:AAHWB5DjvF9Ix_OgwO312vM8Tnjy4YRJmUI";
        $TELEGRAM = "https://api.telegram.org:443/bot$TOKEN";

        $query = http_build_query(array(
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => "HTML"));
        /*, // Optional: Markdown | HTML
            'reply_markup' => json_encode($keyboard)
        ));*/

        $response = file_get_contents("$TELEGRAM/sendMessage?$query");
        return $response;
    }

    public static function isIncidencia($idReporte){
        if(Consultas::obtenerReporteIncidencia($idReporte)){
            echo 'ok';
        }else{
            echo 'no incidencia';
        }
    }
}

?>