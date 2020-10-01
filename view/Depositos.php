<script src="js/tabla.js"></script>


<?php

if ($action == "index" || $action == "guardarDeposito") {

    ?>
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-sm-12 ibox-title">
                <div class="col-sm-10">
                    <h3><?php echo $mensaje; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox-content">

        <div class="row">
            <div class="col-md-12">
                <div class="col-sm-12">
                    <table id="example" class="display" style="font-size:10px;">
                        <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>CUENTA</th>
                            <th>REFERENCIA</th>
                            <th>CONCEPTO</th>
                            <th>DETALLE</th>
                            <th>CARGO</th>
                            <th>ABONO</th>
                            <th>SALDO</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        //var_dump($allDepositos);

                        setlocale(LC_MONETARY, 'es_MX');
                        if (is_array($allDepositos) || is_object($allDepositos)) {
                            foreach ($allDepositos as $dato) { ?>
                                <tr>
                                    <td><?php echo $dato->depFecha ?></td>
                                    <td><?php echo $dato->depCuenta ?></td>
                                    <td><?php echo $dato->depReferencia ?></td>
                                    <td><?php echo $dato->depConcepto ?></td>
                                    <td><?php echo $dato->depDetalle ?></td>

                                    <td><?php echo money_format('%.0n', $dato->depCargo) ?></td>
                                    <td><?php echo money_format('%.0n', $dato->depAbono) ?></td>
                                    <td><?php echo money_format('%.0n', $dato->depSaldo) ?></td>


                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <?php

} else if ($action == "cargarDeposito") {

    ?>
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-sm-12 ibox-title">
                <div class="col-sm-10">
                    <h3><?php echo $mensaje; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox-content">
        <div class="container">
            <div class="col-lg-8 col-lg-offset-2" style="margin-top: 2em;">

                <form action="index.php?controller=Depositos&action=guardarDeposito" method="post"
                      class="form-horizontal"
                      enctype="multipart/form-data">
                    <label> XML: </label>
                    <input type="file" id="xmldeposito" name="xmldeposito" class="form-control"
                           onchange="fileValidation()">
                    <br>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <input type="submit" id="btnGuardarDep" class="btn btn-w-m btn-danger" value="Guardar">
                        </div>
                    </div>
                </form>

                <script>
                    $(document).ready(function () {
                        $("#btnGuardarDep").attr("disabled", true);
                    });

                    function fileValidation() {
                        var fileInput = document.getElementById('xmldeposito');
                        var filePath = fileInput.value;
                        //console.log(filePath);
                        var namefile = filePath.substr(-13, 9);
                        var allowedExtensions = /(.xml)$/i;
                        if (!allowedExtensions.exec(filePath)) {
                            $("#btnGuardarDep").attr("disabled", true);
                            alertify.error('Sólo se pueden subir archivos con el nombre "depositos" y extensión ".xml"');
                        } else {
                            if (namefile != 'depositos') {
                                $("#btnGuardarDep").attr("disabled", true);
                                alertify.error('Sólo se pueden subir archivos con el nombre "depositos" y extensión ".xml"');
                            } else
                                $('#btnGuardarDep').removeAttr("disabled");
                        }
                    }
                </script>

            </div>
        </div>
    </div>


    <?php

}

?>