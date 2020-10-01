<?php

class EmpleadosSalarios extends EntidadBase
{

    private $id_empleados_salarios;
    private $salario;
    private $id_emp;

    public function __construct($adapter)
    {
        $table = "empleados_salarios";
        parent::__construct($table, $adapter);
    }


    // id_empleados_salarios
    public function getIdEmpleadosSalarios()
    {
        return $this->id_empleados_salarios;
    }

    public function setIdEmpleadosSalarios($id_empleados_salarios)
    {
        $this->id_empleados_salarios = $id_empleados_salarios;
    }


    // salario
    public function getSalario()
    {
        return $this->salario;
    }

    public function setSalario($salario)
    {
        $this->salario = $salario;
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


    // ****************************************** INSERTAR SALARIO EMPLEADOS *******************************************
    public function saveSalarioEmpleado()
    {
        $query = "INSERT INTO empleados_salarios (salario, fecha_registro, status, id_emp) 
            VALUES ($this->salario, now(), 1, $this->id_emp)";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


    // **************************************** MODIFICACION DE SALARIO EMPLEADOS **************************************
    public function modificarSalarioEmpleado()
    {
        $query = "UPDATE empleados_salarios SET salario = $this->salario WHERE id_emp = $this->id_emp";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


}

