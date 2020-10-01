<?php

class UsuarioProyecto extends EntidadBase
{

    private $id_Usuario_Proyecto;
    private $id_Usuario;
    private $id_Proyecto;
    private $id_Perfil_Usuario;

    public function __construct($adapter)
    {
        $table = "Usuarios_Proyectos";
        parent::__construct($table, $adapter);
    }


    //ID USUARIO PROYECTO
    public function get_id_Usuario_Proyecto()
    {
        return $this->id_Usuario_Proyecto;
    }

    public function set_id_Usuario_Proyecto($id_Usuario_Proyecto)
    {
        $this->id_Usuario_Proyecto = $id_Usuario_Proyecto;
    }


    //ID USUARIO
    public function get_id_Usuario()
    {
        return $this->id_Usuario;
    }

    public function set_id_Usuario($id_Usuario)
    {
        $this->id_Usuario = $id_Usuario;
    }


    //ID PROYECTO
    public function get_id_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function set_id_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
    }


    //$id_Perfil_Usuario
    public function get_id_Perfil_Usuario()
    {
        return $this->id_Perfil_Usuario;
    }

    public function set_id_Perfil_Usuario($id_Perfil_Usuario)
    {
        $this->id_Perfil_Usuario = $id_Perfil_Usuario;
    }


    /************************************** USUARIOS-PROYECTOS: REGISTRO NUEVO ****************************************/
    public function saveNewUsuarioProyecto($allusuariosproyectos)
    {
        $validate = true;
        if (is_array($allusuariosproyectos) || is_object($allusuariosproyectos)) {
            foreach ($allusuariosproyectos as $usuarioproyecto) {
                if ($usuarioproyecto->id_Usuario == $this->id_Usuario && $usuarioproyecto->id_Proyecto == $this->id_Proyecto) {
                    $validate = false;
                }
            }
        }

        if ($validate) {
            $query = " CALL sp_Add_Up_Usuarios_Proyectos(NULL,$this->id_Usuario,$this->id_Proyecto, $this->id_Perfil_Usuario,'Insertar')";
            if ($this->db()->query($query)) {
                return 1;
            }
        } else {
            return 2;
        }
    }


    /***********************************USUARIOS-PROYECTOS: ACTUALIZAR REGISTRO ***************************************/
    public function modificarUsuarioProyecto($allusuariosproyectos, $id)
    {
        $validate = true;

        foreach ($allusuariosproyectos as $usuarioproyecto) {
            if (
                ($usuarioproyecto->id_Usuario == $this->id_Usuario) &&
                ($usuarioproyecto->id_Proyecto == $this->id_Proyecto) &&
                ($usuarioproyecto->id_Perfil_Usuario == $this->id_Perfil_Usuario) &&
                ($usuarioproyecto->idUsuarios_Proyectos != $id)
            ) {
                $validate = false;
            }
        }


        if ($validate) {
            $query = "CALL sp_Add_Up_Usuarios_Proyectos($id,$this->id_Usuario,$this->id_Proyecto, $this->id_Perfil_Usuario,'Modificar')";
            if ($this->db()->query($query)) {
                return 3;
            }
        } else {
            return 2;
        }
    }

    public function modificarStatusByIdUsuario($id_Usuario)
    {
        $query = "UPDATE Usuarios_Proyectos SET id_Status = 0 WHERE id_Usuario = $id_Usuario";
        $this->db()->query($query);
    }
}

?>
