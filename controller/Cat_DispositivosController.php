<?php
class Cat_DispositivosController extends ControladorBase
{
	public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }
    /*--------------------------------------- VISTA DE TODAS LOS DISPOSITIVOS ----------------------------------------*/
    public function index()
    {
        $mensaje = $_GET['mensaje'];
        if(empty($mensaje)){$mensaje = "<i class='fa fa-mobile' aria-hidden='true'></i> Dispositivos móvil";}
        $dispositivos = new Cat_Dispositivos($this->adapter);
        $alldispositivos = $dispositivos->getAllDispositivos($this->id_Proyecto_constant);
        $allusers = $dispositivos->getAllUser($this->id_Proyecto_constant);
        $this->view("index", array(
            "alldispositivos" => $alldispositivos, "allusers" => $allusers, "mensaje" => $mensaje
        ));
    }

    /*------------------------------------------ VISTA MODIFICAR DISPOSITIVO -----------------------------------------*/
    public function modificar()
    {
        if (isset($_GET["Id_Dispositivo"])) {
            $Id_Dispositivo = $_GET["Id_Dispositivo"];
            $dispositivos = new Cat_Dispositivos($this->adapter);
            $alldispositivos = $dispositivos->getAllDispositivos($this->id_Proyecto_constant);
            $datosdispositivo = $dispositivos->getDispositivosById($Id_Dispositivo);
            $allusers = $dispositivos->getAllUser($this->id_Proyecto_constant);
            $modificar = 1;
        }
        $this->view("index", array(
            "alldispositivos" => $alldispositivos, "datosdispositivo" => $datosdispositivo, "allusers" => $allusers,
            "modificar" => $modificar
        ));
    }
    /*------------------------------------------- METODO CREAR NUEVO DISPOSITIVO -------------------------------------*/
    public function guardarnuevo()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Marca"]) || empty($_POST["id_Usuario"]) || empty($_POST["NumeroSerie"])) {
            $save = "Llenar todos los campos";
            $mensaje = "<i class='fa fa-mobile' aria-hidden='true'></i> ".$save."";
        }
        //SE CREA NUEVO DISPOSITIVO
        else {
            $dispositivos = new Cat_Dispositivos($this->adapter);
            $alldispositivos = $dispositivos->getAllDispositivos($this->id_Proyecto_constant);

            $dispositivos->set_Marca($_POST["Marca"]);
            $dispositivos->set_id_Usuario($_POST["id_Usuario"]);
            $dispositivos->set_NumeroSerie($_POST["NumeroSerie"]);
			$dispositivos->set_Id_Proyecto($this->id_Proyecto_constant);
            $save = $dispositivos->saveNew($alldispositivos);
            $mensaje = "<i class='fa fa-mobile' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Cat_Dispositivos", "index&mensaje=$mensaje");
    }
    /*------------------------------------ METODO GUARDAR MODIFICACION TAREA ----------------------------------------*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Marca"]) || empty($_POST["id_Usuario"]) || empty($_POST["NumeroSerie"])) {
            $mensaje = "Llenar todos los campos";
        }
        //SE GUARDA MODIFICACION
        else {
            $id = $_POST["Id_Dispositivo"];
            $dispositivos = new Cat_Dispositivos($this->adapter);
            $alldispositivos = $dispositivos->getAllDispositivos($this->id_Proyecto_constant);
            $dispositivos->set_Marca($_POST["Marca"]);
            $dispositivos->set_id_Usuario($_POST["id_Usuario"]);
            $dispositivos->set_NumeroSerie($_POST["NumeroSerie"]);
            $dispositivos->set_Id_Dispositivo($_POST["Id_Dispositivo"]);
            $save = $dispositivos->modificar($id,$alldispositivos);
			$mensaje = "<i class='fa fa-mobile' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Cat_Dispositivos", "index&mensaje=$mensaje");
    }
    /*---------------------------------------- METODO BORRAR CONFIGURACION -------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["Id_Dispositivo"])) {
            $Id_Dispositivo = $_GET["Id_Dispositivo"];
            $dispositivos = new Cat_Dispositivos($this->adapter);
            $dispositivos->deleteElementoById($Id_Dispositivo,'Dispositivos');
            $save = "Se elmino el dispositivo con ID " . $Id_Dispositivo . "";
            $mensaje = "<i class='fa fa-mobile' aria-hidden='true'></i> ".$save."";
        }
        $this->redirect("Cat_Dispositivos", "index&mensaje=$mensaje");
    }}
?>
