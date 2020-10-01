<?php

class Firma extends EntidadBase
{
    private $id_Firma;
    private $id_Gpo;
    private $id_Usuario;
    private $fecha_hora;
    private $firma;
    private $id_Status;

    public function __construct($adapter)
    {
        $table = "Firma";
        parent::__construct($table, $adapter);
    }

//ID
    public function get_id_Firma()
    {
        return $this->id_Firma;
    }
    public function set_id_Firma($id_Firma)
    {
        $this->id_Firma = $id_Firma;
    }

//ID GPO
    public function get_id_Gpo()
    {
        return $this->id_Gpo;
    }
    public function set_id_Gpo($id_Gpo)
    {
        $this->id_Gpo = $id_Gpo;
    }

//ID USUARIO
    public function get_id_Usuario()
    {
        return $this->id_Usuario;
    }
    public function set_id_Usuario($id_Usuario)
    {
        $this->id_Usuario = $id_Usuario;
    }

//FECHA HORA
    public function get_fecha_hora()
    {
        return $this->get_fecha_hora();
    }
    public function set_fecha_hora($fecha_hora)
    {
        $this->fecha_hora = $fecha_hora;
    }

//FIRMA
    public function get_firma()
    {
        return $this->firma;
    }
    public function set_firma($firma)
    {
        $this->firma = $firma;
    }

    //id_Status
    public function get_id_Status()
    {
        return $this->id_Status;
    }
    public function set_id_Status($id_Status)
    {
        $this->id_Status = $id_Status;
    }

    public function saveNewFirma()
    {
        $query = "INSERT INTO Firma
        (id_Firma, 
        id_Gpo, 
        id_Usuario,
        fecha_hora,
        firma,
        id_Status) 
        VALUES 
        (NULL, 
        $this->id_Gpo, 
        $this->id_Usuario,
        '$this->fecha_hora',
        '$this->firma',
        $this->id_Status);";
        $this->db()->query($query);
        $mensaje = "carga correcta";
        //return $mensaje;
    }

}

?>
