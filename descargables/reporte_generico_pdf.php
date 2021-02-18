<?php
//:::::::::::::::::::::::::::::::::::::::::::::::::Cargar Dependencias::::::::::::::::::::::::::::::::::::::::::::::::::
require_once '../config/global.php';
require_once '../core/Conectar.php';
require_once '../core/EntidadBase.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/Fotografia.php';
require '../vendor/autoload.php';

session_start();
$conectar = new Conectar();
$adapter = $conectar->conexion();
$entidadBase = new EntidadBase('tabla', $adapter);

$id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
$area = $_SESSION[ID_AREA_SUPERVISOR];

$infoProyecto = $entidadBase->getProyectoById($id_Proyecto);



$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$MPDF = new \Mpdf\Mpdf([
    'setAutoTopMargin' => 'stretch',
    'setAutoBottomMargin' => 'stretch',
    'margin_top' => '0',
    'fontDir' => array_merge($fontDirs, [
        '../vendor/mpdf/mpdf/ttfonts/Roboto'
    ]),
    'fontdata' => $fontData + [
            'roboto' => [
                'R' => 'Roboto-Regular.ttf',
                'I' => 'Roboto-Italic.ttf',
                'B' => 'Roboto-Bold.ttf'
            ]
        ],
    'default_font' => 'roboto'
]);

$htmlBody = $_POST['html'];

$htmlHeader = $_POST['htmlHeader'];

$idGpoReporte = $_POST['idGpoReporte'];

$htmlHeader = str_replace('img/logo', '../img/logo', $htmlHeader);

//$estilos = file_get_contents('../css/estilos.css');

//$estilos = str_replace('h2, .h2 { font-size: 36px !important; }', 'h2, .h2 { font-size: 20px !important; }', $estilos);

$addEstilos = '.col-sm-12 {
width: 100%;
position: relative;
min-height: 1px;
} 

.col-sm-3 {
width: 25%;
}

.h1, .h2, .h3, h1, h2, h3 {
    margin-top: 20px !important;
    margin-bottom: 10px !important;
}

.col-sm-6 {
width: 50%;
}

.form-group {
    border-bottom: #18BC9C 1px solid;
    margin: 0 8px 15px 8px;
    height: max-content;
    float: left;
}

.col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {
    float: left;
}

#panel_title {
text-align: center;
height: 30px !important;
}

#titlereport {
 width: 50%;
 margin-bottom: 15px;
}

#titlereport p {
    display: block;
    margin: unset;
    font-weight: 700;
    font-size: 2rem;
    color: #91231C;
}

#logo_1, #logo_2 {
padding: unset !important;
height: 30px !important;
}

#porcentajeAvance div {
    text-align: right;
}

* {
    box-sizing: border-box;
}

.thumbnail {
    display: block;
    padding: 10px;
    margin-bottom: 20px;
    line-height: 1.42857143;
    background-color: #fff;
    transition: border .2s ease-in-out;
    -webkit-box-shadow: 9px 9px 10px -8px rgba(0,0,0,0.48);
    -moz-box-shadow: 9px 9px 10px -8px rgba(0,0,0,0.48);
    box-shadow: 9px 9px 10px -8px rgba(0,0,0,0.48);
}

.thumbnail .caption {
    padding: 9px;
    color: #333;
}

.container-foto {
padding: 10px;
}

table {
width: 100%;
margin-top: 6px !important;
margin-bottom: 6px !important;
max-width: none !important;
border-collapse: separate !important;
border-spacing: 0;
}

td {
text-align: center;
vertical-align: middle;
border-top: 1px solid #ddd;
}

.table-striped > tbody > tr:nth-of-type(2n+1) {
    background-color: #f9f9f9;
}';

//OBTENER LOS DATOS DEL REPORTE
$llenadoreporte = new ReporteLlenado($adapter);
$fotografia = new Fotografia($adapter);
$allreportellenado = $llenadoreporte->getReporteLlenadoById($idGpoReporte);

//**************************************** DATOS PARA ENLAZAR REPORTES ****************************************
if ($allreportellenado[0]->tipo_Reporte == 1) {
    // REPORTES ENLAZADOS A INCIDENCIAS
    $id_seguimiento = $llenadoreporte->getAllCatReportesByIdReporte($allreportellenado[0]->id_Reporte);
    $id_Reporte_Seguimiento = $id_seguimiento[0]->id_Reporte_Seguimiento;

    if ($id_Reporte_Seguimiento != 0) {
        $noreportes = 'AND rl.id_Reporte = ' . $id_Reporte_Seguimiento . ' AND rl.id_Gpo_Padre = ' . $idGpoReporte;
        $tipo_Reporte1 = '4';
        $allSeguimientosReportesIncidentes = $llenadoreporte->getAllSeguimientoReporteIncidencia($area, $id_Proyecto, $tipo_Reporte1, $noreportes);
    }
} else {
    //OBTENER PROCENTAJE DE REPORTES ENLAZADOS AL REPORTE PADRE
    $allSeguimientosReportesIncidentes = $llenadoreporte->getAllSeguimientoProcesos($idGpoReporte);
}

$estilos = $estilos . $addEstilos;

$fotos = $entidadBase->getAllFotografiasById($idGpoReporte, 1);
$comentarios = $entidadBase->getAllComentariosReporte($idGpoReporte);

$existenFotos = false;

foreach ($fotos as $foto) {
    $carpeta = $foto->fecha_Fotografia;
    $carpeta = str_replace("-", "", $carpeta);
    $carpeta = substr($carpeta, 0, -2);

    $imagen = "../img/reportes/" . $id_Empresa . "/" . $id_Proyecto . "/" . $carpeta . "/" . $foto->nombre_Fotografia;

    if (is_file($imagen)) {
        $splFoto = new SplFileInfo($imagen);
        $extension = $splFoto->getExtension();

        $extensionesImagen = ['jpeg', 'jpg', 'bmp', 'png'];

        if (in_array($extension, $extensionesImagen)) {
            $existenFotos = true;

            $htmlFotos .= '
        <div class="col-sm-6" >
            <div class="thumbnail">
                <img src="' . $imagen . '" alt="...">
                <div class="caption">
                    <p><b>Descripción</b></p>
                    <p style="font - weight: normal">' . $foto->descripcion_Fotografia . '</p>
                </div>
            </div>
        </div>';
        }
    }
}

if ($existenFotos) {
    $htmlBody .=
        '<div class="col-sm-12" style="border-bottom: #166D9B 2px solid; padding-bottom: 10px">
            <h3 class="encabezado" style="margin: unset">Fotografías</h3>
        </div>
        <div class="col-sm-12 container-foto">' . $htmlFotos . '</div>';
}

$existenComentarios = false;
if ((is_array($comentarios) || is_object($comentarios)) && !empty($comentarios)) {
    $index = 1;
    $existenComentarios = true;
    foreach ($comentarios as $comentario) {
        $htmlComentarios .= '<tr>
            <td>' . $index . '</td>
            <td>' . $comentario->nombre_Usuario . " " . $comentario->apellido_Usuario . '</td>
            <td>' . $comentario->Fecha_Comentario . '</td>
            <td>' . $comentario->Comentario_reporte . '</td>
            </tr>';
        $index++;
    }
}

$existenVinculados = false;
if (is_object($allSeguimientosReportesIncidentes) || is_array($allSeguimientosReportesIncidentes)) {
    $existenVinculados = true;
    foreach ($allSeguimientosReportesIncidentes as $vinculados) {
        $htmlVinculados .= "<tr>
            <td>{$vinculados->Id_Reporte}</td>
            <td>{$vinculados->nombre_Reporte}</td>
            <td>{$vinculados->titulo_Reporte}</td>
            <td>{$vinculados->Fecha2}</td>
            <td>{$vinculados->campo_Hora}</td>
            <td>{$vinculados->nombre_Usuario}</td>
            </tr>";
    }
}

if ($existenVinculados) {
    $htmlBody .=
        '<div class="col-sm-12" style="border-bottom: #166D9B 2px solid; padding-bottom: 10px">
            <h3 class="encabezado" style="margin: unset; text-align: center; color: #91231C;">Reportes Vinculados</h3> 
            <table class="table table-striped">
                <thead> 
                    <tr style="background-color: #91231C;">
                    <th style="color: #FFF;">ID Ticket</th>
                    <th style="color: #FFF;">Nombre de Reporte</th>
                    <th style="color: #FFF;">Título</th>
                    <th style="color: #FFF;">Fecha</th>
                    <th style="color: #FFF;">Hora</th>
                    <th style="color: #FFF;">Generado Por</th>
                    </tr>
                </thead>
                <tbody>
                ' . $htmlVinculados . '
                </tbody>
            </table>
        </div>';
}

if ($existenComentarios) {
    $htmlBody .=
        '<div class="col-sm-12" style="border-bottom: #166D9B 2px solid; padding-bottom: 10px">
            <h3 class="encabezado" style="margin: unset">Comentarios</h3> 
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Nombre del Usuario</th>
                    <th>Fecha y Hora del Comentario</th>
                    <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                ' . $htmlComentarios . '
                </tbody>
            </table>
        </div>';
}

//$MPDF->SetHTMLHeader($htmlHeader);
$MPDF->SetHTMLFooter('<div class="col-sm-12" style="font-weight: normal; text-align: right">{PAGENO} de {nbpg}</div>');

$MPDF->WriteHTML($estilos, \Mpdf\HTMLParserMode::HEADER_CSS);

$MPDF->WriteHTML($htmlHeader.$htmlBody, \Mpdf\HTMLParserMode::HTML_BODY);

$output = $MPDF->Output();
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename=filename.pdf');
header('Content-Length: ' . filesize('filename.pdf'));
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
