class Http {
    constructor() {
        this.urlObtenerConfig = './index.php?controller=LlenadosReporte&action=getConfig';
        this.urlGuardarConfig = './index.php?controller=LlenadosReporte&action=saveConfig';
    }

    getUrlParameter(url, parameter) {
        parameter = parameter.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?|&]' + parameter.toLowerCase() + '=([^&#]*)');
        const results = regex.exec('?' + url.toLowerCase().split('?')[1]);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ''));
    }

    async obtenerConfiguracion(idReporte) {
        const options = {
            method: 'POST',
            body: JSON.stringify({
                id_reporte: idReporte
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        };

        return await fetch(this.urlObtenerConfig, options)
            .then(res => res.json());
    }

    async guardarConfiguracion(idReporte, arrayConfig) {
        const options = {
            method: 'POST',
            body: JSON.stringify({
                id_reporte: idReporte,
                json: JSON.stringify(arrayConfig)
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        };

        return await fetch(this.urlGuardarConfig, options)
            .then(res => res.json());
    }
}

class UI extends Http {
    constructor(idReporte, configuracion) {
        super();
        this.panelResumenContent = document.querySelector("#panel_resumen_content");
        this.reportContent = document.querySelector("#report_content");
        this.panelTitle = document.querySelector("#panel_title");
        this.idReporte = idReporte;
        this.configuracion = configuracion;

        this.makeSortable();
        //this.interact();
    }

    /***************************** Habilita las propiedades de los elmentos para ser ordenables *******************/
    makeSortable() {
        Sortable.create(this.panelResumenContent, this.makeOptions(this.panelResumenContent.dataset.id));
        Sortable.create(this.reportContent, this.makeOptions(this.reportContent.dataset.id));
        Sortable.create(this.panelTitle, this.makeOptions(this.panelTitle.dataset.id));
    }

    interact() {
        interact('.panel-resumen-content p')
            .resizable({
                edges: {
                    top: true,
                    left: true,
                    bottom: true,
                    right: true
                },
            })
            .on('resizemove', event => {
                let {x, y} = event.target.dataset;

                x = parseFloat(x) || 0;
                y = parseFloat(y) || 0;

                Object.assign(event.target.style, {
                    width: `${event.rect.width}px`,
                    height: `${event.rect.height}px`,
                    transform: `translate(${event.deltaRect.left}px, ${event.deltaRect.top}px)`
                });

                Object.assign(event.target.dataset, {x, y})
            })
    }

    /**
     * Get the order of elements. Called once during initialization.
     * @param   {String}  nameGroup
     * @returns {Object}
     */
    makeOptions(nameGroup) {
        const self = this;
        return {
            animation: 150,
            chosenClass: 'selected',
            dragClass: 'drag',
            handle: '.btn-handle',
            group: nameGroup,
            store: {
                /**
                 * @param {Sortable} sortable
                 * @returns {Array}
                 */
                get: function (sortable) {
                    const order = self.configuracion[sortable.options.group.name];
                    console.log(order)
                    return Array.isArray(order) ? order.map(
                        elemento => {
                            return elemento.id;
                        }
                    ) : [];
                },
                /**
                 * Save the order of elements. Called onEnd (when the item is dropped).
                 * @param {Sortable} sortable
                 */
                set: function (sortable) {
                    const order = sortable.toArray();
                    self.armarConfiguracion(sortable.options.group.name, order);
                    self.guardarConfiguracion(self.idReporte, self.configuracion).then(
                        response => {
                            self.showMessage(response.message);
                        }
                    );
                }
            }
        };
    }

    armarConfiguracion(name, order) {
        this.configuracion[name] = [];

        order.forEach(elemento => {
            this.configuracion[name].push({
                id: elemento,
                width: '',
                height: ''
            })
        });
    }

    showMessage(mensaje) {
    }
}

document.addEventListener('DOMContentLoaded', (evento) => {
    const http = new Http();
    const idReporte = http.getUrlParameter(window.location.href, 'Id_Reporte');

    http.obtenerConfiguracion(idReporte).then(configuracion => {
        new UI(idReporte, configuracion);
    });
});
