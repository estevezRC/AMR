<?php

class Notificaciones extends EntidadBase
{
    private $id_notificacion;
    private $id_notificacion_detalle;
    private $id_usuarionotificacion;
    private $id_usuarionotifico;
    private $id_Gpo_Valores_Reporte;
    private $id_status;

    public function __construct($adapter)
    {
        $table = "Notificacion";
        parent::__construct($table, $adapter);
    }

    // $id_notificacion
    public function get_id_notificacion()
    {
        return $this->id_notificacion;
    }

    public function set_id_notificacion($id_notificacion)
    {
        $this->id_notificacion = $id_notificacion;
    }

    // $id_notificacion_detalle
    public function get_id_notificacion_detalle()
    {
        return $this->id_notificacion_detalle;
    }

    public function set_id_notificacion_detalle($id_notificacion_detalle)
    {
        $this->id_notificacion_detalle = $id_notificacion_detalle;
    }

    // $id_usuarionotificacion
    public function get_id_usuarionotificacion()
    {
        return $this->id_usuarionotificacion;
    }

    public function set_id_usuarionotificacion($id_usuarionotificacion)
    {
        $this->id_usuarionotificacion = $id_usuarionotificacion;
    }

    // $id_usuarionotifico
    public function get_id_usuarionotifico()
    {
        return $this->id_usuarionotifico;
    }

    public function set_id_usuarionotifico($id_usuarionotifico)
    {
        $this->id_usuarionotifico = $id_usuarionotifico;
    }

    // $id_Gpo_Valores_Reporte
    public function get_id_Gpo_Valores_Reporte()
    {
        return $this->id_Gpo_Valores_Reporte;
    }

    public function set_id_Gpo_Valores_Reporte($id_Gpo_Valores_Reporte)
    {
        $this->id_Gpo_Valores_Reporte = $id_Gpo_Valores_Reporte;
    }

    // $id_status
    public function get_id_status()
    {
        return $this->id_status;
    }

    public function set_id_status($id_status)
    {
        $this->id_status = $id_status;
    }


    public function saveNotificacion()
    {
        $query = "INSERT INTO Notificacion VALUES (NULL, $this->id_notificacion_detalle, $this->id_usuarionotificacion, $this->id_usuarionotifico, $this->id_Gpo_Valores_Reporte, 1, now())";
        //$query = "CALL sp_Add_Up_Notificacion(NULL, $this->id_notificacion_detalle, $this->id_usuarionotificacion,
        //$this->id_usuarionotifico, $this->id_Gpo_Valores_Reporte, NULL, 'Insertar')";
        $this->db()->query($query);
        return 'Se realizo una insercion';
    }

    public function updateNotificacion($id_notificacion)
    {
        $query = "UPDATE Notificacion SET id_status = 0 where id_notificacion = $id_notificacion";
        //$query = "CALL sp_Add_Up_Notificacion($id_notificacion, NULL, NULL, NULL, NULL, 0, 'Modificar')";
        $this->db()->query($query);
    }



}

?>
