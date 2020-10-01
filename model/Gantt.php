<?php

class Gantt extends EntidadBase
{
    private $id;
    private $titulo;
    private $fecha;
    private $status;
    private $idProyecto;
    private $estructura;

    public function __construct($adapter)
    {
        $table = "gantt";
        parent::__construct($table, $adapter);
    }

    //ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    //NOMBRE
    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    //APELLIDO
    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    //CORREO
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    //PASSWORD
    public function getIdProyecto()
    {
        return $this->idProyecto;
    }

    public function setProyecto($idProyecto)
    {
        $this->idProyecto = $idProyecto;
    }

    # Estructura
    public function getEstructura()
    {
        return $this->estructura;
    }

    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;
    }

    /*--- LLENADO REPORTE: REGISTRAR LLENADO---*/
    public function guardar()
    {
        $query = "INSERT INTO gantt (titulo, fecha, status, id_proyecto)
            VALUES ('$this->titulo', NOW(), '$this->status', $this->idProyecto)";

        return $this->db()->query($query);
    }

    public function updateEstructura($idGantt)
    {
        $query = "UPDATE gantt SET estructura = '$this->estructura' WHERE id = $idGantt;";

         return $this->db()->query($query);
    }
}

