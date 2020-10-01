<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<?php
/*--------------------------------- ACCION INDEX: MUESTRA TODOS LOS DISPOSITIVOS --------------------------------------*/
if (($action == "index") || ($action == "modificar")) { ?>
    <?php if ($modificar == NULL) { ?>
        <!-- FORMULARIO CREAR NUEVO ROL -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Nuevo Dispositivo</h4>
                        <form action="<?= $helper->url("Cat_Dispositivos", "guardarnuevo"); ?>" method="post"
                              class="form-horizontal">
                            <label class="control-label">Marca:</label><input type="text" name="Marca" value=""
                                                                              class="form-control"/>
                            <label class="control-label">Usuario:</label>
                            <select name="id_Usuario" class="form-control">
                                <? foreach ($allusers as $correo) { ?>
                                    <option name="<?= $correo->correo_Usuario; ?>"
                                            id="<?= $usuario->id_Usuario; ?>"
                                            value="<?= $correo->id_Usuario; ?>"><?= $correo->correo_Usuario; ?></option>
                                <?php } ?>
                            </select>
                            <label for="NumeroSerie" class="control-label">Número de serie:</label>
                            <input type="text" id="NumeroSerie" name="NumeroSerie" value="" class="form-control">
                            <br>
                            <button type="submit" value="nuevo dispositivo" class="btn btn-w-m btn-danger">
                                Guardar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    if ($modificar == 1) { ?>
        <!-- FORMULARIO MODIFICAR TAREA -->
        <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close"
                                onclick="location.href='index.php?controller=Cat_Dispositivos&action=index';"
                                data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Modificar Dispositivo</h4>
                        <form action="<?php echo $helper->url("Cat_Dispositivos", "guardarmodificacion"); ?>"
                              method="post" class="form-horizontal">
                            <input type="hidden" name="Id_Dispositivo"
                                   value="<?php echo $datosdispositivo->Id_Dispositivo; ?>"/>
                            <label class="control-label">Marca:</label><input type="text" name="Marca"
                                                                              value="<?php echo $datosdispositivo->Marca; ?>"
                                                                              class="form-control"/>
                            <label class="control-label">Usuario:</label>
                            <select name="id_Usuario" class="form-control"/>
                            <option
                                value="<?php echo $datosdispositivo->id_Usuario; ?>"><?php echo $datosdispositivo->correo_Usuario; ?></option>
                            <?php
                            foreach ($allusers as $correo) {
                                if ($correo->id_Usuario != $datosdispositivo->id_Usuario) {
                                    ?>
                                    <option name="<?php echo $correo->correo_Usuario; ?>"
                                            id="<?php echo $usuario->id_Usuario; ?>"
                                            value="<?php echo $correo->id_Usuario; ?>"><?php echo $correo->correo_Usuario; ?></option>
                                <?php }
                            } ?>
                            </select>
                            <label class="control-label">Número de serie:</label>
                            <input type="text" name="NumeroSerie"
                                   value="<?php echo $datosdispositivo->NumeroSerie; ?>"
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
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <a class="px-2 m-1 h4 text-white"
                           href="#" data-trigger="hover" data-content="Nueva" data-toggle="popover"
                           onclick="popover('myModalArea')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Usuario</th>
                            <th class="align-middle">Correo</th>
                            <th class="align-middle">Marca/Modelo</th>
                            <th class="align-middle">Número de serie</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($alldispositivos) || is_object($alldispositivos)) {
                            $contadorDispositivos = 1;
                            foreach ($alldispositivos as $dispositivo) { ?>
                                <tr>
                                    <td><?= $contadorDispositivos; ?></td>
                                    <td><?= $dispositivo->nombre_Usuario . " " . $dispositivo->apellido_paterno . " " . $dispositivo->apellido_materno; ?></td>
                                    <td><?= $dispositivo->correo_Usuario; ?></td>

                                    <td><?= $dispositivo->Marca; ?></td>
                                    <td><?= $dispositivo->NumeroSerie; ?></td>
                                    <td>
                                        <a href="#" data-trigger="hover" data-content="Borrar dispositivo"
                                           data-toggle="popover"
                                           onclick="borrarRegistro(<?= $dispositivo->Id_Dispositivo; ?>, 'Id_Dispositivo', '<?= $dispositivo->Marca; ?> con número de serie <?= $dispositivo->NumeroSerie; ?>', 'Cat_Dispositivos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                    </td>
                                </tr>
                                <? $contadorDispositivos++;
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<? } ?>
