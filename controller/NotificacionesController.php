<?php

class NotificacionesController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    public function obtenerNotificaciones()
    {
        $notificaciones = new Notificaciones($this->adapter);
        $allNotificaciones = $notificaciones->getAllNotificacionesWeb($_SESSION[ID_USUARIO_SUPERVISOR]);

        echo json_encode($allNotificaciones);

    }
}

?>
