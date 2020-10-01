<script>
    $(document).ready(function () {
        $('.dataTables-example').dataTable({
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
            "order": [[1, "ASC"]]
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
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="col-sm-12 ibox-title">
            <?php
            /*--- ACCION INDEX: MUESTRA TODOS LOS SISTEMAS ---*/
            if (($action == "buscar") || ($action == "modificar")){
            //var_dump($datoselemento);
            if (empty($_GET['mensaje'])) {
                $mensaje = "";
            } else {
                $mensaje = $_GET['mensaje'];
            }
            ?>
            <?php if ($modificar == NULL) { ?>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Crear Nuevo Sistema</h4>
                                <form action="<?php echo $helper->url("Sistemas", "guardarnuevo"); ?>" method="post"
                                      class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id_Proyecto"
                                           value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>">
                                    <label class="control-label">Sistema Padre: </label>
                                    <select name="Id_Padre_Sistema" class="form-control">
                                        <option value="NULL">Seleccione sistema</option>
                                        <?php foreach ($allsistemaspadre as $sistema) {
                                            ?>
                                            <option name="<?php echo $sistema->Nombre; ?>"
                                                    id="<?php echo $sistema->Id_Sistema; ?>"
                                                    value="<?php echo $sistema->Id_Sistema; ?>"><?php echo $sistema->Nombre; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="control-label">Nombre: </label><input type="text"
                                                                                        name="nombre_Sistema"
                                                                                        class="form-control"/>
                                    <label class="control-label">Imagen: </label><input type="file" name="uploadedfile"
                                                                                        id="uploadedfile"
                                                                                        class="form-control">
                                    <label class="control-label">Comentario: </label><input type="text"
                                                                                            name="Comentario"
                                                                                            class="form-control"/>
                                    <label class="control-label">Areas:</label><br/>
                                    <?php
                                    //var_dump($allareas);
                                    foreach ($allareas as $area) {
                                        ?>
                                        <input type="checkbox" name="area[]"
                                               value="<?php echo $area->id_Area; ?>">
                                        <?php
                                        echo $area->nombre_Area . "<br/>";
                                    }
                                    ?>
                                    <br>
                                    <button type="submit" value="nuevo sistema" class="btn btn-w-m btn-danger">Nuevo
                                        Sistema
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($modificar == 1) {
                $clasi_nombre = "Sin clasificación";
                $clasi_id = NULL;
                foreach ($clasificacion as $clas) {
                    if ($clas->id_Clasificacion == $datosfotografia[0]->directorio_Fotografia) {
                        $clasi_nombre = $clas->nombre_Clasificacion;
                        $clasi_id = $clas->id_Clasificacion;
                    }
                }
                ?>
                <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">

                                <button type="button" class="close"
                                        onclick="location.href='index.php?controller=Fotografias&action=buscar&id_Gpo=<?php echo $id_Gpo ?>';"
                                        data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Modificar
                                    fotografia <?php echo $datossistema->Nombre; ?></h4>
                                <form action="<?php echo $helper->url("Fotografias", "clasificar"); ?>" method="post"
                                      class="form-horizontal" enctype="multipart/form-data">
                                    <?php //var_dump($datosfotografia);
                                    ?>

                                    <input type="hidden" name="id_Fotografia"
                                           value="<?php echo $datosfotografia[0]->id_Fotografia; ?>"/>
                                    <input type="hidden" name="id_Gpo" value="<?php echo $id_Gpo; ?>"/>
                                    <label class="control-label">Clasificación: </label>
                                    <select name="id_Clasificacion" class="form-control">
                                        <option value="<?php echo $clasi_id; ?>"><?php echo $clasi_nombre; ?></option>
                                        <?php foreach ($clasificacion as $clas) {
                                            if ($clas->id_Clasificacion != $clasi_id) {
                                                ?>
                                                <option name="<?php echo $clas->nombre_Clasificacion; ?>"
                                                        id="<?php echo $clas->id_Clasificacion; ?>"
                                                        value="<?php echo $clas->id_Clasificacion; ?>"><?php echo $clas->nombre_Clasificacion; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                    <label class="control-label">Orientación: </label>
                                    <select name="orientacion_Fotografia" class="form-control">
                                        <?php if ($datosfotografia[0]->orientacion_Fotografia == 1) {
                                            $orientacion = "Horizontal";
                                        } else {
                                            $orientacion = "Vertical";
                                        } ?>
                                        <option name="<?php echo $orientacion; ?>"
                                                id="<?php echo $datosfotografia[0]->orientacion_Fotografia; ?>"
                                                value="<?php echo $datosfotografia[0]->orientacion_Fotografia; ?>"><?php echo $orientacion; ?></option>
                                        <?php if ($datosfotografia[0]->orientacion_Fotografia == 0) { ?>
                                            <option name="Horizontal" id="1" value="1">Horizontal</option>
                                        <?php }
                                        if ($datosfotografia[0]->orientacion_Fotografia == 1) {
                                            ?>
                                            <option name="Vertical" id="0" value="0">Vertical</option>
                                        <?php } ?>
                                    </select>
                                    <br>
                                    <br>
                                    <button type="submit" value="Guardar" class="btn btn-w-m btn-danger"/>
                                    Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="col-sm-10">
                <h3>Supervisor: Sistemas</h3>
            </div>
        </div>
        <div class="ibox-content">
            <h3><?php echo $mensaje; ?></h3>
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <table class="dataTables-example row-border hover">
                        <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>ID</th>
                            <th>Descripcion</th>
                            <th>Clasificación</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (is_array($allfotografias) || is_object($allfotografias)) {
                            foreach ($allfotografias as $fotografia) {
                                ?>
                                <tr id="<?php echo $fotografia->id_Fotografia; ?>">
                                <td>
                                    <?php
                                    if ($fotografia->id_Modulo == 1) {
                                        $modulo = "Reporte";
                                        $directorio = "img/reportes/";
                                    } else {
                                        $modulo = "Ubicacion";
                                        $directorio = "img/geos/";
                                    }
                                    if ($fotografia->orientacion_Fotografia == 1) {
                                        $width = "25%";
                                    } else {
                                        $width = "17%";
                                    } ?>
                                    <a class="example-image-link"
                                       href="<?php echo $directorio . $fotografia->nombre_Fotografia; ?>"
                                       data-lightbox="example-set"
                                       data-title="Click the right half of the image to move forward.">
                                        <img src="<?php echo $directorio . $fotografia->nombre_Fotografia; ?>"
                                             width="<?php echo $width; ?>"/>
                                    </a>
                                </td>
                                <td><?php
                                    echo $modulo . " ID: " . $fotografia->identificador_Fotografia;
                                    ?></td>
                                <td><?php echo $fotografia->descripcion_Fotografia; ?></td>
                                <td>
                                    <?php
                                    //var_dump($clasificacion);
                                    foreach ($clasificacion as $clas) {
                                        if ($clas->id_Clasificacion == $fotografia->directorio_Fotografia) {
                                            echo $clas->nombre_Clasificacion;
                                        }
                                    }
                                    ?></td>
                                <td>
                                    <?php echo $fotografia->puntuacion_Fotografia; ?>&nbsp;
                                    <a href="index.php?controller=Fotografias&action=like&id_Fotografia=<?php echo $fotografia->id_Fotografia; ?>&id_Gpo=<?php echo $id_Gpo ?>">
                                        <i class="fa fa-thumbs-up"></i>
                                    </a>&nbsp;&nbsp;&nbsp;
                                    <a href="index.php?controller=Fotografias&action=modificar&id_Fotografia=<?php echo $fotografia->id_Fotografia; ?>&id_Gpo=<?php echo $id_Gpo ?>"><i
                                                class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
                                </td>
                            <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <?php }
                    if ($action == "busqueda") {


                        ?>

                        <form action="<?php echo $helper->url("Fotografias", "buscar"); ?>" method="post"
                              class="form-horizontal">
                            <input type="hidden" name="id_Area" value="<?php echo $_SESSION[ID_AREA_SUPERVISOR]; ?>"/>
                            <div class="col-lg-2 col-lg-offset-3">
                                <span>ID Sistema: </span>
                                <select name="id_Gpo" id="id_Gpo" class="form-control"/>
                                <option name="" id="" value="">Seleccione Sistema</option>
                                <?php
                                foreach ($allsistemas as $sistema) {
                                    ?>
                                    <option name="<?php echo $sistema->Id_Gpo; ?>" id="<?php echo $sistema->Id_Gpo; ?>"
                                            value="<?php echo $sistema->Id_Gpo; ?>">
                                        <?php echo $sistema->Identificador; ?>
                                    </option>
                                <?php } ?>
                                </select>
                            </div>


                            <div class="row">
                                <div class="col-lg-2">
                                    <br/>
                                    <button type="submit" value="buscar" class="btn btn-w-m btn-danger pull-right"/>
                                    Buscar Reporte</button>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-lg-offset-3 hf-line-dashed"></div>
                                </div>


                        </form>

                    <?php } ?>

                </div>
            </div>
        </div>
