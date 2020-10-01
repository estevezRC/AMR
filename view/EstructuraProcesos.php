<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>


<script>

    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        ReportesHijo();
        duplicarEstructuraProceso();

    });

    //************************************** SECCION DE NUEVA ESTRUCTURAPROCESOS ***************************************
    function ReportesHijo(sel) {

        let id_Gpo_Valores_Padre = $('#id_Gpo_Valores_Padre').val();

        $('#contenedorPorcentaje').empty();

        if (id_Gpo_Valores_Padre == 0) {
            $("#btnSaveEstructura").attr("disabled", true);

        } else {
            $('#btnSaveEstructura').removeAttr("disabled");
            let idReportePadre = $('option:selected', sel).attr("data-parametro");

            $.ajax({
                data: {idReportePadre: idReportePadre, id_Gpo_Valores_Padre: id_Gpo_Valores_Padre},
                url: "index.php?controller=EstructuraProcesos&action=getAllReportesByIdReporte",
                method: "POST",
                success: function (response) {
                    let respuestaJSON = $.parseJSON(response);
                    let reportes = respuestaJSON.allRegistrosProcesos;
                    var porcentaje = respuestaJSON.porcentaje;

                    var nombreReportePadre = respuestaJSON.nombreReporte;

                    var $secondChoice = $("#id_Reporte_Padre");
                    $secondChoice.empty();

                    if (reportes.length == 0) {
                        $secondChoice.append("<option> No hay más Reportes (Crear nuevos Reportes) </option>");
                        $("#btnSaveEstructura").attr("disabled", true);
                    } else if (porcentaje == 100) {
                        $('#porcentaje').removeAttr("hidden");
                        $('#contenedorPorcentaje').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + nombreReportePadre + " tiene " + porcentaje + "% de avance</label>");

                        let n = 0;
                        $.each(reportes, function () {
                            $secondChoice.append("<option value='" + reportes[n]['id_Reporte'] + "'>" + reportes[n]['nombre_Reporte'] + "</option>");
                            n++;
                        });

                        $('#procentajeReporte').val(porcentaje);

                    } else {
                        $('#btnSaveEstructura').removeAttr("disabled");

                        if (porcentaje != 0) {
                            $('#porcentaje').removeAttr("hidden");
                            $('#contenedorPorcentaje').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + nombreReportePadre + " tiene " + porcentaje + "% de avance</label>");
                        } else {
                            $("#porcentaje").attr("hidden", true);
                        }

                        let x = 0;
                        $.each(reportes, function () {
                            $secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "'>" + reportes[x]['nombre_Reporte'] + "</option>");
                            x++;
                        });

                        $('#procentajeReporte').val(porcentaje);
                    }

                    Porcentaje();
                }
            });
        }
    }

    function Porcentaje() {
        var inputPorcentaje = $('#inputPorcentaje').val();
        var porcentajeReporte = $('#procentajeReporte').val();

        inputPorcentaje = parseFloat(inputPorcentaje);
        porcentajeReporte = parseFloat(porcentajeReporte);

        if (!isNaN(inputPorcentaje)) {
            var valorMax = inputPorcentaje + porcentajeReporte;
            //console.log(valorMax);
            $('#contenedorPorcentaje').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + valorMax + "% de avance</label>");
            if (valorMax <= 100) {
                $('#btnSaveEstructura').removeAttr("disabled");
                $('#porcentaje').removeAttr("hidden");
            } else {
                $("#btnSaveEstructura").attr("disabled", true);
                $('#porcentaje').removeAttr("hidden");
                $('#contenedorPorcentaje').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El porcentaje " + valorMax + "% es mayor a 100%. Ingresar otro valor en Porcentaje</label>");
            }
        } else
            $('#contenedorPorcentaje').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + porcentajeReporte + "% de avance</label>");
    }


    //************************************ SECCION DE MODIFICAR PROCESOSOS *********************************************
    function obtenerDatos(id_Proceso_Estructura) {
        popover('modalModificarEstructuraProceso');

        $.ajax({
            data: {id_Proceso_Estructura: id_Proceso_Estructura},
            url: "index.php?controller=EstructuraProcesos&action=modificar",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                console.log(respuestaJSON);

                let infoReporte = respuestaJSON.infoReportePadre;
                let reportes = respuestaJSON.allRegistrosProcesos;

                $('#id_Proceso_Estructura').val(id_Proceso_Estructura);

                $('#id_Gpo_Valores_Padre2').val(infoReporte[0]['id_Gpo_Valores_Padre']);
                $('#id_Gpo_Valores_PadreMod').text(infoReporte[0]['titulo_Reporte']);

                let secondChoice = $("#id_Reporte_PadreMod");
                secondChoice.empty();
                x = 0;
                $.each(reportes, function () {
                    if (infoReporte[0]['id_Reporte_Padre'] == reportes[x]['id_Reporte']) {
                        secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "' selected>" + reportes[x]['nombre_Reporte'] + "</option>");
                    } else {
                        secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "'>" + reportes[x]['nombre_Reporte'] + "</option>");
                    }
                    x++;
                });

                let porcentaje = infoReporte[0]['Porcentaje'];
                $('#contenedorPorcentajeMod').empty();
                $('#inputPorcentajeMod2').val(porcentaje);
                $('#inputPorcentajeMod').val(porcentaje);

                if (porcentaje != 0) {
                    $('#porcentajeMod').removeAttr("hidden");
                    $('#contenedorPorcentajeMod').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + infoReporte[0]['titulo_Reporte'] + " tiene " + respuestaJSON.porcentaje + "% de avance</label>");
                } else {
                    $("#porcentajeMod").attr("hidden", true);
                }

                $('#procentajeReporteMod').val(respuestaJSON.porcentaje);


                $('#inputCantidadMod').val(infoReporte[0]['Cantidad']);

            }
        });
    }

    function PorcentajeMod() {
        var inputPorcentaje = $('#inputPorcentajeMod').val();
        var porcentajeReporte = $('#procentajeReporteMod').val();

        var inputPorcentaje2 = $('#inputPorcentajeMod2').val();

        inputPorcentaje = parseFloat(inputPorcentaje);
        porcentajeReporte = parseFloat(porcentajeReporte);
        inputPorcentaje2 = parseFloat(inputPorcentaje2);

        console.log(porcentajeReporte + ' por: ' + inputPorcentaje + ' PorcentajeR: ' + inputPorcentaje2);

        if (!isNaN(inputPorcentaje)) {
            var valorMax = (porcentajeReporte - inputPorcentaje2) + inputPorcentaje;
            $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + valorMax + "% de avance</label>");
            if (valorMax <= 100)
                $('#btnSaveEstructuraProcessMod').removeAttr("disabled");
            else {
                $("#btnSaveEstructuraProcessMod").attr("disabled", true);
                $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El porcentaje " + valorMax + "% es mayor a 100%. Ingresar otro valor en Porcentaje</label>");
            }
        } else {
            var resultado = porcentajeReporte - inputPorcentaje2;
            $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + resultado + "% de avance</label>");
        }

    }


    //************************************ SECCION DE DUPLICAR PROCESOSOS **********************************************
    function duplicarEstructuraProceso() {
        $.ajax({
            url: "index.php?controller=EstructuraProcesos&action=getAllRegistrosDisponibles",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);

                let $secondChoice = $("#idEstructuraProcesoNueva");
                $secondChoice.empty();

                if (respuestaJSON.length == 0) {
                    $secondChoice.append("<option value='0'> Sin elementos </option>");
                    $("#btnSaveEstructuraProcessDuplicar").attr("disabled", true);

                } else {
                    x = 0;
                    $.each(respuestaJSON, function () {
                        $secondChoice.append("<option value='" + respuestaJSON[x]['id_Gpo_Valores_Reporte'] + "'>" + respuestaJSON[x]['titulo_Reporte'] + "</option>");
                        x++;
                    });
                    $("#btnSaveEstructuraProcessDuplicar").removeAttr("disabled");
                }

            }
        });

    }

</script>


<div class="modal fade" id="modalModificarEstructuraProceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar vincular reporte </h4>

                <form action="<?php echo $helper->url("EstructuraProcesos", "guardarmodificacion"); ?>" method="post"
                      class="form-horizontal">

                    <input type="hidden" name="id_Proceso_Estructura" id="id_Proceso_Estructura">

                    <input id="id_Gpo_Valores_Padre2" name="id_Gpo_Valores_Padre" hidden>

                    <label class="control-label">Seleccionar Reporte Padre:</label>
                    <label class="form-control labelPerfil" id="id_Gpo_Valores_PadreMod"> </label>


                    <label class="control-label">Seleccionar Reporte Hijo:</label><br>
                    <select name="id_Reporte_Padre" id="id_Reporte_PadreMod" class="form-control">
                    </select>

                    <label for="inputPorcentajeMod" class="control-label">Porcentaje:</label>
                    <input type="text" placeholder="Ejemplo: 0.00" name="inputPorcentaje" id="inputPorcentajeMod"
                           class="form-control" min="0.01" pattern="[0-9]+(\.[0-9][0-9]?)?"
                           max="100" onkeyup="PorcentajeMod()" required>

                    <label class="control-label">Cantidad:</label>
                    <input type="number" name="inputCantidad" id="inputCantidadMod" value="1" class="form-control"
                           min="1" required>


                    <input type="hidden" id="inputPorcentajeMod2">

                    <div id="porcentajeMod" class="form-group" hidden>
                        <div class="row" style="margin: 0px 1em 0px 1em; justify-content: center;">
                            <div id="contenedorPorcentajeMod" style="margin-top: 2em;">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="procentajeReporteMod">

                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveEstructuraProcessMod">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModalEstructurasProcesos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Vincular Reporte </h4>

                <form action="<?php echo $helper->url("EstructuraProcesos", "guardarnuevo"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label">Seleccionar Reporte Padre:</label>
                    <select name="id_Gpo_Valores_Padre" class="form-control" id="id_Gpo_Valores_Padre"
                            onchange="ReportesHijo(this)">
                        <option id="default" value="0"> Seleccionar Estructura Padre</option>
                        <?php foreach ($allReportesLlenados as $reporte) { ?>
                            <option value="<?php echo $reporte->id_Gpo_Valores_Reporte; ?>"
                                    data-parametro="<?php echo $reporte->id_Reporte; ?>">
                                <?php echo $reporte->titulo_Reporte; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label class="control-label">Seleccionar Estructura Hijo:</label><br>
                    <select name="id_Reporte_Padre" id="id_Reporte_Padre" class="form-control">
                        <option id="default" value="0"> Seleccionar estructura Hijo</option>

                    </select>

                    <label class="control-label">Porcentaje:</label>
                    <input type="text" placeholder="Ejemplo: 0.00" name="inputPorcentaje" id="inputPorcentaje"
                           class="form-control" min="0.01" pattern="[0-9]+(\.[0-9][0-9]?)?"
                           max="100" onkeyup="Porcentaje()" required>


                    <label class="control-label">Cantidad:</label>
                    <input type="number" name="inputCantidad" value="1" class="form-control" min="1" required>


                    <div id="porcentaje" class="form-group" hidden>
                        <div class="row" style="margin: 0px 1em 0px 1em; justify-content: center;">
                            <div id="contenedorPorcentaje" style="margin-top: 2em;">
                            </div>
                        </div>
                    </div>

                    <input id="procentajeReporte" hidden>

                    <br>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveEstructura" disabled>
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModalDuplicarEstructuraProceso" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Duplicar Proceso </h4>

                <br>
                <form action="<?php echo $helper->url("EstructuraProcesos", "duplicarEstructura"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label"> Seleccionar estructura a duplicar </label>
                    <select name="idEstructuraProcesoDuplicar" id="idEstructuraProcesoDuplicar" class="form-control">
                        <?php foreach ($allEstructuraProcesosGuardados as $proceso) { ?>
                            <option
                                value="<?php echo $proceso->id_Gpo_Valores_Padre ?>"> <?php echo $proceso->titulo_Reporte ?> </option>
                        <?php } ?>
                    </select>

                    <br>
                    <label class="control-label"> Seleccionar nueva estructura </label>
                    <select class="form-control" name="idEstructuraProcesoNueva"
                            id="idEstructuraProcesoNueva"> </select>


                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveEstructuraProcessDuplicar"
                                    disabled>
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<?php if ($action == "index") { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2"> <?= $mensaje; ?> </h4>
                    </div>

                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <? if (!empty($allEstructuraProcesosGuardados)) { ?>
                            <a href="#" data-trigger="hover" data-content="Duplicar Estructura" data-toggle="popover"
                               onclick="popover('myModalDuplicarEstructuraProceso')" class="px-2 m-1 h4 text-white">
                                <i class="fa fa-clone" aria-hidden="true"> </i>
                            </a>
                        <? } ?>

                        <a href="#" data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                           onclick="popover('myModalEstructurasProcesos')" class="px-2 m-1 h4 text-white">
                            <i class="fa fa-plus-square" aria-hidden="true"> </i>
                        </a>
                    </div>
                </div>
                <div class="p-2">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th>No.</th>
                            <th> Reporte Padre</th>
                            <th> Reporte Hijo</th>
                            <th> Avance</th>
                            <th> Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allEstructuraProcesos) || is_object($allEstructuraProcesos)) {
                            $contadorProcesos = 1;
                            foreach ($allEstructuraProcesos as $proceso) { ?>
                                <tr>
                                    <td><?= $proceso->id_Proceso_Estructura; ?></td>
                                    <td><?= $proceso->titulo_Reporte; ?></td>
                                    <td><?= $proceso->nombre_Reporte_Hijo; ?></td>
                                    <td><?= $proceso->Porcentaje; ?>%</td>
                                    <td><?= $proceso->Cantidad; ?></td>
                                    <td>

                                        <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                           onclick="obtenerDatos(<?= $proceso->id_Proceso_Estructura; ?>)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a> &nbsp;


                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $proceso->id_Proceso_Estructura; ?>, 'id_Proceso_Estructura', '<?= $proceso->nombre_Reporte_Hijo; ?> que esta relacionado al reporte <?= $proceso->titulo_Reporte; ?>', 'EstructuraProcesos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                    </td>
                                </tr>
                                <? $contadorProcesos++;
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<? } ?>

