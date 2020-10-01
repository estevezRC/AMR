<?php
class Cat_StatusController extends ControladorBase
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
    /*------------------------------------------- VISTA DE TODOS LOS STAUTS -----------------------------------------*/
    public function index()
    {
        $estatus = new Cat_Status($this->adapter);
        $allestatus = $estatus->getEstatusByModulo(2);
        $modulos = new Cat_Modulos($this->adapter);
        $allmodulos = $modulos->getModuloById(2);

        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = '<i class="fa fa-truck" aria-hidden="true"></i> Estatus de seguimiento';
        }

        $this->view("index", array(
            "allestatus" => $allestatus, "allmodulos" => $allmodulos, "mensaje" => $mensaje
        ));

        //print_r($allmodulos);
    }
    /*--------------------------------------------- VISTA MODIFICAR ESTATUS ------------------------------------------*/
    public function modificar()
    {
        if (isset($_GET["id_Status"])) {
            $estatus = new Cat_Status($this->adapter);
            $allestatus = $estatus->getAllEstatus($this->id_Proyecto_constant);
            $modulo = new Cat_Modulos($this->adapter);
            $allmodulos = $modulo->getAllModulos($this->id_Proyecto_constant);
            $id = (int)$_GET["id_Status"];
            $datosestatus = $estatus->getEstatusById($id);
            $modificar = 1;
        }
        $this->view("index", array(
            "allestatus" => $allestatus, "datosestatus" => $datosestatus, "allmodulos" => $allmodulos,
            "modificar" => $modificar
        ));
    }
        /*------------------------------------------ METODO CREAR NUEVO ESTATUS --------------------------------------*/
    public function guardarnuevo()
    {

        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Status"]) || empty($_POST["IdModulo"]) || empty($_POST["Secuencia"])) {
            $save = "Llenar todos los campos";
        }
        //SE CREA NUEVO ESTATUS
        else {
            //CARGAR IMAGEN
            $nombre_img = $_FILES['Imagen']['name'];
            $tipo_img = $_FILES['Imagen']['type'];
            $extension = explode(".", $nombre_img);
            $nombre_imagen = $_POST["Status"].".".$extension[1];
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
            $target_path = "img/estatus/";
            $target_path = $target_path . basename( $nombre_imagen );
            if(move_uploaded_file($_FILES['Imagen']['tmp_name'], $target_path)) {
                /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
            } else{
                /*echo "Erro al cargar imagen!";*/
            }
            //$img =  "img/estatus/".$nombre_imagen;
            $img = NULL;
            //VARAIBLES AL OBJETO
            $estatus = new Cat_Status($this->adapter);
            $allestatus = $estatus->getAllEstatus();
            $estatus->set_Status($_POST["Status"]);
            $estatus->set_IdModulo($_POST["IdModulo"]);
            $estatus->set_Secuencia($_POST["Secuencia"]);
            $estatus->set_Imagen($img);
            $save = $estatus->saveNew($allestatus);
        }
        $this->redirect("Cat_Status", "index&mensaje=$save");
    }
    /*----------------------------------------------- METODO MODIFICAR ESTATUS ---------------------------------------*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["Status"]) || empty($_POST["IdModulo"]) || empty($_POST["Secuencia"])) {
            $save = "Llenar todos los campos";
        }
        //SE GUARDA MODIFICACION
        else {

            if($_FILES['Imagen']['tmp_name']==""){
                $img = $_POST["Imagen2"];
                //$img = "el anterior";
            }
            //if((!empty($_FILES['uploadedfile']))&&(!empty($_POST['uploadedfile']))){
            if($_FILES['Imagen']['tmp_name']!=""){
                //CARGAR IMAGEN
                $nombre_img = $_FILES['Imagen']['name'];
                $tipo_img = $_FILES['Imagen']['type'];
                $extension = explode(".", $nombre_img);
                $nombre_imagen = $_POST["Status"].".".$extension[1];
                $nombre_imagen = str_replace(' ', '', $nombre_imagen);
                $target_path = "img/estatus/";
                $target_path = $target_path . basename( $nombre_imagen );
                if(move_uploaded_file($_FILES['Imagen']['tmp_name'], $target_path)) {
                    /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
                } else{
                    //echo "Erro al cargar imagen!";
                }
                $img =  "img/estatus/".$nombre_imagen;
            }

            $estatus = new Cat_Status($this->adapter);
            $allestatus = $estatus->getAllEstatus($this->id_Proyecto_constant);
            $id = $_POST["id_Status"];
            $estatus->set_Status($_POST["Status"]);
            $estatus->set_IdModulo($_POST["IdModulo"]);
            $estatus->set_Secuencia($_POST["Secuencia"]);
            $estatus->set_Imagen($img);
            $save = $estatus->modificar($id,$allestatus);
        }
        $this->redirect("Cat_Status", "index&mensaje=$save");
    }
    /*---------------------------------------- METODO BORRAR ESTATUS -------------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["id_Status"])) {
            $id = (int)$_GET["id_Status"];
            $estatus = new Cat_Status($this->adapter);
            $estatus->deleteElementoById($id,'Cat_Status');
            $save = "Se elmino el campo con id " . $id . "";
        }
        $this->redirect("Cat_Status", "index&mensaje=$save");
    }
}
?>
