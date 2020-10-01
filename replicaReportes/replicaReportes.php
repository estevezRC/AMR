<?php
require_once "Consultas.php";


$idReporte = $_POST['id_Reporte'];
$idProyecto = $_POST['id_Proyecto'];

$nombreReporteNew = $_POST['nombreReporte'];

//echo $idReporte.",".$idProyecto;


$consulta = Consultas::getReporteJsonId($idReporte);

if ($consulta) {

    $datos["estado"] = 1;
    $datos["datos"] = $consulta;

    $jsonCreado = json_encode($consulta, JSON_UNESCAPED_UNICODE);
    //print json_encode($consulta,JSON_UNESCAPED_UNICODE);
} else {
    //print $usuarios;
    print json_encode(array(
        "estado" => 2,
        "mensaje" => "Ha ocurrido un error  " . $tabla
    ));
}

$body = json_decode($jsonCreado, true);

$Sistemas = $body['CT_Reportes'];

$idProyectoVar = $idProyecto;
$tipoReporte = "0";


for ($i = 0; $i < count($Sistemas); $i++) {

    $arraySistema = $Sistemas[$i]['Reporte'];

    $idReporte = $arraySistema['id_Reporte'];
    $nombreReporte = $arraySistema['nombre_Reporte'];
    $statusReporte = $arraySistema['id_Status_Reporte'];
    $descripcionReporte = $arraySistema['descripcion_Reporte'];
    $areasReporte = $arraySistema['Areas'];
    $tipoReporte = $arraySistema['tipo_Reporte'];

    if ($nombreReporteNew != "") {
        if ($nombreReporte != $nombreReporteNew) {
            $nombreReporte = $nombreReporteNew;
        }
    }

    $insertaReporte = Consultas::insertaReporte($idProyecto, $nombreReporte, $descripcionReporte, $areasReporte, $tipoReporte);

    if ($insertaReporte) {

        $arrayConfiguraciones = $arraySistema['configuraciones'];

        for ($j = 0; $j < count($arrayConfiguraciones); $j++) {

            $nombreCampo = $arrayConfiguraciones[$j]['nombre_Campo'];
            $tipoValor = $arrayConfiguraciones[$j]['tipo_Valor_Campo'];
            $descripcionCampo = $arrayConfiguraciones[$j]['descripcion_Campo'];
            $tipoReactivo = $arrayConfiguraciones[$j]['tipo_Reactivo_Campo'];
            $status = $arrayConfiguraciones[$j]['id_Status_Campo'];
            $valorDefault = $arrayConfiguraciones[$j]['Valor_Default'];
            $secuencia = $arrayConfiguraciones[$j]['Secuencia'];
            $necesario = $arrayConfiguraciones[$j]['Campo_Necesario'];

            $insertaCampos = Consultas::insertaCampos($idProyectoVar, $nombreCampo, $descripcionCampo, $tipoValor, $tipoReactivo, $status, $valorDefault);

            if ($insertaCampos) {
                $insertaConfiguracion = Consultas::insertaConfiguraciones($idProyecto, $insertaReporte, $insertaCampos, 1, $necesario, $secuencia);
            }

        }

    }

    if ($insertaReporte && $insertaCampos && $insertaConfiguracion) {
        echo "1";
    } else {
        echo "0";
    }

}

