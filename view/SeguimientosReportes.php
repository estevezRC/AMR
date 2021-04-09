<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<style>
    .dropdown-item:hover {
        color: #43aa8b !important;
    }

    .selected {
        padding: 3px 3px !important;
        transform: none;
    }
</style>

<?php
if ($action == "index" || $action == "busqueda") { ?>
    <?php
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::: BITACORA ::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    switch ($tipo_Reporte) {
        case "busqueda": ?>
            <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
                <div class="row pt-4 d-flex justify-content-center">
                    <div class="col-11 p-0 shadow">
                        <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                            <div class="col-sm-10 d-flex align-items-center">
                                <h4 class="text-white m-0 py-2">
                                    Búsqueda
                                </h4>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <p class="m-0 mb-3 px-1 py-2 bg-light"><?= $mensaje; ?></p>
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th class="align-middle">ID</th>
                                    <th class="align-middle">Tipo reporte</th>
                                    <th class="align-middle">Título</th>
                                    <th class="align-middle">Fecha</th>
                                    <th class="align-middle">Generado por</th>
                                    <th class="align-middle">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr>
                                            <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->nombre_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                            <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                            <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                                            <td class="text-center">
                                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>"
                                                   data-trigger="hover" data-content="Ver detalle"
                                                   data-toggle="popover">
                                                    <i class="fa fa-search"></i></a>
                                                <br/>
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

            <?php break;

        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*::::::::::::::::::::::::::::::::::::::::::::::::::: REPORTES ::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

        case "0,1": ?>
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
                                    <a class="px-2 m-1 h4 text-white"
                                       href="index.php?controller=LlenadosReporte&action=index&tipo=0,1"
                                       data-trigger="hover" data-content="Nuevo" data-toggle="popover">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </a>
                                <? }
                                if (getAccess(16, $decimal)) { ?>
                                    <a class="px-2 m-1 h4 text-white"
                                       href="descargables/seguimientosexcel.php?tipo_Reporte=<?= $tipo_Reporte; ?>"
                                       data-trigger="hover" data-content="Descargar lista" data-toggle="popover">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                <? } ?>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th>ID TICKET</th>
                                    <th>Tipo reporte</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Generado por</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                        <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->nombre_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                        <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                        <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_paterno . " " . $seguimientoreporte->apellido_materno; ?></td>
                                        <td>
                                            <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>"
                                               data-trigger="hover" data-content="Ver detalle" data-toggle="popover">
                                                <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp; &nbsp;
                                            <? if ($seguimientoreporte->id_Etapa != 23) {
                                                if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                    <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>&tipo_Reporte=<?php echo $seguimientoreporte->tipo_Reporte; ?>&return=1"
                                                       data-trigger="hover" data-content="Modificar"
                                                       data-toggle="popover">
                                                        <i class="fa fa-pencil-square-o"
                                                           aria-hidden="true"></i></a>&nbsp; &nbsp;

                                                    <a href="#" data-trigger="hover" data-content="Borrar"
                                                       data-toggle="popover"
                                                       onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrar')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
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
                </div>
            </div>
            <? break;
        case "2": ?>
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
                                    <a class="px-2 m-1 h4 text-white"
                                       href="index.php?controller=LlenadosReporte&action=index&tipo=2"
                                       data-trigger="hover" data-content="Nuevo" data-toggle="popover">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </a>
                                <? }
                                if (getAccess(16, $decimal)) { ?>
                                    <a class="px-2 m-1 h4 text-white"
                                       href="descargables/seguimientosexcel.php?tipo_Reporte=<?php echo $tipo_Reporte; ?>"
                                       data-trigger="hover" data-content="Descargar lista" data-toggle="popover">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                <? } ?>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th>ID TICKET</th>
                                    <th>Tipo reporte</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Generado por</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                        <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->nombre_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                        <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                        <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_paterno . " " . $seguimientoreporte->apellido_materno; ?></td>
                                        <td>
                                            <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>"
                                               data-trigger="hover" data-content="Ver detalle" data-toggle="popover">
                                                <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp;
                                            <? if ($seguimientoreporte->id_Etapa != 23) {
                                                if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                    <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>&tipo_Reporte=<?= $seguimientoreporte->tipo_Reporte; ?>&return=1"
                                                       data-trigger="hover" data-content="Modificar"
                                                       data-toggle="popover">
                                                        <i class="fa fa-pencil-square-o"
                                                           aria-hidden="true"></i></a>&nbsp;&nbsp; &nbsp;

                                                    <a href="#" data-trigger="hover" data-content="Borrar"
                                                       data-toggle="popover"
                                                       onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrar')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
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
                </div>
            </div>
            <? break;

        case "3": ?>
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
                                    <a class="px-2 m-1 h4 text-white"
                                       href="index.php?controller=LlenadosReporte&action=index&tipo=3"
                                       data-trigger="hover" data-content="Nuevo" data-toggle="popover">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </a>
                                <? }
                                if (getAccess(16, $decimal)) { ?>
                                    <a class="px-2 m-1 h4 text-white"
                                       href="descargables/seguimientosexcel.php?tipo_Reporte=<?php echo $tipo_Reporte; ?>"
                                       data-trigger="hover" data-content="Descargar lista" data-toggle="popover">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                <? }
                                if (getAccess(16, $decimal)) { ?>
                                    <a class="px-2 m-1 h4 text-white"
                                       href="#" data-trigger="hover" data-content="Vincular inventario ubicación"
                                       data-toggle="popover" onclick="popover('myModalAddConfiguracion')">
                                        <i class="fas fa-link" aria-hidden="true"></i></a>
                                <? } ?>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th>ID TICKET</th>
                                    <th>Tipo reporte</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Generado por</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                        <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->nombre_Reporte; ?></td>
                                        <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                        <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                        <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_paterno . " " . $seguimientoreporte->apellido_materno; ?></td>
                                        <td>
                                            <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>"
                                               data-trigger="hover" data-content="Ver detalle" data-toggle="popover">
                                                <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp;
                                            <? if ($seguimientoreporte->id_Etapa != 23) {
                                                if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                    <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>&tipo_Reporte=<?= $seguimientoreporte->tipo_Reporte; ?>&return=1"
                                                       data-trigger="hover" data-content="Modificar"
                                                       data-toggle="popover">
                                                        <i class="fa fa-pencil-square-o"
                                                           aria-hidden="true"></i></a>&nbsp;&nbsp; &nbsp;

                                                    <a href="#" data-trigger="hover" data-content="Borrar"
                                                       data-toggle="popover"
                                                       onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrar')">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
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
                </div>
            </div>
            <?php
            break;


        /********************************* Listado de Resportes de Incidencia *****************************************/
        case "reportesIncidencia": ?>
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
                                <? if (!empty($reportesSinConfigurar)) {
                                    if (getAccess(64, $decimal)) { ?>
                                        <a class="px-2 m-1 h4 text-white"
                                           href="index.php?controller=LlenadosReporte&action=index&tipo=1"
                                           data-trigger="hover" data-content="Nuevo" data-toggle="popover">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </a>
                                    <? }
                                    if (getAccess(16, $decimal)) { ?>
                                        <a class="px-2 m-1 h4 text-white"
                                           href="descargables/seguimientosexcel.php?tipo_Reporte=<?= $tipo_Reporte; ?>"
                                           data-trigger="hover" data-content="Descargar lista" data-toggle="popover">
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                    <? }
                                } ?>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th>ID TICKET</th>
                                    <th>Tipo de incidencia</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Generado por</th>
                                    <th>Estado de la incidencia</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>

                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                            <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->campo_TipoIncidente; ?></td>
                                            <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                            <td><?= $this->formatearFecha($seguimientoreporte->Fecha); ?></td>
                                            <td><?= $this->formatearHora($seguimientoreporte->Fecha); ?></td>
                                            <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                                            <td>
                                                <? require_once 'core/FuncionesCompartidas.php';
                                                $funciones = new FuncionesCompartidas();
                                                $icon = $funciones->iconosEstadoReporte($seguimientoreporte->id_Etapa);

                                                if ($seguimientoreporte->id_Etapa != 23) {
                                                    echo $icon;
                                                } ?>
                                            </td>
                                            <td>
                                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>"
                                                   data-trigger="hover" data-content="Ver detalle"
                                                   data-toggle="popover">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp;
                                                <? if ($seguimientoreporte->id_Etapa != 23) {
                                                    if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                        <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?= $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?= $seguimientoreporte->id_Reporte2; ?>&return=1"
                                                           data-trigger="hover" data-content="Modificar"
                                                           data-toggle="popover">
                                                            <i class="fa fa-pencil-square-o"
                                                               aria-hidden="true"></i></a> &nbsp;&nbsp;

                                                        <a href="#" data-trigger="hover" data-content="Borrar"
                                                           data-toggle="popover"
                                                           onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrar')">
                                                            <i class="fa fa-trash"
                                                               aria-hidden="true"></i></a> &nbsp;&nbsp;
                                                    <? }
                                                }
                                                if ($seguimientoreporte->id_Reporte_Seguimiento != 0) {
                                                    if ($seguimientoreporte->id_Etapa != 5) {
                                                        if ($seguimientoreporte->id_Etapa != 23) { ?>
                                                            <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte_Seguimiento; ?>&id_Gpo_Valores_Reporte=<?php echo $seguimientoreporte->Id_Reporte; ?>&tipo_Reporte=<?php echo $seguimientoreporte->tipo_Reporte; ?>"
                                                               data-trigger="hover"
                                                               data-content="Seguimiento a incidencia"
                                                               data-toggle="popover" data-placement="left">
                                                                <img src="img/icons_Status/add.png" width="17px"
                                                                     alt="añadir"></a>
                                                            <?php
                                                        }
                                                    }
                                                } ?>
                                                <br/>
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
            <? break;
        /********************************* END Listado de Resportes de Incidencia *************************************/


        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*:::::::::::::::::::::::::::::::::::::::::::::::: DOCUMENTOS BIM ::::::::::::::::::::::::::::::::::::::::::::*/
        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        case "5": ?>

            <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
                <div class="row pt-4 d-flex justify-content-center">
                    <div class="col-11 p-0 shadow">
                        <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                            <div class="col-sm-10 d-flex align-items-center">
                                <h4 class="text-white m-0">
                                    <?= $mensaje; ?>
                                </h4>
                            </div>

                            <div class="col-sm-2 d-flex justify-content-center align-items-center">
                                <? if (getAccess(64, $decimal)) { ?>
                                    <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?= $allseguimientosreporte[0]->id_Reporte2 ?>&tipo_Reporte=5&codigo=<?= $codigoPlano ?>"
                                       data-trigger="hover" data-content="Nueva" data-toggle="popover"
                                       class="p-2 m-1 h4 text-white">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </a>
                                <? } ?>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th class="align-middle">ID TICKET</th>
                                    <th class="align-middle">Título</th>
                                    <th class="align-middle">Fecha</th>
                                    <th class="align-middle">Hora</th>
                                    <th class="align-middle">Generado por</th>
                                    <th class="align-middle">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                            <td><?= $seguimientoreporte->Id_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->titulo_Reporte . ' - ' . $seguimientoreporte->numeroPlano; ?></td>
                                            <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                            <td><?= $seguimientoreporte->Hora; ?></td>
                                            <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                                            <td>
                                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>"
                                                   data-trigger="hover" data-content="Ver detalle"
                                                   data-toggle="popover">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp; &nbsp;
                                                <? if ($seguimientoreporte->id_Etapa != 23) {
                                                    if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                                        <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>"
                                                           data-trigger="hover" data-content="Modificar"
                                                           data-toggle="popover">
                                                            <i class="fa fa-pencil-square-o"
                                                               aria-hidden="true"></i></a> &nbsp; &nbsp;

                                                        <a href="#" data-trigger="hover" data-content="Borrar"
                                                           data-toggle="popover"
                                                           onclick="borrarRegistroAjax(<?= $seguimientoreporte->Id_Reporte; ?>, 'id_gpo_valores', '<?= $seguimientoreporte->titulo_Reporte . ' - ' . $seguimientoreporte->numeroPlano; ?>', 'LlenadosReporte', 'borrar')">
                                                            <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                        <?
                                                    }
                                                } ?>
                                                <br/>
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
            <? break;

        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*:::::::::::::::::::::::::::::::::::::::::::::::::PAPALERA ::::::::::::::::::::::::::::::::::::::::::::::::::*/
        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

        case "papelera": ?>
            <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
                <div class="row pt-4 d-flex justify-content-center">
                    <div class="col-11 p-0 shadow">
                        <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                            <div class="col-sm-10 d-flex align-items-center">
                                <h4 class="text-white m-0 py-2">
                                    <?= $mensaje; ?>
                                </h4>
                            </div>
                        </div>
                        <div class="p-2 table-responsive-md">
                            <table id="example" class="table table-striped">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th>ID TICKET</th>
                                    <th>Tipo reporte</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Generado por</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? if (is_array($allseguimientosreporte) || is_object($allseguimientosreporte)) {
                                    foreach ($allseguimientosreporte as $seguimientoreporte) { ?>
                                        <tr style="color:<?php echo $color; ?>;">
                                            <td><?= $seguimientoreporte->id_Gpo_Valores_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->nombre_Reporte; ?></td>
                                            <td><?= $seguimientoreporte->titulo_Reporte; ?></td>
                                            <td><?= $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                                            <td><?= $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_paterno .
                                                " " . $seguimientoreporte->apellido_materno; ?></td>
                                            <td>
                                                <a href="#" data-trigger="hover" data-content="Restaurar"
                                                   data-toggle="popover"
                                                   onclick="restaurarAjax(<?= $seguimientoreporte->id_Gpo_Valores_Reporte; ?>, 'id_gpo_valores',
                                                       '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'restaurarReporteLlenado')">
                                                    <i class="fa fa-retweet" aria-hidden="true"></i></a> &nbsp; &nbsp;

                                                <a href="#" data-trigger="hover" data-content="Borrar definitivamente"
                                                   data-toggle="popover"
                                                   onclick="borrarRegistroAjax(<?= $seguimientoreporte->id_Gpo_Valores_Reporte; ?>, 'id_gpo_valores',
                                                       '<?= $seguimientoreporte->titulo_Reporte; ?>', 'LlenadosReporte', 'borrarDef')">
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
            <? break;
    }
} ?>




<!-- ************************************** MODAL PARA NUEVA CONFIGURACION ***************************************** -->
<div class="modal fade" id="myModalAddConfiguracion" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="myModalLabel"> Nueva Configuración </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="nueva_configuracion">

                    <div class="form-group" id="container_select_usuarios">
                        <label for="id_reporte" class="col-form-label">
                            Reporte <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <select class="selectpicker form-control" id="id_reporte" name="id_reporte"
                                data-live-search="true" title="Seleccionar Reporte" onchange="getPlantilla()" required>
                            <? array_map(function ($ubicacion) {
                                echo "<option value='$ubicacion->id_Reporte' data-nombre='$ubicacion->nombre_Reporte'>
                                        $ubicacion->nombre_Reporte
                                    </option>";
                            }, $reportesUbicacion); ?>
                        </select>
                    </div>

                    <div class="form-group" id="container_select_usuarios">
                        <label for="id_plantilla" class="col-form-label">
                            Plantilla <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <select class="selectpicker form-control" id="id_Plantilla" name="id_Plantilla"
                                data-live-search="true" title="Seleccionar Plantilla"  required>
                        </select>
                    </div>

                    <div class="form-group" id="container_select_plazas">
                        <label for="componentes" class="col-form-label">
                            Componentes <small class="text-danger">(* obligatorio)</small>
                        </label>
                        <select class="selectpicker form-control" id="componentes" name="componentes[]" data-live-search="true"
                                multiple title="Seleccionar Componentes" required>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger float-right my-auto shadow">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ***************************************** END MODAL PARA NUEVA CONFIGURACION ********************************** -->


<script>
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

    function getPlantilla() {
        let idReporte = $('#id_reporte').val();
        $.ajax({
            data: {id_Reporte: idReporte},
            url: "index.php?controller=SeguimientosReporte&action=getAllReportesByIdPlantilla",
            method: "POST",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);
                let id_Plantilla = $("#id_Plantilla");
                id_Plantilla.empty();

                let x = 0;
                $.each(respuestaJSON, function () {
                    id_Plantilla.append(`<option value='${respuestaJSON[x].id_Gpo_Valores_Reporte}' data-titulo='${respuestaJSON[x].titulo_Reporte}'> ${respuestaJSON[x].titulo_Reporte} </option>`);
                    x++;
                });

                $('.selectpicker').selectpicker('refresh');
                getComponentes();
            }
        });
    }

    function getComponentes() {
        $.ajax({
            url: "index.php?controller=SeguimientosReporte&action=getAllComponentesByProyecto",
            method: "GET",
            success: function (response) {
                let respuestaJSON = $.parseJSON(response);
                //console.log(respuestaJSON);
                let componentes = $("#componentes");
                componentes.empty();

                let x = 0;
                $.each(respuestaJSON, function () {
                    componentes.append(`<option value='${respuestaJSON[x].id_Gpo_Valores_Reporte}' data-titulo='${respuestaJSON[x].titulo_Reporte}'
                    data-serie='${respuestaJSON[x].numero_Serie}' data-familia='${respuestaJSON[x].familia}'>${respuestaJSON[x].numero_Serie} - ${respuestaJSON[x].familia} - ${respuestaJSON[x].titulo_Reporte} </option>`);
                    x++;
                });

                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function sendData(data, event) {
        event.preventDefault();
        const datas = new FormData(data);

        const response = $.ajax({
            method: 'POST',
            url: 'index.php?controller=SeguimientosReporte&action=setInventarioUbicacion',
            contentType: false,
            data: datas,  // mandamos el objeto formdata que se igualo a la variable data
            processData: false,
            cache: false
        });

        response.done(function (data) {
            let response = $.parseJSON(data);
            //console.log(response);

            if (response.status) {
                alertify.success(response.mensaje);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                alertify.error(response.mensaje);
            }
        });
    }


    $("#nueva_configuracion").validate({submitHandler: sendData});
</script>

