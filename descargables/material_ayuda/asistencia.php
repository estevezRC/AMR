<?php
date_default_timezone_set("America/Mexico_City");
/* ---------------------------------------------------- CONEXION -----------------------------------------------------*/
require_once '../config/global.php';
require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
require_once '../core/ControladorBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once './../vendor/autoload.php';
require_once '../model/AvanceActividad.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

session_start();
$id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];;
$nombreProyecto = $_SESSION[NOMBRE_PROYECTO];

# Instanciar clases
$Conectar = new Conectar();
$adapter = $Conectar->conexion();
$EntidadBase = new EntidadBase('tabla', $adapter);
$ControladorBase = new ControladorBase('tabla', $adapter);
$funciones = new FuncionesCompartidas();


$spreadsheet = new Spreadsheet(); // Instancia de la libreria
$logo = new Drawing(); //Instancia de dibujo para imagenes
$richText = new RichText();
$hoja = $spreadsheet->getActiveSheet();
$alignment = new Alignment();


# CONSULTAS PARA OBTENER DATOS
// OBTENER TODOS LOS PROYECTOS
$usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
if ($usuario == 1)
    $allProyectos = $EntidadBase->getAllProyecto();
else
    $allProyectos = $EntidadBase->getAllProyectosLibres(7);

$idsProyectos = implode(",", array_map(function ($ids) {
    return $ids->id_Proyecto;
}, $allProyectos));


// VALIDAR DATOS, SINO VIENE ID_PROYECTOS OBTENER TODOS LOS PROYECTOS
$proyecto = $_REQUEST['id_proyecto'];
if ($proyecto) {
    if ($proyecto == 0) {
        $idProyecto = $idsProyectos;
        $nombreProyecto = 'Todos';
    } else {
        $idProyecto = $proyecto;
        $nombreProyecto = $EntidadBase->getProyectoById($proyecto)->nombre_Proyecto;
    }
} else {
    $idProyecto = $idsProyectos;
    $nombreProyecto = 'Todos';
}

// VALIDAR DATOS, SI NO VIENEN FECHAS, OBTENER QUINCENA ACTUAL
$fechaInicial = $_REQUEST['fecha_inicial'];
$fechaFinal = $_REQUEST['fecha_final'];

$diaInical = explode('-', $fechaInicial)[2];
$diaFinal = explode('-', $fechaFinal)[2];
$rangoDias = [(int)$diaInical, (int)$diaFinal];

if (empty($fechaInicial) && empty($fechaFinal)) {
    $fechaActual = date('Y-m');
    $dia = date('d');

    if ($dia <= 15) {
        $fechaInicial = "$fechaActual-01";
        $fechaFinal = "$fechaActual-15";
        $rangoDias = [1, 15];
    } else {
        $split_fechames = explode('-', $fechaActual);
        $diaFinalMes = cal_days_in_month(CAL_GREGORIAN, $split_fechames[1], $split_fechames[0]);
        $fechaInicial = "$fechaActual-16";
        $fechaFinal = "$fechaActual-$diaFinalMes";
        $rangoDias = [16, $diaFinalMes];
    }
}


// OBTENER TODOS LOS REGISTROS DE ASISTENCIA BY EMPLEADO
$allEmpleados = $EntidadBase->getAllEmpleadosInAsistenciaByIdProyectoAndRangoFechas($idProyecto, $fechaInicial, $fechaFinal);
if ($allEmpleados) {
    $infoEmpleadosAsistencia = array();
    foreach ($allEmpleados as $key => $empleado) {
        $incidencias = [];
        $allAsistencia = $EntidadBase->getAllAsistenciaByIdProyectoAndRangoFechas($empleado->id_emp, $idProyecto, $fechaInicial, $fechaFinal);

        // CALCULO DE HORAS LABORADAS POR EMPLEADOS
        $horasLaboradas = $EntidadBase->getAllHorasLaboradasByIdEmpleado($empleado->id_emp, $idProyecto, $fechaInicial, $fechaFinal)[0]->horas_laboradas;

        for ($inicio = $rangoDias[0]; $inicio <= $rangoDias[1]; $inicio++) {
            $fecha = date('Y-m-d', strtotime(date('Y-m', strtotime($fechaInicial)) . '-' . $inicio));

            if (!empty($allAsistencia)) {
                if (in_array($fecha, array_map(function ($registro) {
                    return $registro->fecha;
                }, $allAsistencia))) {
                    $incidencia = array_map(function ($registro) use ($empleado) {
                        return [
                            'tipo' => (function () use ($registro, $empleado) {
                                if ($registro->incidencia == "Asistencia" && $empleado->id_proyecto !== $registro->proyecto_asignado) {
                                    return 'Cambio';
                                }
                                return $registro->incidencia;
                            })(),
                            'proyecto' => $registro->nombre_proyecto
                        ];
                    },
                        array_filter($allAsistencia, function ($registro) use ($fecha) {
                            return $registro->fecha == $fecha;
                        })
                    );
                    $incidencias[] = $funciones->formatearIncidencia(reset($incidencia));
                } elseif (!$funciones->validarFechaDomingo($fecha)) {
                    if ($fecha > date('Y-m-d'))
                        $incidencias[] = (object)['tipo' => ''];
                    else
                        $incidencias[] = (object)['tipo' => 'F'];
                } else
                    $incidencias[] = (object)['tipo' => 'D'];
            } else
                $incidencias[] = (object)['tipo' => 'F'];
        }

        $infoEmpleadosAsistencia[] = [
            "infoEmpleado" => $empleado, "infoAsistencia" => $incidencias, "horasLaboradas" => $horasLaboradas
        ];
    }
}


$titulo = "CONTROL DE PERSONAL DEL " . $EntidadBase->formatearFecha($fechaInicial) . " AL " . $EntidadBase->formatearFecha($fechaFinal) . " EN EL PROYECTO " . strtoupper($nombreProyecto);

$columnsOfDays = array_merge(range('G', 'Z'), ['AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI','AJ','AK','AL','AM','AN','AO']);

// *********************************************************************************************************************

# Configurar hoja de trabajo
$hoja->getSheetView()->setZoomScale(100);
$hoja->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);
$hoja->getColumnDimension('A')->setWidth(15);
$hoja->getColumnDimension('A')->setAutoSize(false);
$hoja->getColumnDimension('B')->setWidth(10);
$hoja->getColumnDimension('B')->setAutoSize(false);
$hoja->getColumnDimension('C')->setWidth(10);
$hoja->getColumnDimension('C')->setAutoSize(false);
$hoja->getColumnDimension('D')->setWidth(10);
$hoja->getColumnDimension('D')->setAutoSize(false);
$hoja->getColumnDimension('E')->setWidth(15);
$hoja->getColumnDimension('E')->setAutoSize(false);
$hoja->getColumnDimension('F')->setWidth(15);
$hoja->getColumnDimension('F')->setAutoSize(false);

foreach ($columnsOfDays as $column) {
    $hoja->getColumnDimension($column)->setWidth(5);
    $hoja->getColumnDimension($column)->setAutoSize(false);
}

# Estilos de la hoja
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

/************************************* Combinar Celdas ***************************************
 * @param string $rango
 * @return void
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 */
$mergeRange = function (string $rango) use ($hoja) {
    $hoja->mergeCells($rango);
};

/************************************* Agregar Texto ***************************************
 * @param string $celda
 * @param string $content
 * @return void
 * @throws \PhpOffice\PhpSpreadsheet\Exception
 */
$addText = function (string $celda, string $content) use ($hoja) {
    $hoja->getCell($celda)->setValue($content);
};

/************************************* Agregar Texto ***************************************
 * @param string $celda
 * @param string $color
 * @return void
 */
$setCellColor = function (string $celda, string $color) use ($hoja) {
    $hoja->getStyle($celda)->getFill()->setFillType(Fill::FILL_SOLID);
    $hoja->getStyle($celda)->getFill()->getStartColor()->setARGB($color);
};


/************************************* ALINEA EL CONTENIDO, VERTICAL U HORIZONTAL ***************************************
 * @param string $celda
 * @param string $posicionamientoX
 * @param string $posicionamientoY
 * @return void
 */
$alignCellContent = function (
    string $celda,
    string $posicionamientoX = 'CENTER',
    string $posicionamientoY = 'CENTER'
) use ($hoja, $alignment) {
    $posicionamientoY = strtoupper($posicionamientoY);
    $posicionamientoX = strtoupper($posicionamientoX);

    $config = [
        'wrapText' => true
    ];
    eval("\$config['horizontal'] = \$alignment::HORIZONTAL_$posicionamientoX;");
    eval("\$config['vertical'] = \$alignment::VERTICAL_$posicionamientoY;");

    $hoja->getStyle($celda)->applyFromArray(['alignment' => $config]);
};

$hoja->setShowGridlines(false); // Deshabilitar Grid

$hoja->getStyle('A2:D5')->applyFromArray([
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000']
        ],
        'inside' => [
            'borderStyle' => Border::BORDER_DOTTED,
            'color' => ['argb' => '000000']
        ]
    ]
]); // Aplicar bordes

$mergeRange('A2:D2');
$addText('A2', 'Incidencias Obra');
$alignCellContent('A2');

$mergeRange('A3:B3');
$addText('A3', 'Del:');

$mergeRange('A4:B4');
$addText('A4', 'Al:');

$mergeRange('A5:B5');
$addText('A5', 'Fecha de Pago:');

$mergeRange('C3:D3');
$addText('C3', $EntidadBase->formatearFecha($fechaInicial));

$mergeRange('C4:D4');
$addText('C4', $EntidadBase->formatearFecha($fechaFinal));

$mergeRange('C5:D5');
$addText('C5', '');

$hoja->getStyle('F1:L6')->applyFromArray([
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000']
        ],
        'inside' => [
            'borderStyle' => Border::BORDER_DOTTED,
            'color' => ['argb' => '000000']
        ]
    ]
]); // Aplicar bordes

$alignCellContent('F1:T6');

$mergeRange('G1:L1');
$addText('G1', 'Asistencia');
$mergeRange('G2:L2');
$addText('G2', 'Permiso con Goce');
$mergeRange('G3:L3');
$addText('G3', 'Descanso');
$mergeRange('G4:L4');
$addText('G4', 'Falta');
$mergeRange('G5:L5');
$addText('G5', 'Vacaciones');
$mergeRange('G6:L6');
$addText('G6', 'Asistencia en otro proyecto');

$addText('F1', 'A');
$addText('F2', 'P');
$setCellColor('F2', $estilos->colors['P']);
$addText('F3', 'D');
$setCellColor('F3', $estilos->colors['D']);
$addText('F4', 'F');
$setCellColor('F4', $estilos->colors['F']);
$addText('F5', 'V');
$setCellColor('F5', $estilos->colors['V']);
$addText('F6', 'A');
$setCellColor('F6', $estilos->colors['CP']);

$hoja->getStyle('N1:T5')->applyFromArray([
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000']
        ],
        'inside' => [
            'borderStyle' => Border::BORDER_DOTTED,
            'color' => ['argb' => '000000']
        ]
    ]
]); // Aplicar bordes

$mergeRange('O1:T1');
$addText('O1', 'Sin Reporte');
$mergeRange('O2:T2');
$addText('O2', 'Incapacidad');
$mergeRange('O3:T3');
$addText('O3', 'Permiso Sin Goce');
$mergeRange('O4:T4');
$addText('O4', 'Suspension');
$mergeRange('O5:T5');
$addText('O5', 'Oficina');

$addText('N1', 'SR');
$setCellColor('N1', $estilos->colors['SR']);
$addText('N2', 'I');
$setCellColor('N2', $estilos->colors['I']);
$addText('N3', 'PS');
$setCellColor('N3', $estilos->colors['PS']);
$addText('N4', 'S');
$setCellColor('N4', $estilos->colors['S']);
$addText('N5', 'O');
$setCellColor('N5', $estilos->colors['O']);

$hoja->getStyle("A8:F8")->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000']
        ]
    ],
    'font' => [
        'bold' => true
    ]
]); //Aplicar bordes

$alignCellContent('A8:U8');
$addText('A8', 'No.');
$mergeRange('B8:D8');
$addText('B8', 'Nombre');
$addText('E8', 'Proyecto');
$addText('F8', 'Horas Laboradas');

foreach (range($rangoDias[0], $rangoDias[1]) as $key => $dia) {
    $hoja->getStyle("$columnsOfDays[$key]8")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000']
            ]
        ],
        'font' => [
            'bold' => true
        ]
    ]); // Aplicar bordes
    $addText("$columnsOfDays[$key]8", $dia);
}

foreach ($infoEmpleadosAsistencia as $key => $datosEmpleado) {
    $row = 9 + $key;
    $alignCellContent("A{$row}", 'LEFT');
    $addText("A{$row}", "{$datosEmpleado['infoEmpleado']->no_empleado}");
    $mergeRange("B$row:D$row");
    $alignCellContent("B{$row}", 'LEFT');
    $addText("B$row", "{$datosEmpleado['infoEmpleado']->nombre} {$datosEmpleado['infoEmpleado']->apellidos}");
    $alignCellContent("E{$row}", 'LEFT');
    $addText("E$row", "{$datosEmpleado['infoEmpleado']->nombre_Proyecto}");
    $alignCellContent("F{$row}", 'LEFT');
    $addText("F$row", "{$datosEmpleado['horasLaboradas']}");

    $hoja->getStyle("A$row:F$row")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000']
            ]
        ]
    ]);

    foreach (range($rangoDias[0], $rangoDias[1]) as $index => $dia) {
        $addText("{$columnsOfDays[$index]}{$row}", $datosEmpleado['infoAsistencia'][$index]->tipo == 'CP' ? 'A' : $datosEmpleado['infoAsistencia'][$index]->tipo);
        $setCellColor("$columnsOfDays[$index]$row", $estilos->colors[$datosEmpleado['infoAsistencia'][$index]->tipo] ?? 'ffffff');
        $hoja->getStyle("$columnsOfDays[$index]$row")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000']
                ]
            ]
        ]);


        if ($datosEmpleado['infoAsistencia'][$index]->tipo == 'CP') {
            try {
                $hoja->getComment("{$columnsOfDays[$index]}{$row}")
                    ->getText()->createTextRun($datosEmpleado['infoAsistencia'][$index]->proyecto);
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            }
        }

        $alignCellContent("{$columnsOfDays[$index]}{$row}");
    }
}

$spreadsheet->getActiveSheet()->setTitle('Control de Asistencia');
$spreadsheet->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$titulo}_{$fecha}.xlsx");
header('Cache-Control: max-age=0');
try {
    $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
}
$objWriter->save('php://output');
exit;
