<?php

class ProcesosController extends ControladorBase
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

    /*--- VISTA DE TODOS LOS PROCESOS ---*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = '<i class="fa fa-random" aria-hidden="true"></i> Procesos';
        }

        $proceso = new Procesos($this->adapter);
        $allProcesos = $proceso->getAllProcesos($this->id_Proyecto_constant);

        $reporte = new Reporte($this->adapter);
        $noReportes = '';
        $allReportes = $reporte->getAllReporteSinIncidencias($this->id_Proyecto_constant, $noReportes);

        // OBTENER PROCESOS PADRE GUARDADOS PARA DUPLICAR
        $allProcesosGuardados = $proceso->getAllProcesosLlenadosPadre($this->id_Proyecto_constant);

        $this->view("index", array(
            "mensaje" => $mensaje, "insercion" => $insercion, "newElemento" => $newElemento, "allProcesos" => $allProcesos,
            "allReportes" => $allReportes, "allProcesosGuardados" => $allProcesosGuardados
        ));
    }

    /*--- VISTA DE TODOS LOS PROCESOS ---*/
    public function getDatosReportHijos()
    {
        $idReportePadre = $_POST['idReportePadre'];

        $reporte = new Reporte($this->adapter);
        // Obtener reportes ya configurados
        $reportesConfigurados = $reporte->getAllProcesosById_Reporte_Padre($idReportePadre);
        if ($reportesConfigurados == '' || empty($reportesConfigurados))
            $noReportes = 'AND id_Reporte NOT IN(' . $idReportePadre . ')';
        else {
            $idsBloquear = array();
            foreach ($reportesConfigurados as $reportesCon) {
                $idsBloquear[] = $reportesCon->Id_Reporte_Hijo;
            }
            $idsBloquearStr = implode(",", $idsBloquear);
            $noReportes = 'AND id_Reporte NOT IN(' . $idReportePadre . ', ' . $idsBloquearStr . ')';
        }


        $allReportes = $reporte->getAllReporteSinIncidencias($this->id_Proyecto_constant, $noReportes);

        // Calculo de Procentaje
        $allReportesPadre = $reporte->getAllProcesosById_Reporte_Padre($idReportePadre);
        $porcentaje = 0;
        foreach ($allReportesPadre as $reportePadre) {
            $porcentaje = $porcentaje + $reportePadre->Porcentaje;
        }

        echo json_encode(['allReportes' => $allReportes, 'porcentaje' => $porcentaje,
            'nombreReportePadre' => $allReportesPadre[0]->nombre_Reporte_Padre]);

    }


    /*--- VISTA MODIFICAR PROCESOS ---*/
    public function modificar()
    {
        $idProceso = $_POST['idProceso'];
        $proceso = new Procesos($this->adapter);
        $infoReportePadre = $proceso->getAllProcesosbyIdProceso($idProceso);

        echo json_encode(['infoReportePadre' => $infoReportePadre]);
    }

    /*--- METODO CREAR NUEVO PROCESO---*/
    public function guardarnuevo()
    {
        $proceso = new Procesos($this->adapter);
        $allProcesos = $proceso->getAllProcesos($this->id_Proyecto_constant);

        $idReportePadre = $_POST['idReportePadre'];
        $idReporteHijo = $_POST['idReporteHijo'];
        $inputPorcentaje = $_POST['inputPorcentaje'];

        $proceso->set_Id_Reporte_Padre($idReportePadre);
        $proceso->set_Id_Reporte_Hijo($idReporteHijo);
        $proceso->set_Porcentaje($inputPorcentaje);
        $save = $proceso->saveNewProceso($allProcesos);

        $procesoById = $proceso->getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($idReportePadre, $idReporteHijo);
        $nombre_Reporte_Padre = $procesoById[0]->nombre_Reporte_Padre;
        $nombre_Reporte_Hijo = $procesoById[0]->nombre_Reporte_Hijo;

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado la vinculación del reporte: "' . $nombre_Reporte_Hijo . '" en el reporte "' . $nombre_Reporte_Padre . '"';
        } else {
            $insercion = 2;
            $mensaje = 'La vinculación ya existe para el reporte "' . $nombre_Reporte_Hijo . '" en el reporte "' . $nombre_Reporte_Padre . '"';
        }

        $this->redirect("Procesos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO GUARDAR MODIFICACION PROCESOS ---*/
    public function guardarmodificacion()
    {
        $proceso = new Procesos($this->adapter);
        $allProcesos = $proceso->getAllProcesos($this->id_Proyecto_constant);

        $id_Proceso = $_POST['id_Proceso'];
        $idReportePadre = $_POST['idReportePadre'];
        $idReporteHijo = $_POST['idReporteHijo'];
        $inputPorcentaje = $_POST['inputPorcentaje'];

        $procesoById = $proceso->getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($idReportePadre, $idReporteHijo);
        $nombre_Reporte_Padre = $procesoById[0]->nombre_Reporte_Padre;
        $nombre_Reporte_Hijo = $procesoById[0]->nombre_Reporte_Hijo;

        $proceso->set_Id_Reporte_Padre($idReportePadre);
        $proceso->set_Id_Reporte_Hijo($idReporteHijo);
        $proceso->set_Porcentaje($inputPorcentaje);
        $save = $proceso->modificarProceso($id_Proceso, $allProcesos);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 3) {
            $insercion = 3;
            $mensaje = 'Se ha modificado la vinculación del reporte: "' . $nombre_Reporte_Hijo . '" en el reporte "' . $nombre_Reporte_Padre . '"';
        } else {
            $insercion = 2;
            $mensaje = 'La vinculación ya existe para el reporte "' . $nombre_Reporte_Hijo . '" en el reporte "' . $nombre_Reporte_Padre . '"';
        }

        $this->redirect("Procesos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO BORRAR PROCESOS ---*/
    public function borrar()
    {
        $idProceso = $_GET["id_Proceso"];
        if (isset($idProceso)) {
            $proceso = new Procesos($this->adapter);
            $infoProceso = $proceso->getAllProcesosbyIdProceso($idProceso);

            $nombre_Reporte_Hijo = $infoProceso[0]->nombre_Reporte_Hijo;
            $nombre_Reporte_Padre = $infoProceso[0]->nombre_Reporte_Padre;

            $proceso->deleteElementoById($idProceso, 'Procesos');
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se elimino la vinculación del reporte "' . $nombre_Reporte_Hijo . '" en el reporte "' . $nombre_Reporte_Padre . '"';
        }
        $this->redirect("Procesos", "index&insercion=$insercion&newElemento=$mensaje");

    }


    public function duplicarProceso()
    {
        $proceso = new Procesos($this->adapter);

        $id_Reporte_Duplicar = $_POST['idProcesoDuplicar'];
        $id_Reporte_Nuevo = $_POST['idProcesoNuevo'];

        //OBTENER REPORTES HIJOS DE REPORTE DUPLICAR
        $getAllDatosReportesHijos = $proceso->getAllProcesosById_Reporte_Padre($id_Reporte_Duplicar);

        //INSERTAR REPORTES HIJOS A REPORTE NUEVO
        $allProcesos = $proceso->getAllProcesos($this->id_Proyecto_constant);

        foreach ($getAllDatosReportesHijos as $reportesHijos) {
            $idReportePadre = $id_Reporte_Nuevo;
            $idReporteHijo = $reportesHijos->Id_Reporte_Hijo;
            $inputPorcentaje = $reportesHijos->Porcentaje;

            if ($idReporteHijo != $id_Reporte_Nuevo) {
                $proceso->set_Id_Reporte_Padre($idReportePadre);
                $proceso->set_Id_Reporte_Hijo($idReporteHijo);
                $proceso->set_Porcentaje($inputPorcentaje);
                $proceso->saveNewProceso($allProcesos);
            }
        }

        $insercion = 1;
        $mensaje = 'Proceso Duplicado exitosamente';

        $this->redirect("Procesos", "index&insercion=$insercion&newElemento=$mensaje");

    }

    public function getAllReportesPadreDisponibles()
    {
        $reporte = new Reporte($this->adapter);

        // OBTENER ID_REPORTES PADRES YA INSERTADOS
        $allReportesPadresGuardados = $reporte->getAllProcesosLlenadosPadre($this->id_Proyecto_constant);

        if (empty($allReportesPadresGuardados)) {
            $noReportes = '';
        } else {
            $idsBloquear = array();
            foreach ($allReportesPadresGuardados as $reportes) {
                $idsBloquear[] = $reportes->Id_Reporte_Padre;
            }
            $idsBloquearStr = implode(",", $idsBloquear);
            $noReportes = 'AND id_Reporte NOT IN(' . $idsBloquearStr . ')';
        }

        $allReportes = $reporte->getAllReporteSinIncidencias($this->id_Proyecto_constant, $noReportes);

        echo json_encode($allReportes);

    }

}

?>
