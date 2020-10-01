<?php

class EmpleadoUsuario extends EntidadBase
{
    private $id_empleado_usuario;
    private $nombre;
    private $apellido_paterno;
    private $apellido_materno;
    private $id_empleado;
    private $id_usuario;

    public function __construct($adapter)
    {
        $table = "empleados_usuarios";
        parent::__construct($table, $adapter);
    }

    // $id_empleado_usuario
    public function getIdEmpleadoUsuario()
    {
        return $this->id_empleado_usuario;
    }

    public function setIdEmpleadoUsuario($id_empleado_usuario)
    {
        $this->id_empleado_usuario = $id_empleado_usuario;
    }


    // $nombre
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }


    // $apellido_paterno
    public function getApellidoPaterno()
    {
        return $this->apellido_paterno;
    }

    public function setApellidoPaterno($apellido_paterno)
    {
        $this->apellido_paterno = $apellido_paterno;
    }


    // $apellido_materno
    public function getApellidoMaterno()
    {
        return $this->apellido_materno;
    }

    public function setApellidoMaterno($apellido_materno)
    {
        $this->apellido_materno = $apellido_materno;
    }


    // $id_empleado
    public function getIdEmpleado()
    {
        return $this->id_empleado;
    }

    public function setIdEmpleado($id_empleado)
    {
        $this->id_empleado = $id_empleado;
    }


    // $id_usuario
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }


    public function saveNewEmpleadoUsuario()
    {
        $query = "INSERT INTO empleados_usuarios (nombre, apellido_paterno, apellido_materno, id_empleado, id_usuario, status) 
                    VALUES ('$this->nombre', '$this->apellido_paterno', '$this->apellido_materno', '$this->id_empleado',
                            '$this->id_usuario', 1)";

        return $this->db()->query($query);
    }


    public function updateExisteEmpleadoUsuario()
    {
        $query = "UPDATE empleados_usuarios SET id_usuario = '$this->id_usuario' WHERE id_empleado = $this->id_empleado";

        return $this->db()->query($query);
    }

    public function updateExisteUsuarioEmpleado()
    {
        $query = "UPDATE empleados_usuarios SET id_empleado = '$this->id_empleado' WHERE id_usuario = $this->id_usuario";

        return $this->db()->query($query);
    }


    public function modificarEmpleadoUsuario()
    {
        $query = "UPDATE empleados_usuarios SET nombre = '$this->nombre', apellido_paterno = '$this->apellido_paterno',
                apellido_materno = '$this->apellido_materno' WHERE id_usuario = $this->id_usuario";

        return $this->db()->query($query);
    }

    public function modificarEmpleadoUsuario2()
    {
        $query = "UPDATE empleados_usuarios SET nombre = '$this->nombre', apellido_paterno = '$this->apellido_paterno',
                apellido_materno = '$this->apellido_materno' WHERE id_empleado = $this->id_empleado";

        return $this->db()->query($query);
    }

}
