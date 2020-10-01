<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>

    var porcentaje = 0;

    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        ReportesHijo();
        duplicarProceso();

    });

    //************************************** SECCION DE NUEVO PROCESOSOS ***********************************************
    function ReportesHijo() {
        var idReportePadre = $('#idReportePadre').val();
        $('#contenedorPorcentaje').empty();
        $.ajax({
            data: {idReportePadre: idReportePadre},
            url: "index.php?controller=Procesos&action=getDatosReportHijos",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);
                var porcentaje = respuestaJSON.porcentaje;
                console.log(porcentaje);
                var reportes = respuestaJSON.allReportes;

                var nombreReportePadre = respuestaJSON.nombreReportePadre;

                var $secondChoice = $("#idReporteHijo");
                if (reportes.length == 0) {
                    $secondChoice.empty();
                    $secondChoice.append("<option> No hay más Reportes (Crear nuevos Reportes) </option>");

                    $("#btnSaveProcess").attr("disabled", true);
                } else if (porcentaje == 100) {
                    //$("#btnSaveProcess").attr("disabled", true);
                    $('#porcentaje').removeAttr("hidden");
                    $('#contenedorPorcentaje').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + nombreReportePadre + " tiene " + porcentaje + "% de avance</label>");
                } else {
                    $('#btnSaveProcess').removeAttr("disabled");

                    if (porcentaje != 0) {
                        $('#porcentaje').removeAttr("hidden");
                        $('#contenedorPorcentaje').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + nombreReportePadre + " tiene " + porcentaje + "% de avance</label>");
                    } else {
                        $("#porcentaje").attr("hidden", true);
                    }

                    $secondChoice.empty();
                    x = 0;
                    $.each(reportes, function () {
                        $secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "'>" + reportes[x]['nombre_Reporte'] + "</option>");
                        x++;
                    });

                    $('#procentajeReporte').val(porcentaje);
                }
            }
        });
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
                $('#btnSaveProcess').removeAttr("disabled");
                $('#porcentaje').removeAttr("hidden");
            } else {
                $("#btnSaveProcess").attr("disabled", true);
                $('#porcentaje').removeAttr("hidden");
                $('#contenedorPorcentaje').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El porcentaje " + valorMax + "% es mayor a 100%. Ingresar otro valor en Porcentaje</label>");
            }
        } else
            $('#contenedorPorcentaje').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + porcentajeReporte + "% de avance</label>");
    }


    //************************************ SECCION DE MODIFICAR PROCESOSOS *********************************************
    function obtenerDatos(idProceso) {
        popover('modalModificarProceso');

        $.ajax({
            data: {idProceso: idProceso},
            url: "index.php?controller=Procesos&action=modificar",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);
                $('#id_Proceso').val(idProceso);
                let infoReportePAdre = respuestaJSON.infoReportePadre[0]['nombre_Reporte_Padre'];
                let idReportePadre = respuestaJSON.infoReportePadre[0]['Id_Reporte_Padre'];
                $('#idReportePadreMod').text(infoReportePAdre);
                $('#idReportePadreMod2').val(idReportePadre);

                let nombre_Reporte_Hijo = respuestaJSON.infoReportePadre[0]['nombre_Reporte_Hijo'];
                let idReporteHijo = respuestaJSON.infoReportePadre[0]['Id_Reporte_Hijo'];
                let procentajeReporteHijo = respuestaJSON.infoReportePadre[0]['Porcentaje'];

                $('#contenedorPorcentajeMod').empty();
                $('#inputPorcentajeMod').val(procentajeReporteHijo);
                $('#inputPorcentajeMod2').val(procentajeReporteHijo);

                $.ajax({
                    data: {idReportePadre: idReportePadre},
                    url: "index.php?controller=Procesos&action=getDatosReportHijos",
                    method: "POST",
                    success: function (response) {
                        let respuestaJSON = $.parseJSON(response);
                        //console.log(respuestaJSON);
                        var porcentaje = respuestaJSON.porcentaje;
                        var reportes = respuestaJSON.allReportes;
                        var nombreReportePadre = respuestaJSON.nombreReportePadre;

                        if (porcentaje != 0) {
                            $('#porcentajeMod').removeAttr("hidden");
                            $('#contenedorPorcentajeMod').append("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El reporte " + nombreReportePadre + " tiene " + porcentaje + "% de avance</label>");
                        } else {
                            $("#porcentajeMod").attr("hidden", true);
                        }

                        let $secondChoice = $("#idReporteHijoMod");
                        $secondChoice.empty();
                        $secondChoice.append("<option value='" + idReporteHijo + "' selected>" + nombre_Reporte_Hijo + "</option>");
                        x = 0;
                        $.each(reportes, function () {
                            $secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "'>" + reportes[x]['nombre_Reporte'] + "</option>");
                            x++;
                        });

                        $('#procentajeReporteMod').val(porcentaje);
                    }
                });
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

        //console.log(porcentajeReporte + ' por: ' + inputPorcentaje + ' PorcentajeR: ' + inputPorcentaje2);

        if (!isNaN(inputPorcentaje)) {
            var valorMax = (porcentajeReporte - inputPorcentaje2) + inputPorcentaje;
            $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + valorMax + "% de avance</label>");
            if (valorMax <= 100)
                $('#btnSaveProcessMod').removeAttr("disabled");
            else {
                $("#btnSaveProcessMod").attr("disabled", true);
                $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> El porcentaje " + valorMax + "% es mayor a 100%. Ingresar otro valor en Porcentaje</label>");
            }
        } else {
            var resultado = porcentajeReporte - inputPorcentaje2;
            $('#contenedorPorcentajeMod').html("<label class='control-label labelPerfil' style='width: 100%; text-align: center'> Ahora tiene " + resultado + "% de avance</label>");
        }

    }


    //************************************ SECCION DE DUPLICAR PROCESOSOS **********************************************
    function duplicarProceso() {
        //var idReportePadre = $('#idReportePadre').val();
        $.ajax({
            //data: {idReportePadre: idReportePadre},
            url: "index.php?controller=Procesos&action=getAllReportesPadreDisponibles",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);

                let $secondChoice = $("#idProcesoNuevo");
                $secondChoice.empty();

                if (respuestaJSON.length == 0) {
                    $secondChoice.append("<option value='0'> Sin elementos </option>");
                    $("#btnSaveProcessDuplicar").attr("disabled", true);

                } else {
                    x = 0;
                    $.each(respuestaJSON, function () {
                        $secondChoice.append("<option value='" + respuestaJSON[x]['id_Reporte'] + "'>" + respuestaJSON[x]['nombre_Reporte'] + "</option>");
                        x++;
                    });
                    $("#btnSaveProcessDuplicar").removeAttr("disabled");
                }

            }
        });

    }


</script>


<div class="modal fade" id="modalModificarProceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar vincular reporte </h4>

                <form action="<?php echo $helper->url("Procesos", "guardarmodificacion"); ?>" method="post"
                      class="form-horizontal">

                    <input type="hidden" name="id_Proceso" id="id_Proceso">

                    <input id="idReportePadreMod2" name="idReportePadre" hidden>


                    <label class="control-label">Reporte Padre:</label>
                    <label class="form-control labelPerfil" id="idReportePadreMod"> </label>


                    <label class="control-label">Seleccionar Reporte Hijo:</label><br>
                    <select name="idReporteHijo" id="idReporteHijoMod" class="form-control">
                    </select>

                    <label for="inputPorcentajeMod" class="control-label">Porcentaje:</label>
                    <input type="text" placeholder="Ejemplo: 0.00" name="inputPorcentaje" id="inputPorcentajeMod"
                           class="form-control" min="0.01" pattern="[0-9]+(\.[0-9][0-9]?)?"
                           max="100" onkeyup="PorcentajeMod()" required>


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
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveProcessMod">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModalProcesos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Vincular Reporte </h4>

                <form action="<?php echo $helper->url("Procesos", "guardarnuevo"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label">Seleccionar Reporte Padre:</label>
                    <select name="idReportePadre" class="form-control" onchange="ReportesHijo()" id="idReportePadre">
                        <?php foreach ($allReportes as $reporte) { ?>
                            <option name="<?php echo $reporte->nombre_Reporte; ?>"
                                    id="<?php echo $reporte->id_Reporte; ?>"
                                    value="<?php echo $reporte->id_Reporte; ?>"><?php echo $reporte->nombre_Reporte; ?></option>
                        <?php } ?>
                    </select>

                    <label class="control-label">Seleccionar Reporte Hijo:</label><br>
                    <select name="idReporteHijo" id="idReporteHijo" class="form-control">
                        <option id="default" value="0"> Seleccionar reporte</option>
                    </select>

                    <label class="control-label">Porcentaje:</label>
                    <input type="text" placeholder="Ejemplo: 0.00" name="inputPorcentaje" id="inputPorcentaje"
                           class="form-control" min="0.01" pattern="[0-9]+(\.[0-9][0-9]?)?"
                           max="100" onkeyup="Porcentaje()" required>

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
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveProcess">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModalDuplicarProceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Duplicar Proceso </h4>

                <br>
                <form action="<?php echo $helper->url("Procesos", "duplicarProceso"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label"> Seleccionar proceso a duplicar </label>
                    <select name="idProcesoDuplicar" id="idProcesoDuplicar" class="form-control">
                        <?php foreach ($allProcesosGuardados as $proceso) { ?>
                            <option value="<?php echo $proceso->Id_Reporte_Padre ?>"> <?php echo $proceso->nombre_Reporte_Padre ?> </option>
                        <?php } ?>
                    </select>

                    <br>
                    <label class="control-label"> Seleccionar proceso nuevo </label>
                    <select class="form-control" name="idProcesoNuevo" id="idProcesoNuevo"> </select>


                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveProcessDuplicar" disabled>
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<?php
if ($action == "index") { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2"> <?php echo $mensaje; ?> </h4>
                    </div>

                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <?php if (!empty($allProcesos)) { ?>
                            <a href="#" data-trigger="hover" data-content="Duplicar Proceso" data-toggle="popover"
                               onclick="popover('myModalDuplicarProceso')" class="px-2 m-1 h4 text-white">
                                <i class="fa fa-clone" aria-hidden="true"> </i>
                            </a>
                        <?php } ?>

                        <a href="#" data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                           onclick="popover('myModalProcesos')" class="px-2 m-1 h4 text-white">
                            <i class="fa fa-plus-square" aria-hidden="true"> </i>
                        </a>
                    </div>
                </div>
                <div class="p-2">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">No.</th>
                            <th class="align-middle">Reporte Padre</th>
                            <th class="align-middle">Reporte Hijo</th>
                            <th class="align-middle">Avance</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?                        if (is_array($allProcesos) || is_object($allProcesos)) {
                            $contadorProcesos = 1;
                            foreach ($allProcesos as $proceso) { ?>
                                <tr>
                                    <td><?= $contadorProcesos; ?></td>
                                    <td><?= $proceso->nombre_Reporte_Padre; ?></td>
                                    <td><?= $proceso->nombre_Reporte_Hijo; ?></td>
                                    <td><?= $proceso->Porcentaje; ?>%</td>
                                    <td>

                                        <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                           onclick="obtenerDatos(<?= $proceso->Id_Proceso; ?>)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a> &nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $proceso->Id_Proceso; ?>, 'id_Proceso', '<?= $proceso->nombre_Reporte_Hijo; ?> que esta relacionado al reporte padre <?= $proceso->nombre_Reporte_Padre; ?>', 'Procesos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;

                                    </td>
                                </tr>
                                <?
                                $contadorProcesos++;
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<? } ?>

