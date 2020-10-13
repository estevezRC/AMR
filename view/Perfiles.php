<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>


<script>
    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);


        $('.btn_modificar_perfil').on('click', function () {
            var id_perfil = $(this).attr('id');
            $.ajax({
                url: 'index.php?controller=Perfiles&action=modificar',
                method: 'POST',
                data: {perfilid: id_perfil},
                dataType: 'JSON',
                success: function (data) {
                    $('#idPerfilUsuarioM').val(data['data'].id_Perfil_Usuario);
                    $('#nombrePerfilM').val(data['data'].nombre_Perfil);
                }
            });
            $('#myModalModificarPerfiles').modal();
        });

        $('#formModificarPerfil').on('submit', function (event) {
            event.preventDefault();
            console.log('Entra al submit');
            $.ajax({
                url: 'index.php?controller=Perfiles&action=guardarmodificacion',
                method: 'POST',
                dataType: 'JSON',
                data: $(this).serialize(),
                beforeSend: function () {
                    console.log('Entra el Before');
                },
                success: function (data) {
                    if (data.status)
                        alertify.success(data.mensaje);
                    else
                        alertify.error(data.mensaje);

                    setTimeout(function () {
                        location.reload();
                    }, 2100);
                }
            });
        })

    });
</script>


<!-- ACCION CREAR: REGISTRA NUEVO perfil-->
<div class="modal fade" id="myModalPerfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Nuevo Perfil </h4>

                <form action="<?php echo $helper->url("Perfiles", "guardarnuevo"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label">Nombre:</label>
                    <input type="text" name="nombre_Perfil" class="form-control" required>

                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-floppy-o" aria-hidden="true"> </i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalModificarPerfiles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!--<button type="button" class="close"
                        onclick="location.href='index.php?controller=Perfiles&action=index';" data-dismiss="modal2"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>-->

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar Perfil </h4>

                <form id="formModificarPerfil" class="form-horizontal">
                    <input type="hidden" name="id_Perfil_Usuario" id="idPerfilUsuarioM"/>

                    <label class="control-label">Nombre:</label>
                    <input type="text" name="nombre_Perfil" id="nombrePerfilM" class="form-control" required>

                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-danger"> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
/*--- ACCION INDEX: MUESTRA TODOS LOS perfiles ---*/
if (($action == "index")) { ?>
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
                           onclick="popover('myModalPerfil')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="text-light bg-primary">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Nombre</th>
                            <th class="align-middle">Empresa</th>
                            <th class="align-middle">Fecha</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allperfiles) || is_object($allperfiles)) {
                            $contadorPerfiles = 1;
                            foreach ($allperfiles as $perfil) { ?>
                                <tr>
                                    <td><?= $contadorPerfiles; ?></td>
                                    <td><?= $perfil->nombre_Perfil; ?></td>
                                    <td><?= $perfil->nombre_Empresa; ?></td>
                                    <td><?= $perfil->fecha_Registro_Perfil; ?></td>
                                    <td class="text-center">
                                        <a href="#" id="<?= $perfil->id_Perfil_Usuario; ?>" class="btn_modificar_perfil"
                                           data-trigger="hover" data-content="Modificar" data-toggle="popover">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $perfil->id_Perfil_Usuario; ?>, 'id_Perfil_Usuario', '<?= $perfil->nombre_Perfil; ?>', 'Perfiles','borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;

                                        <a href="index.php?controller=Permisos&action=index&id_Perfil_Usuario=<?= $perfil->id_Perfil_Usuario; ?>"
                                           data-trigger="hover" data-content="Ver Permisos" data-toggle="popover">
                                            <i class="fa fa-key" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <? $contadorPerfiles++;
                            } ?>
                        <? } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<? } ?>
