<link href="vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<script src="vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/themes/fas/theme.min.js"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/js/locales/es.js"></script>

<script type="application/javascript">
    // Una vez cargado el DOM se ejecutará la siguiente función
    window.addEventListener("load", function () {
        // Aplica para la vista de Modificar y Llenar, se activan los input de tipo file con la libreria
        $('input[type="file"]').fileinput({
            showUpload: false,
            'language': 'es',
            'theme': 'fas',
            dropZoneEnabled: false,
            maxFileCount: 1,
            maxFileSize: 20971520,
            validateInitialCount: true,
            overwriteInitial: false,
            initialPreviewAsData: true
        });

        // Aplica para la vista de Modificar Reporte
        if (document.getElementById("fotos_tomadas")) {
            const fotosPrecargadas = document.getElementById("fotos_tomadas").value;

            for (let index = 0; index < fotosPrecargadas; index++) {
                const foto = document.getElementById("nombreimg" + (index + 1));
                console.log(foto);
                // Obtener las URL's de cada foto
                const urlFoto = foto.dataset.urlimg;
                const urlErase = foto.dataset.urlerase;

                // Reiniciar el input con el archivo que tenga cargado
                $('#nombreimg' + (index + 1)).fileinput('destroy').fileinput({
                    showUpload: false,
                    'language': 'es',
                    'theme': 'fas',
                    deleteUrl: urlErase,
                    dropZoneEnabled: false,
                    showRemove: false,
                    maxFileCount: 2,
                    maxFileSize: 20971520,
                    validateInitialCount: true,
                    overwriteInitial: true,
                    initialPreview: urlFoto,
                    initialPreviewAsData: true
                }).on('filedeleted', () => {
                    location.reload();
                });
            }
        }
    });

    function validaNumericos() {
        var inputtxt = document.getElementById('hora');
        var valor = inputtxt.value;
        var checkOK = "0123456789:";
        var checkStr = valor;
        var allValid = true;
        var decPoints = 0;
        var nuevoString = "";
        for (i = 0; i < checkStr.length; i++) {
            ch = checkStr.charAt(i);
            for (j = 0; j < checkOK.length; j++) {
                if (ch == checkOK.charAt(j)) {
                    nuevoString = nuevoString + ch;
                }
            }
        }
        inputtxt.value = nuevoString;
    }

    function retornar() {
        window.history.back();
    }
</script>


<!-- ********************* Modal para vincular reporte nuevo a reporte padre *********************************** -->
<div class="modal fade" id="myModalGantt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white justify-content-center">
                <h4 class="modal-title text-center w-100" id="myModalLabel"> Vincular con actividades del Proyecto </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="mt-3" id="spinnerBorrar" style="display: none">

                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <h6> Cargando información </h6>
                    </div>

                </div>

                <div class="mt-3" id="elementosEsquema" style="display: none;">

                    <ul id="contenedorElementos" class="list-group list-group-flush"></ul>

                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-w-m btn-warning" id="btnRegresarVinvularGralEspecifico">
                    <!--onclick="quitarPosiciones()" -->
                    Regresar
                </button>
                <a href="#" class="btn btn-w-m btn-danger" id="btnNoVincular">
                    <!--onclick="quitarPosiciones()" -->
                    No vincular
                </a>
            </div>
        </div>
    </div>
</div>
<!-- ********************* Modal para vincular reporte nuevo a reporte padre *********************************** -->

<?php
/*--- ACCION INDEX: MUESTRA TODOS LOS CAMPOS ---*/
$id_Reporte = $_GET['Id_Reporte'];
if ($action == "index"){ ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-6 p-0 shadow">
                <div class="w-100 d-flex justify-content-between bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                </div>
                <div class="container-fluid p-2">
                    <? if ($existeGantt) { ?>
                        <form action="#" id="formLlenadosReporte" method="post" class="form-horizontal">
                            <input type="hidden" value="<?= $_GET['tipo']; ?>" name="tipo_Reporte"/>
                            <div class="form-group">
                                <label for="Id_Reporte"></label>
                                <? if ($_GET['tipo'] == '0,1') { ?>
                                    <select name="Id_Reporte" id="Id_Reporte" class="custom-select">
                                        <optgroup label="Reportes de Actividades">
                                            <? if (!empty($allreportes)) {
                                                foreach ($allreportes as $reporte) { ?>
                                                    <option name="<?= $reporte->nombre_Reporte; ?>"
                                                            id="<?= $reporte->id_Reporte; ?>"
                                                            value="<?= $reporte->id_Reporte; ?>"><?= $reporte->nombre_Reporte; ?></option>
                                                <? }
                                            } else { ?>
                                                <option value="0"> No hay otros Reportes</option>
                                            <? } ?>
                                        </optgroup>

                                        <optgroup label="Otros Reportes">
                                            <? if (!empty($allreporteNoEsquema)) {
                                                foreach ($allreporteNoEsquema as $reporte) { ?>
                                                    <option name="<?= $reporte->nombre_Reporte; ?>"
                                                            id="<?= $reporte->id_Reporte; ?>"
                                                            value="<?= $reporte->id_Reporte; ?>"><?= $reporte->nombre_Reporte; ?></option>
                                                <? }
                                            } else { ?>
                                                <option value="0"> No hay otros Reportes</option>
                                            <? } ?>
                                        </optgroup>
                                    </select>
                                <? } else { ?>
                                    <select name="Id_Reporte" id="Id_Reporte" class="custom-select">
                                        <? foreach ($allreportes as $reporte) {
                                            if ($reporte->nombre_Reporte != $datoscampo->nombre_Reporte) { ?>
                                                <option name="<?= $reporte->nombre_Reporte; ?>"
                                                        id="<?= $reporte->id_Reporte; ?>"
                                                        value="<?= $reporte->id_Reporte; ?>"><?= $reporte->nombre_Reporte; ?></option>
                                            <? }
                                        } ?>
                                    </select>
                                <? } ?>
                            </div>

                            <div class="form-group float-right">
                                <button type="button" class="btn btn-w-m btn-danger" href="#" data-toggle="modal"
                                        data-target="#myModalGantt" id="btnLlenar">
                                    <!-- onclick="getAllReportesGeneralEspecifico()"-->
                                    Llenar
                                </button>
                            </div>
                        </form>
                    <? } else { ?>
                        <form action="index.php?controller=LlenadosReporte&action=mostrarreportenuevo"
                              id="formLlenadosReporte"
                              method="post" class="form-horizontal">
                            <input type="hidden" value="<?php echo $_GET['tipo']; ?>" name="tipo_Reporte">
                            <div class="form-group">
                                <label for="Id_Reporte">Escoge Reporte</label>
                                <select name="Id_Reporte" id="Id_Reporte" class="custom-select">
                                    <?php foreach ($allreportes as $reporte) {
                                        if ($reporte->nombre_Reporte != $datoscampo->nombre_Reporte) { ?>
                                            <option name="<?php echo $reporte->nombre_Reporte; ?>"
                                                    id="<?php echo $reporte->id_Reporte; ?>"
                                                    value="<?php echo $reporte->id_Reporte; ?>"><?php echo $reporte->nombre_Reporte; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-w-m btn-danger"> Llenar</button>
                                </div>
                            </div>
                        </form>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/llenados.reporte/general-especifico.js"></script>
<?php }else{
/*--------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------- LLENAR REPORTE -----------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------*/
if ($action == "mostrarreporte" || $action == "mostrarreportenuevo") {
    $accion = "guardarreportellenado";
} else if ($action == "modificarreporte") {
    $accion = "guardarmodificacionreportellenado";
} ?>

<div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row pt-3 d-flex justify-content-center">
        <div class="col-11 p-0 shadow">
            <div class="w-100 d-flex justify-content-between bg-gradient-secondary rounded-top">
                <div class="col-10 d-flex align-items-center">
                    <h4 class="text-white m-0">
                        <?php echo $mensaje; ?>
                    </h4>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <div class="text-center">
                        <button type="submit" form="<?php echo $accion ?>" value='guardar'
                                class="btn" id="send" data-trigger="hover" data-content="Guardar"
                                data-toggle="popover">
                            <span class="h3 text-white"><i class="fa fa-floppy-o"></i></span>
                        </button>


                        <?php if ($accion == 'guardarmodificacionreportellenado') {
                            if ($allcamposreportes[0]->tipo_Reporte == 5) { ?>
                                <a href="#" class="btn" onclick="retornar()" data-trigger="hover"
                                   data-content="Cancelar" data-toggle="popover">
                                    <span class="h3 text-white"> <i class="far fa-window-close"></i></span>
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $urlAnterior ?>" class="btn" data-trigger="hover"
                                   data-content="Cancelar" data-toggle="popover">
                                    <span class="h3 text-white"> <i class="far fa-window-close"></i></span> </a>
                            <?php }
                        } ?>

                    </div>
                </div>
            </div>
            <div class="p-2">
                <?php if ($allcamposreportes[0]->tipo_Reporte == 5) { ?>
                    <div class="row">
                        <div class="col-sm-12" style="padding: 0 60px;">
                            <img src="img/formatoCodigoPlanos.png" alt="ejemploTituloPlanos" width="60%">
                        </div>
                    </div>
                <?php }
                if ($action == "mostrarreportenuevo") { ?>
                    <form action="<?php echo $helper->url("LlenadosReporte", $accion); ?>" method="post"
                          enctype="multipart/form-data" id="guardarreportellenado" class="form-reporte">
                        <input type="hidden" name="id_Reporte" value="<?php echo $allcamposreportes[0]->id_Reporte; ?>">
                        <input type="hidden" name="Id_Gpo_Sistema"
                               value="<?php echo $allcamposreportes["id_Gpo_Sistema"]; ?>">
                        <?php include 'view/formularios_llenar/Reporte_Generico_Llenar.php'; ?>
                    </form>
                <?php }
                /*--------------------------------------------------------------------------------------------------------------------*/
                /*---------------------------------------------------- MODIFICAR REPORTE ---------------------------------------------*/
                /*--------------------------------------------------------------------------------------------------------------------*/
                if ($action == "modificarreporte") { ?>
                    <form action="<?php echo $helper->url("LlenadosReporte", $accion); ?>" method="post"
                          enctype="multipart/form-data" id="guardarmodificacionreportellenado" class="form-reporte">
                        <?php
                        include 'view/formularios_llenar/Reporte_Generico_Modificar2.php';
                        ?>
                        <input type="hidden" name="id_Reporte"
                               value="<?php echo $allcamposreportes[0]->id_Reporte; ?>"/>
                        <input type="hidden" name="Id_Gpo_Sistema"
                               value="<?php echo $allcamposreportes["id_Gpo_Sistema"]; ?>"/>
                    </form>

                    <script src="js/llenados.reporte/general-especifico.js"></script>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php } ?>
</div>

<script src="js/campo_especial.js"></script>
<script>
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    function success(pos) {
        var crd = pos.coords;

        //console.log('More or less ' + crd.accuracy + ' meters.');
        $("#latitudm").val(crd.latitude);
        $("#longitudm").val(crd.longitude);
    }

    function error(err) {
        console.warn('ERROR(' + err.code + '): ' + err.message);
        alert("Geolocalización desactivada, no se obtuvieron coordenadas!");
    }

    var accion = '<?php echo $action; ?>';
    if (accion == 'mostrarreportenuevo') {
        navigator.geolocation.getCurrentPosition(success, error, options);

    }

    $(".form-reporte").submit(function () {
        multiple.armarCampoEspecial();

        const inpFechaInicial = document.querySelector('#fecha_inicial'),
            inpFechaFinal = document.querySelector('#fecha_final'),
            containerAlerta = document.querySelector("#container_alert");

        if ($("#validarCheckbox").val() == "required") {
            $('input:checkbox').removeAttr("required");
        }

        if ($('input:checkbox').filter(':checked').length < 1 && $('input:checkbox').length) {
            alert("Selecciona al menos una opción del campo " + $("#labelCheckbox").text());
            return false;
        }

        if (moment(inpFechaInicial.value).format('YYYY-MM-DD') > moment(inpFechaFinal.value).format('YYYY-MM-DD')) {
            containerAlerta.innerHTML = "La fecha inicial es mayor a la fecha final.";
            containerAlerta.classList.replace('invisible', 'visible');
            return false;
        }
    });

    $(document).ready(function () {
        $('.select-asistencia').select2({
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    });
</script>
