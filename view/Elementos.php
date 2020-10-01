<script src="js/tabla.js"></script>

<script>

    $(document).ready(function () {
        let select = $('#codigos').val();
        if (select == 0)
            $("#verElemento").attr("disabled", true);
        else
            $('#verElemento').removeAttr("disabled");

    });

</script>


<?php
/*--- ACCION INDEX: MUESTRA TODAS LAS AREAS ---*/
if (($action == "index") || ($action == "estudioFecha")) {
    ?>

<?php }

if ($action == "index") { ?>

    <div class="container d-flex justify-content-center mt-8">
        <div class="col-sm-10 bg-light p-3">
            <h3 class="m-0 text-secondary"><strong><?php echo $mensaje; ?></strong></h3>
            <hr class="linea-separadora">
            <form action="<?php echo $helper->url("Elementos", "verElementos"); ?>" method="post"
                  class="form-horizontal">
                <br/>

                <label> Seleccionar Elemento </label>
                <select name="codigos" id="codigos" class="form-control">

                    <?php if ($allreportes == null) {
                        ?>
                        <option value="0"> Sin Elementos</option>
                        <?php
                    } else {
                        foreach ($allreportes as $codigo) {
                            ?>
                            <option id="<?php echo $codigo->id_Reporte; ?>"
                                    value="<?php echo $codigo->id_Reporte; ?>"> <?php echo $codigo->nombre_Reporte ?> </option>
                        <?php }
                    } ?>
                </select>
                <br>
                <div class="form-group text-right">
                    <button type="submit" id="verElemento" class="btn btn-danger" disabled>
                        Ver Elementos
                    </button>
                </div>

            </form>
        </div>
    </div>
    <?php

}

if ($action == "verElementos") { ?>

    <div class="container-fluid flex-column justify-content-center p-3">
        <div class="row d-flex justify-content-between">
            <div class="col-sm-10 d-flex align-items-center">
                <h3 class="text-secondary">
                    <?php echo $mensaje; ?>
                </h3>
            </div>

            <div class="col-sm-2 d-flex justify-content-center align-items-center">
                <?php if (getAccess(64, $decimal)) { ?>
                    <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?php echo $id_Reporte; ?>&tipo_Reporte=<?php echo $tipo_Reporte; ?>"
                       data-trigger="hover" data-content="Nuevo" data-toggle="popover"
                       class="p-2 m-2 btn-outline-secondary h4">
                        <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                <?php }
                if (getAccess(16, $decimal)) { ?>
                    <a href="descargables/seguimientosexcel.php?tipo_Reporte=<?php echo $tipo_Reporte; ?>&Id_Reporte=<?php echo $id_Reporte; ?>&nombre_Reporte=<?php echo $nombre_Reporte; ?>"
                       data-trigger="hover" data-content="Descargar lista" data-toggle="popover"
                       class="p-2 m-2 btn-outline-secondary h4">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                <?php } ?>
            </div>
        </div>

        <hr class="linea-separadora">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11">

                <table id="example" class="table table-striped">
                    <thead class="bg-primary text-light">
                    <th>ID TICKET</th>
                    <th>Tipo de Elemento</th>
                    <th>Identificador</th>
                    <th>Fecha</th>
                    <th>Generado por</th>
                    <th>Acciones</th>
                    </thead>
                    <tbody>
                    <?php
                    if (is_array($allseguimientosreportes) || is_object($allseguimientosreportes)) {
                        foreach ($allseguimientosreportes as $seguimientoreporte) {
                            ?>
                            <tr style="color:<?php echo $color; ?>;">
                            <td><?php echo $seguimientoreporte->Id_Reporte; ?></td>
                            <td><?php echo $seguimientoreporte->nombre_Reporte; ?></td>
                            <td><?php echo $seguimientoreporte->titulo_Reporte; ?></td>
                            <td><?php echo $this->formatearFecha($seguimientoreporte->Fecha2); ?></td>
                            <td><?php echo $seguimientoreporte->nombre_Usuario . " " . $seguimientoreporte->apellido_Usuario; ?></td>
                            <td class="text-center">

                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>"
                                   data-trigger="hover" data-content="Ver detalle" data-toggle="popover">
                                    <i class="fa fa-search" aria-hidden="true"></i></a> &nbsp;

                                <?php
                                //if ($seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) {
                                if (getAccess(8, $decimal) || $seguimientoreporte->id_Usuario == $_SESSION[ID_USUARIO_SUPERVISOR]) { ?>
                                    <a href="index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=<?php echo $seguimientoreporte->Id_Reporte; ?>&Id_Reporte=<?php echo $seguimientoreporte->id_Reporte2; ?>&tipo_Reporte=<?php echo $seguimientoreporte->tipo_Reporte; ?>"
                                       data-trigger="hover" data-content="Modificar" data-toggle="popover">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <?php }
                                //}
                                ?>
                                <br/>
                            </td>
                        <?php } ?>
                        </tr>
                    <?php }

                    ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <?php

}

if ($action == "llenarElementos") {
    ?>

    <div class="col-lg-6 col-lg-offset-3 login">
        <div class="nop">
            <h1><strong><?php echo $mensaje; ?></strong></h1>
            <form action="<?php echo $helper->url("LlenadosReporte", "mostrarreportenuevo"); ?>" method="post"
                  class="form-horizontal">
                <br/>

                <label> Seleccionar Elemento </label>
                <select name="Id_Reporte" id="Id_Reporte" class="form-control">
                    <?php foreach ($allreportes as $codigo) {
                        ?>
                        <option id="<?php echo $codigo->id_Reporte; ?>"
                                value="<?php echo $codigo->id_Reporte; ?>"> <?php echo $codigo->nombre_Reporte ?> </option>
                    <?php } ?>
                </select>
                <br>
                <button type="submit" class="btn btn-w-m btn-danger pull-right"/>
                LLenar Elemento </button>
            </form>
        </div>
    </div>
    <?php

}

