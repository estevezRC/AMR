<?php
class Recurso extends EntidadBase{
	private $recursoid;
	private $recrusonombre;
	private $puestoborrado;

	public function __construct($adapter) {
		$table="Recursos_Sistema";
		parent::__construct($table, $adapter);
	}

	//ID
	public function getId(){
		return $this->recursoid;
	}

	public function setId($recursoid){
		$this->recursoid = $recursoid;
	}
	//NOMBRE
	public function getNombre(){
		return $this->recursonombre;
	}

	public function setNombre($recursonombre){
		$this->recursonombre = $recursonombre;
	}
	//DESCRIPCION
	public function getDescripcion(){
		return $this->recursodescripcion;
	}

	public function setDescripcion($recursodescripcion){
		$this->recursodescripcion = $recursodescripcion;
	}
	//BORRADO
	public function getBorrado(){
		return $this->recursoborrado;
	}

	public function setBorrado($recursoborrado){
		$this->recursoborrado = $recursoborrado;
	}

	/*--- RECURSOS: REGISTRAR RECURSO ---*/
	public function saveNewRecurso($recursos){
		$row_cnt = 1;
		$nombres = "";
		if (is_array($recursos) || is_object($recursos)){
		foreach($recursos as $recurso) {
			//areaid
			if($recurso->nombre_Recurso == $this->recursonombre){
				$row_cnt = $row_cnt + 1;
			}
		}
	}
		if($row_cnt > 1) {
			$mensaje = "El recurso " . $this->recursonombre . " ya existe";
			return $mensaje;
		}else{
			$query= "CALL SP_crearRecurso('$this->recursonombre','$this->recursodescripcion')";
			$save=$this->db()->query($query);
			$mensaje = "Se ha creado el recurso " . $this->recursonombre . "";
			//$this->db()->error;
			return $mensaje;
		}
	}
		/*--- RECURSOS: ACTUALIZAR RECURSOS POR ID ---*/
	public function modificarRecurso($id,$recursos){
	$row_cnt = 1;
		$nombres = "";
		foreach($recursos as $recurso) {
			//areaid
			if(($recurso->nombre_Recurso == $this->recursonombre)&&($recurso->id_Recurso_Sistema != $id )){
				$row_cnt = $row_cnt + 1;
			}
		}
		if($row_cnt > 1) {
			$mensaje = "El recurso " . $this->recursonombre . " ya existe";
			return $mensaje;
		}else{ 
		$query= "CALL SP_modificarRecurso('$this->recursonombre','$this->recursodescripcion',$id)";
			$save=$this->db()->query($query);
		//this->db()->next_result();
			$mensaje = "Se han actualizado los datos del recurso "  . $this->recursonombre . "";
			//$this->db()->error;
			return $mensaje;
		}
	}
}
?>