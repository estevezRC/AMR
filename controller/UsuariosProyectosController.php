<?php

class UsuariosProyectosController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    /*------------------------------------ VISTA DE TODOS LOS USUARIOS-PROYECTOS ----------------------------------------*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        // OBTENER TODOS LOS USUARIOS ASIGNADOS A PROYECTOS
        $usuarioproyecto = new UsuarioProyecto($this->adapter);

        // OBTENER DATOS QUE SIRVEN PARA EL MODAL DE AGREGAR NUEVO USUARIO-PROYECTOS
        // OBTENER TODOS LOS USUARIOS
        $usuario = new Usuario($this->adapter);
        $allusuarios = $usuario->getAllUser();

        $mensaje = $_GET['mensaje'];

        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-random' aria-hidden='true'></i> Asignación de Usuarios a Proyectos";
        }

        // PERFILES DE LA EMPRESA
        $perfil = new Perfil($this->adapter);
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyectoSuperAdmin();
            $noId_Perfil_User = '';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyecto();

            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        $this->view("index", array(
            "allusuariosproyectos" => $allusuariosproyectos, "allusuarios" => $allusuarios, "mensaje" => $mensaje,
            "allPerfiles" => $allPerfiles, "insercion" => $insercion, "newElemento" => $newElemento
        ));
    }

    /*----------------------------------------- VISTA MODIFICAR USUARIO-PROYECTO -------------------------------------------------*/
    public function modificar()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if ($insercion != 0 && !empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $id = (int)$_GET["usuarioproyectoid"];

        // OBTENER TODOS LOS USUARIOS Y OBTENER DATOS POR ID_USUARIO
        $usuarioproyecto = new UsuarioProyecto($this->adapter);
        $datosusuarioproyecto = $usuarioproyecto->getUsuarioProyectoById($id);

        // OBTENER TODOS LOS PROYECTOS
        $proyecto = new Proyecto($this->adapter);
        $allproyectos = $proyecto->getAllProyecto();

        // OBTENER TODOS LOS USUARIOS
        $usuario = new Usuario($this->adapter);
        $allusers = $usuario->getAllUser();

        // PERFILES DE LA EMPRESA
        $perfil = new Perfil($this->adapter);
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyectoSuperAdmin();
            $noId_Perfil_User = '';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyecto();

            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        $mensaje = "<i class='fa fa-random' aria-hidden='true'></i> Asignación de Usuarios a Proyectos";

        $modificar = 1;

        $this->view("index", array(
            "allusuariosproyectos" => $allusuariosproyectos, "allusuarios" => $allusers, "modificar" => $modificar,
            "datosusuarioproyecto" => $datosusuarioproyecto, "allproyectos" => $allproyectos, "allPerfiles" => $allPerfiles,
            "insercion" => $insercion, "newElemento" => $newElemento, "mensaje" => $mensaje
        ));
    }

    public function getProyectosAndPerfiles()
    {
        $id_Usuario = $_POST['id_UsuarioProPer'];

        // OBTENER TODOS LOS PROYECTOS EN LOS QUE NO ESTA EL USUARIO
        $proyecto = new Proyecto($this->adapter);
        $allproyectos = $proyecto->getAllProyectosByUser($id_Usuario);

        if ($allproyectos == '' || empty($allproyectos)) {
            $allproyectosLibres = $proyecto->getAllProyecto();
        } else {
            $idsProyectos = array();
            foreach ($allproyectos as $pro) {
                $idsProyectos[] = $pro->id_Proyecto;
            }
            $idsProyectosStr = implode(",", $idsProyectos);

            $allproyectosLibres = $proyecto->getAllProyectosLibres($idsProyectosStr);
        }

        echo json_encode($allproyectosLibres);

    }

    /*------------------------------------------ METODO CREAR USUARIO-PROYECTO ----------------------------------------*/
    public function guardarnuevo()
    {
        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["id_Usuario"])) || (empty($_POST["id_Proyecto"]))) {
            $save = "Llenar todos los campos";
        } //SE CREA NUEVO PROYECTO
        else {
            // Obtener registros dependiendo del perfil admon
            $usuarioproyecto = new UsuarioProyecto($this->adapter);
            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyectoSuperAdmin();
            } else {
                $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyecto();
            }

            $idUsuario = $_POST["id_Usuario"];
            $idProyecto = $_POST["id_Proyecto"];
            $usuarioproyecto->set_id_Usuario($idUsuario);
            $usuarioproyecto->set_id_Proyecto($idProyecto);
            $usuarioproyecto->set_id_Perfil_Usuario($_POST["id_Perfil"]);
            $save = $usuarioproyecto->saveNewUsuarioProyecto($allusuariosproyectos);

            //Obtener Datos del Registro
            $datosRegistro = $usuarioproyecto->getAllUsuarioProyectoByIdUsuarioAndIdProyecto($idUsuario, $idProyecto);
            $nombreUser = $datosRegistro[0]->nombre_Usuario . ' ' . $datosRegistro[0]->apellido_Usuario;
            $nombreProyecto = $datosRegistro[0]->nombre_Proyecto;
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 1) {
                $insercion = 1;
                $mensaje = 'Se ha agregado el usuario: "' . $nombreUser . '" en el proyecto "' . $nombreProyecto . '"';
            } else {
                $insercion = 2;
                $mensaje = 'El usuario "' . $nombreUser . '" ya esta en el proyecto "' . $nombreProyecto . '"';
            }
        }
        $this->redirect("UsuariosProyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*-------------------------------------- METODO GUARDAR MODIFICACION USUARIO-PROYECTO --------------------------------*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["id_Usuario"])) || (empty($_POST["id_Proyecto"]))) {
            $save = "Llenar todos los campos";
        } //SE GUARDA MODIFICACION
        else {
            // Obtener registros dependiendo del perfil admon
            $usuarioproyecto = new UsuarioProyecto($this->adapter);
            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyectoSuperAdmin();
            } else {
                $allusuariosproyectos = $usuarioproyecto->getAllUsuarioProyecto();
            }

            $idUsuario = $_POST["id_Usuario"];
            $idProyecto = $_POST["id_Proyecto"];
            $usuarioproyecto->set_id_Usuario($idUsuario);
            $usuarioproyecto->set_id_Proyecto($idProyecto);
            $usuarioproyecto->set_id_Perfil_Usuario($_POST["id_Perfil"]);
            $id_usuario_proyecto = $_POST["id_usuario_proyecto"];


            //Obtener Datos del Registro
            $datosRegistro = $usuarioproyecto->getUsuarioProyectoById($id_usuario_proyecto);
            $nombreUser = $datosRegistro[0]->nombre_Usuario . ' ' . $datosRegistro[0]->apellido_Usuario;
            $nombreProyecto = $datosRegistro[0]->nombre_Proyecto;

            $save = $usuarioproyecto->modificarUsuarioProyecto($allusuariosproyectos, $id_usuario_proyecto);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 3) {
                $insercion = 3;
                $mensaje = 'Se ha modificado el usuario: "' . $nombreUser . '" en el proyecto "' . $nombreProyecto . '"';
            } else {
                $insercion = 2;
                $mensaje = 'El usuario "' . $nombreUser . '" ya esta en el proyecto "' . $nombreProyecto . '"';
            }
        }
        $this->redirect("UsuariosProyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*------------------------------------------ METODO BORRAR USUARIO-PROYECTO ------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["usuarioproyectoid"])) {
            $id = (int)$_GET["usuarioproyectoid"];
            $usuarioproyecto = new UsuarioProyecto($this->adapter);

            //Obtener Datos del Registro
            $datosRegistro = $usuarioproyecto->getUsuarioProyectoById($id);
            $nombreUser = $datosRegistro[0]->nombre_Usuario . ' ' . $datosRegistro[0]->apellido_Usuario;
            $nombreProyecto = $datosRegistro[0]->nombre_Proyecto;

            $usuarioproyecto->deleteElementoById($id, 'Usuarios_Proyectos');

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se elimino el usuario "' . $nombreUser . '" del proyecto "' . $nombreProyecto . '"';
        }
        $this->redirect("UsuariosProyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }
}

?>
