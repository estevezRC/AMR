import Components from './components/index.js'
import {HttpClient} from '../core/HttpClient.js'

const ROUTE = window.location;
const clearUrl = ROUTE.toString().replace('/index.php?controller=MapasV2&action=index', '')

export const MrMaps = new Vue({
    el: '#root',
    data: {
        zoom: 6,
        center: L.latLng(20.987085584914595, -100.118408203125),
        url: 'https://{s}.tile.osm.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="https://osm.org/copyright">GetItCompany</a>',
        httpsService: HttpClient,
        /*locations: [],*/
        myModel: false,
        open: true,
        isActive: true,
        locationPoints: [],
        maximo: 0,
        ubicationSelected: undefined,
        detailsMarker: [],
        newMarker: [],
        UrlNewMarker: "",
        menu_position: {
            left: 0,
            top: 0
        },
        dataNewMarker: {},
        indexNewMarker: 0,
        show: false,
        titleMarker: '',
        idUbicacion: 0,
        idInventario: 0,
        urlRouteClean: clearUrl,
        imgDetails: [],
        id_carpeta: 0,
        id_proyecto: 0


    },
    methods: {

        async getLocations(ubicatioSelected, ubicationOptions) {
            if (ubicatioSelected === undefined) {
                return
            } else {
                this.locationPoints = [];
                this.newMarker = [];
                this.ubicationSelected = ubicatioSelected
                this.isActive = false;

                const url = `index.php?controller=MapasV2&action=getRegistrosUbicacion`
                const body = `ubicacion` + '=' + `${ubicatioSelected.toString()}`

                const lengthgroup = this.highAndLow(ubicatioSelected.toString())
                for (let i = 0; i < lengthgroup + 1; i++) {
                    this.locationPoints.push([])
                }
                const response = await this.httpsService.post(url, body)

                const {
                    data: data
                } = await response
                this.UrlNewMarker = 'Agregar'

                data[0].features.map(values => {
                    if (values.geometry.type == "Point") {
                        const markers = this.createLocationMarker(values.geometry.coordinates[0], values.geometry.coordinates[1])
                        this.locationPoints[values.properties.id_ubicacion].push({
                            markers: markers,
                            id: values.properties.id,
                            titulo: values.properties.titulo,
                            id_ubicacion: values.properties.id_ubicacion,
                            id_proyecto: values.properties.id_proyecto,
                            tipo: values.properties.tipo,
                            icono: values.properties.configuracion.icono
                        })
                    }


                })
                console.log(this.locationPoints)
                this.isActive = true
            }
            this.markersOffScreen()
        },
        createLocationMarker(latitude, longitude) {
            return L.latLng(latitude, longitude)
        },
        toggle() {
            this.open = !this.open;

        },
        highAndLow(numbers) {
            let arr = numbers.split(",").map(Number);
            let largest = arr[0];
            for (let i = 1; i < arr.length; i++) {
                if (arr[i] > largest) {
                    largest = arr[i];
                }
            }
            return largest;
        },
        async openModel(id_ubicacion, id, titulo) {
            console.log(id_ubicacion, id, titulo)
            const url = `index.php?controller=MapasV2&action=getDetalleRegistroUbicacion`
            const body = {ubicacion: id_ubicacion, id: id}
            const response = await this.httpsService.post2(url, body)

            const {
                data: data
            } = await response
            this.id_carpeta = data.id_carpeta;
            this.id_proyecto = data.id_proyecto;
            this.detailsMarker = data.reportes_llenados;
            this.titleMarker = titulo;
            this.idUbicacion = id_ubicacion;
            this.idReporte = id;
            this.imgDetails = data.info_fotografia;
            this.myModel = true;

        },
        markerClick(event) {
            const latLngs = [event];
            const markerBounds = L.latLngBounds(latLngs);
            this.$refs['map'].fitBounds(markerBounds)

        },
        addMarker(event) {
            this.newMarker.push(event.latlng);
            localStorage.setItem("coordenadas", `${event.latlng.lat},${event.latlng.lng}`);

        },
        removeMarker() {
            const indexM = this.indexNewMarker;
            this.newMarker.splice(indexM, 1);
        },
        Crearnuevo() {
            const ROUTE = window.location;
            const clearUrl = ROUTE.toString().replace('/index.php?controller=MapasV2&action=index', '')
            window.open(`${clearUrl}/index.php?controller=LlenadosReporte&action=index&tipo=2`, '_blank')

        },
        markersOffScreen() {
            if (this.$refs.markerspoints === undefined) {
                return
            } else {
                if (this.zoom < 6) {
                    this.$nextTick(() => {
                        for (let i = this.$refs.markerspoints.length - 1; i >= 0; i--) {
                            let m = this.$refs.markerspoints[i];
                            m._props['visible'] = false;
                        }
                    });
                } else {
                    this.$nextTick(() => {
                        const mapBounds = this.$refs['map'].mapObject.getBounds();
                        for (let i = this.$refs.markerspoints.length - 1; i >= 0; i--) {
                            let m = this.$refs.markerspoints[i];
                            let shouldBeVisible = mapBounds.contains(m._props.latLng);
                            if (!shouldBeVisible) {
                                m._props['visible'] = shouldBeVisible;
                            } else if (shouldBeVisible) {
                                m._props['visible'] = shouldBeVisible;
                            }
                        }
                    });
                }
            }
        },
        onleftclick: function () {
            this.show = false;
        },
        onrightclick: function (e, index) {
            this.dataNewMarker = e;
            this.indexNewMarker = index;
            this.menu_position = {
                transform: `translate3d(${(e.originalEvent.clientX) - 39}px,${(e.originalEvent.clientY) - 42}px,0px)`,
            };
            this.show = true;
        },
        zoomUpdated(zoom) {
            this.zoom = zoom;
        }, sendToUpdateOrSee(action) {

            if (action == false) {
                const url = `index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_ReportePadre=${this.idReporte}&Id_Reporte=${this.idUbicacion}&tipo_Reporte=2&return=1`
                window.open(`${clearUrl}/${url}`, '_blank')
            } else if (action == true) {
                const url = `index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=${this.idReporte}&Id_Reporte=${this.idUbicacion}`
                window.open(`${clearUrl}/${url}`, '_blank')
            }

        }, getImgUrl(UrlRote, date) {
            const DateDolder = moment(date).format("YYYY-MM").toString();
            const stringDate = DateDolder.replace('-', '')
            return `${clearUrl}/img/reportes/${this.id_carpeta}/${this.id_proyecto}/${stringDate}/${UrlRote}`
        }


    },
    ...Components
})