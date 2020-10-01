<?php
/* ------------------------------------- VISTA PRINCIPAL DE LOS MÓDULOS --------------------------------------------- */
if ($action == "busqueda") { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-6 p-0 shadow">
                <div class="w-100 d-flex justify-content-between bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                </div>
                <div class="container-fluid p-2">
                    <form action="#" method="post" id="formBusqueda">
                        <input type="hidden" name="id_Proyecto"
                               value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>"/>
                        <input type="hidden" name="segmento" value="Reportes"/>
                        <input type="hidden" name="Estatus" value="Estatus"/>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="id_Reporte">Reporte: </label>
                                <select name="id_Reporte" id="id_Reporte" class="form-control"
                                        onchange="muestra()">
                                    <option name="" id="" value="-1|TODOS">TODOS</option>
                                    <?php
                                    $varinci = 0;
                                    $varrep = 0;
                                    $varubi = 0;
                                    $varinv = 0;
                                    $varSeg = 0;
                                    $varDocBim = 0;
                                    foreach ($allreportes as $reporte) {

                                        if ($reporte->tipo_Reporte == 0 && $varinci < 1) {
                                            $varinci = 1; ?>
                                            <option name="" id="" value="t0|REPORTES">REPORTES</option>
                                        <?php }

                                        if ($reporte->tipo_Reporte == 1 && $varrep < 1) {
                                            $varrep = 1; ?>
                                            <option name="" id="" value="t1|INCIDENCIAS">INCIDENCIAS</option>
                                        <?php }

                                        if ($reporte->tipo_Reporte == 2 && $varubi < 1) {
                                            $varubi = 1; ?>
                                            <option name="" id="" value="t2|UBICACIONES">UBICACIONES</option>
                                        <?php }

                                        if ($reporte->tipo_Reporte == 3 && $varinv < 1) {
                                            $varinv = 1; ?>
                                            <option name="" id="" value="t3|INVENTARIO">INVENTARIO</option>
                                        <?php }

                                        if ($reporte->tipo_Reporte == 4 && $varSeg < 1) {
                                            $varSeg = 1; ?>
                                            <option name="" id="" value="t4|SEGUIMIENTO INCIDENCIAS">SEGUIMIENTO
                                                INCIDENCIAS
                                            </option>
                                        <?php }

                                        if ($reporte->tipo_Reporte == 5 && $varDocBim < 1) {
                                            $varDocBim = 1; ?>
                                            <option name="" id="" value="t5|DOCUMENTO BIM">DOCUMENTO BIM
                                            </option>
                                        <?php }
                                        ?>

                                        <option name="<?php echo $reporte->nombre_Reporte; ?>" id="<?php echo
                                        $reporte->id_Reporte; ?>"
                                                value="<?php echo $reporte->id_Reporte . "|" . $reporte->nombre_Reporte; ?>">
                                            &nbsp;&nbsp;• <?php echo $reporte->nombre_Reporte; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fechainicio">Fecha Del:</label>
                                <input type="date" name="fecha_Inicio" value="" class="form-control"
                                       id="fechainicio"
                                       placeholder="Fecha Del"/>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fechafinal">Al:</label>
                                <input type="date" name="fecha_Final" value="" id="fechafinal"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="palabras_clave">Palabras Clave:</label>
                            <input type="text" name="palabras_clave" placeholder="Escribir palabra(s) clave"
                                   value=""
                                   id="palabras_clave" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="identificador_reporte">Identificador del reporte: </label>
                            <input type="text" placeholder="Escribir identificador"
                                   name="identificador_reporte"
                                   id="identificador_reporte" value=""
                                   class="form-control"/>
                        </div>

                        <div class="form-group text-right">
                            <button value="buscar" id="btnBuscar" onclick="send(this)"
                                    class="btn btn-w-m btn-danger pull-right">
                                Buscar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
