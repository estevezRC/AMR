<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>

<div class="container-fluid py-4 flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row mt-3">
        <div class="col-11 shadow mx-auto">
            <div class="row bg-gradient-secondary rounded-top">
                <div class="col-sm-10 d-flex align-items-center">
                    <h4 class="text-white m-0 py-2">
                        <?= $titulo ?>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-2">
                    <?php switch ($action) {
                    case 'index': ?>
                    <div class="table-responsive-md">
                        <table id="example" class="table table-striped thead-dark">
                            <thead class="bg-primary text-light">
                            <tr>
                                <th>No.</th>
                                <th>No. Empleado</th>
                                <th>Proyecto</th>
                                <th>Puesto</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if (is_array($allEmpleados) || is_object($allEmpleados)) {
                                foreach ($allEmpleados as $key => $empleado) { ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $empleado->no_empleado ?></td>
                                        <td><?= $empleado->nombre_proyecto ?></td>
                                        <td><?= $empleado->puesto ?></td>
                                        <td><?= "$empleado->nombre $empleado->apellido_paterno $empleado->apellido_materno" ?></td>
                                        <td><?= $empleado->telefono ?></td>

                                        <td>
                                            <a href="index.php?controller=Empleados&action=mostrarDetalleEmpleado&id_empleado=<?= $empleado->id_empleado; ?>"
                                               data-trigger="hover" data-content="Ver detalle"
                                               data-toggle="popover">
                                                <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp;

                                            <a href="index.php?controller=Empleados&action=modificar&id_empleado=<?= $empleado->id_empleado; ?>"
                                               data-trigger="hover" data-content="Modificar"
                                               data-toggle="popover">
                                                <i class="fa fa-edit" aria-hidden="true"></i></a> &nbsp;

                                            <a href="#" data-trigger="hover" data-content="Borrar"
                                               data-toggle="popover"
                                               onclick="borrarRegistroAjax(<?php echo $empleado->id_empleado; ?>, 'id_empleado', '<?php echo("$empleado->nombre $empleado->apellido_paterno $empleado->apellido_materno"); ?>', 'Empleados', 'borrar')">
                                                <i class="fa fa-trash" aria-hidden="true"></i></a>

                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <? break;
                case 'mostrarDetalleEmpleado': ?>
                    <div class="row justify-content-around">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-gradient-light">
                                            <span
                                                class="font-weight-bold h5 text-center d-block m-0">Datos Personales</span>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <b>Empleado:</b> <?= $datosGenerales[0]->no_empleado ?></li>
                                            <li class="list-group-item">
                                                <b>Nombre:</b> <?= "{$datosGenerales[0]->nombre} {$datosGenerales[0]->apellido_paterno}  {$datosGenerales[0]->apellido_materno}" ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Puesto:</b> <?= $datosGenerales[0]->puesto ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Edad:</b> <?= $datosGenerales[0]->edad ?> años
                                            </li>
                                            <li class="list-group-item">
                                                <b>Género:</b> <?= $datosGenerales[0]->genero ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>CURP:</b> <?= $datosGenerales[0]->curp ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>RFC:</b> <?= $datosGenerales[0]->rfc ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>NSS:</b> <?= $datosGenerales[0]->nss ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Teléfono:</b> <?= $datosGenerales[0]->telefono ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Tipo de Sangre:</b> <?= $datosGenerales[0]->tipo_sangre ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Seguro de Gastos
                                                    Mayores:</b> <?= $datosGenerales[0]->seguro_gastos_mayores ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Salario:</b> <?= $datosGenerales[0]->salario ?> pesos
                                            </li>
                                            <li class="list-group-item">
                                                <b>Información
                                                    adicional:</b> <?= nl2br($datosGenerales[0]->info_adicional) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-gradient-light">
                                                <span
                                                    class="font-weight-bold h5 text-center d-block m-0">Dirección</span>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <b>Calle:</b> <?= $datosGenerales[0]->calle ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Número:</b> <?= $datosGenerales[0]->numero ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Colonia:</b> <?= $datosGenerales[0]->colonia ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>CP:</b> <?= $datosGenerales[0]->cp ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Estado:</b> <?= $datosGenerales[0]->estado ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>País:</b> <?= $datosGenerales[0]->pais ?>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header bg-gradient-light">
                                                <span
                                                    class="font-weight-bold h5 text-center d-block m-0">Estudios </span>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <b>Grado de estudios:</b> <?= $datosGenerales[0]->grado_estudios ?>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Titulo obtenido:</b> <?= $datosGenerales[0]->nombre_estudio ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mt-3 mt-md-0">
                                <div class="card-header bg-gradient-light">
                                    <span class="font-weight-bold h5 text-center d-block m-0">Expediente</span>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (count($expedientes) > 0) { ?>
                                        <?php foreach ($expedientes as $expediente) { ?>
                                            <li class="list-group-item">
                                                <a class="btn btn-danger d-flex justify-content-between w-100"
                                                   href="index.php?controller=Empleados&action=descargarExpediente&archivo=<?php echo $expediente->nombre_archivo; ?>&id_empleado=<?php echo $expediente->id_emp; ?>"
                                                   role="button">
                                                    <? echo $this->convertirNombreExpedientes($expediente->tipo_archivo) ?>
                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="alert alert-warning m-2" role="alert">
                                            Ningún archivo en el expediente!
                                        </div>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php break;
                case 'registro': ?>
                    <link href="./css/bs-stepper.css" rel="stylesheet">
                    <div class="bs-stepper">
                        <div class="bs-stepper-header mb-2" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#datos-generales">
                                <button type="button" class="step-trigger" role="tab"
                                        aria-controls="datos-generales" id="datos-generales-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Datos Generales</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#direccion">
                                <button type="button" class="step-trigger" role="tab"
                                        aria-controls="direccion-part" id="direccion-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Dirección</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#expediente">
                                <button type="button" class="step-trigger" role="tab"
                                        aria-controls="expediente" id="expediente-trigger">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label sm">Expediente</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content p-2 border-top border-danger">
                            <form action="#" id="form_registrar">
                                <div id="datos-generales" class="content" role="tabpanel"
                                     aria-labelledby="datos-generales-trigger">
                                    <fieldset>
                                        <div class="form-row align-items-center">
                                            <div class="col-4">
                                                <div class="form-check my-auto">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="check_user_exist">
                                                    <label class="form-check-label font-weight-bold"
                                                           for="check_user_exist">
                                                        Escoger desde usuario existente
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 d-none" id="container_select_users">
                                                <div class="row">
                                                    <label for="users" class="m-0 my-auto">
                                                        Usuarios
                                                    </label>
                                                    <div class="col-8">
                                                        <select id="users" name="users" class="custom-select"
                                                                required>
                                                            <? if ($usersNotEmployees) {
                                                                array_map(function ($user) {
                                                                    echo "<option value='$user->id_usuario' data-apmaterno='$user->apellido_materno'
                                                                    data-appaterno='$user->apellido_paterno' data-nombre='$user->nombre'>
                                                                        $user->nombre $user->apellido_paterno $user->apellido_materno 
                                                                    </option>";
                                                                }, $usersNotEmployees);
                                                            } else { ?>
                                                                <option value="0">No hay usuarios</option>
                                                            <? }?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="proyecto">
                                                    Proyecto
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <select id="proyecto" name="proyecto" class="custom-select"
                                                        required>
                                                    <option value='0'>Seleccionar Proyecto</option>
                                                    <?php array_map(function ($proyecto) {
                                                        echo "<option value='$proyecto->id_Proyecto'>$proyecto->nombre_Proyecto</option>";
                                                    }, $proyectos) ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="nombre">Nombre(s)
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input type="text" class="form-control" id="nombre"
                                                       name="nombre"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Campo Obligatorio.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="apellido_paterno">Apellido Paterno
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="apellido_paterno" name="apellido_paterno" type="text"
                                                       class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Campo Obligatorio.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="apellido_materno">Apellido Materno
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="apellido_materno" name="apellido_materno" type="text"
                                                       class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Campo Obligatorio.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="puesto">
                                                    Puesto
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="puesto" name="puesto" type="text"
                                                       class="form-control" placeholder="">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="no_empleado">
                                                    Número de Empleado
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="no_empleado" name="no_empleado" type="text"
                                                       class="form-control" placeholder="">
                                                <div class="invalid-feedback">
                                                    Se permite hasta 8 caracteres.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="genero">
                                                    Sexo
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <select class="custom-select" name="genero" id="genero">
                                                    <option value='0'> Seleccionar sexo</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Femenino</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="edad">
                                                    Edad
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="edad" name="edad" type="text"
                                                       class="form-control" placeholder="">
                                                <div class="invalid-feedback">
                                                    Debe escribir unícamente números(2 dígitos).
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="curp">
                                                    CURP
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="curp" name="curp" type="text" class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Verifique la CURP, debe tener 18 caracteres.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="rfc">
                                                    RFC
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="rfc" name="rfc" type="text" class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Verifique el RFC, debe contener 13 caracteres.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="telefono">
                                                    Teléfono
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="telefono" name="telefono" type="tel" class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Debe escribir unícamente 10 números.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="nss">
                                                    Número de Seguro Social (NSS)
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="nss" name="nss" type="text" class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Debe escribir 11 números.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="tipo_sangre">
                                                    Tipo de Sangre
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <select id="tipo_sangre" name="tipo_sangre" class="custom-select"
                                                        required>
                                                    <option value='0' selected>Seleccione Tipo de Sangre</option>
                                                    <option value='A+'>A+</option>
                                                    <option value='O+'>O+</option>
                                                    <option value='B+'>B+</option>
                                                    <option value='AB+'>AB+</option>
                                                    <option value='A-'>A-</option>
                                                    <option value='O-'>O-</option>
                                                    <option value='B-'>B-</option>
                                                    <option value='AB-'>AB-</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="gastos_mayores">Seguro de Gastos Mayores</label>
                                                <input id="gastos_mayores" name="gastos_mayores" type="text"
                                                       class="form-control"
                                                       placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="nivel_estudios">
                                                    Nivel de Estudios
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="nivel_estudios" name="nivel_estudios" type="text"
                                                       class="form-control"
                                                       placeholder="Ej: Licenciatura, Posgrado">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="nombre_estudio">
                                                    Título Obtenido
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="nombre_estudio" name="nombre_estudio" type="text"
                                                       class="form-control"
                                                       placeholder="Administración, Derecho">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="salario">
                                                    Salario Mensual Bruto
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input id="salario" name="salario" type="text"
                                                               class="form-control">
                                                        <div class="invalid-feedback">
                                                            Debe escribir unícamente números y decimales a 2 digitos.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="informacion_adicional">Información Adicional</label>
                                            <textarea class="form-control" id="informacion_adicional"
                                                      name="informacion_adicional"
                                                      rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <small class="float-left text-danger d-none" id="mensaje_campos">
                                                *Hay campos obligatorios sin llenar.
                                            </small>
                                            <button type="button" id="btn_next_datos_generales"
                                                    class="btn btn-success float-right">Siguiente
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                                <div id="direccion" class="content" role="tabpanel"
                                     aria-labelledby="direccion-trigger">
                                    <fieldset>
                                        <div class="form-row">
                                            <div class="form-group col-md-8">
                                                <label for="calle">
                                                    Calle
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="calle" name="calle" type="text" class="form-control"
                                                       placeholder="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="numero_calle">
                                                    Número
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="numero_calle" name="numero" type="text"
                                                       class="form-control" placeholder="">
                                                <div class="invalid-feedback">
                                                    Debe escribir unícamente números.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-8">
                                                <label for="colonia">
                                                    Colonia
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="colonia" name="colonia" type="text"
                                                       class="form-control" placeholder="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="municipio">
                                                    Municipio
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="municipio" name="municipio" type="text"
                                                       class="form-control"
                                                       placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="estado">
                                                    Estado
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="estado" name="estado" type="text"
                                                       class="form-control" placeholder="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="cp">
                                                    CP
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="cp" name="cp" type="text" class="form-control"
                                                       placeholder="">
                                                <div class="invalid-feedback">
                                                    Código postal inválido.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="pais">
                                                    País
                                                    <small class="text-danger">*obligatorio</small>
                                                </label>
                                                <input id="pais" name="pais" type="text" class="form-control"
                                                       placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="btn_prev_direccion"
                                                    class="btn btn-success float-left">Anterior
                                            </button>
                                            <button type="button" id="btn_next_direccion"
                                                    class="btn btn-success float-right">Siguiente
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                                <div id="expediente" class="content" role="tabpanel"
                                     aria-labelledby="expediente-trigger">
                                    <fieldset>
                                        <div class="form-group d-flex justify-content-end">
                                            <button type="button" id="btn_agregar_archivo"
                                                    class="btn btn-sm btn-supervisor-celeste">Agregar
                                            </button>
                                            <button type="button" id="btn_quitar_archivo"
                                                    class="btn btn-sm btn-supervisor-red">Quitar
                                            </button>
                                        </div>
                                        <div id="container_files">
                                            <div data-file="1" class="my-4 py-4 px-2 bg-light">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                               id="file1" name="files[]"
                                                               lang="es">
                                                        <label class="custom-file-label" for="file1">Seleccionar
                                                            Archivo</label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <label for="tipo_archivo_1">Tipo de Archivo</label>
                                                    <select id="tipo_archivo_1" class="custom-select tipo-file">
                                                        <option value="acta" selected>Acta de Nacimiento</option>
                                                        <option value="curp">CURP</option>
                                                        <option value="rfc">RFC</option>
                                                        <option value="cv">CV</option>
                                                        <option value="cbte_domicilio">Comprobante de Domicilio
                                                        </option>
                                                        <option value="examen_medico">Examen médico</option>
                                                        <option value="nss">Número de Seguro Social</option>
                                                        <option value="id_oficial">Identificacion Oficial</option>
                                                        <option value="cons_estudio">Constancia de Estudio
                                                        </option>
                                                        <option value="cedula_prof">Cédula Profesional</option>
                                                        <option value="carta_recomend">Carta de Recomendación
                                                        </option>
                                                        <option value="contrato_laboral">Contrato Laboral</option>
                                                        <option value="constancia_laboral">Constancia Laboral
                                                        </option>
                                                        <option value="doc_internos">Documentos Internos</option>
                                                        <option value="fmi">FMP - Formato Múltiple de Incidencias de
                                                            Personal
                                                        </option>
                                                        <option value="fmp">FMP - Formato Múltiple de Movimiento de
                                                            Personal
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="button" id="btn_prev_expediente"
                                                    class="btn btn-success float-left">Anterior
                                            </button>
                                            <button type="submit" id="btn_registrar_expediente"
                                                    class="btn btn-success float-right">Registrar
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script src="./js/stepper.js"></script>
                    <script>
                        class UIController {
                            constructor() {
                                this.DOMelements = {
                                    formulario: document.querySelector('#form_registrar'),
                                    stepperContainer: document.querySelector('.bs-stepper'),
                                    datosGenerales: {
                                        checkUserExist: document.querySelector('#check_user_exist'),
                                        containerSelectUsers: document.querySelector('#container_select_users'),
                                        selectUsers: document.querySelector('#users'),
                                        inputProyecto: document.querySelector('#proyecto'),
                                        inputNombre: document.querySelector('#nombre'),
                                        inputApellidoPaterno: document.querySelector('#apellido_paterno'),
                                        inputApellidoMaterno: document.querySelector('#apellido_materno'),
                                        puesto: document.querySelector('#puesto'),
                                        inputNoEmpleado: document.querySelector('#no_empleado'),
                                        selectGenero: document.querySelector('#genero'),
                                        inputEdad: document.querySelector('#edad'),
                                        inputCURP: document.querySelector('#curp'),
                                        inputRFC: document.querySelector('#rfc'),
                                        inputTelefono: document.querySelector('#telefono'),
                                        inputNSS: document.querySelector('#nss'),
                                        inputTipoSangre: document.querySelector('#tipo_sangre'),
                                        inputGastosMayores: document.querySelector('#gastos_mayores'),
                                        inputNivelEstudios: document.querySelector('#nivel_estudios'),
                                        inputTituloObtenido: document.querySelector('#nombre_estudio'),
                                        inputSalarioMensual: document.querySelector('#salario'),
                                        txtInfoAdicional: document.querySelector('#informacion_adicional'),
                                        mensajeCampos: document.querySelector('#mensaje_campos'),
                                        btnSiguiente: document.querySelector('#btn_next_datos_generales'),
                                        obligatorios: {
                                            inputProyecto: false,
                                            inputNombre: false,
                                            inputApellidoPaterno: false,
                                            inputApellidoMaterno: false,
                                            inputPuesto: false,
                                            inputNoEmpleado: false,
                                            inputSexo: false,
                                            inputEdad: false,
                                            inputCurp: false,
                                            inputRfc: false,
                                            inputTelefono: false,
                                            inputNss: false,
                                            inputTipoSangre: false,
                                            inputSalario: false,
                                            inputNombreEstudio: false,
                                            inputNivelEstudios: false
                                        }
                                    },
                                    direccion: {
                                        inputCalle: document.querySelector('#calle'),
                                        inputNumeroCalle: document.querySelector('#numero_calle'),
                                        inputColonia: document.querySelector('#colonia'),
                                        inputMunicipio: document.querySelector('#municipio'),
                                        inputEstado: document.querySelector('#estado'),
                                        inputCP: document.querySelector('#cp'),
                                        inputPais: document.querySelector('#pais'),
                                        btnAnterior: document.querySelector('#btn_prev_direccion'),
                                        btnSiguiente: document.querySelector('#btn_next_direccion'),
                                        obligatorios: {
                                            inputCalle: false,
                                            inputNumeroCalle: false,
                                            inputColonia: false,
                                            inputMunicipio: false,
                                            inputEstado: false,
                                            inputCP: false,
                                            inputPais: false
                                        }
                                    },
                                    expediente: {
                                        container: document.querySelector('#container_files'),
                                        btnAgregar: document.querySelector('#btn_agregar_archivo'),
                                        inputFile: document.querySelector('#file1'),
                                        btnQuitar: document.querySelector('#btn_quitar_archivo'),
                                        btnAnterior: document.querySelector('#btn_prev_expediente')
                                    }
                                };

                                this.isEnabledSelectUsers = this.DOMelements.datosGenerales.checkUserExist.checked;
                            }

                            habilitarBoton(boton) {
                                boton.disabled = false;
                            }

                            deshabilitarBoton(boton) {
                                boton.disabled = true;
                            }

                            deshabilitarBotones() {
                                this.DOMelements.datosGenerales.btnSiguiente.disabled = true;
                                this.DOMelements.direccion.btnSiguiente.disabled = true;
                            }

                            showInvalidMessage(elemento) {
                                elemento.classList.add('is-invalid');
                            }

                            hideInvalidMessage(elemento) {
                                elemento.classList.remove('is-invalid');
                            }

                            showValidMessage(elemento) {
                                elemento.classList.add('is-valid');
                            }

                            hideValidMessage(elemento) {
                                elemento.classList.remove('is-valid');
                            }

                            showMensajeCampos() {
                                this.DOMelements.datosGenerales.mensajeCampos.classList.remove('d-none');
                            }

                            hideMensajeCampos() {
                                this.DOMelements.datosGenerales.mensajeCampos.classList.add('d-none');
                            }

                            showAlertifyDatosGral() {
                                alertify.error(`Hay campos obligatorios sin llenar en la sección datos generales`);
                            }

                            showAlertifyDir() {
                                alertify.error(`Hay campos obligatorios sin llenar en la sección dirección`);
                            }

                            agregarArchivo() {
                                let lastID = parseInt(this.DOMelements.expediente.container.lastElementChild.dataset.file);
                                if (lastID < 19) {
                                    const markup = `
                                            <div data-file="${lastID + 1}" class="my-4 py-4 px-2 bg-light">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                               id="file${lastID + 1}" name="files[]"
                                                               lang="es">
                                                        <label class="custom-file-label" for="file${lastID + 1}">
                                                            Seleccionar Archivo
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <label for="tipo_archivo_${lastID + 1}">Tipo de Archivo</label>
                                                    <select id="tipo_archivo_${lastID + 1}" class="custom-select tipo-file">
                                                        <option value="acta" selected>Acta de Nacimiento</option>
                                                        <option value="curp">CURP</option>
                                                        <option value="rfc">RFC</option>
                                                        <option value="cv">CV</option>
                                                        <option value="cbte_domicilio">Comprobante de Domicilio</option>
                                                        <option value="examen_medico">Examen médico</option>
                                                        <option value="nss">Número de Seguro Social</option>
                                                        <option value="id_oficial">Identificacion Oficial</option>
                                                        <option value="cons_estudio">Constancia de Estudio</option>
                                                        <option value="cedula_prof">Cédula Profesional</option>
                                                        <option value="carta_recomend">Carta de Recomendación</option>
                                                        <option value="contrato_laboral">Contrato Laboral</option>
                                                        <option value="constancia_laboral">Constancia Laboral</option>
                                                        <option value="doc_internos">Documentos Internos</option>
                                                        <option value="fmi">FMP - Formato Múltiple de Incidencias de Personal</option>
                                                        <option value="fmp">FMP - Formato Múltiple de Movimiento de Personal</option>
                                                    </select>
                                                </div>
                                            </div>
                                        `;
                                    this.DOMelements.expediente.container.insertAdjacentHTML('beforeend', markup);
                                    document.getElementById(`file${lastID + 1}`).addEventListener('change', (evento) => this.updateInputFile(evento.target, this));
                                }
                            }

                            quitarArchivo() {
                                let lastID = parseInt(this.DOMelements.expediente.container.lastElementChild.dataset.file);
                                if (lastID !== 1) {
                                    this.DOMelements.expediente.container.removeChild(this.DOMelements.expediente.container.lastElementChild);
                                }
                            }

                            updateInputFile(input) {
                                if (input.files && input.files[0]) {
                                    input.parentElement.querySelector('label').textContent = input.files[0].name;
                                }
                            }

                            showSelectUsers(element) {
                                this.isEnabledSelectUsers = element.checked;
                                if (element.checked) {
                                    this.DOMelements.datosGenerales.containerSelectUsers.classList.replace('d-none', 'd-block');
                                    this.completeFullName(this.DOMelements.datosGenerales.selectUsers);
                                } else {
                                    this.DOMelements.datosGenerales.containerSelectUsers.classList.replace('d-block', 'd-none');

                                    this.DOMelements.datosGenerales.inputNombre.value = '';
                                    this.DOMelements.datosGenerales.inputApellidoPaterno.value = '';
                                    this.DOMelements.datosGenerales.inputApellidoMaterno.value = '';

                                    this.enableInput(this.DOMelements.datosGenerales.inputNombre);
                                    this.enableInput(this.DOMelements.datosGenerales.inputApellidoPaterno);
                                    this.enableInput(this.DOMelements.datosGenerales.inputApellidoMaterno);
                                }
                            }

                            get idUserSelected (){
                                return this.DOMelements.datosGenerales.selectUsers.value;
                            }

                            completeFullName(element) {
                                if (+element.value) {
                                    const nombre = element.options[element.selectedIndex].dataset.nombre;
                                    const appaterno = element.options[element.selectedIndex].dataset.appaterno;
                                    const apmaterno = element.options[element.selectedIndex].dataset.apmaterno;

                                    this.DOMelements.datosGenerales.inputNombre.value = nombre;
                                    this.DOMelements.datosGenerales.inputApellidoPaterno.value = appaterno;
                                    this.DOMelements.datosGenerales.inputApellidoMaterno.value = apmaterno;

                                    this.disableInput(this.DOMelements.datosGenerales.inputNombre);
                                    this.disableInput(this.DOMelements.datosGenerales.inputApellidoPaterno);
                                    this.disableInput(this.DOMelements.datosGenerales.inputApellidoMaterno);

                                    this.DOMelements.datosGenerales.obligatorios.inputNombre = true;
                                    this.DOMelements.datosGenerales.obligatorios.inputApellidoPaterno = true;
                                    this.DOMelements.datosGenerales.obligatorios.inputApellidoMaterno = true;
                                } else {
                                    this.DOMelements.datosGenerales.inputNombre.value = '';
                                    this.DOMelements.datosGenerales.inputApellidoPaterno.value = '';
                                    this.DOMelements.datosGenerales.inputApellidoMaterno.value = '';

                                    this.enableInput(this.DOMelements.datosGenerales.inputNombre);
                                    this.enableInput(this.DOMelements.datosGenerales.inputApellidoPaterno);
                                    this.enableInput(this.DOMelements.datosGenerales.inputApellidoMaterno);
                                }
                            }

                            disableInput(element) {
                                element.disabled = true;
                            }

                            enableInput(element) {
                                element.disabled = false;
                            }

                            getSelectTipoArchivo() {
                                let elementos = Object.values(document.querySelectorAll('.tipo-file'));

                                return elementos.map(elemento => elemento.value);
                            }

                        }

                        class RegistroController extends UIController {
                            constructor() {
                                super();
                            }

                            validarDatosGenerales(elemento) {
                                switch (elemento.id) {
                                    case 'proyecto':
                                        if (elemento.value && elemento.value != 0) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputProyecto = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputProyecto = false;
                                        }
                                        break;
                                    case 'nombre':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombre = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombre = false;
                                        }
                                        break;
                                    case 'apellido_paterno':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoPaterno = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoPaterno = false;
                                        }
                                        break;
                                    case 'apellido_materno':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoMaterno = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoMaterno = false;
                                        }
                                        break;
                                    case 'puesto':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputPuesto = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputPuesto = false;
                                        }
                                        break;
                                    case 'no_empleado':
                                        if (elemento.value && elemento.value.length <= 8) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNoEmpleado = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNoEmpleado = false;
                                        }
                                        break;

                                    case 'tipo_sangre':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTipoSangre = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTipoSangre = false;
                                        }
                                        break;

                                    case 'nombre_estudio':
                                        if (elemento.value !== '0') {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombreEstudio = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombreEstudio = false;
                                        }
                                        break;

                                    case 'nivel_estudios':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNivelEstudios = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNivelEstudios = false;
                                        }
                                        break;

                                    case 'gastos_mayores':
                                    case 'informacion_adicional':
                                        if (elemento.value) {
                                            this.showValidMessage(elemento);
                                        } else {
                                            this.hideValidMessage(elemento);
                                        }
                                        break;

                                    case 'genero':
                                        if (elemento.value && elemento.value != 0) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSexo = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSexo = false;
                                        }
                                        break;

                                    case 'edad':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = false;
                                            break;
                                        }
                                        if (/^[0-9]{1,2}?$/.test(elemento.value)) {
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = false;
                                        }
                                        break;

                                    case 'curp':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = false;
                                            break;
                                        }
                                        if (elemento.value.length == 18) {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = true;
                                        } else {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = false;
                                        }
                                        break;

                                    case 'rfc':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = false;
                                            break;
                                        }
                                        if (elemento.value.length == 13) {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = true;

                                        } else {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = false;

                                        }
                                        break;

                                    case 'telefono':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 10) {
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = false;
                                        }
                                        break;

                                    case 'nss':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 11) {
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = false;
                                        }
                                        break;

                                    case 'salario':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = false;
                                            break;
                                        }
                                        if (/^\d*(\.\d{1})?\d{0,1}$/.test(elemento.value)) {
                                            this.habilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.datosGenerales.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = false;
                                        }
                                        break;
                                    default:
                                }
                            }

                            validarDireccion(elemento) {
                                switch (elemento.id) {
                                    case 'calle':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCalle = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCalle = false;
                                        }
                                        break;

                                    case 'colonia':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputColonia = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputColonia = false;
                                        }
                                        break;

                                    case 'municipio':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputMunicipio = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputMunicipio = false;
                                        }
                                        break;

                                    case 'estado':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputEstado = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputEstado = false;
                                        }
                                        break;

                                    case 'pais':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputPais = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputPais = false;
                                        }
                                        break;

                                    case 'numero_calle':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value)) {
                                            this.habilitarBoton(this.DOMelements.direccion.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.direccion.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = false;
                                        }
                                        break;

                                    case 'cp':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCP = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 5) {
                                            this.habilitarBoton(this.DOMelements.direccion.btnSiguiente);
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCP = true;
                                        } else {
                                            this.deshabilitarBoton(this.DOMelements.direccion.btnSiguiente);
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCP = false;
                                        }
                                        break;
                                }
                            }

                            validarSeccion(seccion) {
                                if (seccion === 'datos_generales') {
                                    const formValid = !Object.values(this.DOMelements.datosGenerales.obligatorios).some(elemento => elemento === false);
                                    if (formValid) {
                                        this.hideMensajeCampos();
                                        this.stepper.next();
                                    } else {
                                        this.showAlertifyDatosGral();
                                        this.showMensajeCampos();
                                    }
                                } else if (seccion === 'direccion') {
                                    const formValid = !Object.values(this.DOMelements.direccion.obligatorios).some(elemento => elemento === false);
                                    if (formValid) {
                                        this.hideMensajeCampos();
                                        this.stepper.next();
                                    } else {
                                        this.showAlertifyDir();
                                        this.showMensajeCampos();
                                    }
                                }
                            }

                            enviarFormulario(evento) {
                                evento.preventDefault();
                                const formData = new FormData(this.DOMelements.formulario);

                                let tiposArchivo = this.getSelectTipoArchivo();

                                tiposArchivo = JSON.stringify(tiposArchivo);

                                formData.append('tipo_archivo', tiposArchivo);

                                if (this.isEnabledSelectUsers) {
                                    const idUsuario = this.DOMelements.datosGenerales.selectUsers.value;
                                    const nombre = this.DOMelements.datosGenerales.inputNombre.value;
                                    const apellidoPaterno = this.DOMelements.datosGenerales.inputApellidoPaterno.value;
                                    const apellidoMaterno = this.DOMelements.datosGenerales.inputApellidoMaterno.value;
                                    formData.append('id_usuario', idUsuario);
                                    formData.append('nombre', nombre);
                                    formData.append('apellido_paterno', apellidoPaterno);
                                    formData.append('apellido_materno', apellidoMaterno);
                                }

                                $.ajax({
                                    url: './index.php?controller=Empleados&action=guardarNuevoEmpleado',
                                    type: 'POST',
                                    contentType: false,
                                    data: formData,  // mandamos el objeto formdata que se igualo a la variable data
                                    processData: false,
                                    cache: false,
                                    success: function (respuestaAjax) {          console.log(respuestaAjax);
                                        respuestaAjax = JSON.parse(respuestaAjax);
                                        console.log(respuestaAjax);
                                        if (respuestaAjax[0] == 1) {
                                            
                                            if (respuestaAjax[2]) {
                                                alertify.success( `${respuestaAjax[1].trim()}`);
                                                setTimeout(function () {
                                                    document.location.href = 'index.php?controller=Empleados&action=index';
                                                }, 200);
                                            } else {
                                                alertify.confirm('Confirmación', `${respuestaAjax[1].trim()}, ¿desea otorgarle acceso a la plataforma?`, function () {
                                                        document.location.href = 'index.php?controller=Usuarios&action=index';
                                                    },
                                                    function () {
                                                        document.location.href = 'index.php?controller=Empleados&action=index';
                                                    }).set({
                                                    labels: {ok: 'Sí', cancel: 'Ahora no'},
                                                    padding: false
                                                });
                                            }

                                        } else {
                                            alertify.alert('Mensaje', `${respuestaAjax[1]}`);
                                        }

                                    }
                                });
                            }

                            configurarStepper(evento, self) {
                                const options = {
                                    linear: true,
                                    animation: true,
                                    selectors: {
                                        steps: '.step',
                                        trigger: '.step-trigger',
                                        stepper: '.bs-stepper'
                                    }
                                };

                                this.stepper = new Stepper(self.DOMelements.stepperContainer, options);
                            }

                            avanzarStepper() {
                                this.stepper.next();
                            }

                            retrocederStepper() {
                                this.stepper.previous();
                            }

                            stepperResponsive() {
                                const mq = window.matchMedia("(max-width: 768px)");
                                mq.addListener(() => {
                                    if (mq.matches) {
                                        this.DOMelements.stepperContainer.classList.add('vertical');
                                    }
                                });
                            }

                            inputsEventListeners(elementos, seccion) {
                                if (seccion === 'datos_generales') {
                                    Object.values(elementos).forEach(elemento => {
                                        if (elemento instanceof HTMLElement) {
                                            ['change', 'keydown', 'keyup', 'keypress'].forEach(listener => {
                                                elemento.addEventListener(listener, (evento) => this.validarDatosGenerales(evento.target, this));
                                            });
                                        }
                                    });
                                } else if (seccion === 'direccion') {
                                    Object.values(elementos).forEach(elemento => {
                                        if (elemento instanceof HTMLElement) {
                                            ['change', 'keydown', 'keyup', 'keypress'].forEach(listener => {
                                                elemento.addEventListener(listener, (evento) => this.validarDireccion(evento.target, this));
                                            });
                                        }
                                    });
                                }
                            }

                            setEventListeners() {
                                window.addEventListener('load', (evento) => {
                                    this.configurarStepper(evento, this);
                                    this.DOMelements.formulario.reset();
                                });

                                this.DOMelements.datosGenerales.checkUserExist.addEventListener('change', (evento) => this.showSelectUsers(evento.target, this));
                                this.DOMelements.datosGenerales.selectUsers.addEventListener('change', (evento) => this.completeFullName(evento.target, this));
                                this.DOMelements.datosGenerales.btnSiguiente.addEventListener('click', () => this.validarSeccion('datos_generales', this));
                                this.DOMelements.direccion.btnSiguiente.addEventListener('click', () => this.validarSeccion('direccion', this));
                                this.DOMelements.direccion.btnAnterior.addEventListener('click', () => this.retrocederStepper(this));
                                this.DOMelements.expediente.btnAnterior.addEventListener('click', () => this.retrocederStepper(this));
                                this.DOMelements.expediente.btnAgregar.addEventListener('click', () => this.agregarArchivo(this));
                                this.DOMelements.expediente.btnQuitar.addEventListener('click', () => this.quitarArchivo(this));
                                this.DOMelements.expediente.inputFile.addEventListener('change', (evento) => this.updateInputFile(evento.target, this));
                                this.DOMelements.formulario.addEventListener('submit', (evento) => this.enviarFormulario(evento, this));

                                this.inputsEventListeners(this.DOMelements.datosGenerales, 'datos_generales');
                                this.inputsEventListeners(this.DOMelements.direccion, 'direccion');
                                this.stepperResponsive();
                            }

                            start() {
                                this.setEventListeners();
                            }
                        }

                        const registro = new RegistroController();
                        registro.start();
                    </script>
                    <?php break;
                case 'modificar': ?>
                    <ul class="nav nav-tabs" id="modificar_empleado" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="datos_generales-tab" data-toggle="tab"
                               href="#datos_generales" role="tab"
                               aria-controls="datos_generales" aria-selected="true">Datos Generales</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="direccion-tab" data-toggle="tab" href="#direccion" role="tab"
                               aria-controls="direccion" aria-selected="false">Dirección</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="expediente-tab" data-toggle="tab" href="#expediente" role="tab"
                               aria-controls="expediente" aria-selected="false">Expediente</a>
                        </li>
                        <li class="ml-auto" role="presentation">
                            <button type="button" id="btn_actualizar" class="btn btn-primary">Actualizar</button>
                        </li>
                    </ul>
                    <form action="#" id="form_modificar">
                        <div class="tab-content pt-2" id="modificacion">

                            <div class="tab-pane fade show active" id="datos_generales" role="tabpanel"
                                 aria-labelledby="datos_generales-tab">
                                <fieldset>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="proyecto">
                                                Proyecto
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <select id="proyecto" name="proyecto" class="custom-select"
                                                    required>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="nombre">
                                                Nombre(s)
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input type="text" class="form-control" id="nombre"
                                                   name="nombre"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Campo Obligatorio.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="apellido_paterno">Apellido Paterno
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="apellido_paterno" name="apellido_paterno" type="text"
                                                   class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Campo Obligatorio.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="apellido_materno">Apellido Materno
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="apellido_materno" name="apellido_materno" type="text"
                                                   class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Campo Obligatorio.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="puesto">
                                                Puesto
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="puesto" name="puesto" type="text" class="form-control"
                                                   required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="no_empleado">
                                                Número de Empleado
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="no_empleado" name="no_empleado" type="text"
                                                   class="form-control" placeholder="">
                                            <div class="invalid-feedback">
                                                Se permite hasta 8 caracteres.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="genero">
                                                Sexo
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <select class="custom-select" name="genero" id="genero">
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="edad">
                                                Edad
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="edad" name="edad" type="text"
                                                   class="form-control" placeholder="">
                                            <div class="invalid-feedback">
                                                Debe escribir unícamente números(2 dígitos).
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="curp">
                                                CURP
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="curp" name="curp" type="text" class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Verifique la CURP, debe tener 18 caracteres.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="rfc">
                                                RFC
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="rfc" name="rfc" type="text" class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Verifique el RFC, debe tener 13 caracteres.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telefono">
                                                Teléfono
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="telefono" name="telefono" type="tel" class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Debe escribir unícamente 10 números.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="nss">
                                                Número de Seguro Social (NSS)
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="nss" name="nss" type="text" class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Debe escribir 11 números.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="tipo_sangre">
                                                Tipo de Sangre
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <select id="tipo_sangre" name="tipo_sangre" class="custom-select"
                                                    required>
                                                <option value='0' selected>Seleccione Tipo de Sangre</option>
                                                <option value='A+'>A+</option>
                                                <option value='O+'>O+</option>
                                                <option value='B+'>B+</option>
                                                <option value='AB+'>AB+</option>
                                                <option value='A-'>A-</option>
                                                <option value='O-'>O-</option>
                                                <option value='B-'>B-</option>
                                                <option value='AB-'>AB-</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="gastos_mayores">Seguro de Gastos Mayores</label>
                                            <input id="gastos_mayores" name="gastos_mayores" type="text"
                                                   class="form-control"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="nivel_estudios">
                                                Nivel de Estudios
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="nivel_estudios" name="nivel_estudios" type="text"
                                                   class="form-control"
                                                   placeholder="Ej: Licenciatura, Posgrado">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nombre_estudio">
                                                Título Obtenido
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="nombre_estudio" name="nombre_estudio" type="text"
                                                   class="form-control"
                                                   placeholder="Administración, Derecho">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="salario">
                                                Salario Mensual Bruto
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input id="salario" name="salario" type="text"
                                                           class="form-control">
                                                    <div class="invalid-feedback">
                                                        Debe escribir unícamente números y decimales a 2 digitos.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="informacion_adicional">Información Adicional</label>
                                        <textarea class="form-control" id="informacion_adicional"
                                                  name="informacion_adicional"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <small class="float-left text-danger d-none" id="mensaje_campos">
                                            *Hay campos obligatorios sin llenar.
                                        </small>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="tab-pane fade" id="direccion" role="tabpanel" aria-labelledby="profile-tab">
                                <fieldset>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="calle">
                                                Calle
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="calle" name="calle" type="text" class="form-control"
                                                   placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="numero_calle">
                                                Número
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="numero_calle" name="numero" type="text"
                                                   class="form-control" placeholder="">
                                            <div class="invalid-feedback">
                                                Debe escribir unícamente números.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="colonia">
                                                Colonia
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="colonia" name="colonia" type="text"
                                                   class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="municipio">
                                                Municipio
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="municipio" name="municipio" type="text"
                                                   class="form-control"
                                                   placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="estado">
                                                Estado
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="estado" name="estado" type="text"
                                                   class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cp">
                                                CP
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="cp" name="cp" type="text" class="form-control"
                                                   placeholder="">
                                            <div class="invalid-feedback">
                                                Código postal inválido.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="pais">
                                                País
                                                <small class="text-danger">*obligatorio</small>
                                            </label>
                                            <input id="pais" name="pais" type="text" class="form-control"
                                                   placeholder="">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="tab-pane fade" id="expediente" role="tabpanel"
                                 aria-labelledby="expediente-tab">
                                <fieldset>
                                    <div class="form-group d-flex justify-content-end">
                                        <button type="button" id="btn_agregar_archivo"
                                                class="btn btn-sm btn-supervisor-celeste">Agregar
                                        </button>
                                        <button type="button" id="btn_quitar_archivo"
                                                class="btn btn-sm btn-supervisor-red">Quitar
                                        </button>
                                    </div>
                                    <div id="container_files">
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <script>
                        class UIController {
                            constructor() {
                                this.DOMelements = {
                                    formulario: document.querySelector('#form_modificar'),
                                    btnActualizar: document.querySelector('#btn_actualizar'),
                                    datosGenerales: {
                                        inputProyecto: document.querySelector('#proyecto'),
                                        inputNombre: document.querySelector('#nombre'),
                                        inputApellidoPaterno: document.querySelector('#apellido_paterno'),
                                        inputApellidoMaterno: document.querySelector('#apellido_materno'),
                                        inputPuesto: document.querySelector('#puesto'),
                                        inputNoEmpleado: document.querySelector('#no_empleado'),
                                        selectGenero: document.querySelector('#genero'),
                                        inputEdad: document.querySelector('#edad'),
                                        inputCURP: document.querySelector('#curp'),
                                        inputRFC: document.querySelector('#rfc'),
                                        inputTelefono: document.querySelector('#telefono'),
                                        inputNSS: document.querySelector('#nss'),
                                        inputTipoSangre: document.querySelector('#tipo_sangre'),
                                        inputGastosMayores: document.querySelector('#gastos_mayores'),
                                        inputNivelEstudios: document.querySelector('#nivel_estudios'),
                                        inputTituloObtenido: document.querySelector('#nombre_estudio'),
                                        inputSalarioMensual: document.querySelector('#salario'),
                                        txtInfoAdicional: document.querySelector('#informacion_adicional'),
                                        mensajeCampos: document.querySelector('#mensaje_campos'),
                                        obligatorios: {
                                            inputProyecto: false,
                                            inputNombre: false,
                                            inputApellidoPaterno: false,
                                            inputApellidoMaterno: false,
                                            inputPuesto: false,
                                            inputNoEmpleado: false,
                                            inputSexo: false,
                                            inputEdad: false,
                                            inputCurp: false,
                                            inputRfc: false,
                                            inputTelefono: false,
                                            inputNss: false,
                                            inputTipoSangre: false,
                                            inputSalario: false,
                                            inputNombreEstudio: false,
                                            inputNivelEstudios: false
                                        }
                                    },
                                    direccion: {
                                        inputCalle: document.querySelector('#calle'),
                                        inputNumeroCalle: document.querySelector('#numero_calle'),
                                        inputColonia: document.querySelector('#colonia'),
                                        inputMunicipio: document.querySelector('#municipio'),
                                        inputEstado: document.querySelector('#estado'),
                                        inputCP: document.querySelector('#cp'),
                                        inputPais: document.querySelector('#pais'),
                                        obligatorios: {
                                            inputCalle: false,
                                            inputNumeroCalle: false,
                                            inputColonia: false,
                                            inputMunicipio: false,
                                            inputEstado: false,
                                            inputCP: false,
                                            inputPais: false
                                        }
                                    },
                                    expediente: {
                                        container: document.querySelector('#container_files'),
                                        btnAgregar: document.querySelector('#btn_agregar_archivo'),
                                        inputFile: document.querySelector('#file1'),
                                        btnQuitar: document.querySelector('#btn_quitar_archivo'),
                                        btnAnterior: document.querySelector('#btn_prev_expediente')
                                    }
                                };
                            }

                            habilitarBoton(boton) {
                                boton.disabled = false;
                            }

                            deshabilitarBoton(boton) {
                                boton.disabled = true;
                            }

                            deshabilitarBotones() {
                                this.DOMelements.datosGenerales.btnSiguiente.disabled = true;
                                this.DOMelements.direccion.btnSiguiente.disabled = true;
                            }

                            showInvalidMessage(elemento) {
                                elemento.classList.add('is-invalid');
                            }

                            hideInvalidMessage(elemento) {
                                elemento.classList.remove('is-invalid');
                            }

                            showValidMessage(elemento) {
                                elemento.classList.add('is-valid');
                            }

                            hideValidMessage(elemento) {
                                elemento.classList.remove('is-valid');
                            }

                            showMensajeCampos() {
                                this.DOMelements.datosGenerales.mensajeCampos.classList.remove('d-none');
                            }

                            hideMensajeCampos() {
                                this.DOMelements.datosGenerales.mensajeCampos.classList.add('d-none');
                            }

                            showAlertifyDatosGral() {
                                alertify.error(`Hay campos obligatorios sin llenar en la sección datos generales`);
                            }

                            showAlertifyDir() {
                                alertify.error(`Hay campos obligatorios sin llenar en la sección dirección`);
                            }

                            mostrarInfoEmpleado(empleado) {
                                const datosGenerales = empleado.datosGenerales[0],
                                    expediente = empleado.expedientes;
                                // Apartado Datos Generales
                                this.DOMelements.datosGenerales.inputNombre.value = datosGenerales.nombre;
                                this.DOMelements.datosGenerales.inputApellidoPaterno.value = datosGenerales.apellido_paterno;
                                this.DOMelements.datosGenerales.inputApellidoMaterno.value = datosGenerales.apellido_materno;

                                this.DOMelements.datosGenerales.inputProyecto.innerHTML = this.showSelectProyectos(empleado.datosGenerales[0].id_proyecto, empleado.proyectos);
                                this.DOMelements.datosGenerales.inputPuesto.value = datosGenerales.puesto;
                                this.DOMelements.datosGenerales.inputNoEmpleado.value = datosGenerales.no_empleado;
                                this.DOMelements.datosGenerales.selectGenero.value = datosGenerales.genero;
                                this.DOMelements.datosGenerales.inputEdad.value = datosGenerales.edad;
                                this.DOMelements.datosGenerales.inputCURP.value = datosGenerales.curp;
                                this.DOMelements.datosGenerales.inputRFC.value = datosGenerales.rfc;
                                this.DOMelements.datosGenerales.inputTelefono.value = datosGenerales.telefono;
                                this.DOMelements.datosGenerales.inputNSS.value = datosGenerales.nss;
                                this.DOMelements.datosGenerales.inputTipoSangre.value = datosGenerales.tipo_sangre;
                                this.DOMelements.datosGenerales.inputGastosMayores.value = datosGenerales.seguro_gastos_mayores;
                                this.DOMelements.datosGenerales.inputNivelEstudios.value = datosGenerales.grado_estudios;
                                this.DOMelements.datosGenerales.inputTituloObtenido.value = datosGenerales.nombre_estudio;
                                this.DOMelements.datosGenerales.inputSalarioMensual.value = datosGenerales.salario;
                                this.DOMelements.datosGenerales.txtInfoAdicional.value = datosGenerales.info_adicional;

                                // Apartado Direccion
                                this.DOMelements.direccion.inputCalle.value = datosGenerales.calle;
                                this.DOMelements.direccion.inputNumeroCalle.value = datosGenerales.numero;
                                this.DOMelements.direccion.inputColonia.value = datosGenerales.colonia;
                                this.DOMelements.direccion.inputMunicipio.value = datosGenerales.municipio;
                                this.DOMelements.direccion.inputEstado.value = datosGenerales.estado;
                                this.DOMelements.direccion.inputCP.value = datosGenerales.cp;
                                this.DOMelements.direccion.inputPais.value = datosGenerales.pais;

                                // Apartado expediente
                                this.mostrarArchivosExpediente(expediente);
                            }

                            showSelectProyectos(proyectoAsignado, proyectos) {
                                return proyectos.map(proyecto => {
                                    return `<option value="${proyecto.id_Proyecto}" ${proyectoAsignado == proyecto.id_Proyecto ? 'selected' : ''}>${proyecto.nombre_Proyecto}</option>`;
                                }).join('');
                            }

                            mostrarArchivosExpediente(files) {
                                let markup = '';
                                const options = [
                                    {
                                        option: "Acta de Nacimiento",
                                        value: "acta"
                                    }, {
                                        option: "CURP",
                                        value: "curp"
                                    }, {
                                        option: "RFC",
                                        value: "rfc"
                                    }, {
                                        option: "CV",
                                        value: "cv"
                                    }, {
                                        option: "Comprobante de Domicilio",
                                        value: "cbte_domicilio"
                                    }, {
                                        option: "Examen Médico",
                                        value: "examen_medico"
                                    }, {
                                        option: "Número de Seguro Social",
                                        value: "nss"
                                    }, {
                                        option: "Identificación Oficial",
                                        value: "id_oficial"
                                    }, {
                                        option: "Constancia de Estudio",
                                        value: "cons_estudio"
                                    }, {
                                        option: "Cédula Profesional",
                                        value: "cedula_prof"
                                    }, {
                                        option: "Carta de Recomendación",
                                        value: "carta_recomend"
                                    }, {
                                        option: "Contrato Laboral",
                                        value: "contrato_laboral"
                                    }, {
                                        option: "Constancia Laboral",
                                        value: "constancia_laboral"
                                    }, {
                                        option: 'Documentos Internos',
                                        value: 'doc_internos'
                                    }, {
                                        option: 'FMP - Formato Múltiple de Incidencias de Personal',
                                        value: 'fmi'
                                    }, {
                                        option: 'FMP - Formato Múltiple de Movimiento Personal',
                                        value: 'fmp'
                                    }
                                ];

                                if (files.length > 0) {
                                    files.forEach((curElemento, index) => {
                                        const tipoArchivo = curElemento.tipo_archivo;

                                        let newOptions = options.map((curElemento) => {
                                            return `<option value="${curElemento.value}" ${curElemento.value === tipoArchivo ? ' selected' : ''}>${curElemento.option}</option>`;
                                        }).join('');

                                        markup = `
                                            <div data-file="${index + 1}" data-idfile="${curElemento.id}" class="my-4 py-4 px-2 bg-light">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                               id="file${index + 1}" name="files[]"
                                                               lang="es">
                                                        <label class="custom-file-label nombre-archivo" for="file${index + 1}">
                                                            ${curElemento.nombre_archivo}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <label for="tipo_archivo_${index + 1}">Tipo de Archivo</label>
                                                    <select id="tipo_archivo_${index + 1}" class="custom-select tipo-file">
                                                        ${newOptions}
                                                    </select>
                                                </div>
                                            </div>
                                        `;
                                        this.DOMelements.expediente.container.insertAdjacentHTML('beforeend', markup);
                                        document.getElementById(`file${index + 1}`).addEventListener('change',
                                            (evento) => this.updateInputFile(evento.target, this));
                                    });
                                } else {
                                    markup = `
                                            <div data-file="1" class="my-4 py-4 px-2 bg-light">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                               id="file1" name="files[]"
                                                               lang="es">
                                                        <label class="custom-file-label nombre-archivo" for="file1">Seleccionar
                                                            Archivo</label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <label for="tipo_archivo_1">Tipo de Archivo</label>
                                                    <select id="tipo_archivo_1" class="custom-select tipo-file">
                                                        <option value="acta" selected>Acta de Nacimiento</option>
                                                        <option value="curp">CURP</option>
                                                        <option value="rfc">RFC</option>
                                                        <option value="cv">CV</option>
                                                        <option value="cbte_domicilio">Comprobante de Domicilio</option>
                                                        <option value="examen_medico">Examen médico</option>
                                                        <option value="nss">Número de Seguro Social</option>
                                                        <option value="id_oficial">Identificacion Oficial</option>
                                                        <option value="cons_estudio">Constancia de Estudio</option>
                                                        <option value="cedula_prof">Cédula Profesional</option>
                                                        <option value="carta_recomend">Carta de Recomendación</option>
                                                        <option value="contrato_laboral">Contrato Laboral</option>
                                                        <option value="constancia_laboral">Constancia Laboral
                                                        <option value="doc_internos">Documentos Internos</option>
                                                        <option value="fmi">FMP - Formato Múltiple de Incidencias de Personal</option>
                                                        <option value="fmp">FMP - Formato Múltiple de Movimiento de Personal</option>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        `;
                                    this.DOMelements.expediente.container.insertAdjacentHTML('beforeend', markup);
                                    document.getElementById('file1').addEventListener('change',
                                        (evento) => this.updateInputFile(evento.target, this));
                                }
                            }

                            agregarArchivo() {
                                let lastID = parseInt(this.DOMelements.expediente.container.lastElementChild.dataset.file);
                                if (lastID < 9) {
                                    const markup = `
                                            <div data-file="${lastID + 1}" class="my-4 py-4 px-2 bg-light">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                               id="file${lastID + 1}" name="files[]"
                                                               lang="es">
                                                        <label class="custom-file-label nombre-archivo" for="file${lastID + 1}">Seleccionar
                                                            Archivo</label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-0">
                                                    <label for="tipo_archivo_${lastID + 1}">Tipo de Archivo</label>
                                                    <select id="tipo_archivo_${lastID + 1}" class="custom-select tipo-file">
                                                        <option value="acta" selected>Acta de Nacimiento</option>
                                                        <option value="curp">CURP</option>
                                                        <option value="rfc">RFC</option>
                                                        <option value="cv">CV</option>
                                                        <option value="cbte_domicilio">Comprobante de Domicilio</option>
                                                        <option value="examen_medico">Examen médico</option>
                                                        <option value="nss">Número de Seguro Social</option>
                                                        <option value="id_oficial">Identificacion Oficial</option>
                                                        <option value="cons_estudio">Constancia de Estudio</option>
                                                        <option value="cedula_prof">Cédula Profesional</option>
                                                        <option value="carta_recomend">Carta de Recomendación</option>
                                                        <option value="contrato_laboral">Contrato Laboral</option>
                                                        <option value="constancia_laboral">Constancia Laboral</option>
                                                        <option value="doc_internos">Documentos Internos</option>
                                                        <option value="fmi">FMI - Formato Múltiple de Incidencias de Personal</option>
                                                        <option value="fmp">FMP - Formato Múltiple de Movimiento de Personal</option>
                                                    </select>
                                                </div>
                                            </div>
                                        `;
                                    this.DOMelements.expediente.container.insertAdjacentHTML('beforeend', markup);
                                    document.getElementById(`file${lastID + 1}`).addEventListener('change', (evento) => this.updateInputFile(evento.target, this));
                                }
                            }

                            quitarArchivo() {
                                let lastID = parseInt(this.DOMelements.expediente.container.lastElementChild.dataset.file);
                                if (lastID !== 1) {
                                    this.DOMelements.expediente.container.removeChild(this.DOMelements.expediente.container.lastElementChild);
                                }
                            }

                            updateInputFile(input) {
                                if (input.files && input.files[0]) {
                                    input.parentElement.querySelector('label').textContent = input.files[0].name;
                                }
                            }

                            getSelectTipoArchivo() {
                                let elementos = Object.values(document.querySelectorAll('.tipo-file'));

                                return elementos.map(elemento => elemento.value);
                            }

                            getIdsArchivos() {
                                const files = Array.from(this.DOMelements.expediente.container.children).filter(
                                    (elemento) => typeof elemento.dataset.idfile !== 'undefined');

                                return files.map(elemento => elemento.dataset.idfile);
                            }

                            getNombresArchivos() {
                                let elementos = Object.values(document.querySelectorAll('.nombre-archivo'));

                                return elementos.map(elemento => elemento.textContent.replace(/\n/g, '').trim());
                            }
                        }

                        class ModificarController extends UIController {
                            constructor() {
                                super();
                            }

                            validarDatosGenerales(elemento) {
                                switch (elemento.id) {
                                    case 'proyecto':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputProyecto = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputProyecto = false;
                                        }
                                        break;
                                    case 'nombre':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombre = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombre = false;
                                        }
                                        break;
                                    case 'apellido_paterno':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoPaterno = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoPaterno = false;
                                        }
                                        break;
                                    case 'apellido_materno':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoMaterno = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputApellidoMaterno = false;
                                        }
                                        break;
                                    case 'puesto':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputPuesto = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputPuesto = false;
                                        }
                                        break;
                                    case 'no_empleado':
                                        if (elemento.value && elemento.value.length <= 8) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNoEmpleado = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNoEmpleado = false;
                                        }
                                        break;

                                    case 'genero':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSexo = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSexo = false;
                                        }
                                        break;

                                    case 'tipo_sangre':
                                        if (elemento.value !== '0') {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTipoSangre = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTipoSangre = false;
                                        }
                                        break;

                                    case 'nombre_estudio':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombreEstudio = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNombreEstudio = false;
                                        }
                                        break;

                                    case 'nivel_estudios':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNivelEstudios = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNivelEstudios = false;
                                        }
                                        break;

                                    case 'gastos_mayores':
                                    case 'informacion_adicional':
                                        if (elemento.value) {
                                            this.showValidMessage(elemento);
                                        } else {
                                            this.hideValidMessage(elemento);
                                        }
                                        break;

                                    case 'edad':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = false;
                                            break;
                                        }
                                        if (/^[0-9]{1,2}?$/.test(elemento.value)) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputEdad = false;
                                        }
                                        break;

                                    case 'telefono':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 10) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputTelefono = false;
                                        }
                                        break;

                                    case 'nss':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 11) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputNss = false;
                                        }
                                        break;

                                    case 'curp':
                                        if (!elemento.value && elemento) {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = false;
                                            break;
                                        }
                                        if (elemento.value.length == 18) {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = true;
                                        } else {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputCurp = false;
                                        }
                                        break;

                                    case 'rfc':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = false;
                                            break;
                                        }
                                        if (elemento.value.length == 13) {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = true;

                                        } else {
                                            elemento.value = elemento.value.toUpperCase();
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputRfc = false;

                                        }
                                        break;

                                    case 'salario':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = false;
                                            break;
                                        }
                                        if (/^\d*(\.\d{1})?\d{0,1}$/.test(elemento.value)) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.datosGenerales.obligatorios.inputSalario = false;
                                        }
                                        break;

                                    default:
                                }
                            }

                            validarDireccion(elemento) {
                                switch (elemento.id) {
                                    case 'calle':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCalle = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCalle = false;
                                        }
                                        break;
                                    case 'colonia':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputColonia = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputColonia = false;
                                        }
                                        break;
                                    case 'municipio':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputMunicipio = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputMunicipio = false;
                                        }
                                        break;
                                    case 'estado':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputEstado = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputEstado = false;
                                        }
                                        break;
                                    case 'pais':
                                        if (elemento.value) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputPais = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputPais = false;
                                        }
                                        break;
                                    case 'numero_calle':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value)) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.direccion.obligatorios.inputNumeroCalle = false;
                                        }
                                        break;
                                    case 'cp':
                                        if (!elemento.value) {
                                            this.hideValidMessage(elemento);
                                            this.hideInvalidMessage(elemento);
                                            this.DOMelements.direccion.obligatorios.inputCP = false;
                                            break;
                                        }
                                        if (/^\d+$/.test(elemento.value) && elemento.value.length == 5) {
                                            this.hideInvalidMessage(elemento);
                                            this.showValidMessage(elemento);
                                            this.habilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.direccion.obligatorios.inputCP = true;
                                        } else {
                                            this.hideValidMessage(elemento);
                                            this.showInvalidMessage(elemento);
                                            this.deshabilitarBoton(this.DOMelements.btnActualizar);
                                            this.DOMelements.direccion.obligatorios.inputCP = false;
                                        }
                                        break;
                                }
                            }

                            validarSeccion(seccion) {
                                let result;
                                switch (seccion) {
                                    case 'datos_generales':
                                        result = !Object.values(this.DOMelements.datosGenerales.obligatorios).some(elemento => elemento === false);
                                        break;
                                    case 'direccion':
                                        result = !Object.values(this.DOMelements.direccion.obligatorios).some(elemento => elemento === false);
                                        break;
                                    default:
                                        result = false;
                                }
                                return result;
                            }

                            obtenerIdEmpleado() {
                                const idEmpleado = "id_empleado".replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                                let regex = new RegExp("[\\?&]" + idEmpleado + "=([^&#]*)"),
                                    results = regex.exec(location.search);
                                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
                            }


                            async obtenerEmpleado(idEmpleado) {
                                let formData = new FormData();
                                formData.append('id_empleado', idEmpleado);

                                this.empleado = await $.ajax({
                                    url: './index.php?controller=Empleados&action=datosEmpleadoByIdEmpleado',
                                    type: 'POST',
                                    contentType: false,
                                    data: formData,  // mandamos el objeto formdata que se igualo a la variable data
                                    processData: false,
                                    cache: false
                                });

                                this.empleado = JSON.parse(this.empleado);
                            }

                            async mostrarDatosEmpleado() {
                                const idEmpleado = this.obtenerIdEmpleado();
                                const finalInputs = Object.values(this.DOMelements.datosGenerales).concat(Object.values(this.DOMelements.direccion));

                                await this.obtenerEmpleado(idEmpleado);
                                this.mostrarInfoEmpleado(this.empleado);


                                finalInputs.forEach(elemento => {
                                    if (elemento instanceof HTMLElement) {
                                        elemento.dispatchEvent(new Event('change'));
                                    }
                                });
                            }

                            enviarFormulario(evento) {
                                evento.preventDefault();
                                const formData = new FormData(this.DOMelements.formulario);
                                formData.append('id_empleado', this.obtenerIdEmpleado());

                                if (!this.validarSeccion('datos_generales')) {
                                    this.showMensajeCampos();
                                    this.showAlertifyDatosGral();
                                    return;
                                } else if (!this.validarSeccion('direccion')) {
                                    this.showMensajeCampos();
                                    this.showAlertifyDir();
                                    return;
                                } else {
                                    this.hideMensajeCampos();
                                }

                                //tiposArchivo = JSON.stringify(this.getSelectTipoArchivo());

                                formData.append('tipo_archivo', JSON.stringify(this.getSelectTipoArchivo()));
                                formData.append('ids_archivos', JSON.stringify(this.getIdsArchivos()));
                                formData.append('nombres_archivos', JSON.stringify(this.getNombresArchivos()));

                                $.ajax({
                                    url: './index.php?controller=Empleados&action=guardarModificacion',
                                    type: 'POST',
                                    contentType: false,
                                    data: formData,  // mandamos el objeto formdata que se igualo a la variable data
                                    processData: false,
                                    cache: false,
                                    success: function (respuestaAjax) {
                                        respuestaAjax = JSON.parse(respuestaAjax);
                                        if (respuestaAjax[0] == 1) {
                                            alertify.notify(respuestaAjax[1].trim(), 'success', 2, () => {
                                                    setTimeout(() => {
                                                            document.location.href = 'index.php?controller=Empleados&action=index';
                                                        }, 200
                                                    );
                                                }
                                            );
                                        } else {
                                            alertify.notify(respuestaAjax[1].trim(), 'error', 5);
                                        }
                                    }
                                });
                            }

                            inputsEventListeners(elementos, seccion) {
                                if (seccion === 'datos_generales') {
                                    Object.values(elementos).forEach(elemento => {
                                        if (elemento instanceof HTMLElement) {
                                            ['change', 'keydown', 'keyup', 'keypress'].forEach(listener => {
                                                elemento.addEventListener(listener, (evento) => this.validarDatosGenerales(evento.target, this));
                                            });
                                        }
                                    });
                                } else if (seccion === 'direccion') {
                                    Object.values(elementos).forEach(elemento => {
                                        if (elemento instanceof HTMLElement) {
                                            ['change', 'keydown', 'keyup', 'keypress'].forEach(listener => {
                                                elemento.addEventListener(listener, (evento) => this.validarDireccion(evento.target, this));
                                            });
                                        }
                                    });
                                }
                            }

                            setEventListeners() {
                                this.DOMelements.formulario.reset();
                                this.mostrarDatosEmpleado();

                                this.DOMelements.btnActualizar.addEventListener('click', (evento) => this.enviarFormulario(evento, this));

                                this.DOMelements.expediente.btnAgregar.addEventListener('click', () => this.agregarArchivo(this));
                                this.DOMelements.expediente.btnQuitar.addEventListener('click', () => this.quitarArchivo(this));
                                //this.DOMelements.expediente.inputFile.addEventListener('change', (evento) => this.updateInputFile(evento.target, this));
                                this.inputsEventListeners(this.DOMelements.datosGenerales, 'datos_generales');
                                this.inputsEventListeners(this.DOMelements.direccion, 'direccion');
                            }

                            start() {
                                window.addEventListener('load', (evento) => {
                                    this.setEventListeners();
                                });
                            }
                        }

                        const modificar = new ModificarController();
                        modificar.start();
                    </script>
                    <? break;
                } ?>
            </div>
        </div>
    </div>
</div>

<? if ($allEmpleadosRes) { ?>
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
                            <th>No. Empleado</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? if (is_array($allEmpleadosRes) || is_object($allEmpleadosRes)) {
                            $contador = 1;
                            foreach ($allEmpleadosRes as $empleado) {
                                ?>
                                <tr>
                                    <td><?= $contador; ?></td>
                                    <td><?= $empleado->no_empleado; ?></td>
                                    <td><?= "$empleado->nombre $empleado->apellido_paterno $empleado->apellido_materno" ?></td>
                                    <td>
                                        <a href="#" data-trigger="hover" data-content="Resturar"
                                           data-toggle="popover"
                                           onclick="restaurarAjax(<?= $empleado->id_empleado; ?>, 'id_empleado', '<?= "$empleado->nombre $empleado->apellido_paterno $empleado->apellido_materno" ?>', 'Empleados', 'restaurar')">
                                            <i class="fa fa-retweet" aria-hidden="true"></i></a> &nbsp;
                                        &nbsp;

                                        <a href="#" data-trigger="hover"
                                           data-content="Borrar definitivamente"
                                           data-toggle="popover"
                                           onclick="borrarRegistroAjax(<?= $empleado->id_empleado; ?>, 'id_empleado', '<?= "$empleado->nombre $empleado->apellido_paterno $empleado->apellido_materno" ?>', 'Empleados', 'borrarDefinitivamente')">
                                            <i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <? $contador++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<? } ?>
