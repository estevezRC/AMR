<!-- Resources -->
<script src="js/amcharts/core.js"></script>
<script src="js/amcharts/charts.js"></script>
<script src="js/amcharts/dataviz.js"></script>
<script src="js/amcharts/animated.js"></script>

<?php if ($action == "index") { ?>
    <!-- ++++++++++++++++++++++++++++++++++  Codigo de la Vista ++++++++++++++++++++++++++++++++++++++++++ -->
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
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-5 my-md-0 my-3 px-2">
                                <div id="reportes_usuarios" class=" shadow-sm chartdiv mx-auto  bg-light"></div>
                            </div>
                            <div class="col-12 col-md-7 mb-3">
                                <div id="reportes_utilizados" class="shadow-sm chartdiv-600 mx-auto bg-light"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart code -->
    <script src="js/graficas.js"></script>
    <script>
        dashBoard(<?php echo json_encode($nuevoResul, JSON_NUMERIC_CHECK); ?>,
            <?php echo json_encode($reportes, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK); ?>); // end am4core.ready()
    </script>
<?php }

if ($action == "avances") { ?>
    <!-- ++++++++++++++++++++++++++++++++++  Codigo de la Vista ++++++++++++++++++++++++++++++++++++++++++ -->
    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-4 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between mb-3 bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            <?= $existeGantt ? $datosGenerales->actividad : $datosGenerales[0]->titulo_Reporte; ?>
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 py-3">
                        <div class="row justify-content-around">
                            <div class="col-sm-12 col-md-5 my-md-0 my-3 shadow-sm bg-light">
                                <div class="w-100">
                                    <p class="w-100 h5 text-center text-uppercase font-weight-bold p-1">Elementos</p>
                                    <? if ($existeGantt) { ?>
                                        <table class="table table-sm">
                                            <thead>
                                            <tr class="bg-color-third-3-2">
                                                <th scope="col">No.</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">% Avance</th>
                                                <th scope="col">Valor sobre Proyecto</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <? foreach ($desglose as $key => $elemento) { ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $elemento->info->actividad ?></td>
                                                    <td><?= ($elemento->info->porcentaje_avance ?? "0") . "%"; ?></td>
                                                    <!--<td><?php /*echo ($desglose[1][$key]) / ($elemento->Porcentaje / $elemento->Cantidad) . '%'; */ ?></td>-->

                                                    <td><?= "{$elemento->info->porcentaje}%"; ?></td>
                                                    <td>
                                                        <? if ($elemento->children) { ?>
                                                            <a href="index.php?controller=Graficas&action=avances&nodo=<?= $elemento->info->id_nodo ?>"
                                                               data-trigger="hover" data-content="Detalle de avances"
                                                               data-placement="left" data-toggle="popover">
                                                                <i class="fa fa-search"></i></a> &nbsp;
                                                        <? } ?>

                                                        <? if ($elemento->info->gpo_valores) { ?>
                                                            <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?= $elemento->info->gpo_valores ?>"
                                                               data-trigger="hover" data-content="Detalle del reporte"
                                                               data-toggle="popover">
                                                                <i class="fas fa-info-circle"></i></a>
                                                        <? } ?>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                            </tbody>
                                        </table>

                                        <p class="text-center">
                                            Total Avance: <?= $datosGenerales->perc_nodo ?>% <br>
                                            Total Proyecto: <?= $datosGenerales->porcentaje; ?>%
                                        </p>
                                    <? } else { ?>
                                        <table class="table table-sm">
                                            <thead>
                                            <tr class="table-supervisor-lightgreen">
                                                <th scope="col">No.</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">% Avance</th>
                                                <th scope="col">Valor sobre Proyecto</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($desglose[0] as $key => $elemento) { ?>
                                                <tr>
                                                    <td><?php echo $key + 1 ?></td>
                                                    <td><?php echo $elemento->titulo_Reporte ?></td>

                                                    <td><?php echo ($desglose[1][$key] * $elemento->Porcentaje) / (100 * $elemento->Cantidad) . '%'; ?></td>
                                                    <!--<td><?php /*echo ($desglose[1][$key]) / ($elemento->Porcentaje / $elemento->Cantidad) . '%'; */ ?></td>-->

                                                    <td><?php echo $elemento->Porcentaje / $elemento->Cantidad ?>%</td>

                                                    <td>
                                                        <a href="index.php?controller=Graficas&action=avancesDetalle&id_Gpo_Valores=<?php echo $elemento->id_Gpo_Valores_Reporte ?>">
                                                            <i class="fa fa-search"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <br>

                                        <table class="table table-sm">
                                            <thead>
                                            <tr class="table-supervisor-lightgreen">
                                                <th scope="col">No.</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">% Avance</th>
                                                <th scope="col">Valor sobre Proyecto</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <? $sumaPorcentaje = 0;
                                            $sumaProyecto = 0;
                                            if (is_array($gruposEstructuras) || is_object($gruposEstructuras)) {
                                                foreach ($gruposEstructuras as $row => $result) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row + 1; ?> </td>
                                                        <td><?php echo $result->nombreReporte; ?> </td>
                                                        <td><?php echo $result->porcentaje; ?>%</td>
                                                        <td><?php echo $result->porcentajeProyecto; ?>%</td>
                                                    </tr>
                                                    <?php

                                                    $sumaPorcentaje += $result->porcentaje;
                                                    $sumaProyecto += $result->porcentajeProyecto;
                                                }
                                            } ?>
                                            </tbody>
                                        </table>

                                        <p class="text-center">
                                            Total Avance: <?= $sumaPorcentaje; ?>% <br>
                                            Total Proyecto: <?= $sumaProyecto; ?>%
                                        </p>

                                    <?php } ?>
                                    <br>

                                    <!-----------<?php var_dump($desglose) ?> -->

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-5">
                                <div id="pastel_graph" class="chartdiv shadow-sm bg-light"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart code -->
    <script src="js/graficas.js"></script>
    <script>
        pastel(<?php echo $datosFinales ?>); // end am4core.ready()
    </script>
<?php }

if ($action == "avancesDetalle") { ?>
    <!-- ++++++++++++++++++++++++++++++++++  Codigo de la Vista ++++++++++++++++++++++++++++++++++++++++++ -->
    <div class="w-100 p-3 d-flex justify-content-center animated fadeIn slow">
        <div class="col">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="m-0 p-2 bg-gradient-secondary text-white br-top"><?php echo $datosGenerales[0]->titulo_Reporte; ?></h4>

                    <div class="col-12 p-3 d-md-flex justify-content-md-around">
                        <div class="col-sm-12 col-md-5 my-md-0 my-3 shadow-sm">
                            <div class="w-100 scroll-down-arrow">
                                <p class="w-100 h5 text-center text-uppercase font-weight-bold p-1">Elementos</p>
                                <table class="table table-sm">
                                    <thead>
                                    <tr class="table-supervisor-lightgreen">
                                        <th scope="col">No.</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">% Avance</th>
                                        <th scope="col"> Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sumaPorcentajeDetalle = 0;
                                    foreach ($desglose as $key => $elemento) { ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $elemento->titulo_Reporte ?></td>
                                            <td><?php echo $elemento->Porcentaje1 ?>%</td>
                                            <td>
                                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $elemento->id_Gpo_Valores_Reporte; ?>&Id_Reporte=<?php echo $elemento->id_Reporte_Padre; ?>">
                                                    <i class="fa fa-search"></i> </a></td>
                                        </tr>
                                        <?php
                                        $sumaPorcentajeDetalle += $elemento->Porcentaje1;
                                    } ?>
                                    </tbody>
                                </table>

                                <p class="text-center p-4">
                                    Total avance: <?php echo $sumaPorcentajeDetalle; ?> %
                                </p>


                                <!-----------<?php var_dump($desglose) ?> -->
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div id="pastel_graph" class="shadow-sm chartdiv"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart code -->
    <script src="js/graficas.js"></script>
    <script>
        am4core.ready(pastel(<?php echo $datosFinales ?>)); // end am4core.ready()
    </script>
<?php }

if ($action == "mapa") { ?>
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }

        .filter-group {
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            font-weight: 600;
            position: absolute;
            top: 10px;
            right: 50px;
            z-index: 1;
            border-radius: 3px;
            width: 120px;
            color: #fff;
        }

        .filter-group input[type=checkbox]:first-child + label {
            border-radius: 3px 3px 0 0;
        }

        .filter-group label:last-child {
            border-radius: 0 0 3px 3px;
            border: none;
        }

        .filter-group input[type=checkbox] {
            display: none;
        }

        .filter-group input[type=checkbox] + label {
            background-color: #3386c0;
            display: block;
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        }

        .filter-group input[type=checkbox] + label {
            background-color: #3386c0;
            text-transform: capitalize;
        }

        .filter-group input[type=checkbox] + label:hover,
        .filter-group input[type=checkbox]:checked + label {
            background-color: #4ea0da;
        }

        .filter-group input[type=checkbox]:checked + label:before {
            content: '✔';
            margin-right: 5px;
        }
    </style>


    <div id="cuerpo" style="margin-top: 4em;position:absolute; top:0; bottom:0; width:100%;">
        <div id='map'></div>
        <nav id='filter-group' class='filter-group'></nav>
    </div>


    <script>
        var book = <?php echo json_encode($book, JSON_PRETTY_PRINT) ?>;

        mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlamFuZHJvdDk1IiwiYSI6ImNrMmZibTJkdjAzb28zY2xpZWhoemF0YmcifQ.J8-Ga38xt8ZWEOwxhSOYqw';
        var places = {
            "type": "FeatureCollection",
            "features": book
        };
        var filterGroup = document.getElementById('filter-group');
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-99.520828, 27.462464],
            // 27.462464, -99.520828
            zoom: 8
        });

        let server = `https://${document.domain}/supervisor/amr`;

        map.on('load', function () {
            map.loadImage(server + '/img/iconos_ubicaciones/its.png', function (error1, image) {
                if (error1) throw error1;
                map.addImage('its', image);
            });
            map.loadImage(server + '/img/iconos_ubicaciones/restaurante.png', function (error, image2) {
                if (error) throw error;
                map.addImage('restaurante', image2);
            });

            map.loadImage(server + '/img/iconos_ubicaciones/carril.png', function (error, image3) {
                if (error) throw error;
                map.addImage('carril', image3);
            });

            map.loadImage(server + '/img/iconos_ubicaciones/wc.png', function (error, image4) {
                if (error) throw error;
                map.addImage('wc', image4);
            });

            map.loadImage(server + '/img/iconos_ubicaciones/pc.png', function (error, image5) {
                if (error) throw error;
                map.addImage('pc', image5);
            });

            map.loadImage(server + '/img/iconos_ubicaciones/area.png', function (error, image6) {
                if (error) throw error;
                map.addImage('area', image6);
            });

            map.loadImage(server + '/img/iconos_ubicaciones/atendido.png', function (error, image7) {
                if (error) throw error;
                map.addImage('ubicacion', image7);
            });


            places.features.forEach(function (feature) {

                var symbol = feature.properties['icon'];
                var layerID = 'poi-' + symbol;
                if (!map.getLayer(layerID)) {


                    switch (symbol) {
                        case("pc"):
                            iconomapa = "pc";
                            icon = "P.C.";
                            break;
                        case("carril"):
                            iconomapa = "carril";
                            icon = "Carril";
                            break;
                        case("its"):
                            iconomapa = "its";
                            icon = "ITS´s";
                            break;
                        case("wc"):
                            iconomapa = "wc";
                            icon = "Baños";
                            break;
                        case("restaurant"):
                            iconomapa = "restaurante";
                            icon = "Restaurante";
                            break;
                        case("area"):
                            iconomapa = "area";
                            icon = "Área en P.C.";
                            break;
                        case("ubicacion"):
                            iconomapa = "ubicacion";
                            icon = "Ubicación";
                            break;
                    }

                    map.addLayer({
                        "id": layerID,
                        "type": "symbol",
                        "source": {
                            "type": "geojson",
                            "data": places
                        },
                        "layout": {
                            "icon-image": iconomapa,
                            "icon-size": 0.7,
                            "icon-allow-overlap": true
                        },
                        "filter": ["==", "icon", symbol]
                    });

                    var input = document.createElement('input');
                    input.type = 'checkbox';
                    input.id = layerID;
                    input.checked = true;
                    filterGroup.appendChild(input);
                    var label = document.createElement('label');
                    label.setAttribute('for', layerID);
                    label.textContent = icon;
                    filterGroup.appendChild(label);
// When the checkbox changes, update the visibility of the layer.
                    input.addEventListener('change', function (e) {
                        map.setLayoutProperty(layerID, 'visibility',
                            e.target.checked ? 'visible' : 'none');
                    });
                }


                var popup = new mapboxgl.Popup({
                    closeButton: false,
                    closeOnClick: false
                });

                map.on('mouseenter', layerID, function (e) {
                    //map.getCanvas().style.cursor = 'pointer';
                    var coordinates = e.features[0].geometry.coordinates.slice();
                    var description = e.features[0].properties.description;

                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

                    popup.setLngLat(coordinates).setHTML(description).addTo(map);
                });

                map.on('click', layerID, function () {
                    map.getCanvas().style.cursor = '';
                    popup.remove();
                });


            });
        });
        map.addControl(new mapboxgl.NavigationControl());
    </script>

<?php }

if ($action == "mapaReportes") { ?>
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }

        .filter-group {
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            font-weight: 600;
            position: absolute;
            top: 10px;
            right: 50px;
            z-index: 1;
            border-radius: 3px;
            width: 120px;
            color: #fff;
        }

        .filter-group input[type=checkbox]:first-child + label {
            border-radius: 3px 3px 0 0;
        }

        .filter-group label:last-child {
            border-radius: 0 0 3px 3px;
            border: none;
        }

        .filter-group input[type=checkbox] {
            display: none;
        }

        .filter-group input[type=checkbox] + label {
            background-color: #3386c0;
            display: block;
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        }

        .filter-group input[type=checkbox] + label {
            background-color: #3386c0;
            text-transform: capitalize;
        }

        .filter-group input[type=checkbox] + label:hover,
        .filter-group input[type=checkbox]:checked + label {
            background-color: #4ea0da;
        }

        .filter-group input[type=checkbox]:checked + label:before {
            content: '✔';
            margin-right: 5px;
        }

        .enlace {

            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            font-weight: 600;
            top: 10px;
            right: 10px;
            z-index: 1;
            border-radius: 3px;
            width: 120px;
            color: #fff !important;
            margin-bottom: 5px;

            background-color: #4ea0da;
            display: block;
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        }
    </style>


    <div id="cuerpo" style="margin-top: 4em;position:absolute; top:0; bottom:0; width:100%;">
        <div id='map'></div>

        <nav id='filter-group' class='filter-group'>
            <a class="enlace" href="#" data-toggle="modal" data-target="#myModalMapaReportes"
               onclick="obtenerUsuariosLLenadosReportes()"> Buscar </a>
        </nav>

    </div>


    <script>

        function msj() {
            $(document).ready(function () {
                alertify.confirm('Mapa de reportes', '' + 'No hay registros de reportes, desea continuar', function () {
                        //document.location.href = 'index.php?controller=Graficas&action=mapaReportes';
                        alertify.error('No hay registros de reportes');
                    },
                    function () {
                        console.log('No continuar');
                        window.history.back();
                    }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
            });
        }

        var book = <?php echo json_encode($book, JSON_PRETTY_PRINT) ?>;

        let latitud;
        let longitud;

        let coordenadas;
        let cantidad2;

        if (book == null) {
            //alert('No hay registros');
            msj();

            latitud = -100.3779596;
            longitud = 20.5818743;
        } else {
            let cantidadDatos = book.length;
            console.log(cantidadDatos);

            if (cantidadDatos == 1) {
                coordenadas = book[0]['geometry']['coordinates'];

                latitud = coordenadas[0];
                longitud = coordenadas[1];

            } else {
                cantidad2 = Math.round(cantidadDatos / 2);
                coordenadas = book[cantidad2]['geometry']['coordinates'];

                latitud = coordenadas[0];
                longitud = coordenadas[1];
            }
        }

        mapboxgl.accessToken = 'pk.eyJ1IjoiYWxlamFuZHJvdDk1IiwiYSI6ImNrMmZibTJkdjAzb28zY2xpZWhoemF0YmcifQ.J8-Ga38xt8ZWEOwxhSOYqw';
        var places = {
            "type": "FeatureCollection",
            "features": book
        };
        var filterGroup = document.getElementById('filter-group');
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [latitud, longitud],
            // 27.462464, -99.520828
            zoom: 8
        });


        let server = `https://${document.domain}/supervisor/amr`;

        map.on('load', function () {
            map.loadImage(server + '/img/iconos_ubicaciones/atendido.png', function (error, image7) {
                if (error) throw error;
                map.addImage('reportes', image7);
            });


            //if (book.length != null) {
            places.features.forEach(function (feature) {

                var symbol = feature.properties['icon'];
                var layerID = 'poi-' + symbol;
                if (!map.getLayer(layerID)) {


                    switch (symbol) {
                        case("pc"):
                            iconomapa = "pc";
                            icon = "P.C.";
                            break;
                        case("carril"):
                            iconomapa = "carril";
                            icon = "Carril";
                            break;
                        case("its"):
                            iconomapa = "its";
                            icon = "ITS´s";
                            break;
                        case("wc"):
                            iconomapa = "wc";
                            icon = "Baños";
                            break;
                        case("restaurant"):
                            iconomapa = "restaurante";
                            icon = "Restaurante";
                            break;
                        case("area"):
                            iconomapa = "area";
                            icon = "Área en P.C.";
                            break;
                        case("reportes"):
                            iconomapa = "reportes";
                            icon = "Reportes";
                            break;
                    }

                    map.addLayer({
                        "id": layerID,
                        "type": "symbol",
                        "source": {
                            "type": "geojson",
                            "data": places
                        },
                        "layout": {
                            "icon-image": iconomapa,
                            "icon-size": 0.7,
                            "icon-allow-overlap": true
                        },
                        "filter": ["==", "icon", symbol]
                    });

                    var input = document.createElement('input');
                    input.type = 'checkbox';
                    input.id = layerID;
                    input.checked = true;
                    filterGroup.appendChild(input);
                    var label = document.createElement('label');
                    label.setAttribute('for', layerID);
                    label.textContent = icon;
                    filterGroup.appendChild(label);
// When the checkbox changes, update the visibility of the layer.
                    input.addEventListener('change', function (e) {
                        map.setLayoutProperty(layerID, 'visibility',
                            e.target.checked ? 'visible' : 'none');
                    });
                }


                var popup = new mapboxgl.Popup({
                    closeButton: false,
                    closeOnClick: false
                });

                map.on('mouseenter', layerID, function (e) {
                    //map.getCanvas().style.cursor = 'pointer';
                    var coordinates = e.features[0].geometry.coordinates.slice();
                    var description = e.features[0].properties.description;

                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

                    popup.setLngLat(coordinates).setHTML(description).addTo(map);
                });

                map.on('click', layerID, function () {
                    map.getCanvas().style.cursor = '';
                    popup.remove();
                });

            });
            //}


        });

        map.addControl(new mapboxgl.NavigationControl());

    </script>

<?php }

if ($action == "diagrama") { ?>
    <style>
        .just-padding {
            padding: 15px;
        }

        .list-group.list-group-root {
            padding: 0;
            overflow: hidden;
        }

        .list-group.list-group-root .list-group {
            margin-bottom: 0;
        }

        .list-group.list-group-root .list-group-item {
            border-radius: 0;
            border-width: 0;
        }

        .list-group.list-group-root > .list-group-item:first-child {
            border-top-width: 0;
        }

        .list-group.list-group-root > .list-group > .list-group-item {
            margin-left: 30px;
        }

        .list-group.list-group-root > .list-group > .list-group > .list-group-item {
            margin-left: 45px;
        }

        .list-group-item .glyphicon {
            margin-right: 5px;
        }
    </style>
    <script>
        $(function () {
            $('.list-group-item').on('click', function () {
                $('.fas', this)
                    .toggleClass('fa-chevron-right')
                    .toggleClass('fa-chevron-down');
            });

        });
    </script>

    <div class="container-fluid flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row pt-3 d-flex justify-content-center">
            <div class="col-11 p-0 shadow">
                <div class="w-100 d-flex justify-content-between bg-gradient-secondary rounded-top">
                    <div class="col-sm-10 d-flex align-items-center">
                        <h4 class="text-white m-0 py-2">
                            Diagrama <?php echo $datosReporte[0]->titulo_Reporte; ?>
                        </h4>
                    </div>
                </div>
                <div class="just-padding">
                    <div class="list-group list-group-root well">
                        <?php foreach ($diagrama as $key => $item) { ?>
                            <div class="d-flex justify-content-between m-1 border border-secondary">
                                <a href="#item-<?php echo $key + 1 ?>" class="list-group-item p-2"
                                   data-toggle="collapse">
                                    <i class="fas fa-chevron-right"></i> <?php echo $item->titulo_Reporte ?>
                                </a>
                                <div class="p-2 d-flex align-items-center">
                                    <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $item->id_Gpo_Valores_Reporte; ?>&Id_Reporte=<?php echo $item->id_Reporte_Padre; ?>">
                                        <span class="badge badge-supervisor-celeste">Detalles</span>
                                    </a>
                                </div>
                            </div>
                            <?php if (count($item->datos) !== 0) { ?>
                                <div class="list-group collapse" id="item-<?php echo $key + 1 ?>">
                                    <?php foreach ($item->datos as $keyDato => $dato) { ?>
                                        <div class="d-flex justify-content-between m-1 ml-4 border border-secondary">
                                            <?php if (isset($dato->datos)) { ?>
                                                <a href="#item-<?php echo $key + 1 ?>-<?php echo $keyDato + 1 ?>"
                                                   class="list-group-item text-primary"
                                                   data-toggle="collapse">
                                                    <?php echo $dato->titulo_Reporte ?>
                                                </a>
                                            <?php } else { ?>
                                                <p class="m-0 p-1 d-flex align-items-center text-primary"><?php echo $dato->titulo_Reporte ?></p>
                                            <?php } ?>
                                            <div class="p-2 d-flex align-items-center">
                                                <a href="index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=<?php echo $dato->id_Gpo_Valores_Reporte; ?>&Id_Reporte=<?php echo $dato->id_Reporte_Padre; ?>">
                                                    <span class="badge badge-danger">Detalles</span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
