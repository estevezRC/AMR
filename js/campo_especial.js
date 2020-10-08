// Script para el campo especial multipe
class UI {
    elements = {
        containerCampoMultiple: "#multiple",
        campos: "#json_campos",
        headers: "#multiple .card-header",
        cards: "#multiple .card",
        iconoEliminar: ".icon-eliminar",
        iconoAgregar: ".fa.fa-plus",
        iconoEliminarUltimo: ".fa.fa-minus",
        campoMultiple: "#campo_multiple"
    }

    agregarElemento(contador, body, infoCampo) {
        console.log(infoCampo)
        const {nombre_Campo: nombreCampo} = infoCampo;

        const markup = `
            <div class="card">
                <div class="card-header" id="heading_${contador}">
                  <h2 class="mb-0 d-flex justify-content-between align-items-center">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#elemento_${contador}" aria-expanded="false" aria-controls="collapse_${contador}">
                       ${nombreCampo} #${contador}
                    </button>

                    <a href="#" class="lead text-warning d-none icon-eliminar"><span><i class="fa fa-trash"></i></span></a>
                  </h2>
                </div>

                <div id="elemento_${contador}" class="collapse" aria-labelledby="heading${contador}"
                    data-parent="${this.elements.containerCampoMultiple}">
                  <div class="card-body">
                    ${body}
                  </div>
                </div>
              </div>
            `

        $(this.elements.containerCampoMultiple).append(markup)
    }

    eliminarUltimoElemento(contador) {
        $(`#heading_${contador}`).parent().remove()
    }

    eliminarElemento(elemento) {
        $(elemento).closest(this.elements.headers).parent().remove()
    }

    crearHtmlDeCampos(campos) {
        let markup = ""

        for (let campo of campos) {
            let {
                tipo_Reactivo_Campo: tipoReactivoCampo,
                nombre_Campo: nombreCampo,
                descripcion_Campo: descripcionCampo,
                Valor_Default: valorDefault
            } = campo

            descripcionCampo = descripcionCampo.toLowerCase()

            if (tipoReactivoCampo === "text-cadenamiento") {
                markup += `
                    <div class="form-group">
                        <label>${nombreCampo}</label>
                        <div class="input-group">
                            <input type="text" placeholder="Km" minlength="3" maxlength="3"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   class="form-control text-center ${descripcionCampo}-inicio">
                            <div class="input-group-append">
                                 <span class="input-group-text"
                                       id="cadenamiento"><i class="fa fa-plus"></i></span>
                            </div>
                            <input type="text" placeholder="m" minlength="3" maxlength="3"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   class="form-control text-center ${descripcionCampo}-fin">
                        </div>
                    </div>
                    `
            } else if (tipoReactivoCampo === "select") {
                let options = ""
                for (let valor of valorDefault.split("/")) {
                    options += `<option value="${valor}">${valor}</option>`
                }

                markup += `
                    <div class="form-group">
                        <label for="${descripcionCampo}">
                            ${nombreCampo}
                        </label>
                        <select id="${descripcionCampo}" class="custom-select ${descripcionCampo}">
                            ${options}
                        </select>
                    </div>
                    `
            } else if (tipoReactivoCampo === "date") {
                const date = new Date()

                let day = date.getDate(),
                    month = date.getMonth() + 1,
                    year = date.getFullYear()

                month = (month < 10 ? "0" : "") + month;
                day = (day < 10 ? "0" : "") + day;

                markup += `
                <div class="form-group">
                    <label for="${descripcionCampo}">
                        ${nombreCampo}
                    </label>
                    <input type="date" id="${descripcionCampo}"
                           value="${year}-${month}-${day}"
                           class="form-control ${descripcionCampo}">
                </div>
                `
            } else if (tipoReactivoCampo === "select-tabla") {
                let options = ""

                for (let valor of valorDefault) {
                    const {id, nombre} = valor
                    options += `<option value="${id}">${nombre}</option>`
                }

                markup += `
                <div class="form-group">
                    <label for="${descripcionCampo}">
                        ${nombreCampo}
                    </label>
                    <select id="${descripcionCampo}" class="custom-select ${descripcionCampo}">
                        ${options}
                    </select>
                </div>
                `
            } else if (tipoReactivoCampo === "textarea") {
                markup += `
                <div class="form-group">
                    <label for="${descripcionCampo}">${nombreCampo}</label>
                    <textarea id="${descripcionCampo}"
                          style="height: 150px; resize: none;"
                          class="form-control ${descripcionCampo}"></textarea>
                </div>
                `
            } else if (tipoReactivoCampo === "number") {
                markup += `
                <div class="form-group">
                    <label for="${descripcionCampo}">${nombreCampo}</label>
                    <input type="number" min="0" id="${descripcionCampo}" class="form-control ${descripcionCampo}">
                </div>
                `
            } else if (tipoReactivoCampo === "decimal") {
                markup += `
                <div class="form-group">
                    <label for="${descripcionCampo}">${nombreCampo}</label>
                    <input type="number" min="0" step="0.01" id="${descripcionCampo}" class="form-control ${descripcionCampo}">
                </div>
                `
            }
        }

        return markup
    }

    ocultarIconoEliminar(header) {
        $(header).find(this.elements.iconoEliminar).addClass("d-none")
    }

    mostrarIconoEliminar(header) {
        $(header).find(this.elements.iconoEliminar).removeClass("d-none")
    }

    obtenerValoresElemento(card, campo) {
        const {Valor_Default: campos} = campo
        let valoresElemento = [];

        for (let [index, campo] of campos.entries()) {
            let {
                tipo_Reactivo_Campo: tipoReactivoCampo,
                descripcion_Campo: descripcionCampo,
                id_Campo_Reporte: idCampoReporte
            } = campo

            descripcionCampo = descripcionCampo.toLowerCase()

            if (tipoReactivoCampo === "text-cadenamiento") {
                const cadenamientoInicio = $(card).find(`.${descripcionCampo}-inicio`).val()
                const cadenamientoFinal = $(card).find(`.${descripcionCampo}-fin`).val()
                const cadenamiento = `${cadenamientoInicio}.${cadenamientoFinal}`

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: cadenamiento
                })
            } else if (tipoReactivoCampo === "select" || tipoReactivoCampo === "select-tabla") {
                const valorSelect = $(card).find(`.${descripcionCampo}`).val()

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: valorSelect
                })
            } else if (tipoReactivoCampo === "textarea") {
                const valorTextArea = $(card).find(`.${descripcionCampo}`).val()
                valorTextArea.replace('\n', '<br>');

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: valorTextArea
                })
            } else if (tipoReactivoCampo === "date") {
                const valorInputDate = $(card).find(`.${descripcionCampo}`).val()

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: valorInputDate
                })
            } else if (tipoReactivoCampo === 'number') {
                const valorInputNumber = $(card).find(`.${descripcionCampo}`).val()

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: valorInputNumber
                })
            } else if (tipoReactivoCampo === 'decimal') {
                const valorInputDecimal = $(card).find(`.${descripcionCampo}`).val()

                valoresElemento.push({
                    posicion: index,
                    idCampo: idCampoReporte,
                    valorCampo: valorInputDecimal
                })
            }
        }
        return {Valor: valoresElemento}
    }
}

class Multiple extends UI {
    contadorActual
    infoCampo

    administrarClick(event, self) {
        event.preventDefault()

        if ($(event.target).closest(this.elements.iconoAgregar).length > 0) {
            this.contadorActual++

            const {Valor_Default: subCampos} = this.infoCampo
            const body = this.crearHtmlDeCampos(subCampos)

            self.agregarElemento(this.contadorActual, body, this.infoCampo)
            this.setupEventListeners(this.elements.headers)
        }

        if ($(event.target).closest(this.elements.iconoEliminarUltimo).length > 0) {
            if (this.contadorActual >= 2) {
                self.eliminarUltimoElemento(this.contadorActual)
                this.contadorActual--
            }
        }

        if ($(event.target).closest(this.elements.iconoEliminar).length > 0) {
            this.eliminarElemento(event.target)
        }
    }

    administrarHoverHeaders(event, self) {
        const {type, target} = event
        //console.log($(self.elements.headers).is(":hover"))
        if (type === "mouseleave") {
            self.ocultarIconoEliminar(target)
        } else if (type === "mouseenter") {
            self.mostrarIconoEliminar(target)
        }
    }

    armarCampoEspecial() {
        if ($(this.elements.containerCampoMultiple).length > 0) {
            let datosCampo = {
                Valores: []
            }

            $.each($(this.elements.cards), (index, container) => {
                const valoresActividad = this.obtenerValoresElemento(container, this.infoCampo)
                datosCampo.Valores.push(valoresActividad)
            })

            $(this.elements.campoMultiple).val(JSON.stringify(datosCampo))
            console.log(JSON.stringify(datosCampo))
        }
    }

    setupEventListeners(elemento) {
        if (elemento === undefined) {
            if ($(this.elements.containerCampoMultiple).length > 0) { // Validar si existe el campo
                // Obtener datos de los campos
                this.infoCampo = $(this.elements.campos).data('campo')
                const cantidadActividades = $(this.elements.campos).data('cantidad-actividades')
                this.contadorActual = cantidadActividades !== undefined ? cantidadActividades : 1;

                $(this.elements.containerCampoMultiple).on("click", (event) => {
                    this.administrarClick(event, this)
                })

                $(this.elements.headers).on("mouseleave mouseenter", (event) => {
                    this.administrarHoverHeaders(event, this)
                })
            }
        } else {
            $(elemento).unbind("mouseleave mouseenter");
            $(elemento).on("mouseleave mouseenter", (event) => {
                this.administrarHoverHeaders(event, this)
            })
        }
    }

    init() {
        this.setupEventListeners()
    }
}

const multiple = new Multiple();
multiple.init();
