<?php
/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/
require_once '../config/global.php';
require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
require_once '../core/ControladorBase.php';
require_once '../core/FuncionesCompartidas.php';
require '../' . AUTOLOAD;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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


$fecha = $fecha_hora = date("Ymd_His", strtotime("-6 hour"));
$url = $_SERVER["REQUEST_URI"];
//EXCLUIR REPORTES
switch ($tipo_Reporte) {
    case '0,1':
        $columnas = array("ID", "Tipo reporte", "Título", "Fecha", "Generado por");
        $titulo = "Reportes";
        $tipo_Reporte1 = '0';
        $noreportes = '';
        $resultado = $EntidadBase->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
        $noColumnas = 'A1:E1';
        $letras = array('A', 'B', 'C', 'D', 'E', 'F');
        break;
    case '2':
        $columnas = array("ID", "Tipo de Elemento", "Identificador", "Fecha", "Generado por");
        $titulo = 'Ubicaciones';
        $tipo_Reporte1 = '2';
        $noreportes = '';
        $resultado = $EntidadBase->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
        $noColumnas = 'A1:E1';
        $letras = array('A', 'B', 'C', 'D', 'E', 'F');
        break;
    case '3':
        $columnas = array("ID", "Tipo de Elemento", "Identificador", "Fecha", "Generado por");
        $titulo = 'Inventarios';
        $tipo_Reporte1 = '3';
        $noreportes = '';
        $resultado = $EntidadBase->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
        $noColumnas = 'A1:E1';
        $letras = array('A', 'B', 'C', 'D', 'E', 'F');
        break;
    case '4':
        $columnas = array("ID", "Tipo de documento", "Folio", "Fecha", "Generado por");
        $titulo = "Documentos";
        $tipo_Reporte1 = '4';
        $noreportes = '';
        $resultado = $EntidadBase->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
        $noColumnas = 'A1:E1';
        $letras = array('A', 'B', 'C', 'D', 'E', 'F');
        break;
    case 'reportesIncidencia':
        $columnas = array("ID", "Tipo de incidencia", "Título", "Fecha", "Hora", "Generado por", "Estado de la incidencia");
        $titulo = "Reportes de Incidencia";
        $noreportes = '';
        $tipo_Reporte1 = '1';
        $resultado = $EntidadBase->getAllSeguimientoReporteIncidencia($area, $id_Proyecto, $tipo_Reporte1, $noreportes);
        $noColumnas = 'A1:G1';
        $letras = array('A', 'B', 'C', 'D', 'E', 'F', 'G');
        break;
    default:
        break;
}

//print_r($resultado);

$fileName = $titulo . "_" . $fecha . ".xlsx";
//echo $area."-".$usuario."-".$id_Proyecto."-".$tipo_Reporte."-".$noreportes;

//var_dump($resultado);
$style = array(
    'font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')),
    'fill' => array('fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => '15629D')),
    'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
);

$filas = array(
    'fill' => array('fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'E2E2E2'))
);


$objPHPExcel = new Spreadsheet();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle($noColumnas)->applyFromArray($style);

$rowCount = 1;

$x = 0;
foreach ($columnas as &$columna) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($letras[$x])->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->SetCellValue($letras[$x] . $rowCount, $columna);
    $x++;
}

//while($row = mysqli_fetch_array($resultado)){
if (is_array($resultado) || is_object($resultado)) {
    foreach ($resultado as $res) {
        $rowCount++;
        switch ($tipo_Reporte) {
            case '0,1':
            case '2':
            case '3':
            case '4':
            case 'tiket':
                if ($rowCount % 2 == 0)
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':' . 'E' . $rowCount)->applyFromArray($filas);

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $res->Id_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $res->nombre_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $res->titulo_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $ControladorBase->formatearFecha($res->Fecha2));
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $res->correo_Usuario);
                break;
            case 'bitacora':
                if ($rowCount % 2 == 0)
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':' . 'F' . $rowCount)->applyFromArray($filas);
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $res->Id_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $res->nota);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $res->titulo_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $res->descripcion);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $ControladorBase->formatearFecha($res->Fecha2));
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $res->nombre_Usuario . " " . $res->apellido_Usuario);
                break;
            case 'reportesIncidencia':
                if ($rowCount % 2 == 0)
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':' . 'G' . $rowCount)->applyFromArray($filas);

                //$columnas = array("ID", "Tipo de incidencia", "Título", "Fecha", "Hora", "Generado por", "Estado de la incidencia");
                switch ($res->id_Etapa) {
                    case 2:
                        $nombre = 'Abierto';
                        break;
                    case 7:
                        $nombre = 'En proceso';
                        break;
                    case 3:
                        $nombre = 'Atendido';
                        break;
                    case 5:
                        $nombre = 'Validado';
                        break;
                }
                $estado = $nombre;

                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $res->Id_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $res->campo_TipoIncidente);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $res->titulo_Reporte);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $ControladorBase->formatearFecha($res->Fecha2));
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $res->campo_Hora);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $res->nombre_Usuario . " " . $res->apellido_Usuario);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $estado);
                break;
        }
    }
}

$objPHPExcel->getActiveSheet()->setTitle('Seguimientos');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="01simple.xls"');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
//$objWriter = new Xlsx($objPHPExcel);
$objWriter->save('php://output');
exit;
