<script src="js/tabla.js"></script>
<?php if ($modificar == NULL) { ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nueva Monitoreo</h4>
                    <form action="<?php echo $helper->url("Monitoreo", "guardarnuevo"); ?>" method="post"
                          class="form-horizontal">
                        <label class="control-label">Acronimo:</label><input type="text" name="Acronimo"
                                                                             class="form-control"/>
                        <label class="control-label">Concepto:</label><input type="text" name="Concepto"
                                                                             class="form-control">
                        <label> Concepto Padre: </label>
                        <select name="IdPadreCatMonitorio" class="form-control"/>
                        <option value="0" id="0" name="Sin padre"> Sin Padre</option>
                        <?php
                        foreach ($allmonitoreos as $monitoreo) { ?>
                            <option name="<?php echo $monitoreo->Concepto; ?>"
                                    id="<?php echo $monitoreo->idCatMonitoreo; ?>"
                                    value="<?php echo $monitoreo->idCatMonitoreo; ?>"><?php echo $monitoreo->Concepto; ?></option>
                        <?php } ?>
                        </select><br/>
                        <label> Descriptiva: </label> <input type="text" name="Descriptiva" class="form-control">
                        <br>
                        <button type="submit" value="nuevo monitoreo" class="btn btn-w-m btn-danger"/>
                        Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
/*--- ACCION MODIFICAR: EDITA UNA ACTIVIDAD---*/
if ($modificar == 1) { ?>
    <!-- FORMULARIO MODIFICAR TAREA -->
    <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close"
                            onclick="location.href='index.php?controller=Monitoreo&action=index';"
                            data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Modificar Monitoreo</h4>

                    <form action="<?php echo $helper->url("Monitoreo", "guardarmodificar"); ?>"
                          method="post" class="form-horizontal">

                        <input type="hidden" name="idCatMonitoreo"
                               value="<?php echo $datosmonitoreo->idCatMonitoreo; ?>"/>

                        <label class="control-label"> Acronimo: </label><input type="text" name="Acronimo"
                                                                               value="<?php echo $datosmonitoreo->Acronimo; ?>"
                                                                               class="form-control"/>

                        <label class="control-label"> Concepto: </label><input type="text" name="Concepto"
                                                                               value="<?php echo $datosmonitoreo->Concepto; ?>"
                                                                               class="form-control"/>

                        <label class="control-label"> Concepto Padre: </label>
                        <select name="IdPadreCatMonitorio" class="form-control">
                            <option
                                value="<?php echo $datosmonitoreo->idCatMonitoreo; ?>"><?php echo $datosmonitoreo->Concepto2; ?></option>

                            <option value="0" id="0" name="Sin padre"> Sin Padre</option>
                            <?php
                            foreach ($allmonitoreos as $monitoreo) {
                                if ($monitoreo->idCatMonitoreo != $datosmonitoreo->IdPadreCatMonitorio) {
                                    ?>
                                    <option name="<?php echo $monitoreo->Concepto; ?>"
                                            id="<?php echo $monitoreo->idCatMonitoreo; ?>"
                                            value="<?php echo $monitoreo->idCatMonitoreo; ?>"><?php echo $monitoreo->Concepto; ?></option>
                                <?php }
                            } ?>
                        </select>


                        <label class="control-label">Descriptiva:</label><input type="text" name="Descriptiva"
                                                                                value="<?php echo $datosmonitoreo->Descriptiva; ?>"
                                                                                class="form-control"/>
                        <br>
                        <button type="submit" value="Guardar" class="btn btn-w-m btn-danger">
                            Guardar
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
<? } ?>
<div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row pt-4 d-flex justify-content-center">
        <div class="col-11 p-0 shadow">
            <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top ">
                <div class="col-sm-10 d-flex align-items-center">
                    <h4 class="text-white m-0 py-2">
                        <?= $mensaje; ?>
                    </h4>
                </div>
            </div>
            <div class="p-2 table-responsive-md">
                <table id="example" class="table table-striped">
                    <thead class="bg-primary text-light">
                    <tr>
                        <th class="align-middle"> Id</th>
                        <th class="align-middle"> Fecha</th>
                        <th class="align-middle"> Hora</th>
                        <!--<th> Tabla </th>-->
                        <th class="align-middle"> Usuario</th>
                        <th class="align-middle"> Accion</th>
                        <th class="align-middle"> IdGrupo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? if (is_array($allbitacoras) || is_object($allbitacoras)) {
                        $contadorBitacora = count($allbitacoras);
                        foreach ($allbitacoras as $bitacora) { ?>
                            <tr>
                                <td><?= $contadorBitacora ?></td>
                                <td><?= $this->formatearFecha($bitacora->fecha_Bitacora); ?></td>
                                <td><?= $bitacora->hora_Bitacora; ?></td>
                                <td><?= $bitacora->nombre_Usuario . " " . $bitacora->apellido_paterno . " " . $bitacora->apellido_materno; ?></td>
                                <td><?= $bitacora->Status; ?></td>
                                <td>
                                    <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $bitacora->id_Gpo; ?>&Id_Reporte=<?= $bitacora->id_Reporte; ?>"
                                       data-trigger="hover" data-content="Ver detalle del reporte"
                                       data-toggle="popover">
                                        <?= $bitacora->id_Gpo; ?>
                                </td>
                            </tr>
                            <? $contadorBitacora--;
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
