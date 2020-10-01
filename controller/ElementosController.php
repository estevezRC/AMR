<?php

class ElementosController extends ControladorBase
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

    /*--- VISTA DE TODAS LAS TARIFAS ---*/
    public function index()
    {
        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-list-alt' aria-hidden='true'></i> Elementos";
        }


        $camporeporte = new CampoReporte($this->adapter);
        $allreportes = $camporeporte->getAllElementosByInvAndUbi($this->id_Proyecto_constant);


        $noElementos = $camporeporte->getAllCampoReporte($this->id_Proyecto_constant);


        if (empty($noElementos)) {
            $this->redirect('Plantilla', 'index');
        } else {
            $this->view("index", array(
                "mensaje" => $mensaje, "allreportes" => $allreportes
            ));
        }
    }

    public function verElementos()
    {
        $id_Reporte = $_POST['codigos'];
        $mensaje = $_GET['mensaje'];

        $camporeporte = new CampoReporte($this->adapter);
        $allreportes = $camporeporte->getAllElementosByInvAndUbiByID($this->id_Proyecto_constant, $id_Reporte);

        $resul = '';
        foreach ($allreportes as $codigoConcepto) {
            $resul = $codigoConcepto->nombre_Reporte;
        }

        $tipo_Reporte = '';
        foreach ($allreportes as $codigoConcepto) {
            $tipo_Reporte = $codigoConcepto->tipo_Reporte;
        }

        $nombre_Reporte = '';
        foreach ($allreportes as $codigoConcepto) {
            $nombre_Reporte = $codigoConcepto->nombre_Reporte;
        }

        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-list-alt' aria-hidden='true'></i> " . $resul;
        }

        $seguimientoreporte = new ReporteLlenado($this->adapter);

        $id_Proyecto_Actual = (int)$_POST["id_Proyecto"];
        if (!empty($id_Proyecto_Actual)) {
            $proyecto = new Proyecto($this->adapter);
            $datosproyecto = $proyecto->getProyectoById($id_Proyecto_Actual);
            $_SESSION[NOMBRE_PROYECTO] = $datosproyecto->nombre_Proyecto;
            session_start();
            $_SESSION[ID_PROYECTO_SUPERVISOR] = $datosproyecto->id_Proyecto;
        }
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

        $area = $_SESSION[ID_AREA_SUPERVISOR];

        $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoElementosByUbiAndInv($area, $id_Proyecto, $id_Reporte);

        $this->view("index", array(
            "mensaje" => $mensaje, "allseguimientosreportes" => $allseguimientosreportes, "id_Reporte" => $id_Reporte,
            "tipo_Reporte" => $tipo_Reporte, "nombre_Reporte" => $nombre_Reporte
        ));

        //print_r($allseguimientosreportes);

    }

    /*--- VISTA DE TODAS LAS TARIFAS ---*/ //Posiblemente esta accion ya no funciona
    public function llenarElementos()
    {
        $mensaje = $_GET['mensaje'];

        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> LLenar Elementos";
        }


        $camporeporte = new CampoReporte($this->adapter);
        $allreportes = $camporeporte->getAllElementosByInvAndUbi($this->id_Proyecto_constant);


        $this->view("index", array(
            "mensaje" => $mensaje, "allreportes" => $allreportes
        ));
    }

}

?>
