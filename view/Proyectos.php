<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>
<link href="vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<script src="vendor/kartik-v/bootstrap-fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/themes/fas/theme.min.js"></script>
<script src="vendor/kartik-v/bootstrap-fileinput/js/locales/es.js"></script>

<script>
    $(() => {
        let insercion = <?php echo $insercion; ?>;
        let elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        $("#logoUno").fileinput({
            showUpload: false,
            'language': 'es',
            'theme': 'fas',
            dropZoneEnabled: false,
            maxFileCount: 1,
            maxFileSize: 500,
            validateInitialCount: true,
            overwriteInitial: false,
            initialPreviewAsData: true,
            allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"]
        });

        $("#logoDos").fileinput({
            showUpload: false,
            'language': 'es',
            'theme': 'fas',
            dropZoneEnabled: false,
            maxFileCount: 1,
            maxFileSize: 500,
            validateInitialCount: true,
            overwriteInitial: false,
            initialPreviewAsData: true,
            allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"]
        });


        //$('#logoUnoMod').fileinput();
        $("#logoUnoMod").fileinput({
            showUpload: false,
            'language': 'es',
            'theme': 'fas',
            dropZoneEnabled: false,
            showRemove: false,
            maxFileCount: 1,
            maxFileSize: 2048,
            overwriteInitial: true,
            initialPreviewAsData: true,
            allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"],
            purifyHtml: true
        });

        $("#logoDosMod").fileinput({
            showUpload: false,
            'language': 'es',
            'theme': 'fas',
            dropZoneEnabled: false,
            showRemove: false,
            maxFileCount: 1,
            maxFileSize: 2048,
            overwriteInitial: true,
            initialPreviewAsData: true,
            allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"],
            purifyHtml: true
        });

        $("#modalModificar").on("hidden.bs.modal", function () {
            $("#frmModProyecto").trigger("reset");
        });
    });

    function preload(idProyecto) {
        popover('modalModificarProyecto');
        /** @typedef {Object} response **/
        /** @namespace fileinput() **/
        /** @property {string} fileInput **/
        /** @property {string} proyecto **/
        /** @property {string} id_Proyecto **/
        /** @property {string} nombre_Proyecto **/
        /** @property {string} descripcion_Proyecto **/
        /** @property {string} logos **/
        /** @property {string} primary **/
        /** @property {string} secondary **/
        $.ajax({
                data: {idProyecto: idProyecto},
                url: "./index.php?controller=Proyectos&action=getLogos",
                method: "POST",
                success: function (response) {
                    let respuestaJSON = JSON.parse(response);
                    let fileInput = respuestaJSON.fileInput;
                    let ProyectoActual = respuestaJSON.proyecto;
                    $('#idProyectoMod').val(ProyectoActual.id_Proyecto);
                    $('#proyectoNombreMod').val(ProyectoActual.nombre_Proyecto);
                    $('#proyectoDescripcionMod').val(ProyectoActual.descripcion_Proyecto);

                    let previewLogos = [];
                    let maxFilePrimary = 1, maxFileSecondary = 1, previewConfig = [], deleteUrl = [];
                    if (ProyectoActual.logos != null) {
                        let logos = JSON.parse(ProyectoActual.logos);
                        if (logos.primary !== '') {
                            previewLogos = [logos.primary];
                            previewConfig = [fileInput.primary.initialPreviewConfig];
                            deleteUrl = [fileInput.primary.initialPreviewConfig.url];
                            maxFilePrimary = 2;

                            $("#logoUnoMod").fileinput('destroy').fileinput({
                                showUpload: false,
                                'language': 'es',
                                'theme': 'fas',
                                deleteUrl: deleteUrl[0],
                                dropZoneEnabled: false,
                                showRemove: false,
                                maxFileCount: maxFilePrimary,
                                maxFileSize: 2048,
                                overwriteInitial: true,
                                initialPreview: [previewLogos[0]],
                                initialPreviewConfig: [previewConfig[0]],
                                initialPreviewAsData: true,
                                allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"],
                                purifyHtml: true
                            });
                        }

                        if (logos.secondary !== '') {
                            previewLogos[1] = logos.secondary;
                            previewConfig[1] = fileInput.secondary.initialPreviewConfig;
                            deleteUrl[1] = fileInput.secondary.initialPreviewConfig.url;
                            maxFileSecondary = 2;

                            $("#logoDosMod").fileinput('destroy').fileinput({
                                showUpload: false,
                                'language': 'es',
                                'theme': 'fas',
                                deleteUrl: deleteUrl[1],
                                dropZoneEnabled: false,
                                showRemove: false,
                                maxFileCount: maxFileSecondary,
                                maxFileSize: 2048,
                                overwriteInitial: true,
                                initialPreview: [previewLogos[1]],
                                initialPreviewConfig: [previewConfig[1]],
                                initialPreviewAsData: true,
                                allowedFileExtensions: ["jpg", "png", "jpeg", "bmp"],
                                purifyHtml: true
                            });
                        }
                    }
                }
            }
        );
    }
</script>

<div class="modal fade" id="modalModificarProyecto" tabindex="-1" role="dialog" aria-labelledby="modalModTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="modalModTitle" style="text-align: center">Modificar Proyecto</h4>

                <div class="content">
                    <form id="frmModProyecto" action="<?= $helper->url("Proyectos", "guardarmodificacion"); ?>"
                          method="post"
                          enctype="multipart/form-data">
                        <input type="hidden" name="proyectoid" id="idProyectoMod"
                               value="<?= $datosproyecto->id_Proyecto; ?>">

                        <div class="form-group">
                            <label for="proyectoNombreMod">Nombre </label>
                            <input type="text" name="proyectonombre" id="proyectoNombreMod"
                                   value="<?= $datosproyecto->nombre_Proyecto; ?>"
                                   class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="proyectoDescripcionMod">Descripción </label>
                            <input type="text" name="proyectodescripcion" id="proyectoDescripcionMod"
                                   class="form-control"
                                   value="<?= $datosproyecto->descripcion_Proyecto; ?>" required>
                        </div>

                        <div class="fallback">
                            <div class="form-group">
                                <label for="logoUnoMod">Logotipo Uno</label>
                                <div class="file-loading">
                                    <input name="logoUno" type="file" id="logoUnoMod" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="fallback">
                            <div class="form-group">
                                <label for="logoDosMod">Logotipo Dos</label>
                                <div class="file-loading">
                                    <input name="logoDos" type="file" id="logoDosMod" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="btnGuardar"
                                    style="text-align: center;">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModalAddProyectos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 text-white bg-gradient-secondary">
                <h5 class="modal-title text-center w-100" id="myModalLabel">Crear Nuevo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <form id="frm_nuevoProyecto"
                  action="<?= $helper->url("Proyectos", "guardarnuevo"); ?>"
                  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="proyectonombre">Nombre</label>
                        <input type="text" name="proyectonombre" id="proyectonombre" class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="proyectodescripcion">Descripción</label>
                        <input type="text" name="proyectodescripcion" id="proyectodescripcion"
                               class="form-control" required>
                    </div>

                    <div class="fallback">
                        <div class="form-group">
                            <label for="logoUno">Logotipo Uno</label>
                            <div class="file-loading">
                                <input name="logoUno" type="file" id="logoUno" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="fallback">
                        <div class="form-group">
                            <label for="logoDos">Logotipo Dos</label>
                            <div class="file-loading">
                                <input name="logoDos" type="file" id="logoDos" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="btnGuardar">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
/*--- ACCION INDEX: MUESTRA TODOS LOS PROYECTOS ---*/
if (($action == "index") || ($action == "modificar")) { ?>

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
                           href="#"
                           data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                           onclick="popover('myModalAddProyectos')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Nombre</th>
                            <th class="align-middle">Descripcion</th>
                            <th class="align-middle">Estatus</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allproyectos) || is_object($allproyectos)) {
                            foreach ($allproyectos as $proyecto) { ?>
                                <tr>
                                    <td><?= $proyecto->id_Proyecto; ?></td>
                                    <td><?= $proyecto->nombre_Proyecto; ?></td>
                                    <td><?= $proyecto->descripcion_Proyecto; ?></td>
                                    <td><?= $proyecto->id_Status_Proyecto; ?></td>
                                    <td>
                                        <a href="#" data-trigger="hover" data-content="Modificar" data-toggle="popover"
                                           onclick="preload(<?= $proyecto->id_Proyecto; ?>)">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $proyecto->id_Proyecto; ?>, 'proyectoid', '<?= $proyecto->nombre_Proyecto; ?>', 'Proyectos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a>
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
