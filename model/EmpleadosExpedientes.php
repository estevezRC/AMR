<?php

class EmpleadosExpedientes extends EntidadBase
{

    private $id;
    private $tipo_archivo;
    private $nombre_archivo;
    private $id_emp;


    public function __construct($adapter)
    {
        $table = "empleados_expediente";
        parent::__construct($table, $adapter);
    }


    // id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    // tipo_archivo
    public function getTipoArchivo()
    {
        return $this->tipo_archivo;
    }

    public function setTipoArchivo($tipo_archivo)
    {
        $this->tipo_archivo = $tipo_archivo;
    }


    // nombre_archivo
    public function getNombreArchivo()
    {
        return $this->nombre_archivo;
    }

    public function setNombreArchivo($nombre_archivo)
    {
        $this->nombre_archivo = $nombre_archivo;
    }


    // id_emp
    public function getIdEmp()
    {
        return $this->id_emp;
    }

    public function setIdEmp($id_emp)
    {
        $this->id_emp = $id_emp;
    }


    // ****************************************** INSERTAR EXPEDIENTE EMPLEADOS ****************************************
    public function saveExpedienteEmpleado()
    {
        $query = "INSERT INTO empleados_expediente (tipo_archivo, nombre_archivo, status, id_emp) 
            VALUES ('$this->tipo_archivo', '$this->nombre_archivo', 1, $this->id_emp)";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


    // *************************************** MODIFICACION DE EXPEDIENTE EMPLEADOS ************************************
    public function modificarExpedienteEmpleado()
    {
        $query = "UPDATE empleados_expediente SET tipo_archivo = '$this->tipo_archivo', nombre_archivo = '$this->nombre_archivo' 
            WHERE id = $this->id";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


    // ************************************ ACTUALIZAR STATUS DE REGISTRO **********************************************
    public function borrarArchivoExpediente()
    {
        $query = "UPDATE empleados_expediente SET status = 0 WHERE id = $this->id";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


}

