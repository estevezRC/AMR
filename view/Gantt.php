<?php if ($action == "index") { ?>
    <script
        src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
    <script
        src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
    <script
        src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
    <script
        src="https://cdn.anychart.com/releases/v8/js/anychart-gantt.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
    <script
        src="https://cdn.anychart.com/releases/v8/js/anychart-data-adapter.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
    <script src="https://cdn.anychart.com/releases/v8/locales/es-mx.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/themes/wines.min.js"></script>
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"
          type="text/css" rel="stylesheet">
    <link
        href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"
        type="text/css" rel="stylesheet">
    <div class="w-100 h-100 pt-3 d-flex justify-content-center animated fadeIn slow">
        <div class="col h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-sm-11 h-100">
                    <h4 class="m-0 p-2 bg-gradient-secondary text-white br-top"><?php echo $mensaje; ?></h4>
                    <?php $test = json_decode($datosGantt);
                    if ($test) { ?>
                        <div id="container" class="col p-3 h-100 shadow"></div>
                        <br>
                    <?php } else { ?>
                        <div class="col p-3 shadow">
                            <div class="alert alert-danger m-0" role="alert">
                                Parece que no hay algún diagrama para el proyecto actual!
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        html, body {
            height: 100%;
        }
    </style>
    <script>
        anychart.onDocumentReady(function () {

            anychart.format.outputLocale("es-mx");
            anychart.format.outputDateTimeFormat("dd/MM/yyyy H:mm:ss");

            // set chart theme
            anychart.theme('wines');
            // The data used in this sample can be obtained from the CDN
            // https://cdn.anychart.com/samples/gantt-charts/activity-oriented-chart/data.js
            // create data tree
            var treeData = anychart.data.tree(<?php echo $datosGantt ?>, 'as-table');

            // create project gantt chart
            var chart = anychart.ganttProject();

            // set data for the chart
            chart.data(treeData);

            // set start splitter position settings
            chart.splitterPosition(370);

            // get chart data grid link to set column settings
            var dataGrid = chart.dataGrid();
            // set first column settings
            dataGrid.column(0)
                .title('No.')
                .width(30)
                .labels({hAlign: 'center'});

            // set second column settings
            dataGrid.column(1)
                .title('Actividad')
                .labels()
                .hAlign('left')
                .width(180);

            // set third column settings
            dataGrid.column(2)
                .title('Inicio')
                .width(70)
                .labels()
                .hAlign('right')
                .format(function () {
                    var date = new Date((this.actualStart));
                    //console.log(this.actualStart * 1000);
                    var month = date.getUTCMonth() + 1;
                    var strMonth = (month > 9) ? month : '0' + month;
                    var utcDate = date.getUTCDate();
                    var strDate = (utcDate > 9) ? utcDate : '0' + utcDate;
                    return strDate + '-' + strMonth + '-' + date.getUTCFullYear();
                });

            // set fourth column settings
            dataGrid.column(3)
                .title('Fin')
                .width(80)
                .labels()
                .hAlign('right')
                .format(function () {
                    var date = new Date(this.actualEnd);
                    var month = date.getUTCMonth() + 1;
                    var strMonth = (month > 9) ? month : '0' + month;
                    var utcDate = date.getUTCDate();
                    var strDate = (utcDate > 9) ? utcDate : '0' + utcDate;
                    return strDate + '-' + strMonth + '-' + date.getUTCFullYear();
                });

            dataGrid.column(4)
                .title('Porcentaje')
                .width(120)
                .labels()
                .hAlign('right')
                .format(function () {
                    //console.log(this);
                    return this.item.na.porcentajeAsignado;
                });

            dataGrid.column(5)
                .title('Reporte')
                .width(80)
                .labels()
                .hAlign('center')
                .useHtml(true)
                .format(function () {
                    return this.id;
                    return `<a class="btn btn-sm btn-secondary" href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo">Llenar</a>`;
                });

            // set container id for the chart
            chart.container('container');

            // initiate chart drawing
            chart.draw();

            // zoom chart to specified date
            chart.zoomTo(951350400000, 954201600000);
        })
        ;
    </script>

<?php }

if ($action == "nuevo") { ?>
    <div class="container-fluid py-4 flex-column justify-content-center p-3 animated fadeIn slow">
        <div class="row mt-3">
            <div class="col-11 shadow mx-auto">
                <div class="row bg-gradient-secondary rounded-top">
                    <div class="col-10">
                        <p class="h4 text-white m-0 lead py-2"><?= $mensaje ?></p>
                    </div>
                    <div class="col-2">

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 p-2" id="container_info">
                        <form id="frm_nuevo_xml" action="#">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inp_file"
                                           name="file"><label
                                        class="custom-file-label">Seleccionar XML </label>
                                </div>

                            </div>
                            <div class="form-row" id="container_btn">
                                <div class="form-group col">
                                    <button id="btn_submit" type="button" class="btn btn-danger">
                                        <i class="far fa-share-square"></i> Enviar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        class UI {
            elements = {
                'containerInfo': document.querySelector("#container_info"),
                'containerPaneles': "#container-paneles",
                'tablas': ".tbl-plantillas-registros",
                'formNuevoXML': document.querySelector("#frm_nuevo_xml"),
                'inpFile': document.querySelector("#inp_file"),
                'containerBtn': document.querySelector("#container_btn"),
                'btnSubmit': document.querySelector("#btn_submit"),
                'contBtnEstructura': "#container_btn_estructura"
            };

            updateInput(input) {
                if (input.files && input.files[0]) {
                    input.parentElement.querySelector('label').textContent = input.files[0].name;
                }
            }

            cambiarBoton(accion) {
                let markup = "";

                if (accion === "subiendo") {
                    markup = `
                    <div class="form-group col">
                         <button class="btn btn-danger" type="button" disabled>
                              <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                              Subiendo XML...
                        </button>
                    </div>
                    `;
                }

                if (accion === "enviar") {
                    markup = `
                     <div class="form-group col">
                        <button id="btn_submit" type="button" class="btn btn-danger">
                            <i class="far fa-share-square"></i> Enviar
                        </button>
                     </div>
                    `;
                }

                if (accion === "validando") {
                    markup = `
                    <div class="form-group col">
                         <button class="btn btn-danger" type="button" disabled>
                              <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                              Validando XML...
                        </button>
                    </div>
                    `;
                }

                this.elements.containerBtn.innerHTML = markup;
            }

            mostrarMensaje(mensaje) {
                const markup = `
                    <div class="form-group col">
                        <div class="p-1 alert alert-warning m-0" role="alert">
                          ${mensaje}
                        </div>
                    </div>
                `;

                this.elements.containerBtn.insertAdjacentHTML("beforeend", markup);
                this.elements.containerBtn.classList.add('justify-content-between');
            }

            mostrarBtnCrearEstructura() {
                const markup = `
                   <div class="form-group col" id="container_btn_estructura">
                        <button id="btn_crear_estructura" type="button" class="btn btn-primary float-right">
                            <i class="far fa-share-square"></i> Crear Estructura
                        </button>
                     </div>
                `;

                this.elements.containerBtn.insertAdjacentHTML("beforeend", markup);
                this.elements.containerBtn.classList.add('justify-content-between');
            }

            cambiarBtnEstructura(estado) {
                let markup;
                if (estado === "creando") {
                    markup = `
                       <button class="btn btn-primary float-right" type="button" disabled>
                          <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                          Creando Estructura...
                        </button>
                    `;
                }

                document.querySelector(this.elements.contBtnEstructura).innerHTML = markup;
            }

            eliminarContainerEstructura() {
                this.elements.containerBtn.removeChild(document.querySelector(this.elements.contBtnEstructura));
            }

            crearPaneles(informacion) {
                try {
                    this.elements.containerInfo.removeChild(document.querySelector(this.elements.containerPaneles));
                } catch (e) {
                }

                const plantillas = (plantillas) => {
                    let markup = plantillas.map((cur, index) => {
                        return `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${cur.actividad}</td>
                            <td>${cur.descripcion}</td>
                            <td>${cur.tipo_reporte}</td>
                        </tr>
                        `;
                    });
                    return markup.join("");
                };

                const registros = (registros) => {
                    let markup = registros.map((cur, index) => {
                        return `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${cur.id_reporte}</td>
                            <td>${cur.actividad}</td>
                            <td>${cur.gpo_padre}</td>
                            <td>${cur.tipo_reporte}</td>
                        </tr>
                        `;
                    });
                    return markup.join("");
                };

                let markup;

                markup = `
                <div id="container-paneles">
                    <hr>
                    <p class="h5 py-2 text-leading text-center bg-light">Vista previa de los elementos que serán creados</p>
                    <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-plantillas" role="tab" aria-controls="nav-home" aria-selected="true">Plantillas</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-registros" role="tab" aria-controls="nav-profile" aria-selected="false">Registros</a>
                      </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-plantillas" role="tabpanel" aria-labelledby="nav-plantillas-tab">
                          <div class="table-responsive-md py-2" id="cont_plantillas">
                            <table class="table table-striped thead-dark tbl-plantillas-registros">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th class="align-middle">No.</th>
                                    <th class="align-middle">Actividad</th>
                                    <th class="align-middle">Descripción</th>
                                    <th class="align-middle">Tipo de Reporte</th>
                                </tr>
                                </thead>
                                <tbody id="plantillas-body">
                                    ${plantillas(informacion.plantillas)}
                                </tbody>
                            </table>
                          </div>
                      </div>
                      <div class="tab-pane fade" id="nav-registros" role="tabpanel" aria-labelledby="nav-registros-tab">
                        <div class="table-responsive-md py-2" id="cont_registros">
                            <table class="table table-striped thead-dark tbl-plantillas-registros">
                                <thead class="bg-primary text-light">
                                <tr>
                                    <th class="align-middle">No.</th>
                                    <th class="align-middle">ID de Reporte</th>
                                    <th class="align-middle">Actividad</th>
                                    <th class="align-middle">Grupo Padre</th>
                                    <th class="align-middle">Tipo de Reporte</th>
                                </tr>
                                </thead>
                                <tbody id="registros-body">
                                    ${registros(informacion.registros)}
                                </tbody>
                            </table>
                          </div>
                      </div>
                    </div>
                </div>
                `;

                this.elements.containerInfo.insertAdjacentHTML("beforeend", markup);
            }

            mostrarDatos(informacion) {
                this.crearPaneles(informacion);

                $(this.elements.tablas).DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                    },
                    "ordering": false,
                    "pageLength": 50
                });
            }

            showConfirmDialog(global) {
                alertify.confirm('Crear Estructura', '' + '¿Desea continuar con la creación de la estructura?', () => {
                        global.iniciarCreacionEstructura();
                    },
                    function () {
                        global.eliminarArchivoXML();
                    }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
            }
        }

        class NuevoXML extends UI {
            async enviarXML() {
                const formNuevoXML = new FormData(this.elements.formNuevoXML);

                this.response = await $.ajax({
                    url: './index.php?controller=Gantt&action=subirArchivo',
                    type: 'POST',
                    contentType: false,
                    data: formNuevoXML, // mandamos el objeto formdata que se igualo a la variable data
                    processData: false,
                    cache: false
                });
            }

            async obtenerPrevisualizacion(archivo) {
                const data = new FormData();
                data.append('nombre_archivo', archivo);

                this.validacion = await $.ajax({
                    url: './index.php?controller=Gantt&action=previsualizar',
                    type: 'POST',
                    contentType: false,
                    data: data, // mandamos el objeto formdata que se igualo a la variable data
                    processData: false,
                    cache: false
                });

                this.validacion = JSON.parse(this.validacion);
            }

            async procesarXML(evento) {
                evento.preventDefault();
                this.cambiarBoton("subiendo");

                await this.enviarXML();

                this.response = JSON.parse(this.response);
                if (this.response.carga) {

                    this.cambiarBoton("validando");
                    await this.obtenerPrevisualizacion(this.response.nombre_archivo);

                    this.cambiarBoton("enviar");

                    this.mostrarDatos(this.validacion);

                    this.mostrarBtnCrearEstructura();

                } else {
                    this.cambiarBoton("enviar");
                    this.mostrarMensaje(this.response.mensaje);
                }
            }

            crearEstructura() {
                this.showConfirmDialog(this);
            }

            async iniciarCreacionEstructura() {
                // Cambiar botón de crear Estructura
                this.cambiarBtnEstructura("creando")

                const data = new FormData();
                data.append("nombre_archivo", this.response.nombre_archivo);

                this.creacionEstructura = await $.ajax({
                    url: './index.php?controller=Gantt&action=crear',
                    type: 'POST',
                    contentType: false,
                    data: data, // mandamos el objeto formdata que se igualo a la variable data
                    processData: false,
                    cache: false
                });

                try {
                    this.creacionEstructura = JSON.parse(this.creacionEstructura);
                } catch (e) {
                    this.creacionEstructura = {status: "error", code: 400};
                }

                if (this.creacionEstructura.status === "success") {
                    alertify.success("Creación exitosa");
                } else {
                    alertify.error("La estructura no pudo ser creada revise el archivo XML e inténtalo de nuevo");
                }

                this.eliminarContainerEstructura();
                this.mostrarBtnCrearEstructura();

            }

            async eliminarArchivoXML() {
                const data = new FormData();
                data.append("nombre_archivo", this.response.nombre_archivo);

                this.eliminacionArchivo = await $.ajax({
                    url: './index.php?controller=Gantt&action=borrarArchivo',
                    type: 'POST',
                    contentType: false,
                    data: data, // mandamos el objeto formdata que se igualo a la variable data
                    processData: false,
                    cache: false
                });

                this.eliminarArchivo = JSON.parse(this.eliminacionArchivo);
            }

            setEventListeners() {
                this.elements.inpFile.addEventListener("change", (evento) => this.updateInput(evento.target, this));

                document.body.addEventListener('click', evento => {
                    if (evento.target.id === 'btn_submit') {
                        this.procesarXML(evento, this);
                    }

                    if (evento.target.id === 'btn_crear_estructura') {
                        this.crearEstructura(evento, this);
                    }
                });
            }

            start() {
                this.setEventListeners();
            }
        }

        const nuevoXML = new NuevoXML();
        nuevoXML.start();
    </script>
<?php }
