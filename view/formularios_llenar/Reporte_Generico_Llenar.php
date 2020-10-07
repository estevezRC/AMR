<script type="application/javascript">
    let date = new Date(), hora = date.getHours(), minutos = date.getMinutes(), segundos = date.getSeconds();

    if (hora < 10) hora = '0' + hora;
    if (minutos < 10) minutos = '0' + minutos;
    if (segundos < 10) segundos = '0' + segundos;

    window.onload = function () {
        document.getElementById('hora').value = hora + ':' + minutos + ':' + segundos;
        try {
            document.getElementById('labelHora').textContent = `${hora}:${minutos}:${segundos}`;
        } catch (e) {

        }
    }
</script>

<?php
$total_cantidad_solicitadas = 0;
$sizecol = "col-sm-12 col-md-6";
foreach ($allcamposreportes as $camporeporte) {
    if ($camporeporte->tipo_Reactivo_Campo == "file") {
        $cantidad_solicitadas = explode("/", $camporeporte->Valor_Default);
        $total_cantidad_solicitadas = count($cantidad_solicitadas);
    }
}
if ($total_cantidad_solicitadas == 0) {
    $sizecol = "col-12";
} ?>


<script>

    function validar(elem) {
        var currentId = $(elem).attr("id");
        var idcoment = "descFoto" + currentId;
        document.getElementById(idcoment).required = true;
    }

    function cambiarNombreTitulo() {
        let organizacion = $('#Organización').val();
        let nombreProyecto = $('#Nombre_proyecto').val();
        let nombreCompuesto;
        if (organizacion.length == 0) {
            nombreCompuesto = nombreProyecto
        } else {
            let volumen_Sistema = $('#Volumen-Sistema').val();
            let Niveles_Ubicaciones = $('#Niveles-Ubicaciones').val();
            let TipoPlano = $('#Tipo_de_Documento').val();
            let rol = $('#Rol').val();
            let clasificacion = $('#Clasificación-Documento').val();
            let numero = $('#Número-Documento').val();
            if (clasificacion.length == 0)
                nombreCompuesto = nombreProyecto + '_' + organizacion + '_' + volumen_Sistema + '_' + Niveles_Ubicaciones + '_' + TipoPlano + '_' + rol + '_000' + numero;
            else
                nombreCompuesto = nombreProyecto + '_' + organizacion + '_' + volumen_Sistema + '_' + Niveles_Ubicaciones + '_' + TipoPlano + '_' + rol + '_' + clasificacion + '_000' + numero;
        }
        $('#titulo_Reporte').val(nombreCompuesto);
        $('#titulo_Reporte2').text(nombreCompuesto);
    }

    $(document).ready(function () {
        let id_Reporte = <?php echo $allcamposreportes[0]->id_Reporte ?>;

        var sistemas = <?php echo json_encode($allsistemas); ?>;
        var fruits = ["Banana", "Orange", "Apple", "Mango"];
        $("#first-choice").change(function () {
            var $dropdown = $(this);
            var key = $dropdown.val();
            var $secondChoice = $("#second-choice");
            var x = 0;
            $secondChoice.empty();

            if (key == 0) {
                $secondChoice.append("<option name='0' id='0' value='0'>Sin Elemento</option>");
            } else {
                $.each(sistemas, function () {
                    if (key == sistemas[x]["id_Reporte"]) {
                        $secondChoice.append("<option name='" + sistemas[x]["id_Gpo_Valores_Reporte"] + "' id='" + sistemas[x]["id_Gpo_Valores_Reporte"] + "' value='" + sistemas[x]["id_Gpo_Valores_Reporte"] + "'>" + sistemas[x]["titulo_Reporte"] + "</option>");
                    }
                    x++;
                });
            }
        });
    });

</script>


<div class="row justify-content-center p-1">
    <div class="<?php echo $sizecol ?>">

        <!---------------------------------******** DATOS GENERICOS *********------------------------------------------>
        <div class="form-group">
            <label for=""><?php echo $this->nombreReporteId($allcamposreportes[0]->tipo_Reporte, 2) ?></label>

            <?php
            if ($allcamposreportes[0]->tipo_Reporte == 5) {
                if (empty($titulo_ReportePlanos)) { ?>
                    <label class="form-control labelPerfil" id="titulo_Reporte2"> </label>
                    <input type="text" name="titulo_Reporte" id="titulo_Reporte" hidden>
                <?php } else { ?>
                    <label class="form-control labelPerfil"
                           id="titulo_Reporte2"> <?php echo $titulo_ReportePlanos; ?> </label>
                    <input type="text" name="titulo_Reporte" id="titulo_Reporte"
                           value="<?php echo $titulo_ReportePlanos; ?>" hidden>

                <?php }
            } elseif ($allcamposreportes[0]->tipo_Reporte == 6) { ?>
                <label class="form-control labelPerfil"
                       id="titulo_Reporte2"> Asistencia <?php $date = new DateTime();
                    echo $date->format('d-m-Y') ?>
                </label>
                <input type="text" name="titulo_Reporte" id="titulo_Reporte"
                       value="Asistencia <?php $date = new DateTime();
                       echo $date->format('d-m-Y') ?>" hidden>
            <? } else { ?>
                <input type="text" name="titulo_Reporte" id="titulo_Reporte" class="form-control"
                       placeholder="Título" required>
            <?php } ?>
        </div>


        <?php if ($allcamposreportes[0]->tipo_Reporte != 5) { ?>

            <div class="form-group">
                <!-- Datos Id_Padre de Reporte de Incidencia -->
                <?php
                if ($idReportePadreVincular != 0 && $idGpoValoresPadreVincular != 0) { ?>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label for="first-choice"> Plantilla(s) </label>
                            <p class="border p-1"> <?php echo $nombre_ReportePadreVincular; ?> </p>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label for="first-choice"> Registro(s) </label>
                            <p class="border p-1"> <?php echo $titulo_ReportePadreVincular; ?> </p>
                            <input name="id_Gpo_Padre" value="<?php echo $idGpoValoresPadreVincular; ?>" hidden>
                        </div>
                    </div>

                <?php } else {
                    if ($allcamposreportes[0]->tipo_Reporte != 4) { ?>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <label for="first-choice"> Plantilla(s) </label>
                                <select name="gruposubicaciones" id="first-choice" class="form-control">
                                    <option name="0" id="0" value="0">Sin grupo</option>

                                    <!--<optgroup label="Reportes de Actividades" id="id_reporteLlenar1"></optgroup>
                                    <optgroup label="Otros Reportes" id="id_reporteLlenar2"></optgroup>-->


                                    <?php foreach ($gruposubicaciones as $grupo) { ?>
                                        <option name="<?php echo $grupo->id_Reporte; ?>"
                                                id="<?php echo $grupo->id_Reporte; ?>"
                                                value="<?php echo $grupo->id_Reporte; ?>"><?php echo $grupo->nombre_Reporte; ?>
                                        </option>
                                    <?php } ?>


                                </select>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <label for="first-choice"> Registro(s) </label>
                                <select name="id_Gpo_Padre" id="second-choice" class="form-control">
                                    <option name="0" id="0" value="0">Sin elemento</option>
                                </select>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="form-group">
                            <label for=""> Tipo de Incidencia </label>
                            <label
                                class="form-control labelPerfil"> <?php echo $datosReporte[0]->campo_Tipo_Incidente; ?>
                                / <?php echo $datosReporte[0]->campo_Fecha; ?> </label>
                        </div>
                        <input type="hidden" name="id_Gpo_Padre"
                               value="<?php echo $datosReporte[0]->id_Gpo_Valores_Reporte; ?>">
                    <?php }
                } ?>
            </div>

            <?php if ($allcamposreportes[0]->tipo_Reporte == 2) { ?>
                <div class="form-group">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="coordenadas"><i class="fa fa-map-marker" onclick="getLocationm()"></i>
                                Coordenadas
                            </label>
                        </div>

                        <div class="row" id="coordenadas">
                            <div class="col">
                                <label for="latitudm">Latitud</label>
                                <input type="text" name="latitud" id="latitudm" value="0" class="form-control">
                            </div>
                            <div class="col">
                                <label for="longitudm">Longitud</label>
                                <input type="text" name="longitud" id="longitudm" value="0" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>

                <?php
            } else { ?>
                <div class="form-group" hidden>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="coordenadas"><i class="fa fa-map-marker" onclick="getLocationm()"></i>
                                Coordenadas
                            </label>
                        </div>

                        <div class="row" id="coordenadas">
                            <div class="col">
                                <label for="latitudm">Latitud</label>
                                <input type="text" name="latitud" id="latitudm" value="0" class="form-control">
                            </div>
                            <div class="col">
                                <label for="longitudm">Longitud</label>
                                <input type="text" name="longitud" id="longitudm" value="0" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>

                <?php
            }
        }
        /*-------------------------------- ACCION MOSTAR REPORTE: MUESTRA EL REPORTE SELECCIONADO ----------------------------*/
        //$x = count($allreportellenado);
        $x = 0;
        $num_ubicacion = 1;

        foreach ($allcamposreportes as $reporte) {
            $i = $reporte->tipo_Reactivo_Campo;
            $isRequired = "";
            if ($reporte->Campo_Necesario == 1) {
                $isRequired = "required";
            }

            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: TEXTO :::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "text") { ?>
                <div class="form-group">
                    <?php if ($reporte->nombre_Campo == "Identificador del dispositivo") {
                        $valor_default = $allcamposreportes["Id_Sistema_Llenado"] .
                            $valor_default = $allreportellenado[$x]->valor_Texto_Reporte . $allreportellenado[$x]->valor_Entero_Reporte;
                    } else {
                        $valor_default = $valor_default = $allreportellenado[$x]->valor_Texto_Reporte .
                            $allreportellenado[$x]->valor_Entero_Reporte;
                    } ?>
                    <label for="<?php echo $valor_default ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 5) {
                        if (empty($titulo_ReportePlanos)) {
                            if ($reporte->descripcion_Campo == 'Clasificación-Documento') { ?>
                                <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" minlength="8" maxlength="8"
                                       name="<?php echo $reporte->descripcion_Campo; ?>"
                                       id="<?php echo $reporte->descripcion_Campo ?>"
                                       placeholder="Formato requerido: 30_10_30" value="<?php echo $valor_default ?>"
                                       class="form-control" <?php echo $isRequired ?>
                                       onkeyup="cambiarNombreTitulo()">
                            <?php } else { ?>
                                <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" minlength="3" maxlength="6"
                                       name="<?php echo $reporte->descripcion_Campo; ?>"
                                       id="<?php echo $reporte->descripcion_Campo ?>"
                                       value="<?php echo $valor_default ?>"
                                       class="form-control" <?php echo $isRequired ?>
                                       onkeyup="cambiarNombreTitulo()">
                            <?php }
                        } else {
                            if ($reporte->descripcion_Campo == 'Clasificación-Documento') { ?>
                                <label class="form-control"> <?php echo $allcamposreportes[8]->Valor_Default ?> </label>
                                <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" minlength="8" maxlength="8"
                                       name="<?php echo $reporte->descripcion_Campo; ?>"
                                       id="<?php echo $reporte->descripcion_Campo ?>"
                                       placeholder="Formato requerido: 30_10_30"
                                       value="<?php echo $allcamposreportes[8]->Valor_Default ?>" <?php echo $isRequired ?>
                                       hidden>
                            <?php } else if ($reporte->descripcion_Campo == 'Organización') { ?>
                                <label class="form-control"> <?php echo $allcamposreportes[3]->Valor_Default ?> </label>
                                <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" minlength="3"
                                       maxlength="6" name="<?php echo $reporte->descripcion_Campo; ?>"
                                       id="<?php echo $reporte->descripcion_Campo ?>"
                                       value="<?php echo $allcamposreportes[3]->Valor_Default ?>" <?php echo $isRequired ?>
                                       hidden>
                            <?php } else { ?>
                                <label class="form-control"> <?php echo $allcamposreportes[2]->Valor_Default ?> </label>
                                <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" minlength="3"
                                       maxlength="6" name="<?php echo $reporte->descripcion_Campo; ?>"
                                       id="<?php echo $reporte->descripcion_Campo ?>"
                                       value="<?php echo $allcamposreportes[2]->Valor_Default ?>"<?php echo $isRequired ?>
                                       hidden>
                            <?php }
                        }
                    } else { ?>
                        <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>"
                               name="<?php echo $reporte->descripcion_Campo; ?>" id="<?php echo $valor_default ?>"
                               value="<?php echo $valor_default ?>" class="form-control" <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }


            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: TEXTAREA ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "textarea") { ?>
                <div class="form-group">
                    <label for="<?php echo $reporte->nombre_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <textarea id="<?php echo $reporte->nombre_Campo ?>" name="<?php echo $reporte->nombre_Campo ?>"
                              style="background-color: #f0f0f0; height: 150px; resize: none;"
                              class="form-control" <?php echo $isRequired ?>></textarea>
                </div>
            <?php }

            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: UNA OPCION ::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "radio") {
                $opciones = explode("/", $reporte->Valor_Default);
                $valor_checado = $allreportellenado[$x]->valor_Texto_Reporte; ?>
                <div class="form-group">
                    <label for=""> <?php echo $reporte->nombre_Campo ?> </label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php foreach ($opciones as $opcion) {
                            if ($opcion == $valor_checado) {
                                $checado = "checked";
                            } else if ($opcion != $valor_checado) {
                                $checado = "";
                            } ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="<?php echo $reporte->descripcion_Campo; ?>"
                                           value="<?php echo $opcion; ?>"
                                           class="radio i-checks" <?php echo $checado; ?> <?php echo $isRequired ?>>
                                    <?php echo $opcion; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }

            /* ::::::::::::::::::::::::::::::::::::::::::::::::: CHECK LIST ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "checkbox") { ?>
                <div class="form-group">
                    <label id="labelCheckbox"><?php echo $reporte->nombre_Campo ?></label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php $opciones = explode("/", $reporte->Valor_Default);
                        $valores = explode("/", $allreportellenado[$x]->valor_Texto_Reporte);
                        foreach ($opciones as $opcion) {
                            $checado = "";
                            foreach ($valores as $valor_checado) {
                                if ($opcion == $valor_checado) {
                                    $checado = "checked";
                                }
                            } ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="<?php echo $reporte->descripcion_Campo; ?>[]"
                                           value="<?php echo $opcion; ?>"
                                           class="radio i-checks" <?php echo $checado; ?> >
                                    <?php echo $opcion; ?>
                                </label>
                                <input type="hidden" id="validarCheckbox" value="<?php echo $isRequired ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }

            /* :::::::::::::::::::::::::::::::::::::::::::::::::::NUMERO::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "number") { ?>
                <div class="form-group">
                    <label
                        for="<?php echo $reporte->descripcion_Campo; ?>"> <?php echo $reporte->nombre_Campo; ?> </label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 5) {
                        if (empty($titulo_ReportePlanos)) { ?>
                            <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" min="1" max="1"
                                   name="<?php echo $reporte->descripcion_Campo; ?>"
                                   id="<?php echo $reporte->descripcion_Campo; ?>" class="form-control"
                                   value="1"
                                <?php echo $isRequired ?>>
                            <?php
                        } else { ?>
                            <label class="form-control"> <?php echo $allcamposreportes[9]->Valor_Default ?> </label>

                            <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>"
                                   min="<?php echo $allcamposreportes[9]->Valor_Default ?>"
                                   max="<?php echo $allcamposreportes[9]->Valor_Default ?>"
                                   name="<?php echo $reporte->descripcion_Campo; ?>"
                                   id="<?php echo $reporte->descripcion_Campo; ?>"
                                   value="<?php echo $allcamposreportes[9]->Valor_Default ?>"
                                <?php echo $isRequired ?> hidden>
                        <?php }
                    } else { ?>
                        <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>" min="0"
                               name="<?php echo $reporte->descripcion_Campo; ?>"
                               id="<?php echo $reporte->descripcion_Campo; ?>" class="form-control"
                               value="<?php echo $allreportellenado[$x]->valor_Texto_Reporte . $allreportellenado[$x]->valor_Entero_Reporte; ?>"
                            <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }


            /* ::::::::::::::::::::::::::::::::::: NUMEROS DECIMALES :::::::::::::::::::::::::::::::::::::::::::::::: */
            if ($reporte->tipo_Reactivo_Campo == "decimal") { ?>
                <div class="form-group">
                    <label
                        for="<?php echo $reporte->descripcion_Campo; ?>"> <?php echo $reporte->nombre_Campo; ?> </label>
                    <input type="" min="0" step="0.01"
                           name="<?php echo $reporte->descripcion_Campo; ?>"
                           id="<?php echo $reporte->descripcion_Campo; ?>" class="form-control"
                        <?php echo $isRequired ?>>
                </div>
            <?php }


            /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::FECHA:::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "date") {
                $date = new DateTime($allreportellenado[$x]->valor_Texto_Reporte); ?>
                <div class="form-group">
                    <label for="<?php echo $reporte->descripcion_Campo; ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 6) { ?>
                        <label class="form-control" disabled> <?php echo $date->format('Y-m-d') ?> </label>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo; ?>"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?> hidden>
                    <?php } else { ?>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo; ?>"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }

            /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::HORA:::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "time") { ?>
                <div class="form-group">
                    <label for="hora"><?php echo $reporte->nombre_Campo ?></label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 6) { ?>
                        <label class="form-control" id="labelHora" disabled> </label>
                        <input type="time" placeholder="hh:mm:ss" onblur="validaNumericos()" step="1"
                               name="<?php echo $reporte->descripcion_Campo ?>"
                               id="hora" class="form-control labelPerfil" <?php echo $isRequired ?> hidden>
                    <?php } else { ?>
                        <input type="time" placeholder="hh:mm:ss" onblur="validaNumericos()" step="1"
                               name="<?php echo $reporte->descripcion_Campo ?>"
                               id="hora" class="form-control labelPerfil" <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }

            /*::::::::::::::::::::::::::::::::::::::::::::::::CHECK INCIDENCIA:::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "checkbox-incidencia") { ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <div class="form-group text-center">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="<?php echo $reporte->descripcion_Campo ?>"
                                               value="Sí" <?php echo $isRequired ?>>
                                        Sí
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="<?php echo $reporte->descripcion_Campo ?>"
                                               value="No" <?php echo $isRequired ?>>
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }

            /*:::::::::::::::::::::::::::::::::::::::::::::::SELECT STATUS:::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select-status") {
                $opciones = explode("/", $reporte->Valor_Default); ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <select name="<?php echo $reporte->descripcion_Campo ?>"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="custom-select" <?php echo $isRequired ?>>
                        <?php echo $allreportellenado[$x]->valor_Texto_Reporte;
                        foreach ($opciones as $opcion) { ?>
                            <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php
            }

            /*:::::::::::::::::::::::::::::::::::::::::::::::SELECT TABLAS :::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select-tabla") { ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <select name="<?php echo $reporte->descripcion_Campo ?>"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="custom-select" <?php echo $isRequired ?>>
                        <?php foreach ($allRegistrosTablas as $opcion) { ?>
                            <option value="<?php echo $opcion->id; ?>">
                                <?php echo $opcion->nombre; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <?php
            }

            /*:::::::::::::::::::::::::::::::::::::::::::::: SELECT ::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select") {
                $opciones = explode("/", $reporte->Valor_Default); ?>
                <div class="form-group">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>

                    <?php if ($allcamposreportes[0]->tipo_Reporte == 5) { ?>
                        <select name="<?php echo $reporte->descripcion_Campo ?>"
                                id="<?php echo $reporte->descripcion_Campo ?>"
                                class="custom-select" <?php echo $isRequired ?>
                                onchange="cambiarNombreTitulo()">

                            <?php
                            //echo $allreportellenado[$x]->valor_Texto_Reporte;
                            foreach ($opciones as $opcion) { ?>
                                <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                            <?php } ?>
                        </select>

                    <?php } else { ?>
                        <select name="<?php echo $reporte->descripcion_Campo ?>"
                                id="<?php echo $reporte->descripcion_Campo ?>"
                                class="custom-select" <?php echo $isRequired ?>>
                            <?php
                            //echo $allreportellenado[$x]->valor_Texto_Reporte;
                            foreach ($opciones as $opcion) { ?>
                                <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>


                </div>
                <?php
            }


            /* :::::::::::::::::::::::::::::::::::::::::::TEXT CADENAMIENTO:::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "text-cadenamiento") { ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <div class="input-group">
                        <input type="number" placeholder="Km" name="<?php echo $reporte->descripcion_Campo . "1"; ?>"
                               class="form-control text-center" <?php echo $isRequired ?>>
                        <span class="input-group-addon" id="cadenamiento"><i class="fa fa-plus"></i></span>
                        <input type="number" placeholder="m" name="<?php echo $reporte->descripcion_Campo . "2"; ?>"
                               class="form-control text-center" <?php echo $isRequired ?>>
                    </div>
                </div>
                <?php
            }

            /* ::::::::::::::::::::::::::::::::::::::::::: CHECK LIST ASISTENCIA :::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "check_list_asistencia") { ?>
                <div class="form-group">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <small class="text-danger"> *Puedes realizar busqueda de empleados</small>
                    <select name="<?php echo $reporte->descripcion_Campo ?>[]"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="select-asistencia" multiple="multiple"
                            data-placeholder="Selecciona uno o varios" <?php echo $isRequired ?>>
                        <?php foreach ($datosIdAndName as $dato) { ?>
                            <option value="<?= $dato->id; ?>">
                                <?= $dato->nombre; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            <?php }

            /* ::::::::::::::::::::::::::::::::::::::::::: RANGO DE FECHAS :::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "rango_fechas") { ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">De</span>
                        </div>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>1"
                               id="fecha_inicial"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Hasta</span>
                        </div>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>2"
                               id="fecha_final"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                    </div>
                    <span>
                        <small class="text-danger invisible" id="container_alert"></small>
                    </span>
                </div>
                <?php
            }

            /*:::::::::::::::::::::::::::::::::::::::: CAMPO MULTIPLE ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "multiple") { ?>
                <div class="accordion mb-3" id="multiple">
                    <div class="d-flex justify-content-end py-1">
                        <a href="#" class="text-primary btn-plus mr-2"
                           data-toggle="tooltip" data-placement="top"
                           title="Agregar <?= strtolower($reporte->nombre_Campo) ?>">
                            <span><i class="fa fa-plus"></i></span></a>
                        <a href="#" class="text-primary btn-minus"
                           data-toggle="tooltip" data-placement="top"
                           title="Eliminar último(a) <?= strtolower($reporte->nombre_Campo) ?>">
                            <span><i class="fa fa-minus"></i></span></a>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading_1">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                        data-target="#collapse_1" aria-expanded="true" aria-controls="collapse_1">
                                    <?= $reporte->nombre_Campo ?> #1
                                </button>
                            </h2>
                        </div>

                        <span class="d-none" id="json_campos"
                              data-campo='<?= json_encode($reporte) ?>'></span>

                        <div id="collapse_1" class="collapse show" aria-labelledby="heading_1"
                             data-parent="#multiple">
                            <div class="card-body">
                                <? foreach ($reporte->Valor_Default as $subCampo) {
                                    $subCampo->descripcion_Campo = strtolower($subCampo->descripcion_Campo);
                                    if ($subCampo->tipo_Reactivo_Campo === "select") { ?>
                                        <div class="form-group">
                                            <label for="<?= $subCampo->descripcion_Campo ?>">
                                                <?= $subCampo->nombre_Campo ?>
                                            </label>
                                            <select id="<?= $subCampo->descripcion_Campo ?>"
                                                    class="custom-select <?= $subCampo->descripcion_Campo ?>">
                                                <? foreach (explode("/", $subCampo->Valor_Default) as $valor) { ?>
                                                    <option value="<?= $valor ?>"><?= $valor ?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    <? } elseif ($subCampo->tipo_Reactivo_Campo === "text-cadenamiento") { ?>
                                        <div class="form-group">
                                            <label><?= $subCampo->nombre_Campo ?></label>
                                            <div class="input-group">
                                                <input type="number" placeholder="Km"
                                                       class="form-control text-center <?= $subCampo->descripcion_Campo ?>-inicio"
                                                    <?= $isRequired ?>>
                                                <div class="input-group-append">
                                                     <span class="input-group-text"
                                                           id="cadenamiento"><i class="fa fa-plus"></i></span>
                                                </div>
                                                <input type="number" placeholder="m"
                                                       class="form-control text-center <?= $subCampo->descripcion_Campo ?>-fin"
                                                    <?= $isRequired ?>>
                                            </div>
                                        </div>
                                    <? } elseif ($subCampo->tipo_Reactivo_Campo === "select-tabla") { ?>
                                        <div class="form-group">
                                            <label for="<?= $subCampo->descripcion_Campo ?>">
                                                <?= $subCampo->nombre_Campo ?>
                                            </label>
                                            <select id="<?= $subCampo->descripcion_Campo ?>"
                                                    class="custom-select <?= $subCampo->descripcion_Campo ?>">
                                                <? foreach ($subCampo->Valor_Default as $valor) { ?>
                                                    <option value="<?= $valor->id ?>"><?= $valor->nombre ?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    <? } elseif ($subCampo->tipo_Reactivo_Campo === "date") {
                                        $date = new DateTime(); ?>
                                        <div class="form-group">
                                            <label for="<?= $subCampo->descripcion_Campo; ?>">
                                                <?= $subCampo->nombre_Campo ?>
                                            </label>
                                            <input type="date" id="<?= $subCampo->descripcion_Campo; ?>"
                                                   value="<?= $date->format('Y-m-d') ?>"
                                                   class="form-control <?= $subCampo->descripcion_Campo ?>"
                                                <?= $isRequired ?>>
                                        </div>
                                    <? } elseif ($subCampo->tipo_Reactivo_Campo === "textarea") { ?>
                                        <div class="form-group">
                                            <label
                                                for="<?= $subCampo->descripcion_Campo ?>"><?= $subCampo->nombre_Campo ?></label>
                                            <textarea id="<?= $subCampo->descripcion_Campo ?>"
                                                      style="height: 150px; resize: none;"
                                                      class="form-control <?= $subCampo->descripcion_Campo ?>"
                                                <?= $isRequired ?>></textarea>
                                        </div>
                                    <? }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="campo_multiple" value='' name="<?= $reporte->descripcion_Campo ?>">
            <? }

            $x++;
        } ?>
    </div>

    <?php
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::IMAGENES:::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    $opcion2 = 1;
    if (is_array($cantidad_solicitadas) || is_object($cantidad_solicitadas)) { ?>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <?php foreach ($cantidad_solicitadas as $cantidad) {
                $descripcion = "desc" . $cantidad; ?>

                <div class="col-sm-12 col-md-12 col-lg-12 container-file">
                    <div class="col-sm-12 col-md-12 col-lg-12" id="contencabezado"
                         style="margin-bottom: 10px; float: unset">
                        <div class="encabezado">Subir Archivo</div>
                    </div>

                    <div class="fallback">
                        <div class="form-group">
                            <div class="file-loading">
                                <input type="file" name="<?php echo $cantidad; ?>"
                                       id="<?php echo $cantidad; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="<?php echo "clasFoto" . $opcion2; ?>">Clasificación </label>
                        <select id="<?php echo "clasFoto" . $opcion2; ?>" name="<?php echo "clasFoto" . $opcion2; ?>"
                                class="form-control">
                            <?php foreach ($clasificacion as $clas) {
                                //if ($clas->id_Clasificacion != 1) { ?>
                                <option name="<?php echo $clas->nombre_Clasificacion; ?>"
                                        id="<?php echo $clas->id_Clasificacion; ?>"
                                        value="<?php echo $clas->id_Clasificacion; ?>"><?php echo $clas->nombre_Clasificacion; ?></option>
                                <?php //} else { ?>
                                <!-- <option disabled selected name="<?php echo $clas->nombre_Clasificacion; ?>" id="<?php echo $clas->id_Clasificacion; ?>"
                                    value="<?php echo $clas->id_Clasificacion; ?>">Introduce Descripción</option> -->
                                <?php //}
                            } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="<?php echo "descFoto" . $cantidad; ?>">Observaciones </label>
                        <textarea name="<?php echo "descFoto" . $opcion2; ?>" id="<?php echo "descFoto" . $cantidad; ?>"
                                  class="form-control"></textarea>
                    </div>
                    <!--                <div class="form-group">-->
                    <!--                    <label>Orientación </label>-->
                    <!--                    <select name="-->
                    <?php //echo "oriFoto" . $opcion2; ?><!--" class="form-control">-->
                    <!--                        <option value="1">Horizontal</option>-->
                    <!--                        <option value="0">Vertical</option>-->
                    <!--                    </select>-->
                    <!--                </div>-->

                </div>

                <?php $opcion2++;
            } ?>
        </div>
    <?php } ?>
</div>
