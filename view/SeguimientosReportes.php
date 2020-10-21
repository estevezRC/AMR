<script src="js/tabla.js"></script>
<script src="js/mensaje.js"></script>
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

