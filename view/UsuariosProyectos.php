<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>

    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        obtenerDatosProPer();

        $('.btn_modificar_proyecto').on('click', function(){
            var id_proyecto = $(this).attr('id');

            $.ajax({
                url: 'index.php?controller=UsuariosProyectos&action=modificar',
                method: 'POST',
                dataType: 'JSON',
                data: { usuarioproyectoid: id_proyecto},
                success: function(data) {

                    let selectPerfiles = $('#id_Perfil');
                    selectPerfiles.empty();
                    let x = 0;
                    let dataUser = data.data;
                    let perfiles = data.perfiles
                    $('#id_usuario_proyecto').val(dataUser.idUsuarios_Proyectos);
                    $('#labelUsuario').text(dataUser.nombre_Usuario + ' ' + dataUser.apellido_Usuario);
                    $('#id_Usuario').val(dataUser.id_Usuario);
                    $('#labelProyecto').text(dataUser.nombre_Proyecto);
                    $('#id_Proyecto').val(dataUser.id_Proyecto);
                    $.each(perfiles, function () {
                        if (perfiles[x].id_Perfil_Usuario == dataUser.id_Perfil_Usuario)
                            selectPerfiles.append(`<option value="${dataUser.id_Perfil_Usuario}" selected> ${dataUser.nombre_Perfil}</option>`);
                        else
                            selectPerfiles.append(`<option value="${perfiles[x].id_Perfil_Usuario}"> ${perfiles[x].nombre_Perfil} </option>`);
                        x++;
                    });

                    $('#myModal2').modal();
                }
            })
        } );

        $('#formUsuariosProyectos').on('submit', function (event) {
            let idUserProject = $('#id_usuario_proyecto').val();
            let idUser = $('#id_Usuario').val();
            let idProject = $('#id_Proyecto').val();
            let idProfile = $('#id_Perfil').val();
            var dataPost = {
                id_usuario_proyecto : idUserProject,
                id_Usuario : idUser,
                id_Proyecto : idProject,
                id_Perfil: idProfile
            }
            event.preventDefault();
            $.ajax({
                url: 'index.php?controller=UsuariosProyectos&action=guardarmodificacion',
                method: 'POST',
                dataType: 'JSON',
                data: dataPost,
                beforeSend: function () {
                    console.log('Envio Before: ');
                },
                success: function(data){
                    if(data.status) {
                        alertify.success(data.mensaje);
                    }else {
                        alertify.error(data.mensaje);
                    }

                    setTimeout(function () {

                        location.reload();
                    }, 2100);
                }
            });
        });


    });

    function obtenerDatosProPer() {
        var id_Usuario = $('#selectUsuarioPro').val();
        $.ajax({
            data: {id_UsuarioProPer: id_Usuario},
            url: "index.php?controller=UsuariosProyectos&action=getProyectosAndPerfiles",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON.length);
                var $secondChoice = $("#selectProyecto");
                if (respuestaJSON.length == 0) {
                    $secondChoice.empty();
                    $secondChoice.append("<option> El usuario ya esta en todos los proyectos </option>");
                    $("#nuevo_registro").attr("disabled", true);
                } else {
                    $secondChoice.empty();
                    x = 0;
                    $.each(respuestaJSON, function () {
                        $secondChoice.append("<option value='" + respuestaJSON[x]['id_Proyecto'] + "'>" + respuestaJSON[x]['nombre_Proyecto'] + "</option>");
                        x++;
                    });
                    $('#nuevo_registro').removeAttr("disabled");
                }
            }
        });
    }

</script>

<div class="modal fade" id="myModaladdUsuPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title text-center" id="myModalLabel">Asignar Usuario a Proyecto</h4>

                <form action="<?php echo $helper->url("UsuariosProyectos", "guardarnuevo"); ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal">

                    <label class="control-label">Usuario:</label>
                    <select name="id_Usuario" class="form-control" id="selectUsuarioPro"
                            onchange="obtenerDatosProPer()">
                        <?php
                        foreach ($allusuarios as $usuario) { ?>
                            <option name="<?php echo $usuario->nombre_Usuario; ?>"
                                    id="<?php echo $usuario->id_Usuario; ?>"
                                    value="<?php echo $usuario->id_Usuario; ?>"><?php echo $usuario->nombre_Usuario . " " . $usuario->apellido_Usuario; ?></option>
                        <?php } ?>
                    </select>


                    <label class="control-label">Proyecto:</label>
                    <select name="id_Proyecto" class="form-control" id="selectProyecto">

                    </select>


                    <label class="control-label">Perfil:</label>
                    <select name="id_Perfil" class="form-control" id="selectPerfil">
                        <?php
                        foreach ($allPerfiles as $perfil) { ?>
                            <option name="<?php echo $perfil->nombre_Perfil; ?>"
                                    id="<?php echo $perfil->id_Perfil_Usuario; ?>"
                                    value="<?php echo $perfil->id_Perfil_Usuario; ?>"><?php echo $perfil->nombre_Perfil; ?></option>
                        <?php } ?>
                    </select>

                    <br>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-w-m btn-danger" id="nuevo_registro">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <!--<button type="button" class="close"
                            onclick="location.href='index.php?controller=UsuariosProyectos&action=index';"
                            data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title text-center" id="myModalLabel">Modificar asignación</h4>

                    <form id="formUsuariosProyectos" class="form-horizontal">
                        <input type="hidden" name="id_usuario_proyecto" id="id_usuario_proyecto"/>


                        <label class="control-label">Usuario:</label>
                        <label
                            class="form-control labelPerfil" id="labelUsuario"> </label>
                        <input name="id_Usuario" id="id_Usuario" hidden>

                        <label class="control-label">Proyecto:</label>
                        <label
                            class="form-control labelPerfil" id="labelProyecto"></label>
                        <input name="id_Proyecto" id="id_Proyecto"  hidden>

                        <label class="control-label">Perfil:</label>
                        <select name="id_Perfil" class="form-control" id="id_Perfil">

                        </select>

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



<?php
/*--- ACCION INDEX: MUESTRA TODOS LOS USUARIO-PROYECTOS ---*/
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
                           href="#" data-trigger="hover" data-content="Nueva" data-toggle="popover"
                           onclick="popover('myModaladdUsuPro')">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Usuario</th>
                            <th class="align-middle">Proyecto</th>
                            <th class="align-middle">Perfil</th>
                            <th class="align-middle">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allusuariosproyectos) || is_object($allusuariosproyectos)) {
                            $contadorUP = 1;
                            foreach ($allusuariosproyectos as $usuarioproyecto) { ?>
                                <tr>
                                    <td><?= $contadorUP; ?></td>
                                    <td><?= $usuarioproyecto->nombre_Usuario . ' ' . $usuarioproyecto->apellido_Usuario; ?> </td>
                                    <td><?= $usuarioproyecto->nombre_Proyecto; ?></td>
                                    <td><?= $usuarioproyecto->nombre_Perfil; ?></td>
                                    <td>
                                        <a href="#" id="<?= $usuarioproyecto->idUsuarios_Proyectos; ?>" class="btn_modificar_proyecto"
                                           data-trigger="hover" data-content="Modificar" data-toggle="popover">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;

                                        <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                           onclick="borrarRegistro(<?= $usuarioproyecto->idUsuarios_Proyectos; ?>, 'usuarioproyectoid', '<?= $usuarioproyecto->nombre_Usuario; ?> <?= $usuarioproyecto->apellido_Usuario; ?> que esta en el proyecto <?= $usuarioproyecto->nombre_Proyecto; ?>', 'UsuariosProyectos', 'borrar')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;

                                    </td>
                                </tr>
                                <? $contadorUP++;
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
