<?php

class Usuario extends EntidadBase
{
    private $usuarioid;
    private $usuariocorreo;
    private $usuariopassword;
    private $usuariofecha;
    private $usuariofotografia;
    private $usuarioborrado;
    private $participante;
    private $areaid;
    private $id_Empresa;
    private $puesto;
    private $empresa;

    public function __construct($adapter)
    {
        $table = "Usuarios";
        parent::__construct($table, $adapter);
    }

    //ID
    public function getId()
    {
        return $this->usuarioid;
    }

    public function setId($usuarioid)
    {
        $this->usuarioid = $usuarioid;
    }

    //CORREO
    public function getCorreo()
    {
        return $this->usuariocorreo;
    }

    public function setCorreo($usuariocorreo)
    {
        $this->usuariocorreo = $usuariocorreo;
    }

    //PASSWORD
    public function getPassword()
    {
        return $this->usuariopassword;
    }

    public function setPassword($usuariopassword)
    {
        $this->usuariopassword = $usuariopassword;
    }

    //FECHA
    public function getFecha()
    {
        return $this->usuariofecha;
    }

    public function setFecha($usuariofecha)
    {
        $this->usuariofecha = $usuariofecha;
    }

    //FOOGRAFIA
    public function getFotografia()
    {
        return $this->usuariofotografia;
    }

    public function setFotografia($usuariofotografia)
    {
        $this->usuariofotografia = $usuariofotografia;
    }

    //BORRADO
    public function getBorrado()
    {
        return $this->usuarioborrado;
    }

    public function setBorrado($usuarioborrado)
    {
        $this->usuarioborrado = $usuarioborrado;
    }

    //AREA
    public function getArea()
    {
        return $this->areaid;
    }

    public function SetArea($areaid)
    {
        $this->areaid = $areaid;
    }

    //EMPRESA
    public function get_Empresa()
    {
        return $this->id_Empresa;
    }

    public function set_Empresa($id_Empresa)
    {
        $this->id_Empresa = $id_Empresa;
    }

    //PARTICIPANTE
    public function get_participante()
    {
        return $this->participante;
    }

    public function set_participante($participante)
    {
        $this->participante = $participante;
    }

    // puesto
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }

    // empresa
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /*--- USUARIOS: REGISTRAR USUARIO ---*/
    public function save($usuarios)
    {
        $validate = true;
        foreach ($usuarios as $usuario) {
            if ($usuario->correo_Usuario == $this->usuariocorreo)
                $validate = false;
        }

        if ($validate) {
            $query = "INSERT INTO Usuarios (id_Empresa, id_Area, correo_Usuario, password_Usuario, fecha_Usuario, 
                fotografia_Usuario, id_Status_Usuario, participante, puesto, empresa)
                VALUES ($this->id_Empresa, $this->areaid, '$this->usuariocorreo', AES_ENCRYPT('$this->usuariopassword', 'getitcom_2017'),
                NOW(), null, 1, $this->participante, '$this->puesto', '$this->empresa')";
            if ($this->db()->query($query))
                return 1;
            else
                return 2;
        } else
            return 2;
    }

    /*--- USUARIOS: ACTUALIZAR USUARIO POR ID ---*/
    public function modificarUsuario($id, $usuarios)
    {
        $validate = true;
        foreach ($usuarios as $usuario) {
            if ($usuario->correo_Usuario == $this->usuariocorreo && $usuario->id_Usuario != $id) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "UPDATE Usuarios SET id_Empresa = $this->id_Empresa, id_Area = $this->areaid,
                correo_Usuario = '$this->usuariocorreo', participante = $this->participante, puesto = '$this->puesto', 
                empresa = '$this->empresa' WHERE id_Usuario = $id";
            if ($this->db()->query($query))
                return 3;
            else
                return $query;

        } else {
            return 2;
        }
    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function modificarUsuarioDatos($id, $usuarios)
    {
        $row_cnt = 1;
        foreach ($usuarios as $usuario) {
            if (($usuario->correo_Usuario == $this->usuariocorreo) && ($usuario->id_Usuario != $id)) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            return 2;
        } else {
            $query = "CALL SP_AddUpUsuario(
  					$id,
  					NULL,
  					NULL,
  					'$this->usuariocorreo',
  					NULL,
  					NULL,
  					NULL,
  					NULL,
  					NULL,
  					'Modificardatos')";
            $this->db()->query($query);
            return 1;
        }
    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function modificarUsuarioPwd($id)
    {
        $query = "CALL SP_AddUpUsuario(
  					$id,
  					NULL,
  					NULL,
  					NULL,
  					'$this->usuariopassword',
  					NULL,
  					NULL,
  					NULL,
  					'Modificarpwd')";
        //return $query;
        return $this->db()->query($query);

    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function guardarkey($id, $publickey, $privatekey)
    {
        $query = "UPDATE Usuarios SET llave_privada='$privatekey',llave_publica='$publickey'
WHERE id_Usuario = $id";
        $this->db()->query($query);
        $mensaje = 5;
        return $mensaje;

    }

    /*--- USUARIOS: ACTUALIZAR NIP DE USUARIO ---*/
    public function guardarNip($id, $nip)
    {
        $query = "UPDATE Usuarios SET nip_Usuario='$nip' WHERE id_Usuario = $id";
        $this->db()->query($query);
        $mensaje = 6;
        return $mensaje;
    }

    public function restaurarUsuario($id_Usuario, $status)
    {
        $query = "UPDATE Usuarios SET id_Status_Usuario = $status WHERE id_Usuario = $id_Usuario";
        $this->db()->query($query);
        $mensaje = 1;
        return $mensaje;

    }

}

?>
