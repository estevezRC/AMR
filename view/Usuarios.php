<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>

    $(document).ready(function () {

        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        var savekey = <?php echo $savekey ?>;
        if (savekey == 5) {
            alertify.success('Se ha creado la firma electronica con exito');
        }

        var notify = <?php echo $notify ?>;
        if (notify == 6) {
            alertify.success('Su Nip se ha guardado exitosamente');
        }

        var registrarNip = <?php echo $registrarNip ?>;
        if (registrarNip == 7) {
            alertify.alert('Nip de usuario', ' Registrar Nip para tener permiso de Firmar Reportes');
        }

        hiddenNames();
        hiddenCamposParticipantes();

        let action = '<?= $action ?>';
        if (action == 'modificar') {
            let participante = document.getElementById("participante").value;
            if (participante == 1)
                showCamposParticipantesM();
            else
                hiddenCamposParticipantesM();
        }

        //bloquearbtnNoEmpleados();
        validarDatos1(2);

        setTimeout(function () {
            $("#emailU, #pwd1, #pwd2").val('').change();
        }, 500);
    });

    function validarNip() {
        var nip = document.getElementById("nip").value;
        if (nip.length == 4)
            $('#btnGuardarNip').removeAttr("disabled");
        else {
            $("#btnGuardarNip").attr("disabled", true);
        }

    }

    function validarDatos1(accion) {
        var pw1 = document.getElementById("pwd1").value;
        var pw2 = document.getElementById("pwd2").value;

        var pwd11 = document.getElementById("pwd11").value;
        var pwd12 = document.getElementById("pwd12").value;

        if (accion == 1) {
            pw1 = pwd11;
            pw2 = pwd12;
        }

        var expresionR = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{6,12}$/;
        var resultado = expresionR.test(pw1);
        var resultado1 = expresionR.test(pw2);

        if (resultado == true && resultado1 == true) {
            if (pw1 == pw2) {
                $('#btnGuardar').removeAttr("disabled");
                $('#btnGuardar1').removeAttr("disabled");
            } else {
                $("#btnGuardar").attr("disabled", true);
                $("#btnGuardar1").attr("disabled", true);
            }
        } else {
            $("#btnGuardar").attr("disabled", true);
            $("#btnGuardar1").attr("disabled", true);
        }

    }

    function validarRadio() {
        if ($('input[name="participante"]').is(':checked')) {
        } else {
            alertify.error('Seleccionar una opción de Participante');
            return false;
        }
    }

    function bloquearbtnNoEmpleados() {
        let idEmp = document.getElementById("idEmp").value;

        if (idEmp == 0) {
            $("#btnGuardar").attr("disabled", true);
            $("#pwd1").attr("disabled", true);
            $("#pwd2").attr("disabled", true);
        } else {
            $('#btnGuardar').removeAttr("disabled");
            $('#pwd1').removeAttr("disabled");
            $('#pwd2').removeAttr("disabled");
        }
    }

    function hiddenNames() {
        let idEmp = document.getElementById("idEmp").value;

        if (idEmp != "a" && idEmp != 0) {
            $("#name").attr("hidden", true);
            $("#labelName").attr("hidden", true);

            $("#surnameP").attr("hidden", true);
            $("#labelSurnameP").attr("hidden", true);

            $("#surnameM").attr("hidden", true);
            $("#labelSurnameM").attr("hidden", true);
        } else {
            $('#name').removeAttr("hidden");
            $('#name').removeAttr("required");
            $('#labelName').removeAttr("hidden");


            $('#surnameP').removeAttr("hidden");
            $('#surnameP').removeAttr("required");
            $('#labelSurnameP').removeAttr("hidden");


            $('#surnameM').removeAttr("hidden");
            $('#surnameM').removeAttr("required");
            $('#labelSurnameM').removeAttr("hidden");
        }
    }

    function showCamposParticipantes() {
        let labelPuesto = $('#labelPuesto');
        let labelEmpresa = $('#labelEmpresa');
        let puesto = $('#puesto');
        let empresa = $('#empresa');

        labelPuesto.removeAttr("hidden");
        labelEmpresa.removeAttr("hidden");
        puesto.removeAttr("hidden");
        empresa.removeAttr("hidden");

        puesto.attr("required", true);
        empresa.attr("required", true);
    }

    function hiddenCamposParticipantes() {
        let labelPuesto = $('#labelPuesto');
        let labelEmpresa = $('#labelEmpresa');
        let puesto = $('#puesto');
        let empresa = $('#empresa');

        labelPuesto.attr("hidden", true);
        labelEmpresa.attr("hidden", true);
        puesto.attr("hidden", true);
        empresa.attr("hidden", true);

        puesto.removeAttr("required");
        empresa.removeAttr("required");
    }

    function showCamposParticipantesM() {
        let labelPuesto = $('#labelPuestoM');
        let labelEmpresa = $('#labelEmpresaM');
        let puesto = $('#puestoM');
        let empresa = $('#empresaM');

        labelPuesto.removeAttr("hidden");
        labelEmpresa.removeAttr("hidden");
        puesto.removeAttr("hidden");
        empresa.removeAttr("hidden");

        puesto.attr("required", true);
        empresa.attr("required", true);
    }

    function hiddenCamposParticipantesM() {
        let labelPuesto = $('#labelPuestoM');
        let labelEmpresa = $('#labelEmpresaM');
        let puesto = $('#puestoM');
        let empresa = $('#empresaM');

        labelPuesto.attr("hidden", true);
        labelEmpresa.attr("hidden", true);
        puesto.attr("hidden", true);
        empresa.attr("hidden", true);

        puesto.removeAttr("required");
        empresa.removeAttr("required");
    }

</script>

<div class="modal fade" id="myModalAddUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title text-center" id="myModalLabel"> Nuevo Usuario </h4>

                <form action="<?php echo $helper->url("Usuarios", "guardarnuevo"); ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal" onsubmit="return validarRadio()">

                    <label class="control-label">*Elegir desde empleado existente:</label><br>
                    <select name="id_emp" class="form-control" id="idEmp" onchange="hiddenNames()">
                        <?php if (!empty($allUserSinRegistrar)) { ?>
                            <option value="a"> Seleccionar empleado</option>
                            <? foreach ($allUserSinRegistrar as $user) { ?>
                                <option value="<?php echo $user->id_empleado; ?>">
                                    <?= "$user->nombre $user->apellido_paterno $user->apellido_materno"; ?>
                                </option>
                            <?php }
                        } else { ?>
                            <option value="0"> No hay más empleados</option>
                        <?php } ?>

                    </select>


                    <label class="control-label" id="labelName">*Nombre:</label>
                    <input type="text" name="name" class="form-control" id="name"
                           required>

                    <label class="control-label" id="labelSurnameP">*Apellido Paterno:</label>
                    <input type="text" name="surnameP" class="form-control" id="surnameP"
                           required>

                    <label class="control-label" id="labelSurnameM">*Apellido Materno:</label>
                    <input type="text" name="surnameM" class="form-control" id="surnameM"
                           required>


                    <label class="control-label">*Email:</label>
                    <input type="email" name="usuarioemail" class="form-control" id="emailU"
                           required>

                    <label class="control-label">*Contraseña(6-12 carácteres debe incluir 1 mayúscula y un
                        número): </label>

                    <label class="control-label">*Ingresar Contraseña:</label>
                    <input type="password" class="form-control" id="pwd1" onkeyup="validarDatos1(2)"
                           pattern="(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ].*"
                           placeholder="Ingresar Contraseña" required
                           title="Contraseña(6-12 carácteres debe incluir 1 mayúscula y un número)">

                    <label class="control-label">*Repetir Contraseña:</label>
                    <input type="password" class="form-control" id="pwd2" onkeyup="validarDatos1(2)"
                           name="usuariopassword"
                           pattern="(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ].*"
                           placeholder="Repetir Contraseña" required
                           title="Contraseña(6-12 carácteres debe incluir 1 mayúscula y un número)">

                    <label class="control-label">*Área:</label><br>
                    <select name="usuarioarea" class="form-control">
                        <?php foreach ($allareas as $area) { ?>
                            <option name="<?php echo $area->nombre_Area; ?>" id="<?php echo $area->id_Area; ?>"
                                    value="<?php echo $area->id_Area; ?>"><?php echo $area->nombre_Area; ?></option>
                        <?php } ?>
                    </select>

                    <label class="control-label">Participante:</label><br/>
                    <input type="radio" name="participante" id="partipante1" value="1"
                           onclick="showCamposParticipantes()"> Sí&nbsp;
                    <input type="radio" name="participante" id="partipante2" value="0"
                           onclick="hiddenCamposParticipantes()" checked> No

                    <br>
                    <label class="control-label" id="labelPuesto" hidden>*Puesto:</label>
                    <input type="text" name="puesto" class="form-control" id="puesto" hidden
                           required>

                    <label class="control-label" id="labelEmpresa" hidden>*Empresa:</label>
                    <input type="text" name="empresa" class="form-control" id="empresa" hidden
                           required>


                    <div class="row" style="margin-top: 1em;">
                        <hr style="border: 1px solid #166D9B; margin: 0px;">
                        <div class="col-sm-12" style="padding: 0px 15px 1em 15px; background-color: #f0f0f0">

                            <h6> Agregar Usuario a Proyecto </h6>

                            <label class="control-label">*Proyecto:</label><br>
                            <select name="usuarioproyecto" class="form-control">
                                <?php foreach ($allProyectos as $proyecto) { ?>
                                    <option name="<?php echo $proyecto->nombre_Proyecto; ?>"
                                            id="<?php echo $proyecto->id_Proyecto; ?>"
                                            value="<?php echo $proyecto->id_Proyecto; ?>"><?php echo $proyecto->nombre_Proyecto; ?></option>
                                <?php } ?>
                            </select>

                            <label class="control-label">*Perfil:</label><br>
                            <select name="usuarioperfil" class="form-control">

                                <?php foreach ($allPerfiles as $perfil) { ?>
                                    <option name="<?php echo $perfil->nombre_Perfil; ?>"
                                            id="<?php echo $perfil->id_Perfil_Usuario; ?>"
                                            value="<?php echo $perfil->id_Perfil_Usuario; ?>"><?php echo $perfil->nombre_Perfil; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <hr style="border: 1px solid #166D9B;margin: 0px;">
                    </div>

                    <br>

                    <div class="row">&nbsp;
                        <div class="col-md-12 text-right">
                            <button style="width: 24%" type="submit" id="btnGuardar" class="btn btn-w-m btn-danger"
                                    disabled>
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
/*--- ACCION MODIFICAR: EDITA UN USUARIO ---*/
if ($modificar == 1) {
    ?>
    <div class="modal modal-viejo" id="myModalModificarUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close"
                            onclick="location.href='index.php?controller=Usuarios&action=index';" data-dismiss="modal2"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>-->

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar Usuario</h4>

                    <form action="<?php echo $helper->url("Usuarios", "guardarmodificacion"); ?>" method="post"
                          enctype="multipart/form-data" class="form-horizontal" onsubmit="return validarRadio()">
                        <input type="hidden" name="usuarioid" value="<?php echo $datosusuario->id_Usuario; ?>"/>


                        <label class="control-label">*Nombre(s):</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= $datosusuario->nombre; ?>">

                        <label class="control-label">*Apellido Paterno:</label>
                        <input type="text" name="surnameP" class="form-control" required
                               value="<?= $datosusuario->apellido_paterno ?>">

                        <label class="control-label">*Apellido Materno:</label>
                        <input type="text" name="surnameM" class="form-control" required
                               value="<?= $datosusuario->apellido_materno ?>">


                        <label class="control-label">*Email:</label>
                        <input type="text" name="usuarioemail" value="<?php echo $datosusuario->correo_Usuario; ?>"
                               class="form-control" required/>

                        <input type="hidden" name="uploadedfile2" id="uploadedfil2"
                               value="<?php echo $datosusuario->fotografia_Usuario; ?>"/>

                        <label class="control-label">*Área:</label><br>
                        <select name="usuarioarea" class="form-control">
                            <option value="<?php echo $datosusuario->id_Area; ?>">
                                <?php echo $datosusuario->nombre_Area; ?>
                            </option>
                            <?php foreach ($allareas as $area) {
                                if ($area->nombre_Area != $datosusuario->nombre_Area) {
                                    ?>
                                    <option name="<?php echo $area->nombre_Area; ?>" id="<?php echo $area->id_Area; ?>"
                                            value="<?php echo $area->id_Area; ?>"><?php echo $area->nombre_Area; ?></option>
                                <?php }
                            } ?>
                        </select>

                        <label class="control-label">Participante:</label><br/>
                        <input type="radio" name="participante" onclick="showCamposParticipantesM()"
                               value="1" <?= $datosusuario->participante == 1 ? 'checked' : ''; ?>> Sí &nbsp;

                        <input type="radio" name="participante" onclick="hiddenCamposParticipantesM()"
                               value="0" <?= $datosusuario->participante == 0 ? 'checked' : ''; ?>> No

                        <input type="hidden" value="<?= $datosusuario->participante ?>" id="participante">

                        <br>
                        <label class="control-label" id="labelPuestoM">*Puesto:</label>
                        <input type="text" value="<?= $datosusuario->puesto; ?>" name="puesto" class="form-control"
                               id="puestoM" required>

                        <label class="control-label" id="labelEmpresaM">*Empresa:</label>
                        <input type="text" value="<?= $datosusuario->empresa; ?>" name="empresa" class="form-control"
                               id="empresaM" required>

                        <br/><br/>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-w-m btn-danger" id="btnModificar">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<? }
if ($modificar == 2) { ?>
    <div class="modal modal-viejo" id="myModalPwdUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <!--<button type="button" class="close"
                            onclick="location.href='index.php?controller=Usuarios&action=index';" data-dismiss="modal2"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>-->

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="myModalLabel">Password del usuario</h4>

                    <p><?php echo $datosusuario->password_Usuario; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php if (($action == "index") || ($action == "modificar") || ($action == "verpass")) { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <a href="#"
                           data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                           onclick="popover('myModalAddUsuarios')" class="px-2 m-1 h4 text-white">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <table id="example" class="table table-striped">
                        <thead class="bg-primary text-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Área</th>
                            <th>Participante</th>
                            <? if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1 || $_SESSION[ID_PERFIL_USER_SUPERVISOR] == 2) { ?>
                                <th>Acciones</th>
                            <? } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allusers) || is_object($allusers)) {
                            $contador = 1;
                            foreach ($allusers as $user) {
                                if ($user->id_Status_Usuario == 1) { ?>
                                    <tr>
                                        <td><?php echo $contador; ?></td>
                                        <td><?php echo $user->nombre_Usuario . " " . $user->apellido_Usuario; ?></td>
                                        <td><?php echo $user->correo_Usuario; ?></td>
                                        <td><?php echo $user->nombre_Area; ?></td>
                                        <td><?php echo $this->replaceSiNo($user->participante); ?></td>

                                        <?php if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1 || $_SESSION[ID_PERFIL_USER_SUPERVISOR] == 2) { ?>
                                            <td>

                                                <a href="index.php?controller=Usuarios&action=modificar&usuarioid=<?php echo $user->id_Usuario; ?>&insercion=<?php echo $insercion; ?>&newElemento=<?php echo $newElemento; ?>"
                                                   data-trigger="hover" data-content="Modificar" data-toggle="popover">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp;

                                                <? if ($user->id_Usuario != $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                                    ?>
                                                    <a href="#" data-trigger="hover" data-content="Borrar"
                                                       data-toggle="popover"
                                                       onclick="borrarRegistro(<?php echo $user->id_Usuario; ?>, 'usuarioid', '<?php echo $user->nombre_Usuario . " " . $user->apellido_Usuario; ?>', 'Usuarios', 'borrar')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                                    <?php
                                                } ?>

                                                <a href="index.php?controller=Usuarios&action=verpass&usuarioid=<?php echo $user->id_Usuario; ?>"
                                                   data-trigger="hover" data-content="Ver contraseña"
                                                   data-toggle="popover">
                                                    <i class="fa fa-eye" aria-hidden="true"></i></a> &nbsp;

                                                <?php if ($user->llave != null) {
                                                    ?>
                                                    <a href="index.php?controller=Usuarios&action=generakey&usuarioid=<?php echo $user->id_Usuario; ?>"
                                                       data-trigger="hover" data-content="Crear firma"
                                                       data-toggle="popover">
                                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                                    </a>
                                                <? } ?>
                                            </td>
                                        <? } ?>
                                    </tr>
                                    <? $contador++;
                                }
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <? if ($allUserRes) { ?>
        <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
            <div class="row pt-4 d-flex justify-content-center">
                <div class="col-11 p-0 shadow">
                    <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                        <div class="col-sm-10 d-flex align-items-center">
                            <h4 class="text-white m-0 py-2">
                                <?= $mensajeRes ?>
                            </h4>
                        </div>
                    </div>
                    <div class="p-2 table-responsive-md">
                        <table id="example1" class="table table-striped">
                            <thead class="bg-primary text-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? if (is_array($allUserRes) || is_object($allUserRes)) {
                                $contador = 1;
                                foreach ($allUserRes as $user) { ?>
                                    <tr>
                                        <td><?= $contador; ?></td>
                                        <td><?= $user->nombre . " " . $user->apellido_paterno . " " . $user->apellido_materno; ?></td>
                                        <td><?= $user->correo_Usuario; ?></td>
                                        <td>
                                            <a href="index.php?controller=Usuarios&action=restaurar&id_Usuario=<?php echo $user->id_Usuario; ?>"
                                               data-trigger="hover" data-content="Resturar"
                                               data-toggle="popover">
                                                <i class="fa fa-retweet" aria-hidden="true"></i></a> &nbsp;
                                        </td>
                                    </tr>
                                    <? $contador++;
                                }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <? }
}
if ($action == "perfil") { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-6 p-0 shadow">
                <div class="w-100 d-flex justify-content-between bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje ?>
                        </h4>
                    </div>
                    <div class="col-sm-2 d-flex justify-content-center align-items-center">
                        <a href="#"
                           data-trigger="hover" data-content="Modificar Contraseña" data-toggle="popover"
                           onclick="popover('myModalpwd')" data-placement="left" class="px-2 m-1 h4 text-white">
                            <i class="fa fa-key" aria-hidden="true"></i></a>
                        <? if ($allusers->nip_Usuario == null || empty($allusers->nip_Usuario) || $allusers->nip_Usuario == '') { ?>
                            <a href="#"
                               data-trigger="hover" data-content="Modificar Nip" data-toggle="popover"
                               onclick="popover('myModalNip')" data-placement="left" class="px-2 m-1 h4 text-white">
                                <i class="fa fa-user-secret" aria-hidden="true"></i></a>
                        <? } else { ?>
                            <a href="#"
                               data-trigger="hover" data-content="Modificar Nip" data-toggle="popover"
                               onclick="popover('myModalNip')" data-placement="left" class="px-2 m-1 h4 text-white">
                                <i class="fa fa-user-secret" aria-hidden="true"></i></a>
                        <? } ?>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">
                    <div class="container-fluid bg-light">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="py-2 text-color-primary-2 text-center font-weight-bold"> Información
                                    Personal </h4>
                                <label class="control-label">*Nombre:</label>
                                <label
                                        class="form-control labelPerfil"> <?php echo $allusers->nombre_Usuario; ?> </label>

                                <label class="control-label">*Apellido(s):</label>
                                <label
                                        class="form-control labelPerfil"> <?php echo $allusers->apellido_Usuario; ?> </label>

                                <label class="control-label">*Email:</label>
                                <label
                                        class="form-control labelPerfil"> <?php echo $allusers->correo_Usuario; ?> </label>

                                <label class="control-label">*Area:</label>
                                <label
                                        class="form-control labelPerfil"> <?php echo $allusers->nombre_Area; ?> </label>

                                <label class="control-label">*Perfil:</label>
                                <label class="form-control labelPerfil"> <?php echo $perfilEmpleado; ?> </label>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<!-- +++++++++++++++++++++++++++++++++++++ Modificar Info Personal +++++++++++++++++++++++++++++++++++++++++++++++ -->
<div class="modal fade" id="myModaluser" tabindex="-1" role="dialog" aria-labelledby="myModaluser">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Modificar Información Personal</h4>
                <form action="<?php echo $helper->url("Usuarios", "guardarDatos"); ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal">

                    <input type="hidden" name="usuarioid" value="<?php echo $allusers->id_Usuario; ?>"/>
                    <input name="id_emp" value="<?php echo $allusers->id_emp; ?>" hidden>

                    <label class="control-label">*Nombre:</label>
                    <input type="text" id="name" name="usuarionombre"
                           value="<?php echo $allusers->nombre_Usuario; ?>"
                           class="form-control" required/>

                    <label class="control-label">*Apellido:</label>
                    <input type="text" id="apellido" name="usuarioapellido"
                           value="<?php echo $allusers->apellido_Usuario; ?>"
                           class="form-control" required/>

                    <label class="control-label">*Email:</label>
                    <input type="text" id="email" name="usuarioemail"
                           value="<?php echo $allusers->correo_Usuario; ?>"
                           class="form-control" required/>

                    <input type="hidden" name="perfil" id="perfil" value="1"/>

                    <br/>

                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6 text-right">
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
<!-- ++++++++++++++++++++++++++++++++++ End Modificar Info Personal ++++++++++++++++++++++++++++++++++++++++++++++ -->


<!-- +++++++++++++++++++++++++++++++++++++ Modificar Solo Contraseña ++++++++++++++++++++++++++++++++++++++++++++++ -->
<div class="modal fade" id="myModalpwd" tabindex="-1" role="dialog" aria-labelledby="myModalpwd">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Modificar Contraseña</h4>
                <form action="<?= $helper->url("Usuarios", "guardarPwd"); ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal">

                    <label class="control-label">*Contraseña(6-12 carácteres debe incluir 1 mayúscula y un
                        número):</label>
                    <br><br>
                    <label class="control-label">*Ingresar Contraseña:</label>
                    <input type="password" class="form-control" id="pwd11" onkeyup="validarDatos1(1)"
                           pattern="(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ].*"
                           placeholder="Ingresar Contraseña" required
                           title="Contraseña(6-12 carácteres debe incluir 1 mayúscula y un número)">
                    <br>
                    <label class="control-label">*Repetir Contraseña:</label>
                    <input type="password" class="form-control" id="pwd12" onkeyup="validarDatos1(1)"
                           name="usuariopassword"
                           pattern="(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ].*"
                           placeholder="Repetir Contraseña" required
                           title="Contraseña(6-12 carácteres debe incluir 1 mayúscula y un número)">
                    <br/>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="btnGuardar1" disabled class="btn btn-w-m btn-danger">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ++++++++++++++++++++++++++++++++++ End Modificar Solo Contraseña ++++++++++++++++++++++++++++++++++++++++++++++ -->


<!-- ++++++++++++++++++++++++++++++++++++++++ Modificar Solo Nip +++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<div class="modal fade" id="myModalNip" tabindex="-1" role="dialog" aria-labelledby="myModalNip">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                <? if ($allusers->nip_Usuario == null || empty($allusers->nip_Usuario) || $allusers->nip_Usuario == '') { ?>
                    <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Registrar Nip </h4>
                <? } else { ?>
                    <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar Nip </h4>
                    <?
                } ?>


                <form action="<?php echo $helper->url("Usuarios", "guardarNip"); ?>" method="post"
                      enctype="multipart/form-data" class="form-horizontal">
                    <br>
                    <label class="control-label">*Nip(4 números)</label>
                    <br>
                    <? if ($allusers->nip_Usuario == null || empty($allusers->nip_Usuario) || $allusers->nip_Usuario == '') { ?>
                        <input type="password" min="0"
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                               class="form-control" id="nip" name="nip" placeholder="Ingresar Nip"
                               onkeyup="validarNip()"/>
                    <? } else { ?>
                        <input type="password" min="0"
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                               value="<?php echo $allusers->nip_Usuario; ?>" class="form-control" id="nip"
                               name="nip"
                               placeholder="Ingresar Nip" onkeyup="validarNip()"/>
                    <? } ?>
                    <br>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="btnGuardarNip" disabled class="btn btn-w-m btn-danger">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar Nip
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- +++++++++++++++++++++++++++++++++++++++++ END Modificar Solo Nip ++++++++++++++++++++++++++++++++++++++++++++++ -->
