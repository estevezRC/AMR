<?php

class Participante extends EntidadBase
{
    private $id_Usuario;
    private $correo_Usuario;
    private $password_Usuario;
    private $puesto;
    private $empresa;

    public function __construct($adapter)
    {
        $table = "Usuarios";
        parent::__construct($table, $adapter);
    }


    // id_Usuario
    public function setIdUsuario($id_Usuario): Participante
    {
        $this->id_Usuario = $id_Usuario;
        return $this;
    }


    // correo_Usuario
    public function setCorreoUsuario($correo_Usuario): Participante
    {
        $this->correo_Usuario = $correo_Usuario;
        return $this;
    }


    // password_Usuario
    public function setPasswordUsuario($password_Usuario): Participante
    {
        $this->password_Usuario = $password_Usuario;
        return $this;
    }


    // puesto
    public function setPuesto($puesto): Participante
    {
        $this->puesto = $puesto;
        return $this;
    }


    // empresa
    public function setEmpresa($empresa): Participante
    {
        $this->empresa = $empresa;
        return $this;
    }


    // ********************************** METODO PARA GUARDAR A UN NUEVO PARTICIPANTE **********************************
    public function save($usuarios)
    {
        $validate = true;
        foreach ($usuarios as $usuario) {
            if ($usuario->correo_Usuario == $this->correo_Usuario)
                $validate = false;
        }

        if ($validate) {
            $query = "INSERT INTO Usuarios (id_Empresa, id_Area, correo_Usuario, password_Usuario, fecha_Usuario, 
            id_Status_Usuario, participante, puesto, empresa) 
            VALUES (1, 1, '$this->correo_Usuario', AES_ENCRYPT('$this->usuariopassword', 'getitcom_2017'), now(), 2, 1, 
            '$this->puesto', '$this->empresa')";

            return $this->db()->query($query);
        } else
            return false;

    }


    // ************************************ METODO PARA ACTUALIZAR A UN PARTICIPANTE ***********************************
    public function update($usuarios)
    {
        $validate = true;
        foreach ($usuarios as $usuario) {
            if ($usuario->correo_Usuario == $this->correo_Usuario && $usuario->id_usuario != $this->id_Usuario)
                $validate = false;
        }

        if ($validate) {
            $query = "UPDATE Usuarios SET correo_Usuario = '$this->correo_Usuario', puesto = '$this->puesto',
                empresa = '$this->empresa' WHERE id_Usuario = $this->id_Usuario";

            return $this->db()->query($query);
        } else
            return false;
    }

    // ********************************** METODO PARA CAMBIAR STATUS DE UN PARTICIPANTE ********************************
    public function updateStatus($id, $status)
    {
        $query = "UPDATE Usuarios SET participante = $status WHERE id_Usuario = $id";
        return $this->db()->query($query);
    }


}

?>
