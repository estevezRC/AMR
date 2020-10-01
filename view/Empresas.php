<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>
    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);
    });
</script>


<?php
/*--- ACCION INDEX: MUESTRA TODAS LAS EMPRESAS ---*/
if (($action == "index") || ($action == "modificar")) { ?>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="myModalLabel">Nueva Empresa</h4>

                    <form action="<?php echo $helper->url("Empresas", "guardarnuevo"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal">

                        <label class="control-label">Nombre: </label>
                        <input type="text" name="nombre_Empresa" class="form-control"/>

                        <label class="control-label">Teléfono: </label>
                        <input type="tel" maxlength="10" pattern="[0-9]{10}" name="telefono_Empresa"
                               placeholder="Telefono a 10 dígitos" class="form-control"/>

                        <label class="control-label">Celular: </label>
                        <input type="tel" maxlength="10" pattern="[0-9]{10}" name="celular_Empresa"
                               placeholder="Telefono a 10 dígitos" class="form-control"/>

                        <label class="control-label">Correo: </label>
                        <input type="text" name="correo_Empresa" class="form-control"/>

                        <label class="control-label">Rol: </label>
                        <input type="text" name="rol_Empresa" class="form-control"/>

                        <label class="control-label">Descripción: </label>
                        <input type="text" name="descripcion_Empresa" class="form-control"/>

                        <br>

                        <button type="submit" value="nueva empresa" class="btn btn-w-m btn-danger"/>
                        Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    /*--- ACCION MODIFICAR: EDITA EMPRESA--*/
    if ($modificar == 1) { ?>
        <div class="modal modal-viejo" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <!--<button type="button" class="close"
                                onclick="location.href='index.php?controller=Empresas&action=index';"
                                data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>-->

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                        <h4 class="modal-title" id="myModalLabel">Modificar Empresa</h4>

                        <form action="<?php echo $helper->url("Empresas", "guardarmodificacion"); ?>" method="post"
                              enctype="multipart/form-data" class="form-horizontal">
                            <input type="hidden" name="empresaid" value="<?php echo $datosempresa->id_Empresa; ?>"/>

                            <label class="control-label">Nombre: </label>
                            <input type="text" name="nombre_Empresa"
                                   value="<?php echo $datosempresa->nombre_Empresa; ?>" class="form-control"/>

                            <label class="control-label">Teléfono: </label>
                            <input type="tel" maxlength="10" pattern="[0-9]{10}" name="telefono_Empresa"
                                   placeholder="Telefono a 10 dígitos" class="form-control"
                                   value="<?php echo $datosempresa->telefono_Empresa; ?>"/>

                            <label class="control-label">Celular: </label>
                            <input type="tel" maxlength="10" pattern="[0-9]{10}" name="celular_Empresa"
                                   placeholder="Telefono a 10 dígitos" class="form-control"
                                   value="<?php echo $datosempresa->celular_Empresa; ?>"/>

                            <label class="control-label">Correo: </label>
                            <input type="text" name="correo_Empresa" class="form-control"
                                   value="<?php echo $datosempresa->correo_Empresa; ?>"/>

                            <label class="control-label">Rol: </label>
                            <input type="text" name="rol_Empresa" value="<?php echo $datosempresa->rol_Empresa; ?>"
                                   class="form-control"/>

                            <label class="control-label">Descripción: </label>
                            <input type="text" name="descripcion_Empresa" class="form-control"
                                   value="<?php echo $datosempresa->descripcion_Empresa; ?>"/>

                            <br>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" value="Guardar" class="btn btn-w-m btn-danger">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                    </button>
                                </div>
                            </div>
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
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Empresa</th>
                            <th class="align-middle">Teléfono</th>
                            <th class="align-middle">Celular</th>
                            <th class="align-middle">Correo</th>
                            <th class="align-middle">Rol</th>
                            <th class="align-middle">Descripción</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allempresas) || is_object($allempresas)) {
                            foreach ($allempresas as $empresa) { ?>
                                <tr>
                                    <td><?= $empresa->id_Empresa; ?></td>
                                    <td><?= $empresa->nombre_Empresa; ?></td>
                                    <td><?= $empresa->telefono_Empresa; ?></td>
                                    <td><?= $empresa->celular_Empresa; ?></td>
                                    <td><?= $empresa->correo_Empresa; ?></td>
                                    <td><?= $empresa->rol_Empresa; ?></td>
                                    <td><?= $empresa->descripcion_Empresa; ?></td>
                                    <td>
                                        <a href="index.php?controller=Empresas&action=modificar&empresaid=<?= $empresa->id_Empresa; ?>&insercion=<?= $insercion; ?>&newElemento=<?= $newElemento; ?>"
                                           data-trigger="hover" data-content="Modificar información"
                                           data-toggle="popover">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            <? }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<? } ?>
