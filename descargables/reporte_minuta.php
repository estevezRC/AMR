<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;


require_once '../config/global.php';
require '../vendor/autoload.php';

/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/

require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
//require_once '../core/FuncionesCompartidas.php';
require_once '../core/FormatosCorreo.php';
require_once '../core/CalculosCompartidos.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/EstructuraProcesos.php';
require_once '../model/AvanceActividad.php';
require_once '../model/Usuario.php';
require_once '../model/CampoReporte.php';

session_start();
$id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
$area = $_SESSION[ID_AREA_SUPERVISOR];

$Conectar = new Conectar();
$adapter = $Conectar->conexion();

$EntidadBase = new EntidadBase('tabla', $adapter);
$funciones = new FormatosCorreo();



$phpWord = new PhpWord();
$phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

$id_gpo_Valores = $_GET['gpo'] ?? 32;

$allreportellenado = $EntidadBase->getReporteLlenadoById($id_gpo_Valores);
$campos = $funciones->obtenerValoresReporteLlenado($id_gpo_Valores);

//print_r($campos);

/*echo '<pre>';
print_r($allreportellenado);
print_r($campos);
echo '</pre>';
die();*/

$nombreReporte = $allreportellenado[0]->nombre_Reporte;
$tituloReporte = $allreportellenado[0]->titulo_Reporte;

//OBTENER LOS COMENTARIOS
$allcomentarios = $EntidadBase->getAllComentariosReporte($id_gpo_Valores);

$fechaArchivo = substr($allreportellenado[0]->fecha_registro, 0, 10);
$fecha_registro = $EntidadBase->formatearFecha($allreportellenado[0]->fecha_registro);

$fecha = $campos[0]['valor'];
$horaInicio = $campos[1]['valor'];

$lugar = $campos[2]['valor'];
$participantes = $campos[4]['valor'];
$asusntos = $campos[5]['valor'];
$acuerdosArray = $campos[6]['valor'];

/*echo '<pre>';
print_r($acuerdosArray);
echo '</pre>';*/

$fontStyleb13 = array(
    'name' => 'Arial',
    'size' => 13,
    'bold' => true,
    'color' => '034667'
);

$fontStyleb12 = array(
    'name' => 'Arial',
    'size' => 12,
    'bold' => true,
    'color' => '034667'
);

$fontStyleb13Right = array(
    'name' => 'Arial',
    'size' => 13,
    'bold' => true,
    'color' => '034667',
    'align' => 'right'
);

$center = array('align' => 'center');

$fontStyleb11 = array(
    'name' => 'Arial',
    'size' => 11,
    'bold' => false,
    'color' => '034667'
);

$fontStyleb11bold = array(
    'name' => 'Arial',
    'size' => 11,
    'bold' => true,
    'color' => '034667'
);

$fontStyleb10 = array(
    'name' => 'Arial',
    'size' => 10,
    'bold' => false,
    'color' => '034667'
);

$fontStyleHeader = array(
    'name' => 'Verdana',
    'bold' => true,
    'color' => '000000',
    'size' => 8,
    'alignment' => 'center');

$verdana9 = array(
    'name' => 'Verdana',
    'bold' => true,
    'color' => '6F605A',
    'size' => 9,
    'alignment' => 'center');

$styleTablesCenter = array(
    'valign' => 'center',
    'vMerge' => 'restart',
    'borderSize' => '1',
    'color' => '6F605A');

$styleTables = array(
    'vMerge' => 'restart',
    'borderSize' => '1',
    'color' => '6F605A');

$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);

// Begin code
$section = $phpWord->addSection(array('paperSize' => 'Letter', 'marginLeft' => 1300, 'marginRight' => 1300, 'marginTop' => 1200, 'marginBottom' => 1050,'borderColor' => '6F605A', 'borderSize' => 12));
//$section = $phpWord->addSection(array('borderColor' => '00FF00', 'borderSize' => 12));

/*-------------------------------------------- Encabezado con Logos -------------------------------------------------*/
$proyecto = $EntidadBase->getProyectoById($id_Proyecto);

if (!is_null($proyecto->logos)) {
    $logos = (array)json_decode($proyecto->logos);
}

$header = $section->addHeader();
$tableHeaderStyle = array('borderSize' => 1);

$table = $header->addTable('header');

$row = $table->addRow(600);
if (is_file('../img/SUP_Digital_Web_Desktop-02.png')) {
    $table->addCell(3000,array('vMerge' => 'restart', 'valign' => 'center','borderSize' => 3))->addImage('../img/SUP_Digital_Web_Desktop-02.png',
        array('height' => 33, 'align' => 'left'));
}
$row->addCell(6000, array('valign' => 'center','gridSpan' => 2, 'vMerge' => 'restart','borderSize' => 3))->addText('MINUTA DE REUNIÓN',['bold' => true, 'color' => '000000', 'size' => 12, 'alignment' => 'center'],$cellHCentered);
$row->addCell(3000,array('valign' => 'bottom','borderTopSize' => 3,'borderRightSize' => 3))
    ->addText('CEM-DIR-FOR-01',$fontStyleHeader,$cellHCentered);

$row = $table->addRow();
$row->addCell(3000, array('vMerge' => 'continue','borderSize' => 3));
$row->addCell(6000, array('vMerge' => 'continue', 'gridSpan' => 2,'borderSize' => 3));
$row->addCell(3000,array('valign' => 'top','borderRightSize' => 3))
    ->addText('Rev. 0',$fontStyleHeader,$cellHCentered);

$row = $table->addRow();
$row->addCell(3000, array('vMerge' => 'continue','borderSize' => 3));
$row->addCell(6000, array('vMerge' => 'continue', 'gridSpan' => 2,'borderSize' => 3));
$row->addCell(3000,array('valign' => 'center','borderRightSize' => 3))
    ->addText('',$fontStyleHeader,$cellHCentered);

$row = $table->addRow();
$row->addCell(3000, array('vMerge' => 'continue','borderSize' => 3));
$row->addCell(6000, array('vMerge' => 'continue', 'gridSpan' => 2,'borderSize' => 3));
$row->addCell(3000, array('valign' => 'center','borderRightSize' => 3,'borderBottomSize' => 3))
    ->addPreserveText('Hoja {PAGE} de {NUMPAGES}',$fontStyleHeader,$cellHCentered);

$section->addTextBreak();
$section->addTextBreak(2);

/*----------------------------------Se define el estilo de la tabla y celdas ----------------------------------*/

/*********************************** TABLA MOTIVO ************************************/

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$tableMotivo = $section->addTable('motivo');

$row = $tableMotivo->addRow();
$row->addCell(9000, array('valign' => 'center','vMerge' => 'restart'))->addText("Motivo de la Reunión:", $verdana9,$cellHCentered);
$row->addCell(3000, array('valign' => 'center','vMerge' => 'restart'))->addText("Fecha:", $verdana9,$cellHCentered);

$row = $tableMotivo->addRow(500);
$row->addCell(9000, $styleTablesCenter)->addText($tituloReporte, $verdana9,$cellHCentered);
$row->addCell(3000, $styleTablesCenter)->addText($fecha_registro, $verdana9,$cellHCentered);


/*********************************** TABLA MOTIVO ************************************/

$section->addTextBreak();

/*********************************** TABLA LUGAR ***********************************/

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$tableLugar = $section->addTable('lugar');

$row = $tableLugar->addRow();
$row->addCell(8000, array('valign' => 'center','vMerge' => 'restart'))->addText("Lugar:", $verdana9,$cellHCentered);
$row->addCell(2000, array('valign' => 'center','vMerge' => 'restart'))->addText("Inicio:", $verdana9,$cellHCentered);


$row = $tableLugar->addRow(500);
$row->addCell(8000, $styleTablesCenter)->addText($lugar, $verdana9,$cellHCentered);
$row->addCell(2000, $styleTablesCenter)->addText($lugar, $verdana9,$cellHCentered);



/*********************************** TABLA LUGAR ************************************/

$section->addTextBreak();

/*********************************** TABLA PARTICIPANTES ************************************/

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$tableParticipantes = $section->addTable('PARTICIPANTES');

$rowCabecera = $tableParticipantes->addRow();
$rowCabecera->addCell(500, array('vMerge' => 'restart'))->addText("No.", $verdana9,$cellHCentered);
$rowCabecera->addCell(7900, array('vMerge' => 'restart'))->addText("Asistentes:", $verdana9,$cellHCentered);
//$rowCabecera->addCell(800, array('vMerge' => 'restart'))->addText("Iniciales:", $verdana9,$cellHCentered);
$rowCabecera->addCell(1800, array('vMerge' => 'restart'))->addText("Firma:", $verdana9,$cellHCentered);
$rowCabecera->addCell(1800, array('vMerge' => 'restart'))->addText("Firma:", $verdana9,$cellHCentered);

//$datosParticipantes = $funciones->obtenerParticipantesMinuta("1,2,5");
//print_r($datosParticipantes['datos']);

//$participantes = null;//$datosParticipantes['datos'];
$contP = 1;
if($participantes) {
    $contParticipantes = count($participantes);
    foreach ($participantes AS $participante) {
        $nombreAsistente = $participante['nombre'];
        $iniciales = $participante['iniciales'];
        $empresa = $participante['empresa'];
        $puesto = $participante['puesto'];

        //echo $nombreAsistente.','.$iniciales.'<br/>';

        if ($contP % 2 == 1) {
            $rowParticpantes = $tableParticipantes->addRow(500);
            $rowParticpantes->addCell(500, $styleTablesCenter)->addText($contP, $verdana9, $cellHCentered);
            $rowParticpantes->addCell(7100, $styleTablesCenter)->addText($nombreAsistente, $verdana9, $cellHCentered);
            //$rowParticpantes->addCell(800, $styleTablesCenter)->addText($iniciales, $verdana9, $cellHCentered);
            if($contParticipantes != $contP) {

                $rowParticpantes->addCell(1800, array(
                    'valign' => 'top',
                    'vMerge' => 'restart',
                    'borderSize' => '1',
                    'color' => '6F605A'))->addText($contP, $verdana9);

                $rowParticpantes->addCell(1800, array(
                    'valign' => 'top',
                    'vMerge' => 'restart',
                    'borderSize' => '1',
                    'color' => '6F605A'))->addText($contP + 1, $verdana9);

            }else{
                $rowParticpantes->addCell(1800, array(
                    'valign' => 'top',
                    'vMerge' => 'restart',
                    'gridSpan' => 2,
                    'borderSize' => '1',
                    'color' => '6F605A'))->addText($contP, $verdana9);
            }


        } else {
            $rowParticpantes = $tableParticipantes->addRow(500);
            $rowParticpantes->addCell(500, $styleTablesCenter)->addText($contP, $verdana9, $cellHCentered);
            $rowParticpantes->addCell(7100, $styleTablesCenter)->addText($nombreAsistente, $verdana9, $cellHCentered);
            //$rowParticpantes->addCell(800, $styleTablesCenter)->addText($iniciales, $verdana9, $cellHCentered);
            $rowParticpantes->addCell(1800, array(
                'valign' => 'top',
                'vMerge' => 'continue',
                'borderSize' => '1',
                'align' => 'right',
                'color' => '6F605A'))->addText("", $verdana9);
            $rowParticpantes->addCell(1800, array(
                'valign' => 'center',
                'vMerge' => 'continue',
                'borderSize' => '1',
                'align' => 'right',
                'color' => '6F605A'))->addText("", $verdana9,array('align' => 'right'));
        }

        $contP++;
    }
}else{
    $rowParticpantes = $tableParticipantes->addRow(500);
    $rowParticpantes->addCell(500, $styleTablesCenter)->addText($contP, $verdana9, $cellHCentered);
    $rowParticpantes->addCell(7100, $styleTablesCenter)->addText("1", $verdana9, $cellHCentered);
    //$rowParticpantes->addCell(800, $styleTablesCenter)->addText("", $verdana9, $cellHCentered);
    $rowParticpantes->addCell(1800, array(
        'valign' => 'top',
        'vMerge' => 'restart',
        'borderSize' => '1',
        'color' => '6F605A'))->addText("1", $verdana9);

    $rowParticpantes->addCell(1800, array(
        'valign' => 'top',
        'vMerge' => 'restart',
        'borderSize' => '1',
        'color' => '6F605A'))->addText("2", $verdana9);

    $rowParticpantes = $tableParticipantes->addRow(500);
    $rowParticpantes->addCell(500, $styleTablesCenter)->addText($contP, $verdana9, $cellHCentered);
    $rowParticpantes->addCell(7100, $styleTablesCenter)->addText("2", $verdana9, $cellHCentered);
    //$rowParticpantes->addCell(800, $styleTablesCenter)->addText("", $verdana9, $cellHCentered);
    $rowParticpantes->addCell(1800, array(
        'valign' => 'top',
        'vMerge' => 'continue',
        'borderSize' => '1',
        'align' => 'right',
        'color' => '6F605A'))->addText("", $verdana9);
    $rowParticpantes->addCell(1800, array(
        'valign' => 'center',
        'vMerge' => 'continue',
        'borderSize' => '1',
        'align' => 'right',
        'color' => '6F605A'))->addText("", $verdana9,array('align' => 'right'));
}

/*********************************** TABLA PARTICIPANTES ************************************/

$section->addTextBreak();

/*********************************** TABLA ASUNTOS ************************************/

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$tableAsuntos = $section->addTable('asustos');

$rowCabecera = $tableAsuntos->addRow();
$rowCabecera->addCell(500, array('vMerge' => 'restart'))->addText("No.", $verdana9,$cellHCentered);
$rowCabecera->addCell(11500, array('vMerge' => 'restart'))->addText("Asusntos:", $verdana9,$cellHCentered);


if($asusntos != null || $asusntos != ""){
    $arrayAsusntos = explode("\n",$asusntos);

    if(count($arrayAsusntos)>0) {
        $contAsutos = 1;
        foreach ($arrayAsusntos as $asusnto) {

            $rowAsustos = $tableAsuntos->addRow(500);
            $rowAsustos->addCell(500, array('vMerge' => 'restart', 'borderSize' => '1'))->addText($contAsutos, $verdana9, $cellHCentered);
            $rowAsustos->addCell(2000, array('vMerge' => 'restart', 'borderSize' => '1'))->addText($asusnto, $verdana9, null);

            $contAsutos++;
        }
    }else{
        $rowAsustos = $tableAsuntos->addRow(500);
        $rowAsustos->addCell(500, array('vMerge' => 'restart', 'borderSize' => '1'))->addText("1", $verdana9, $cellHCentered);
        $rowAsustos->addCell(2000, array('vMerge' => 'restart', 'borderSize' => '1'))->addText($asusntos, $verdana9, $cellHCentered);
    }

}else {
    $rowAsustos = $tableAsuntos->addRow(500);
    $rowAsustos->addCell(500, array('vMerge' => 'restart', 'borderSize' => '1'))->addText("1", $verdana9, $cellHCentered);
    $rowAsustos->addCell(2000, array('vMerge' => 'restart', 'borderSize' => '1'))->addText("", $verdana9, $cellHCentered);
}

/*********************************** TABLA ASUNTOS ************************************/

$section->addTextBreak();

/*********************************** TABLA ACUERDOS ************************************/

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$tableAcuerdos = $section->addTable('ACUERDOS');

/*echo '<pre>';
print_r($acuerdosArray);
echo '</pre>';*/

$rowCabecera = $tableAcuerdos->addRow();
$rowCabecera->addCell(500, array('vMerge' => 'restart'))->addText("No.", $verdana9,$cellHCentered);
$rowCabecera->addCell(9700, array('vMerge' => 'restart'))->addText("Acuerdos:", $verdana9,$cellHCentered);
$rowCabecera->addCell(1800, array('vMerge' => 'restart'))->addText("Responsable:", $verdana9,$cellHCentered);

if($acuerdosArray){
    $contAcuerdos = 1;
    foreach ($acuerdosArray as $campoAcuerdo){
        foreach ($campoAcuerdo as $acuerdos){
            /*echo '<pre>';
            print_r($acuerdos);
            echo '</pre>';*/
            $idCampo = $acuerdos->idCampo;
            $valorCampo = $acuerdos->valorCampo;

            if($idCampo == "43"){
                $acuerdo = $valorCampo;
            }
            if($idCampo == "50"){
                $responsable = $valorCampo;
            }

        }


        $acuerdo = str_replace("<br>","<w:br/>",$acuerdo);//"<w:br/>",$acuerdo);
        //echo $acuerdo.','.$fechaCompromiso.','.$responsable;

        $rowAcuerdos = $tableAcuerdos->addRow(500);
        $rowAcuerdos->addCell(500, $styleTablesCenter)->addText($contAcuerdos, $verdana9, $cellHCentered);
        $rowAcuerdos->addCell(9700, array('vMerge' => 'restart', 'borderSize' => '1'))->addText($acuerdo, $verdana9);
        $rowAcuerdos->addCell(1800, $styleTablesCenter)->addText($responsable, $verdana9,$cellHCentered);

        $contAcuerdos++;
    }
}else {
    $rowAcuerdos = $tableAcuerdos->addRow(500);
    $rowAcuerdos->addCell(500, $styleTablesCenter)->addText("1", $verdana9, $cellHCentered);
    $rowAcuerdos->addCell(7900, $styleTablesCenter)->addText("", $verdana9);
    $rowAcuerdos->addCell(1800, $styleTablesCenter)->addText("", $verdana9,$cellHCentered);
}

/*************************************** TABLA ACUERDOS **************************************/

/*************************************** Se define el nombre del Archivo **********************************/
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . 'Archivo'  .'12/12/12' .$fechaArchivo . '.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

try {
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
} catch (\PhpOffice\PhpWord\Exception\Exception $e) {
}
$objWriter->save('php://output');
