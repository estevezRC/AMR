<?php

class AvanceActividad extends EntidadBase
{
    private $id_avance;
    private $id_nodo;
    private $gpo_valores;
    private $id_Proyecto;

    public function __construct($adapter)
    {
        $table = "avance_actividad";
        parent::__construct($table, $adapter);
    }

    // id_avance
    public function get_id_avance()
    {
        return $this->id_avance;
    }

    public function set_id_avance($id_avance)
    {
        $this->id_avance = $id_avance;
    }


    // id_nodo
    public function get_id_nodo()
    {
        return $this->id_nodo;
    }

    public function set_id_nodo($id_nodo)
    {
        $this->id_nodo = $id_nodo;
    }


    // gpo_valores
    public function get_gpo_valores()
    {
        return $this->gpo_valores;
    }

    public function set_gpo_valores($gpo_valores)
    {
        $this->gpo_valores = $gpo_valores;
    }


    // id_Proyecto
    public function get_id_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function set_id_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
    }


    // ************************ FUNCION PARA INSERTAR EN LA TABLA avance_actividad *************************************
    public function saveAvanceActividad()
    {
        $query = "INSERT INTO avance_actividad (
                    id_avance, id_nodo, gpo_valores, fecha, id_proyecto, id_status) 
            VALUES (null, $this->id_nodo, $this->gpo_valores, now(), $this->id_Proyecto, 1)";

        return $this->db()->query($query);

    }

    // ************************** FUNCION PARA MODIFICAR EN LA TABLA avance_actividad **********************************
    public function modificarAvanceActividad()
    {
        $query = "UPDATE avance_actividad SET id_nodo = $this->id_nodo WHERE gpo_valores = $this->gpo_valores";

        return $this->db()->query($query);
    }


}

