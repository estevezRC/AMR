<!-- Resources -->
<script src="js/leaflet/leaflet.js"></script>

<!-- LIBRERIA MARKER CLUSTER PARA AGRUPACION DE MARCAS EN EL MAPA -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<!-- LIBRERIA LEAFLET GROUP LAYER CONTROL, PARA AGRUPACION DE CAPAS EN EL MAPA-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-groupedlayercontrol/0.6.1/leaflet.groupedlayercontrol.js"
        integrity="sha512-2OAO6Vw7QqbRSoHqfdIhur/B7urhzltUGHOufhmGJRScSz8S0ZUyBp1ixI9BB0pLXIKqyQZ/cOwS4PgBTviT6Q=="
        crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>

<style>

    .legend {
        line-height: 18px;
        color: #333333;
        font-family: 'Open Sans', Helvetica, sans-serif;
        padding: 6px 8px;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        width: 200px;
        font-size: 15px;
        height: auto;
    }

    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }

    .legend img {
        width: 18px;
        height: 18px;
        float: left;
    }

    .legend p {
        font-size: 12px;
        line-height: 18px;
        margin: 0;
    }

    html, body {
        overflow-x: hidden;
        overflow-y: hidden;
    }

    .imgThales {
        width: 130px !important;
        margin-top: 6px;
    }

    .imgWatermark {
        width: 100px !important;
    }

    .leaflet-bottom .leaflet-control {
        margin-bottom: 60px !important;
    }


</style>

<?php if ($action == "index") { ?>



    <div class="container-fluid flex-column justify-content-center animated fadeIn margin-top-6 slow p-0">
        <div id="mapid" class="nt-6" style="height: 100vh;"></div>

    </div>

    <script>

        $(document).ready(function () {
            console.log('JQUERY WORKS');
            let fechaInicio = '<?= date('Y-m') . '-01'; ?>';
            let fechaFinal = ' <?= date('Y-m-t'); ?>';

            console.log('Inicio = ' + fechaInicio , 'Final = ' + fechaFinal)
            initMap();
        });

        function initMap() {
            console.log('INIT MAP')
            let idProyecto = '<?= $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>'
            let latitudInicial = '';
            if(idProyecto === '1') {
                latitudInicial = [23.6552962, -100.6733939]
            } else {
                latitudInicial = [15.7199872, -91.3559766]
            }

            let zoom = 5
            let token = "pk.eyJ1IjoiZ2V0aXRjb21wYW55IiwiYSI6ImNraHo4MXNxcDI0dGgycm4zOXR3YWhrcGgifQ.gjogsvX2eGpg8AJ9yM8LnA"
            let map = L.map('mapid').setView(latitudInicial, zoom, {});
            let server = `https://${document.domain}/supervisor/thales`;


            ///////////////////////////////////////////////////////////////
            // INICIAR ICONOS MAPA
            ///////////////////////////////////////////////////////////////
            let indicadorMapa = L.Icon.extend({
                options: {
                    shadowUrl: 'img/leaflet/sombra_indicador_amplia.png',
                    iconSize: [25, 25],
                    shadowSize: [25, 25],
                    iconAnchor: [18, 18],
                    shadowAnchor: [18, 18],
                    popupAnchor: [0, -6]
                }
            });

            var redIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_rojo.png'});
            var greenIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_verde_shadow.png'});
            var yellowIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_amarillo.png'});
            var greyIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_gris.png'});
            var pinkIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_rosa.png'});
            var orangeIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_naranja.png'});


            $.ajax({
                url: 'index.php?controller=Mapas&action=getAllUbicaciones',
                method: 'POST',
                data: {'tipoReporte' : 2},
                dataType: 'JSON',
                success: function (data) {
                    data.map(({latlng, description, icon}) => {
                        let coordenadas = [parseFloat(latlng[1]),parseFloat(latlng[0])];
                        console.log(coordenadas, icon);
                        switch (icon) {
                            case("oficina"):
                                iconomapa = redIcon;
                                icons = "Oficina";
                                break;
                            case("sistema"):
                                iconomapa = greenIcon;
                                icons = "Sistema";
                                break;
                            case("ptz"):
                                iconomapa = orangeIcon;
                                icons = "PTZ";
                                break;
                            case("adosamiento"):
                                iconomapa = greenIcon;
                                icons = "Adosamiento";
                                break;
                            case("cople"):
                                iconomapa = greyIcon;
                                icons = "Cople";
                                break;
                            case("registrofo"):
                                iconomapa = pinkIcon;
                                icons = "Registro FO";
                                break;
                            case("its"):
                                iconomapa = yellowIcon;
                                icons = "ITS´s";
                                break;
                            case("reportes"):
                                iconomapa = greyIcon;
                                icons = "Reportes";
                                break;
                        }

                        L.marker(coordenadas,{icon: iconomapa}).on('click', (event) => {
                            L.popup()
                                .setLatLng(coordenadas)
                                .setContent(description)
                                .openOn(map);
                        }).addTo(map);


                    })
                }
            });


            ///////////////////////////////////////////////////////////////
            // INICIAR LAYER MAPA
            ///////////////////////////////////////////////////////////////
            let layerMap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 20.5,
                zoomControl: false,
                minZoom: 6,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: token
            }).addTo(map);
            L.control.zoom()
        }

        function msjReports() {
            alertify.confirm('Mapa de reportes', '' + 'No hay registros de reportes, desea continuar', function () {
                    //document.location.href = 'index.php?controller=Graficas&action=mapaReportes';
                    alertify.error('No hay registros de reportes');
                },
                function () {
                    console.log('No continuar');
                    window.history.back();
                }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
        }

    </script>

<?php } ?>

<?php if ($action == "reportes") { ?>
    <div class="container-fluid flex-column justify-content-center animated fadeIn margin-top-6 slow p-0">
        <div id="mapid" class="nt-6" style="height: 100vh;"></div>
    </div>

    <script>

        $(document).ready(function () {
            console.log('JQUERY WORKS');
            let fechaInicio = '<?= date('Y-m') . '-01'; ?>';
            let fechaFinal = ' <?= date('Y-m-t'); ?>';

            console.log('Inicio = ' + fechaInicio , 'Final = ' + fechaFinal)
            initMap();
        });

        function initMap() {
            console.log('INIT MAP')
            let idProyecto = '<?= $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>'
            let latitudInicial = '';
            latitudInicial = [23.6552962, -100.6733939]

            let zoom = 5
            let token = "pk.eyJ1IjoiZ2V0aXRjb21wYW55IiwiYSI6ImNraHo4MXNxcDI0dGgycm4zOXR3YWhrcGgifQ.gjogsvX2eGpg8AJ9yM8LnA"
            let map = L.map('mapid').setView(latitudInicial, zoom, {});
            let server = `https://${document.domain}/supervisor/thales`;


            ///////////////////////////////////////////////////////////////
            // INICIAR ICONOS MAPA
            ///////////////////////////////////////////////////////////////
            let indicadorMapa = L.Icon.extend({
                options: {
                    shadowUrl: 'img/leaflet/sombra_indicador_amplia.png',
                    iconSize: [25, 25],
                    shadowSize: [25, 25],
                    iconAnchor: [18, 18],
                    shadowAnchor: [18, 18],
                    popupAnchor: [0, -6]
                }
            });

            var redIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_rojo.png'});
            var greenIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_verde_shadow.png'});
            var yellowIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_amarillo.png'});
            var greyIcon = new indicadorMapa({iconUrl: 'img/leaflet/indicador_circulo_gris.png'});


            $.ajax({
                url: 'index.php?controller=Mapas&action=getAllReports',
                method: 'POST',
                data: {'tipoReporte' : 0},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    data.map(({latlng, description, icon}) => {
                        let coordenadas = [parseFloat(latlng[1]),parseFloat(latlng[0])];
                        console.log(coordenadas, icon);
                        switch (icon) {
                            case("pc"):
                                iconomapa = redIcon;
                                icons = "P.C.";
                                break;
                            case("carril"):
                                iconomapa = greenIcon;
                                icons = "Carril";
                                break;
                            case("its"):
                                iconomapa = yellowIcon;
                                icons = "ITS´s";
                                break;
                            case("reportes"):
                                iconomapa = greyIcon;
                                icons = "Reportes";
                                break;
                        }

                        L.marker(coordenadas,{icon: iconomapa}).on('click', (event) => {
                            L.popup()
                                .setLatLng(coordenadas)
                                .setContent(description)
                                .openOn(map);
                        }).addTo(map);


                    })
                }
            });


            ///////////////////////////////////////////////////////////////
            // INICIAR LAYER MAPA
            ///////////////////////////////////////////////////////////////
            let layerMap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 20.5,
                zoomControl: false,
                minZoom: 6,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: token
            }).addTo(map);
            L.control.zoom()
        }

        function msjReports() {
            alertify.confirm('Mapa de reportes', '' + 'No hay registros de reportes, desea continuar', function () {
                    //document.location.href = 'index.php?controller=Graficas&action=mapaReportes';
                    alertify.error('No hay registros de reportes');
                },
                function () {
                    console.log('No continuar');
                    window.history.back();
                }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
        }

    </script>


<?php } ?>




