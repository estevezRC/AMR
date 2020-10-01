<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
      rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script src="js/sortable.js"></script>
<script src="js/interact.js"></script>
<script src="js/reporte_dinamico.js"></script>

<script>
    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);
    });
</script>

<style>
    .border-punteado {
        border: 1px dashed red;
        padding: 0 5px;
    }

    .border-solido {
        border: 1px solid red;
        padding: 0 5px;
    }
</style>

<script type="application/javascript">

    function infoDinamic() {
        $('#popCRDinamico').popover({
            placement: 'bottom',
            title: '<h5 style="margin: unset; padding: unset" class="text-center">Acomoda tus elementos</h5>',
            content: "Lo que puedes hacer: <br> <br>" +
                "<ul style='list-style: square' class='text-justify'>" +
                "<li style='margin: 0 15px;'>Cambiar de tamaño los campos con borde punteado: </br> <div class='border-punteado'>Nombre</div></li> <br>" +
                "<li style='margin: 0 0px;'>Cambiar de posición los campos con borde solido: </br> <div class='border-solido'>Fecha</div></li>" +
                '</ul> <br>' +
                "<div class='text-justify'>El acomodo de los campos, asi como el de los logotipos (en caso que hubiese) lo podrás notar una vez llenado el reporte.</div>",
            html: true
        });
    }

    $().ready(function () {

        $('.pasar').click(function () {
            return !$('#origen option:selected').remove().appendTo('#destino');
        });
        $('.quitar').click(function () {
            return !$('#destino option:selected').remove().appendTo('#origen');
        });
        $('.pasartodos').click(function () {
            $('#origen option').each(function () {
                $(this).remove().appendTo('#destino');
            });
        });
        $('.quitartodos').click(function () {
            $('#destino option').each(function () {
                $(this).remove().appendTo('#origen');
            });
        });

        $('.procesa').click(function () {
            var valores = $('#destino').val();
            var value = "";
            for (x = 0; x < valores.length; x++) {
                var pos = x + 1;
                value += pos + "," + valores[x] + "|";
            }
            document.getElementById("inp").value = value;
            document.getElementById("formvals").submit();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".up,.down").click(function () {
            var row = $(this).parents("tr:first");
            if ($(this).is(".up")) {
                row.insertBefore(row.prev());
            } else {
                row.insertAfter(row.next());
            }
        });

        $(".requiredItems").click(function () {
            if ($(this).prop('checked') == true) {
                $(this).val('1');
            } else if ($(this).prop('checked') == false) {
                $(this).val('0');
            }
        });

        $("#Campo_Obligatorio").click(function () {
            if ($(this).prop('checked') == true) {
                $(this).val('1');
                $("#Campo_Necesario").val($("#Campo_Obligatorio").val());
            } else if ($(this).prop('checked') == false) {
                $(this).val('0');
                $("#Campo_Necesario").val($("#Campo_Obligatorio").val());
            }
        });

        var amountPastReports = <?php echo json_encode($_GET);?>;

        if (amountPastReports["amountPastReports"] > 0) {
            $('#pastReports').modal('show');
        }

        $('#sendValues').click(function () {
            alertify.confirm('Confirmar Acción', '' + ' Estas por agregar los campos a todos los reportes anteriores. ¿Estás seguro?',
                function () {
                    sendConfiguracionReporte();
                }, function () {
                    console.log('Registro no borrado');
                }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
        });

        function sendConfiguracionReporte() {
            var id_Reporte = "<?php echo $allcamposreporteById[0]->id_Reporte; ?>";

            $.ajax({
                data: {
                    valores: id_Reporte
                },
                url: "./index.php?controller=CamposReporte&action=anadirCampos",
                type: 'POST',
                success: function (response) {
                    console.log(response);
                    alertify.success(response);
                }
            });
        }
    });
</script>


<script>
    function myFunction() {
        var Id_Reporte = "<?php echo $allcamposreporteById[0]->id_Reporte; ?>";
        var orden = '';
        var ordennecesario = '';
        $(function () {
            $('#example tr').each(function (a, b) {
                var name = $('.attrName', b).text();
                var value = $('.attrValue', b).text();
                var necesario = $('.requiredItems', b).val();
                //ary.push({ Name: name, Value: value });
                orden = orden + ',' + name;
                ordennecesario = ordennecesario + ',' + necesario;
            });
            window.location.href = "index.php?controller=CamposReporte&action=ordenar&orden=" + orden + "&necesario=" + ordennecesario + "&Id_Reporte=" + Id_Reporte;
        });
    }
</script>

<!--------------------------- ACCION INDEX: MUESTRA TODOS LAS CONFIGURACIONES ----------------------------------------->
<?php if (($action == "index") || ($action == "modificar") || ($action == "getCamposByReporte") || ($action == "guardarnuevo")) {
    if ($modificar == NULL) { ?>
        <!------------------------------------ FORMULARIO CREAR NUEVA CONFIGURACION ------------------------------------------>
        <div class="modal fade" id="myModalCamposReportes" tabindex="-2" role="dialog" aria-labelledby="myModalLabel"
             style="margin-top:unset;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                        <h4 class="modal-title" id="myModal2Label" style="text-align: center">Nueva Configuración</h4>

                        <form action="<?php echo $helper->url("CamposReporte", "guardarnuevo"); ?>" method="post"
                              class="form-horizontal">
                            <input type="hidden" name="id_Proyecto"
                                   value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>"/>

                            <?php if ($action == "index") { ?>
                                <input type="hidden" name="id_Reporte"
                                       value="<?php echo $allcamposreportes[0]->id_Reporte; ?>"/>
                            <?php } else { ?>
                                <input type="hidden" name="id_Reporte"
                                       value="<?php echo $allcamposreporteById[0]->id_Reporte; ?>"/>
                            <?php } ?>


                            <label class="control-label">Reporte:</label>
                            <label class="form-control labelPerfil">
                                <?php echo $allcamposreporteById[0]->nombre_Reporte; ?>
                            </label>
                            <input value="<?php echo $allcamposreporte[0]->id_Reporte; ?>" name="Id_Reporte" hidden>


                            <label for="Id_Campo_Reporte" class="control-label">Campo:</label>
                            <select name="Id_Campo_Reporte" id="Id_Campo_Reporte" class="form-control">
                                <?php foreach ($allcampos as $campo) { ?>
                                    <option name="<?php echo $campo->nombre_Campo; ?>"
                                            id="<?php echo $campo->id_Campo_Reporte; ?>"
                                            value="<?php echo $campo->id_Campo_Reporte; ?>"><?php echo $campo->nombre_Campo; ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="Campo_Obligatorio" value="0">
                                    Este campo será obligatorio
                                </label>
                            </div>

                            <input type="hidden" name="Campo_Necesario" id="Campo_Necesario" value="0">

                            <br>

                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-w-m btn-danger">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-------------------------------------- TABLA CON LAS CONFIGURACIONES ----------------------------------------------->

    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">

        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <? if ($action == "getCamposByReporte") { ?>
                            <a class="px-2 m-1 h4 text-white"
                               href="#" data-trigger="hover" data-content="Agregar campos" data-toggle="popover"
                               onclick="popover('myModalCamposReportes')">
                                <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                            <a class="px-2 m-1 h4 text-white"
                               href="#" id="sendValues"
                               data-trigger="hover" data-content="Agregar campo(s) a reportes anteriores"
                               data-toggle="popover">
                                <i class="fas fa-save" aria-hidden="true"></i></a>
                        <? } ?>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <? if ($action != "index") { ?>
                        <table id="example" class="table table-striped">
                            <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">Id</th>
                                <!--<th class="text-center">Proyecto</th>-->
                                <th class="text-center">Campo</th>
                                <th class="text-center">Campo Necesario</th>
                                <th class="text-center">Secuencia</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($allcamposreporteById) || is_object($allcamposreporteById)) {

                                foreach ($allcamposreporteById as $camporeporte) {
                                    if (!empty($camporeporte->nombre_Proyecto)) { ?>
                                        <tr>
                                            <td class="attrName"><?php echo $camporeporte->id_Configuracion_Reporte; ?></td>
                                            <!--<td><?php /*echo $camporeporte->nombre_Proyecto; */ ?></td>-->
                                            <td class="attrValue"><?php echo $camporeporte->nombre_Campo; ?></td>

                                            <td class="text-center">
                                                <?php if ($camporeporte->Campo_Necesario == 1) { ?>
                                                    <div>
                                                        <input type="checkbox" class="requiredItems"
                                                               value="<?php echo $camporeporte->Campo_Necesario ?>"
                                                               style="margin: unset !important;" checked
                                                               onclick="myFunction()">
                                                    </div>
                                                <? } else { ?>
                                                    <div>
                                                        <input type="checkbox" class="requiredItems"
                                                               value="<?php echo $camporeporte->Campo_Necesario ?>"
                                                               style="margin: unset !important;"
                                                               onclick="myFunction()">
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center"><?php echo $camporeporte->Secuencia; ?></td>
                                            <td class="text-center">
                                                <a href="#" onclick="myFunction()" class="up" data-trigger="hover"
                                                   data-content="Subir" data-toggle="popover">
                                                    <i class="fa fa-arrow-up" aria-hidden="true"></i></a> &nbsp;

                                                <a href="#" onclick="myFunction()" class="down" data-trigger="hover"
                                                   data-content="Bajar" data-toggle="popover">
                                                    <i class="fa fa-arrow-down" aria-hidden="true"></i></a> &nbsp;

                                                <a href="#" data-trigger="hover" data-content="Borrar"
                                                   data-toggle="popover"
                                                   onclick="borrarRegistro(<?php echo $camporeporte->id_Configuracion_Reporte ?>+'&Id_Reporte='+<?php echo $camporeporte->id_Reporte; ?>, 'id_Configuracion_Reporte', '<?php echo $camporeporte->nombre_Campo; ?>', 'CamposReporte', 'borrar')">
                                                    <i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    <? } ?>
                </div>
            </div>
        </div>

        <div class="row pt-5 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            Organiza los campos del reporte
                            <a id="popCRDinamico" tabindex="0" role="button" onclick="infoDinamic()">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <? if (getAccess(32, $decimal)) { ?>
                            <input type="checkbox" id="toggle" data-toggle="toggle"
                                   data-onstyle="success" data-offstyle="danger" data-on="Guardar"
                                   data-off="No Guardar" data-style="toggle-minimalist">
                        <? } ?>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <? if ($action != "index") { ?>
                        <div id="paneles" class="w-100 d-flex flex-column">
                            <div class="shadow-sm bw-100 mb-3 mt-3 d-flex flex-wrap" id="panel_title"
                                 data-id="panel_title">
                                <div class="col-sm-3 p-2 d-flex justify-content-center align-items-center"
                                     data-id="logo_1"
                                     id="logo_1">
                                    <button type="button" class="btn btn-outline-color-primary-3 btn-handle">
                                        <i class="fa fa-arrows-alt"></i></button>
                                    <div class="w-100">
                                        <?php if ($logos['primary'] != '') { ?>
                                            <img src="<?php echo $logos['primary'] ?>"
                                                 style="max-width: 100%; height: 60px" alt="logo_principal">
                                        <?php } else { ?>
                                            <div class="text-center">
                                                Logo Principal
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-sm-6 p-2 d-flex justify-content-center align-items-center"
                                     data-id="titlereport"
                                     id="titlereport">
                                    <button type="button" class="btn btn-outline-color-primary-3 btn-handle">
                                        <i class="fa fa-arrows-alt"></i></button>
                                    <p class="h4 font-weight-bold m-0 text-center"><?php echo $allcamposreporteById[0]->nombre_Reporte ?></p>
                                </div>

                                <div class="col-sm-3 p-2 d-flex justify-content-center align-items-center"
                                     data-id="logo_2"
                                     id="logo_2">
                                    <button type="button" class="btn btn-outline-color-primary-3 btn-handle">
                                        <i class="fa fa-arrows-alt"></i></button>
                                    <div class="w-100">
                                        <?php if ($logos['secondary'] != '') { ?>
                                            <img src="<?php echo $logos['secondary'] ?>"
                                                 style="max-width: 100%; height: 60px;" alt="logo_secundario">
                                        <?php } else { ?>
                                            <div class="text-center">
                                                Logo Secundario
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row justify-content-around" id="report_content"
                                     data-id="report_content">
                                    <?php if (is_array($allcamposreporteById) || is_object($allcamposreporteById)) {
                                        foreach ($allcamposreporteById as $campo) {
                                            if ($campo->tipo_Reactivo_Campo == 'file') {
                                                $col_detalles = "col-sm-6 p-0 m-2"; ?>
                                                <div class="col-sm-5 p-0 m-2" id="panel_photos_files"
                                                     data-id="panel_photos_files">
                                                    <!-- panel de Fotos -->
                                                    <button type="button"
                                                            class="btn btn-outline-color-primary-3 btn-handle">
                                                        <i class="fa fa-arrows-alt"></i></button>
                                                    <div class="carousel slide mt-3" id="carousel_fotos"
                                                         data-ride="carousel"
                                                         style="height: 350px">
                                                        <!-- Indicadores -->
                                                        <ol class="carousel-indicators" id="puntos">
                                                            <li data-target="#carousel_fotos" data-slide-to="0"
                                                                class="active">
                                                            </li>
                                                        </ol>

                                                        <div class="carousel-inner">
                                                            <div class="item active">
                                                                <img src="https://picsum.photos/id/1/5616/3744"
                                                                     alt=""
                                                                     style="width:100%;height:350px;object-fit:cover;">
                                                                <div class="carousel-caption">
                                                                    <p class="p-2" data-id="lbl_title"
                                                                       id="lbl_title">
                                                                        <span
                                                                            class="font-weight-bold"> Descripción: </span>
                                                                        Imagen de Ejemplo
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="caption-area" style="padding-top: 15px"
                                                         id="photo_details"></div>
                                                </div>
                                            <?php }
                                        }
                                    } ?>

                                    <div class="<?php echo $col_detalles; ?> panel-resumen" id="panel_resumen"
                                         data-id="panel_resumen">

                                        <button type="button"
                                                class="btn btn-outline-color-primary-3 btn-handle">
                                            <i
                                                class="fa fa-arrows-alt"></i></button>
                                        <div class="panel-resumen-content mt-3" id="panel_resumen_content"
                                             data-id="panel_resumen_content">
                                            <?php if (is_array($allcamposreporteById) || is_object($allcamposreporteById)) { ?>

                                                <p class="p-2" data-id="lbl_title" id="lbl_title">
                                                    <button type="button"
                                                            class="btn btn-outline-color-primary-3 btn-handle">
                                                        <i class="fa fa-arrows-alt"></i></button>
                                                    <span class="font-weight-bold"> Título: </span>
                                                    Título del reporte
                                                </p>

                                                <p class="p-2" data-id="lbl_grupoelemento" id="lbl_grupoelemento">
                                                    <button type="button"
                                                            class="btn btn-outline-color-primary-3 btn-handle">
                                                        <i class="fa fa-arrows-alt"></i></button>
                                                    <span class="font-weight-bold">Vinculado a: </span>
                                                    Grupo de Elemento
                                                </p>

                                                <p class="p-2" data-id="lbl_generadopor" id="lbl_generadopor">
                                                    <button type="button"
                                                            class="btn btn-outline-color-primary-3 btn-handle">
                                                        <i class="fa fa-arrows-alt"></i></button>
                                                    <span class="font-weight-bold">Generado por: </span>
                                                    Nombre de quien genera el reporte
                                                </p>

                                                <?php foreach ($allcamposreporteById as $campo) {
                                                    if ($campo->tipo_Reactivo_Campo != 'file' && isset($camporeporte->nombre_Proyecto)) { ?>
                                                        <p class="p-2"
                                                           data-id="lbl_<?php echo preg_replace('/[^a-zA-Z0-9]/', "", str_replace(" ", "", $campo->nombre_Campo)) ?>"
                                                           id="lbl_<?php echo preg_replace('/[^a-zA-Z0-9]/', "", str_replace(" ", "", $campo->nombre_Campo)) ?>">
                                                            <?php switch ($campo->tipo_Reactivo_Campo) {
                                                                case "text-nota":
                                                                case "textarea":
                                                                case "radio":
                                                                case "checkbox":
                                                                case "select":
                                                                case "select-status":
                                                                case "checkbox-incidencia":
                                                                case "number":
                                                                case "text":
                                                                    $nombre = $campo->nombre_Campo;
                                                                    $valor = 'Texto de Ejemplo';
                                                                    break;
                                                                case "time":
                                                                    $nombre = $campo->nombre_Campo;
                                                                    $valor = '12:23';
                                                                    break;
                                                                case "date":
                                                                    $nombre = $campo->nombre_Campo;
                                                                    $text = str_replace('/', '-', $campo->valor_Texto_Reporte);
                                                                    $date = new DateTime($text);
                                                                    $valor = $date->format('d-m-Y');
                                                                    break;
                                                                case "label":
                                                                    $nombre = $campo->nombre_Campo;
                                                                    $valor = "";
                                                                    break;
                                                                default:
                                                                    $nombre = $campo->nombre_Campo;
                                                                    $valor = 'Valor de Ejemplo';
                                                            } ?>
                                                            <button type="button"
                                                                    class="btn btn-outline-color-primary-3 btn-handle">
                                                                <i class="fa fa-arrows-alt"></i></button>
                                                            <span
                                                                class="font-weight-bold"><?php echo $nombre; ?>: </span>
                                                            <?php echo $valor; ?>
                                                        </p>
                                                    <?php } ?>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>


<script>
    jQuery(function ($) {
        $('.carousel').carousel();
        var caption = $('div.item:nth-child(1) .carousel-caption');
        $('.caption-area').html(caption.html());
        caption.css('display', 'none');

        $(".carousel").on('slide.bs.carousel', function (evt) {
            var caption = $('div.item:nth-child(' + ($(evt.relatedTarget).index() + 1) + ') .carousel-caption');
            $('.caption-area').html(caption.html());
            caption.css('display', 'none');
        });
    });
</script>
