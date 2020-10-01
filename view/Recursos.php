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
            "order": [[0, "asc"]]
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
/*--- ACCION INDEX: MUESTRA TODOS LOS RECURSOS ---*/
if (($action == "index") || ($action == "modificar") || ($action == "crear")) {
    //var_dump($allrecursos);
    if (empty($_GET['mensaje'])) {
        $mensaje = "";
    } else {
        $mensaje = $_GET['mensaje'];
    }
    ?>

<?php }
/*--- ACCION CREAR: REGISTRA NUEVO RECURSO--*/
if ($modificar == NULL) {
    ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Crear Nuevo Recurso</h4>
                    <form action="<?php echo $helper->url("Recursos", "guardarnuevo"); ?>" method="post"
                          class="form-horizontal">
                        <label class="control-label">Nombre:</label><input type="text" name="recursonombre"
                                                                           class="form-control"/>
                        <label class="control-label">Descripción:</label> <textarea name="recursodescripcion"
                                                                                    class="form-control"></textarea><br>
                        <button type="submit" value="nuevo recurso" class="btn btn-success"/>
                        Nuevo Recurso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
/*--- ACCION MODIFICAR: EDITA UN RECURSO ---*/
if ($modificar == 1) {
    ?>
    <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close"
                            onclick="location.href='index.php?controller=Recursos&action=index';" data-dismiss="modal2"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modificar Recurso</h4>
                    <?php //var_dump($datosrecurso);
                    ?>
                    <form action="<?php echo $helper->url("Recursos", "guardarmodificacion"); ?>" method="post"
                          class="form-horizontal">
                        <input type="hidden" name="recursoid" value="<?php echo $datosrecurso->id_Recurso_Sistema; ?>"/>
                        <label class="control-label">Nombre:</label><input type="text" name="recursonombre"
                                                                           value="<?php echo $datosrecurso->nombre_Recurso; ?>"
                                                                           class="form-control"/>
                        <label class="control-label">Descripción:</label><textarea name="recursodescripcion"
                                                                                   class="form-control"><?php echo $datosrecurso->descripcion_Recurso; ?></textarea><br>
                        <button type="submit" value="Guardar" class="btn btn-success"/>
                        Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row pt-4 d-flex justify-content-center">
        <div class="col-11 p-0 shadow">
            <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                <div class="col-sm-10 d-flex align-items-center">
                    <h4 class="text-white m-2">
                        Recursos del Sistema
                    </h4>
                </div>
                <div class="col-sm-2 d-flex justify-content-center align-items-center">
                    <!--    <? /* if (getAccess(64, $decimal)) { */ ?>
                        <a href="#"
                           data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                           onclick="popover('myModalCampo')" class="p-2 m-1 h4 text-white">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </a>
                    --><? /* } */ ?>
                </div>
            </div>
            <div class="p-2 table-responsive-md">
                <table class="table table-striped">
                    <thead class="bg-primary text-light">
                    <tr>
                        <th class="align-middle">ID</th>
                        <th class="align-middle">Nombres</th>
                        <th class="align-middle">Descricpción</th>
                        <th class="align-middle">Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? if (is_array($allrecursos) || is_object($allrecursos)) {
                        $valor = 1;
                        $contadorRecursos = 1;
                        foreach ($allrecursos as $recurso) {
                            ?>
                            <tr>
                            <td><?= $contadorRecursos; ?></td>
                            <td><?= $recurso->nombre_Recurso; ?></td>
                            <td><?= $recurso->descripcion_Recurso; ?></td>
                            <td><?= $valor; ?></td>
                            <!-- <td>
                                    <a href="index.php?controller=Recursos&action=modificar&recursoid=<?php /*echo $recurso->id_Recurso_Sistema; */ ?>"><i
                                                class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
                                    <a href="index.php?controller=Recursos&action=borrar&recursoid=<?php /*echo $recurso->id_Recurso_Sistema; */ ?>"><i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>-->
                            <?
                            $valor = $valor + $valor;
                            $contadorRecursos++;
                        } ?>
                        </tr>
                    <? } ?>
                </table>
            </div>
        </div>
    </div>
</div>
