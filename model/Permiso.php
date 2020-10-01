<?php

class Permiso extends EntidadBase
{
    private $permisoid;
    private $puestoid;
    private $recursoid;
    private $permisoconsultar;
    private $permisoagregar;
    private $permisoeditar;
    private $permisoeliminar;
    private $permisoborrado;

    public function __construct($adapter)
    {
        $table = "Permisos_Usuarios";
        parent::__construct($table, $adapter);
    }

    //ID
    public function getId()
    {
        return $this->permisoid;
    }

    public function setId($permisoid)
    {
        $this->permisoid = $permisoid;
    }

    //PUESTO ID
    public function getPuestoId()
    {
        return $this->puestoid;
    }

    public function setPuestoId($puestoid)
    {
        $this->puestoid = $puestoid;
    }

    //RECURSO ID
    public function getRecursoId()
    {
        return $this->recursoid;
    }

    public function setRecursoId($recursoid)
    {
        $this->recursoid = $recursoid;
    }

    //PERMISO CONSULTAR
    public function getPermisoConsultar()
    {
        return $this->permisoconsultar;
    }

    public function setPermisoConsultar($permisoconsultar)
    {
        $this->permisoconsultar = $permisoconsultar;
    }

    //PERMISO BORRADO
    public function getPermisoBorrado()
    {
        return $this->permisoborrado;
    }

    public function setPermisoBorrado($permisoborrado)
    {
        $this->permisborrado = $permisoborrado;
    }

    /*--- PERMISOS REGISTRAR PERMISO ---*/
    public function saveNewPermiso($permisos)
    {
        $row_cnt = 1;
        $nombres = "";
        foreach ($permisos as $permiso) {
            //areaid
            if (($permiso->id_Puesto_Usuario == $this->puestoid) && ($permiso->recursoid == $this->recursoid)) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El permiso ya existe";
            return $mensaje;
        } else {
            $query = "CALL SP_crearPermiso($this->puestoid,$this->recursoid,$this->permisoconsultar,$this->permisoagregar,$this->permisoeditar,$this->permisoeliminar)";
            $save = $this->db()->query($query);
            $mensaje = "Se ha creado el permiso";
            //$this->db()->error;
            return $mensaje;
        }
    }

    // Guardar todos los Permisos para los Usuarios
    public function saveRegistrosPermisosUsuario($valorMaxPerfil, $idRecurso, $proyecto)
    {
        $query = "insert into Permisos_Usuarios values (NULL, $valorMaxPerfil, $idRecurso, 0, 1, $proyecto)";
        $this->db()->query($query);
    }

    /*--- PERMISOS: ACTUALIZAR PERMISO POR ID ---*/
    public function modificarPermiso($id)
    {
        $query = "CALL SP_modificarPermiso($this->permisoconsultar,$id)";
        $save = $this->db()->query($query);
        //return $id." ".$this->permisoconsultar."<br/>";
    }
}

?>