<script src="js/tabla.js"></script>

<script src="js/mensaje.js"></script>

<script>
    $(document).ready(function () {
        var insercion = <?php echo $insercion; ?>;
        var elemento = '<?php echo $newElemento; ?>';
        mensajes(insercion, elemento);

        $('.btn_modificar_area').on('click', function() {
           let id_area = $(this).attr('id');

           $.ajax({
              url: 'index.php?controller=Areas&action=modificar',
              method: 'POST',
               dataType: 'JSON',
              data:  {areaid : id_area},
               success: function (data) {
                   $('#areaidM').val(data.id_Area);
                   $('#areanombreM').val(data.nombre_Area)
               }
           });
            $('#myModal2').modal();
        });
    });
</script>

<div class="modal fade" id="myModalArea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Nueva Área </h4>

                <form action="<?php echo $helper->url("Areas", "guardarnuevo"); ?>" method="post"
                      class="form-horizontal">

                    <label class="control-label">Nombre:</label>
                    <input type="text" name="areanombre" class="form-control" required>

                    <!--<label class="control-label">Empresa:</label><br>
                    <select name="id_Empresa" class="form-control"/>
                    <?php
                    /*                    foreach($allempresas as $empresa) { */
                    ?>
                        <option name ="<?php /*echo $empresa->nombre_Empresa; */
                    ?>" id ="<?php /*echo $empresa->id_Empresa; */
                    ?>"
                                value ="<?php /*echo $empresa->id_Empresa; */
                    ?>"><?php /*echo $empresa->nombre_Empresa; */
                    ?></option>
                    <?php /*}*/
                    ?>
                    </select>-->
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


    <div class="modal fadeIn" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Modificar Área </h4>

                    <form action="<?php echo $helper->url("Areas", "guardarmodificacion"); ?>" method="post" class="form-horizontal">
                        <input type="hidden" name="areaid" id="areaidM" />

                        <label class="control-label">Nombre:</label>
                        <input type="text" name="areanombre" id="areanombreM" class="form-control" required>


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
                       onclick="popover('myModalArea')">
                        <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="p-2 table-responsive-md">
                <table id="example" class="table table-striped">
                    <thead class="bg-primary text-light">
                    <tr>
                        <th class="align-middle">ID</th>
                        <th class="align-middle">Nombre</th>
                        <th class="align-middle">Fecha</th>
                        <th class="align-middle">Empresa</th>
                        <th class="align-middle">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? if (is_array($allareas) || is_object($allareas)) {
                        $contadorAreas = 1;
                        foreach ($allareas as $area) { ?>
                            <tr>
                                <td><?= $contadorAreas; ?></td>
                                <td><?= $area->nombre_Area; ?></td>
                                <td><?= $this->formatearFecha($area->fecha_Registro_Area); ?></td>
                                <td><?= $area->nombre_Empresa; ?></td>
                                <td>
                                    <a href="#" id="<?= $area->id_Area; ?>" class="btn_modificar_area" data-trigger="hover" data-content="Modificar" data-toggle="popover">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;

                                    <a href="#" data-trigger="hover" data-content="Borrar" data-toggle="popover"
                                       onclick="borrarRegistro(<?= $area->id_Area; ?>, 'areaid', '<?= $area->nombre_Area; ?>', 'Areas', 'borrar')">
                                        <i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp;
                                </td>
                            </tr>
                            <? $contadorAreas++;
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
