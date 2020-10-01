<?php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;

//require_once '../../vendor/autoload.php';
require_once '../vendor/autoload.php';

/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/
require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';

$Conectar = new Conectar();
$adapter = $Conectar->conexion();
$EntidadBase = new EntidadBase('tabla', $adapter);
session_start();

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

$id_gpo_Valores = $_GET['gpo'];
$allreportellenado = $EntidadBase->getReporteLlenadoById($id_gpo_Valores);

$nombreReporte = $allreportellenado[0]->nombre_Reporte;

$fechaArchivo = substr($allreportellenado[0]->fecha_registro, 0, 10);
$fecha_registro = $EntidadBase->formatearFecha($allreportellenado[0]->fecha_registro);

$fontStyleb13 = array(
    'name' => 'Arial',
    'size' => 13,
    'bold' => true
);

$fontStyleb12 = array(
    'name' => 'Arial',
    'size' => 12,
    'bold' => true
);

$center = array('align' => 'center');

$fontStyleb11 = array(
    'name' => 'Arial',
    'size' => 11,
    'bold' => true
);

// Begin code
$section = $phpWord->addSection(array('paperSize' => 'Letter', 'marginLeft' => 1300, 'marginRight' => 1300, 'marginTop' => 1200, 'marginBottom' => 1050));

// *********************************************************************************************************************
$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();
/*$texttitle = $section->addTextRun(array('align' => 'right'));
$texttitle->addText('Código: ' . $allreportellenado[0]->nombre_Reporte, $fontStyleb13);*/

//$section->addTextBreak();
//$section->addTextBreak();
//$section->addTextBreak();
$texttitle = $section->addTextRun(array('align' => 'center'));
$texttitle->addText($nombreReporte, $fontStyleb13);



/*$section->addTextBreak();
$texttitle = $section->addTextRun(array('align' => 'center'));
$texttitle->addText('Planta de Emergencia', $fontStyleb12);

$section->addTextBreak();
$texttitle = $section->addTextRun(array('align' => 'center'));
$texttitle->addText('Supervisión y Prueba', $fontStyleb12);

$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();
$texttitle = $section->addTextRun(array('align' => 'left'));
$texttitle->addText('Plaza de Cobro: ' . $allreportellenado[0]->Identificador, $fontStyleb12);*/
// *********************************************************************************************************************


/*-------------------------------------------- Encabezado con Logos -------------------------------------------------*/
$header = $section->addHeader();
$table = $header->addTable();
$table->addRow();
$table->addCell(4600)->addImage(
    '../img/reportech_large_logo.png',
    array('height' => 33, 'align' => 'left')
);
$table->addCell(4600)->addImage(
    '../img/logo_generico.png',
    array('height' => 30, 'align' => 'right')
);
/*----------------------------------Se define el estilo de la tabla y celdas ----------------------------------*/
$tableStyle = array(
    'borderColor' => 'D3D3D3',
    'borderSize' => 5,
    'cellMargin' => 0
);

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$i=0;
foreach ($allreportellenado as $reporteVal){
    $nombreCampo = $reporteVal->nombre_Campo;
    $tipoValor = $reporteVal->tipo_Valor_Campo;

    if($tipoValor == "varchar"){
        $valor = $reporteVal->valor_Texto_Reporte;
    }else{
        $valor = $reporteVal->valor_Entero_Reporte;
    }

    $row = $table->addRow(400, array("exactHeight" => true));
    $row->addCell(6000, array('vMerge' => 'restart'))->addText($nombreCampo, $fontStyleb13);
    $row->addCell(6000, array('vMerge' => 'restart'))->addText($valor, $fontStyleb13);
}
/*********************************************************************************************************************
$section->addTextBreak();
$section->addTextBreak();

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow();
$row->addCell(6000, array('vMerge' => 'restart'))->addText('Fecha: ' . $fecha_registro, $fontStyleb12);
$row->addCell(3000, array('gridSpan' => 2, 'vMerge' => 'restart'))->addText('Hora inicio: ' . $allreportellenado[1]->valor_Texto_Reporte, $fontStyleb12);
$row = $table->addRow();
$row->addCell(3000, array('vMerge' => 'continue'));
$row->addCell(5000)->addText('Hora final: ' . $allreportellenado[2]->valor_Texto_Reporte, $fontStyleb12);
// *********************************************************************************************************************


$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow(400, array("exactHeight" => true));
$row->addCell(6000, array('vMerge' => 'restart'))->addText('Niveles', $fontStyleb13);

$row = $table->addRow(400, array("exactHeight" => true));
$row->addCell(6000, array('vMerge' => 'restart'))->addText('        Aceite: ' . $allreportellenado[4]->valor_Texto_Reporte, $fontStyleb11);

$row = $table->addRow(400, array("exactHeight" => true));
$row->addCell(6000, array('vMerge' => 'restart'))->addText('        Diesel: ' . $allreportellenado[5]->valor_Texto_Reporte, $fontStyleb11);

$row = $table->addRow(400, array("exactHeight" => true));
$row->addCell(6000, array('vMerge' => 'restart'))->addText('        Temperatura: ' . $allreportellenado[6]->valor_Texto_Reporte, $fontStyleb11);

$row = $table->addRow(400, array("exactHeight" => true));
$row->addCell(6000, array('vMerge' => 'restart'))->addText('        Presión Aceite: ' . $allreportellenado[7]->valor_Texto_Reporte, $fontStyleb11);

$row = $table->addRow();
$row->addCell(6000, array('vMerge' => 'restart'))->addText('        Carga Acumulador: ' . $allreportellenado[8]->valor_Texto_Reporte, $fontStyleb11);
// *********************************************************************************************************************


// *********************************************************************************************************************
$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');
$row = $table->addRow();
$row->addCell(9000, array('vMerge' => 'restart'))->addText('Observaciones: ' . $allreportellenado[9]->valor_Texto_Reporte, $fontStyleb11);
// *********************************************************************************************************************
$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();
$section->addTextBreak();
// ********************************************************************************************************************

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow(700, array("exactHeight" => true));
$row->addCell(5000, array('vMerge' => 'restart'))->addText('Realizo: ', $fontStyleb11, $center);
$row->addCell(5000, array('vMerge' => 'restart'))->addText('Responsable de Mantenimiento: ', $fontStyleb11, $center);

$row = $table->addRow();
$row->addCell(5000, array('vMerge' => 'restart'))->addText('_________________________', $fontStyleb11, $center);
$row->addCell(5000)->addText('_________________________', $fontStyleb11, $center);

$row = $table->addRow();
$row->addCell(5000, array('vMerge' => 'restart'))->addText('AAS-AM: ' . $allreportellenado[0]->nombre_Usuario .' '. $allreportellenado[0]->apellido_Usuario, $fontStyleb11, $center);
$row->addCell(5000)->addText($allreportellenado[10]->valor_Texto_Reporte, $fontStyleb11, $center);
// *********************************************************************************************************************/


// ****************************************** Se define el nombre del Archivo ******************************************
$nombreDocFinal = 'AASAM-FO-SM-PR02-01_';
header("Content-Disposition: attachment; filename=" . $nombreDocFinal . $fechaArchivo . ".docx");
header('Cache-Control: max-age=0');
try {
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
} catch (\PhpOffice\PhpWord\Exception\Exception $e) {
}
$objWriter->save('php://output');