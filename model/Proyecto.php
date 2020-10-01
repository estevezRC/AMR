<?php

class Proyecto extends EntidadBase
{
    private $id_Proyecto;
    private $nombre_Proyecto;
    private $descripcion_Proyecto;
    private $id_Area;
    private $id_Estatus_Proyecto;
    private $logotipos;

    public function __construct($adapter)
    {
        $table = "Proyectos";
        parent::__construct($table, $adapter);
    }

//ID
    public function get_id_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function set_id_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
    }

//NOMBRE
    public function get_nombre_Proyecto()
    {
        return $this->nombre_Proyecto;
    }

    public function set_nombre_Proyecto($nombre_Proyecto)
    {
        $this->nombre_Proyecto = $nombre_Proyecto;
    }

//DESCRIPCION
    public function get_descripcion_Proyecto()
    {
        return $this->descripcion_Proyecto;
    }

    public function set_descripcion_Proyecto($descripcion_Proyecto)
    {
        $this->descripcion_Proyecto = $descripcion_Proyecto;
    }

    public function get_logotipos()
    {
        return $this->logotipos;
    }

    public function set_logotipos($logotipos)
    {
        $this->logotipos = $logotipos;
    }

    /*--- PROYECTOS: REGISTRAR PROYECTOS ---*/
    public function saveNewProyecto($proyectos)
    {
        $validate = true;
        foreach ($proyectos as $proyecto) {
            if ($proyecto->nombre_Proyecto == $this->nombre_Proyecto) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_Proyectos(NULL,'$this->nombre_Proyecto','$this->descripcion_Proyecto','Insertar', $this->logotipos)";

            if ($this->db()->query($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*--- AREAS: ACTUALIZAR AREA POR ID ---*/
    public function modificarProyecto($id, $proyectos)
    {
        $validate = true;
        foreach ($proyectos as $proyecto) {
            if (($proyecto->nombre_Proyecto == $this->nombre_Proyecto) && ($proyecto->id_Proyecto != $id)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_Proyectos($id,'$this->nombre_Proyecto','$this->descripcion_Proyecto','Modificar', $this->logotipos)";
            if ($this->db()->query($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateLogos($jsonLogos, $idProyecto)
    {
        $query = "UPDATE Proyectos SET logotipos = $jsonLogos WHERE id_Proyecto = $idProyecto";

        if ($this->db()->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
