<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>
<script src="js/utils/utils.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>

    .label-info {
        background-color: #5bc0de;
    }

    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
    }

    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        display: inline-block;
        padding: 4px 6px;
        color: #555;
        vertical-align: middle;
        border-radius: 4px;
        max-width: 100%;
        line-height: 22px;
        cursor: text;
    }
    .bootstrap-tagsinput input {
        border: none;
        box-shadow: none;
        outline: none;
        background-color: transparent;
        padding: 0 6px;
        margin: 0;
        width: auto;
        max-width: inherit;
    }
    .bootstrap-tagsinput.form-control input::-moz-placeholder {
        color: #777;
        opacity: 1;
    }
    .bootstrap-tagsinput.form-control input:-ms-input-placeholder {
        color: #777;
    }
    .bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
        color: #777;
    }
    .bootstrap-tagsinput input:focus {
        border: none;
        box-shadow: none;
    }
    .bootstrap-tagsinput .tag {
        margin-right: 2px;
        color: white;
    }
    .bootstrap-tagsinput .tag [data-role="remove"] {
        margin-left: 8px;
        cursor: pointer;
    }
    .bootstrap-tagsinput .tag [data-role="remove"]:after {
        content: "x";
        padding: 0px 2px;
    }
    .bootstrap-tagsinput .tag [data-role="remove"]:hover {
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    .bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    }

    /*
 * bootstrap-tagsinput v0.8.0
 *
 */

    .twitter-typeahead .tt-query,
    .twitter-typeahead .tt-hint {
        margin-bottom: 0;
    }

    .twitter-typeahead .tt-hint
    {
        display: none;
    }

    .tt-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        font-size: 14px;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        background-clip: padding-box;
        cursor: pointer;
    }

    .tt-suggestion {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.428571429;
        color: #333333;
        white-space: nowrap;
    }

    .tt-suggestion:hover,
    .tt-suggestion:focus {
        color: #ffffff;
        text-decoration: none;
        outline: 0;
        background-color: #428bca;
    }

</style>

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
            alertify.error("Seleccionar al menos un ??rea");
            return false;
        }

        /*if (!$('input[name="perfil[]"]').is(':checked')) {
            alertify.error('Seleccionar al menos un Perfil');
            return false;
        }*/

    }

    /*function obtenerDatosReporte(idReporte) {
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
                            tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia", "Termino de jornada laboral"];
                            console.log('Bim no existe ni Asistencia,  Asitencia Si existe, Salida Si existe 11111');
                        } else {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia"];
                            console.log('Bim no existe ni Asistencia O Asitencia Si 2222222');
                        }
                    } else {
                        if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "", "Termino de jornada laboral"];
                            console.log('Bim no existe ni Asistencia,  Asitencia Si existe, Salida Si existe 33333');
                        } else {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM"];
                            console.log('Bim no existe, Asistencia existe, Salida no exite 444444');
                        }
                    }
                } else {
                    if (respuestaJSON.datosreporte['tipo_Reporte'] == 5) {
                        if (reporteTipoSix == 0) {
                            tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "Asistencia", "Termino de jornada laboral"];
                            console.log('Bim existe y Asistencia no existe ni Salida 55555');
                        } else {
                            if (reporteTipoSeven == 0) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM", "", "Termino de jornada laboral"];
                                console.log('Bim existe, Asistencia existe, Salida no existe 6666666');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "Documento BIM"];
                                console.log('Bim existe, Asistencia existe, Salida existe 77777');
                            }
                        }
                    } else {
                        if (reporteTipoSix == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 6) {
                            if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "", "Asistencia", "Termino de jornada laboral"];
                                console.log('bim existe2, Asistencia selectd y Salida no existe XXXXXX');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "", "Asistencia"];
                                console.log('Bim existe, Asistencia selected, Salida existe BBBBB');
                            }
                        } else {
                            if (reporteTipoSeven == 0 || respuestaJSON.datosreporte['tipo_Reporte'] == 7) {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia", "", "", "Termino de jornada laboral"];
                                console.log('bim existe2 y Asistencia Existe, Salida no 88888');
                            } else {
                                tipoDatos = ["Reporte", "Incidencia", "Ubicaci??n", "Inventario", "Seguimiento a Incidencia"];
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
    }*/

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

                let tipoDatos = respuestaJSON.tipoDatos;

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
            dataType: 'JSON',
            url: "index.php?controller=Reportes&action=getAllReportesByIdProyecto",
            method: "POST",
            success: function (respuestaJSON) {

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
        console.log('JQUERY WORKS');
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


        var insercion = '<?php echo $insercion; ?>';
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

        $('#formAddVideoManual').submit(function (evento) {
            evento.preventDefault();
            let wrapper = $('.wrapper');
            let progress_bar = $('.progress-bar');
            const formVideo = document.getElementById("formAddVideoManual");
            const formData = new FormData(formVideo);
            console.log(Array.from(formData.entries()));
            progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
            progress_bar.css('width', '0%');
            progress_bar.html('Preparando...');
            wrapper.fadeIn();
            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            let percentComplete = Math.floor((e.loaded / e.total) * 100);

                            //Mostramos la barra
                            progress_bar.css("width", percentComplete + "%");
                            progress_bar.html(percentComplete + "%");
                        }
                    }, false);
                    return xhr;
                },
                url: 'index.php?controller=Reportes&action=addVideoManual',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('button', formVideo).attr('disabled', true);
                    $('#btn_submit_video').attr('disabled', true);
                }
            }).done(res => {
                console.log(res);
                if (res.status) {
                    progress_bar.removeClass('bg-info').addClass('bg-success');
                    progress_bar.html('!Listo??');
                    $('#formAddVideoManual').trigger("reset");
                    alertify.success(res.mensaje);

                    setTimeout(() => {
                        wrapper.fadeOut();
                        progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                        progress_bar.css('width', '0%');

                    }, 1500);

                    setTimeout(() => {
                        $('#addVideoModal').modal('hide');
                        location.reload();
                    }, 2000);

                } else {
                    alertify.warning(res.mensaje);
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.mensaje);
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                progress_bar.html('Ha ocurrido un error!');
                alertify.error('Ha ocurrido un error!');
            }).always(() => {
                $('button', formVideo).attr('disabled', false);
                $('#btn_submit_video').attr('disabled', false);
            });

        });

        $('#formFiltersAyuda').submit(function(evento) {
            evento.preventDefault();
            const $inputs = $('#formFiltersAyuda').serializeArray();
            const test = $('#formFiltersAyuda');
            let dataFormToSend = {
                plataforma: '',
                palabrasClave: '',
            };
            $inputs.map( (input) => {
                console.log(input)
                if (input.name === 'movil' || input.name === 'web') {
                    dataFormToSend.plataforma += input.name ? input.name+',' : '';
                } else {
                dataFormToSend[input.name] = input.value ? input.value : '';
                }
            } );
            dataFormToSend.plataforma = dataFormToSend.plataforma.substring(0, dataFormToSend.plataforma.length - 1)
            dataFormToSend['bandera'] = true;

            $.ajax({
                type: 'POST',  // Env??o con m??todo POST
                url: "./index.php?controller=Reportes&action=ayuda",
                data: dataFormToSend, // Datos que se env??an
            }).done(function (msg) {
                const json = JSON.parse(msg);
                console.log(json)
                const newTemplateManuales = json.data.map( (e) => {
                    const urlAssetArray = e.ruta_video.split('.');
                    const currentExtensionFile = urlAssetArray[urlAssetArray.length - 1];
                    const option =
                        (currentExtensionFile === 'mp4' || currentExtensionFile === 'jpeg')
                            ? 'visual' : 'documento';

                    const objectOptions = {
                        visual: generateHtmlVideo(e),
                        documento: generateHtmlDocument(e),
                    };
                    return objectOptions[option];
                });

                function generateHtmlVideo(e) {
                    return `
                        <div class="col-6 mb-3">
                            <div class="card p-3 rounded">
                                <div class="col-12">
                                        <video controls style="height: 200px; width: 100%;">
                                            <source src="videoManuales/${e.ruta_video}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <div class="col-12">
                                            <p class="card-text"><b>T??tulo: </b>${e.titulo}</p>
                                            <p class="card-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="${e.description}">
                                            <b>Descripci??n: </b>
                                             ${e.descripcion.substring(0, 50)}
                                            </p>
                                            <p class="card-text"><b>Clasificaci??n: </b>${e.clasificacion}</p>
                                            <p class="card-text"><b>Versi??n: </b>${e.version}</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    `;
                }

                function generateHtmlDocument(e) {
                    const urlAssetArray = e.ruta_video.split('.');
                    const currentExtensionFile = urlAssetArray[urlAssetArray.length - 1];
                    return `
                        <div class="col-sm-6" style="padding-bottom: 3em;">
                            <div class="card text-center rounded">
                                <div class="row">
                                    <div class="card-body d-flex justify-content-center align-items-center col-8">
                                        <a style="text-decoration: none" href="videoManuales/${e.ruta_video}"
                                        target="_blank">
                                            <p class="card-text"><b>T??tulo: </b>${e.titulo}</p>
                                            <p class="card-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="${e.description}">
                                                <b>Descripci??n: </b>
                                                ${e.descripcion.substring(0, 50)}
                                            </p>
                                            <p class="card-text"><b>Clasificaci??n: </b>${e.clasificacion}</p>
                                            <p class="card-text"><b>Versi??n: </b>${e.version}</p>
                                        </a>
                                    </div>
                                    <div class="col-4 d-flex justify-content-center align-items-center">
                                        <a class="w-100 p-4" href="videoManuales/${e.ruta_video}" target="_blank">
                                            <img src="videoManuales/file-${currentExtensionFile}.svg"
                                            class="text-danger" alt="Imagen Extension">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                }

                const containerManuales = $('#dataFiltered');
                const resultadosContainer = $('#resultadosContainer');
                const mainResultsContainer = $('#mainResultsContainer');

                resultadosContainer.removeClass('hidden');
                resultadosContainer.addClass('show');
                mainResultsContainer.removeClass('show');
                mainResultsContainer.addClass('hidden');

                containerManuales.html(newTemplateManuales);
              }).fail(function (jqXHR, textStatus, errorThrown) { // Funci??n que se ejecuta si algo ha ido mal
                $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
            });
        });
    });

    function changeClassesResults() {
        const resultadosContainer = $('#resultadosContainer');
        const mainResultsContainer = $('#mainResultsContainer');

        resultadosContainer.removeClass('show');
        resultadosContainer.addClass('hidden');
        mainResultsContainer.removeClass('hidden');
        mainResultsContainer.addClass('show');
    }

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
                        <label class="control-label">Descripci??n:</label>
                        <input type="text" name="descripcion_Reporte" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Tipo de registro:</label>
                        <select id="tipo_Reporte" name="tipo_Reporte" class="custom-select"
                                onchange="mostrarInputSeguimiento()">
                            <option value="0">Reporte</option>
                            <option value="1">Incidencia</option>
                            <option value="2">Ubicaci??n</option>
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

                            <?php if (empty($reporteTipoOcho)) { ?>
                                <option value="8"> Documentos Entregables</option>
                            <?php } ?>

                            <?php if (empty($reporteTipoNueve)) { ?>
                                <option value="9"> Minuta</option>
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
                        <label class="control-label" id="labelDuracion" style="display: none;">Duraci??n
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
                        <label class="control-label" id="labelRevision" style="display: none;">Revisi??n
                            (hrs.):</label>
                        <input type="number" name="tiempo_Revision" style="display: none;" id="inputRevision"
                               class="form-control">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="control-label">??rea(s):</label>
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


                    <label class="control-label">Descripci??n:</label>
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


                    <label class="control-label" id="labelDuracionMod">Duraci??n (hrs.):</label>
                    <input type="number" name="tiempo_Reporte" id="inputDuracionMod" class="form-control">


                    <label class="control-label" id="labelAlarmaMod">Alarma (hrs.):</label>
                    <input type="number" name="tiempo_Alarma" id="inputAlarmaMod" class="form-control">


                    <label class="control-label" id="labelRevisionMod">Revisi??n (hrs.):</label>
                    <input type="number" name="tiempo_Revision" id="inputRevisionMod" class="form-control">


                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">??rea(s):</label><br/>

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


<!-- **************** SECCION DE AGREGAR NUEVO VIDEO DE MANUALES *************************** -->
<div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cargar nuevo video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddVideoManual" enctype="multipart/form-data" class="form-group">
                    <div class="row mt-3 mb-4">
                        <div class="col-lg-12">
                            <label for="tituloVideo" class="form-control-label">Titulo</label>
                            <input type="text" name="tituloVideo" id="tituloVideo" class="form-control">
                        </div>
                        <div class="col-lg-12">
                            <label for="descripcion" class="form-control-label">Descripci??n</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" cols="50"></textarea>
                        </div>
                        <div class="col-lg-12">
                            <label for="palabrasClave" class="form-control-label">Palabras Clave</label>
                            <input type="text" name="palabrasClave" id="palabrasClave" class="form-control">
                        </div>
                        <div class="col-lg-12">
                            <label for="clasificacion" class="form-control-label">Clasificaci??n</label>
                            <select name="clasificacion" id="clasificacion" class="form-control">
                                <option value="Administracion">Administraci??n</option>
                                <option value="Usuarios">Usuarios</option>
                                <option value="Fotografias">Cargar Fotograf??as</option>
                                <option value="Configuracion">Configuraci??n</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label for="plataforma" class="form-control-label">Plataforma</label>
                            <select name="plataforma" id="plataforma" class="form-control">
                                <option value="Web">Web</option>
                                <option value="Movil">M??vil</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label for="version" class="form-control-label">Versi??n</label>
                            <input type="text" name="version" id="version" class="form-control">
                        </div>
                        <div class="col-lg-12 mt-1">
                            <label for="Cargar archivo" class="form-control-label">Cargar Archivo</label>
                            <input type="file" name="video_file" id="video_file" class="form-control">
                        </div>

                        <div class="col-lg-12">
                            <div class="wrapper mt-5" style="display: none;">
                                <div class="progress progress-wrapper">
                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress-bar"
                                         role="progressbar" style="width: 80%;">0%
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btn_submit_video">Subir</button>
            </div>
            </form>
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
                           href="#" data-trigger="hover" data-content="Duplicar Configuraci??n de Plantilla"
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
                            <th class="align-middle">Descripci??n del Reporte</th>
                            <th class="align-middle">??reas</th>
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
                        <? if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) { ?>
                            <a class="px-2 m-1 h4 text-white" href="#" data-trigger="hover"
                               data-content="Nueva Manual"
                               data-toggle="modal" data-target="#addVideoModal" data-original-title="" title="">
                                <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        <? } ?>
                    </div>
                </div>

                <div class="card ">

                    <!-- Start Filter-->
                    <div class="col-12 justify-content-center mt-3">
                        <div class="accordion-item">
                            <div class="d-flex justify-content-between filter-bar bg-primary rounded px-3">
                                <h4 class="text-white m-0 py-2">
                                    <i class="fas fa-filter"></i> Filtros
                                </h4>
                                <button
                                        data-toggle="collapse"
                                        data-target=".collapse.filters"
                                        data-text="Collapse"
                                        class="btn btn-link">
                                    <i class="dinamyc-arrow text-white fas fa-arrow-down"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <div class="col-12 collapse filters show">
                                    <form id="formFiltersAyuda" enctype="multipart/form-data" class="form-group">
                                        <div class="row mt-3 mb-4">
                                            <div class="col-3 text-center">
                                                <label for="plataform" class="font-bold">Plataformas</label>
                                                <div class="row">
                                                    <div class="col-6 d-flex justify-content-end align-items-center">
                                                        <input type="checkbox" name="web" id="webPlatforma">
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-start align-items-center">
                                                        <label  for="webPlatforma" class="m-0 mr-4">Web</label>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-6 d-flex justify-content-end align-items-center">
                                                        <input type="checkbox" name="movil" id="movilPlataforma">
                                                    </div>
                                                    <div class="col-6 d-flex justify-content-start align-items-center">
                                                        <label class="m-0 mr-4" for="movilPlataforma">M??vil</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-3">
                                                <label for="clasificacion" class="form-control-label">Clasificaci??n</label>
                                                <br/>
                                                <select name="clasificacion" id="clasificacion" class="form-select">
                                                    <option value="Administracion">Administraci??n</option>
                                                    <option value="Usuarious">Usuarios</option>
                                                    <option value="Fotografias">Cargar Fotograf??as</option>
                                                    <option value="Configuracion">Configuraci??n</option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="palabraClave" class="form-control-label">Palabra clave</label>
                                                <div class="col-12">
                                                    <input type="text" value="" name="palabrasClave" id="palabraClave"
                                                           class="form-control" data-role="tagsinput">
                                                </div>
                                            </div>
                                            <div class="col-3 d-flex justify-content-center align-items-center">
                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        onclick="changeClassesResults()"
                                                        >Limpiar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter-->



                    <!--Main Buttons-->
                    <!--<div class="col-12 d-flex justify-content-center ">
                        <div class="mb-12 row w-100">
                            <div class="col-6 container-manuales">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 200px; height: 200px;"
                                        data-toggle="collapse"
                                        data-target=".collapse.manuales"
                                        data-text="Collapse"
                                    >
                                        <div class="col-12">
                                            <i class="bounce fas fa-book fs-3" style="font-size: 70px;"></i>
                                            <h4 class="mt-1">Manuales</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 container-videos">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="d-flex justify-content-center align-items-center"
                                    style="width: 200px; height: 200px;"
                                    data-toggle="collapse"
                                    data-target=".collapse.videos"
                                    data-text="Collapse"
                                    >
                                        <div class="col-12">
                                            <i class="fas fa-photo-video" style="font-size: 70px;"></i>
                                            <h4 class="mt-1">Videos</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="collapse hidden" id="resultadosContainer">
                        <div class="w-100 d-flex justify-content-center mb-3">
                            <div class="col-sm-10 d-flex justify-content-between bg-primary rounded">
                                <h4 class="text-white m-0 py-2">
                                    <i class="fas fa-poll-h"></i> Resultados</h4>
                            </div>
                        </div>
                        <div class="w-100 d-flex justify-content-center mb-3 ">
                            <div class="col-sm-10 ">
                                <div id="dataFiltered"
                                     class="row justify-content-between rounded">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="mainResultsContainer" class="collapse show">
                        <div class="w-100 d-flex justify-content-center mb-3">
                            <div class="col-sm-10 d-flex justify-content-between bg-primary rounded">
                                <h4 class="text-white m-0 py-2">
                                    <i class="fas fa-book"></i> Manuales de ayuda </h4>
                                <button
                                        data-toggle="collapse"
                                        data-target=".collapse.manuales"
                                        data-text="Collapse"
                                        class="btn btn-link">
                                    <i class=" text-white fas fa-arrow-down"></i>
                                </button>
                            </div>
                        </div>

                        <div class="w-100 d-flex justify-content-center mb-3 ">
                            <div class="col-sm-10 collapse show manuales">
                                <div class="row justify-content-between rounded" id="manuales-container">
                                    <?php
                                    $info = '';
                                    $extension = '';
                                    if (is_array($registros) || is_object($registros)) {
                                        foreach ($registros as $registroVideo) {
                                            $info = new SplFileInfo($registroVideo->ruta_video);
                                            $extension = $info->getExtension();
                                            if ($extension == 'docx' || $extension == 'xlsx' || $extension == 'pdf') {
                                                ?>
                                                <div class="col-sm-6" style="padding-bottom: 3em;">
                                                    <div class="card text-center rounded">
                                                        <div class="row">
                                                            <div class="card-body d-flex justify-content-center align-items-center col-8">
                                                                <a style="text-decoration: none" href="videoManuales/<?php echo $registroVideo->ruta_video; ?>"
                                                                   target="_blank">
                                                                    <p class="card-text"><b>T??tulo: </b><?php echo $registroVideo->titulo; ?></p>
                                                                    <p class="card-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                       title="<?php echo $registroVideo->descripcion; ?>">
                                                                        <b>Descripci??n: </b>
                                                                        <?php echo substr($registroVideo->descripcion, 0, 50); ?>
                                                                    </p>
                                                                    <p class="card-text"><b>Clasificaci??n: </b><?php echo $registroVideo->clasificacion; ?></p>
                                                                    <p class="card-text"><b>Versi??n: </b><?php echo $registroVideo->version; ?></p>
                                                                </a>
                                                            </div>
                                                            <div class="col-4 d-flex justify-content-center align-items-center">
                                                                <a class="w-100 p-4" href="videoManuales/<?php echo $registroVideo->ruta_video; ?>" target="_blank">
                                                                    <img src="videoManuales/file-<?= $extension; ?>.svg"
                                                                         class="text-danger" alt="Imagen Extension">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!--<div class="card-footer">
                                                            <small class="text-muted">Last updated 3 mins ago</small>
                                                        </div>-->
                                                    </div>
                                                </div>

                                            <?php                                        }
                                        }
                                    } ?>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 d-flex justify-content-center mb-3">
                            <div class="col-sm-10 d-flex justify-content-between bg-primary rounded">
                                <h4 class="text-white m-0 py-2">
                                    <i class="fas fa-photo-video"></i> Videos de ayuda </h4>
                                <button
                                        data-toggle="collapse"
                                        data-target=".collapse.videos"
                                        data-text="Collapse"
                                        class="btn btn-link">
                                    <i class=" text-white fas fa-arrow-down"></i>
                                </button>
                            </div>
                        </div>
                        <div class="w-100 d-flex justify-content-center mb-3 ">
                            <div class="col-sm-10 collapse show videos">
                                <div class="row justify-content-between rounded">
                                    <?php
                                    $info = '';
                                    $extension = '';
                                    if (is_array($registros) || is_object($registros)) {
                                        foreach ($registros as $registroVideo) {
                                            $info = new SplFileInfo($registroVideo->ruta_video);
                                            $extension = $info->getExtension();
                                            if ($extension == 'mp4' || $extension == 'mkv' || $extension == 'avi') {
                                                ?>

                                                <div class="col-6 mb-3">
                                                    <div class="card p-3 rounded">
                                                        <!--<iframe src="videoManuales/<?php /*echo $registroVideo->ruta_video; */?>"
                                                        frameborder="0"></iframe>-->
                                                        <div class="col-12">
                                                            <video controls style="height: 200px; width: 100%;">
                                                                <source src="videoManuales/<?php echo $registroVideo->ruta_video; ?>" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-center align-items-center">
                                                            <div class="col-12">
                                                                <p class="card-text"><b>T??tulo: </b><?php echo $registroVideo->titulo; ?></p>
                                                                <p class="card-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="<?php echo $registroVideo->descripcion; ?>">
                                                                    <b>Descripci??n: </b>
                                                                    <?php echo substr($registroVideo->descripcion, 0, 50); ?>
                                                                </p>
                                                                <p class="card-text"><b>Clasificaci??n: </b><?php echo $registroVideo->clasificacion; ?></p>
                                                                <p class="card-text"><b>Versi??n: </b><?php echo $registroVideo->version; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                            }
                                        }
                                    } else { ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="alert alert-warning">NO HAY REGISTROS!</div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--<div class="row pt-3 d-flex justify-content-center">
        <div class="col-12">
            <div class="container">
                <div class="card" style="max-width: 70rem;">
                    <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                        <div class="col-sm-10 d-flex align-items-center">
                            <h4 class="text-white m-0 py-2">
                                <i class="fas fa-book"></i> Manuales de ayuda </h4>
                        </div>
                        <div class="col-sm-2 d-flex justify-content-center align-items-center">
                            <? /* if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) { */ ?>
                                <a class="px-2 m-1 h4 text-white" href="#" data-trigger="hover"
                                   data-content="Nueva Manual"
                                   data-toggle="modal" data-target="#addVideoModal" data-original-title="" title="">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                            <? /* } */ ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <?php
    /*                            $info = '';
                                $extension = '';
                                if (is_array($registros) || is_object($registros)) {
                                    foreach ($registros as $registroVideo) {
                                        $info = new SplFileInfo($registroVideo->ruta_video);
                                        $extension = $info->getExtension();
                                        if ($extension == 'docx' || $extension == 'xlsx' || $extension == 'pdf') {
                                            */ ?>
                                        <div class="col-md-2 col-sm-4 col-6 mt-2">
                                            <div class="card">
                                                <div class="container mt-1 text-danger">
                                                    <a href="videoManuales/<?php /*echo $registroVideo->ruta_video; */ ?>"
                                                       target="_blank">
                                                        <img src="videoManuales/file-<? /*= $extension; */ ?>.svg"
                                                             class="text-danger"
                                                             alt="Imagen Extension"
                                                             style="max-width: 100%; height: auto; display: table-cell;">
                                                    </a>

                                                </div>
                                                <div class="card-body">
                                                    <a href="videoManuales/<?php /*echo $registroVideo->ruta_video; */ ?>"
                                                       target="_blank"><p
                                                                class="card-text"><?php /*echo $registroVideo->titulo; */ ?></p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
    /*                                    }
                                    }
                                } */ ?>


                        </div>

                    </div>
                </div>


                <hr class="linea-separadora">
                <div class="card" style="max-width: 70rem;">
                    <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                        <div class="col-sm-10 d-flex align-items-center">
                            <h4 class="text-white m-0 py-2">
                                <i class="fas fa-photo-video"></i> Videos de ayuda </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row ">

                            <?php
    /*                            $info = '';
                                $extension = '';
                                if (is_array($registros) || is_object($registros)) {
                                    foreach ($registros as $registroVideo) {
                                        $info = new SplFileInfo($registroVideo->ruta_video);
                                        $extension = $info->getExtension();
                                        if ($extension == 'mp4' || $extension == 'mkv' || $extension == 'avi') {
                                            */ ?>

                                        <div class="col-sm-4 mt-2">
                                            <div class="card">
                                                <iframe src="videoManuales/<?php /*echo $registroVideo->ruta_video; */ ?>"
                                                        frameborder="0"></iframe>
                                                <div class="card-body">
                                                    <p class="card-text"><?php /*echo $registroVideo->titulo; */ ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
    /*                                    }
                                    }
                                } else { */ ?>
                                <div class="alert alert-warning">NO HAY REGISTROS!</div>
                            <?php /*} */ ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>-->
    <?php } ?>



