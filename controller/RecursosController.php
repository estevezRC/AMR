<?php
class RecursosController extends ControladorBase
{
	public $conectar;
    public $adapter;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }
    /*--- VISTA DE TODOS LOS RECURSOS ---*/
    public function index()
    {
        $recurso = new Recurso($this->adapter);
        $allrecursos = $recurso->getAllRecurso();
        $this->view("index", array(
            "allrecursos" => $allrecursos
        ));
    }
    /*--- VISTA MODIFICAR RECURSOS ---*/
    public function modificar()
    {
        if (isset($_GET["recursoid"])) {
        	$recurso = new Recurso($this->adapter);
            $id = (int)$_GET["recursoid"];
            $datosrecurso = $recurso->getRecursoById($id);
			$allrecursos = $recurso->getAllRecurso();
			$modificar = 1;
        }
        $this->view("index", array(
            "datosrecurso" => $datosrecurso,"modificar" => $modificar,"allrecursos" => $allrecursos
        ));
    }

    /*--- METODO CREAR NUEVO RECURSO---*/
    public function guardarnuevo()
    {

        //COMPROBAR CAMPOS VACIOS
        if ((empty($_POST["recursonombre"])) || (empty($_POST["recursodescripcion"]))) {
            $save = "Llenar todos los campos";
        }
        //SE CREA NUEVO RECURSO
        else {
            $recurso = new Recurso($this->adapter);
            $allrecursos = $recurso->getAllRecurso();
            $recurso->setNombre($_POST["recursonombre"]);
            $recurso->setDescripcion($_POST["recursodescripcion"]);
            $save = $recurso->saveNewRecurso($allrecursos);
        }
        $this->redirect("Recursos", "crear&mensaje=$save");
    }
    /*--- METODO GUARDAR MODIFICACION RECURSO ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["recursonombre"]))
        {
            $save = "Llenar todos los campos";
        }
        //SE GUARDA MODIFICACION
        else {
            $recurso = new Recurso($this->adapter);
            $allrecursos = $recurso->getAllRecurso();
            $id = $_POST["recursoid"];
            $recurso->setNombre($_POST["recursonombre"]);
            $recurso->SetDescripcion($_POST["recursodescripcion"]);
            $save = $recurso->modificarRecurso($id,$allrecursos);
        }
        $this->redirect("Recursos", "index&mensaje=$save");
    }
    /*--- METODO BORRAR USUARIO ---*/
    public function borrar()
    {
        if (isset($_GET["recursoid"])) {
            $id = (int)$_GET["recursoid"];
            $recurso = new Recurso($this->adapter);
            $recurso->deleteElementoById($id,"Recursos_Sistema");
            $save = "Se elmino el recurso con id " . $id . "";
        }
        $this->redirect("Recursos", "index&mensaje=$save");
    }


    /*--- METODO REACTIVAR RECURSO ---*/
    public function activarRecurso()
    {
        if (isset($_GET["recursoid"])) {
            $id = (int)$_GET["recursoid"];
            $recurso = new Recurso($this->adapter);
            $recurso->reactivarRecursoById($id);
            $save = "Se reactivo el recruso con id " . $id . "";
        }
        $this->redirect("Recursos", "index&mensaje=$save");
    }
}
?>
