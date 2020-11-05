<?php

require_once 'ConsultasCron.php';

//echo __DIR__;

$fecha_actual = date("Y-m-d");
//echo $fecha_actual.'<br>';

$fechaAnterior = date("Y-m-d", strtotime($fecha_actual . "- 1 days"));

$contadorReportes = ConsultasCron::contadorReportes($fechaAnterior);

print_r($contadorReportes);


/*$servername = "localhost";
$username = "supervis_superad";
$password = "B@se89*12_";
$dbname = "supervis_supervisor_InnoDB_Hmk_3";
//echo $textoPersonal;

//$textoPersonal = $_GET['texto'];

//sendMessage(262453015, $textoPersonal);
///sendMessageTelegram(298180244, $textoPersonal);
///
///

$contadorReportes = contadorReportes($fechaAnterior);

$texto = "";

$totalReportes = 0;

foreach ($contadorReportes as $contReportes){
    $nombreProyecto = $contReportes['nombreProyecto'];
    $total = $contReportes['total'];

    $texto .= $nombreProyecto ." = ". $total ."\n";

    $totalReportes += $total;
}

$textoFinal = "El dia $fechaAnterior se registraron $totalReportes nuevos reportes \n $texto";

$textoPersonal = 'El día ' . $fechaAnterior . ' se registraron ' . $contadorReportes . ' nuevos reportes';

//echo $textoFinal;
sendMessageTelegram(262453015, $textoFinal);
sendMessageTelegram(298180244, $textoFinal);
sendMessageTelegram(997510023, $textoFinal);


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


function contadorReportes($fecha)
{
    global $servername, $dbname, $username, $password;

    $consulta = "SELECT p.id_Proyecto,p.nombre_Proyecto as nombreProyecto, (SELECT COUNT(vwrl.id_Gpo_Valores_Reporte) FROM VW_getAllReportesLlenados vwrl 
                        WHERE vwrl.id_Proyecto = p.id_Proyecto AND vwrl.id_Status_Elemento = 1 AND vwrl.tipo_Reporte IN (0,1,4,6) 
                        AND vwrl.fecha_registro >= '$fecha 00:00:00' AND vwrl.fecha_registro <= '$fecha 23:59:59') total
                        FROM Proyectos p
                        WHERE p.id_Status_Proyecto = 1
                    group by p.id_Proyecto;";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare($consulta);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}*/