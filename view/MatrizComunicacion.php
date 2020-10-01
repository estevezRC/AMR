<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>

    let reportes = [], idsMatriz = [];

    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        obtenerReportesByUsuario();
    });

    function noReportes() {
        alertify.error('No existen reportes configurados');
    }

    function obtenerReportesByUsuario() {
        reportes = [];
        let id_Usuario = $('#select_id_Usuario').val();
        $.ajax({
            data: {id_Usuario: id_Usuario},
            url: "index.php?controller=MatrizComunicacion&action=getReportesByIdUsuario",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                var $secondChoice = $("#tablaReportes");
                $secondChoice.empty();
                if (respuestaJSON.length > 0) {
                    for (i = 0; i < respuestaJSON.length; i++) {
                        $secondChoice.append("<tr> <td>" + respuestaJSON[i].nombre_Reporte + "</td> <td> <input type='checkbox' id='mat_Correo_" + respuestaJSON[i].id_Reporte + "'></td>  <td><input type='checkbox' id='mat_Telegram_" + respuestaJSON[i].id_Reporte + "'></td> <td><input type='checkbox' id='mat_Push_" + respuestaJSON[i].id_Reporte + "'></td> </tr>");
                        reportes.push(respuestaJSON[i].id_Reporte);
                    }
                    $('#btnGuardarMatriz').removeAttr("disabled");
                } else {
                    $secondChoice.append("<tr> <td colspan='4'> <b>El usuario ya esta configurado en todos los reportes </b> </td></tr>");
                    $("#btnGuardarMatriz").attr("disabled", true);
                }
            }
        });

    }

    function armarInformacion(accion) {
        let datosReportes = [];
        if (reportes.length > 0) {
            for (n = 0; n < reportes.length; n++) {
                let arrayCheckbox = [];

                if (accion != 1)
                    arrayCheckbox.push(parseInt(idsMatriz[n]));

                arrayCheckbox.push(parseInt(reportes[n]));

                if (document.getElementById('mat_Correo_' + reportes[n])) {
                    if (document.getElementById('mat_Correo_' + reportes[n]).checked)
                        arrayCheckbox.push(1);
                    else
                        arrayCheckbox.push(0);
                }

                if (document.getElementById('mat_Telegram_' + reportes[n])) {
                    if (document.getElementById('mat_Telegram_' + reportes[n]).checked)
                        arrayCheckbox.push(1);
                    else
                        arrayCheckbox.push(0);
                }

                if (document.getElementById('mat_Push_' + reportes[n])) {
                    if (document.getElementById('mat_Push_' + reportes[n]).checked)
                        arrayCheckbox.push(1);
                    else
                        arrayCheckbox.push(0);
                }

                datosReportes.push(arrayCheckbox);
            }

            if (accion == 1)
                $('#arrayDatos').val(JSON.stringify(datosReportes));
            else
                $('#arrayDatosMod').val(JSON.stringify(datosReportes));

        }

        //console.log(JSON.stringify(datosReportes));
        //return false;
    }

    function obtenerInformacionMatrizComunicacionByUsuario(idUsuario) {
        popover('myModalModificarMatriz');

        reportes = [];

        $.ajax({
            data: {id_Usuario: idUsuario},
            url: "index.php?controller=MatrizComunicacion&action=obtenerInformacionMatrizComunicacionByUsuario",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);
                var $secondChoice = $("#tablaReportesMod");
                $secondChoice.empty();
                let correo;
                let telegram;
                let push;
                for (i = 0; i < respuestaJSON.length; i++) {
                    $('#contenedorUsuario').html("<label class='control-label labelPerfil' style='width: 100%; text-align: left'> " + respuestaJSON[i].correo_Usuario + " </label>");

                    if (respuestaJSON[i].mat_Correo != 0)
                        correo = 'checked';
                    else
                        correo = '';

                    if (respuestaJSON[i].mat_Telegram != 0)
                        telegram = 'checked';
                    else
                        telegram = '';

                    if (respuestaJSON[i].mat_Push != 0)
                        push = 'checked';
                    else
                        push = '';

                    $secondChoice.append("<tr> <td>" + respuestaJSON[i].nombre_Reporte + "</td> <td> <input type='checkbox' id='mat_Correo_" + respuestaJSON[i].mat_Id_Reporte + "' " + correo + "> </td>  <td><input type='checkbox' id='mat_Telegram_" + respuestaJSON[i].mat_Id_Reporte + "' " + telegram + "></td> <td><input type='checkbox' id='mat_Push_" + respuestaJSON[i].mat_Id_Reporte + "' " + push + "></td> </tr>");
                    reportes.push(respuestaJSON[i].mat_Id_Reporte);
                    idsMatriz.push(respuestaJSON[i].mat_Id);
                }
                $('#idUsuarioMod').val(idUsuario);
            }
        });
    }

</script>


<!-- *************************************** Modal para modificar informacion ************************************** -->
<div class="modal fade" id="myModalModificarMatriz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="width: 100%">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar Notificación</h4>

                <form action="<?php echo $helper->url("MatrizComunicacion", "guardarmodificacion"); ?>" method="post"
                      class="form-horizontal" onsubmit="return armarInformacion(2)">
                    <label class="control-label">Correo:</label>
                    <div class="row" style="margin: 0px 0em 0px 0em;">
                        <div id="contenedorUsuario">
                        </div>
                    </div>

                    <input name="mat_Id_Usuario" id="idUsuarioMod" hidden>

                    <div class="row" style="margin: 0px; padding-top: 1em;">
                        <table class="table table-striped" style="font-size:12px;">
                            <thead>
                            <tr>
                                <th>Reportes</th>
                                <th>Correo</th>
                                <th>Telegram</th>
                                <th>Push</th>
                            </tr>
                            </thead>
                            <tbody id="tablaReportesMod">
                            </tbody>

                        </table>
                    </div>

                    <input type="hidden" name="arrayDatos" id="arrayDatosMod">
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
<!-- *************************************** Modal para modificar informacion ************************************** -->


<div class="modal fade" id="myModalMatrizComunicacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="width: 100%">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Nueva Notificación</h4>

                <form action="<?php echo $helper->url("MatrizComunicacion", "guardarnuevo"); ?>" method="post"
                      class="form-horizontal" onsubmit="return armarInformacion(1)">
                    <label class="control-label">Correo:</label>
                    <select name="mat_Id_Usuario" class="form-control" id="select_id_Usuario"
                            onchange="obtenerReportesByUsuario()">
                        <?php
                        foreach ($alluser as $correo) { ?>
                            <option name="<?php echo $correo->correo_Usuario; ?>"
                                    id="<?php echo $usuario->id_Usuario; ?>"
                                    value="<?php echo $correo->id_Usuario; ?>"><?php echo $correo->correo_Usuario; ?></option>
                        <?php } ?>
                    </select>


                    <div class="row" style="margin: 0px; padding-top: 1em;">
                        <table class="table table-striped" style="font-size:12px;">
                            <thead>
                            <tr>
                                <th>Reportes</th>
                                <th>Correo</th>
                                <th>Telegram</th>
                                <th>Push</th>
                            </tr>
                            </thead>
                            <tbody id="tablaReportes">
                            </tbody>

                        </table>
                    </div>

                    <input type="hidden" name="arrayDatos" id="arrayDatos">
                    <br>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnGuardarMatriz">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

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
                    <? if ($allreportes) { ?>
                        <a href="#" data-trigger="hover" data-content="Nueva" data-toggle="popover"
                           class="px-2 m-1 h4 text-white" onclick="popover('myModalMatrizComunicacion')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </a>
                    <? } else { ?>
                        <a href="#" data-trigger="hover" data-content="Nueva" data-toggle="popover"
                           class="px-2 m-1 h4 text-white" onclick="noReportes()">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </a>
                    <? } ?>
                </div>
            </div>
            <div class="p-2 table-responsive-md">
                <table id="example" class="table table-striped">
                    <thead class="bg-primary text-light">
                    <tr>
                        <th class="align-middle">ID</th>
                        <th class="align-middle">Nombre</th>
                        <th class="align-middle">Correo</th>
                        <th class="align-middle">Reporte</th>
                        <th class="align-middle">E-mail</th>
                        <th class="align-middle">Telegram</th>
                        <th class="align-middle">Push</th>
                        <th class="align-middle">ID Telegram</th>
                        <th class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? if (is_array($allmatriz) || is_object($allmatriz)) {
                        $contadorMatriz = 1;
                        foreach ($allmatriz as $matriz) { ?>
                            <tr>
                                <td><?= $contadorMatriz ?></td>
                                <td><?= $matriz->nombre_Usuario . ' ' . $matriz->apellido_Usuario; ?></td>
                                <td><?= $matriz->correo_Usuario; ?></td>
                                <td><?= $matriz->nombre_Reporte; ?></td>
                                <td><?= $this->replaceSiNo($matriz->mat_Correo); ?></td>
                                <td><?= $this->replaceSiNo($matriz->mat_Telegram); ?></td>
                                <td><?= $this->replaceSiNo($matriz->mat_Push); ?></td>
                                <td><?= $matriz->id_telegram; ?></td>
                                <td>
                                    <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                       onclick="obtenerInformacionMatrizComunicacionByUsuario(<?= $matriz->mat_Id_Usuario; ?>)">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a> &nbsp;

                                    <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                       onclick="borrarRegistro(<?= $matriz->mat_Id; ?>, 'mat_Id', '<?= $matriz->nombre_Usuario . ' ' . $matriz->apellido_Usuario; ?> que esta configurado a <?= $matriz->nombre_Reporte; ?>', 'MatrizComunicacion', 'borrar')">
                                        <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                </td>
                            </tr>
                            <?
                            $contadorMatriz++;
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

