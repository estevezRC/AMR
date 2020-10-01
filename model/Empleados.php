<?php

class Empleados extends EntidadBase
{

    private $id_proyecto;
    private $id_empleado;
    private $puesto;
    private $no_empleado;
    private $edad;
    private $genero;

    public function __construct($adapter)
    {
        $table = "empleados";
        parent::__construct($table, $adapter);
    }

    // id_empleado
    public function get_Id_Empleado()
    {
        return $this->id_empleado;
    }

    public function set_Id_Empleado($id_empleado)
    {
        $this->id_empleado = $id_empleado;
    }

    // id_proyecto
    public function getIdProyecto()
    {
        return $this->id_proyecto;
    }

    public function setIdProyecto($id_proyecto)
    {
        $this->id_proyecto = $id_proyecto;
    }


    // puesto
    public function getPuesto()
    {
        return $this->puesto;
    }

    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }


    // no_empleado
    public function getNoEmpleado()
    {
        return $this->no_empleado;
    }

    public function setNoEmpleado($no_empleado)
    {
        $this->no_empleado = $no_empleado;
    }

    // apellido_materno
    public function get_Edad()
    {
        return $this->edad;
    }

    public function set_Edad($edad)
    {
        $this->edad = $edad;
    }


    // genero
    public function get_Genero()
    {
        return $this->genero;
    }

    public function set_Genero($genero)
    {
        $this->genero = $genero;
    }

    // ****************************************** INSERTAR NUEVOS EMPLEADOS ********************************************
    public function saveNewEmpleado()
    {
        $query = "INSERT INTO empleados (id_proyecto, puesto, no_empleado, edad, genero,fecha_registro,status) 
                    VALUES ($this->id_proyecto, '$this->puesto','$this->no_empleado', $this->edad, '$this->genero', now(), 1)";

        return $this->db()->query($query);
    }


    // ****************************************** MODIFICACION DE EMPLEADOS ********************************************
    public function modificarEmpleado()
    {
        $query = "UPDATE empleados SET id_proyecto = $this->id_proyecto, puesto = '$this->puesto', 
                no_empleado = '$this->no_empleado', edad = $this->edad, genero = '$this->genero' 
                WHERE id_empleado = $this->id_empleado";

        return $this->db()->query($query);
    }

    // ************************************** MODIFICACION DE EMPLEADOS (STATUS) ***************************************
    public function modificarEstatus($status)
    {
        $query = "UPDATE empleados SET status = $status WHERE id_empleado = $this->id_empleado";
        return $this->db()->query($query);
    }
}

