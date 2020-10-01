<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>

<div class="modal fade" tabindex="-1" role="dialog" id="nuevo_filtro_empleado">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="./index.php?controller=Asistencia&action=index" class="m-0" method="post"
                  id="frm_filtrar_empleados">
                <div class="modal-header bg-gradient-secondary">
                    <h5 class="modal-title text-center text-white w-100">Filtro de Empleados</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="proyecto">Elige un proyecto</label>
                        <select name="id_proyecto" id="proyecto" class="custom-select">
                            <option value="0">Todos</option>
                            <?php foreach ($allProyectos as $proyecto) { ?>
                                <option value="<?= $proyecto->id_Proyecto ?>"><?= $proyecto->nombre_Proyecto ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_final">Rango de fechas</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">De</span>
                            </div>
                            <input type="date" name="fecha_inicial"
                                   id="fecha_inicial"
                                   class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Hasta</span>
                            </div>
                            <input type="date" name="fecha_final"
                                   id="fecha_final"
                                   class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span>
                        <small class="text-danger invisible" id="container_alert"></small>
                    </span>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid py-4 flex-column justify-content-center p-3 animated fadeIn slow">
    <div class="row mt-3">
        <div class="col-11 shadow mx-auto">
            <div class="row bg-gradient-secondary rounded-top">
                <div class="col-10 d-flex align-items-center">
                    <p class="h4 text-white m-0 lead py-2"><?= $mensaje ?></p>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <span class="d-flex align-items-center">
                        <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=496"
                           data-trigger="hover" data-content="Nuevo reporte" data-toggle="popover"
                           class="p-2 m-1 h4 text-white">
                            <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                    </span>
                    <span class="d-flex align-items-center">
                        <a href="#" data-trigger="hover" data-content="Nuevo Filtro" data-toggle="popover"
                           onclick="popover('nuevo_filtro_empleado')" class="p-2 m-1 h4 text-white">
                            <i class="fa fa-search" aria-hidden="true"></i></a>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-2">
                    <div class="d-flex my-2 flex-column flex-md-row justify-content-between">
                        <div class="mr-md-3">
                            <div class="alert alert-light rounded" role="alert">
                                Periodo consultado del <strong><?= $this->formatearFecha($fechaInicial) ?></strong>
                                hasta
                                el <strong><?= $this->formatearFecha($fechaFinal) ?></strong>. <br>
                                <strong>Proyecto: </strong> <?=$nombreProyecto?>
                            </div>
                        </div>

                        <div class="d-flex d-md-inline-flex flex-column border border-light p-1">
                            <p class="font-weight-bold mb-1 text-center">
                                Abreviaturas
                            </p>
                            <div class="d-flex flex-column flex-md-row small">
                                <div class="mx-2 d-flex flex-row flex-wrap">
                                    <div class="d-flex m-1">
                                        <span class="border p-1 text-white" style="background-color: #21bf73">A</span>
                                        <span class="border p-1 flex-grow-1">Asistencia</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1 bg-info text-white">A</span>
                                        <span class="border p-1 flex-grow-1">Asistencia en otro Proyecto</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">P</span>
                                        <span class="border p-1 flex-grow-1">Permiso con Goce</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">D</span>
                                        <span class="border p-1 flex-grow-1">Descanso</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1 text-white" style="background-color: #d32626">F</span>
                                        <span class="border p-1 flex-grow-1">Falta</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">V</span>
                                        <span class="border p-1 flex-grow-1">Vacaciones</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">SR</span>
                                        <span class="border p-1 flex-grow-1">Sin Reporte</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">I</span>
                                        <span class="border p-1 flex-grow-1">Incapacidad</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">PS</span>
                                        <span class="border p-1 flex-grow-1">Permiso Sin Goce</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">S</span>
                                        <span class="border p-1 flex-grow-1">Suspensión</span>
                                    </div>
                                    <div class="d-flex m-1">
                                        <span class="border p-1">O</span>
                                        <span class="border p-1 flex-grow-1">Oficina</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped thead-dark">
                            <thead class="bg-primary text-light">
                            <tr>
                                <th class="align-middle">No. Empleado</th>
                                <th class="align-middle">Nombre</th>
                                <th class="align-middle">Proyecto</th>
                                <th class="align-middle">
                                    <span class="d-block">Periodo de Incidencias</span>
                                    <div class="d-flex justify-content-center mt-1">
                                        <? for ($rangoDias[0]; $rangoDias[0] <= $rangoDias[1]; $rangoDias[0]++) { ?>
                                            <span class="border border-white px-3 py-2 text-center"
                                                  style="min-width: 50px"><?= $rangoDias[0] ?></span>
                                        <? } ?>
                                    </div>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <? if (is_array($infoEmpleadosAsistencia) || is_object($infoEmpleadosAsistencia)) {
                                foreach ($infoEmpleadosAsistencia as $empleado) { ?>
                                    <tr>
                                        <td><?= $empleado['infoEmpleado']->no_empleado; ?></td>
                                        <td><?= "{$empleado['infoEmpleado']->nombre} {$empleado['infoEmpleado']->apellidos}" ?></td>
                                        <td><?= $empleado['infoEmpleado']->nombre_Proyecto; ?></td>

                                        <td class="d-flex justify-content-center">
                                            <? foreach ($empleado['infoAsistencia'] as $incidencia) {
                                                if ($incidencia->tipo == 'CP') { ?>
                                                    <span class="user-select-none px-3 py-2 text-center bg-info text-white" data-toggle="tooltip"
                                                          data-placement="right"
                                                          title="Se presento en: <?= $incidencia->proyecto ?>"
                                                          style="min-width: 50px">A</span>
                                                <? } elseif ($incidencia->tipo == 'F') { ?>
                                                    <span class="user-select-none px-3 py-2 text-center text-white" data-toggle="tooltip"
                                                          data-placement="right"
                                                          title="Falta"
                                                          style="min-width: 50px; background-color: #d32626">F</span>
                                                <? } elseif ($incidencia->tipo == 'A') { ?>
                                                    <span class="user-select-none px-3 py-2 text-center text-white" data-toggle="tooltip"
                                                          data-placement="right"
                                                          title="Asistencia"
                                                          style="min-width: 50px; background-color: #21bf73">A</span>
                                                <? } else { ?>
                                                    <span class="user-select-none px-3 py-2 text-center"
                                                          style="min-width: 50px"><?= $incidencia->tipo ?></span>
                                                <? }
                                            } ?>
                                        </td>
                                    </tr>
                                <? }
                            } ?>
                            </tbody>
                        </table>
                        <script>
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip()
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inpFechaInicial = document.querySelector('#fecha_inicial'),
        inpFechaFinal = document.querySelector('#fecha_final'),
        frmFiltrarEmpleado = document.querySelector('#frm_filtrar_empleados'),
        containerAlerta = document.querySelector("#container_alert");

    function enviarFormulario(evento) {
        if (moment(inpFechaInicial.value).format('YYYY-MM-DD') > moment(inpFechaFinal.value).format('YYYY-MM-DD')) {
            containerAlerta.innerHTML = "La fecha inicial es mayor a la fecha final.";
            containerAlerta.classList.replace('invisible', 'visible');
            evento.preventDefault();
        } else if (moment(inpFechaInicial.value).format('MM') !== moment(inpFechaFinal.value).format('MM')) {
            containerAlerta.innerHTML = "Las fechas deben ser del mismo mes.";
            containerAlerta.classList.replace('invisible', 'visible');
            evento.preventDefault();
        } else
            containerAlerta.classList.replace('visible', 'invisible');
    }

    frmFiltrarEmpleado.addEventListener('submit', enviarFormulario);
</script>
