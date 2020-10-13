<?php

class AreasController extends ControladorBase
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

    /*--- VISTA DE TODOS LOS AREAS ---*/
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
            $mensaje = '<i class="fa fa-sitemap" aria-hidden="true"></i> Áreas';
        }
        $area = new Area($this->adapter);
        $allareas = $area->getAllArea();
        $allempresas = $area->getAllEmpresas();
        $this->view("index", array(
            "allareas" => $allareas, "allempresas" => $allempresas, "mensaje" => $mensaje, "insercion" => $insercion,
            "newElemento" => $newElemento
        ));
    }

    /*--- VISTA MODIFICAR AREA ---*/
    public function modificar()
    {
        if (isset($_POST["areaid"])) {

            $area = new Area($this->adapter);
            $id = (int)$_POST["areaid"];
            $datosarea = $area->getAreaById($id);
        }

        echo json_encode($datosarea);
    }

    /*--- METODO CREAR NUEVO AREA---*/
    public function guardarnuevo()
    {
        $area = new Area($this->adapter);
        $allareas = $area->getAllArea();
        $nombreArea = $_POST["areanombre"];
        $area->setNombre($nombreArea);
        $fecha_hora = $this->fecha();
        $area->setFecha($fecha_hora);
        $area->set_id_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);
        $save = $area->saveNewArea($allareas);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado el area: "' . $nombreArea . '"';
        } else {
            $insercion = 2;
            $mensaje = 'El area "' . $nombreArea . '" ya existe';
        }

        $this->redirect("Areas", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO GUARDAR MODIFICACION AREA ---*/
    public function guardarmodificacion()
    {
        $area = new Area($this->adapter);
        $allareas = $area->getAllArea();
        $id = $_POST["areaid"];
        $nombreArea = $_POST["areanombre"];
        $area->setNombre($nombreArea);
        $area->set_id_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);
        $save = $area->modificarArea($id, $allareas);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 3) {
            $insercion = 3;
            $mensaje = 'Se ha modificado el area: "' . $nombreArea . '"';
        } else {
            $insercion = 2;
            $mensaje = 'El area "' . $nombreArea . '" ya existe';
        }

        $this->redirect("Areas", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO BORRAR AREA ---*/
    public function borrar()
    {
        if (isset($_GET["areaid"])) {
            $id = (int)$_GET["areaid"];
            $area = new Area($this->adapter);
            $allareas = $area->getAreaById($id);
            $nombreArea = $allareas->nombre_Area;
            $area->deleteElementoById($id, 'Areas');
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se eliminó el area "' . $nombreArea . '"';
        }
        $this->redirect("Areas", "index&insercion=$insercion&newElemento=$mensaje");

    }
}

?>
