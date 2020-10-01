class UIController {
    constructor() {
        this.DOMelements = {
            spinner: '#spinnerBorrar',
            container: '#contenedorElementos',
            campoSelect: '#Id_Reporte',
            inputIdReporte: '#id_Reporte_NEW',
            btnRegresar: '#btnRegresarVinvularGralEspecifico',
            btnNoVincular: '#btnNoVincular',
            btnLlenar: '#btnLlenar',
            btnCambiarVinculo: '#btnCambiarVinculo',
            parReportePadre: '#parReportePadre',
            inputGpoPadre: '#inputGpoPadre'
        };
    }

    displayContenedor() {
        document.querySelector(this.DOMelements.container).parentElement.style.display = 'block';
    }

    hideContenedor() {
        document.querySelector(this.DOMelements.container).parentElement.style.display = 'none';
    }

    displayLoading() {
        document.querySelector(this.DOMelements.spinner).style.display = 'block';
    }

    hideLoading() {
        document.querySelector(this.DOMelements.spinner).style.display = 'none';
    }

    enableBtnReturn() {
        document.querySelector(this.DOMelements.btnRegresar).disabled = false;
    }

    disableBtnReturn() {
        document.querySelector(this.DOMelements.btnRegresar).disabled = true;
    }

    enableBtnNoVincular() {
        document.querySelector(this.DOMelements.btnNoVincular).style.display = "inline-block";
    }

    disableBtnNoVincular() {
        document.querySelector(this.DOMelements.btnNoVincular).style.display = "none";
    }

    actualizarDatosVinculo(actividad, gpoValores) {
        document.querySelector(this.DOMelements.parReportePadre).textContent = actividad;
        document.querySelector(this.DOMelements.inputGpoPadre).value = gpoValores;
        $('#myModalGantt').modal('hide');
    }

    pintarElementos(elementos, estaDepurada, vista) {
        let contenido = ``, elementoDerecho = '';
        if (typeof elementos == 'object') elementos = Object.entries(elementos);

        elementos.forEach(([index, elemento]) => {
            let liElement, hipervinculo;
            const enlace = `index.php?controller=LlenadosReporte&action=mostrarreportenuevo&idReportePadreVincular=${elemento.info.id_reporte}&idGpoValoresPadreVincular=${elemento.info.gpo_valores}&nombre_ReportePadreVincular=${elemento.info.actividad}&titulo_ReportePadreVincular=${elemento.info.actividad}&Id_Reporte=${this.getIdReporte()}`;

            if (elemento.children) {
                liElement = `<a href="#" data-id="${index}" class="btn-link"> ${elemento.info.actividad}</a>`;
            } else if (estaDepurada) {
                if (vista !== "modificarreporte") {
                    liElement = `<a href="#" data-id="${index}" class="btn-link"> ${elemento.info.actividad}</a>`;
                } else {
                    const datosPadre = llenado.getValoresPadre();
                    liElement = `<a href="#" onclick="llenado.actualizarDatosVinculo('${datosPadre.actividad}', '${datosPadre.gpo_valores}')" class="btn-link"> ${elemento.info.actividad}</a>`;
                }
            } else {
                liElement = `${elemento.info.actividad}`;
            }

            if (vista !== "modificarreporte") {
                hipervinculo = `
                <a href="${enlace}" class="btn btn-secondary btn-sm" data-trigger="hover"
                   data-content="Vincular con este registro" data-toggle="popover">
                    <i class="fas fa-link"></i>
                </a>
                `;
            } else {
                hipervinculo = `
                <a href="#" class="btn btn-secondary btn-sm" onclick="llenado.actualizarDatosVinculo('${elemento.info.actividad}', '${elemento.info.gpo_valores}')" data-trigger="hover"
                   data-content="Vincular con este registro" data-toggle="popover">
                    <i class="fas fa-link"></i>
                </a>
                `;
            }

            if (!estaDepurada && elemento.info.gpo_valores) {
                elementoDerecho = `
                    <div class="d-flex align-items-center ml-sm-2">
                        ${hipervinculo}
                    </div>`;
            } else if (elemento.children || (!elemento.children && !estaDepurada)) {
                elementoDerecho = '<small class="text-muted"> No es posible vincular </small>';
            }

            contenido += `
                <li class="list-group-item d-flex justify-content-between">
                    ${liElement}

                    ${elementoDerecho}
                </li>
            `;
        });

        document.querySelector(this.DOMelements.container).innerHTML = contenido;
    }

    getIdReporte() {
        let valor;
        if (document.querySelector(this.DOMelements.campoSelect)) {
            valor = document.querySelector(this.DOMelements.campoSelect).value;
        } else {
            valor = document.querySelector(this.DOMelements.inputIdReporte).value;
        }

        return parseInt(valor);
    }

    llenarNuevoReporte(info, idReporte) {
        document.location.href = `index.php?controller=LlenadosReporte&action=mostrarreportenuevo&idReportePadreVincular=${info.id_reporte}&idGpoValoresPadreVincular=${info.gpo_valores}&nombre_ReportePadreVincular=${info.actividad}&titulo_ReportePadreVincular=${info.actividad}&Id_Reporte=${idReporte}`;
    }

    redireccionarLlenarReporte(idReporte) {
        document.location.href = `index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=${idReporte}`;
    }
}

class GralEspecificoController extends UIController {
    constructor() {
        super();
        this.idReporte = 0;
        this.posiciones = [];
    }

    async getEstructuraDepuradaBD(idReporte) {
        // 1. Hacer la peticion HTTP al servidor
        let respuesta = await $.ajax({
            data: {
                idReporte: idReporte,
            },
            url: "./index.php?controller=LlenadosReporte&action=getAllReportesGeneralEspecifico",
            type: 'POST'
        });

        // 2. Convertir respuesta a objeto
        respuesta = JSON.parse(respuesta);
        this.estructuraDepurada = respuesta.estructura;
        this.estaDepurada = respuesta.estaDepurada;
    }

    getSubEstructura() {
        let posicion = '';

        this.posiciones.forEach(elemento => {
            posicion += `[${elemento}]['children']`;
        });

        return eval(`this.estructuraDepurada${posicion}`);
    }

    getPosiciones() {
        return this.posiciones;
    }

    addPosition(position) {
        this.posiciones.push(position);
    }

    deletePosition() {
        this.posiciones.pop();
    }

    setEstructura(estructura) {
        this.estructuraDepurada = estructura;
    };

    resetPosiciones() {
        this.posiciones = [];
    }

    getValoresPadre() {
        let posicionTem = '';
        this.posiciones.forEach((posicion, index) => {
            if (index !== this.posiciones.length - 1) {
                posicionTem += `[${posicion}]['children']`;
            } else {
                posicionTem += `[${posicion}]['info']`
            }
        });

        return eval(`this.estructuraDepurada${posicionTem}`);
    }

    obtenerVistaActual() {
        const action = "action".replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        let regex = new RegExp("[\\?&]" + action + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
}

class LlenadosReportesController extends GralEspecificoController {
    constructor() {
        super();
    }

    setupEventListeners() {
        const DOM = this.DOMelements;
        document.querySelector(DOM.container).addEventListener('click', (evento) => this.actualizarElementos(evento, this));
        document.querySelector(DOM.btnRegresar).addEventListener('click', (evento) => this.actualizarContenido(evento, this));
        if (document.querySelector(DOM.btnLlenar)) {
            document.querySelector(DOM.btnLlenar).addEventListener('click', (evento) => this.obtenerInformacion(evento, this));
        }

        if (document.querySelector(DOM.btnNoVincular)) {
            document.querySelector(DOM.btnNoVincular).addEventListener('click', () => {
                const idReporte = this.getIdReporte();
                this.redireccionarLlenarReporte(idReporte);
            });
        }

        if (document.querySelector(DOM.btnCambiarVinculo)) {
            document.querySelector(DOM.btnCambiarVinculo).addEventListener('click', (evento) => this.obtenerInformacion(evento, this));
        }
    }

    actualizarElementos(evento, self) {
        // 1. Obtener Posicion
        let posicion = evento.target.dataset.id;

        if (typeof posicion !== 'undefined') {
            // 2. Obtener esquema Padre
            const padre = self.getSubEstructura();

            if (padre[posicion]['children']) {
                // 3. Agregar Posicion al arreglo
                self.addPosition(posicion);

                // 4. Obtener Sub Estructura
                const subEstructura = self.getSubEstructura();

                // 5. Actualizar el contenedor
                self.pintarElementos(subEstructura, self.estaDepurada, self.obtenerVistaActual());
            } else {
                // 6. Obtener Info del nodo Padre
                let infoPadre = self.getValoresPadre();

                // 7. Llenar el reporte con los datos para enlazarlo al padre
                self.llenarNuevoReporte(infoPadre, self.idReporte);
            }

            // 8. Activar boton de regreso
            self.enableBtnReturn();
        }
    }

    actualizarContenido(evento, self) {
        // 1. Decrementar posicion
        self.deletePosition();

        // 3. Obtener Sub Estructura
        const subEstructura = self.getSubEstructura();

        // 4. Actualizar el contenedor
        self.pintarElementos(subEstructura, self.estaDepurada, self.obtenerVistaActual());

        // 5. Obtener posiciones actuales(ruta)
        const posiciones = self.getPosiciones();

        // 5. Validar Retroceso
        if (posiciones.length === 0) {
            // 6. Deshabilitar boton de Regreso
            self.disableBtnReturn();
        }
    }

    async obtenerInformacion(evento, self) {
        // 1. Ocultar contenedor
        self.hideContenedor();

        // 2. Mostrar Spinner
        self.displayLoading();

        // 3. Obtener Id de Reporte
        const idReporte = self.getIdReporte();

        if (idReporte !== self.idReporte) {
            // 4. Obtener Estructura
            await self.getEstructuraDepuradaBD(idReporte);

            self.estaDepurada ? this.disableBtnNoVincular() : this.enableBtnNoVincular();

            // 5. Actualizar globalmente idReporte
            self.idReporte = idReporte;

            // 6. Resetear arreglo de posiciones
            self.resetPosiciones();
        }

        // Validar si vienen elementos
        if (self.estructuraDepurada.length !== 0) {
            // 7. Agregar Elementos
            if (self.getPosiciones().length === 0) {
                // Pintar Nodos Raiz
                self.pintarElementos(self.estructuraDepurada, self.estaDepurada, self.obtenerVistaActual());
            } else {
                // Pintar SubEstructura
                const subEstructura = self.getSubEstructura();
                self.pintarElementos(subEstructura, self.estaDepurada, self.obtenerVistaActual());
            }

            // 8. Guardar la Estructura
            self.setEstructura(self.estructuraDepurada);

            // 9. Mostrar Contenedor
            self.displayContenedor();

            // 10. Ocultar Spinner
            self.hideLoading();

        } else {
            // 11. Llenar el reporte seleccionado
            self.redireccionarLlenarReporte(self.idReporte);
        }
    }

    start() {
        // 1. Configurar EventListeners
        this.setupEventListeners();

        // 2. Deshabilitar el boton de regreso
        this.disableBtnReturn();
    }
}

// Instanciar Controlador
let llenado = new LlenadosReportesController();

// Iniciar Aplicación
llenado.start();
