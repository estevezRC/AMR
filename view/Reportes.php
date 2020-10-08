<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<?php
$accion = $_GET['accion'];
?>


<script>
    function mostrarInputSeguimiento() {
        var selectedValue = tipo_Reporte.options[tipo_Reporte.selectedIndex].value;

        if (selectedValue == 1) {
            console.log('Valor: ' + selectedValue);
            //document.getElementById(labelSegIncidencia).style.display = "inline";
            $("#labelSegIncidencia").css({"display": "inline"});
            $("#selectSegIncidencia").css({"display": "inline"});

            $("#labelDuracion").css({"display": "inline"});
            $("#inputDuracion").css({"display": "inline"});

            $("#labelAlarma").css({"display": "inline"});
            $("#inputAlarma").css({"display": "inline"});

            $("#labelRevision").css({"display": "inline"});
            $("#inputRevision").css({"display": "inline"});
        } else {
            //console.log('Valor: ' + selectedValue);
            $("#labelSegIncidencia").css({"display": "none"});
            $("#selectSegIncidencia").css({"display": "none"});

            $("#labelDuracion").css({"display": "none"});
            $("#inputDuracion").css({"display": "none"});

            $("#labelAlarma").css({"display": "none"});
            $("#inputAlarma").css({"display": "none"});

            $("#labelRevision").css({"display": "none"});
            $("#inputRevision").css({"display": "none"});
        }
    }

    function mostrarInputSeguimientoMod() {
        var selectedValueMod = tipo_ReporteMod.options[tipo_ReporteMod.selectedIndex].value;

        //console.log(selectedValueMod);
        if (selectedValueMod == 1) {
            $("#labelSegIncidenciaMod").css({"display": "inline"});
            $("#selectSegIncidenciaMod").css({"display": "inline"});

            $("#labelDuracionMod").css({"display": "inline"});
            $("#inputDuracionMod").css({"display": "inline"});

            $("#labelAlarmaMod").css({"display": "inline"});
            $("#inputAlarmaMod").css({"display": "inline"});

            $("#labelRevisionMod").css({"display": "inline"});
            $("#inputRevisionMod").css({"display": "inline"});
        } else {
            $("#labelSegIncidenciaMod").css({"display": "none"});
            $("#selectSegIncidenciaMod").css({"display": "none"});

            $("#labelDuracionMod").css({"display": "none"});
            $("#inputDuracionMod").css({"display": "none"});

            $("#labelAlarmaMod").css({"display": "none"});
            $("#inputAlarmaMod").css({"display": "none"});

            $("#labelRevisionMod").css({"display": "none"});
            $("#inputRevisionMod").css({"display": "none"});
        }
    }

    function mostrarInputSeguimientoDuplicar() {
        var selectedValue = tipo_ReporteDuplicar.options[tipo_ReporteDuplicar.selectedIndex].value;

        if (selectedValue == 1) {
            console.log('Valor: ' + selectedValue);
            //document.getElementById(labelSegIncidencia).style.display = "inline";
            $("#labelSegIncidenciaDuplicar").css({"display": "inline"});
            $("#selectSegIncidenciaDuplicar").css({"display": "inline"});

            $("#labelDuracionDuplicar").css({"display": "inline"});
            $("#inputDuracionDuplicar").css({"display": "inline"});

            $("#labelAlarmaDuplicar").css({"display": "inline"});
            $("#inputAlarmaDuplicar").css({"display": "inline"});

            $("#labelRevisionDuplicar").css({"display": "inline"});
            $("#inputRevisionDuplicar").css({"display": "inline"});
        } else {
            //console.log('Valor: ' + selectedValue);
            $("#labelSegIncidenciaDuplicar").css({"display": "none"});
            $("#selectSegIncidenciaDuplicar").css({"display": "none"});

            $("#labelDuracionDuplicar").css({"display": "none"});
            $("#inputDuracionDuplicar").css({"display": "none"});

            $("#labelAlarmaDuplicar").css({"display": "none"});
            $("#inputAlarmaDuplicar").css({"display": "none"});

            $("#labelRevisionDuplicar").css({"display": "none"});
            $("#inputRevisionDuplicar").css({"display": "none"});
        }
    }

    function validate() {

        if (!$('input[name="area[]"]').is(':checked')) {
            alertify.error("Seleccionar al menos un área");
            return false;
        }

        /*if (!$('input[name="perfil[]"]').is(':checked')) {
            alertify.error('Seleccionar al menos un Perfil');
            return false;
        }*/

    }

    function obtenerDatosReporte(idReporte) {
        popover('myModalModReporte');
        //console.log(idReporte);
        $.ajax({
            data: {idReporte: idReporte},
            url: "index.php?controller=Reportes&action=modificar",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                console.log(respuestaJSON);

                $('#myModalLabelTitulo').text('Modificar ' + respuestaJSON.datosreporte.nombre_Reporte);
                $('#id_reporteMod').val(idReporte);
                $('#nombre_ReporteMod').val(respuestaJSON.datosreporte.nombre_Reporte);
                $('#descripcionMod').val(respuestaJSON.datosreporte.descripcion_Reporte);

                let tipoDatos;
                let reporteTipoFive = respuestaJSON.reporteTipoFive.length;
                let reporteTipoSix = respuestaJSON.reporteTipoSix.length;
                let reporteTipoSeven = respuestaJSON.reporteTipoSeven.length;

                if (reporteTipoFive == 0) {
                    if (reporteTipoSix == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 6) {
                        if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia", "Termino de jornada laboral"];
                            console.log('Bim no existe ni Asistencia,  Asitencia Si existe, Salida Si existe 11111');
                        } else {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia"];
                            console.log('Bim no existe ni Asistencia O Asitencia Si 2222222');
                        }
                    } else {
                        if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "", "Termino de jornada laboral"];
                            console.log('Bim no existe ni Asistencia,  Asitencia Si existe, Salida Si existe 33333');
                        } else {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM"];
                            console.log('Bim no existe, Asistencia existe, Salida no exite 444444');
                        }
                    }
                } else {
                    if (respuestaJSON.datosreporte['tipo_Reporte'] == 5) {
                        if (reporteTipoSix == 0) {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia", "Termino de jornada laboral"];
                            console.log('Bim existe y Asistencia no existe ni Salida 55555');
                        } else {
                            if (reporteTipoSeven == 0) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "", "Termino de jornada laboral"];
                                console.log('Bim existe, Asistencia existe, Salida no existe 6666666');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "Documento BIM"];
                                console.log('Bim existe, Asistencia existe, Salida existe 77777');
                            }
                        }
                    } else {
                        if (reporteTipoSix == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 6) {
                            if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "", "Asistencia", "Termino de jornada laboral"];
                                console.log('bim existe2, Asistencia selectd y Salida no existe XXXXXX');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "", "Asistencia"];
                                console.log('Bim existe, Asistencia selected, Salida existe BBBBB');
                            }
                        } else {
                            if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia", "", "", "Termino de jornada laboral"];
                                console.log('bim existe2 y Asistencia Existe, Salida no 88888');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicación", "Inventario", "Seguimiento a Incidencia"];
                                console.log('bim existe2, Asistencia Existe Salida existe 9999');
                            }
                        }
                    }
                }

                var $secondChoice = $("#tipo_ReporteMod");
                $secondChoice.empty();
                let x = 0;
                $.each(tipoDatos, function () {
                    if (respuestaJSON.datosreporte.tipo_Reporte == x)
                        $secondChoice.append("<option value='" + x + "' selected>" + tipoDatos[x] + "</option>");
                    else if (tipoDatos[x])
                        $secondChoice.append("<option value='" + x + "'>" + tipoDatos[x] + "</option>");
                    x++;
                });


                const areas = $("#containerAreas");
                areas.empty();
                for (i = 0; i < respuestaJSON.allAreas.length; i++) {
                    if (respuestaJSON.areas.indexOf(respuestaJSON.allAreas[i].id_Area) >= 0)
                        areas.append("<input type='checkbox' value='" + respuestaJSON.allAreas[i].id_Area + "' name='area[]' id='areaCheck_" + respuestaJSON.allAreas[i].id_Area + "' checked> " + respuestaJSON.allAreas[i].nombre_Area + "<br>");
                    else
                        areas.append("<input type='checkbox' value='" + respuestaJSON.allAreas[i].id_Area + "' name='area[]' id='areaCheck_" + respuestaJSON.allAreas[i].id_Area + "'> " + respuestaJSON.allAreas[i].nombre_Area + "<br>");
                }

                const perfiles = $("#containerPerfiles");
                perfiles.empty();
                for (i = 0; i < respuestaJSON.allPerfiles.length; i++) {
                    if (respuestaJSON.perfiles.indexOf(respuestaJSON.allPerfiles[i].id_Perfil_Usuario) >= 0)
                        perfiles.append("<input type='checkbox' value='" + respuestaJSON.allPerfiles[i].id_Perfil_Usuario + "' name='perfil[]' id='perfilCheck_" + respuestaJSON.allPerfiles[i].id_Perfil_Usuario + "' checked> " + respuestaJSON.allPerfiles[i].nombre_Perfil + "<br>");
                    else
                        perfiles.append("<input type='checkbox' value='" + respuestaJSON.allPerfiles[i].id_Perfil_Usuario + "' name='perfil[]' id='perfilCheck_" + respuestaJSON.allPerfiles[i].id_Perfil_Usuario + "'> " + respuestaJSON.allPerfiles[i].nombre_Perfil + "<br>");
                }


                let selectSegIncidencia = $('#selectSegIncidenciaMod');
                selectSegIncidencia.empty();

                if (respuestaJSON.datosreporte.id_Seguimiento != 0) {
                    let duracion = respuestaJSON.datosreporte.tiempo_Reporte / 60;
                    let alarma = respuestaJSON.datosreporte.tiempo_Alarma / 60;
                    let revision = respuestaJSON.datosreporte.tiempo_Revision / 60;

                    $('#inputDuracionMod').val(duracion);
                    $('#inputAlarmaMod').val(alarma);
                    $('#inputRevisionMod').val(revision);

                    selectSegIncidencia.append("<option value='" + respuestaJSON.datosreporte.id_Seguimiento + "' selected>" + respuestaJSON.datosreporte.nombre_ReporteSeguimiento + " </option>");
                    let i = 0;
                    $.each(respuestaJSON.allReportesSeguimiento, function () {
                        if (respuestaJSON.datosreporte.id_Seguimiento != respuestaJSON.allReportesSeguimiento[i].id_Reporte)
                            selectSegIncidencia.append("<option value='" + respuestaJSON.allReportesSeguimiento[i].id_Reporte + "'>" + respuestaJSON.allReportesSeguimiento[i].nombre_Reporte + " </option>");
                        i++;
                    });
                    selectSegIncidencia.append("<option value='0'> Sin Seguimiento </option>");

                } else {
                    selectSegIncidencia.append("<option value='0'> Sin Seguimiento </option>");
                    let i = 0;
                    $.each(respuestaJSON.allReportesSeguimiento, function () {
                        //if (respuestaJSON.datosreporte.id_Seguimiento != respuestaJSON.allReportesSeguimiento[i].id_Reporte)
                        selectSegIncidencia.append("<option value='" + respuestaJSON.allReportesSeguimiento[i].id_Reporte + "'>" + respuestaJSON.allReportesSeguimiento[i].nombre_Reporte + " </option>");
                        i++;
                    });
                }
                mostrarInputSeguimientoMod();

            }
        });
    }

    function getAllReportesByIdProyecto() {
        let id_Proyecto = $('#id_ProyectoDuplicarReporte').val();
        //console.log(id_Proyecto);

        $.ajax({
            data: {id_Proyecto: id_Proyecto},
            url: "index.php?controller=Reportes&action=getAllReportesByIdProyecto",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                console.log(respuestaJSON);

                var $secondChoice = $("#id_ReporteDuplicarReporte");
                $secondChoice.empty();

                if (respuestaJSON.length == 0) {
                    $("#btnSaveDuplicar").attr("disabled", true);
                    $secondChoice.append("<option> No hay Plantillas en el Proyecto </option>");
                } else {
                    $('#btnSaveDuplicar').removeAttr("disabled");
                    let x = 0;
                    $.each(respuestaJSON, function () {
                        $secondChoice.append("<option value='" + respuestaJSON[x].id_Reporte + "'>" + respuestaJSON[x].nombre_Reporte + "</option>");
                        x++;
                    });
                }

            }
        });
    }

    $(document).ready(function () {
        let accion = '<?php echo $action; ?>';
        let selectedValueMod;

        if (accion == 'modificar')
            selectedValueMod = tipo_ReporteMod.options[tipo_ReporteMod.selectedIndex].value;
        else
            selectedValueMod = '';

        if (selectedValueMod == 1) {
            $("#labelSegIncidenciaMod").css({"display": "inline"});
            $("#selectSegIncidenciaMod").css({"display": "inline"});

            $("#labelDuracionMod").css({"display": "inline"});
            $("#inputDuracion").css({"display": "inline"});

            $("#labelAlarma").css({"display": "inline"});
            $("#inputAlarma").css({"display": "inline"});

            $("#labelRevision").css({"display": "inline"});
            $("#inputRevision").css({"display": "inline"});
        } else {
            $("#labelSegIncidenciaMod").css({"display": "none"});
            $("#selectSegIncidenciaMod").css({"display": "none"});

            $("#labelDuracion").css({"display": "none"});
            $("#inputDuracion").css({"display": "none"});

            $("#labelAlarma").css({"display": "none"});
            $("#inputAlarma").css({"display": "none"});

            $("#labelRevision").css({"display": "none"});
            $("#inputRevision").css({"display": "none"});
        }


        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        getAllReportesByIdProyecto();

        $('#formDuplicarReporte').submit(function (evento) {
            evento.preventDefault();
            let a = enviarDatosForDuplicar();

            //if (a == 1) {
            setTimeout(function () {
                //document.location.href = ''
                location.reload();
            }, 2100);
            //}
        });
    });

    function enviarDatosForDuplicar() {
        let accion = 0;
        let id_Proyecto = <?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>;
        let id_Reporte = $('#id_ReporteDuplicarReporte').val();
        let nombreReporte = $('#nombreReporte').val();
        //console.log(id_Proyecto + ' ' + id_Reporte);
        //document.location.href = 'replicaReportes/replicaReportes.php?idReporte=' + id_Reporte + '&idProyecto=' + id_Proyecto;
        $.ajax({
            data: {id_Proyecto: id_Proyecto, id_Reporte: id_Reporte, nombreReporte: nombreReporte},
            url: "replicaReportes/replicaReportes.php",
            method: "POST",
            success: function (response) {
                console.log(response);

                accion = response;

                if (response == 1) {
                    alertify.success('Reporte duplicado exitosamente');
                } else {
                    alertify.error('El reporte ya existe');
                    return false;
                }
            }
        });

        return accion;

    }
</script>

<div class="modal fade" id="myModalAddReportes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 font-weight-bold bg-gradient-secondary">
                <h5 class="modal-title text-center text-white w-100" id="myModalLabel"> Nueva Plantilla</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?php echo $helper->url("Reportes", "guardarnuevo"); ?>" method="post"
                  class="form-horizontal" onsubmit="return validate()">
                <div class="modal-body">
                    <input type="hidden" name="id_Proyecto" value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>">

                    <div class="form-group">
                        <label class="control-label">Nombre:</label>
                        <input type="text" name="nombre_Reporte" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Descripción:</label>
                        <input type="text" name="descripcion_Reporte" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Tipo de registro:</label>
                        <select id="tipo_Reporte" name="tipo_Reporte" class="custom-select"
                                onchange="mostrarInputSeguimiento()">
                            <option value="0">Reporte</option>
                            <option value="1">Incidencia</option>
                            <option value="2">Ubicación</option>
                            <option value="3">Inventario</option>
                            <option value="4">Seguimiento a Incidencia</option>

                            <?php if (empty($reporteTipoFive)) { ?>
                                <option value="5">Documento BIM</option>
                            <?php } ?>

                            <?php if (empty($reporteTipoSix)) { ?>
                                <option value="6">Asistencia</option>
                            <?php } ?>

                            <?php if (empty($reporteTipoSeven)) { ?>
                                <option value="7">Termino de jornada laboral</option>
                            <?php } ?>

                            <?php if (empty($reporteTipoSeven)) { ?>
                                <option value="8"> Documentos Entregables </option>
                            <?php } ?>

                            <?php if (empty($reporteTipoSeven)) { ?>
                                <option value="9"> Minuta </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" style="display: none;" id="labelSegIncidencia"> Seguimientos de
                            Incidencias: </label>
                        <select name="seguimientoIncidencia" id="selectSegIncidencia" class="custom-select"
                                style="display: none;">
                            <option value="0"> Sin Seguimiento</option>
                            <? foreach ($allReportesSeguimiento as $reporteSeguimiento) { ?>
                                <option value="<?= $reporteSeguimiento->id_Reporte; ?>"
                                        class="form-control"> <?= $reporteSeguimiento->nombre_Reporte; ?> </option>
                            <? } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" id="labelDuracion" style="display: none;">Duración
                            (hrs.):</label>
                        <input type="number" id="inputDuracion" name="tiempo_Reporte" style="display: none;"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label" id="labelAlarma" style="display: none;">Alarma
                            (hrs.):</label>
                        <input type="number" name="tiempo_Alarma" style="display: none;" id="inputAlarma"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label" id="labelRevision" style="display: none;">Revisión
                            (hrs.):</label>
                        <input type="number" name="tiempo_Revision" style="display: none;" id="inputRevision"
                               class="form-control">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="control-label">Área(s):</label>
                            <? foreach ($allareas as $area) { ?>
                                <input type="checkbox" name="area[]" id="areaCheck<?= $area->id_Area; ?>"
                                       value="<?= $area->id_Area; ?>">
                                <?= $area->nombre_Area . "<br/>";
                            } ?>
                        </div>
                        <div class="col">
                            <label class="control-label">Perfil(es) (FIEL):</label><br/>
                            <? foreach ($allperfiles as $perfil) { ?>
                                <input type="checkbox" name="perfil[]"
                                       id="perfilCheck<?= $perfil->id_Perfil_Usuario; ?>"
                                       value="<?= $perfil->id_Perfil_Usuario; ?>">
                                <?= $perfil->nombre_Perfil . "<br/>";
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-w-m btn-danger" style="text-align: center;">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ************************************ SECCION MODIFICAR ELEMENTO *********************************************** -->
<div class="modal fade" id="myModalModReporte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="margin-top:0%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabelTitulo" style="text-align: center"></h4>

                <form action="<?php echo $helper->url("Reportes", "guardarmodificacion"); ?>" method="post"
                      class="form-horizontal" onsubmit="return validate()">

                    <input type="hidden" name="id_Reporte" id="id_reporteMod">


                    <label class="control-label">Nombre:</label>
                    <input type="text" name="nombre_Reporte" id="nombre_ReporteMod"
                           class="form-control" required>


                    <label class="control-label">Descripción:</label>
                    <input type="text" name="descripcion_Reporte" id="descripcionMod" class="form-control" required>


                    <label class="control-label">Tipo de registro:</label>
                    <select name="tipo_Reporte" class="form-control" id="tipo_ReporteMod"
                            onchange="mostrarInputSeguimientoMod()">

                    </select>


                    <label class="control-label" style="display: none;" id="labelSegIncidenciaMod"> Seguimientos de
                        Incidencias: </label>
                    <select name="seguimientoIncidenciaMod" id="selectSegIncidenciaMod" class="form-control"
                            style="display: none;">

                    </select>


                    <label class="control-label" id="labelDuracionMod">Duración (hrs.):</label>
                    <input type="number" name="tiempo_Reporte" id="inputDuracionMod" class="form-control">


                    <label class="control-label" id="labelAlarmaMod">Alarma (hrs.):</label>
                    <input type="number" name="tiempo_Alarma" id="inputAlarmaMod" class="form-control">


                    <label class="control-label" id="labelRevisionMod">Revisión (hrs.):</label>
                    <input type="number" name="tiempo_Revision" id="inputRevisionMod" class="form-control">


                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">Área(s):</label><br/>

                            <div id="containerAreas">
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Perfil(es) (FIEL):</label><br/>

                            <div id="containerPerfiles">
                            </div>

                        </div>
                    </div>

                    <hr style="border-color: #fc9260!important;">

                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger"
                                    style="text-align: center;">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- **************** SECCION DE AGREGAR NUEVO REPORTE CON CONFIGURACION DE OTRO REPORTE *************************** -->
<div class="modal fade" id="myModalDuplicarReportes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="margin-top:0%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Duplicar Plantilla</h4>

                <form action="#" method="post" class="form-horizontal" id="formDuplicarReporte">

                    <!--<input type="hidden" name="id_Proyecto" value="<?php /*echo $_SESSION[ID_PROYECTO_SUPERVISOR]; */ ?>">-->

                    <br>
                    <label class="control-label"> Seleccionar Proyecto Origen: </label>
                    <select class="form-control" name="id_Proyecto" id="id_ProyectoDuplicarReporte"
                            onchange="getAllReportesByIdProyecto()">
                        <? foreach ($allProyectos as $proyecto) { ?>
                            <option value="<?php echo $proyecto->id_Proyecto; ?>"
                                    class="form-control"> <?php echo $proyecto->nombre_Proyecto; ?> </option>
                        <? } ?>
                    </select>

                    <label class="control-label" style="padding-top: 15px;"> Seleccionar Plantilla: </label>
                    <select class="form-control" name="id_Reporte" id="id_ReporteDuplicarReporte">

                    </select>


                    <label class="control-label" style="padding-top: 15px;">
                        Nombre:
                        <span> (Nota: Si la plantilla existe, se necesita renombrar)</span>
                    </label>
                    <input type="text" name="nombreReporte" class="form-control" id="nombreReporte">

                    <br>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnSaveDuplicar"
                                    style="text-align: center;" disabled>
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
/*------------------------------------ ACCION INDEX: MUESTRA TODOS LOS REPORTES --------------------------------------*/
if (($action == "index") || ($action == "modificar")) { ?>

    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <a class="px-2 m-1 h4 text-white"
                           href="#" data-trigger="hover" data-content="Duplicar Configuración de Plantilla"
                           data-toggle="popover"
                           onclick="popover('myModalDuplicarReportes')">
                            <i class="fa fa-clone" aria-hidden="true"></i></a>

                        <a class="px-2 m-1 h4 text-white"
                           href="#" data-trigger="hover" data-content="Nueva Plantilla"
                           data-toggle="popover"
                           onclick="popover('myModalAddReportes')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped thead-dark">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">Id_Reporte</th>
                            <th class="align-middle">Nombre de Plantilla</th>
                            <th class="align-middle">Descripción del Reporte</th>
                            <th class="align-middle">Áreas</th>
                            <th class="align-middle">Tipo</th>
                            <th class="align-middle">Perfil(es) (FIEL)</th>
                            <th class="align-middle">Reporte de Seguimiento</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        <? if (is_array($allreportes) || is_object($allreportes)) {
                            $contadorElementos = 1;
                            foreach ($allreportes as $reporte) { ?>
                                <tr>
                                    <td><?= $reporte->id_Reporte; ?></td>
                                    <td><?= $reporte->nombre_Reporte; ?></td>
                                    <td><?= $reporte->descripcion_Reporte; ?></td>
                                    <td><?= $this->areas($reporte->Areas, $allareas); ?></td>
                                    <td><?= $this->nombreReporteId($reporte->tipo_Reporte, 1, 'null'); ?></td>

                                    <td>
                                        <?= $this->perfiles($reporte->perfiles_firma, $allperfiles); ?>
                                    </td>

                                    <td>
                                        <?= $reporte->id_Seguimiento == 0 ? 'Ninguno' : $reporte->nombre_ReporteSeguimiento; ?>
                                    </td>

                                    <td>
                                        <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                           onclick="obtenerDatosReporte(<?= $reporte->id_Reporte; ?>)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a> &nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $reporte->id_Reporte; ?>, 'id_Reporte','<?= $reporte->nombre_Reporte; ?>', 'Reportes', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;

                                        <a href="index.php?controller=CamposReporte&action=getCamposByReporte&Id_Reporte=<?= $reporte->id_Reporte; ?>"
                                           data-trigger="hover" data-content="Configurar Plantilla"
                                           data-toggle="popover"
                                           data-placement="left"> <i class="fa fa-cog" aria-hidden="true"></i></a>
                                        &nbsp;
                                    </td>
                                </tr>
                                <? $contadorElementos++;
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if ($action == "ayuda") { ?>

    <div class="container-fluid flex-column justify-content-center p-3">

        <div class="row d-flex justify-content-between">
            <div class="col-sm-10 d-flex align-items-center">
                <h3 class="text-secondary"> <?php echo $mensaje; ?> </h3>
            </div>
        </div>


        <hr class="linea-separadora">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11">

                <div class="container">

                    <div class="card text-white mb-3" style="max-width: 70rem;">
                        <div class="card-header bg-success"> Manuales</div>
                        <div class="card-body">

                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a target="_blank" href="descargables/material_ayuda/manualUsuarioSupervisor.pdf">
                                        Manual Supervisor.uno</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                </div>

            </div>
        </div>

    </div>
<?php } ?>
