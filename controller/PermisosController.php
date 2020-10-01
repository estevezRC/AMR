<?php

class PermisosController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    /*--- VISTA DE TODOS LOS PERMISOS ---*/
    public function index()
    {
        $id_Perfil_Usuario = $_GET['id_Perfil_Usuario'];
        $permiso = new Permiso($this->adapter);

        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $allpermisos = $permiso->getAllPermiso($id_Perfil_Usuario);
        } else {
            $allpermisos = $permiso->getAllPermisoNoSuperAdmin($id_Perfil_Usuario, 9);
        }
        $mensaje = $allpermisos[0]->nombre_Perfil;

        $this->view("index", array(
            "allpermisos" => $allpermisos, "mensaje" => $mensaje
        ));
    }

    /*--- VISTA MODIFICAR PUESTO ---*/
    public function modificar()
    {
        if (isset($_GET["permisoid"])) {
            $puesto = new Puesto($this->adapter);
            $allpuestos = $puesto->getAllperfiles($this->id_Proyecto_constant);
            $recurso = new Recurso($this->adapter);
            $allrecursos = $recurso->getAllRecurso();
            $valor = new Valor($this->adapter);
            //$allvalores = $valor->getAllValor();
            $permiso = new Permiso($this->adapter);
            $allpermisos = $permiso->getAllPermiso($this->id_Proyecto_constant);
            $id = (int)$_GET["permisoid"];
            $datospermiso = $permiso->getPermisoById($id);
            $modificar = 1;
        }
        $this->view("index", array(
            "allpermisos" => $allpermisos, "datospermiso" => $datospermiso, "allpuestos" => $allpuestos,
            "allrecursos" => $allrecursos, "modificar" => $modificar
        ));
    }

    /*--- METODO CREAR NUEVO PERMISO---*/
    public function guardarnuevo()
    {

        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["permisopuesto"]) || empty($_POST["permisorecurso"])/* || empty($_POST["permisoconsultar"]) || empty($_POST["permisoagregar"]) || empty($_POST["permisoeditar"]) || empty($_POST["permisoeliminar"])*/)) {
            $save = "Llenar todos los campos";
        } //SE CREA NUEVO PUESTO
        else {
            $permiso = new Permiso($this->adapter);
            $allpermisos = $permiso->getAllPermiso($this->id_Proyecto_constant);
            $permiso->setPuestoId($_POST["permisopuesto"]);
            $permiso->setRecursoId($_POST["permisorecurso"]);
            $permiso->setPermisoConsultar($_POST["permisoconsultar"]);
            $permiso->setPermisoAgregar(0);
            $permiso->setPermisoEditar(0);
            $permiso->setPermisoEliminar(0);
            $save = $permiso->saveNewPermiso($allpermisos);
            //echo $save;
        }
        $this->redirect("Permisos", "crear&mensaje=$save");
    }

    /*--- METODO GUARDAR MODIFICACION PERMISO ---*/
    public function guardarmodificacionpermiso()
    {
        $id_Perfil_Usuario = $_POST['id_Perfil_Usuario'];
        $permiso = new Permiso($this->adapter);
        $allpermisos = $permiso->getAllPermiso($id_Perfil_Usuario);

        foreach ($allpermisos as $per) {
            $permiso = new Permiso($this->adapter);
            $id = $per->id_Permiso;
            $permiso->setPermisoConsultar($_POST[$per->id_Permiso]);
            //echo $id." ".$_POST[$per->id_Permiso]."<br/>";
            $save = $permiso->modificarPermiso($id);
            //echo $save;
        }

        $this->redirect("Permisos", "index&mensaje=$save&id_Perfil_Usuario=$id_Perfil_Usuario ");
    }

    /*--- METODO BORRAR PERMISO ---*/
    public function borrar()
    {
        if (isset($_GET["permisoid"])) {
            $id = (int)$_GET["permisoid"];
            $permiso = new Permiso($this->adapter);
            $permiso->deletePermisoById($id);
            $save = "Se elmino el permiso con id " . $id . "";
        }
        $this->redirect("Permisos", "index&mensaje=$save");
    }


    /*--- METODO REACTIVAR USUARIO ---*/
    public function activarPermiso()
    {
        if (isset($_GET["permisoid"])) {
            $id = (int)$_GET["permisoid"];
            $permiso = new Permiso($this->adapter);
            $permiso->reactivarPermisoById($id);
            $save = "Se reactivo el permiso con id " . $id . "";
        }
        $this->redirect("Permisos", "index&mensaje=$save");
    }
}

?>
