<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>

    //$(document).ready(function () {
    //    var insercion = <?php //echo $insercion; ?>//;
    //    var elemento = '<?php //echo $newElemento; ?>//';
    //    mensajes(insercion, elemento);
    //});

</script>


<?php
/*--- ACCION INDEX: MUESTRA TODAS LAS AREAS ---*/
if (($action == "index") || ($action == "modificar")) {
    ?>

<?php }
/*--- ACCION CREAR: REGISTRA NUEVA AREA---*/
if ($modificar == NULL) {
    ?>
    <!-- FORMULARIO CREAR NUEVA CONFIGURACION -->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nuevo Status</h4>
                    <form action="<?php echo $helper->url("Cat_Status", "guardarnuevo"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="id_Proyecto" value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>"/>

                        <label class="control-label">Estatus:</label>
                        <input type="text" name="Status" class="form-control" required>

                        <label class="control-label">Modulo:</label>
                        <br>
                        <label class="form-control labelPerfil"> <?php echo $allmodulos->Nombre; ?> </label>
                        <input value="<?php echo $allmodulos->Id_Modulo; ?>" name="IdModulo" hidden>

                        <!--
                        <select name="IdModulo" class="form-control"/>
                        <?php /*if ($action == "index") { */ ?>
                            <option value="">Seleccione el modulo</option>
                            <?php
                        /*foreach ($allmodulos as $modulo) { */ ?>
                                <option name="<?php /*echo $modulo->Nombre; */ ?>" id="<?php /*echo $modulo->Id_Modulo; */ ?>"
                                        value="<?php /*echo $modulo->Id_Modulo; */ ?>"><?php /*echo $modulo->Nombre; */ ?></option>
                            <?php /*}
                        } */ ?>
                        </select>
                        -->

                        <label class="control-label">Ponderación:</label>
                        <input type="number" name="Secuencia" class="form-control" required>

                        <!--<label class="control-label">Imagen:</label><input type="file" name="Imagen" id="Imagen"
                                                                           class="form-control">-->
                        <br>

                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-w-m btn-danger">
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
}
if ($modificar == 1) {
    ?>
    <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close"
                            onclick="location.href='index.php?controller=Cat_Status&action=index';"
                            data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modificar Status <?php echo $datosestatus->Status; ?></h4>
                    <form action="<?php echo $helper->url("Cat_Status", "guardarmodificacion"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="id_Status" value="<?php echo $datosestatus->id_Status; ?>"/>
                        <label class="control-label">Nombre:</label><input type="text" name="Status"
                                                                           value="<?php echo $datosestatus->Status; ?>"
                                                                           class="form-control"/>
                        <label class="control-label">Módulo:</label>
                        <select name="IdModulo" class="form-control">
                            <option name="<?php echo $datosestatus->Nombre_Modulo; ?>"
                                    id="<?php echo $datosestatus->IdModulo; ?>"
                                    value="<?php echo $datosestatus->IdModulo; ?>"><?php echo $datosestatus->Nombre_Modulo; ?></option>
                            <?php foreach ($allmodulos as $modulo) {
                                if ($modulo->Nombre != $datosestatus->Nombre_Modulo) {
                                    ?>
                                    <option name="<?php echo $modulo->Nombre; ?>" id="<?php echo $modulo->Id_Modulo; ?>"
                                            value="<?php echo $modulo->Id_Modulo; ?>"><?php echo $modulo->Nombre; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <label class="control-label">Secuencia:</label><input type="number" name="Secuencia"
                                                                              value="<?php echo $datosestatus->Secuencia; ?>"
                                                                              class="form-control"/>
                        <label class="control-label">Imagen:</label><input type="file" name="Imagen" id="Imagen"
                                                                           value="<?php echo $datosestatus->Imagen; ?>"
                                                                           class="form-control">
                        <input type="hidden" name="Imagen2" value="<?php echo $datosestatus->Imagen; ?>"/>
                        <br>

                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" value="Guardar" class="btn btn-w-m btn-danger"> Guardar</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="col-sm-12 ibox-title">
            <div class="col-sm-10">
                <h3><?php echo $mensaje; ?></h3>
            </div>
            <div class="col-sm-1" style="text-align:right;">
                <h3>
                    <a href="" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-plus-square" aria-hidden="true"> </i>
                    </a>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="ibox-content">
    <div class="col-sm-12">
        <table id="example" class="display" style="font-size:12px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Estatus</th>
                <th>Modulo</th>
                <th>Ponderación</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (is_array($allestatus) || is_object($allestatus)) {
                foreach ($allestatus as $estatus) {
                    ?>
                    <tr>
                    <td><?php echo $estatus->id_Status; ?></td>
                    <td><?php echo $estatus->Status; ?></td>
                    <td><?php echo $estatus->Nombre_Modulo; ?></td>
                    <td><?php echo $estatus->Secuencia; ?>%</td>
                    <td>
                        <a href="index.php?controller=Cat_Status&action=modificar&id_Status=<?php echo
                        $estatus->id_Status; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;

                        <a href="index.php?controller=Cat_Status&action=borrar&id_Status=<?php echo
                        $estatus->id_Status; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>

                    </td>
                <?php } ?>
                </tr>

                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

