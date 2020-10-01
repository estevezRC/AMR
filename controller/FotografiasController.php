<?php
class FotografiasController extends ControladorBase
{
	public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
	public $id_Usuario;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
		$this->id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
    }
    /*--- VISTA DE TODOS LAS FOTOGRAFIAS ---*/
    public function index()
    {
        $fotografia = new Fotografia($this->adapter);
        $allfotografias = $fotografia->getAllFotografias($this->id_Proyecto_constant);
		//var_dump($allfotografias);
        $this->view("index", array(
            "allfotografias" => $allfotografias
        ));
    }
	
	/*--- VISTA DE TODOS LAS FOTOGRAFIAS ---*/
    public function busqueda()
    {
		$sistema = new Sistema($this->adapter);
        $allsistemas = $sistema->getAllSistemasByAreaEnTramo($_SESSION[ID_AREA_SUPERVISOR]);
        $this->view("index", array(
            "allsistemas" => $allsistemas
        ));
    }
	
	/*--- VISTA DE TODOS LAS FOTOGRAFIAS ---*/
    public function buscar()
    {
		$id_Gpo = $_GET['id_Gpo'];
		if(empty($id_Gpo)){
		$id_Gpo = $_POST['id_Gpo'];
		}
		$fotografia = new Fotografia($this->adapter);
		$clasificacion = $fotografia->getAllClasificacionFotografias();
		//var_dump($clasificacion);
		//CONSULTAR REPORTES
		$invubi = $fotografia->getInventarioUbicacionByIdUbicacion($id_Gpo);
		$id_InvUbi = $invubi[0]->Id_InvUbi;
		$reportes = $fotografia->getReportesByIdInvUbi($id_InvUbi);
		foreach($reportes as $reporte){
		$ids_reporte = $ids_reporte.",".$reporte->Id_Reporte;	
		}
		$ids_reporte = substr($ids_reporte,1);
        $allfotografias = $fotografia->getBusquedaFoto($id_Gpo,$ids_reporte);
        
		$this->view("index", array(
            "allfotografias" => $allfotografias, "id_Gpo" => $id_Gpo, "clasificacion" => $clasificacion
        ));
    }
	
    /*--- VISTA MODIFICAR SISTEMA ---*/
    public function modificar()
    {
        if (isset($_GET["id_Fotografia"])) {
			$id = $_GET['id_Fotografia'];
			$id_Gpo = $_GET['id_Gpo'];
        	$fotografia = new Sistema($this->adapter);
			$clasificacion = $fotografia->getAllClasificacionFotografias();
			$datosfotografia = $fotografia->getFotografiaById($id);
            $modificar = 1;
			//var_dump($datosfotografia);
        }
        $this->view("index", array(
		"modificar" => $modificar, "datosfotografia" => $datosfotografia, "clasificacion" => $clasificacion, "allfotografias" => $allfotografias, "id_Gpo" => $id_Gpo
        ));
    }

    /*--- METODO CREAR NUEVO SISTEMA---*/
    public function guardamodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["descripcion"])) {
            $save = "Llenar descripcion";
        }
        //SE GUARDA MODIFICACION
        else {
            $fotografia = new Fotografia($this->adapter);
            $fotografia->set_descripcion_Fotografia($_POST["descripcion"]);
            $fotografia->set_id_Fotografia($_POST["id_Fotografia"]);
            $save = $fotografia->modificarFotografia();
        }
		//echo $save;
        $this->redirect("Fotografias", "index&mensaje=$save");
    }
	
	
	
	/*--- LIKE---*/
    public function like()
    {
	$id = $_GET['id_Fotografia'];
	$id_Gpo = $_GET['id_Gpo'];
	
	//BUSCAR BITACORA
	$bitacora = new Bitacora($this->adapter);
	$consultar_bitacora = $bitacora->getBitacoraLike($this->id_Usuario,$id);
	
	if($consultar_bitacora==NULL){
	
    $fotografia = new Fotografia($this->adapter);
	$datosfotografia = $fotografia->getFotografiaById($id);
	$suma = $datosfotografia[0]->puntuacion_Fotografia + 1;
    $save = $fotografia->likeFotografia($id,$suma);
	
	//GUARDAR BITACORA
		$bitacora->set_id_Modulo(1);
		$bitacora->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
		$bitacora->set_id_Gpo($id);
		$bitacora->set_accion_Bitacora(15);
        $bitacora->set_accion_Bitacora2(NULL);
		$guardar_bitacora = $bitacora->saveBitacora();
	}else{
		$save = "Ya ha dado like a esta fotografìa antes";
	}
	
    $this->redirect("Fotografias", "buscar&mensaje=$save&id_Gpo=$id_Gpo&#$id");
    }
	
	
	/*--- LIKE---*/
    public function clasificar()
    {
	$id = $_POST['id_Fotografia'];
	$id_Gpo = $_POST['id_Gpo'];
	
    $fotografia = new Fotografia($this->adapter);
	$clasificacion = $_POST['id_Clasificacion'];
	$orientacion = $_POST['orientacion_Fotografia'];
    $save = $fotografia->clasificacionFotografia($id,$clasificacion,$orientacion);
    //echo $id." ".$clasificacion." ".$orientacion;
	$this->redirect("Fotografias", "buscar&mensaje=$save&id_Gpo=$id_Gpo&#$id");
    }

}
?>
