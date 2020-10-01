<?php
class Cat_ModulosController extends ControladorBase
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
    /*---------------------------------------- VISTA DE TODOS LOD MODULOS --------------------------------------------*/
    public function index()
    {
        $modulo = new Cat_Modulos($this->adapter);
        $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
        $this->view("index", array(
            "allmodulos" => $allmodulos
        ));
    }
    /*----------------------------------- VISTA DE LOS CAMPOS DE UNA CONFIGURACION DE REPORTE ------------------------*/
    public function getCamposByReporte()
    {
        if ($_POST["Id_Reporte"]!="todos") {
        if (empty($_POST["Id_Reporte"])) {
            $id_Reporte = $_GET["Id_Reporte"];
        } else if (empty($_GET["Id_Reporte"])) {
            $id_Reporte = $_POST["Id_Reporte"];
        }
        $mensaje = $_GET["mensaje"];
        //$id_Reporte = $_POST["Id_Reporte"];
        $modulo = new Modulo($this->adapter);
        $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
        $allcamposreporte = $modulo->getAllCamposReporte($this->id_Proyecto_constant, $id_Reporte);
        $campo = new Campo($this->adapter);
        $allcampos = $campo->getAllCampo($this->id_Proyecto_constant);
        $reporte = new Reporte($this->adapter);
        $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
        $this->view("index", array(
            "allcamposreporte2" => $allmodulos, "allcamposreporte" => $allcamposreporte, "allcampos" => $allcampos,
            "allreportes" => $allreportes, "mensaje" => $mensaje
        ));
    }else{
            $this->redirect("CamposReporte", "index");
        }
    }

    /*------------------------------------------ VISTA MODIFICAR CONFIGURACION ---------------------------------------*/
    public function modificar()
    {
        if (isset($_GET["Id_Modulo"])) {
            $Id_Modulo = $_GET["Id_Modulo"];
            $modulo = new Cat_Modulos($this->adapter);
            $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
            $datosmodulo = $modulo->getModuloById($Id_Modulo);
            $modificar = 1;
        }
        $this->view("index", array(
            "allmodulos" => $allmodulos, "modificar" => $modificar, "datosmodulo" => $datosmodulo
        ));
    }
    /*------------------------------------------ METODO CREAR NUEVO MÓDULO -------------------------------------------*/
    public function guardarnuevo()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Nombre"])) {
            $save = "Llenar todos los campos";
        }
        //SE CREA NUEVO MODULO
        else {
            if(!empty($_POST["Id_Modulo"])) {
                $id_Modulo = $_POST["Id_Modulo"];
            }else{
                $id_Modulo = $_POST["id_Modulo"];
            }
            $modulo = new Cat_Modulos($this->adapter);
            $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
            $modulo->set_Nombre($_POST["Nombre"]);
            $save = $modulo->saveNew($allmodulos);
            $guardarnuevo = 1;
        }

        $this->redirect("Cat_Modulos", "index&mensaje=$save&Id_Modulo=$id_Modulo");
    }
    /*------------------------------------ METODO GUARDAR MODIFICACION MODULO ----------------------------------------*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Nombre"])) {
            $save = "Llenar todos los campos";
        }
        //SE GUARDA MODIFICACION
        else {
            $Id_Modulo = $_POST["Id_Modulo"];
            $modulo = new Cat_Modulos($this->adapter);
            $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
            $modulo->set_Nombre($_POST["Nombre"]);
            $save = $modulo->modificar($Id_Modulo,$allmodulos);
        }
        $this->redirect("Cat_Modulos", "index&mensaje=$save&Id_Reporte=$id_Reporte");
    }
    /*---------------------------------------- METODO BORRAR CONFIGURACION -------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["Id_Modulo"])) {
            $Id_Modulo = $_GET["Id_Modulo"];
            $modulo = new Cat_Modulos($this->adapter);
            $modulo->deleteElementoById($Id_Modulo,'Cat_Modulos');
            $save = "Se elmino el módulo con id " . $Id_Modulo . "";
        }
        $this->redirect("Cat_Modulos", "index&mensaje=$save");
    }}
?>
