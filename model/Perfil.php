<?php

class Perfil extends EntidadBase
{
    private $id_Perfil_Usuario;
    private $nombre_Perfil;
    private $fecha_Registro_Perfil;
    private $id_Status_Perfil;
    private $id_Proyecto;
    private $id_Empresa;

    public function __construct($adapter)
    {
        $table = "Perfiles_Usuarios";
        parent::__construct($table, $adapter);
    }

    //ID
    public function getid_Perfil_Usuario()
    {
        return $this->id_Perfil_Usuario;
    }

    public function setid_Perfil_Usuario($id_Perfil_Usuario)
    {
        $this->id_Perfil_Usuario = $id_Perfil_Usuario;
    }

    //NOMBRE
    public function getnombre_Perfil()
    {
        return $this->nombre_Perfil;
    }

    public function setnombre_Perfil($nombre_Perfil)
    {
        $this->nombre_Perfil = $nombre_Perfil;
    }

    //FECHA
    public function getfecha_Registro_Perfil()
    {
        return $this->fecha_Registro_Perfil;
    }

    public function setfecha_Registro_Perfil($fecha_Registro_Perfil)
    {
        $this->fecha_Registro_Perfil = $fecha_Registro_Perfil;
    }

    //STATUS
    public function getid_Status_Perfil()
    {
        return $this->id_Status_Perfil;
    }

    public function setid_Status_Perfil($id_Status_Perfil)
    {
        $this->id_Status_Perfil = $id_Status_Perfil;
    }

    //PROYECTO
    public function getid_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function setid_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
    }

    //EMPRESA
    public function getid_Empresa()
    {
        return $this->id_Empresa;
    }

    public function setid_Empresa($id_Empresa)
    {
        $this->id_Empresa = $id_Empresa;
    }

    /*--- REGISTRAR NUEVO PERFIL ---*/
    public function saveNewPerfil($perfiles)
    {
        $validate = true;
        foreach ($perfiles as $perfil) {
            if (($perfil->nombre_Perfil == $this->nombre_Perfil) && ($perfil->id_Empresa == $this->id_Empresa)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "INSERT INTO Perfiles_Usuarios VALUES(NULL, '$this->nombre_Perfil', NOW(), 1,$this->id_Proyecto, $this->id_Empresa)";
            if ($this->db()->query($query))
                return 1;
            else
                return 2;
        } else
            return 2;
    }

    /*--- USUARIOS: ACTUALIZAR USUARIO POR ID ---*/
    public function modificarPerfil($id, $perfiles)
    {
        $validate = true;
        foreach ($perfiles as $perfil) {
            if (($perfil->nombre_Perfil == $this->nombre_Perfil) && ($perfil->id_Empresa == $this->id_Empresa)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_Perfiles_Usuarios ($id,'$this->nombre_Perfil',NULL,$this->id_Empresa,'Modificar')";
            return $this->db()->query($query);
        } else
            return $validate;
    }
}
