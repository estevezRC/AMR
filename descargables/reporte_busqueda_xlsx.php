<?php
/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/
require_once '../config/global.php';
require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
require_once '../core/ControladorBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../model/LlenadoReporte.php';
require_once '../model/SeguimientoReporte.php';

require_once 'descargablesFunctions.php';

require '../' . AUTOLOAD;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/* -------------------------------------------------- VARIABLES ------------------------------------------------------*/
session_start();
$id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
$area = $_SESSION[ID_AREA_SUPERVISOR];
$usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
$tipo_Reporte = $_GET['tipo_Reporte'];
$Id_Reporte = $_GET['Id_Reporte'];
$nombre_Reporte = $_GET['nombre_Reporte'];

$Conectar = new Conectar();
$adapter = $Conectar->conexion();

$EntidadBase = new EntidadBase('tabla', $adapter);
$ControladorBase = new ControladorBase('tabla', $adapter);
$functionsExcel = new descargablesFunctions();


function fecha()
{
    $fecha_hora = date("Y-m-d H:i:s");
    $fecha_hora2 = date("Y-m-d H:i:s", strtotime("-5 hour"));
    return $fecha_hora2;
}

$ids = "NULL";
$ids_titulo = "NULL";

$palabras_clave = $_REQUEST["palabras_clave"];
/* ************************************* BUSQUEDA PALBRA CLAVE ***************************************** */
$c_palabras_clave = "' '";
if (!empty($_REQUEST["palabras_clave"])) {
    $seguimientoreporte = new LlenadoReporte($adapter);
    //PALABRA CLAVE
    $palabras_clave = $_REQUEST["palabras_clave"];
    $c_palabras_clave = "'" . $palabras_clave . "'";
    //EN TITULO
    $ids_titulo = $seguimientoreporte->getBusquedaPalabraClaveTitulo($palabras_clave, $id_Proyecto);

    if ((is_array($ids_titulo) || is_object($ids_titulo)) && count($ids_titulo) > 0) {
        foreach ($ids_titulo as $id_titulo) {
            $ids_titulo = $ids_titulo . "," . $id_titulo->id_Gpo_Valores_Reporte;
        }
        if (!empty($ids_titulo))
            $ids_titulo = substr($ids_titulo, 5);
        else
            $ids_titulo = "";
    } else
        $ids_titulo = "";

    //EN TEXTO
    $ids_palabra_clave = $seguimientoreporte->getBusquedaPalabraClave($palabras_clave, $id_Proyecto);
    if ((is_array($ids_palabra_clave) || is_object($ids_palabra_clave)) && count($ids_palabra_clave) > 0) {
        foreach ($ids_palabra_clave as $id_palabra_clave) {

            $ids = $ids . "," . $id_palabra_clave->Id_Reporte;
        }
        $ids = substr($ids, 5);
    }
    if (empty($ids_palabra_clave)) {
        $ids = 11111111;
    }
}

$ids_final = "'" . $ids . $ids_titulo . "'";

/* ***************************************** TIPO DE REPORTE ******************************************* */
if (empty($_REQUEST["id_Reporte"])) {
    $nombre_reporte = -1;
    $c_nombre_reporte = "Todos";
} else {

    $name_report = explode("|", $_REQUEST["id_Reporte"]);
    $nombre_reporte = $name_report[0];
    $c_nombre_reporte = $name_report[1];
    $tipo_Reporte = "tipo_Reporte";
    switch ($name_report[0]) {
        case('t0'):
            $tipo_Reporte = "0";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('t1'):
            $tipo_Reporte = "1";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('t2'):
            $tipo_Reporte = "2";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('t3'):
            $tipo_Reporte = "3";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('t4'):
            $tipo_Reporte = "4";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('t5'):
            $tipo_Reporte = "5";
            $nombre_reporte = "rl.id_Reporte";
            break;
        case('tipo_Reporte'):
            $tipo_Reporte = "tipo_Reporte";
            $nombre_reporte = "rl.id_Reporte";
            break;
    }

}

/* ***************************************** FECHA INICIO ******************************************* */
if (empty($_REQUEST["fecha_Inicio"])) {
    $fecha_inicio = '2010-01-01';
    $c_fecha_inicio = "Todas";
} else {
    $fecha_inicio = $_REQUEST["fecha_Inicio"];
    $c_fecha_inicio = $fecha_inicio;
}

/* ***************************************** FECHA FIN ******************************************* */
if (empty($_REQUEST["fecha_Final"])) {
    $fecha_final = fecha();
    $c_fecha_final = "Todas";
} else {
    $fecha_final = $_REQUEST["fecha_Final"];
    $c_fecha_final = $fecha_final;
}

/* ***************************************** ESTADO DE REPORTE ******************************************* */
$name_estado = explode("|", $_REQUEST["estado_reporte"]);
$estado_reporte = $name_estado[0];
$c_estado_reporte = $name_estado[1];
if ($_REQUEST["estado_reporte"] == "Estatus") {
    $c_estado_reporte = "Todos";
}

/* ***************************************** IDENTIFICADOR ******************************************* */
if (empty($_REQUEST["identificador_reporte"])) {
    $identificador_reporte = $ids_final;
    $c_identificador_reporte = "' '";
} else {
    $identificador_reporte = "'" . $_REQUEST["identificador_reporte"] . "'";
    $c_identificador_reporte = $_REQUEST["identificador_reporte"];
}
$seguimientoreporte = new SeguimientoReporte($adapter);

$allseguimientosreportes = $seguimientoreporte->getAllBusquedaReporte($nombre_reporte, $fecha_inicio,
    $fecha_final, $_SESSION[ID_AREA_SUPERVISOR], $identificador_reporte, $id_Proyecto, $tipo_Reporte);

$c_nombre_reporte = "Tipo reporte: " . $c_nombre_reporte;
$c_estado_reporte = "Estado reporte: " . $c_estado_reporte;
$c_fecha_inicio = "Fecha de inicio: " . $c_fecha_inicio;
$c_fecha_final = "Fecha final: " . $c_fecha_final;
$c_palabras_clave = "Palabras clave: " . $c_palabras_clave;
$c_identificador_reporte = "Identificador reporte: " . $c_identificador_reporte;
$mensaje_seguimiento = $c_nombre_reporte . ", " . $c_estado_reporte . ", " . $c_fecha_inicio . ", " . $c_fecha_final . ", " . $c_palabras_clave . ", " . $c_identificador_reporte . $cadena_incidencias;


$fecha = date("Y/m/d_His", strtotime("-6 hour"));
$titulo = 'Reportes_Inventarios';
$fechainicial = date('YmdHms');

// Se crea el libro de trabajo
$spreadsheet = new Spreadsheet();
// Acceder al objeto de la hoja
$hoja = $spreadsheet->getActiveSheet();
// Alineamiento de columnas
$alignment = new Alignment();


// Arreglo de estilos Y ESTILOS Y ENCABEZADOS
$estilos = (object)[
    'colors' => [
        'P' => 'fbe6cb',
        'D' => 'd4a7be',
        'F' => 'f8cacd',
        'V' => 'd8ead2',
        'SR' => 'dcd1e4',
        'I' => 'fce49e',
        'PS' => 'e49038',
        'S' => 'fffffd',
        'O' => 'cee2f9',
        'CP' => '03fffc'
    ]
];

$letrasEncabezadoS = array(
    'A','B', 'C', 'D', 'E'
);

$drawBorder = array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
    ),
);

$drawBorderSize14 = array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
    ),
    'font' => [
        'bold' => false,
        'size' => '14',
        'name' => 'Calibri'
    ]
);

$drawBorderFormatTitle = array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
    ),
    'font' => [
        'bold' => true,
        'size' => '22',
        'name' => 'Calibri'
    ]
);

////////////////////////////////////////////
// UNIR FILAS
////////////////////////////////////////////

/***
 * UNION PARA TITULOS
***/
$functionsExcel->mergeRange('A1:E1', $hoja);
$functionsExcel->mergeRange('A2:E2', $hoja);
$functionsExcel->mergeRange('A3:E3', $hoja);

////////////////////////////////////////////
//FIN UNIR FILAS
////////////////////////////////////////////

////////////////////////////////////////////
// ALINEAR FILAS
////////////////////////////////////////////
$functionsExcel->alignCellContent('A1:E1', 'CENTER', 'CENTER',$hoja, $alignment);
$functionsExcel->alignCellContent('A2:E2','CENTER', 'CENTER',$hoja, $alignment);
$functionsExcel->alignCellContent('A3:E3', 'CENTER', 'CENTER',$hoja, $alignment);
////////////////////////////////////////////
//ALINEAR UNIR FILAS
////////////////////////////////////////////

$tituloReporte = 'REPORTE DE BUSQUEDA';
$functionsExcel->addText('A1', $tituloReporte, $hoja);
$functionsExcel->addText('A3', $mensaje_seguimiento, $hoja);

///////////////////////////////////////////////////////////////////
// Cambiar Tamaños de columnas
$hoja->getRowDimension(1)->setRowHeight(60);
$hoja->getRowDimension(3)->setRowHeight(34);

// DE LA LETRA A:H SE PONDRA UNA COLUMNA DE 40 DE ANCHO
foreach ($letrasEncabezadoS as $letra) {
    $hoja->getColumnDimension($letra)->setWidth(40);
}

///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
// APLICACION DE ESTILOS A LAS COLUMNAS
$hoja->getStyle("A1")->applyFromArray($drawBorderFormatTitle);
//$hoja->getStyle("A7")->applyFromArray($drawBorderFormatTitle);
$hoja->getStyle('A1:E1')->applyFromArray($drawBorder);
$hoja->getStyle('A3:E3')->applyFromArray($drawBorderSize14);
$hoja->getStyle('A5:E5')->applyFromArray($drawBorderSize14);
//$hoja->getStyle('A7:I7')->applyFromArray($drawBorder);
//////////////////////////////////////////////////////////////////

$encabezadosTitulos = [
    'ID', // A
    'Tipo Reporte',   // B
    'Título',// C
    'Fecha',   // D
    'Generado por:', // E
];


// Contador de donde se empieza a formar informacion
$countRows = 5;
foreach ($encabezadosTitulos as $row => $titulos) {
    $functionsExcel->addText($letrasEncabezadoS[$row] . $countRows, $titulos, $hoja);
}

$urlServer = $_SERVER['SERVER_NAME'].'/supervisor/amr/';
$contador = 6;
foreach ($allseguimientosreportes as $reportes) {
    $functionsExcel->addText('A' . $contador,$reportes->Id_Reporte, $hoja);
    $hoja->getCell('A'.$contador)
        ->getHyperlink()
        ->setUrl("https://{$urlServer}index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte={$reportes->Id_Reporte}&Id_Reporte={$reportes->id_Reporte2}")
        ->setTooltip('Ver reporte');

    $functionsExcel->addText('B' . $contador, $reportes->nombre_Reporte, $hoja);
    $functionsExcel->addText('C' . $contador, $reportes->titulo_Reporte, $hoja);
    $functionsExcel->addText('D' . $contador, $reportes->Fecha2, $hoja);
    $functionsExcel->addText('E' . $contador, $reportes->nombre_Usuario . ' ' . $reportes->apellido_Usuario, $hoja);

    $hoja->getStyle('A'.$contador.':E'.$contador)->applyFromArray($drawBorder);
    $contador++;
}

$hoja->getColumnDimension('A')->setWidth(7);




//die();
$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->setTitle('Busqueda'); // Mandar titulo para la hoja de trabajo

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$titulo}_{$fechainicial}.xlsx");
header('Cache-Control: max-age=0');

try {
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
}
$writer->save('php://output');
exit;

?>