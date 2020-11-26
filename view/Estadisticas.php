<script src="js/tabla.js"></script>

<? if ($action == 'index') { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top ">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $mensaje; ?>
                        </h4>
                    </div>
                </div>
                <div class="p-2 table-responsive-md">

                    <!-- Aqui va la tabla por EMPRESa -->

                </div>
            </div>
        </div>
    </div>
<? } elseif ($action == 'index2') { ?>
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top ">
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
                            <th>Id Inventario</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Observaciones</th>
                            <th>Movimiento</th>
                            <th>Responsable</th>
                            <th>Elemento</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (is_array($registros) || is_object($registros)) {
                            foreach ($registros as $registro) { ?>
                                <tr>
                                    <td><?= $registro->id_Gpo_Valores_Reporte; ?></td>
                                    <td><?= $registro->fecha; ?></td>
                                    <td><?= $registro->hora; ?></td>
                                    <td><?= $registro->observaciones; ?></td>
                                    <td><?= $registro->movimiento; ?></td>
                                    <td><?= $registro->responsable; ?></td>
                                    <td><?= $registro->elemento; ?></td>
                                    <td><?= $registro->cantidad; ?></td>
                                </tr>
                            <? } ?>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
<? }
