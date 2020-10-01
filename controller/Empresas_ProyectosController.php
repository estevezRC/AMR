<?php
class Empresas_ProyectosController extends ControladorBase
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
    /*--- VISTA DE TODOS LOS PROYECTOS ---*/
    public function index()
    {
        $mensaje = $_GET['mensaje'];
        if(empty($mensaje)){$mensaje = "<i class='fa fa-link' aria-hidden='true'></i> Relación Empresas Proyectos";}
        $empresaproyecto = new Empresas_Proyectos($this->adapter);
        $allempresasproyectos = $empresaproyecto->getAllEmpresasProyectos();
        $allproyectos = $empresaproyecto->getAllProyecto();
        $allempresas = $empresaproyecto->getAllEmpresas();
        $this->view("index", array(
            "allempresasproyectos" => $allempresasproyectos, "allproyectos" => $allproyectos,"allempresas" => $allempresas,
            "mensaje" => $mensaje
        ));
    }
    /*--- VISTA MODIFICAR PROYECTO ---*/
    public function modificar()
    {
        $mensaje = "<i class='fa fa-link' aria-hidden='true'></i> Relación Empresas Proyectos";
        if (isset($_GET["id_Empresas_Proyectos"])) {
            $empresaproyecto = new Empresas_Proyectos($this->adapter);
            $allproyectos = $empresaproyecto->getAllProyecto();
            $allempresas = $empresaproyecto->getAllEmpresas();
            $allempresasproyectos = $empresaproyecto->getAllEmpresasProyectos();
            $id = (int)$_GET["id_Empresas_Proyectos"];
            $datosempresaproyecto = $empresaproyecto->getEmpresaProyectoById($id);
            $modificar = 1;
        }
        $this->view("index", array(
            "allproyectos" => $allproyectos,"allempresas" => $allempresas,"datosempresaproyecto" => $datosempresaproyecto,
            "modificar" => $modificar,"allempresasproyectos" => $allempresasproyectos,"mensaje" => $mensaje
        ));
    }

    /*--- METODO CREAR NUEVO PROYECTO---*/
    public function guardarnuevo()
    {
        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["id_Empresa"]))||(empty($_POST["id_Proyecto"]))) {
            $save = "Llenar todos los campos";
        }
        //SE CREA NUEVA RELACION EMPRESA PROYECTO
        else {
            $empresaproyecto = new Empresas_Proyectos($this->adapter);
            $empresasproyectos = $empresaproyecto->getAllEmpresasProyectos();
            $empresaproyecto->set_id_Proyecto($_POST["id_Proyecto"]);
            $empresaproyecto->set_id_Empresa($_POST["id_Empresa"]);
            $save = $empresaproyecto->saveNewEmpresaProyecto($empresasproyectos );
            $mensaje = "<i class='fa fa-link' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Empresas_Proyectos", "index&mensaje=$mensaje");
    }
    /*--- METODO GUARDAR MODIFICACION AREA ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["id_Empresa"]))||(empty($_POST["id_Proyecto"]))) {
            $save = "Llenar todos los campos";
        }
        //SE GUARDA MODIFICACION
        else {
            $empresaproyecto = new Empresas_Proyectos($this->adapter);
            $empresasproyectos = $empresaproyecto->getAllEmpresasProyectos();
            $id = $_POST["id_Empresas_Proyectos"];
            $empresaproyecto->set_id_Proyecto($_POST["id_Proyecto"]);
            $empresaproyecto->set_id_Empresa($_POST["id_Empresa"]);
            $save = $empresaproyecto->modificarEmpresaProyecto($id,$empresasproyectos);
            $mensaje = "<i class='fa fa-link' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Empresas_Proyectos", "index&mensaje=$mensaje");
    }
    /*--- METODO BORRAR EMPRESA-PROYECTO ---*/
    public function borrar()
    {
        if (isset($_GET["id_Empresas_Proyectos"])) {
            $id = (int)$_GET["id_Empresas_Proyectos"];
            $empresaproyecto = new Empresas_Proyectos($this->adapter);
            $empresaproyecto->deleteElementoById($id,'Empresas_Proyectos');
            $save = "Se elmino la relación empresa proyecto";
            $mensaje = "<i class='fa fa-link' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Empresas_Proyectos", "index&mensaje=$mensaje");
    }
}
?>
