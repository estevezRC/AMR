<script src="js/tabla.js"></script>

<script>

    let accion = '<?php echo $action; ?>';
    if (accion == 'index') {
        getUbicacion();
    }

    function getUbicacion() {
        if (navigator.geolocation) {
            function exitoso(pos) {
                let crd = pos.coords;
                $("#latitudm").val(crd.latitude);
                $("#longitudm").val(crd.longitude);
            }

            navigator.geolocation.getCurrentPosition(exitoso);
        } else {
            console.log("Geolocalización no soportada por el buscador!");
        }
    }
</script>


<?php

if ($action == "index") {

    ?>


    <div class="container p-3 mt-8 bg-light">

        <div class="container" style="padding: 0 40px;">
            <div class="col-sm-10 d-flex align-items-center">
                <h3 class="text-secondary">
                    <?php echo $mensaje; ?>
                </h3>
            </div>
        </div>

        <div class="container" style="padding: 0 55px;">
            <form action="index.php?controller=Plantilla&action=guardarPlantilla" method="post" class="form-horizontal">

                <label> Selecciona alguna plantilla </label>
                <select id="id_Plantilla" name="id_Plantilla" class="form-control">
                    <option value="1"> Reporte de trabajo</option>
                    <!--<option value="2"> Reporte de bitácora</option>-->
                    <!--<option value="3"> Reporte de Ubicación</option>-->
                    <option value="4"> Ninguna Plantilla</option>
                </select>

                <div class="form-group" id="coordenadas" hidden>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Latitud</label>
                            <input type="text" name="latitud" id="latitudm" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Longitud</label>
                            <input type="text" name="longitud" id="longitudm" value="0" class="form-control">
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-w-m btn-danger pull-right" onclick="getUbicacion()"> Empezar ahora
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>

    <?php
}

