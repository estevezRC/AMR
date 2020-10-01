<?php

require 'Consultas.php';
//$response = array("estado" => "0");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $body = json_decode(file_get_contents("php://input"), true);

    $usuario = $body['usuario'];
    $token = $body['token'];

    $registra = Consultas::RegistraToken($usuario,$token);

    $response["estado"] = $registra;
    $response["mensaje"] = $usuario."***********".$token;
    echo json_encode($response);

} else {
    // required post params is missing
    $response["estado"] = "0";
    $response["error_msg"] = "No recibio datos!";
    //$response["serie"] = [$responseSerie];
    echo json_encode($response);
}
?>