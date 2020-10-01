<?php

class BitacorasController extends ControladorBase
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

    /*--- VISTA DE TODAS LAS ACTIVIDADES ---*/
    public function index()
    {
        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-list-alt' aria-hidden='true'></i> Bitacora de Actividades";
        }
        $bitacora = new Bitacora($this->adapter);

        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1)
            $allbitacoras = $bitacora->getAllBitacora($this->id_Proyecto_constant);
        else
            $allbitacoras = $bitacora->getAllBitacoraNoAdmin($this->id_Proyecto_constant);

        $this->view("index", array(
            "allbitacoras" => $allbitacoras, "mensaje" => $mensaje
        ));
        //print_r($allbitacoras);
    }


}

?>
