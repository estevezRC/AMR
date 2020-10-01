<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable({
            responsive: true,
            "dom": 'T<"clear">lfrtip',
            "iTotalDisplayRecords": 50,
            "pageLength": 50,
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "ordering": false,
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": ["_all"]}
            ]
        });

        /* Init DataTables */
        var oTable = $('#editable').dataTable();

        /* Apply the jEditable handlers to the table */
        oTable.$('td').editable('http://webapplayers.com/example_ajax.php', {
            "callback": function (sValue, y) {
                var aPos = oTable.fnGetPosition(this);
                oTable.fnUpdate(sValue, aPos[0], aPos[1]);
            },
            "submitdata": function (value, settings) {
                return {
                    "row_id": this.parentNode.getAttribute('id'),
                    "column": oTable.fnGetPosition(this)[2]
                };
            },

            "width": "90%",
            "height": "100%"
        });


    });

    function fnClickAddRow() {
        $('#editable').dataTable().fnAddData([
            "Custom row",
            "New row",
            "New row",
            "New row",
            "New row"]);

    }
</script>
<style>
    body.DTTT_Print {
        background: #fff;

    }

    .DTTT_Print #page-wrapper {
        margin: 0;
        background: #fff;
    }

    button.DTTT_button, div.DTTT_button, a.DTTT_button {
        border: 1px solid #e7eaec;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }

    button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
        border: 1px solid #d2d2d2;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }

    .dataTables_filter label {
        margin-right: 5px;
        font-weight: normal;
    }

    .dataTables_filter input {
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #cccccc;
        padding: 0 0px 0 0px;

    }
</style>
<?php
/*--- ACCION INDEX: MUESTRA TODAS LOS PERMISOS ---*/
if (($action == "index") || ($action == "modificar")) {
    //var_dump($allpermisos);
//if (empty($_GET['mensaje'])) { $mensaje="";} else { $mensaje=$_GET['mensaje'];}
    ?>

<?php }
/*--- ACCION CREAR: REGISTRA NUEVO PERMISO---*/
if ($modificar == NULL) {
    ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Crear Nuevo Permiso<br><?php echo $mensaje; ?></h4>
                    <form action="<?php echo $helper->url("Permisos", "guardarnuevo"); ?>" method="post"
                          class="form-horizontal">
                        <label class="control-label">Puesto:</label><br>
                        <select name="permisopuesto" class="form-control"/>
                        <option value="">Seleccione puesto</option>
                        <?php foreach ($allpuestos as $puesto) { ?>
                            <option name="<?php echo $puesto->nombre_Puesto; ?>"
                                    id="<?php echo $puesto->id_Puesto_Usuario; ?>"
                                    value="<?php echo $puesto->id_Puesto_Usuario; ?>"><?php echo $puesto->nombre_Puesto; ?></option>
                        <?php } ?>
                        </select>
                        <label class="control-label">Recurso:</label><br>
                        <select name="permisorecurso" class="form-control"/>
                        <option value="">Seleccione recurso</option>
                        <?php foreach ($allrecursos as $recurso) { ?>
                            <option name="<?php echo $recurso->nombre_Recurso; ?>"
                                    id="<?php echo $recurso->id_Recurso_Sistema; ?>"
                                    value="<?php echo $recurso->id_Recurso_Sistema; ?>"><?php echo $recurso->nombre_Recurso; ?></option>
                        <?php } ?>
                        </select>
                        <label class="control-label">Consultar:</label><br>
                        <select name="permisoconsultar" class="form-control"/>
                        <option value="">Seleccione permiso</option>
                        <option name="consultarsi" id="consultarsi" value="1">Si</option>
                        <option name="consultarno" id="consultarno" value="0">No</option>
                        </select>
                        <label class="control-label">Agregar:</label><br>
                        <select name="permisoagregar" class="form-control"/>
                        <option value="">Seleccione permiso</option>
                        <option name="agregarsi" id="agregarsi" value="1">Si</option>
                        <option name="agregarno" id="agregarno" value="0">No</option>
                        </select>
                        <label class="control-label">Editar:</label><br>
                        <select name="permisoeditar" class="form-control"/>
                        <option value="">Seleccione permiso</option>
                        <option name="editarsi" id="editarsi" value="1">Si</option>
                        <option name="editarno" id="editarno" value="0">No</option>
                        </select>
                        <label class="control-label">Eliminar:</label><br>
                        <select name="permisoeliminar" class="form-control"/>
                        <option value="">Seleccione permiso</option>
                        <option name="eliminarsi" id="eliminarsi" value="1">Si</option>
                        <option name="eliminarno" id="eliminarno" value="0">No</option>
                        </select>
                        <br>
                        <button type="submit" value="nuevo permiso" class="btn btn-success"/>
                        Nuevo Permiso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

}
/*--- ACCION MODIFICAR: EDITA UN PERMISO---*/
if ($modificar == 1) {
    ?>
    <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close"
                            onclick="location.href='index.php?controller=Permisos&action=index';" data-dismiss="modal2"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modificar Permiso</h4>
                    <?php
                    //var_dump($datospermiso);
                    ?>
                    <form action="<?php echo $helper->url("Permisos", "guardarmodificacionpermiso"); ?>" method="post"
                          class="form-horizontal">
                        <input type="hidden" name="permisoid" value="<?php echo $datospermiso->id_Permiso; ?>"/>
                        <label class="control-label">Puesto:</label><br>
                        <select name="permisopuesto" class="form-control"/>
                        <option value="<?php echo $datospermiso->id_Puesto_Usuario; ?>"><?php echo $datospermiso->nombre_Puesto; ?></option>
                        <?php foreach ($allpuestos as $puesto) {
                            if ($puesto->nombre_Puesto != $datospermiso->nombre_Puesto) {
                                ?>
                                <option name="<?php echo $puesto->nombre_Puesto; ?>"
                                        id="<?php echo $puesto->id_Puesto_Usuario; ?>"
                                        value="<?php echo $puesto->id_Puesto_Usuario; ?>"><?php echo $puesto->nombre_Puesto; ?></option>
                            <?php }
                        } ?>
                        </select>
                        <label class="control-label">Recurso:</label><br>
                        <select name="permisorecurso" class="form-control"/>
                        <option value="<?php echo $datospermiso->id_Recurso_Sistema; ?>"><?php echo $datospermiso->nombre_Recurso; ?></option>
                        <?php foreach ($allrecursos as $recurso) {
                            if ($recurso->nombre_Recurso != $datospermiso->nombre_Recurso) {
                                ?>
                                <option name="<?php echo $recurso->nombre_Recurso; ?>"
                                        id="<?php echo $recurso->id_Recurso_Sistema; ?>"
                                        value="<?php echo $recurso->id_Recurso_Sistema; ?>"><?php echo $recurso->nombre_Recurso; ?></option>
                            <?php }
                        } ?>
                        </select>
                        <label class="control-label">Consultar:</label><br>
                        <label class="radio-inline">
                            <input name="permisoconsultar" type="radio"
                                   value="1" <?php if ($datospermiso->consultar_Permiso == 1) {
                                echo "checked";
                            } else {
                            } ?>/>Si</label>
                        <label class="radio-inline">
                            <input name="permisoconsultar" type="radio"
                                   value="0" <?php if ($datospermiso->consultar_Permiso == 0) {
                                echo "checked";
                            } else {
                            } ?>/>No</label><br/>
                        <label class="control-label">Agregar:</label><br>
                        <label class="radio-inline">
                            <input name="permisoagregar" type="radio"
                                   value="1" <?php if ($datospermiso->agregar_Permiso == 1) {
                                echo "checked";
                            } ?>/>Si</label>
                        <label class="radio-inline">
                            <input name="permisoagregar" type="radio"
                                   value="0" <?php if ($datospermiso->agregar_Permiso == 0) {
                                echo "checked";
                            } ?>/>No</label><br/>
                        <label class="control-label">Editar:</label><br>
                        <label class="radio-inline">
                            <input name="permisoeditar" type="radio"
                                   value="1" <?php if ($datospermiso->editar_Permiso == 1) {
                                echo "checked";
                            } ?>/>Si</label>
                        <label class="radio-inline">
                            <input name="permisoeditar" type="radio"
                                   value="0" <?php if ($datospermiso->editar_Permiso == 0) {
                                echo "checked";
                            } ?>/>No</label><br/>
                        <label class="control-label">Eliminar:</label><br>
                        <label class="radio-inline">
                            <input name="permisoeliminar" type="radio"
                                   value="1" <?php if ($datospermiso->eliminar_Permiso == 1) {
                                echo "checked";
                            } ?>/>Si</label>
                        <label class="radio-inline">
                            <input name="permisoeliminar" type="radio"
                                   value="0" <?php if ($datospermiso->eliminar_Permiso == 0) {
                                echo "checked";
                            } ?>/>No</label><br/>
                        <br>
                        <button type="submit" value="Guardar" class="btn btn-success"/>
                        Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<div class="col-sm-12 ibox-title">
    <div class="col-sm-10">
        <h3>Permisos <?php echo $mensaje; ?></h3>

    </div>
</div>
<div class="ibox-content">
    <div class="col-sm-12" style="padding: 0 55px;">
        <form action="<?php echo $helper->url("Permisos", "guardarmodificacionpermiso"); ?>" method="post"
              class="form-horizontal">
            <input type="hidden" name="id_Perfil_Usuario" value="<?php echo $_GET['id_Perfil_Usuario']; ?>"
                   checked>
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Recurso</th>
                    <th>Descripcion</th>
                    <th>Si</th>
                    <th>No</th>
                </tr>
                </thead>
                <tbody>
                <?php if (is_array($allpermisos) || is_object($allpermisos)) {
                    $valor = 1;
                    foreach ($allpermisos as $permiso) {
                        if ($permiso) {
                            ?>
                            <tr>
                            <td> <?php echo $valor; ?> </td>
                            <td><label><?php echo $permiso->nombre_Recurso; ?></label></td>
                            <td><label><?php echo $permiso->descripcion_Recurso; ?></label></td>
                            <?php if ($permiso->consultar_Permiso == 1) { ?>
                                <td><input type="radio" name="<?php echo $permiso->id_Permiso; ?>" value="1"
                                           checked></td>
                                <td><input type="radio" name="<?php echo $permiso->id_Permiso; ?>"
                                           value="0">
                                </td>
                            <?php } else if ($permiso->consultar_Permiso == 0) { ?>
                                <td><input type="radio" name="<?php echo $permiso->id_Permiso; ?>"
                                           value="1">
                                </td>
                                <td><input type="radio" name="<?php echo $permiso->id_Permiso; ?>" value="0"
                                           checked></td>
                            <?php }
                            $valor++;
                        }
                    } ?></tr>
                <?php } ?>
            </table>


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
