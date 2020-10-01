<script src="js/Campos.js"></script>

<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>
    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        $("#modalModificarCampos").on("hidden.bs.modal", function () {
            $('#reactivo_campoMod').empty();
            $('#contenedor').empty();
            $('#contenedorMod').empty();
            $('#conteo_defaultMod').val(0);
        });

    });

    function obtenerDatos(idCampo) {
        popover('modalModificarCampos');
        $.ajax({
            data: {id_Campo_Reporte: idCampo},
            url: "index.php?controller=Campos&action=modificar",
            method: "POST",
            success: function (response) {
                //console.log(response);
                let respuestaJSON = $.parseJSON(response);
                let nombreCampo = respuestaJSON.datosCampo.nombre_Campo;
                let idCampoReporte = respuestaJSON.datosCampo.id_Campo_Reporte;
                let tipo_Reactivo = respuestaJSON.datosCampo.tipo_Reactivo_Campo;
                console.log(tipo_Reactivo);

                $('#tituloModal').text('Modificar campo "' + nombreCampo + '"');
                $('#idCampoReporte').val(idCampoReporte);
                $('#nombreCampo').val(nombreCampo);

                let nombre_reactivo = Nombre_Campo(tipo_Reactivo);

                var tipoDatos = [
                    ['text', 1, 'text', 'Texto'],
                    ['textarea', 2, 'textarea', 'Texto largo'],
                    ['radio', 3, 'radio', 'Una opción'],
                    ['checkbox', 4, 'checkbox', 'Check list'],
                    ['number', 5, 'number', 'Número'],
                    ['date', 6, 'date', 'Fecha'],
                    ['time', 7, 'time', 'Hora'],
                    ['select', 8, 'select', 'Menú'],
                    ['file', 9, 'file', 'Imagen'],
                    ['label', 10, 'label', 'Etiqueta'],
                    ['label', 11, 'checkbox-incidencia', 'Incidencia'],
                    ['check_list_asistencia', 12, 'check_list_asistencia', 'Asistencia'],
                    ['label', 13, 'text-cadenamiento', 'Cadenamiento'],
                    ['rango_fechas', 14, 'rango_fechas', 'Rango de Fechas'],
                    ['select-tabla', 15, 'select-tabla', 'General(Tabla)'],
                    ['decimal', 16, 'decimal', 'Decimal']
                ];

                //console.log(tipoDatos);

                for (x = 0; x < tipoDatos.length; x++) {
                    if (tipo_Reactivo == tipoDatos[x][0]) {
                        $('#reactivo_campoMod').append("<option name='text' value='" + tipo_Reactivo + "' selected>" + nombre_reactivo + "</option>");
                    } else {
                        $('#reactivo_campoMod').append("<option name='" + tipoDatos[x][0] + "' id='" + tipoDatos[x][1] + "' value='" + tipoDatos[x][2] + "'>" + tipoDatos[x][3] + "</option>");
                    }
                }


                let contador = 0;
                let valoresDefault = respuestaJSON.valoresDefault;
                if (tipo_Reactivo == 'select' || tipo_Reactivo == 'radio' || tipo_Reactivo == 'checkbox') {
                    $('#valoresMod').removeAttr("hidden");
                    $("#cantidadMod").attr("hidden", true);

                    //console.log('valores' + valoresDefault.length);
                    for (x = 0; x < valoresDefault.length; x++) {
                        contador = x + 1;

                        $('#contenedorMod').append("<table> <tr> <td style='width: 98%'> <input type='text' style='margin-top: .5em;' name='mitexto_" + contador + "' id='campo_" + contador + "' placeholder='Ingresar valor' class='form-control' value='" + valoresDefault[x] + "'>" +
                            "</td> <td> <a href='#' class='eliminar' style='font-size: 18px; margin-top: .5em;'><i class='fa fa-trash-o' aria-hidden='true'></i></a>" +
                            "</td> </tr> </table>");

                    }
                } else if (tipo_Reactivo == 'file') {
                    $("#valoresMod").attr("hidden", true);
                    $('#cantidadMod').removeAttr("hidden");
                    $('#cantImgMod').val(valoresDefault.length);
                } else {
                    $("#valoresMod").attr("hidden", true);
                    $('#cantidadMod').attr("hidden", true);
                    //$('#cantImg').val(valoresDefault.length);
                }

                let conteo_default = respuestaJSON.valoresDefault.length;
                $('#conteo_defaultMod').val(conteo_default);
                $('#conteo_defaultNoMod').val(conteo_default);

            }
        });
    }

    function add() {
        var xMod;
        var FieldCountMod;

        var MaxInputsMod = 200; //maximum input boxes allowed
        var conteo = $('#conteo_defaultMod').val();
        var conteoNoMod = $('#conteo_defaultNoMod').val();
        xMod = parseInt(conteo) + 1;
        FieldCountMod = xMod - 1; //to keep track of text box added

        if (xMod <= MaxInputsMod) { //max input box allowed
            FieldCountMod++; //text box added increment
            conteoNoMod++;

            $("#contenedorMod").append("<table> <tr> <td style='width: 98%'> <input style='margin-top: .5em;' type='text' name='mitexto_" + conteoNoMod + "' id='campo_" + FieldCountMod + "' placeholder='Ingresar valor' class='form-control'>" +
                "</td> <td> <a href='#' class='eliminar' style='font-size: 18px; margin-top: .5em;'><i class='fa fa-trash-o' aria-hidden='true'></i></a>" +
                "</td> </tr> </table>");

            $('#conteo_defaultMod').val(FieldCountMod);
            $('#conteo_defaultNoMod').val(conteoNoMod);
            xMod++;
        }
    }

    $("body").on("click", ".eliminar", function (e) { //user click on remove text
        var FieldCountMod = $('#conteo_defaultMod').val();
        var xMod = FieldCountMod + 1;
        if (xMod > 1) {
            //$(this).parent('div').remove(); //remove text box
            $(this).parent().parent().parent().parent().remove();
            FieldCountMod--;
            $('#conteo_defaultMod').val(FieldCountMod);
        }
        return false;
    });


</script>


<div class="modal fade" id="myModalCampo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Nuevo Campo</h4>

                <form action="<?php echo $helper->url("Campos", "guardarnuevo"); ?>" method="post">
                    <input type="hidden" name="id_Proyecto"
                           value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>"/>
                    <div class="form-group">
                        <label>Nombre: </label>
                        <input type="text" name="nombre_Campo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Tipo reactivo: </label>
                        <select name="tipo_Reactivo_Campo" id="reactivo_campo" class="form-control"
                                onchange="val(this.id)">
                            <option value="NULL">Seleccione tipo de reactivo</option>
                            <option name="text" id="1" value="text">Texto</option>
                            <option name="textarea" id="2" value="textarea">Texto largo</option>
                            <option name="radio" id="3" value="radio">Una opcion</option>
                            <option name="checkbox" id="4" value="checkbox">Check list</option>
                            <option name="number" id="5" value="number">Número</option>
                            <option name="date" id="6" value="date">Fecha</option>
                            <option name="time" id="7" value="time">Hora</option>
                            <option name="select" id="8" value="select">Menú</option>
                            <option name="file" id="9" value="file">Imagen</option>
                            <option name="label" id="10" value="label">Etiqueta</option>
                            <option name="label" id="11" value="checkbox-incidencia">Incidencia</option>
                            <option name="label" id="12" value="check_list_asistencia">Asistencia</option>
                            <option name="label" id="13" value="text-cadenamiento">Cadenamiento</option>
                            <option name="select" id="14" value="rango_fechas">Rango de Fechas</option>
                            <option name="select" id="15" value="select-tabla">General(Tabla)</option>
                            <option name="decimal" id="16" value="decimal">Decimal</option>
                        </select>
                    </div>


                    <div id="valores" class="form-group" hidden>
                        <div id="contenedor">
                            <div class="added">
                                <table>
                                    <tr>
                                        <td style="width: 98%">
                                            <input type="text" name="mitexto_1" id="campo_1"
                                                   placeholder="Ingresar valor" class="form-control"/>
                                        </td>
                                        <td>
                                            <a href="#" class="eliminar"
                                               style="font-size: 18px; margin-top: .5em;">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 1em;">
                            <div class="col-sm-12">
                                <a id="agregarCampo" href="#" style="font-size: 25px;">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>


                    <div id="cantidad" class="form-group" hidden>
                        <label>Cantidad:</label>
                        <input type="number" name="cantImg" id="cantImg" class="form-control">
                    </div>


                    <input type="hidden" name="conteo_default" id="conteo_default" value=""/>
                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnGuardar" disabled>
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalModificarCampos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="margin-top:0%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="tituloModal" style="text-align: center"></h4>

                <form action="<?php echo $helper->url("Campos", "guardarmodificacion"); ?>" method="post" id="formMod">
                    <input type="hidden" name="id_Proyecto" value="<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>">

                    <input type="hidden" name="id_Campo_Reporte" id="idCampoReporte">


                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre_Campo" id="nombreCampo" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label>Tipo reactivo:</label>
                        <select name="tipo_Reactivo_Campo" id="reactivo_campoMod" class="form-control"
                                onchange="val(this.id)">
                        </select>
                    </div>


                    <div id="valoresMod" class="form-group" hidden>
                        <div id="contenedorMod">
                        </div>

                        <div class="row" style="margin-top: 1em;">
                            <div class="col-sm-12">
                                <a id="agregarCampoMod" href="#" onclick="add()" style="font-size: 25px;"
                                   data-trigger="hover" data-content="Agregar opción" data-toggle="popover">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                            </div>
                        </div>

                    </div>


                    <div id="cantidadMod" class="form-group" hidden>
                        <label class="control-label">Cantidad:</label>
                        <input type="number" name="cantImg" id="cantImgMod" class="form-control">
                    </div>


                    <input type="hidden" name="conteo_default" id="conteo_defaultMod">

                    <input type="hidden" name="conteo_defaultNoMod" id="conteo_defaultNoMod">

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


<?php if (($action == "index") || ($action == "modificar")) { ?>
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
                        <? if (getAccess(64, $decimal)) { ?>
                            <a href="#"
                               data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                               onclick="popover('myModalCampo')" class="px-2 m-1 h4 text-white">
                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                            </a>
                        <? } ?>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th>No.</th>
                            <!--<th style="width:15%;">Proyecto</th>-->
                            <th>Nombre del Campo</th>
                            <th>Tipo del Reactivo</th>
                            <th>Valor Predeterminado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        //var_dump($allcampos);
                        if (is_array($allcampos) || is_object($allcampos)) {
                            $contadorCampos = 1;
                            foreach ($allcampos as $campo) { ?>
                                <tr>
                                    <td><?php echo $contadorCampos; ?></td>
                                    <!--<td><?php /*echo $campo->nombre_Proyecto; */ ?></td>-->
                                    <td><?php echo $campo->nombre_Campo; ?></td>
                                    <td>
                                        <?php echo $this->nombreCampo($campo->tipo_Reactivo_Campo); ?>
                                    </td>
                                    <td><?php echo $campo->Valor_Default; ?></td>

                                    <td>
                                        <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                           onclick="obtenerDatos(<?php echo $campo->id_Campo_Reporte; ?>)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a> &nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?php echo $campo->id_Campo_Reporte; ?>, 'id_Campo_Reporte', '<?php echo $campo->nombre_Campo; ?>', 'Campos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                    </td>
                                </tr>
                                <?php
                                $contadorCampos++;
                            }
                            ?>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
