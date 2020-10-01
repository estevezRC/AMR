<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

<!-- *************************** MODAL PARA NUEVO PARTICIPANTE *************************** -->
<div class="modal fade" id="myModalAddParticipantes" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="myModalLabel"> Nuevo Participante </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="nuevo_participante">

                    <? if ($allUserSinRegistrar) { ?>
                        <div class="form-group">
                            <input type="checkbox" value="" id="check_empleados" onclick="showSelectEmpleados()">
                            <label for="check_empleados" class="font-weight-bold">
                                Escoger desde empleado existente
                            </label>
                        </div>

                        <div class="form-group d-none" id="containerSelectEmpleados">
                            <label for="empleados" class="col-form-label">
                                Empleados
                            </label>
                            <select id="empleados" name="id_empleado" class="custom-select" required
                                    onchange="insertFullName(this)">
                                <option value="0" disabled selected>Selecciona ...</option>
                                <? array_map(function ($user) {
                                    echo "<option value='$user->id_usuario' data-nombre='$user->nombre' 
                                    data-appaterno='$user->apellido_paterno' data-apmaterno='$user->apellido_materno'>
                                                                        $user->nombre $user->apellido_paterno $user->apellido_materno 
                                                                    </option>";
                                }, $allUserSinRegistrar); ?>
                            </select>
                        </div>
                    <? } ?>

                    <div class="form-group">
                        <label for="nombre" class="col-form-label">Nombre:
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="nombre" class="form-control" id="name">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Apellido Paterno
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="apellido_paterno" class="form-control" id="surnameP">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Apellido Materno
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="apellido_materno" class="form-control" id="surnameM">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Email
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="email" name="correo_Usuario" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Puesto
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="puesto" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Empresa
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="empresa" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-danger float-right my-auto shadow">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- *************************** END MODAL PARA NUEVO PARTICIPANTE *************************** -->


<!-- *************************** MODAL PARA MODIFICAR PARTICIPANTE *************************** -->
<div class="modal fade" id="myModalEditParticipantes" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="myModalLabel"> Modificar Participante </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="modificar_participante">

                    <input type="text" name="id" id="id-modificar" hidden>

                    <div class="form-group">
                        <label for="nombre" class="col-form-label">Nombre:
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="nombre" class="form-control" id="name-modificar">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Apellido Paterno
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="apellido_paterno" class="form-control" id="surnameP-modificar">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Apellido Materno
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="apellido_materno" class="form-control" id="surnameM-modificar">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Email
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="email" name="correo_Usuario" class="form-control" id="correo-modificar">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Puesto
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="puesto" class="form-control" id="puesto-modificar">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Empresa
                            <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <input type="text" name="empresa" class="form-control" id="empresa-modificar">
                    </div>

                    <button type="submit" class="btn btn-danger float-right my-auto shadow">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- *************************** END MODAL PARA MODIFICAR PARTICIPANTE *************************** -->

<div class="container-fluid py-4 flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row mt-3">
        <div class="col-11 shadow mx-auto">
            <div class="row bg-gradient-secondary rounded-top">
                <div class="col-sm-10 d-flex align-items-center">
                    <h4 class="text-white m-0 py-2">
                        <?= $mensaje ?>
                    </h4>
                </div>
                <div class="col-sm-2 d-flex justify-content-center align-items-center">
                    <a href="#" data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                       onclick="popover('myModalAddParticipantes')" class="px-2 m-1 h4 text-white">
                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-2">
                    <?php
                    switch ($action) {
                        case 'index': ?>
                            <div class="table-responsive-md">
                                <table id="example" class="table table-striped thead-dark">
                                    <thead class="bg-primary text-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Puesto</th>
                                        <th>Empresa</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (is_array($participantes) || is_object($participantes)) {
                                        foreach ($participantes as $key => $participante) { ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $participante->nombre ?></td>
                                                <td><?= $participante->correo_Usuario ?></td>
                                                <td><?= $participante->puesto ?></td>
                                                <td><?= $participante->empresa ?></td>
                                                <td>
                                                    <a href="#" data-trigger="hover" data-content="Modificar"
                                                       data-toggle="popover"
                                                       onclick="obtenerDatos(<?= $participante->id; ?>)">
                                                        <i class="fa fa-edit" aria-hidden="true"></i></a> &nbsp;

                                                    <a href="#" data-trigger="hover" data-content="Borrar"
                                                       data-toggle="popover"
                                                       onclick="borrarRegistroAjax(<?= $participante->id; ?>, 'id', '<?= $participante->nombre; ?>', 'Participantes', 'destroy')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                            <? break;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        email: "Formato del correo invalido"
    });

    jQuery.validator.setDefaults({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
            $(element).removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
        }
    });

    function sendData(data, event) {
        event.preventDefault();
        const datas = new FormData(data);

        const response = $.ajax({
            method: 'POST',
            url: 'index.php?controller=Participantes&action=store',
            contentType: false,
            data: datas,  // mandamos el objeto formdata que se igualo a la variable data
            processData: false,
            cache: false
        });

        response.done(function (data) {
            var response = $.parseJSON(data);
            console.log(response);

            if (response.status) {
                alertify.success(response.mensaje);
                setTimeout(() => {
                    document.location.href = "index.php?controller=Participantes&action=index";
                }, 2000);
            } else {
                alertify.error(response.mensaje);
            }
        });

    }

    const rules = {
        rules: {
            nombre: {
                required: true
            },
            apellido_paterno: {
                required: true
            },
            apellido_materno: {
                required: true
            },
            correo_Usuario: {
                required: true
            },
            puesto: {
                required: true
            },
            empresa: {
                required: true
            },
            id_empleado: {
                required: true
            }
        },
        submitHandler: sendData
    }

    $("#nuevo_participante").validate(rules);

    function showSelectEmpleados() {
        let containerSelectEmpleados = $('#containerSelectEmpleados');
        if ($('#check_empleados').prop('checked')) {
            containerSelectEmpleados.removeClass('d-none');
            containerSelectEmpleados.addClass('d-block');
        } else {
            containerSelectEmpleados.removeClass('d-block');
            containerSelectEmpleados.addClass('d-none');
            $('#empleados').val('0');
            cleanFullName();
        }
    }

    function insertFullName(sel) {
        const nombre = $('option:selected', sel).data("nombre");
        const appaterno = $('option:selected', sel).data("appaterno");
        const apmaterno = $('option:selected', sel).data("apmaterno");

        let name = $('#name');
        let surnameP = $('#surnameP');
        let surnameM = $('#surnameM');

        name.val(nombre);
        surnameP.val(appaterno);
        surnameM.val(apmaterno);
    }

    function cleanFullName() {
        $('#name').val('');
        $('#surnameP').val('');
        $('#surnameM').val('');
    }

    // ******************************** SECCION DE MODIFICAR ****************************
    function obtenerDatos(id) {
        const response = $.ajax({
            method: 'POST',
            url: 'index.php?controller=Participantes&action=show',
            data: {id: id}
        });

        response.done(function (data) {
            var response = $.parseJSON(data);
            if (response.status) {
                //console.log(response.participante);
                let datos = response.participante;
                $('#id-modificar').val(datos['id']);
                $('#name-modificar').val(datos['nombre']);
                $('#surnameP-modificar').val(datos['apellido_paterno']);
                $('#surnameM-modificar').val(datos['apellido_materno']);
                $('#correo-modificar').val(datos['correo_Usuario']);
                $('#puesto-modificar').val(datos['puesto']);
                $('#empresa-modificar').val(datos['empresa']);
                popover('myModalEditParticipantes');
            } else {
                alertify.error(response.mensaje);
            }
        });
    }

    function sendDataModificar(data, event) {
        event.preventDefault();
        const datas = new FormData(data);

        const response = $.ajax({
            method: 'POST',
            url: 'index.php?controller=Participantes&action=update',
            contentType: false,
            data: datas,  // mandamos el objeto formdata que se igualo a la variable data
            processData: false,
            cache: false
        });

        response.done(function (data) {
            var response = $.parseJSON(data);
            //console.log(response);
            if (response.status) {
                alertify.success(response.mensaje);
                setTimeout(() => {
                    document.location.href = "index.php?controller=Participantes&action=index";
                }, 2000);
            } else {
                alertify.error(response.mensaje);
            }
        });

    }

    const rulesModificar = {
        rules: {
            nombre: {
                required: true
            },
            apellido_paterno: {
                required: true
            },
            apellido_materno: {
                required: true
            },
            correo_Usuario: {
                required: true
            },
            puesto: {
                required: true
            },
            empresa: {
                required: true
            }
        },
        submitHandler: sendDataModificar
    }

    $("#modificar_participante").validate(rulesModificar);

</script>
