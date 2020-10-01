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


/*
function nuevoUsuario($id_Usuario, $correo_Usuario, $pwd, $nombre_Usuario, $apellido_Usuario)
{

   $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
   $_SESSION[NOMBRE_EMPRESA_SUPERVISOR];

   $titulo = " <h4> ¡Hola " . $_SESSION[NOMBRE_EMPRESA_SUPERVISOR] . "! </h4> <br>";

   $cuerpo = "Es un gusto para nosotros el ser parte de tus emprendimientos, nuestro compromiso contigo es poner
       nuestro mayor esfuerzo e ingenio en ofrecerte siempre productos confiables e innovadores que te simplifiquen
       las actividades laborales diarias.
       Te enviamos el usuario y contraseña de administrador de " . NAMEAPP . " para que ingreses desde web a través de
       tu navegador favorito (recomendamos Chrome y Firefox).  Para la versión móvil, descárgala desde Google Play
       buscandonos como " . NAMEAPP . " <br> <br>";

   $datosUser = "Usuario: " . $correo_Usuario . "<br> Contraseña: " . $pwd . "<br> <br>";

   $botTelegram = "Valida tu cuenta en el siguiente enlace: https://t.me/SupervisorUnoBot?start=" . $id_Usuario . "-" . $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR] . "<br> <br>";

   $instruccionesInstalacion = "
       <h4>INSTALACIÓN DE LA PLATAFORMA MÓVIL.</h4>
       1. <a href='https://play.google.com/store/apps/details?id=developer.getitcompany.supervisoruno.hmk'>Descargar Aplicación</a> <br>
       2. En la primer ocasión que ingresan al sistema, les solicitará su usuario y contraseña para registrar el número de serie de su dispositivo en la base de datos del sistema. <br>
       3. Una vez sale el mensaje de “Dispositivo registrado”, cerrar la ventana e ingresar datos en el login del sistema. <br>
       4. Al cargar por primera vez la interfaz, les mostrará un menú vacío respecto a los proyectos que tiene el sistema cargados, se requiere que den clic en continuar o cancelar y el sistema iniciará con la carga de los proyectos definidos en el ambiente web. <br> <br>";

   $despedida = "Estamos atentos a cualquier duda: <br>
       mail:  contacto@getitcompany.com <br>
       móvil: 55 3412 5304 <br> <br>
       Saludos! <br>
       Equipo Get IT!";

   $mensaje = $titulo . $cuerpo . $datosUser . $botTelegram . $instruccionesInstalacion .$despedida;

   echo $mensaje;

}

nuevoUsuario(1, 'fatotorresortiz@gmail.com', 'Atorres1995', 'Alejandro', 'Torres');
//*/


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

$funcion->modificarInformacionControlAsistencia($arrayEmpleados, $fechaInicial, $fechaFinal, $motivo, $id_Proyecto, $grupovalores);


//$a = $funcion->crearRegistrarReportes('Instalación de elemento', '', 7);
//echo $a; fee



