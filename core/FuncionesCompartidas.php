<?php
require_once 'ControladorBase.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class FuncionesCompartidas extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public $url;
    private $connectorDB;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->connectorDB = new AvanceActividad($this->adapter);
        $this->url = $_SERVER["SERVER_NAME"];
    }

    public function guadarNotificacionMatrizComunicacion($id_usuarioNotificacion, $id_usuarioSesionNotifico, $id_Gpo, $detalleNotificacion)
    {
        $notificacion = new Notificaciones($this->adapter);
        $notificacion->set_id_notificacion_detalle($detalleNotificacion);
        $notificacion->set_id_usuarionotificacion($id_usuarioNotificacion);
        $notificacion->set_id_usuarionotifico($id_usuarioSesionNotifico);
        $notificacion->set_id_Gpo_Valores_Reporte($id_Gpo);
        if ($id_usuarioNotificacion != $id_usuarioSesionNotifico) {
            $notificacion->saveNotificacion();
        }
    }

    public function guardarNotificacion($id_usuarioNotificacion, $id_usuarioSesionNotifico, $id_Gpo, $detalleNotificacion)
    {
        /* ::::::::::::::::::::::::::::::::::: NOTIFICACION Primaria :::::::::::::::::::::::::::::::::::::::::::::::::*/
        $notificacion = new Notificaciones($this->adapter);
        $notificacion->set_id_notificacion_detalle($detalleNotificacion);
        $notificacion->set_id_usuarionotificacion($id_usuarioNotificacion);
        $notificacion->set_id_usuarionotifico($id_usuarioSesionNotifico);
        $notificacion->set_id_Gpo_Valores_Reporte($id_Gpo);
        if ($id_usuarioNotificacion != $id_usuarioSesionNotifico) {
            $notificacion->saveNotificacion();
        }
        // ::::::::::::::::::::::::::::::::::::::::::::: NOTIFICACION MOVIL :::::::::::::::::::::::::::::::::::::::::::
        if ($id_usuarioNotificacion != $id_usuarioSesionNotifico) {
            $notificaciones = new Notificaciones($this->adapter);
            $allNotificaciones = $notificaciones->getAllNotificaciones($id_usuarioNotificacion);
            $data = array(
                'title' => NAMEAPP . ': ' . $allNotificaciones[0]->nombre_usuario_notifico . ' ' . $allNotificaciones[0]->apellido_usuario_notifico,
                'body' => $allNotificaciones[0]->Descripcion . ' ' . $allNotificaciones[0]->titulo_Reporte,
                'idReporte' => $allNotificaciones[0]->id_Reporte,
                'idGpo' => $allNotificaciones[0]->id_Gpo_Valores_ReportesLlenados,
                'idNotificacion' => $allNotificaciones[0]->id_notificacion
            );

            if ($allNotificaciones[0]->token != null || $allNotificaciones[0]->token != '' || !empty($allNotificaciones[0]->token)) {
                $this->sendPushNotification($allNotificaciones[0]->token, $data);
            }
        }
        // ::::::::::::::::::::::::::::::::::::::::::::: END NOTIFICACION MOVIL ::::::::::::::::::::::::::::::::::::::::
        /* ::::::::::::::::::::::::::::::::::: NOTIFICACION Primaria :::::::::::::::::::::::::::::::::::::::::::::::::*/

        /* ::::::::::::::::::::::::::::::::::: NOTIFICACION Global :::::::::::::::::::::::::::::::::::::::::::::::::::*/
        $notificacion2 = new Notificaciones($this->adapter);
        $id_usuariosnotificacion = $notificacion2->getAllUserNotificacion($id_Gpo, $id_usuarioNotificacion);
        if (is_array($id_usuariosnotificacion) || is_object($id_usuariosnotificacion)) {
            foreach ($id_usuariosnotificacion as $id_usuario1) {
                // ::::::::::::::::::::::::::::::::::::::::::: NOTIFICACION MOVIL ::::::::::::::::::::::::::::::::::::::
                $notificacion1 = new Notificaciones($this->adapter);
                $notificacion1->set_id_notificacion_detalle($detalleNotificacion);
                $notificacion1->set_id_usuarionotificacion($id_usuario1->id_usuarionotifico);
                $notificacion1->set_id_usuarionotifico($id_usuarioSesionNotifico);
                $notificacion1->set_id_Gpo_Valores_Reporte($id_Gpo);
                if ($id_usuario1->id_usuarionotifico != $id_usuarioSesionNotifico) {
                    $notificacion1->saveNotificacion();
                }

                if ($id_usuario1->id_usuarionotifico != $id_usuarioSesionNotifico) {
                    $notificaciones = new Notificaciones($this->adapter);
                    $allNotificaciones = $notificaciones->getAllNotificaciones($id_usuario1->id_usuarionotifico);
                    $data = array(
                        'title' => NAMEAPP . ': ' . $allNotificaciones[0]->nombre_usuario_notifico . ' ' . $allNotificaciones[0]->apellido_usuario_notifico,
                        'body' => $allNotificaciones[0]->Descripcion . ' ' . $allNotificaciones[0]->titulo_Reporte,
                        'idReporte' => $allNotificaciones[0]->id_Reporte,
                        'idGpo' => $allNotificaciones[0]->id_Gpo_Valores_ReportesLlenados,
                        'idNotificacion' => $allNotificaciones[0]->id_notificacion
                    );
                    if ($allNotificaciones[0]->token != null || $allNotificaciones[0]->token != '' || !empty($allNotificaciones[0]->token)) {
                        $this->sendPushNotification($allNotificaciones[0]->token, $data);
                    }
                }
                // ::::::::::::::::::::::::::::::::::::::::::: NOTIFICACION MOVIL ::::::::::::::::::::::::::::::::::::::
            }
        }
        /* ::::::::::::::::::::::::::::::::::: END NOTIFICACION Global :::::::::::::::::::::::::::::::::::::::::::::::*/
    }

    function sendPushNotification($to = '', $data = array())
    {

        $apiKey = 'AAAA9T0GHIE:APA91bFnV2HQP1C0dSNMNwkfwjI9T8Kc5poiF9DitywyWAijDQgXk7WGQSBvdg1qInL9869hwON53EYTzYBahVkPNUjo4jAbzhbXP6xnGKzErx1wOaEsQ8-ANmgHNX40KwgLhgPxFy9j';

        $fields = array(
            'to' => $to,
            'notification' => $data,
            'data' => $data,
        );

        $headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');
        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    /* :::::::::::::::::::::::::: NOTIFICACIONES (TELEGRAM, CORREO, PUSH, ETC) :::::::::::::::::::::::::::::::::::*/
    public function enviarNotificaciones($id_Gpo_Grupo_Valores, $id_Proyecto, $id_EmpresaGral, $nombreCarpeta)
    {
        $reportes_Llenados = new ReporteLlenado($this->adapter);
        $getAllReportesLlenado = $reportes_Llenados->getAllReportesLlenadosByIdGpoNotificaciones($id_Gpo_Grupo_Valores);

        // Consultar Matriz de comunicacion
        $matrizComunicacion = new MatrizComunicacion($this->adapter);
        $getAllMatriz = $matrizComunicacion->getAllMatrizByReporte($getAllReportesLlenado[0]->id_Reporte);

        $fotografia = new Fotografia($this->adapter);
        $info_fotografia = $fotografia->getAllFotografiasById($id_Gpo_Grupo_Valores, 1);

        $idUsuarioTemp = "0";
        if (is_array($getAllMatriz) || is_object($getAllMatriz)) {
            foreach ($getAllMatriz as $userMatriz) {
                $correo = $userMatriz->mat_Correo;
                $telegram = $userMatriz->mat_Telegram;
                $push = $userMatriz->mat_Push;
                $idTelegram = $userMatriz->id_telegram;
                $Token = $userMatriz->Token;
                $correoUsuario = $userMatriz->correo_Usuario;
                $nombreUsuario = $userMatriz->nombre_Usuario;
                $apellidoUsuario = $userMatriz->apellido_Usuario;
                $idUsuario = $userMatriz->mat_Id_Usuario;

                //ES DE AASAPP Para el guardado de Notificaciones de otros reportes a traves de matrizcomunicacion
                switch ($getAllReportesLlenado[0]->tipo_Reporte) {
                    case 0: //reportes
                        $tipo_Reporte = 6;
                        break;
                    case 1: // Incidencias
                        $tipo_Reporte = 4;
                        break;
                    case 4: // Seguimientos Incidencias
                        $tipo_Reporte = 5;
                        break;
                }

                //if ($this->url == 'supervisor.uno') {
                if ($idUsuario != $idUsuarioTemp)
                    $this->guadarNotificacionMatrizComunicacion($userMatriz->mat_Id_Usuario, $getAllReportesLlenado[0]->id_Usuario, $getAllReportesLlenado[0]->id_Gpo_Valores_Reporte, $tipo_Reporte);

                if ($idUsuario != $idUsuarioTemp)
                    $this->medio_Notificacion($userMatriz->mat_Id_Usuario, $telegram, $correo, 0, $push, $getAllReportesLlenado, $idTelegram, $Token, $correoUsuario, $nombreUsuario, $apellidoUsuario, $info_fotografia, $id_Proyecto, $id_EmpresaGral, $nombreCarpeta);
                else
                    $this->medio_Notificacion($userMatriz->mat_Id_Usuario, 0, 0, 0, $push, $getAllReportesLlenado, $idTelegram, $Token, $correoUsuario, $nombreUsuario, $apellidoUsuario, $info_fotografia, $id_Proyecto, $id_EmpresaGral, $nombreCarpeta);

                $idUsuarioTemp = $idUsuario;
                //}
            }
        }
    }


    // ***************** Funcion Ayxiliar de Notificaciones (TELEGRAM, CORREO, PUSH) ************************
    public function medio_Notificacion($id_Usuario, $telegram, $correo, $whatsapp, $push, $datos_reporte, $id_telegram, $token_Movil, $correo_Usuario, $nombre_Usuario, $apellido_Usuario, $fotografias, $id_Proyecto, $id_EmpresaGral, $nombreCarpeta)
    {
        if ($this->url == URL_DESARROLLO)
            $server = 'https://' . URL_DESARROLLO . '/supervisor/' . $nombreCarpeta;
        else
            $server = 'https://' . URL_PRODUCCION . '/supervisor/' . $nombreCarpeta;

        if ($telegram == 1) {
            if ($id_telegram != "") {
                $valores = new Campo($this->adapter);
                $reportesIncidencia = $valores->getAllCatReportesByTipoReporte($id_Proyecto);

                if ($reportesIncidencia == '' || empty($reportesIncidencia)) {
                    $reportes = $valores->getAllReportesByIdProyecto($id_Proyecto);
                    foreach ($reportes as $reporte) {
                        if ($datos_reporte[0]->id_Reporte == $reporte->id_Reporte)
                            $existe = true;
                    }
                } else {
                    $existe = false;
                    foreach ($reportesIncidencia as $reporte) {
                        if ($datos_reporte[0]->id_Reporte == $reporte->id_Reporte)
                            $existe = true;
                    }
                }

                if ($existe) {
                    $campoTipoIncidencia = $valores->getExistCampoTipoIncidente($datos_reporte[0]->id_Gpo_Valores_Reporte);
                    if ($campoTipoIncidencia == '' || empty($campoTipoIncidencia)) {
                        $textoIncidencia = '<b>NUEVA INCIDENCIA (' . $datos_reporte[0]->nombre_Reporte . ')</b>
                    <b>Titulo: </b>' . $datos_reporte[0]->titulo_Reporte . '
                    <b>Creado por: </b>' . $datos_reporte[0]->nombre_Usuario . " " . $datos_reporte[0]->apellido_Usuario . '
                    <b>Fecha: </b>' . date("d-m-Y", strtotime($datos_reporte[0]->fecha_registro)) . '
                    <a href="' . $server . '/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' .
                            $datos_reporte[0]->id_Gpo_Valores_Reporte . '&Id_Reporte=' . $datos_reporte[0]->id_Reporte . '">Ver detalles</a>';
                    } else {
                        $textoIncidencia = '<b>NUEVA INCIDENCIA (' . $datos_reporte[0]->nombre_Reporte . ')</b>
                    <b>Titulo: </b>' . $datos_reporte[0]->titulo_Reporte . '
                    <b>Tipo de Incidente: </b>' . $datos_reporte[0]->campo_Tipo_Incidencia . '
                    <b>Creado por: </b>' . $datos_reporte[0]->nombre_Usuario . " " . $datos_reporte[0]->apellido_Usuario . '
                    <b>Fecha: </b>' . date("d-m-Y", strtotime($datos_reporte[0]->fecha_registro)) . '
                    <a href="' . $server . '/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' .
                            $datos_reporte[0]->id_Gpo_Valores_Reporte . '&Id_Reporte=' . $datos_reporte[0]->id_Reporte . '">Ver detalles</a>';
                    }
                } else {
                    $datoSelectStatus = $valores->getExistCampoSelectStatus($datos_reporte[0]->id_Gpo_Valores_Reporte);
                    if ($datoSelectStatus != '' || !empty($datoSelectStatus)) {
                        $textoIncidencia = '<b>NUEVA NOTIFICACIÓN (' . $datos_reporte[0]->nombre_Reporte . ')</b>
                    <b>Titulo: </b>' . $datos_reporte[0]->titulo_Reporte . '
                    <b>Creado por: </b>' . $datos_reporte[0]->nombre_Usuario . " " . $datos_reporte[0]->apellido_Usuario . '
                    <b>Fecha: </b>' . date("d-m-Y", strtotime($datos_reporte[0]->fecha_registro)) . '
                    <a href="' . $server . '/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' .
                            $datos_reporte[0]->id_Gpo_Valores_Reporte . '&Id_Reporte=' . $datos_reporte[0]->id_Reporte . '">Ver detalles</a>';
                    } else {
                        $textoIncidencia = '<b>' . $datos_reporte[0]->nombre_Reporte . ' (Estado de Incidencia: Atendido) </b>
                    <b>Titulo: </b>' . $datos_reporte[0]->titulo_Reporte . '
                    <b>Creado por: </b>' . $datos_reporte[0]->nombre_Usuario . " " . $datos_reporte[0]->apellido_Usuario . '
                    <b>Fecha: </b>' . date("d-m-Y", strtotime($datos_reporte[0]->fecha_registro)) . '
                    <a href="' . $server . '/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' .
                            $datos_reporte[0]->id_Gpo_Valores_Reporte . '&Id_Reporte=' . $datos_reporte[0]->id_Reporte . '">Ver detalles</a>';
                    }
                }

                $response = $this->sendMessageTelegram($id_telegram, $textoIncidencia);

                $arrayResponse = json_decode($response, true);

                // **************** GUARDAR NOTIFICACION EN BITACORA **************************************************
                /*if ($arrayResponse['ok'] == 1) {
                    $bitacoraNotificaciones->set_id_Usuario($id_Usuario);
                    $bitacoraNotificaciones->set_id_Medio_Notificacion(26);
                    $bitacoraNotificaciones->set_id_Notificacion_Detalle(4);
                    $bitacoraNotificaciones->set_id_Gpo_Valores_Reporte($datos_reporte[0]->id_Gpo_Valores_Reporte);
                    $bitacoraNotificaciones->saveNewBitacoraNotificaciones();
                }*/
                // **************** GUARDAR NOTIFICACION EN BITACORA **************************************************
            }
        }

        if ($correo == 1) {
            $proyecto = new Proyecto($this->adapter);
            $infoProyectos = $proyecto->getProyectoById($id_Proyecto);

            if (!is_null($infoProyectos->logos)) {
                $logos = json_decode($infoProyectos->logos);
            }

            if ($logos->primary != '') {
                $logos1 = '<td style="text-align: left"><img src="' . $server . '/' . $logos->primary . '" width="" height="40px"></td>';
            } else
                $logos1 = '<td style="text-align: right; width: 40px;"></td>';

            if ($logos->secondary != '') {
                $logos2 = '<td style="text-align: right"><img src="' . $server . '/' . $logos->secondary . '" width="" height="40px"></td>';
            } else
                $logos2 = '<td style="text-align: right; width: 40px;"></td>';

            $allCampos = $this->obtenerValoresCampos($datos_reporte[0]->id_Gpo_Valores_Reporte);
            $registros = "";

            $colorText = '#166D9B';
            $colorBorder = '#F15A24';

            foreach ($allCampos as $infoReporte) {
                $registros .= '
                    <div style="width: 100%; border-bottom: 1px solid ' . $colorBorder . '; margin-bottom: 1em;">
                        <table style="width: 100%">
                            <tr style="width: 100%">
                                <td style="text-align: left"> 
                                    <label style="color: ' . $colorText . ';"> <b>' . $infoReporte['nombre'] . ': </b> </label>  
                                    <label style="color: ' . $colorText . ';"> ' . $infoReporte['valor'] . ' </label>
                                </td>
                            </tr>
                        </table> 
                    </div>
                ';
            }

            $bodyPrincipal = '
            <div style="padding:0 20px;margin-right:auto;margin-left:auto">
                <table style="margin: 0 auto; width: 100%">
                  <tr>
                    ' . $logos1 . '
                    <td style=" width: 800px; min-width: 300px; max-width: 700px"></td>
                    ' . $logos2 . '
                  </tr>
                  <tr>
                    <th></th>
                    <th style="text-align: center; padding: 1em 0 2em 0; color: ' . $colorText . '"> <b> NUEVA NOTIFICACIÓN (' . $datos_reporte[0]->nombre_Reporte . ') </b> </th>
                    <th></th>
                  </tr>
                </table>              
                
                <div style="margin: 0 40px">
                  <div style="width: 100%; border-bottom: 1px solid ' . $colorBorder . '; margin-bottom: 1em;">
                    <table style="width: 100%">
                        <tr style="width: 100%">
                            <td style="text-align: left">
                                <label style="color: ' . $colorText . ';"> <b>Título: </b> </label>
                                <label style="color: ' . $colorText . ';"> ' . $datos_reporte[0]->titulo_Reporte . ' </label>
                            </td>
                        </tr>
                    </table> 
                  </div>   
                   <div style="width: 100%; border-bottom: 1px solid ' . $colorBorder . '; margin-bottom: 1em;">
                    <table style="width: 100%">
                        <tr style="width: 100%">
                            <td style="text-align: left">
                                <label style="color: ' . $colorText . ';"> <b>Realizado por: </b> </label>
                                <label style="color: ' . $colorText . ';"> ' . $datos_reporte[0]->nombre_Usuario . " " . $datos_reporte[0]->apellido_Usuario . ' </label>
                            </td>
                        </tr>
                    </table> 
                  </div>                      
                  ' . $registros . '
                </div>
                    
                <div style="margin-top: 1em; width: 100%; color: ' . $colorText . '; text-align: center">
                    <b> Para ver más detalles, dar clic en el siguiente enlace:  </b> 
                    <a href="' . $server . '/index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' .
                $datos_reporte[0]->id_Gpo_Valores_Reporte . '&Id_Reporte=' . $datos_reporte[0]->id_Reporte . '">Ver detalles</a>
                </div>                   
            </div>
            <br>
            <br>
        ';

            $sectionImg = '';
            if (is_array($fotografias) || is_object($fotografias)) {
                if ($fotografias != '' || !empty($fotografias)) {
                    foreach ($fotografias as $foto) {
                        $date = new DateTime($foto->fecha_Fotografia);
                        $fechaStr = $date->format("Ym");
                        $fechaFoto = $date->format("d-m-Y");
                        if (!file_exists($server . '/img/reportes/' . $id_EmpresaGral . "/" . $id_Proyecto . "/" . $fechaStr . '/' . $foto->nombre_Fotografia)) {
                            $sectionImg .= '
                    <div style="text-align: center; width: 100%;"> 
                        <img src="' . $server . '/img/reportes/' . $id_EmpresaGral . "/" . $id_Proyecto . "/" . $fechaStr . '/' . $foto->nombre_Fotografia . '" width="25%">
                        <h5 style="margin: 0px; text-align: center; color: ' . $colorText . ';"> Fecha: ' . $fechaFoto . '</h5>
                        <h5 style="margin: 0px; text-align: center; color: ' . $colorText . ';"> Descripción: ' . $foto->descripcion_Fotografia . '</h5>
                        <br>
                        <br>
                    </div>
                ';
                        }
                    }
                }
            }

            $cuerpo = $bodyPrincipal . $sectionImg;

            // LLAMAR FUNCION PARA ENVIAR CORREO
            $asunto = NAMEAPP . ': ' . $datos_reporte[0]->titulo_Reporte;
            $this->sendMail($correo_Usuario, $nombre_Usuario, $apellido_Usuario, $asunto, $cuerpo);
            //$this->sendMail('atorres@getitcompany.com', $nombre_Usuario, $apellido_Usuario, $asunto, $cuerpo);
        }

        if ($whatsapp == 1) {
            //echo 'Notificacion por WHATSAAP' . $id_User . ' ';
        }

        if ($push == 1) {
            if ($token_Movil != "") {
                // $id_usuario1->id_usuarionotifico != $id_usuarioSesionNotifico
                if ($id_Usuario != $datos_reporte[0]->id_Usuario) {
                    $notificaciones = new Notificaciones($this->adapter);
                    $allNotificaciones = $notificaciones->getAllNotificaciones($id_Usuario);
                    $data = array(
                        'title' => NAMEAPP . ': ' . $datos_reporte[0]->nombre_Reporte,
                        'body' => $datos_reporte[0]->titulo_Reporte,
                        'idReporte' => $datos_reporte[0]->id_Reporte,
                        'idGpo' => $datos_reporte[0]->id_Gpo_Valores_Reporte,
                        'idNotificacion' => $allNotificaciones[0]->id_notificacion
                    );

                    $res = $this->sendPushNotification($token_Movil, $data);
                }
            }

        }
    }

    function sendMessageTelegram($chatId, $text)
    {
        $TOKEN = "1070413462:AAH_vUE3xMnYVtqJVwnxrJWh5fd8LDT58Go";
        $TELEGRAM = "https://api.telegram.org:443/bot$TOKEN";

        $query = http_build_query(array(
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => "HTML"));
        $response = file_get_contents("$TELEGRAM/sendMessage?$query");
        return $response;
    }

    public function obtenerValoresCampos($id)
    {
        // Instanciar conectores
        $reportes = new ReporteLlenado($this->adapter); // Modelo Reportes Llenados

        //Consultar a Entidad Base a través del modelo, y guardar el resultado
        $datosCamposReporte = $reportes->getReporteLlenadoById($id);

        // Consultar las categorias de monitoreos
        $monitoreo = $reportes->getAllCatMonitoreo();

        $arrayValores = array();
        if (is_array($datosCamposReporte) || is_object($datosCamposReporte) && !empty($datosCamposReporte)) {
            foreach ($datosCamposReporte as $valores) {
                switch ($valores->tipo_Reactivo_Campo) {
                    case "text-nota":
                    case "textarea":
                    case "checkbox":
                    case "radio":
                    case "time":
                    case "select":
                    case "checkbox-incidencia":
                    case "text":
                        $nombre = $valores->nombre_Campo;
                        $valor = $valores->valor_Texto_Reporte;
                        break;
                    case "number":
                        $nombre = $valores->nombre_Campo;
                        $valor = $valores->valor_Entero_Reporte;
                        break;
                    case "date":
                        $nombre = $valores->nombre_Campo;
                        $text = str_replace('/', '-', $valores->valor_Texto_Reporte);
                        $date = new DateTime($text);
                        $valor = $date->format('d-m-Y');
                        break;
                    case "label":
                        $nombre = $valores->nombre_Campo;
                        $valor = "";
                        break;

                    case "check_list_asistencia":
                        $nombre = $valores->nombre_Campo;
                        $idsEmpleados = str_replace('/', ',', $valores->valor_Texto_Reporte);
                        $allEmpleadosAsistencia = $reportes->getAllEmpleadosByInIdEmpleados($idsEmpleados);

                        $empleados = array_map(function ($empleado) {
                            return trim("{$empleado->nombre} {$empleado->apellidos}");
                        }, $allEmpleadosAsistencia);

                        $valor = implode(', ', $empleados);
                        break;

                    case "select-catalogo":
                        $nombre = $valores->nombre_Campo;
                        $menucatalogo = $reportes->getCatCategoriaByIdCategoria($valores->Valor_Default);
                        foreach ($menucatalogo as $data) {
                            if ($data->idCatalogo == $valores->valor_Texto_Reporte) {
                                $valor = $data->concepto;
                            }
                        }
                        break;
                    case "select-monitoreo":
                        $nombre = $valores->nombre_Campo;
                        foreach ($monitoreo as $data) {
                            if ($data->idCatMonitoreo == $valores->valor_Texto_Reporte) {
                                $valor = $data->Concepto;
                            }
                        }
                        break;
                    case "text-cadenamiento":
                        //$cadenamiento = str_replace(".","+",$reportellenado->valor_Texto_Reporte);
                        $cadenamientos = explode(".", $valores->valor_Texto_Reporte);
                        $cadenamiento1 = $cadenamientos[0];
                        $cadenamiento2 = $cadenamientos[1];
                        $size1 = strlen($cadenamiento1);
                        $size2 = strlen($cadenamiento2);
                        if ($size1 == 0) {
                            $cadenamiento1 = "000";
                        }
                        if ($size1 == 1) {
                            $cadenamiento1 = "00" . $cadenamiento1;
                        }
                        if ($size1 == 2) {
                            $cadenamiento1 = "0" . $cadenamiento1;
                        }
                        if ($size2 == 0) {
                            $cadenamiento2 = "000";
                        }
                        if ($size2 == 1) {
                            $cadenamiento2 = "00" . $cadenamiento2;
                        }
                        if ($size2 == 2) {
                            $cadenamiento2 = "0" . $cadenamiento2;
                        }
                        $nombre = $valores->nombre_Campo;
                        $valor = $cadenamiento1 . " + " . $cadenamiento2;
                        break;
                    case "rango_fechas":
                        $fechasInicioFinal = explode(".", $valores->valor_Texto_Reporte);
                        $fechaInicio = $this->formatearFecha($fechasInicioFinal[0]);
                        $fechaFinal = $this->formatearFecha($fechasInicioFinal[1]);
                        $nombre = $valores->nombre_Campo;
                        $valor = "Desde $fechaInicio hasta $fechaFinal";
                        break;
                    default:
                        $nombre = $valores->nombre_Campo;
                        $valor = $valores->valor_Texto_Reporte;
                }

                // Añadir campos al arrelo que sera devuelto por la funcion
                array_push($arrayValores, array(
                    'nombre' => $nombre,
                    'valor' => $valor
                ));
            }
        }

        return $arrayValores;
    }

    /*************************************************** **************************************************************/
    /******************************** FUNCIONES AUXILIARES PARA ENVIAR NOTIFICACIONES *********************************/
    /*************************************************** **************************************************************/
    public function sendMail($correo_Usuario, $nombre_Usuario, $apellido_Usuario, $asunto, $cuerpo)
    {
        if ($this->url == URL_DESARROLLO) {
            $host = URL_DESARROLLO;
            $username = 'soporte@get-s.dev';
            $password = 'ch,11gI$TO}#';
        } else {
            $host = URL_PRODUCCION;
            $username = 'contacto@supervisor.uno';
            $password = '$ContactoSupervisor$';
        }

        $emisor = NAMEAPP;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host = $host;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = $username;                     // SMTP username
            $mail->Password = $password;                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            //Recipients
            $mail->setFrom($username, $emisor);
            $mail->addAddress($correo_Usuario, $nombre_Usuario . ' ' . $apellido_Usuario);     // Add a recipient
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;
            $mail->CharSet = 'UTF-8';
            $mail->send();
        } catch (Exception $e) {
            //echo "Mensaje no enviado. Error: {$mail->ErrorInfo}";
        }
    }


    /*************************************************** **************************************************************/
    /******************************** FUNCION PARA SELECCIONAR ICON DE ESTADO DE REPORTE ******************************/
    /*************************************************** **************************************************************/
    function iconosEstadoReporte($estadoReporte)
    {
        switch ($estadoReporte) {
            case 2:
                $nombre = 'Abierto';
                $clase = 'estadoReporteAbierto';
                break;
            case 7:
                $nombre = 'En proceso';
                $clase = 'estadoReporteProceso';
                break;
            case 3:
                $nombre = 'Atendido';
                $clase = 'estadoReporteAtendido';
                break;
            case 5:
                $nombre = 'Validado';
                $clase = 'estadoReporteValidado';
                break;
        }
        $icon = '<div class="' . $clase . '">  <i class="fa fa-circle sizeIconEstadoReporte" aria-hidden="true"></i> &nbsp; ' . $nombre . '  </div>';
        return $icon;
    }

    /*************************************************** **************************************************************/
    /****************************** END FUNCION PARA SELECCIONAR ICON DE ESTADO DE REPORTE ****************************/
    /*************************************************** **************************************************************/
    public function guardarBitacora($idModulo, $id_Usuario, $id_Gpo_Grupo_Valores, $accion, $accion2, $idProyecto)
    {
        $bitacora = new Bitacora($this->adapter);
        $bitacora->set_id_Modulo($idModulo);
        $bitacora->set_id_Usuario($id_Usuario);
        $bitacora->set_id_Gpo($id_Gpo_Grupo_Valores);
        $bitacora->set_accion_Bitacora($accion);
        $bitacora->set_accion_Bitacora2($accion2);
        $bitacora->setIdProyecto($idProyecto);
        return $bitacora->saveBitacora();
    }



    /*************************************************** **************************************************************/
    /***************************************** FUNCIONES PARA EL MODULO DE PROCESOS ***********************************/
    /*************************************************** **************************************************************/
    function procesosAvance($id_Gpo_Valores_Padre, $id_Gpo_Valores_Hijo, $id_Proceso)
    {
        $procesoAvance = new ProcesosAvances($this->adapter);

        $procesoAvance->set_Id_Gpo_Valores_Padre($id_Gpo_Valores_Padre);
        $procesoAvance->set_Id_Gpo_Valores_Hijo($id_Gpo_Valores_Hijo);
        $procesoAvance->set_Id_Proceso($id_Proceso);

        $allProcesosAvance = $procesoAvance->getAllProcesosAvance($id_Gpo_Valores_Padre, $id_Proceso);
        if ($allProcesosAvance != '' || !empty($allProcesosAvance)) {
            $procesoAvance->modificarProcesoAvance();
        }

        $procesoAvance->saveProcesoAvance();
    }

    function obtenerIdProceso($id_Gpo_Padre, $grupovalores, $id_Reporte)
    {
        $reportesLlenado = new ReporteLlenado($this->adapter);

        if (!empty($id_Gpo_Padre)) {
            $datosReporteLlenadoPadre = $reportesLlenado->getAllReportesLlenadosByIdGpo($id_Gpo_Padre);
            $id_Reporte_Padre = $datosReporteLlenadoPadre[0]->id_Reporte;

            $proceso = new Procesos($this->adapter);
            $allProcesos = $proceso->getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($id_Reporte_Padre, $id_Reporte);
            $idProceso = $allProcesos[0]->Id_Proceso;
            if ($allProcesos != '' || !empty($allProcesos)) {
                if ($idProceso != null)
                    $this->procesosAvance($id_Gpo_Padre, $grupovalores, $idProceso);
            }
        }
    }

    /****************************** FUNCION PARA DETERMINAR SI UN REPORTE LLENADO ES ESTRUCTURA ***********************/
    public function Estructura($id_Gpo_Valores_Padre, $id_Proyecto)
    {
        $estructura = new EstructuraProcesos($this->adapter);
        $allEstructuras = $estructura->getAllEstructuraProcesosByIdReporte($id_Proyecto, $id_Gpo_Valores_Padre);

        if (empty($allEstructuras))
            return false;
        else
            return true;
    }
    /*************************************************** **************************************************************/
    /***************************************** FUNCIONES PARA EL MODULO DE PROCESOS ***********************************/
    /*************************************************** **************************************************************/


    /*************************************************** **************************************************************/
    /*************************************** FUNCION PARA NUEVO ESQUEMA DE PROCESOS ***********************************/
    /*************************************************** **************************************************************/

    // ************************* FUNCION PARA GUARDAR EN LA TABLA DE AVANCE_ACTIVIDAD ************************************
    public function obtenerIdNodoAnterior($id_gpo_Valores, $id_Proyecto)
    {
        $avance = new AvanceActividad($this->adapter);
        return $avance->getRegistroAvanceActividad($id_gpo_Valores, $id_Proyecto)[0]->id_nodo;
    }

    // *********************************** FUNCION PARA OBTENER DATOS DE ID_NODO Y ID_GANTT ******************************
    public function obtenerDatosAuxiliares($id_Gpo_Padre, $id_Reporte, $id_Proyecto)
    {
        // ************************************* OBTENER NODO DEL $id_Gpo_Padre ****************************************
        $avance = new AvanceActividad($this->adapter);
        $registroAvanceActividad = $avance->getRegistroAvanceActividad($id_Gpo_Padre, $id_Proyecto);
        $id_nodo_padre = $registroAvanceActividad[0]->id_nodo;

        // ************************* OBTENER REGISTRO DE GANTT_VALORES POR $id_Proyecto ********************************
        $registroGantt = $avance->getIdGanttByid_proyecto($id_Proyecto);
        $id_Gantt = $registroGantt[0]->id;

        // ************** OBTENER REGISTRO DE GANTT_VALORES POR id_gantt, id_nodo_padre y idReporte ********************
        if ($id_nodo_padre) {
            $registroGanttValores = $avance->getRegistroGanttValoresByid_ganttANDid_nodo_padreANDid_reporte($id_Gantt, $id_nodo_padre, $id_Reporte);
            $id_nodo = $registroGanttValores[0]->id_nodo;

            return [$id_nodo, $id_Gantt];
        }
    }

    // ************************* FUNCION PARA GUARDAR EN LA TABLA DE AVANCE_ACTIVIDAD ************************************
    public function guardarAvanceActividad($id_Gpo_Padre, $grupovalores, $id_Reporte, $id_Proyecto)
    {
        $avance = new AvanceActividad($this->adapter);

        $datosAuxiliares = $this->obtenerDatosAuxiliares($id_Gpo_Padre, $id_Reporte, $id_Proyecto);
        $id_nodo = $datosAuxiliares[0];
        $id_Gantt = $datosAuxiliares[1];

        if ($id_nodo) {
            $avance->set_id_nodo($id_nodo);
            $avance->set_gpo_valores($grupovalores);
            $avance->set_id_Proyecto($id_Proyecto);
            $save = $avance->saveAvanceActividad();
            //$save = true;
            # Modificar estructura general
            if ($save) {
                $gantt = new Gantt($this->adapter);
                $infoJson = $avance->getJson($id_Gantt)[0]->estructura;
                $infoJson = !$infoJson ?: json_decode($infoJson);

                # Obtener información del nodo
                $infoNodo = $avance->getRegistroGanttValoresByid_ganttANDid_nodo($id_Gantt, $id_nodo);

                $rutaAcceso = array_map(function ($value) {
                    return (int)$value - 1;
                }, explode(".", $infoNodo[0]->wbs));

                $ruta = '[0]->children';
                foreach (array_slice($rutaAcceso, 1, count($rutaAcceso) - 2) as $nivel) {
                    $ruta .= "[{$nivel}]->children";
                }

                $ruta .= "[" . end($rutaAcceso) . "]->info";
                eval("\$infoJson{$ruta}->completado = true;");
                eval("\$infoJson{$ruta}->gpo_valores = \$grupovalores;");

                $gantt->setEstructura(str_replace('\\"', '\\\\"', json_encode($infoJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

                $gantt->updateEstructura($id_Gantt);
            }
        }
    }

// ************************* FUNCION PARA MODIFICAR EN LA TABLA DE AVANCE_ACTIVIDAD ************************************
    public function modificarGantt($id_Gpo_Padre, $grupovalores, $id_Reporte, $id_Proyecto, $id_nodoAnterior)
    {
        $avance = new AvanceActividad($this->adapter);
        $gantt = new Gantt($this->adapter);

        $datosAuxiliares = $this->obtenerDatosAuxiliares($id_Gpo_Padre, $id_Reporte, $id_Proyecto);
        $id_nodo = $datosAuxiliares[0];
        $id_Gantt = $datosAuxiliares[1];

        // ****************************** MODIFICAR EN LA TABLA DE avance_actividad ************************************
        if ($id_nodo && $id_nodo != $id_nodoAnterior) {
            $avance->set_id_nodo($id_nodo);
            $avance->set_gpo_valores($grupovalores);
            $save = $avance->modificarAvanceActividad();

        }

        ############################################## Actualizar JSON #################################################
        if (isset($save) && $save) {
            $infoJson = $avance->getJson($id_Gantt)[0]->estructura;
            $infoJson = !$infoJson ?: json_decode($infoJson);

            # Obtener información del nodo anterior
            if ($id_nodoAnterior)
                $infoNodo = $avance->getRegistroGanttValoresByid_ganttANDid_nodo($id_Gantt, $id_nodoAnterior);

            $modificarAvance = function ($modificar = true) use (&$infoNodo, &$infoJson) {
                if (!$infoNodo) return;
                $rutaAcceso = array_map(function ($value) {
                    return (int)$value - 1;
                }, explode(".", $infoNodo[0]->wbs));

                $ruta = '[0]->children';
                foreach (array_slice($rutaAcceso, 1, count($rutaAcceso) - 2) as $nivel) {
                    $ruta .= "[{$nivel}]->children";
                }

                $ruta .= "[" . end($rutaAcceso) . "]->info";
                if ($modificar) {
                    eval("\$infoJson{$ruta}->completado = true;");
                    eval("\$infoJson{$ruta}->gpo_valores = \$grupovalores;");
                } else {
                    eval("unset(\$infoJson{$ruta}->completado);");
                    eval("unset(\$infoJson{$ruta}->gpo_valores);");
                }
            };

            $modificarAvance(false);

            # Obtener información del nodo nuevo
            $infoNodo = $avance->getRegistroGanttValoresByid_ganttANDid_nodo($id_Gantt, $id_nodo);

            $modificarAvance();

            $gantt->setEstructura(str_replace('\\"', '\\\\"', json_encode($infoJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

            $gantt->updateEstructura($id_Gantt);
        }

    }

    /************** FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REPORTES, CAMPOS, CONFIGURACIONES DE REPORTES *********/
    public function crearRegistrarReportes($nombreReporte, $descripcionReporte, $id_Proyecto, $tipo_Reporte)
    {
        //OBTENER TODAS LAS AREAS DE LA EMPRESA
        $areas = new Area($this->adapter);
        $allAreas = $areas->getAllArea();
        $id_Areas = array();
        foreach ($allAreas as $area) {
            $id_Areas[] = $area->id_Area;
        }
        $id_AreasStr = implode(",", $id_Areas);

        $reporte = new Reporte($this->adapter);
        $allreportes = $reporte->getAllReporte($id_Proyecto);
        $reporte->set_id_Proyecto($id_Proyecto);
        $reporte->set_nombre_Reporte($nombreReporte);
        $reporte->set_descripcion_Reporte($descripcionReporte);
        $reporte->set_Areas($id_AreasStr);
        $reporte->set_tiempo_Reporte(0);
        $reporte->set_tiempo_Alarma(0);
        $reporte->set_tiempo_Revision(0);
        $reporte->set_tipo_Reporte($tipo_Reporte);
        $reporte->set_Perfiles('');
        $reporte->set_id_Seguimiento(0);
        $save = $reporte->saveNewReporte($allreportes);

        if ($save == 1) {
            $id_Reporte = $reporte->getUltimoRegistro();
            $this->crear_InsertarCampos($id_Proyecto);
            $this->insertarConfiguracionCamposReportes($id_Reporte, $id_Proyecto);
        } else {
            $getRegistroReporte = $reporte->getRegistroCatReportesByNombreReporte($nombreReporte, $id_Proyecto);
            $id_Reporte = $getRegistroReporte[0]->id_Reporte;
        }
        return $id_Reporte;
    }

    public function crear_InsertarCampos($id_Proyecto)
    {
        $campos = [
            ["nombreCampo" => "Fecha", "valorCampo" => "varchar", "tipoReactivoCampo" => "date", "Valor_Default" => ""],
            ["nombreCampo" => "Hora", "valorCampo" => "varchar", "tipoReactivoCampo" => "time", "Valor_Default" => ""],
            ["nombreCampo" => "Observaciones", "valorCampo" => "varchar", "tipoReactivoCampo" => "textarea", "Valor_Default" => ""],
            ["nombreCampo" => "Se realiza limpieza de área posterior a la ejecución", "valorCampo" => "select", "tipoReactivoCampo" => "Menú", "Valor_Default" => "NA/Sí/No"],
            ["nombreCampo" => "Fotografía (3)", "valorCampo" => "varchar", "tipoReactivoCampo" => "file", "Valor_Default" => "Foto1/Foto2/Foto3"],
        ];

        $cam = new Campo($this->adapter);
        $allcampos = $cam->getAllCampo();
        foreach ($campos as $campo) {
            $cam->set_id_Proyecto($id_Proyecto);
            $nombreCampo = $campo['nombreCampo'];
            $cam->set_nombre_Campo($nombreCampo);
            $cam->set_descripcion_Campo(str_replace(' ', '_', $nombreCampo));
            $cam->set_tipo_Valor_Campo($campo['valorCampo']);
            $cam->set_tipo_Reactivo_Campo($campo['tipoReactivoCampo']);
            $cam->set_Valor_Default($campo['Valor_Default']);
            $cam->saveNewCampoPlantilla($allcampos);
        }
    }

    public function insertarConfiguracionCamposReportes($id_Reporte, $id_Proyecto)
    {
        $campos = [
            ["nombreCampo" => "Fecha", "campoNecesario" => "1"],
            ["nombreCampo" => "Hora", "campoNecesario" => "1"],
            ["nombreCampo" => "Observaciones", "campoNecesario" => "0"],
            ["nombreCampo" => "Se realiza limpieza de área posterior a la ejecución", "campoNecesario" => "0"],
            ["nombreCampo" => "Fotografía (3)", "campoNecesario" => "0"],
        ];

        $camporeporte = new CampoReporte($this->adapter);
        foreach ($campos as $key => $campo) {
            $campoFecha = $camporeporte->getAllCampoByNombre($campo['nombreCampo']);
            $camporeporte->set_id_Proyecto($id_Proyecto);
            $camporeporte->set_id_Reporte($id_Reporte);
            $camporeporte->set_id_Campo_Reporte($campoFecha[0]->id_Campo_Reporte);
            $camporeporte->set_Campo_Necesario($campo['campoNecesario']);
            $camporeporte->set_Secuencia($key + 1);
            $camporeporte->saveNewConfiguracionPlantilla();
        }
    }
    // ************* FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REPORTES, CAMPOS, CONFIGURACIONES DE REPORTES *********
    /*************************************************** **************************************************************/


    /*************************************************** **************************************************************/
    // *************************** VALIDAR SI EXISTE REGISTRO A TRAVES DE PROCESO AUTOMATICO ***************************
    public function validarRegistroByTituloAndIdReporte($tituloRegistro, $id_Reporte)
    {
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $registroReporte = $llenadoreporte->getRegistroLlenadoByTituloReporteAndidReporte($tituloRegistro, $id_Reporte);
        if (empty($registroReporte))
            return null;
        else
            return (int)$registroReporte[0]->id_Gpo_Valores_Reporte;
    }
    // *************************** VALIDAR SI EXISTE REGISTRO A TRAVES DE PROCESO AUTOMATICO ***************************
    /*************************************************** **************************************************************/


    /*************************************************** **************************************************************/
    // *********** FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REGISTROS DE REPORTES (VALORES Y REPORTESLLENADOS) ******
    public function insertarValoresReporte($id_Reporte, $tituloReporte, $id_Gpo_Padre, $tipo_Reporte, $id_Proyecto)
    {
        $fecha = date('d-m-Y');
        date_default_timezone_set("America/Mexico_City");
        $hora = date('G:i');

        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportesllenados = $llenadoreporte->getAllReportesLlenados();
        if (count($allreportesllenados) == 0) {
            $grupovalores = 1;
        }
        if (count($allreportesllenados) != 0) {
            $ultimogrupo = $llenadoreporte->getUltimoReporteLlenado();
            $grupovalores = (int)$ultimogrupo + 1;
        }

        $configuracion = $llenadoreporte->getCampoReporteByIdReporte($id_Reporte);
        $campos = [
            ["valorTextoReporte" => $fecha],
            ["valorTextoReporte" => $hora],
            ["valorTextoReporte" => "S/O"],
            ["valorTextoReporte" => "NA"]
        ];
        $llenadoreporte = new LlenadoReporte($this->adapter);
        foreach ($campos as $key => $campo) {
            $llenadoreporte->set_id_Proyecto($id_Proyecto);
            $llenadoreporte->set_id_Configuracion_Reporte($configuracion[$key]->id_Configuracion_Reporte);
            $llenadoreporte->set_valor_Entero_Reporte('NULL');
            $llenadoreporte->set_valor_Texto_Reporte($campo['valorTextoReporte']);
            $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
            $llenadoreporte->saveNewLlenadoPlantilla();
        }

        $this->insertarReportesLlenados($grupovalores, $id_Reporte, $tituloReporte, $id_Gpo_Padre, $tipo_Reporte, $allreportesllenados);
        return $grupovalores;
    }

    public function insertarReportesLlenados($grupovalores, $id_Reporte, $tituloReporte, $id_Gpo_Padre, $tipo_Reporte, $allreportesllenados)
    {
        $id_Usuario = 1;

        $registrarreportellenado = new ReporteLlenado($this->adapter);
        $registrarreportellenado->set_id_Gpo_Valores_Reporte($grupovalores);
        $registrarreportellenado->set_id_Usuario($id_Usuario);
        $registrarreportellenado->set_id_Reporte($id_Reporte);
        $registrarreportellenado->set_titulo_Reporte($tituloReporte);
        $registrarreportellenado->set_id_Gpo_Padre($id_Gpo_Padre);
        $registrarreportellenado->set_latitud_Reporte(0);
        $registrarreportellenado->set_longitud_Reporte(0);
        $registrarreportellenado->set_clas_Reporte($tipo_Reporte);
        $registrarreportellenado->saveNewReporteLlenado($allreportesllenados);
    }
    // *********** FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REGISTROS DE REPORTES (VALORES Y REPORTESLLENADOS) ******
    /*************************************************** **************************************************************/


    /*************************************************** **************************************************************/
    // ******************* FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REGISTROS EN avance_actividad *******************
    public function insertarAvanceActividad($grupovalores, $id_nodo, $id_Proyecto)
    {
        $avance = new AvanceActividad($this->adapter);

        $avance->set_id_nodo($id_nodo);
        $avance->set_gpo_valores($grupovalores);
        $avance->set_id_Proyecto($id_Proyecto);
        $avance->saveAvanceActividad();
    }
    /*************************************************** **************************************************************/
    // ******************* FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REGISTROS EN avance_actividad *******************


    /*************************************************** **************************************************************/
    /*************************************** FUNCION PARA NUEVO ESQUEMA DE PROCESOS ***********************************/
    /*************************************************** **************************************************************/
    /****************************************** CALCULAR PORCENTAJES DEL GANTT ***************************************
     * @param $subNodos
     * @param $estructura
     */
    public function armarEstructura($subNodos, &$estructura)
    {
        foreach ($subNodos as $key => $subNodo) {
            $nodoAvance = $this->connectorDB->getGpoValoresByIdNodo($subNodo->id_nodo);
            $estructura[$key]['info'] = (object)((array)$subNodo + ['gpo_valores' => $nodoAvance[0]->gpo_valores]);
            $subNodos = $this->connectorDB->getSubNodos($subNodo->id_nodo);

            if ($subNodos) {
                $this->armarEstructura($subNodos, $estructura[$key]['children']);
            }
        }
    }


    /*************************************************** **************************************************************/
    /* **************************************** FUNCION PARA SECCION DE ASISTENCIA ********************************** */
    /*************************************************** **************************************************************/

    // ************************************** FUNCION PARA LA SECCION DE INSERTAR ASISTENCIA ***************************
    public function InsertarAsistencia($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $turnoAsistencia, $id_Proyecto, $grupovalores)
    {
        $asistencia = new Asistencia($this->adapter);
        foreach ($arrayIdsEmpleados as $idEmpleado) {
            // VALIDAR SI YA EXISTE REGISTRO DEL EMPLEADO
            $existeEmpleado = $asistencia->getEmpleadoByIdEmpleadoAndFecha($idEmpleado, $fechaAsistencia);
            $asistencia->setFecha($fechaAsistencia);
            $asistencia->setHora($horaAsistencia);
            $asistencia->setTurno($turnoAsistencia);
            $asistencia->setProyectoAsignado($id_Proyecto);
            $asistencia->setIdEmp($idEmpleado);
            if ($existeEmpleado) {
                $asistencia->setIdGpoValoresReporte($existeEmpleado[0]->id_gpo_valores_reporte);
                $asistencia->modificarStatusAsistencia(0);
            }
            $asistencia->setIdGpoValoresReporte($grupovalores);
            $asistencia->saveNewAsistencia();
        }
    }

    // *********************************** FUNCION PARA LA SECCION DE MODIFICACION DE ASISTENCIA ***********************
    public function ModificarAsistencia($idGpoValores, $turno, $arrayEmpleados)
    {
        // OBTENER TODOS LOS EMPLEADOS REGISTROS
        $empleados = new Empleados($this->adapter);
        $allEmpleados = $empleados->getAllEmpleadosByIdGpoValores($idGpoValores);
        $asistencia = new Asistencia($this->adapter);

        $fechaAsistencia = "";
        $horaAsistencia = "";
        $proyectoAsignado = "";
        foreach ($allEmpleados as $empleado) {
            $fechaAsistencia = $empleado->fecha;
            $horaAsistencia = $empleado->hora;
            $proyectoAsignado = $empleado->proyecto_asignado;
            $idEmpleado = $empleado->id_emp;
            $idStatus = $empleado->id_status;

            $clave = in_array($idEmpleado, $arrayEmpleados);

            $asistencia->setFecha($fechaAsistencia);
            $asistencia->setHora($horaAsistencia);
            if (!$clave) {
                $asistencia->setIdGpoValoresReporte($idGpoValores);
                $asistencia->setTurno($turno);
                $asistencia->setIdEmp($idEmpleado);
                $asistencia->setProyectoAsignado($proyectoAsignado);
                $asistencia->updateAsistencia(0);
            } else {
                if ($idStatus == 0) {
                    $asistencia->setIdGpoValoresReporte($idGpoValores);
                    $asistencia->setTurno($turno);
                    $asistencia->setIdEmp($idEmpleado);
                    $asistencia->setProyectoAsignado($proyectoAsignado);
                    $asistencia->updateAsistencia(1);
                } else {
                    $asistencia->setIdGpoValoresReporte($idGpoValores);
                    $asistencia->setTurno($turno);
                    $asistencia->setIdEmp($idEmpleado);
                    $asistencia->setProyectoAsignado($proyectoAsignado);
                    $asistencia->updateAsistencia($idStatus);
                }
            }
        }

        foreach ($arrayEmpleados as $idEmpleado) {
            $existeEmpleado = $empleados->getEmpleadoByIdGpoValores($idGpoValores, $idEmpleado, $fechaAsistencia);
            if (!$existeEmpleado) {
                $asistencia->setFecha($fechaAsistencia);
                $asistencia->setHora($horaAsistencia);
                $asistencia->setTurno($turno);
                $asistencia->setProyectoAsignado($proyectoAsignado);
                $asistencia->setIdGpoValoresReporte($idGpoValores);
                $asistencia->setIdEmp($idEmpleado);
                $asistencia->saveNewAsistencia();
            } else {
                $idStatus = $existeEmpleado[0]->id_status;
                $horaAsistencia = $existeEmpleado[0]->hora;
                $asistencia->setHora($horaAsistencia);
                $asistencia->setIdGpoValoresReporte($idGpoValores);
                $asistencia->setIdEmp($idEmpleado);
                $asistencia->setTurno($turno);
                $asistencia->setProyectoAsignado($proyectoAsignado);
                if ($idStatus == 0)
                    $asistencia->updateAsistencia(1);
                else
                    $asistencia->updateAsistencia($idStatus);
            }
        }
    }

    // FUNCION PARA PROCESAR E INSERTAR INFORMACION DE CONTROL DE PERSONAL
    public function procesarInformacionControlAsistencia($arrayIdsEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $id_Proyecto, $grupovalores)
    {
        $asistencia = new Asistencia($this->adapter);
        foreach ($arrayIdsEmpleados as $idEmpleado) {
            $fecha = $fechaInicial;
            while ($fecha <= $fechaFinal):
                if (!$this->validarFechaDomingo($fecha)) {
                    // VALIDAR SI YA EXISTE REGISTRO DEL EMPLEADO
                    $existeEmpleado = $asistencia->getEmpleadoByIdEmpleadoAndFecha($idEmpleado, $fecha);

                    $asistencia->setFecha($fecha);
                    $asistencia->setHora($horaAsistencia);
                    $asistencia->setTurno($motivo);
                    $asistencia->setProyectoAsignado($id_Proyecto);
                    $asistencia->setIdEmp($idEmpleado);

                    if ($existeEmpleado) {
                        $asistencia->setIdGpoValoresReporte($existeEmpleado[0]->id_gpo_valores_reporte);
                        $asistencia->modificarStatusAsistencia(0);
                    }

                    $asistencia->setIdGpoValoresReporte($grupovalores);
                    $asistencia->saveNewAsistencia();
                }
                $fecha = date("Y-m-d", strtotime($fecha . "+ 1 days"));
            endwhile;
        }
    }

    public function modificarInformacionControlAsistencia($arrayIdsEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $id_Proyecto, $grupovalores)
    {
        $empleados = new Empleados($this->adapter);
        $asistencia = new Asistencia($this->adapter);

        $allEmpleados = $empleados->getAllEmpleadosByIdGpoValores($grupovalores);

        foreach ($allEmpleados as $empleado) {
            $fechaAsistencia = $empleado->fecha;
            $idEmpleado = $empleado->id_emp;

            $asistencia->setFecha($fechaAsistencia);
            $asistencia->setHora($horaAsistencia);
            $asistencia->setProyectoAsignado($id_Proyecto);
            $asistencia->setIdGpoValoresReporte($grupovalores);
            $asistencia->setTurno($motivo);
            $asistencia->setIdEmp($idEmpleado);

            $clave = in_array($idEmpleado, $arrayIdsEmpleados);
            if (!$clave) {
                $asistencia->updateAsistencia(0);
            } else {
                if ($this->checarFechaRango($fechaInicial, $fechaFinal, $fechaAsistencia))
                    $asistencia->updateAsistencia(1);
                else
                    $asistencia->updateAsistencia(0);
            }
        }

        foreach ($arrayIdsEmpleados as $idEmpleado) {
            $fecha = $fechaInicial;
            while ($fecha <= $fechaFinal):
                if (!$this->validarFechaDomingo($fecha)) {
                    $existeEmpleado = $empleados->getEmpleadoByIdGpoValores($grupovalores, $idEmpleado, $fecha);

                    if (!$existeEmpleado) {
                        // VALIDAR SI YA EXISTE REGISTRO DEL EMPLEADO
                        $existeEmpleadoR = $asistencia->getEmpleadoByIdEmpleadoAndFecha($idEmpleado, $fecha);
                        $asistencia->setFecha($fecha);
                        $asistencia->setHora($horaAsistencia);
                        $asistencia->setTurno($motivo);
                        $asistencia->setProyectoAsignado($id_Proyecto);
                        $asistencia->setIdEmp($idEmpleado);

                        if ($existeEmpleadoR) {
                            $asistencia->setIdGpoValoresReporte($existeEmpleadoR[0]->id_gpo_valores_reporte);
                            $asistencia->modificarStatusAsistencia(0);
                        }

                        $asistencia->setIdGpoValoresReporte($grupovalores);
                        $asistencia->saveNewAsistencia();
                    } else {
                        $asistencia->setHora($horaAsistencia);
                        $asistencia->setFecha($fecha);
                        $asistencia->setProyectoAsignado($id_Proyecto);
                        $asistencia->setIdGpoValoresReporte($grupovalores);
                        $asistencia->setTurno($motivo);
                        $asistencia->setIdEmp($idEmpleado);
                        $asistencia->updateAsistencia(1);
                    }
                }
                $fecha = date("Y-m-d", strtotime($fecha . "+ 1 days"));
            endwhile;
        }
    }

    // ACTUALIZAR TABLA DE ASISTENCIA EN HORA DE SALIDA
    public function updateAsistenciaHoraSalida($arrayIdsEmpleados, $fecha, $hora, $id_Gpo_Padre)
    {
        $asistencia = new Asistencia($this->adapter);
        foreach ($arrayIdsEmpleados as $idEmpleado) {
            $asistencia->setIdEmp($idEmpleado);
            $asistencia->setIdGpoValoresReporte($id_Gpo_Padre);
            $fechaHora = date("Y-m-d H:i:s", strtotime("$fecha $hora"));
            $asistencia->updateRegisterHoraSalida($fechaHora);
        }
    }

    // ************************ FUNCION PARA VALIDAR SI UNA FECHA ES DOMINGO *******************************************
    public function validarFechaDomingo($fecha)
    {
        return date('N', strtotime($fecha)) == "7";
    }

    public function formatearIncidencia($incidencia)
    {
        return (object)[
            'tipo' => array_search(strtolower($incidencia['tipo']), [
                "D" => "descanso",
                "P" => "permiso con goce",
                "V" => "vacaciones",
                "SR" => "sin reporte",
                "I" => "incapacidad",
                "PS" => "permiso sin goce",
                "S" => "suspension",
                "O" => "oficina",
                "F" => "falta",
                "A" => "asistencia",
                "CP" => "cambio"
            ]),
            'proyecto' => $incidencia['proyecto']
        ];
    }

    function checarFechaRango($fecha_inicio, $fecha_fin, $fecha)
    {
        $fecha_inicio = strtotime($fecha_inicio);
        $fecha_fin = strtotime($fecha_fin);
        $fecha = strtotime($fecha);

        return ($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin);
    }

    /* **************************************** FUNCION PARA SECCION DE ASISTENCIA ********************************** */
    /*************************************************** **************************************************************/


    /****************** FUNCIONES PARA INSERTAR CAMPOS Y CONFIGURACIONES DE REPORTES PERSONALIZABLES ******************/
    public function crearCampos($id_Proyecto, $campos)
    {
        $cam = new Campo($this->adapter);
        $allcampos = $cam->getAllCampo();
        foreach ($campos as $campo) {
            $cam->set_id_Proyecto($id_Proyecto);
            $nombreCampo = $campo['nombreCampo'];
            $cam->set_nombre_Campo($nombreCampo);
            $cam->set_descripcion_Campo(str_replace(' ', '_', $nombreCampo));
            $cam->set_tipo_Valor_Campo($campo['valorCampo']);
            $cam->set_tipo_Reactivo_Campo($campo['tipoReactivoCampo']);
            $cam->set_Valor_Default($campo['Valor_Default']);
            $cam->saveNewCampoPlantilla($allcampos);
        }
    }

    public function configurarCamposEnReportes($id_Reporte, $id_Proyecto, $camposReporte)
    {
        $camporeporte = new CampoReporte($this->adapter);
        foreach ($camposReporte as $key => $campo) {
            $campoFecha = $camporeporte->getAllCampoByNombre($campo['nombreCampo']);
            $camporeporte->set_id_Proyecto($id_Proyecto);
            $camporeporte->set_id_Reporte($id_Reporte);
            $camporeporte->set_id_Campo_Reporte($campoFecha[0]->id_Campo_Reporte);
            $camporeporte->set_Campo_Necesario($campo['campoNecesario']);
            $camporeporte->set_Secuencia($key + 1);
            $camporeporte->saveNewConfiguracionPlantilla();
        }
    }
    // ************* FUNCIONES PARA INSERTAR DE FORMA AUTOMATICA REPORTES, CAMPOS, CONFIGURACIONES DE REPORTES *********
    /*************************************************** **************************************************************/

}
