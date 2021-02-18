<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;


require_once '../config/global.php';
require '../vendor/autoload.php';

/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/

require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../core/CalculosCompartidos.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/EstructuraProcesos.php';
require_once '../model/AvanceActividad.php';

session_start();
$id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
$area = $_SESSION[ID_AREA_SUPERVISOR];

$Conectar = new Conectar();
$adapter = $Conectar->conexion();

$EntidadBase = new EntidadBase('tabla', $adapter);
$funciones = new FuncionesCompartidas();

$phpWord = new PhpWord();
$phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

$id_gpo_Valores = $_GET['gpo'];
$allreportellenado = $EntidadBase->getReporteLlenadoById($id_gpo_Valores);
$campos = $funciones->obtenerValoresCampos($id_gpo_Valores);

$infoProyecto = $EntidadBase->getProyectoById($id_Proyecto);

$estructura = [];

//**************************************** DATOS PARA ENLAZAR REPORTES ****************************************
if ($allreportellenado[0]->tipo_Reporte == 1) {
    // REPORTES ENLAZADOS A INCIDENCIAS
    $id_seguimiento = $EntidadBase->getAllCatReportesByIdReporte($allreportellenado[0]->id_Reporte);
    $id_Reporte_Seguimiento = $id_seguimiento[0]->id_Reporte_Seguimiento;

    if ($id_Reporte_Seguimiento != 0) {
        $noreportes = 'AND rl.id_Reporte = ' . $id_Reporte_Seguimiento . ' AND rl.id_Gpo_Padre = ' . $id_gpo_Valores;
        $tipo_Reporte1 = '4';
        $allSeguimientosReportesIncidentes = $EntidadBase->getAllSeguimientoReporteIncidencia($area, $id_Proyecto, $tipo_Reporte1, $noreportes);
    }

} else {
    //OBTENER PROCENTAJE DE REPORTES ENLAZADOS AL REPORTE PADRE
    $allSeguimientosReportesIncidentes = $EntidadBase->getAllSeguimientoProcesos($id_gpo_Valores);

    # Obtener id_nodo_padre
    $calculosCompartidos = new CalculosCompartidos();
    $datosAvances = $EntidadBase->getRegistroAvanceActividad($id_gpo_Valores, $id_Proyecto);
    if ($datosAvances) {
        $idNodo = $datosAvances[0]->id_nodo;
        $porcentajeValor = $datosAvances[0]->porcentaje;

        # OBTENER ID_GANTT DEL PROYECTO
        $registroGantt = $EntidadBase->getIdGanttByid_proyecto($id_Proyecto);
        $idGantt = $registroGantt[0]->id;
        $subNodos = $EntidadBase->getSubNodos($idNodo);

        # Obtener datos generales del nodo
        $nodo = $EntidadBase->getRegistroGanttValoresByid_ganttANDid_nodo($idGantt, $idNodo);

        $porcentajes = $calculosCompartidos->calculo($subNodos, $estructura, $nodo[0]->porcentaje);
        //$porcentajes = $armarestructura($subNodos, $estructura, $nodo[0]->porcentaje);
        $porcentaje = $porcentajes->perc_nodo;
    } else {
        // REPORTES ENLAZADOS A ESTRUCTURAS
        $valor = $funciones->Estructura($id_gpo_Valores, $id_Proyecto);
        $porcentaje = 0;
        if ($valor) {
            switch ($id_gpo_Valores) {
                case 1:
                    $resultado = $calculosCompartidos->calcularPorcentaje(1);
                    $porcentaje = $resultado[0];
                    break;

                case 369:
                    $resultado = $calculosCompartidos->calcularPorcentajeEdificio(369);
                    $porcentaje = $resultado[0];
                    break;

                default:
                    $datos = $EntidadBase->calcularPorcentajeEstructura($id_gpo_Valores);
                    foreach ($datos as $dato) {
                        $porcentaje += $dato->Porcentaje1;
                    }
            }
        } else {
            // REPORTES ENLAZADOS A PROCESOS
            $allDatosReportesProcesos = $EntidadBase->getAllProcesosAvancesVinculados($id_gpo_Valores);
            foreach ($allDatosReportesProcesos as $proceso) {
                $porcentaje += $proceso->Porcentaje;
            }
        }
    }


    if ($porcentaje > 100)
        $porcentaje = 100;
    else
        $porcentaje = round($porcentaje, 2, PHP_ROUND_HALF_UP);
}

//**************************************** DATOS PARA ENLAZAR REPORTES ****************************************


$nombreReporte = $allreportellenado[0]->nombre_Reporte;
$tituloReporte = $allreportellenado[0]->titulo_Reporte;

//OBTENER IMAGNES
$info_fotografia = $EntidadBase->getAllFotografiasById($id_gpo_Valores, 1);

$sectionImg = array();
$descripcion = array();

if (is_array($info_fotografia) || is_object($info_fotografia)) {
    if ($info_fotografia != '' || !empty($info_fotografia)) {
        foreach ($info_fotografia as $foto) {
            $nombreFoto = $foto->nombre_Fotografia;
            //$nombreFoto1 = explode('.', $nombreFoto);
            $splFoto = new SplFileInfo($nombreFoto);
            $extension = $splFoto->getExtension();

            $date = new DateTime($foto->fecha_Fotografia);
            $fechaStr = $date->format("Ym");
            $fechaFoto = $date->format("d-m-Y");

            $extensionesImagen = ['jpeg', 'jpg', 'bmp', 'png'];

            if (in_array($extension, $extensionesImagen)) {
                $ruta = '../img/reportes/' . $id_Empresa . "/" . $id_Proyecto . "/" . $fechaStr . '/' . $nombreFoto;
                if (file_exists($ruta)) {
                    $sectionImg[] = $ruta;
                    $descripcion[] = $foto->descripcion_Fotografia;
                }
            }
        }
    }
}

//OBTENER LOS COMENTARIOS
$allcomentarios = $EntidadBase->getAllComentariosReporte($id_gpo_Valores);

$fechaArchivo = substr($allreportellenado[0]->fecha_registro, 0, 10);
$fecha_registro = $EntidadBase->formatearFecha($allreportellenado[0]->fecha_registro);


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

// Begin code
$section = $phpWord->addSection(array('paperSize' => 'Letter', 'marginLeft' => 1300, 'marginRight' => 1300, 'marginTop' => 1200, 'marginBottom' => 1050));

// *********************************************************************************************************************
$section->addTextBreak();

$posicionPorcentaje = $section->addTextRun(array('align' => 'right'));

$texttitle = $section->addTextRun(array('align' => 'center'));
$texttitle->addText($nombreReporte, $fontStyleb13);

$section->addTextBreak();

// *********************************************************************************************************************


/*-------------------------------------------- Encabezado con Logos -------------------------------------------------*/
$proyecto = $EntidadBase->getProyectoById($id_Proyecto);

if (!is_null($proyecto->logos)) {
    $logos = (array)json_decode($proyecto->logos);
}

$header = $section->addHeader();
$table = $header->addTable();

if (isset($logos) && ($logos['primary'] != '' || $logos['secondary'] != '')) {
    $table->addRow();
    if (is_file('../' . $logos['primary'])) {
        $table->addCell(4600)->addImage(
            '../' . $logos['primary'],

            array('height' => 33, 'align' => 'left')
        );
    }
    if (is_file('../' . $logos['secondary'])) {
        $table->addCell(4600)->addImage(
            '../' . $logos['secondary'],
            array('height' => 33, 'align' => 'right')
        );
    }
}

/*$section->addTextBreak();
$section->addTextRun(array('align' => 'right'))->addText("Porcentaje de Avance: {$porcentaje}%", ['bold' => true, 'color' => '91231C', 'size' => 12]);
$section->addTextBreak(2);*/
/*----------------------------------Se define el estilo de la tabla y celdas ----------------------------------*/
$tableStyle = array(
    'borderColor' => '034667',
    'borderSize' => 5,
    'cellMargin' => 0
);

$styleTable = array('borderSize' => 'none', 'borderColor' => '999999');
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow(400);
$row->addCell(12000, array('vMerge' => 'restart'))->addText("Titulo reporte:", $fontStyleb12);


$row1 = $table->addRow(400);
$row1->addCell(12000, array(
    'vMerge' => 'restart',
    'borderBottomColor' => 'FB6611',
    'borderBottomSize' => 6,
))->addText("    " . $tituloReporte, $fontStyleb11);
$section->addTextBreak();

$campoMultiple = function ($reporteval) {
    $actividades = [];
    $valores = [];
    foreach ($reporteval['valor']->valores->Valores as $valor) {
        foreach ($reporteval['valor']->subCampos as $subCampo) {
            foreach ($valor->Valor as $valorSubcampo) {
                if ($subCampo->id_Campo_Reporte == $valorSubcampo->idCampo) {

                    if ($subCampo->tipo_Reactivo_Campo === "select") {
                        $valores[] = new stdClass();
                        $valores[count($valores) - 1]->nombre = $subCampo->nombre_Campo;
                        $valores[count($valores) - 1]->valor = $valorSubcampo->valorCampo;
                    } elseif ($subCampo->tipo_Reactivo_Campo === "text-cadenamiento") {
                        $valores[] = new stdClass();
                        $cadenamientos = explode(".", $valorSubcampo->valorCampo);
                        $cadenamientos = array_map(function ($valor) {
                            return sprintf('%03d', (int)$valor);
                        }, $cadenamientos);
                        $cadenamiento = implode(" + ", $cadenamientos);
                        $valores[count($valores) - 1]->nombre = $subCampo->nombre_Campo;
                        $valores[count($valores) - 1]->valor = $cadenamiento;
                    } elseif ($subCampo->tipo_Reactivo_Campo === "decimal") {
                        $valores[] = new stdClass();
                        $valores[count($valores) - 1]->nombre = $subCampo->nombre_Campo;
                        $valores[count($valores) - 1]->valor = $valorSubcampo->valorCampo;
                    } elseif ($subCampo->tipo_Reactivo_Campo === "number") {
                        $valores[] = new stdClass();
                        $valores[count($valores) - 1]->nombre = $subCampo->nombre_Campo;
                        $valores[count($valores) - 1]->valor = $valorSubcampo->valorCampo;
                    }
                    elseif ($subCampo->tipo_Reactivo_Campo === "text") {
                        $valores[] = new stdClass();
                        $valores[count($valores) - 1]->nombre = $subCampo->nombre_Campo;
                        $valores[count($valores) - 1]->valor = $valorSubcampo->valorCampo;
                    }
                }
            }
        }

        array_push($actividades, $valores);
        $valores = [];
    }
    return $actividades;
};

$tableCampos1 = $section->addTable('Colspan Rowspan');
$row = $tableCampos1->addRow(400, array("exactHeight" => true));
$row->addCell(12000, array('vMerge' => 'restart'))->addText("Tramo: ", $fontStyleb12);

$tableCampos2 = $section->addTable('Colspan Rowspan');
$row1 = $tableCampos2->addRow(400, array("exactHeight" => true));
$row1->addCell(12000, array(
    'vMerge' => 'restart',
    'borderBottomColor' => 'FB6611',
    'borderBottomSize' => 6,
))->addText("  " . $infoProyecto->nombre_Proyecto , $fontStyleb11);
$section->addTextBreak();

foreach ($campos as $reporteVal) {
    if ($reporteVal['tipo'] !== "multiple") {
        $tableCampos1 = $section->addTable('Colspan Rowspan');
        $row = $tableCampos1->addRow(400, array("exactHeight" => true));
        $row->addCell(12000, array('vMerge' => 'restart'))->addText($reporteVal['nombre'] . ":", $fontStyleb12);

        $tableCampos2 = $section->addTable('Colspan Rowspan');
        $row1 = $tableCampos2->addRow(400, array("exactHeight" => true));
        $row1->addCell(12000, array(
            'vMerge' => 'restart',
            'borderBottomColor' => 'FB6611',
            'borderBottomSize' => 6,
        ))->addText("    " . $reporteVal['valor'], $fontStyleb11);
        $section->addTextBreak();
    } else {
        //var_dump($reporteVal['nombre']);
        $actividades = $campoMultiple($reporteVal);
        $tableCampos1 = $section->addTable('Colspan Rowspan');
        $row = $tableCampos1->addRow(400, array("exactHeight" => false));
        $celdaPrincipal = $row->addCell(12000, array('vMerge' => 'restart'));
        $celdaPrincipal->addText($reporteVal['nombre'], $fontStyleb11bold);

        foreach ($actividades as $key => $actividad) {
            $contador = $key +1;
            $row = $tableCampos1->addRow(400, array("exactHeight" => false));
            $celda = $row->addCell(12000, array('vMerge' => 'restart', 'align' => 'center'));
            $textrun = $celda->addTextRun();
            $textrun->addTextBreak();

            $textrun->addText("Actividad $contador", array(
                'name' => 'Arial',
                'size' => 11,
                'bold' => true,
                'color' => '034667',
                'underline' => 'single'
            ));

            $textrun->addTextBreak();

            foreach ($actividad as $subCampo) {
                $tableCampos1 = $section->addTable('Colspan Rowspan');
                $row = $tableCampos1->addRow(400, array("exactHeight" => false));
                $celda = $row->addCell(12000, array('vMerge' => 'restart'));
                //$celda->addText($subCampo->nombre . ": ", $fontStyleb11bold);
                $textrun = $celda->addTextRun();
                $textrun->addText($subCampo->nombre . ": ", $fontStyleb11bold);
                $textrun->addText($subCampo->valor, $fontStyleb10);
            }
        }
        //die();
    }
}

$i = 0;
foreach ($sectionImg as $img) {
    $section->addTextBreak();
    $tableImg = $section->addTable('Colspan Rowspan');
    $rowImg = $tableImg->addRow();
    $rowImg->addCell(12000)->addImage($img, array('height' => 180, 'align' => 'center', 'border' => 'blue'));

    $texttitle = $section->addTextRun(array('align' => 'center'));
    $section->addTextBreak();
    $texttitle->addText('Descripción: ',
        array('name' => 'Arial',
            'size' => 11,
            'bold' => true,
            'color' => '034667'));

    $texttitle->addText($descripcion[$i],
        array('name' => 'Arial',
            'size' => 11,
            'bold' => false,
            'color' => '034667'));

    $section->addTextBreak();
    $i++;
}

$section->addTextBreak();
$section->addTextBreak();

if (!empty($allcomentarios)) {
    $texttitle = $section->addTextRun(array('align' => 'center'));
    $section->addTextBreak();
    $section->addTextBreak();
    $texttitle->addText('Comentarios', $fontStyleb13);

    foreach ($allcomentarios as $comentarios) {
        $tableComentario1 = $section->addTable('Colspan Rowspan');
        $rowComentario1 = $tableComentario1->addRow(400, array("exactHeight" => true));
        $rowComentario1->addCell(12000, array('vMerge' => 'restart'))->addText($comentarios->nombre_Usuario . ' ' . $comentarios->apellido_Usuario . ' | ' . $comentarios->Fecha_Comentario . ":", $fontStyleb12);

        $tableComentario2 = $section->addTable('Colspan Rowspan');
        $rowComentario2 = $tableComentario2->addRow(400, array("exactHeight" => true));
        $rowComentario2->addCell(12000, array(
            'vMerge' => 'restart',
            'borderBottomColor' => 'FB6611',
            'borderBottomSize' => 6,
        ))->addText("    " . $comentarios->Comentario_reporte, $fontStyleb11);

        $section->addTextBreak();
    }
}

$tableStyleVinculados = array(
    'borderColor' => '034667',
    'borderSize' => 5,
    'cellMargin' => 0,
    'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
    'width' => 100 * 50,
);
$firstRowStyle = array('bgColor' => '91231C');
// Define font style for first row
$fontStyle = array('color' => 'FFFFFF', 'bold' => true, 'align' => 'center');
$styleCell = ['valign' => 'center'];
$phpWord->addTableStyle('tblReportes', $tableStyleVinculados, $firstRowStyle);

if (!empty($allSeguimientosReportesIncidentes)) {
    $texttitle = $section->addTextRun(array('align' => 'center'));
    $section->addTextBreak();
    $texttitle->addText('Reportes Vinculados', $fontStyleb13);

    $tblReportesVinculados = $section->addTable('tblReportes');
    $row = $tblReportesVinculados->addRow(400, ['tblHeader' => true]);
    $row->addCell(2000, $styleCell)->addTextRun($center)->addText('ID Ticket', $fontStyle);
    $row->addCell(2000, $styleCell)->addTextRun($center)->addText("Título de Reporte", $fontStyle);
    $row->addCell(2000, $styleCell)->addTextRun($center)->addText("Fecha", $fontStyle);
    $row->addCell(2000, $styleCell)->addTextRun($center)->addText("Hora", $fontStyle);
    $row->addCell(2000, $styleCell)->addTextRun($center)->addText("Generado Por", $fontStyle);

    foreach ($allSeguimientosReportesIncidentes as $reporteVinculado) {
        $row = $tblReportesVinculados->addRow(400);
        $row->addCell(2000, $styleCell)->addTextRun($center)->addText($reporteVinculado->Id_Reporte);
        $row->addCell(2000, $styleCell)->addTextRun($center)->addText($reporteVinculado->titulo_Reporte);
        $row->addCell(2000, $styleCell)->addTextRun($center)->addText($reporteVinculado->Fecha2);
        $row->addCell(2000, $styleCell)->addTextRun($center)->addText($reporteVinculado->campo_Hora);
        $row->addCell(2000, $styleCell)->addTextRun($center)->addText("{$reporteVinculado->nombre_Usuario} {$reporteVinculado->apellido_Usuario}");
    }
}

// ****************************************** Se define el nombre del Archivo ******************************************
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $nombreReporte . $fechaArchivo . '.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

try {
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
} catch (\PhpOffice\PhpWord\Exception\Exception $e) {
}
$objWriter->save('php://output');
