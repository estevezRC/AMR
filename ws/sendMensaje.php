<?php

//require_once '../core/Conectar.php';
//require_once '../core/ControladorBase.php';
//require_once '../core/ModeloBase.php';

require_once '../config/global.php';
require_once '../core/EntidadBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../model/Notificaciones.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/MatrizComunicacion.php';
require_once '../model/Fotografia.php';
require_once '../model/Comentarios.php';
require_once '../model/Campo.php';
require_once '../model/Proyecto.php';
require_once '../model/AvanceActividad.php';
require_once '../model/Area.php';
require_once '../model/Reporte.php';
require_once '../model/CampoReporte.php';
require_once '../model/Gantt.php';

require_once '../model/Empleados.php';
require_once '../model/Asistencia.php';

require_once '../core/FormatosCorreo.php';

require_once '../model/LlenadoReporte.php';
require_once '../vendor/autoload.php';


//$this->conectar = new Conectar();
//$this->adapter = $this->conectar->conexion();
//$this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];

/*$androidTokens = ['ehZhjJ_XdGs:APA91bFzZdA05EahZU3h-DqVLzXUQJb3WSYi0tU4wW8K9FQlYMo4W61ONcWy51G32PKMYDC0yLZzfufFG-4mfDMFNGYiysB0yVoMBDnxrHnHJD9Ns27guAhAQgS0mTehkDDXcRCGYMiC'];//, 'token2'];

function sendPushNotification($to = '', $data = array()) {

    $apiKey = 'AAAAsFj7ydk:APA91bHCwyQ1MSqTUeO4QBYBt8owBBb9YMzUS2S_ikIFa6p0e3nEeiNzppPF4y_tL0UnE2l-PXQdGUnQtfckXgB8HzoaSn-MLSOuFtMyy3ylUz7le0vYJw75Z_JxVCQIdmkaDCqV5DJK';

    $fields = array(
        'to' => $to,o
        'notification' => $data,
        'data' => $data,
    );

    $headers = array('Authorization: key='.$apiKey, 'Content-Type: application/json');

    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    echo json_encode($fields);
    echo "<br><br>RESPUESTA SERVIDOR: ";

    $result = curl_exec($ch);

    curl_close($ch);

    return json_decode($result, true);
}

$to = $androidTokens[0];
//echo $to.'<br/>';

// DATOS DE LA NOTIFICACION
$data = array(
    'title' => 'Aasapp: Luis Juarez',
    'body' => 'Realizo un comentario en bitacora en linea MR',
    'idReporte' => '10',
    'idGpo' => '20482'
);

//sendPushNotification($to,  $data);*/

//$funcion = new FuncionesCompartidas();
//$funcion->sendPushNotification($to,$data);
//$funcion->sendPushNotification($to,  $data);
//$funcion->guardarNotificacion(28, 29, 11, 2);
//print_r($funcion->sendPushNotification($to,  $data));

//$funcion->sendMessageTelegram(262453015,"Hola desde reportechDesarrollo");

session_start();
//$_SESSION[NOMBRE_EMPRESA_SUPERVISOR];
//$_SESSION[ID_EMPRE_GENERAL_SUPERVISOR] = 1;
//$_SESSION[CARPETA_SUPERVISOR] = 'getit';

$funcion = new FuncionesCompartidas();

//$funcion->crearRegistrarReportes('Prueba 5 campos', 'prueba', 2, 0);
//$funcion->guardarAvanceActividad(3034, 5400, 365, 8);


//$funcion->enviarNotificaciones(4, 1, 3, 'hmk');
//$funcion->sendMessageTelegram(262453015,"Hola desde reportechDesarrollo");

//$funcion->sendPushNotification($allNotificaciones[0]->token, $data);

//$funcion->guardarAvanceActividad(2312,16785,602, 11);
$idEmpleados = "1";
$arrayEmpleados = explode("/", $idEmpleados);
$grupovalores = 9275;
$motivo = "Descanso";//"Permiso con Goce";//"Vacaciones";
$id_Proyecto = 6;

$fechaInicial = "2020-06-01";
$fechaFinal = "2020-06-08";
$horaAsistencia = "14:18:35";

//$funcion->procesarInformacionControlAsistencia($arrayEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $id_Proyecto, $grupovalores);


$idEmpleados = "2/5/11";
$arrayEmpleados = explode("/", $idEmpleados);
$grupovalores = 9251;
$motivo = "Vacaciones";//"Permiso con Goce";//"Descanso";
$id_Proyecto = 1;

$fechaInicial = "2020-06-09";
$fechaFinal = "2020-06-13";
$horaAsistencia = "14:18:35";
//
//$funcion->ModificarAsistencia($idGpoValores,$arrayEmpleados);
//var_dump($funcion->validarFechaDomingo('2020-06-07'));

//$funcion->modificarInformacionControlAsistencia($arrayEmpleados, $fechaInicial, $fechaFinal, $motivo, $id_Proyecto, $grupovalores);

//$a = $funcion->crearRegistrarReportes('Instalación de elemento', '', 7);
//echo $a; fee


/*
// ********************* OBTENER INFORMACION PARA ENVIAR POR CORREO (REPORTE MINUTA) ***************************
if ($saveReporteLlenado && $tipo_Reporte == 9) {
    $datos = $funciones->obtenerValoresReporteLlenado($grupovalores);
    $destinatarios = $funciones->obtenerCorreosParticipantesMinuta($idsParticipantes);
    $funciones->enviarMinuta($nombreReporte, $datos, $destinatarios, $nombreCarpeta);
}
*/

$datos = new FormatosCorreo();

$nombreReporte = 'Minuta de reunión';
$nombreCarpeta = 'amr';
$resultado = $datos->obtenerValoresReporteLlenado(29);
$destinatarios = $datos->obtenerCorreosParticipantesMinuta(1);
$datos->enviarMinuta($nombreReporte, $resultado, $destinatarios, $nombreCarpeta);
//print_r($resultado);

