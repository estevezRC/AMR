<script src="js/sortable.js"></script>
<script src="js/interact.js"></script>
<script src="js/reporte_dinamico.js"></script>
<script src="js/galeria/galleria.min.js"></script>

<script>
    function generarPDF() {
        let panel_title = document.getElementById('panel_title');
        let panel_resumen_content = document.getElementById('panel_resumen_content');
        let porcentajeAvance = document.getElementById('porcentajeAvance');

        let porcentaje = parseFloat(<?= $porcentajeReporte ?>);

        let http = new XMLHttpRequest();
        let url = 'descargables/reporte_generico_pdf.php';
        http.open('POST', url, true);
        http.responseType = 'blob';

        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        http.onreadystatechange = function () {
            if (http.readyState === 4 && http.status === 200) {
                let URL = window.webkitURL || window.url;
                let downloadUrl = URL.createObjectURL(http.response);
                window.open(downloadUrl, "PDF", '_blank');
            }
        };


        if (porcentaje == 0)
            http.send("htmlHeader=" + encodeURIComponent(panel_title.outerHTML.toString()) + "&html=" + encodeURIComponent(panel_resumen_content.outerHTML.toString()) + "&idGpoReporte=" + <?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte ?>);
        else
            http.send("htmlHeader=" + encodeURIComponent(porcentajeAvance.outerHTML.toString() + panel_title.outerHTML.toString()) + "&html=" + encodeURIComponent(panel_resumen_content.outerHTML.toString()) + "&idGpoReporte=" + <?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte ?>);
    }
</script>

<!------------------------- LOGOS ------------------------------->
<div class="row text-center mt-3" id="panel_title" data-id="panel_title">
    <div class="col-sm-3" style="padding-top: 14px; padding-bottom: 14px" id="logo_1" data-id="logo_1">
        <?php if ($logos['primary'] != '') { ?>
            <img src="<?php echo $logos['primary'] ?>" style="max-width: 100%; height: 60px" alt="logo_principal">
        <?php } ?>
    </div>

    <div class="col-sm-6" id="titlereport" data-id="titlereport">
        <p class="h3 font-weight-bold m-0 h-100 d-flex justify-content-center align-items-center text-primary"><?php echo $allreportellenado[0]->nombre_Reporte ?></p>
    </div>

    <div class="col-sm-3" style="padding-top: 14px; padding-bottom: 14px" id="logo_2" data-id="logo_2">
        <?php if ($logos['secondary'] != '') { ?>
            <img src="<?php echo $logos['secondary'] ?>" style="max-width: 100%; height: 60px;" alt="logo_secundario">
        <?php } ?>
    </div>
</div>

<hr class="linea-separadora">

<!------------------------- TABLA DATOS ------------------------->

<?php if (is_array($info_fotografia) || is_object($info_fotografia)) {
    $noimg = 0;
    $noarchivos = 0;

    foreach ($info_fotografia as $datos_foto) {

        $carpeta = $datos_foto->fecha_Fotografia;
        $carpeta = str_replace("-", "", $carpeta);
        $carpeta = substr($carpeta, 0, -2);

        $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

        $nombreFoto = $datos_foto->nombre_Fotografia;
        $imagen = "img/reportes/" . $id_Empresa . "/" . $id_Proyecto . "/" . $carpeta . "/" . $datos_foto->nombre_Fotografia;

        if (is_file($imagen)) {
            //$ext = explode(".", $datos_foto->nombre_Fotografia);

            $splFoto = new SplFileInfo($nombreFoto);
            $extension = $splFoto->getExtension();

            switch ($extension) {
                case 'jpeg':
                case 'png':
                case 'bmp':
                case 'jpg':
                    $noimg += 1;
                    break;
                case 'rar':
                case 'docx':
                case 'xls':
                case 'xml':
                case 'pdf':
                case 'zip':
                case 'doc':
                    $noarchivos += 1;
                    break;
                default:
                    $noarchivos += 1;
            }
        }
    }
}
$col_detalles = "col-sm-12";
if ($noarchivos > 0 || $noimg > 0) {
    $col_detalles = "col-sm-6 p-0 m-2";
} ?>

<div class="row justify-content-around" id="report_content" data-id="report_content">
    <div class="<?php echo $col_detalles; ?> panel-resumen" id="panel_resumen"
         data-id="panel_resumen">

        <div class="panel-resumen-content" id="panel_resumen_content" data-id="panel_resumen_content">

            <div class="form-group" id="lbl_title" data-id="lbl_title">
                <label class="font-weight-bold">Título:</label>
                <label> <?php echo $allreportellenado[0]->titulo_Reporte; ?></label>
            </div>

            <?php if ($allreportellenado[0]->Identificador != '') { ?>
                <div class="form-group" id="lbl_grupoelemento" data-id="lbl_grupoelemento">
                    <label class="font-weight-bold"> Vinculado a:</label>
                    <label>
                        <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $allreportellenado[0]->id_Gpo_Padre; ?>">
                            <?php echo $allreportellenado[0]->Identificador; ?> </a>
                    </label>
                </div>
            <?php } ?>

            <div class="form-group" id="lbl_generadopor" data-id="lbl_generadopor">
                <label class="font-weight-bold"> Generado por:</label>
                <label> <?php echo $allreportellenado[0]->nombre_Usuario . " " . $allreportellenado[0]->apellido_Usuario; ?></label>
            </div>

            <div class="form-group" id="lbl_coordenadas" data-id="lbl_coordenadas">
                <label class="font-weight-bold"> Coordenadas:</label>
                <label>
                    <a target="_blank"
                       href="http://maps.google.com/maps?q=&layer=c&cbll=<?php echo $allreportellenado[0]->latitud ?>,<?php echo $allreportellenado[0]->longitud ?>&cbp=11,0,0,0,0"> <?php echo $allreportellenado[0]->latitud . ", " . $allreportellenado[0]->longitud; ?> </a>
                </label>
            </div>

            <?php
            if (is_array($allreportellenado) || is_object($allreportellenado)) {
                foreach ($allreportellenado as $reportellenado) {
                    if ($reportellenado->tipo_Reactivo_Campo == 'label') {
                        $valorClass = '';
                    } else {
                        $valorClass = 'form-group';
                    } ?>
                    <div class="<?php echo $valorClass ?>"
                         id="lbl_<?php echo preg_replace('/[^a-zA-Z0-9]/', "", str_replace(" ", "", $reportellenado->nombre_Campo)) ?>"
                         data-id="lbl_<?php echo preg_replace('/[^a-zA-Z0-9]/', "", str_replace(" ", "", $reportellenado->nombre_Campo)) ?>">
                        <?php switch ($reportellenado->tipo_Reactivo_Campo) {
                            case "text-nota":
                            case "textarea":
                            case "radio":
                            case "checkbox":
                            case "select":
                            case "select-status":
                            case "checkbox-incidencia":
                            case "decimal":
                            case "text":
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = $reportellenado->valor_Texto_Reporte;
                                $likeLabel = true;
                                break;
                            case "number":
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = $reportellenado->valor_Entero_Reporte;
                                $likeLabel = true;
                                break;
                            case "time":
                                $nombre = $reportellenado->nombre_Campo . ":";
                                $valor = $reportellenado->valor_Texto_Reporte;
                                ?>
                                <div class="campos-reporte">
                                    <label class="name font-weight-bold"><?php echo $nombre; ?>&nbsp</label>
                                    <label id="hora">
                                        <?php $validarHora = $this->validateTime($valor);
                                        if (!$validarHora) {
                                            echo nl2br($valor . ':00');
                                        } else {
                                            echo nl2br($valor);
                                        } ?>
                                    </label>
                                </div>
                                <?php
                                $likeLabel = false;
                                break;
                            case "date":
                                $nombre = $reportellenado->nombre_Campo . ":";
                                $valor = $this->formatearFecha($reportellenado->valor_Texto_Reporte);
                                //$valor = $reportellenado->valor_Texto_Reporte;
                                ?>
                                <div class="campos-reporte">
                                    <label class="name font-weight-bold"><?php echo $nombre; ?>&nbsp</label>
                                    <label id="fecha"><?php echo nl2br($valor); ?></label>
                                </div>
                                <?php
                                $likeLabel = false;
                                break;
                            case "label":
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = "";
                                $likeLabel = true;
                                break;
                            case "check_list_asistencia":
                                $likeLabel = true;
                                $nombre = $reportellenado->nombre_Campo;

                                $empleados = array_map(function ($empleado) {
                                    return "{$empleado->nombre} {$empleado->apellidos}";
                                }, $allEmpleadosAsistencia);

                                $valor = implode(' / ', $empleados);
                                if ($allreportellenado[0]->tipo_Reporte == 6) {
                                    $likeLabel = false; ?>
                                    <p class="lead text-center border-0" style="box-shadow: unset;"><?= $nombre ?></p>
                                    <ul class="list-group">
                                        <? foreach ($empleados as $key => $empleado) { ?>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between">
                                                <p class="m-0" style='box-shadow: unset;'>
                                                    <?= "{$empleado}" ?> <br>
                                                    <strong>Tiempo laborado:</strong> <?= "{$horasTrabajadas[$key]}" ?>
                                                </p>
                                            </li>
                                        <? } ?>
                                    </ul>
                                    <br>
                                <? }
                                break;
                            case
                            "select-catalogo":
                                $nombre = $reportellenado->nombre_Campo;
                                foreach ($menucatalogo as $data) {
                                    if ($data->idCatalogo == $reportellenado->valor_Texto_Reporte) {
                                        $valor = $data->concepto;
                                    }
                                }
                                break;
                            case "text-cadenamiento":
                                //$cadenamiento = str_replace(".","+",$reportellenado->valor_Texto_Reporte);
                                $cadenamientos = explode(".", $reportellenado->valor_Texto_Reporte);
                                $cadenamiento1 = $cadenamientos[0];
                                $cadenamiento2 = $cadenamientos[1];
                                $size1 = strlen($cadenamiento1);
                                $size2 = strlen($cadenamiento2);
                                if ($size1 == 0) {
                                    $cadenamiento1 = "000";
                                }
                                if ($size1 == 1) {
                                    $cadenamiento1 = "00" . $cadenamiento1;
                                }
                                if ($size1 == 2) {
                                    $cadenamiento1 = "0" . $cadenamiento1;
                                }
                                if ($size2 == 0) {
                                    $cadenamiento2 = "000";
                                }
                                if ($size2 == 1) {
                                    $cadenamiento2 = "00" . $cadenamiento2;
                                }
                                if ($size2 == 2) {
                                    $cadenamiento2 = "0" . $cadenamiento2;
                                }
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = $cadenamiento1 . " + " . $cadenamiento2;
                                $likeLabel = true;
                                break;
                            case "rango_fechas":
                                $fechasInicioFinal = explode(".", $reportellenado->valor_Texto_Reporte);
                                $fechaInicio = $this->formatearFecha($fechasInicioFinal[0]);
                                $fechaFinal = $this->formatearFecha($fechasInicioFinal[1]);
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = "Desde $fechaInicio hasta $fechaFinal";
                                $likeLabel = true;
                                break;
                            case "select-tabla":
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = $allInfoProyecto->nombre_Proyecto;
                                $likeLabel = true;
                                break;
                            default:
                                $nombre = $reportellenado->nombre_Campo;
                                $valor = $reportellenado->valor_Entero_Reporte;
                                $likeLabel = true;
                        }


                        if ($likeLabel) {
                            if ($reportellenado->tipo_Reactivo_Campo == 'label') { ?>
                                <label
                                        style="width: 100%;font-size: 16px; margin: 12px 8px 15px 8px; text-align: center">
                                    <?php echo $nombre; ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="font-weight-bold"><?php echo $nombre; ?>:</label>
                                <label class="ml-1"> <?php echo nl2br($valor); ?></label>
                                <?php
                            }
                        } ?>
                    </div>
                <?php }
            } ?>
        </div>
    </div>

    <?php if ($noimg > 0 || $noarchivos > 0) { ?>
        <div class="col-sm-5" id="panel_photos_files" data-id="panel_photos_files">
            <?php if ($noimg > 0) { ?>
                <div class="row flex-column" id="panel_photos" data-id="panel_photos">
                    <div class="galeria">
                        <?php $extensionesPermitidas = ['png', 'jpg', 'jpeg', 'bmp'];
                        foreach ($info_fotografia as $datos_foto) {
                            $archivo = new SplFileInfo($datos_foto->nombre_Fotografia);
                            if (in_array(strtolower($archivo->getExtension()), $extensionesPermitidas)) {
                                $carpeta = $datos_foto->fecha_Fotografia;
                                $carpeta = str_replace("-", "", $carpeta);
                                $carpeta = substr($carpeta, 0, -2);

                                $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
                                $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

                                if ($datos_foto->latitud_Fotografia && $datos_foto->longitud_Fotografia) {
                                    $link = "http://maps.google.com/maps?q=&layer=c&cbll=$datos_foto->latitud_Fotografia,$datos_foto->longitud_Fotografia&cbp=11,0,0,0,0";
                                }

                                $imagen = "img/reportes/$id_Empresa/$id_Proyecto/$carpeta/$datos_foto->nombre_Fotografia"; ?>
                                <img src="<?= $imagen ?>" data-title="<?= $datos_foto->descripcion_Fotografia ?>"
                                     data-layer="<p id='layer' class='mt-2'>
                                            <strong>Fecha: </strong> <?= $this->formatearFecha($datos_foto->fecha_Fotografia) ?> <br>
                                            <strong>Hora: </strong> <?= $this->formatearHora($datos_foto->hora_Fotografia) ?><br>
                                            <strong>GPS: </strong> <?= isset($link) ?
                                         "<a href='{$link}' target='_blank'>{$datos_foto->latitud_Fotografia}, {$datos_foto->longitud_Fotografia}</a>" :
                                         "Coordenadas no válidas";
                                     ?><br>
                                            <a class='btn btn-warning btn-sm mt-2' href='<?= $imagen ?>' download>Descargar</a>
                                        </p>"
                                     alt="">
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <script>
                    (function () {
                        Galleria.loadTheme('js/galeria/themes/twelve/galleria.twelve.min.js');
                        Galleria.run('.galeria',
                            {
                                theme: 'twelve',
                                imageCrop: false
                            });
                        Galleria.ready(function () {
                            this.bind("loadfinish", function (e) {
                                if (e.cached) {
                                    $("#panel_photos > #layer").last().remove();
                                    $("#layer").appendTo("#panel_photos");
                                }
                            });
                        });
                    })();
                    $(document).load(function () {
                        $("#panel_photos > #layer").last().remove();
                        $("#layer").appendTo("#panel_photos");
                    });
                </script>
            <?php }

            if ($noarchivos > 0) { ?>
                <div class="col-sm-12" id="panel_files"> <!-- panel de Archivos -->
                    <!--                    <div class="form-group">-->
                    <!--                        <h3 class="encabezado" style="margin: unset">Archivos</h3>-->
                    <!--                    </div>-->
                    <ul>
                        <?php foreach ($info_fotografia as $datos_foto) {
                            $carpeta = $datos_foto->fecha_Fotografia;
                            $carpeta = str_replace("-", "", $carpeta);
                            $carpeta = substr($carpeta, 0, -2);
                            $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
                            $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

                            $imagen = "img/reportes/" . $id_Empresa . "/" . $id_Proyecto . "/" . $carpeta . "/" . $datos_foto->nombre_Fotografia . "";

                            //TIPOS DE ARCHIVO
                            $archivo = new SplFileInfo($datos_foto->nombre_Fotografia);
                            $ext = strtolower($archivo->getExtension());
                            ?>

                            <li class="list-unstyled pt-3">
                                <?php
                                switch ($ext) {
                                    case 'docx':
                                    case 'doc': ?>
                                        <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                        <?php break;
                                    case 'xlsx':
                                    case 'xls': ?>
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                        <?php break;
                                    case 'rar': ?>
                                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                        <?php break;
                                    case 'pdf': ?>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                        <?php break;
                                    case 'ppt':
                                    case 'pptx': ?>
                                        <i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                        <?php break;
                                    case 'jpeg':
                                    case 'bmp':
                                    case 'png':
                                    case 'jpg':
                                        break;
                                    default: ?>
                                        <i class="fa fa-file-o" aria-hidden="true"></i>
                                        <a href="<?php echo $imagen; ?>"><?php echo $datos_foto->nombre_Fotografia; ?> </a>
                                    <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

        </div>
    <?php } ?>
</div>

