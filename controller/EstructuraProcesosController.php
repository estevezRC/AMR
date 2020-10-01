<?php

class EstructuraProcesosController extends ControladorBase
{

    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    /*--- FUNCION INDEX () ---*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $estructuraProceso = new EstructuraProcesos($this->adapter);
        $allEstructuraProcesos = $estructuraProceso->getAllEstructuraProcesos($this->id_Proyecto_constant);


        // Obtener registros de Ubicaciones e Inventarios Insertados
        $reportes = new Reporte($this->adapter);
        $tipo_Reporte = '2,3';
        $noReportes = '';
        $noRepit = '';
        $allReportesLlenados = $reportes->getAllReportesLlenadosByType($tipo_Reporte, $this->id_Proyecto_constant, $noReportes, $noRepit);

        // OBTENER PROCESOESTRUCTURA PADRE GUARDADOS PARA DUPLICAR
        $allEstructuraProcesosGuardados = $estructuraProceso->getAllEstructuraProcesosLlenadosGpoValores($this->id_Proyecto_constant);

        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = '<i class="fa fa-sitemap" aria-hidden="true"></i> Estructura Procesos';
        }

        $this->view("index", array(
            "mensaje" => $mensaje, "insercion" => $insercion, "newElemento" => $newElemento,
            "allReportesLlenados" => $allReportesLlenados, "allEstructuraProcesos" => $allEstructuraProcesos,
            "allEstructuraProcesosGuardados" => $allEstructuraProcesosGuardados
        ));
    }


    public function getAllReportesByIdReporte()
    {
        $id_Reporte = $_POST['idReportePadre'];

        $id_Gpo_Valores_Padre = $_POST['id_Gpo_Valores_Padre'];

        $reportes = new Reporte($this->adapter);

        // Obtener Reportes Ya insertador del Reporte Padre
        //$allRegistrosInsertados = $reportes->getAllEstructuraProcesosByIdGpoValoresReportePadre($this->id_Proyecto_constant, $id_Reporte);

        //if ($allRegistrosInsertados == '' || empty($allRegistrosInsertados)) {
        //$noReportes = 'AND rl.id_Reporte != ' . $id_Reporte;
        //}
        /*else {
            $idsBloquear = array();
            foreach ($allRegistrosInsertados as $reportesCon) {
                $idsBloquear[] = $reportesCon->id_Reporte_Padre;
            }
            $idsBloquearStr = implode(",", $idsBloquear);
            $noReportes = 'AND rl.id_Reporte NOT IN(' . $id_Reporte . ', ' . $idsBloquearStr . ')';
        }*/


        // Obtener registros de PROCESOESTRUCTURA
        $tipo_Reporte = '2,3';
        $noReportes = 'AND rl.id_Reporte != ' . $id_Reporte;
        $noRepit = 'GROUP BY rl.id_Reporte ORDER BY nombre_Reporte';
        //$allRegistrosProcesos = $reportes->getAllReportesLlenadosByType($tipo_Reporte, $this->id_Proyecto_constant, $noReportes, $noRepit);

        $allRegistrosProcesos = $reportes->getAllCatReportesByNotIdReporte($id_Reporte, $this->id_Proyecto_constant);



        // Calculo de Procentaje
        $allReportesPadre = $reportes->getAllEstructuraProcesosByIdGpoValoresReporte($this->id_Proyecto_constant, $id_Gpo_Valores_Padre);
        $porcentaje = 0;
        foreach ($allReportesPadre as $reportePadre) {
            $porcentaje = $porcentaje + $reportePadre->Porcentaje;
        }

        if ($porcentaje > 100)
            $porcentaje = 100;
        else
            $porcentaje = round($porcentaje, 2, PHP_ROUND_HALF_UP);


        echo json_encode([
            "allRegistrosProcesos" => $allRegistrosProcesos, "porcentaje" => $porcentaje,
            "nombreReporte" => $allReportesPadre[0]->titulo_Reporte
        ]);
    }

    /*--- METODO CREAR NUEVO PROCESOESTRUCTURA---*/
    public function guardarnuevo()
    {
        $estructuraProceso = new EstructuraProcesos($this->adapter);
        $allEstructuraProcesos = $estructuraProceso->getAllEstructuraProcesos($this->id_Proyecto_constant);

        $id_Gpo_Valores_Padre = $_POST['id_Gpo_Valores_Padre'];
        $id_Reporte_Padre = $_POST['id_Reporte_Padre'];
        $inputPorcentaje = $_POST['inputPorcentaje'];
        $cantidad = $_POST['inputCantidad'];

        $estructuraProceso->set_id_Gpo_Valores_Padre($id_Gpo_Valores_Padre);
        $estructuraProceso->set_id_Reporte_Padre($id_Reporte_Padre);
        $estructuraProceso->set_Porcentaje($inputPorcentaje);
        $estructuraProceso->set_Cantidad($cantidad);
        $save = $estructuraProceso->saveNewEstructura($allEstructuraProcesos);

        $procesoById = $estructuraProceso->getAllEstructuraProcesosByIdGpoValoresReportePadreAndIdReportePadre($this->id_Proyecto_constant, $id_Gpo_Valores_Padre, $id_Reporte_Padre);
        $nombre_Gpo_Valores_Reporte_Padre = $procesoById[0]->titulo_Reporte;
        $nombre_Reporte_Padre = $procesoById[0]->nombre_Reporte_Hijo;


        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado la vinculación del reporte: "' . $nombre_Reporte_Padre . '" en el reporte "' . $nombre_Gpo_Valores_Reporte_Padre . '"';
        } else {
            $insercion = 2;
            $mensaje = 'La vinculación ya existe para el reporte "' . $nombre_Reporte_Padre . '" en el reporte "' . $nombre_Gpo_Valores_Reporte_Padre . '"';
        }

        $this->redirect("EstructuraProcesos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO CREAR NUEVO PROCESOESTRUCTURA---*/
    public function guardarmodificacion()
    {
        $estructuraProceso = new EstructuraProcesos($this->adapter);
        $allEstructuraProcesos = $estructuraProceso->getAllEstructuraProcesos($this->id_Proyecto_constant);

        $id_Proceso_Estructura = $_POST['id_Proceso_Estructura'];
        $id_Gpo_Valores_Padre = $_POST['id_Gpo_Valores_Padre'];
        $id_Reporte_Padre = $_POST['id_Reporte_Padre'];
        $inputPorcentaje = $_POST['inputPorcentaje'];
        $cantidad = $_POST['inputCantidad'];

        $procesoById = $estructuraProceso->getAllEstructuraProcesosByIdGpoValoresReportePadreAndIdReportePadre($this->id_Proyecto_constant, $id_Gpo_Valores_Padre, $id_Reporte_Padre);
        $nombre_Gpo_Valores_Reporte_Padre = $procesoById[0]->titulo_Reporte;
        $nombre_Reporte_Padre = $procesoById[0]->nombre_Reporte_Hijo;

        $estructuraProceso->set_id_Proceso_Estructura($id_Proceso_Estructura);
        $estructuraProceso->set_id_Reporte_Padre($id_Reporte_Padre);
        $estructuraProceso->set_Porcentaje($inputPorcentaje);
        $estructuraProceso->set_Cantidad($cantidad);
        $save = $estructuraProceso->modificarEstructura($id_Proceso_Estructura, $allEstructuraProcesos);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 3) {
            $insercion = 3;
            $mensaje = 'Se ha modificado la vinculación del reporte: "' . $nombre_Reporte_Padre . '" en el reporte "' . $nombre_Gpo_Valores_Reporte_Padre . '"';
        } else {
            $insercion = 2;
            $mensaje = 'La vinculación ya existe para el reporte "' . $nombre_Reporte_Padre . '" en el reporte "' . $nombre_Gpo_Valores_Reporte_Padre . '"';
        }

        $this->redirect("EstructuraProcesos", "index&insercion=$insercion&newElemento=$mensaje");
    }


    /*--- VISTA MODIFICAR PROCESOESTRUCTURA ---*/
    public function modificar()
    {
        $id_Proceso_Estructura = $_POST['id_Proceso_Estructura'];
        $estructuraProceso = new EstructuraProcesos($this->adapter);
        $infoReportePadre = $estructuraProceso->getAllEstructuraProcesosByIdEstrcturaProceso($id_Proceso_Estructura);

        $id_Gpo_Valores_Padre = $infoReportePadre[0]->id_Gpo_Valores_Padre;


        // Obtener registros de PROCESOESTRUCTURA
        $tipo_Reporte = '2,3';
        $noReportes = 'AND rl.id_Reporte != ' . $infoReportePadre[0]->id_Reporte;
        $noRepit = 'GROUP BY rl.id_Reporte ORDER BY nombre_Reporte';
        //$allRegistrosProcesos = $estructuraProceso->getAllReportesLlenadosByType($tipo_Reporte, $this->id_Proyecto_constant, $noReportes, $noRepit);

        $allRegistrosProcesos = $estructuraProceso->getAllCatReportesByNotIdReporte($infoReportePadre[0]->id_Reporte, $this->id_Proyecto_constant);

        // Calculo de Procentaje
        $allReportesPadre = $estructuraProceso->getAllEstructuraProcesosByIdGpoValoresReporte($this->id_Proyecto_constant, $id_Gpo_Valores_Padre);
        $porcentaje = 0;
        foreach ($allReportesPadre as $reportePadre) {
            $porcentaje = $porcentaje + $reportePadre->Porcentaje;
        }

        if ($porcentaje > 100)
            $porcentaje = 100;
        else
            $porcentaje = round($porcentaje, 2, PHP_ROUND_HALF_UP);

        echo json_encode([
            'infoReportePadre' => $infoReportePadre, 'allRegistrosProcesos' => $allRegistrosProcesos,
            "porcentaje" => $porcentaje
        ]);
    }


    /*--- METODO BORRAR PROCESOSESTRUCTURAs ---*/
    public function borrar()
    {
        $id_Proceso_Estructura = $_GET["id_Proceso_Estructura"];

        if (isset($id_Proceso_Estructura)) {
            $estructuraProceso = new EstructuraProcesos($this->adapter);
            $infoEstructura = $estructuraProceso->getAllEstructuraProcesosByIdEstrcturaProceso($id_Proceso_Estructura);

            $nombre_Gpo_Valores_Reporte_Padre = $infoEstructura[0]->titulo_Reporte;
            $nombre_Reporte_Padre = $infoEstructura[0]->nombre_Reporte_Hijo;

            $estructuraProceso->deleteElementoById($id_Proceso_Estructura, 'Proceso_Estructura');
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se elimino la vinculación del reporte "' . $nombre_Reporte_Padre . '" en el reporte "' . $nombre_Gpo_Valores_Reporte_Padre . '"';
        }
        $this->redirect("EstructuraProcesos", "index&insercion=$insercion&newElemento=$mensaje");

    }


    // FUNCION PARA DUPLICAR CONFIGURACION DE ESTRUCTURAS
    public function duplicarEstructura()
    {
        $estructuraProceso = new EstructuraProcesos($this->adapter);

        $id_Reporte_Duplicar = $_POST['idEstructuraProcesoDuplicar'];
        $id_Reporte_Nuevo = $_POST['idEstructuraProcesoNueva'];

        //$id_Reporte_Duplicar = '2';
        //$id_Reporte_Nuevo = '9';


        //OBTENER REPORTES DE LA ESTRUCTURA A DUPLICAR
        $getAllDatosEstructura = $estructuraProceso->getAllEstructuraProcesosByIdReporte($this->id_Proyecto_constant, $id_Reporte_Duplicar);

        //INSERTAR REPORTES HIJOS A REPORTE NUEVO
        $allEstructuraProcesos = $estructuraProceso->getAllEstructuraProcesos($this->id_Proyecto_constant);

        // /*
        foreach ($getAllDatosEstructura as $reportes) {
            $id_Gpo_Valores_Padre = $id_Reporte_Nuevo;
            $id_Reporte_Padre = $reportes->id_Reporte_Padre;
            $inputPorcentaje = $reportes->Porcentaje;
            $cantidad = $reportes->Cantidad;

            $estructuraProceso->set_id_Gpo_Valores_Padre($id_Gpo_Valores_Padre);
            $estructuraProceso->set_id_Reporte_Padre($id_Reporte_Padre);
            $estructuraProceso->set_Porcentaje($inputPorcentaje);
            $estructuraProceso->set_Cantidad($cantidad);
            $estructuraProceso->saveNewEstructura($allEstructuraProcesos);
        }
        // */


        $insercion = 1;
        $mensaje = 'Estructura Duplicada exitosamente';

        $this->redirect("EstructuraProcesos", "index&insercion=$insercion&newElemento=$mensaje");

    }

    // OBTENER DATOS DE ESTRUCTURAS DISPONIBLES (QUITAR LAS INSERTADAS)
    public function getAllRegistrosDisponibles()
    {
        $estructuraProceso = new EstructuraProcesos($this->adapter);
        $allEstructuraProcesosGuardados = $estructuraProceso->getAllEstructuraProcesosLlenadosGpoValores($this->id_Proyecto_constant);

        if (empty($allEstructuraProcesosGuardados)) {
            $noReportes = '';
        } else {
            $idsBloquear = array();
            foreach ($allEstructuraProcesosGuardados as $reportes) {
                $idsBloquear[] = $reportes->id_Gpo_Valores_Padre;
            }
            $idsBloquearStr = implode(",", $idsBloquear);
            $noReportes = 'AND rl.id_Gpo_Valores_Reporte NOT IN(' . $idsBloquearStr . ')';
        }

        $noRepit = '';
        $tipo_Reporte = '2,3';

        $allReportes = $estructuraProceso->getAllReportesLlenadosByType($tipo_Reporte, $this->id_Proyecto_constant, $noReportes, $noRepit);

        echo json_encode($allReportes);

    }


}
