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


function nuevoUsuario($id_Usuario, $correo_Usuario, $pwd, $nombre_Usuario, $apellido_Usuario, $perfil)
{
    session_start();
    $funciones = new FuncionesCompartidas();
    $nombreApp = NAMEAPP;
    $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR] = 7;
    session_start();


    $titulo = " <h4> <strong> ¡Hola $nombre_Usuario $apellido_Usuario! </strong> </h4> <br>";

    $cuerpo = "Es un gusto para nosotros formar parte de tus proyectos, nuestro compromiso 
            es poner el mayor esfuerzo e ingenio para ofrecerte productos confiables e innovadores que simplifiquen tus 
            labores diarias. Por ello te enviamos el usuario y contraseña de ${$perfil} en ${$nombreApp} para tu ingreso 
            via web a través de tu navegador favorito. <br> <br>";

    $datosUser = "
        Página: https://supervisor.uno <br>
        Usuario: $correo_Usuario <br> 
        Contraseña: $pwd <br> <br>";

    $instruccionesInstalacion = "
        <h5> <strong> Móvil </strong> </h5>
        Te invitamos a que descargues desde Google Play la app, a través de la siguiente liga: <br>
        https://play.google.com/store/apps/details?id=developer.getitcompany.supervisoruno.arm <br> <br>
        
        Una vez instalada, te solicitará acceso a tu galería fotográfica, a tu cámara, GPS y al identificador de llamadas 
        entrantes, por favor acepta estas solicitudes para tener la mejor experiencia con nuestra solución.  
        Luego, introduce los datos de usuario y contraseña que te estamos enviando.  Al ingresar por primera ocasión, 
        en segundo plano se inicia la descarga de los distintos proyectos a los cuales tienes acceso, proceso que 
        puede llevar hasta un minuto.
        <br> <br>";

    $botTelegram = "
        <h5> <strong> Notificaciones mediante Telegram </strong> </h5>
        Nuestra plataforma se interconecta a Telegram para facilitar y dar seguridad a las notificaciones en tiempo 
        real; para activar este medio necesitas contar con una cuenta en dicho sistema de mensajería y que des 
        clic en el siguiente enlace:
        
        Valida tu cuenta en el siguiente enlace: 
        https://t.me/SupervisorUnoBot?start=${$id_Usuario}-${$_SESSION[ID_EMPRE_GENERAL_SUPERVISOR]} <br> <br>";

    $dudas = "
        <h5> <strong> ¿Tienes alguna duda? </strong> </h5>
        No dudes en comunicarte con nosotros mediante los siguientes medios: <br>
        mail: contacto@getitcompany.com <br>
        móvil: 442 1151321
        
        O consulta nuestro manual de usuario localizado bajo el icono del usuario, localizado en la extrema derecha 
        de la barra de herramientas de la plataforma web. <br> <br>";

    $despedida = "
        <h5> <strong> Lineamientos de Privacidad </strong> </h5>
        Nos tomamos muy enserio respetar tu privacidad, si deseas conocer el tratamiento que hacemos con tus datos, 
        visita la siguiente liga:
        https://${$_SERVER["SERVER_NAME"]}/supervisor/amr/descargables/material_ayuda/Manejo-Datos.pdf <br> <br>
        
        Saludos! <br> 
        Equipo Get IT!
        ";

    $mensaje = $titulo . $cuerpo . $datosUser . $instruccionesInstalacion . $botTelegram  . $dudas . $despedida;

    $funciones->sendMail($correo_Usuario, $nombre_Usuario, $apellido_Usuario, 'Nuevo registro ' . NAMEAPP, $mensaje);
}
//nuevoUsuario(1, 'atorres@getitcompany.com', '$FatoAmr$', 'Alejandro', 'Torres', 'SA');
//nuevoUsuario(1, 'franciscoalejandrotorresortiz@gmail.com', '$FatoAmr$', 'Alejandro', 'Torres', 'SA');





//$funcion->guardarAvanceActividad(2312,16785,602, 11);
//
//$idEmpleados  = "5/2/11/12";
//$arrayEmpleados = explode("/", $idEmpleados);
//$idGpoValores = 9222;
//
//$funcion->ModificarAsistencia($idGpoValores,$arrayEmpleados);
//var_dump($funcion->validarFechaDomingo('2020-06-07'));


$idEmpleados  = "1";
$arrayEmpleados = explode("/", $idEmpleados);
$grupovalores = 9275;
$motivo = "Descanso";//"Permiso con Goce";//"Vacaciones";
$id_Proyecto = 6;

$fechaInicial = "2020-06-01";
$fechaFinal = "2020-06-08";
$horaAsistencia = "14:18:35";

//$funcion->procesarInformacionControlAsistencia($arrayEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $id_Proyecto, $grupovalores);




$idEmpleados  = "2/5/11";
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



