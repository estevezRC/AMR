<?php $total_cantidad_solicitadas = 0;
$sizecol = "col-sm-12 col-md-6";
foreach ($allcamposreportes as $camporeporte) {
    if ($camporeporte->tipo_Reactivo_Campo == "file") {
        $cantidad_solicitadas = explode("/", $camporeporte->Valor_Default);
        $total_cantidad_solicitadas = count($cantidad_solicitadas);
    }
}
if ($total_cantidad_solicitadas == 0) {
    $sizecol = "col-12";
}
?>
<div class="row justify-content-center p-1">
    <div class="<?= $sizecol ?>">
        <!----------------------------------------------DATOS GENERICOS ----------------------------------------------->
        <div class="form-group" id="title_field">
            <label for="titulo_Reporte"><?= $this->nombreReporteId($allcamposreportes[0]->tipo_Reporte, 2) ?></label>
            <input type="text" name="titulo_Reporte" id="titulo_Reporte"
                   value="<?php echo htmlentities($allreportellenado[0]->titulo_Reporte); ?>"
                   class="form-control" placeholder="Título" required>
        </div>

        <input type="hidden" name="id_Reporte_NEW" id="id_Reporte_NEW"
               value="<?php echo $allreportellenado[0]->id_Reporte ?>"
               class="form-control">
        <input type="hidden" name="id_Gpo_Valores_Reporte_NEW"
               value="<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte ?>" class="form-control">

        <? if ($allcamposreportes[0]->tipo_Reporte != 5) {
            if ($allcamposreportes[0]->tipo_Reporte != 4) { ?>
                <div class="form-group">
                    <p class="d-flex justify-content-between">
                        <label for="first-choice"><?= $existeGantt && ($allcamposreportes[0]->tipo_Reporte == 0 || $allcamposreportes[0]->tipo_Reporte == 1) ? 'Vinculado a:' : 'Elementos' ?></label>
                        <? if ($existeGantt && ($allcamposreportes[0]->tipo_Reporte == 0 || $allcamposreportes[0]->tipo_Reporte == 1)) { ?>
                            <button type="button" class="btn btn-sm btn-link" data-toggle="modal"
                                    data-target="#myModalGantt" id="btnCambiarVinculo">Cambiar vínculo
                            </button>
                        <? } ?>
                    </p>

                    <? if (!$existeGantt || ($allcamposreportes[0]->tipo_Reporte != 0 && $allcamposreportes[0]->tipo_Reporte != 1)) { ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <select name="gruposubicaciones" id="first-choice" class="custom-select">
                                    <option name="0" id="0" value="0">Sin grupo</option>
                                    <?php foreach ($gruposubicaciones as $grupo) {
                                        if ($grupo->id_Reporte == $allreportellenado[0]->id_Reporte_Padre) { ?>
                                            <option name="<?php echo $grupo->id_Reporte; ?>"
                                                    id="<?php echo $grupo->id_Reporte; ?>"
                                                    value="<?php echo $grupo->id_Reporte; ?>" selected>
                                                <?php echo $grupo->nombre_Reporte; ?>
                                            </option>
                                        <?php } else { ?>
                                            <option name="<?php echo $grupo->id_Reporte; ?>"
                                                    id="<?php echo $grupo->id_Reporte; ?>"
                                                    value="<?php echo $grupo->id_Reporte; ?>">
                                                <?php echo $grupo->nombre_Reporte; ?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <select name="id_Gpo_Padre" id="second-choice" class="custom-select"> </select>
                            </div>
                        </div>
                    <? } else {
                        foreach ($gruposubicaciones as $grupo) {
                            if ($grupo->id_Reporte == $allreportellenado[0]->id_Reporte_Padre) $grupoReporte = $grupo;
                        }

                        if (isset($grupoReporte)) { ?>
                            <input type="hidden" name="first-choice" id="first-choice"
                                   value="<?= $grupoReporte->id_Reporte ?>">
                        <? }
                        ?>

                        <p class="m-0" id="parReportePadre">Sin Reporte Vinculado</p>
                        <input type="hidden" name="id_Gpo_Padre" id="inputGpoPadre" value="">
                    <? } ?>
                </div>

            <? } else { ?>
                <div class="form-group">
                    <label for=""> Tipo de Incidencia </label>
                    <label class="form-control labelPerfil"><?php echo $datosReporte[0]->campo_Tipo_Incidente; ?>
                        / <?php echo $datosReporte[0]->campo_Fecha; ?></label>
                </div>
                <input type="hidden" name="id_Gpo_Padre"
                       value="<?php echo $datosReporte[0]->id_Gpo_Valores_Reporte; ?>">
            <?php }
        } ?>

        <?php if ($allcamposreportes[0]->tipo_Reporte == 2) { ?>
            <div class="form-group" id="coordenadas_field">
                <div class="form-group">
                    <div class="form-group">
                        <label for="coordenadas"><i class="fa fa-map-marker" onclick="getLocationm()"></i>
                            Coordenadas
                        </label>
                    </div>

                    <div class="row" id="coordenadas">
                        <div class="col">
                            <label for="latitudm">Latitud</label>
                            <input type="text" name="latitud" id="latitudm"
                                   value="<?php echo $allreportellenado[0]->latitud; ?>" class="form-control">
                        </div>

                        <div class="col">
                            <label for="longitudm">Longitud</label>
                            <input type="text" name="longitud" id="longitudm"
                                   value="<?php echo $allreportellenado[0]->longitud; ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="form-group" id="coordenadas_field" hidden>
                <div class="form-group">
                    <div class="form-group">
                        <label for="coordenadas"><i class="fa fa-map-marker" onclick="getLocationm()"></i>
                            Coordenadas
                        </label>
                    </div>

                    <div class="row" id="coordenadas">
                        <div class="col">
                            <label for="latitudm">Latitud</label>
                            <input type="text" name="latitud" id="latitudm"
                                   value="<?php echo $allreportellenado[0]->latitud; ?>" class="form-control">
                        </div>

                        <div class="col">
                            <label for="longitudm">Longitud</label>
                            <input type="text" name="longitud" id="longitudm"
                                   value="<?php echo $allreportellenado[0]->longitud; ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <input type="hidden" id="idActual" value="<?php echo $allreportellenado[0]->id_Reporte_Padre; ?>">
        <input type="hidden" id="idGpoActual" value="<?php echo $allreportellenado[0]->id_Gpo_Padre; ?>">


        <?php
        /*-------------------------------- ACCION MOSTAR REPORTE: MUESTRA EL REPORTE SELECCIONADO ----------------------------*/
        $x = count($allreportellenado);
        $x = 0;
        $num_ubicacion = 1;
        foreach ($allreportellenado as $reporte) {
            $i = $reporte->tipo_Reactivo_Campo;
            $isRequired = "";
            if ($reporte->Campo_Necesario == 1) {
                $isRequired = "required";
            }
            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: TEXTO :::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "text") { ?>
                <div class="form-group" id="text_field">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?> </label>

                    <?php if ($reporte->nombre_Campo == "Identificador del dispositivo") {
                        $valor_default = $allcamposreportes["Id_Sistema_Llenado"] .
                            $valor_default = $allreportellenado[$x]->valor_Texto_Reporte . $allreportellenado[$x]->valor_Entero_Reporte;
                    } else {
                        $valor_default = $valor_default = $allreportellenado[$x]->valor_Texto_Reporte .
                            $allreportellenado[$x]->valor_Entero_Reporte;
                    } ?>
                    <input type="<?php echo $reporte->tipo_Reactivo_Campo ?>"
                           name="<?php echo $reporte->descripcion_Campo ?>"
                           id="<?php echo $reporte->descripcion_Campo ?>"
                           value="<?php echo $valor_default ?>" class="form-control" <?php echo $isRequired ?>>
                </div>
            <?php }

            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: TEXTAREA ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "textarea") { ?>
                <div class="form-group" id="textarea_field">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo; ?></label>
                    <textarea
                            name="<?php echo $reporte->descripcion_Campo ?>"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            style="background-color: #f0f0f0; height: 150px; resize: none;"
                            class="form-control"><?php echo $allreportellenado[$x]->valor_Texto_Reporte . $allreportellenado[$x]->valor_Entero_Reporte; ?></textarea>
                </div>
            <?php }
            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: UNA OPCION ::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "radio") {
                $opciones = explode("/", $reporte->Valor_Default);
                $valor_checado = $allreportellenado[$x]->valor_Texto_Reporte; ?>
                <div class="form-group" id="radio_field">
                    <label><?php echo $reporte->nombre_Campo; ?></label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php foreach ($opciones as $opcion) {
                            if ($opcion == $valor_checado) {
                                $checado = "checked";
                            } else if ($opcion != $valor_checado) {
                                $checado = "";
                            } ?>

                            <div class="radio" style="margin-top: unset">
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
                <div class="form-group" id="checkbox_field">
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
                                           class="radio i-checks" <?php echo $checado; ?>>
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
                <div class="form-group" id="number_field">
                    <label for=""> <?php echo $reporte->nombre_Campo; ?> </label>
                    <input type="<?php echo $reporte->tipo_Reactivo_Campo; ?>"
                           name="<?php echo $reporte->descripcion_Campo; ?>"
                           value="<?php echo $allreportellenado[$x]->valor_Texto_Reporte . $allreportellenado[$x]->valor_Entero_Reporte; ?>"
                           class="form-control" <?php echo $isRequired ?>>
                </div>
            <?php }


            /* ::::::::::::::::::::::::::::::::::: NUMEROS DECIMALES :::::::::::::::::::::::::::::::::::::::::::::::: */
            if ($reporte->tipo_Reactivo_Campo == "decimal") { ?>
                <div class="form-group" id="number_field">
                    <label for=""> <?php echo $reporte->nombre_Campo; ?> </label>
                    <input type="number" step="0.01"
                           name="<?php echo $reporte->descripcion_Campo; ?>"
                           value="<?php echo $allreportellenado[$x]->valor_Texto_Reporte; ?>"
                           class="form-control" <?php echo $isRequired ?>>
                </div>
            <?php }

            /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::FECHA:::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "date") {
                $campofecha = str_replace('/', '-', $allreportellenado[$x]->valor_Texto_Reporte);
                $date = new DateTime($campofecha); ?>
                <div class="form-group" id="date_field">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 6) { ?>
                        <label class="form-control"> <?php echo $date->format('Y-m-d') ?> </label>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo ?>"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?> hidden>
                    <?php } else { ?>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo ?>"
                               value="<?php echo $date->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }

            /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::HORA:::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "time") {
                $campohora = str_replace(' ', '', $allreportellenado[$x]->valor_Texto_Reporte) ?>
                <div class="form-group" id="time_field">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <?php if ($allcamposreportes[0]->tipo_Reporte == 6) { ?>
                        <label class="form-control"> <?php echo $campohora ?> </label>

                        <input type="time" onblur='validaNumericos()' step="1"
                               name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo ?>" value="<?php echo $campohora ?>"
                               id="hora" class="form-control" <?php echo $isRequired ?> hidden>
                    <?php } else { ?>
                        <input type="time" onblur='validaNumericos()' step="1"
                               name="<?php echo $reporte->descripcion_Campo ?>"
                               id="<?php echo $reporte->descripcion_Campo ?>" value="<?php echo $campohora ?>"
                               id="hora" class="form-control" <?php echo $isRequired ?>>
                    <?php } ?>
                </div>
            <?php }

            /*::::::::::::::::::::::::::::::::::::::::::::::::::::::SELECT::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select") {
                $opciones = explode("/", $reporte->Valor_Default); ?>
                <div class="form-group" id="select_field">
                    <label><?php echo $reporte->nombre_Campo ?> </label>
                    <select name="<?php echo $reporte->descripcion_Campo ?>"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="form-control" onchange='muestra(this.id)' <?php echo $isRequired ?>>

                        <?php foreach ($opciones as $opcion) {
                            if ($opcion != $allreportellenado[$x]->valor_Texto_Reporte) { ?>
                                <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $allreportellenado[$x]->valor_Texto_Reporte; ?>" selected>
                                    <?php echo $allreportellenado[$x]->valor_Texto_Reporte; ?>
                                </option>
                                <?php
                            }
                        }
                        ?>
                        <!--<option value="otroselect">Otro</option>-->
                    </select>
                </div>
                <!--<div class="form-group">
                    <input type="text" style="display:none;" name="otroselect<?php /*echo $reporte->descripcion_Campo; */ ?>"
                           id="otro<?php /*echo $reporte->descripcion_Campo; */ ?>" value="" class="form-control">
                </div>-->
            <?php }

            /*::::::::::::::::::::::::::::::::::::::::::::::::CHECK INCIDENCIA:::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "checkbox-incidencia") { ?>

                <div class="form-group" id="checkbox-incidencia_field">
                    <label> <?php echo $reporte->nombre_Campo; ?> </label>

                    <div class="form-group text-center">
                        <?php if ($allreportellenado[$x]->valor_Texto_Reporte == "Sí") { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?php echo $reporte->descripcion_Campo; ?>"
                                                   value="Sí"
                                                   checked <?php echo $isRequired ?>> Sí
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?php echo $reporte->descripcion_Campo; ?>"
                                                   value="No" <?php echo $isRequired ?>> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?php echo $reporte->descripcion_Campo ?>"
                                                   value="Sí" <?php echo $isRequired ?>> Sí
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="<?php echo $reporte->descripcion_Campo ?>"
                                                   value="No"
                                                   checked <?php echo $isRequired ?>> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }

            /*:::::::::::::::::::::::::::::::::::::::::::::::SELECT STATUS::::::::::::::::::::::::::::::::+:::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select-status") {
                $opciones = explode("/", $allreportellenado[$x]->valor_Texto_Reporte); ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <select name=" <?php echo $reporte->descripcion_Campo ?>"
                            id=" <?php echo $reporte->descripcion_Campo ?>"
                            class="form-control" <?php echo $isRequired ?>>
                        <?php echo $allreportellenado[$x]->valor_Texto_Reporte;
                        foreach ($opciones as $opcion) { ?>
                            <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php }


            /*:::::::::::::::::::::::::::::::::::::::::::::::SELECT TABLAS :::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "select-tabla") { ?>
                <div class="form-group">
                    <label><?php echo $reporte->nombre_Campo ?></label>
                    <select name="<?php echo $reporte->descripcion_Campo ?>"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="form-control" <?php echo $isRequired ?>>

                        <?php foreach ($allRegistrosTablas as $datos) {
                            if ($allreportellenado[$x]->valor_Texto_Reporte == $datos->id_Proyecto) { ?>
                                <option value="<?php echo $datos->id_Proyecto; ?>" selected>
                                    <?php echo $datos->nombre_Proyecto ?>
                                </option>
                            <?php } else { ?>
                                <option value="<?php echo $datos->id_Proyecto; ?>">
                                    <?php echo $datos->nombre_Proyecto ?>
                                </option>
                            <?php }
                        } ?>

                    </select>
                </div>
                <?php
            }

            /* :::::::::::::::::::::::::::::::::::::::::::TEXT CADENAMIENTO:::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "text-cadenamiento") {
                $cadenamientos = explode(".", $allreportellenado[$x]->valor_Texto_Reporte);
                $cadenamiento1 = $cadenamientos[0];
                $cadenamiento2 = $cadenamientos[1]; ?>
                <div class="form-group" id="text-cadenamiento_field">
                    <label><?php echo $reporte->nombre_Campo; ?></label>
                    <div class="input-group">
                        <input type="text" placeholder="Km" name="<?php echo $reporte->descripcion_Campo . "1"; ?>"
                               class="form-control text-center"
                               value="<?php echo $cadenamiento1; ?>" <?php echo $isRequired ?>>
                        <span class="input-group-addon" id="cadenamiento"><i class="fa fa-plus"></i></span>
                        <input type="text" placeholder="m" name="<?php echo $reporte->descripcion_Campo . "2"; ?>"
                               class="form-control text-center"
                               value="<?php echo $cadenamiento2; ?>" <?php echo $isRequired ?>>
                    </div>
                </div>
            <?php }


            /* ::::::::::::::::::::::::::::::::::::::::::: RANGO DE FECHAS :::::::::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "rango_fechas") {
                $fechasInicialFinal = explode(".", $allreportellenado[$x]->valor_Texto_Reporte);
                $fechaInical = $fechasInicialFinal[0];
                $fechaFinal = $fechasInicialFinal[1];
                $date1 = new DateTime($fechaInical);
                $date2 = new DateTime($fechaFinal);
                ?>
                <div class="form-group" id="text-cadenamiento_field">
                    <label><?php echo $reporte->nombre_Campo; ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">De</span>
                        </div>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>1"
                               id="fecha_inicial"
                               value="<?php echo $date1->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Hasta</span>
                        </div>
                        <input type="date" name="<?php echo $reporte->descripcion_Campo ?>2"
                               id="fecha_final"
                               value="<?php echo $date2->format('Y-m-d') ?>"
                               class="form-control" <?php echo $isRequired ?>>
                    </div>
                    <span>
                        <small class="text-danger invisible" id="container_alert"></small>
                    </span>
                </div>
            <?php }


            /* ::::::::::::::::::::::::::::::::::::::::::: CHECK LIST ASISTENCIA :::::::::::::::::::::::::::::::::::::*/
            if ($reporte->tipo_Reactivo_Campo == "check_list_asistencia") {
                $opciones = explode("/", $allreportellenado[$x]->valor_Texto_Reporte); ?>
                <div class="form-group">
                    <label for="<?php echo $reporte->descripcion_Campo ?>"><?php echo $reporte->nombre_Campo ?></label>
                    <select name="<?php echo $reporte->descripcion_Campo ?>[]"
                            id="<?php echo $reporte->descripcion_Campo ?>"
                            class="select-asistencia" multiple="multiple" <?php echo $isRequired ?>>
                        <?php foreach ($allEmpleados as $empleado) {
                            $igual = false;
                            for ($i = 0; $i <= count($opciones); $i++) {
                                if ($empleado->id_empleado == $opciones[$i])
                                    $igual = true;
                            }
                            if ($igual) { ?>
                                <option value="<?php echo $empleado->id_empleado; ?>" selected>
                                    <?php echo $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno; ?>
                                </option>
                            <?php } else { ?>
                                <option value="<?php echo $empleado->id_empleado; ?>">
                                    <?php echo $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno; ?>
                                </option>
                            <?php }
                        } ?>
                    </select>
                </div>
            <?php }
            $x++;
        } ?>
    </div>

    <!--:::::::::::::::::::::::::::::::::::::::::::::::::::::IMAGENES:::::::::::::::::::::::::::::::::::::::::::::::::::::-->


    <?php if (is_array($info_fotografia) || is_object($info_fotografia)) {
        $cantidad_tomadas = count($info_fotografia);
    }
    $fotos_restantes = $total_cantidad_solicitadas - $cantidad_tomadas;
    $opcion2 = 1;
    if ($total_cantidad_solicitadas > 0) { ?>
        <div class="col-sm-12 col-md-6">
            <?php if (is_array($info_fotografia) || is_object($info_fotografia)) {
                foreach ($info_fotografia as $datos_foto) {
                    $descripcion = $datos_foto->descripcion_Fotografia;
                    $orientacion = $datos_foto->orientacion_Fotografia;
                    if ($orientacion == 1) {

                        $style = "width:90%;";
                        $valor_or = 1;
                        $texto_or = "Horizontal";
                        $valor_or2 = 0;
                        $texto_or2 = "Vertical";
                    } else {
                        $style = "width:90%;";
                        $valor_or = 0;
                        $texto_or = "Vertical";
                        $valor_or2 = 1;
                        $texto_or2 = "Horizontal";
                    }

                    $carpeta = $datos_foto->fecha_Fotografia;
                    $carpeta = str_replace("-", "", $carpeta);
                    $carpeta = substr($carpeta, 0, -2);

                    $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
                    $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

                    $imagen = "img/reportes/" . $id_Empresa . "/" . $id_Proyecto . "/" . $carpeta . "/" . $datos_foto->nombre_Fotografia . ""; ?>
                    <div class="col-sm-12 container-file" id="foto0">
                        <div class="d-block text-center font-weight-bold pb-2 h6">Cargar Archivo</div>
                        <?php
                        $clasi_nombre = "Sin clasificación";
                        $clasi_id = NULL;
                        if (is_array($clasificacion) || is_object($clasificacion)) {
                            foreach ($clasificacion as $clas) {
                                if ($clas->id_Clasificacion == $info_fotografia[$opcion2 - 1]->directorio_Fotografia) {
                                    $clasi_nombre = $clas->nombre_Clasificacion;
                                    $clasi_id = $clas->id_Clasificacion;
                                }
                            }
                        }

                        //TIPO DE ARCHIVO
                        $ext = explode(".", $datos_foto->nombre_Fotografia);
                        switch ($ext[1]) {
                            case 'jpg':
                            case 'png':
                            case 'bmp':
                                $img = "<img src='" . $imagen . "' style='" . $style . "'>";
                                break;
                            case 'doc':
                            case 'docx':
                                $img = "<h5><i class=\"fa fa-file-word-o\" aria-hidden=\"true\"></i> <br/><a href='" . $imagen . "'>" . $datos_foto->nombre_Fotografia . "</h5></a>";
                                break;
                            case 'xls':
                            case 'xml':
                                $img = "<h3<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> <a href='" . $imagen . "'>" . $datos_foto->nombre_Fotografia . "</h3></a>";
                                break;
                            case 'pdf':
                                $img = "<h3<i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i> <a href='" . $imagen . "'>" . $datos_foto->nombre_Fotografia . "</h3></a>";
                                break;
                            case 'zip':
                            case 'rar':
                                $img = "<h3<i class=\"fa fa-file-archive-o\" aria-hidden=\"true\"></i> <a href='" . $imagen . "'>" . $datos_foto->nombre_Fotografia . "</h3></a>";
                                break;
                            default:
                                $img = "<h3<i class=\"fa fa-file-o\" aria-hidden=\"true\"></i> <a href='" . $imagen . "'>" . $datos_foto->nombre_Fotografia . "</h3></a>";
                        } ?>

                        <div class="fallback">
                            <div class="form-group">
                                <div class="file-loading">
                                    <input type="file" name="<?php echo "nombreimg" . $opcion2; ?>"
                                           id="<?php echo "nombreimg" . $opcion2; ?>"
                                           data-urlimg="<?php echo $imagen ?>"
                                           data-urlerase="index.php?controller=LlenadosReporte&action=borrarimg&Id_Fotografia=<?php echo $datos_foto->id_Fotografia ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="clasificacion_field">
                            <label for="<?php echo "clas" . $opcion2 ?>">Clasificación</label>
                            <select name="<?php echo "clas" . $opcion2 ?>" class="form-control"
                                    id="<?php echo "clas" . $opcion2 ?>">
                                <?php foreach ($clasificacion as $clas) {
                                    if ($clas->id_Clasificacion != $clasi_id && $clas->id_Clasificacion != 1) { ?>
                                        <option name="<?php echo $clas->nombre_Clasificacion; ?>"
                                                id="<?php echo $clas->id_Clasificacion; ?>"
                                                value="<?php echo $clas->id_Clasificacion; ?>"><?php echo $clas->nombre_Clasificacion; ?></option>
                                        <?php //} else if ($clas->id_Clasificacion == 1) { ?>
                                        <!-- <option disabled name="<?php echo $clas->nombre_Clasificacion; ?>"
                                            id="<?php echo $clas->id_Clasificacion; ?>"
                                            value="<?php echo $clas->id_Clasificacion; ?>">Introduce Descripción
                                    </option> -->
                                    <?php } else { ?>
                                        <option selected
                                                value="<?php echo $clasi_id; ?>"><?php echo $clasi_nombre; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group" id="observaciones_field">
                            <label for="descFotoFoto1">Observaciones </label>
                            <textarea name="<?php echo "desc" . $opcion2; ?>" id="descFotoFoto1"
                                      class="form-control"><?php echo $descripcion; ?></textarea>
                        </div>

                        <input type="hidden" name="<?php echo "nombre" . $opcion2; ?>"
                               value="<?php echo $datos_foto->nombre_Fotografia; ?>"/>
                        <input type="hidden" name="<?php echo "id" . $opcion2; ?>"
                               value="<?php echo $datos_foto->id_Fotografia; ?>"/>
                        <!-- <input type="file" name="<?php echo "nombreimg" . $opcion2; ?>"  id="<?php echo "nombreimg" . $opcion2; ?>" class='form-control'> -->

                        <input type="hidden" data-urlimg<?php echo $opcion2 . "='" . $imagen . "'" ?>>

                    </div>
                    <?php $opcion2++;
                } ?>

                <input type="hidden" id="fotos_tomadas" name="fotos_tomadas" value="<?php echo $cantidad_tomadas; ?>">
            <?php }

            /********************************************* FOTOS RESTANTES ********************************************************/
            for ($x2 = 1; $x2 <= $fotos_restantes; $x2++) { ?>
                <div class="col-sm-12 container-file" id="foto<?php echo $x2 ?>">
                    <div class="d-block text-center font-weight-bold pb-2 h6">Subir Archivo
                        <!--<a href="#"><i class='fa fa-trash' aria-hidden='true'></i></a>--></div>

                    <div class="fallback">
                        <div class="form-group">
                            <div class="file-loading">
                                <input type="file" name="<?php echo "fotorestante" . $x2; ?>"
                                       id="<?php echo "fotorestante" . $x2; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="<?php echo "clasrestante" . $x2; ?>" class="control-label">Clasificación </label>
                        <select name="<?php echo "clasrestante" . $x2; ?>" class="form-control"
                                id="<?php echo "clasrestante" . $x2; ?>">
                            <?php foreach ($clasificacion as $clas) {
                                //if ($clas->id_Clasificacion != 1) { ?>
                                <option name="<?php echo $clas->nombre_Clasificacion; ?>"
                                        id="<?php echo $clas->id_Clasificacion; ?>"
                                        value="<?php echo $clas->id_Clasificacion; ?>"><?php echo $clas->nombre_Clasificacion; ?>
                                </option>
                                <?php //}
                            } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="<?php echo "descrestantefotorestante" . $x2 ?>">Observaciones </label>
                        <textarea name="<?php echo "descrestante" . $x2 ?>"
                                  style="resize: unset; height: 100px;	background-color: #f0f0f0;"
                                  class="form-control"
                                  id="<?php echo "descrestantefotorestante" . $x2 ?>"></textarea>
                    </div>

                    <input type="hidden" name="<?php echo "orirestante" . $x2; ?>" value="1">

                </div>

                <!-- <input type="file" name="<?php echo "fotorestante" . $x2; ?>"  id="<?php echo "fotorestante" . $x2; ?>" class='form-control'> -->
            <?php } ?>

            <input type="hidden" name="fotos_restantes" value="<?php echo $fotos_restantes; ?>">
        </div>
    <?php } ?>

</div>
<script>
    function muestra(clicked_id) {
        //alert('algo');
        var valorotro = $("#" + clicked_id).val();
        //alert(valorotro);
        var idotro = "otro" + clicked_id;
        if ((valorotro == "otroselect") || (valorotro == "otrocatalogo") || (valorotro == "otromonitoreo")) {
            document.getElementById(idotro).style.display = "inline";
        } else {
            document.getElementById(idotro).style.display = "none";
        }
    }
</script>

<script>
    var $fileInput = $('.file-input');
    var $droparea = $('.file-drop-area');

    // highlight drag area
    $fileInput.on('dragenter focus click', function () {
        $droparea.addClass('is-active');
    });

    // back to normal state
    $fileInput.on('dragleave blur drop', function () {
        $droparea.removeClass('is-active');
    });

    // change inner text
    $fileInput.on('change', function () {
        var filesCount = $(this)[0].files.length;
        var $textContainer = $(this).prev();

        if (filesCount === 1) {
            // if single file is selected, show file name
            var fileName = $(this).val().split('\\').pop();
            //OBTENER EXTENSION Y MOSTRAR TEXTO
            var ext = fileName.split(".");
            switch (ext[1]) {
                case 'jpg':
                case 'png':
                case 'bmp':
                    var img = "<i class=\"fa fa-file-image-o\" aria-hidden=\"true\"></i>"
                    break;
                case 'doc':
                case 'docx':
                    var img = "<i class=\"fa fa-file-word-o\" aria-hidden=\"true\"></i>"
                    break;
                case 'xls':
                case 'xml':
                    var img = "<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i>"
                    break;
                case 'pdf':
                    var img = "<i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i>"
                    break;
                case 'zip':
                case 'rar':
                    var img = "<i class=\"fa fa-file-archive-o\" aria-hidden=\"true\"></i>"
                    break;
                default:
                    var img = "<i class=\"fa fa-file-o\" aria-hidden=\"true\"></i>"
            }
        } else {
            // otherwise show number of files
            $textContainer.text(filesCount + ' files selected');
        }
        var valor = '<h4>' + img + ' ' + fileName + '</h4>';
        $textContainer.html(valor);
    });
</script>

<script>
    function validar(elem) {
        var currentId = $(elem).attr("id");
        var idcoment = "descrestante" + currentId;
        document.getElementById(idcoment).required = true;
    }
</script>

<script>
    /* APARTADO PARA OBTENER LOS ELEMENTOS PADRE GUARDADOS EN LA BD Y PRECARGARLOS EN EL MOMENTO DE MODIFICAR */
    let firstchoice = $('#first-choice'); // SELECT Elemento padre
    let secondchoice = $('#second-choice'); // SELECT Hijos del elemento padre
    $(document).ready(function () {

        if (firstchoice.val() != 0) {
            if (secondchoice.length) {
                secondchoice.val(function () {
                    obtenerHijos(1);
                });
            } else {
                obtenerHijos(1);
            }

        } else {
            secondchoice.empty();
            secondchoice.append('<option value="0" selected>Sin Elemento</option>');
        }
    });

    firstchoice.change(function () {
        if (firstchoice.val() != 0) {
            obtenerHijos(2);
        } else {
            secondchoice.empty();
            secondchoice.append('<option value="0" selected>Sin Elemento</option>');
        }
    });

    function obtenerHijos(selector) {
        const btnCambiarVinculo = document.querySelector("#btnCambiarVinculo");

        $.ajax({
            data: {
                id: firstchoice.val()
            },
            url: "./index.php?controller=LlenadosReporte&action=getChildren",
            type: 'POST',
            success: function (response) {
                let result = $.parseJSON(response);
                let seloption = "";
                secondchoice.empty();
                if (!btnCambiarVinculo) {
                    if (selector == 2) {
                        $.each(result, function (key) {
                            seloption += '<option value="' + result[key].id + '">' + result[key].name + '</option>';
                        });
                    } else {
                        $.each(result, function (key) {
                            if (result[key].id == <?php echo $allreportellenado[0]->id_Gpo_Padre ?>) {
                                seloption += '<option value="' + result[key].id + '" selected>' + result[key].name + '</option>';
                            } else {
                                seloption += '<option value="' + result[key].id + '">' + result[key].name + '</option>';
                            }
                        });
                    }
                    secondchoice.append(seloption);
                } else {
                    $.each(result, function (key) {
                        if (result[key].id == <?php echo $allreportellenado[0]->id_Gpo_Padre ?>) {
                            document.querySelector("#parReportePadre").textContent = result[key].name;
                            document.querySelector("#inputGpoPadre").value = result[key].id;
                        }
                    });
                }

            }
        });
    }
</script>
