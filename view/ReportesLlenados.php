<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>

<script type="application/javascript">

    let estadoIncidencia = <?php echo $allreportellenado[0]->id_Etapa ?>;


    function fAgrega(clicked_id) {
        popover('myModalComentarios');

        id1 = "Text1" + clicked_id;
        id2 = "Text2" + clicked_id;
        document.getElementById("comentarios2").value = document.getElementById(id1).value;
        document.getElementById("idcomentario").value = document.getElementById(id2).value;
    }

    function getAllTiposReportes(idReporte, id_Gpo_Valores) {
        popover('myModalTiposReportes');
        $.ajax({
            data: {
                idReportePadre: idReporte,
                id_Gpo_Valores: id_Gpo_Valores
            },
            url: "./index.php?controller=LlenadosReporte&action=getAllTiposReportes",
            type: 'POST',
            success: function (response) {
                console.log(response);
                let info = $.parseJSON(response);
                console.log(info);
                let reportes = info.allreportes;
                let noReportes = info.allreportesNoEsquema;
                //console.log(noReportes);
                let $secondChoice = $("#id_reporteLlenar1");
                $secondChoice.empty();
                if (reportes.length == 0) {
                    $secondChoice.append("<option value='0'> No hay reportes de actividades  </option>");
                } else {
                    x = 0;
                    $.each(reportes, function () {
                        $secondChoice.append("<option value='" + reportes[x]['id_Reporte'] + "'>" + reportes[x]['nombre_Reporte'] + "</option>");
                        x++;
                    });
                }

                let secondChoice = $("#id_reporteLlenar2");
                secondChoice.empty();
                if (noReportes.length == 0) {
                    secondChoice.append("<option value='0'> No hay reportes de otro tipo  </option>");
                } else {
                    x = 0;
                    $.each(noReportes, function () {
                        secondChoice.append("<option value='" + noReportes[x]['id_Reporte'] + "'>" + noReportes[x]['nombre_Reporte'] + "</option>");
                        x++;
                    });
                }

                bloquearBtnVinvularReporte();
            }
        });
    }

    function bloquearBtnVinvularReporte() {
        let valorSelect = $("#id_reporteLlenar").val();

        if (valorSelect == 0)
            $("#btnLlenarVincularReporte").attr("disabled", true);
        else
            $('#btnLlenarVincularReporte').removeAttr("disabled");
    }


    function zero(n) {
        return (n > 9 ? '' : '0') + n;
    }

    function calularTiempoIncidencia() {
        let hora = $('#hora').text();
        let fecha = $('#fecha').text();


        let date = new Date();
        let time = date.getFullYear() + "-" + zero(date.getMonth() + 1) + "-" + zero(date.getDate()) + " " + zero(date.getHours()) + ":" + zero(date.getMinutes()) + ":" + zero(date.getSeconds());

        let fechaSistema = fecha + ' ' + hora;
        let fecha1_1 = moment(fechaSistema, "DD-MM-YYYY hh:mm:ss");
        //let fecha1 = moment(fecha1_1);
        let fecha2 = moment(time);
        let dias = fecha2.diff(fecha1_1, 'days');


        let horaActual = zero(date.getHours()) + ":" + zero(date.getMinutes()) + ":" + zero(date.getSeconds());
        var hora1 = (horaActual).split(":"),
            hora2 = (hora).split(":"),
            t1 = new Date(),
            t2 = new Date();
        t1.setHours(hora1[0], hora1[1], hora1[2]);
        t2.setHours(hora2[0], hora2[1], hora2[2]);
        //Aquí hago la resta
        t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());
        $('#valorIncidencia').text('Tiempo transcurrido de la incidencia: ' + dias + ' Días ' + t1.getHours() + ' Hora(s) ' + t1.getMinutes() + ' Minuto(s) ' + t1.getSeconds() + ' Segundo(s)');
    }

    function calularTiempoIncidenciaValidada() {

        let hora = $('#hora').text();
        let fecha = $('#fecha').text();

        let horaValidado = $('#horaValidado').text();
        let fechaValidado = $('#fechaValidado').text();

        let fechaSistema = fecha + ' ' + hora;
        let fecha1_1 = moment(fechaSistema, "DD-MM-YYYY hh:mm:ss");
        //let fecha1 = moment(fecha1_1);

        let fechaSistemaValidado = fechaValidado + ' ' + horaValidado;
        let fecha1_1Validado = moment(fechaSistemaValidado, "DD-MM-YYYY hh:mm:ss");
        //let fecha2 = moment(fecha1_1Validado);


        let dias = fecha1_1Validado.diff(fecha1_1, 'days');

        var hora1 = (horaValidado).split(":"),
            hora2 = (hora).split(":"),
            t1 = new Date(),
            t2 = new Date();
        t1.setHours(hora1[0], hora1[1], hora1[2]);
        t2.setHours(hora2[0], hora2[1], hora2[2]);
        //Aquí hago la resta
        t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());
        $('#valorIncidenciaValidada').text('Tiempo transcurrido de la incidencia: ' + dias + ' Días ' + t1.getHours() + ' Hora(s) ' + t1.getMinutes() + ' Minuto(s) ' + t1.getSeconds() + ' Segundo(s)');
    }

    if (estadoIncidencia != 5)
        setInterval(calularTiempoIncidencia, 1000);


    $(document).ready(function () {
        if (estadoIncidencia != 5)
            calularTiempoIncidencia();
        else
            calularTiempoIncidenciaValidada();

        let firma = <?php echo $firma ?>;
        if (firma === 1) {
            alertify.success('Se ha firmado el reporte');
        }
    });


</script>


<?php if ($action == "verreportellenado") {
    $_SESSION['allreportellenado'] = $allreportellenado;
    $_SESSION['info_fotografia'] = $info_fotografia;
    $_SESSION['datos_extras'] = $datos_extras;
    $_SESSION['datos_ubicacion'] = $datos_ubicacion;
    $_SESSION['info_inventario'] = $info_inventario;
    $_SESSION['residente'] = $residente;
    $_SESSION['info_reporte'] = $info_reporte;
    $_SESSION['info_reg_inicial'] = $info_reg_inicial;
    $_SESSION['info_reg_final'] = $info_reg_final;

    $id_Reporte = $_GET['Id_Reporte'];
    $id_Gpo = $_GET['id_Gpo_Valores_Reporte'];
    $valores = $info_conf[0]->valores_Conf;

    if (is_array($allreportellenado) || is_object($allreportellenado)) {
        $var_word = "phpword/generico.php?gpo=$id_Gpo";
    } ?>


    <!-- ********************* Modal para vincular reporte nuevo a reporte padre *********************************** -->
    <div class="modal fade" id="myModalTiposReportes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title text-center" id="myModalLabel"> Vincular reporte </h4>

                    <form action="<?php echo $helper->url("LlenadosReporte", "mostrarreportenuevo"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal">

                        <label class="control-label">*Seleccionar reporte</label>
                        <br>

                        <select name="Id_Reporte" id="id_reporteLlenar" class="form-control"
                                onchange="bloquearBtnVinvularReporte()">
                            <optgroup label="Reportes de Actividades" id="id_reporteLlenar1"></optgroup>

                            <optgroup label="Otros Reportes" id="id_reporteLlenar2"></optgroup>
                        </select>

                        <input name="idReportePadreVincular" value="<?php echo $allreportellenado[0]->id_Reporte ?>"
                               hidden>

                        <input name="idGpoValoresPadreVincular"
                               value="<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte ?>" hidden>

                        <input name="nombre_ReportePadreVincular"
                               value="<?php echo $allreportellenado[0]->nombre_Reporte ?>" hidden>

                        <input name="titulo_ReportePadreVincular"
                               value="<?php echo $allreportellenado[0]->titulo_Reporte ?>" hidden>

                        <br>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-w-m btn-danger" id="btnLlenarVincularReporte">
                                    Llenar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ********************* Modal para vincular reporte nuevo a reporte padre *********************************** -->


    <!-- ********************************** Modal para editar comentarios ****************************************** -->
    <div class="modal fade" id="myModalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar comentario</h4>
                    <form action="<?php echo $helper->url("ReportesLlenados", "editarcomentario"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal">
                        <textarea rows="4" cols="50" class="form-control" id="comentarios2" name="comentarios2"
                                  required></textarea>
                        <br/>
                        <input name="img_comentario2" class="form-control" type="file">
                        <input type="hidden" name="idcomentario" id="idcomentario">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>" id="id_usuario_modal">
                        <input type="hidden" name="id_Reporte" value="<?php echo $id_Reporte ?>" id="id_Reporte_modal">
                        <input type="hidden" name="id_Gpo_Valores_Reporte" value="<?php echo $id_Gpo ?>"
                               id="id_Gpo_Valores_Reporte_modal">
                        <br/>
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
    <!-- ********************************** Modal para editar comentarios ****************************************** -->


    <!-- ++++++++++++++++++++++++++++++++++++++++ Firmar Con  Nip ++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <div class="modal" id="myModalNip" tabindex="-1" role="dialog" aria-labelledby="myModalNip">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title text-center" id="myModalLabel"> Ingresar Nip para Firmar
                        Documento </h4>
                    <form action="#" method="post" class="form-horizontal">
                        <br>
                        <label class="control-label">*Nip(4 números)</label>
                        <br>
                        <!-- <label class="control-label">*Ingresar Nip:</label>-->
                        <input type="password" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                               class="form-control" id="nip" name="nip" pattern="[0-9]" placeholder="Ingresar Nip"
                               onkeyup="validarNip()"/>
                        <br>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" id="btnGuardarNip" disabled class="btn btn-w-m btn-danger"
                                        onclick="validarNipForFirmar(<?php echo $nipUser ?>, 'LlenadosReporte', 'firmar', <?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte ?>,  <?php echo $id_Reporte ?>)">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Firmar Documento
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- +++++++++++++++++++++++++++++++++++++++++ END Modificar Solo Nip ++++++++++++++++++++++++++++++++++++++++++++++ -->

    <script>
        function validarNip() {
            var nip = document.getElementById("nip").value;
            if (nip.length == 4)
                $('#btnGuardarNip').removeAttr("disabled");
            else {
                $("#btnGuardarNip").attr("disabled", true);
            }

        }

        function validarNipForFirmar(nipUser, controller, action, id_gpo, id_reporte) {
            var inputNip = document.getElementById("nip").value;
            if (inputNip == nipUser) {
                document.location.href = 'index.php?controller=' + controller + '&action=' + action + '&id_Gpo_Valores_Reporte=' + id_gpo + '&Id_Reporte=' + id_reporte;
            } else if (nipUser == null || nipUser == 0) {
                document.location.href = 'index.php?controller=Usuarios&action=perfil&registrarNip=7';
            } else {
                alertify.error('Nip Incorrecto');
            }
        }

        $(document).ready(function () {
            var firma = <?php echo $firma ?>;
            if (firma == 1) {
                alertify.success('Se ha firmado el reporte');
            }
        });
    </script>

    <!--TABLA CON TODOS LOS REPORTES-->

    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 pr-0 pr-md-2 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?php echo $mensaje; ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 pl-0 pl-md-2 d-flex justify-content-center align-items-center">
                        <?php if (getAccess(256, $decimal) || getAccess(512, $decimal)) { ?>
                            <div class="dropdown">
                                <button type="button" class="btn" id="descargables"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <a class="px-2 p-md-2 m-1 h5 text-white" href="#" data-trigger="hover"
                                       data-content="Descargar" data-toggle="popover">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </a>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="descargables">
                                    <?php if (getAccess(256, $decimal)) { ?>
                                        <li>
                                            <a class="p-2 dropdown-item text-secondary"
                                               href="descargables/reporte_generico_word.php?gpo=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>">
                                                <i class="fa fa-file-word-o" aria-hidden="true"></i> Word</a>
                                        </li>
                                    <?php }

                                    if (getAccess(512, $decimal)) { ?>
                                        <li>
                                            <a class="p-2 dropdown-item text-secondary" href="#" onclick="generarPDF()">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>

                        <?php if ($allreportellenado[0]->id_Etapa != 23) {
                            if (getAccess(8, $decimal) || $allreportellenado[0]->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                //if ($allreportellenado[0]->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                if ($id_Padre != '' || $id_Padre != null) { ?>
                                    <a class="px-2 p-md-2 m-1 h5 text-white" data-trigger="hover"
                                       data-content="Modificar" data-toggle="popover"
                                       href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>&Id_Reporte=<?php echo $id_Reporte ?>&id_Gpo_Valores_Reporte=<?php echo $id_Padre ?>">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <?php } else { ?>
                                    <a class="px-2 p-md-2 m-1 h5 text-white" data-trigger="hover"
                                       data-content="Modificar" data-toggle="popover"
                                       href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>&Id_Reporte=<?php echo $id_Reporte ?>&return=2">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <?php
                                }
                                //}
                            }
                        }

                        //SÍ TENGO ACTIVADA MI FIRMA (*)
                        if ($llaveE[0]->llave == null && $reporteFirmado == 1) {
                            $perfilUser = explode(',', $perfilesFirma);
                            $tengoPerfil = false;
                            foreach ($perfilUser as $perfil) {
                                // SÍ MI PERFIL ESTA AUTORIZADO (*)
                                if ($perfil == $_SESSION[ID_PERFIL_USER_SUPERVISOR]) {
                                    $tengoPerfil = true;
                                }
                            }
                            //SÍ SOY EL DUEÑO O TENGO EL PERFIL AUTORIZADO(*)
                            if (($allreportellenado[0]->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR] || $tengoPerfil == true) && $reporteFirmado == 1) { ?>

                                <a class="p-2 m-1 h5 text-white" href="#" data-trigger="hover"
                                   data-content="Firmar"
                                   data-toggle="popover" onclick="popover('myModalNip')">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </a>

                            <?php }
                        } ?>
                    </div>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col p-0 pt-3">
                        <div class="row justify-content-center">
                            <div class="col-9 col-md-11 p-2 bg-light" id="paneles">
                                <?php if ($allreportellenado[0]->tipo_Reporte != 1) {
                                    if ($allSeguimientosReportesIncidentes != null) { ?>
                                        <div class="row" id="porcentajeAvance">
                                            <div class="col-sm-12 text-right">
                                                <h5> Porcentaje de
                                                    Avance: <?php echo $porcentajeReporte; ?>% </h5>
                                            </div>
                                        </div>
                                    <?php }
                                } else if ($allreportellenado[0]->tipo_Reporte == 1) {
                                    if ($allreportellenado[0]->id_Etapa != 5) { ?>
                                        <div class="row">
                                            <div class="col-sm-12 text-right">
                                                <h4 id="valorIncidencia"></h4>
                                            </div>
                                        </div>
                                        <?php
                                    } else { ?>
                                        <div class="row">
                                            <div class="col-sm-12 text-right">
                                                <h4 id="valorIncidenciaValidada"></h4>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } ?>

                                <? include 'view/formularios_llenados/Reporte_Generico_2.php'; ?>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-11 p-0 mt-3">

                                <!-- ************************ SEGUIMIENTO DE REPORTES DE INCIDENCIAS ************************************ -->
                                <? if ($allreportellenado[0]->tipo_Reporte == 1) { ?>

                                    <hr class="linea-separadora mb-3">
                                    <div class="row">
                                        <h3 class="d-block w-100 p-2 text-primary text-center font-weight-bold">
                                            <span> REPORTES DE SEGUIMIENTO </span>
                                        </h3>

                                        <div class="col-sm-12 text-right pr-5 pb-3">
                                            <? if ($id_Reporte_Seguimiento != 0) {
                                                if ($allreportellenado[0]->id_Etapa != 5) { ?>

                                                    <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?= $id_Reporte_Seguimiento ?>&id_Gpo_Valores_Reporte=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>"
                                                       data-trigger="hover" data-content="Seguimiento a incidencia"
                                                       data-toggle="popover">
                                                        <img src="img/icons_Status/add.png" width="20px" alt="añadir">
                                                    </a>

                                                <? }
                                            } ?>
                                        </div>
                                        <div class="col-12 table-responsive-md">
                                            <table id="seguimientos" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ID TICKET</th>
                                                    <th>Título</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Estado del Reporte</th>
                                                    <th>Generado por</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <? if (is_array($allSeguimientosReportesIncidentes) || is_object($allSeguimientosReportesIncidentes)) {
                                                    foreach ($allSeguimientosReportesIncidentes as $seguimientoreporte) { ?>
                                                        <tr>
                                                        <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                                        <td><?= $seguimientoreporte->titulo_Reporte; ?></td>

                                                        <? if ($seguimientoreporte->campo_EstadoReporte == 'Validado') { ?>
                                                            <td id="fechaValidado"><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                                            <td id="horaValidado"><?= $seguimientoreporte->campo_Hora; ?></td>
                                                        <? } else { ?>
                                                            <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                                            <td><?= $seguimientoreporte->campo_Hora; ?></td>
                                                            <?
                                                        } ?>

                                                        <td>
                                                            <?
                                                            //require_once 'core/FuncionesCompartidas.php';
                                                            $funciones = new FuncionesCompartidas();
                                                            $texto = $seguimientoreporte->campo_EstadoReporte;
                                                            switch ($texto) {
                                                                case 'Abierto':
                                                                    $etapa = 2;
                                                                    break;
                                                                case 'En proceso':
                                                                    $etapa = 7;
                                                                    break;
                                                                case 'Atendido':
                                                                    $etapa = 3;
                                                                    break;
                                                                case 'Validado':
                                                                    $etapa = 5;
                                                                    break;

                                                            }

                                                            $icon = $funciones->iconosEstadoReporte($etapa);
                                                            echo $icon; ?>

                                                        </td>

                                                        <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                                                        <td>

                                                            <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>&id_Padre=<?= $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>"
                                                               data-trigger="hover" data-content="Ver detalle"
                                                               data-toggle="popover">
                                                                <i class="fa fa-search" aria-hidden="true"></i></a>
                                                            &nbsp;

                                                            <? if ($seguimientoreporte->id_Etapa != 23) {
                                                                if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                                                    //if ($seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                                    <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>&id_Gpo_Valores_Reporte=<?= $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>&return=3&id_ReporteP=<?= $id_Reporte ?>"
                                                                       data-trigger="hover" data-content="Modificar"
                                                                       data-toggle="popover">
                                                                        <i class="fa fa-pencil-square-o"
                                                                           aria-hidden="true"></i></a> &nbsp;&nbsp;

                                                                    <a href="#" data-trigger="hover"
                                                                       data-content="Borrar" data-toggle="popover"
                                                                       onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrar')">
                                                                        <i class="fa fa-trash"
                                                                           aria-hidden="true"></i></a>
                                                                <? }
                                                            } ?>
                                                            <br/>
                                                        </td>
                                                    <? } ?>
                                                    </tr>
                                                <? } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <? } ?>
                                <!-- ************************ END SEGUIMIENTO DE REPORTES DE INCIDENCIAS ************************************ -->


                                <!-- ************************ REPORTES VINCULADOS A REPORTE PADRE ****************************************** -->
                                <?php if ($allreportellenado[0]->tipo_Reporte != 1) {
                                    //if ($allSeguimientosReportesIncidentes != null) { ?>
                                    <hr class="linea-separadora mb-3">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <h3 class="d-block w-100 p-2 text-primary text-center font-weight-bold">
                                                    <span>REPORTES VINCULADOS</span>
                                                </h3>
                                                <div class="col-sm-12 text-right pr-5 pb-3">
                                                    <!--CUANDO SE INVOQUE LA FUNCION PASAR COMO PARAMETRO EL ID DEL REPORTE
                                                    PADRE PARA QUE NO SE PUEDA VINCULAR A EL MISMO.-->
                                                    <a href="#" data-trigger="hover"
                                                       data-content="Vincular reporte" data-toggle="popover"
                                                       onclick="getAllTiposReportes(<?php echo $allreportellenado[0]->id_Reporte ?>,  <?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>)">
                                                        <img src="img/icons_Status/add.png" width="20px">
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center mb-3">
                                                <div class="col-sm-6 col-md-11 col-lg-12 table-responsive-md">
                                                    <table id="vinculados" class="table table-striped">
                                                        <thead class="bg-primary text-light">
                                                        <tr>
                                                            <th>ID TICKET</th>
                                                            <th>Nombre de Reporte</th>
                                                            <th>Título</th>
                                                            <th>Fecha</th>
                                                            <th>Hora</th>
                                                            <th>Generado por</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <?php if (is_array($allSeguimientosReportesIncidentes) || is_object($allSeguimientosReportesIncidentes)) {
                                                            foreach ($allSeguimientosReportesIncidentes as $seguimientoreporte) { ?>
                                                                <tr>
                                                                <td><?php echo $seguimientoreporte->Id_Reporte; ?></td>
                                                                <td><?php echo $seguimientoreporte->nombre_Reporte; ?></td>
                                                                <td><?php echo $seguimientoreporte->titulo_Reporte; ?></td>
                                                                <td><?php echo $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                                                <td><?php echo $seguimientoreporte->campo_Hora; ?></td>
                                                                <td><?php echo $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                                                                <td>
                                                                    <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>&id_Padre=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>"
                                                                       data-trigger="hover" data-content="Ver detalle"
                                                                       data-toggle="popover"
                                                                       data-placement="left">
                                                                        <i class="fa fa-search" aria-hidden="true"></i></a>
                                                                    &nbsp;
                                                                    <?php if ($seguimientoreporte->id_Etapa != 23) {
                                                                        if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                                                            //if ($seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                                            <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>&tipo_Reporte=<?php echo $allreportellenado[0]->tipo_Reporte; ?>&id_Gpo_Valores_Reporte=<?php echo $allreportellenado[0]->id_Gpo_Valores_Reporte; ?>"
                                                                               data-trigger="hover"
                                                                               data-content="Modificar"
                                                                               data-toggle="popover">
                                                                                <i class="fa fa-pencil-square-o"
                                                                                   aria-hidden="true"></i></a> &nbsp;
                                                                        <?php }
                                                                    } ?>
                                                                    <br/>
                                                                </td>
                                                            <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>


                                    </div>


                                    <?php //}
                                } ?>
                                <!-- ************************ REPORTES VINCULADOS A REPORTE PADRE ****************************************** -->
                                <hr class="linea-separadora mb-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <h3 class="d-block w-100 p-2 text-primary text-center font-weight-bold">
                                                &nbsp;&nbsp;&nbsp;COMENTARIOS&nbsp;&nbsp;&nbsp;
                                            </h3>
                                        </div>

                                        <?php if (is_array($allcomentarios) || is_object($allcomentarios)) {
                                            foreach ($allcomentarios as $comentario) {
                                                $id1 = "Text1" . $comentario->id_comentario;
                                                $id2 = "Text2" . $comentario->id_comentario;
                                                $id3 = "editar" . $comentario->id_comentario;
                                                echo "<input type='hidden' id='" . $id1 . "' type='text' value='" . $comentario->Comentario_reporte . "' />";
                                                echo "<input type='hidden' id='" . $id2 . "' type='text' value='" . $comentario->id_comentario . "' />";
                                                $date = new DateTime($comentario->Fecha_Comentario); ?>

                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="form-group">
                                                                <label>
                                                                    <?= $comentario->nombre_Usuario . ' ' . $comentario->apellido_Usuario . " | " . $date->format('d-m-Y H:i:s'); ?>
                                                                </label> &nbsp;

                                                                <?php if ($comentario->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                                    <button data-trigger="hover"
                                                                            data-content="Modificar"
                                                                            data-toggle="popover" style="border: none"
                                                                            value="<?= $comentario->id_comentario; ?>"
                                                                            onclick="fAgrega(this.value);"
                                                                            class="btn-outline-secondary h5">
                                                                        <i class="fa fa-pencil-square-o"
                                                                           aria-hidden="true"></i>
                                                                    </button>

                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-sm-8">
                                                            <div class="form-group">
                                                                <label><?= nl2br($comentario->Comentario_reporte); ?> </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-right">
                                                            <?php if ($comentario->nombre_Fotografia != NULL) {
                                                                switch (explode('.', $comentario->nombre_Fotografia)[1]) {
                                                                    case 'bmp':
                                                                    case 'jpg':
                                                                    case 'png': ?>
                                                                        <a href="img/comentarios/<?= $comentario->nombre_Fotografia; ?>">
                                                                            <img width="40%"
                                                                                 src="img/comentarios/<?= $comentario->nombre_Fotografia; ?>"
                                                                                 alt="img">
                                                                        </a>
                                                                        <?php break;
                                                                    case 'xlsx':
                                                                    case 'xls': ?>
                                                                        <a href="img/comentarios/<?= $comentario->nombre_Fotografia; ?>"><i
                                                                                class="fa fa-file-excel-o"></i> <?= $comentario->nombre_Fotografia; ?>
                                                                        </a>
                                                                        <?php break;
                                                                    case 'docx':
                                                                    case 'doc': ?>
                                                                        <a href="img/comentarios/<?= $comentario->nombre_Fotografia; ?>"><i
                                                                                class="fa fa-file-word-o"></i> <?= $comentario->nombre_Fotografia; ?>
                                                                        </a>
                                                                        <?php break;
                                                                    case 'pdf': ?>
                                                                        <a href="img/comentarios/<?= $comentario->nombre_Fotografia; ?>"><i
                                                                                class="fa fa-file-pdf-o"></i> <?= $comentario->nombre_Fotografia; ?>
                                                                        </a>
                                                                        <?php break;
                                                                    default: ?>
                                                                        <a href="img/comentarios/<?= $comentario->nombre_Fotografia; ?>"><i
                                                                                class="fa fa-file"></i> <?= $comentario->nombre_Fotografia; ?>
                                                                        </a>
                                                                    <?php }
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <form
                                            action="<?= $helper->url("ReportesLlenados", "guardarcomentario"); ?>"
                                            method="post"
                                            enctype="multipart/form-data">

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="comentarios">Escribe un comentario:</label>
                                                        <textarea rows="4" cols="50" class="form-control"
                                                                  id="comentarios"
                                                                  name="comentarios" required></textarea>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12 text-right">
                                                            <input type="hidden" name="id_Reporte"
                                                                   value="<?= $id_Reporte ?>"
                                                                   id="id_Reporte">

                                                            <div class="form-group">

                                                                <input type="hidden" name="id_Gpo_Valores_Reporte"
                                                                       value="<?= $id_Gpo ?>"
                                                                       id="id_Gpo_Valores_Reporte">

                                                                <input type="hidden" name="id_usuario"
                                                                       value="<?= $id_usuario ?>"
                                                                       id="id_usuario">

                                                                <button type="submit" value="nuevo campo"
                                                                        class="btn btn-w-m btn-danger">
                                                                    Enviar Comentario&nbsp;<img
                                                                        src="././img/telegram-icon.svg"
                                                                        alt=""
                                                                        width="16px">
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? } ?>
