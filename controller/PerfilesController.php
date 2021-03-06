<?php

class PerfilesController extends ControladorBase
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

    /*--- VISTA DE TODOS LOS perfiles ---*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }


        // PERFILES DE LA EMPRESA
        $perfil = new Perfil($this->adapter);
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $noId_Perfil_User = '';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        $empresa = new Empresa($this->adapter);
        $allempresas = $empresa->getAllEmpresas();

        $mensaje = $_GET['mensaje'];

        if (empty($mensaje)) {
            $mensaje = "Perfiles";
        }

        $this->view("index", array(
            "allperfiles" => $allperfiles, "allempresas" => $allempresas, "mensaje" => $mensaje, "insercion" => $insercion,
            "newElemento" => $newElemento
        ));

    }

    /*--- VISTA MODIFICAR perfil ---*/
    public function modificar()
    {
        $perfil = new perfil($this->adapter);
        $id = (int)$_POST["perfilid"];
        $datosperfil = $perfil->getperfilById($id);
        echo json_encode(array(
            'data' => $datosperfil
        ));
    }

    /*--- METODO CREAR NUEVO perfil---*/
    public function guardarnuevo()
    {
        $nombrePerfil = $_POST["nombre_Perfil"];
        $recurso = new Recurso($this->adapter);


        // PERFILES DE LA EMPRESA
        $perfil = new Perfil($this->adapter);
        $sumaValorPerfil = $perfil->getAllCountValoresPerfil();


        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $noId_Perfil_User = '';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        $perfil->setnombre_Perfil($nombrePerfil);
        $perfil->setid_Proyecto($_SESSION[ID_PROYECTO_SUPERVISOR]);
        $perfil->setid_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);

        $save = $perfil->saveNewPerfil($allperfiles);

        $sumaValorPerfilActual = $perfil->getAllCountValoresPerfil();

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado el perfil "' . $nombrePerfil . '"';
        } else {
            $insercion = 2;
            $mensaje = 'El perfil "' . $nombrePerfil . '" ya existe';
        }

        if ($sumaValorPerfil < $sumaValorPerfilActual) {
            $permiso = new Permiso($this->adapter);

            // $valorMaxPerfil es el id mas actual
            $valorMaxPerfil = $perfil->getAllValorMaxPerfil();
            //echo $valorMaxPerfil;

            $allIdRecSistema = $recurso->getAllIdRecSistema();
            foreach ($allIdRecSistema as $idRecurso) {
                $permiso->saveRegistrosPermisosUsuario($valorMaxPerfil, $idRecurso->id_Recurso_Sistema, $this->id_Proyecto_constant);
            }
        }

        $this->redirect("Perfiles", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO GUARDAR MODIFICACION perfil ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["nombre_Perfil"])) {
            $mensaje = "Llenar todos los campos";
            $status = false;
        } //SE GUARDA MODIFICACION
        else {
            $perfil = new perfil($this->adapter);

            $id = $_POST["id_Perfil_Usuario"];

            $perfilActual = $perfil->getPerfilById($id);
            $nombreNuevo = $_POST["nombre_Perfil"];

            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $noId_Perfil_User = '';
                $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            } else {
                $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
                $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            }

            $perfil->setnombre_Perfil($nombreNuevo);
            $perfil->setid_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);
            $save = $perfil->modificarperfil($id, $allperfiles);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save) {
                $status = true;
                $mensaje = 'Se ha modificado el perfil "' . $perfilActual->nombre_Perfil . '"';
            } else {
                $status = false;
                $mensaje = 'El perfil "' . $nombreNuevo . '" ya existe';
            }

        }

        echo json_encode([
            'mensaje' => $mensaje, 'status' => $status
        ]);
    }

    /*--- METODO BORRAR USUARIO ---*/
    public function borrar()
    {
        if (isset($_GET["id_Perfil_Usuario"])) {
            $id = (int)$_GET["id_Perfil_Usuario"];
            $perfilCon = new Perfil($this->adapter);
            $perfil = $perfilCon->getPerfilById($id);
            $perfilCon->deleteElementoById($id, "Perfiles_Usuarios");

            $insercion = 4;
            $mensaje = "Se elimin?? el perfil " . $perfil->nombre_Perfil . "";
        }
        $this->redirect("Perfiles", "index&insercion=$insercion&newElemento=$mensaje");
    }


    /*--- METODO REACTIVAR USUARIO ---*/
    public function activarperfil()
    {
        if (isset($_GET["perfilid"])) {
            $id = (int)$_GET["perfilid"];
            $perfil = new perfil($this->adapter);
            $perfil->reactivarperfilById($id);
            $save = "Se reactivo el perfil con id " . $id . "";
        }
        $this->redirect("perfiles", "index&mensaje=$save");
    }
}

