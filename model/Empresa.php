<?php
class Empresa extends EntidadBase{
	private $id_Proyecto;
	private $nombre_Empresa;
	private $logo_Izquierda_Empresa;
	private $logo_Derecha_Empresa;
	private $telefono_Empresa;
	private $celular_Empresa;
	private $fecha_Alta_Empresa;
	private $correo_Empresa;
	private $directorio_Empresa;
	private $rol_Empresa;
	private $descripcion_Empresa;
	private $id_Estatus_Empresa;

	public function __construct($adapter) {
		$table="Empresas_Proyectos";
		parent::__construct($table,$adapter);
	}
//ID
	public function getId(){
		return $this->id_Proyecto;
	}

	public function setId($id_Proyecto){
		$this->id_Proyecto = $id_Proyecto;
	}
//NOMBRE EMPRESA
	public function getNombre(){
		return $this->nombre_Empresa;
	}

	public function setNombre($nombre_Empresa){
		$this->nombre_Empresa = $nombre_Empresa;
	}
//LOGO IZQUIERDO
	public function getLogoIzquierdo(){
		return $this->logo_Izquierda_Empresa;
	}

	public function setLogoIzquierdo($logo_Izquierda_Empresa){
		$this->logo_Izquierda_Empresa = $logo_Izquierda_Empresa;
	}
//LOGO DERECHO
	public function getLogoDrecho(){
		return $this->logo_Derecha_Empresa;
	}

	public function setLogoDrecho($logo_Derecha_Empresa){
		$this->logo_Derecha_Empresa = $logo_Derecha_Empresa;
	}
//TELEFONO
	public function getTelefono(){
		return $this->telefono_Empresa;
	}

	public function setTelefono($telefono_Empresa){
		$this->telefono_Empresa = $telefono_Empresa;
	}
//CELULAR
	public function getCelular(){
		return $this->celular_Empresa;
	}

	public function setCelular($celular_Empresa){
		$this->celular_Empresa = $celular_Empresa;
	}
//FECHA ALTA
	public function getFecha(){
		return $this->fecha_Alta_Empresa;
	}

	public function setFecha($fecha_Alta_Empresa){
		$this->fecha_Alta_Empresa = $fecha_Alta_Empresa;
	}
//CORREO EMPRESA
	public function getCorreo(){
		return $this->correo_Empresa;
	}

	public function setCorreo($correo_Empresa){
		$this->correo_Empresa = $correo_Empresa;
	}
//DIRECTORIO EMPRESA
	public function getDirectorio(){
		return $this->directorio_Empresa;
	}

	public function setDirectorio($directorio_Empresa){
		$this->directorio_Empresa = $directorio_Empresa;
	}
//ROL EMPRESA
	public function getRol(){
		return $this->rol_Empresa;
	}

	public function setRol($rol_Empresa){
		$this->rol_Empresa = $rol_Empresa;
	}
//DESCRIPCION
	public function getDescripcion(){
		return $this->descripcion_Empresa;
	}

	public function setDescripcion($descripcion_Empresa){
		$this->descripcion_Empresa = $descripcion_Empresa;
	}
//ESTATUS EMPRESA
	public function getEstatus(){
		return $this->id_Estatus_Empresa;
	}

	public function setEstatus($id_Eestatus_Empresa){
		$this->id_Estatus_Empresa = $id_Estatus_Empresa;
	}
/*--- EMPRESAS: REGISTRAR EMPRESA ---*/
	public function saveNewEmpresa($empresas){
		$row_cnt = 1;
		$nombres = "";
		if (is_array($empresas) || is_object($empresas)){
		foreach($empresas as $empresa) {
			if($empresa->nombre_Empresa == $this->nombre_Empresa){
				$row_cnt = $row_cnt + 1;
			}
		}
	}
		if($row_cnt > 1) {
			$mensaje = "La empresa " . $this->nombre_Empresa . " ya existe";
			return $mensaje;
		}else{
			$query= "CALL sp_Add_Up_Empresas('$this->nombre_Empresa','$this->logo_Izquierda_Empresa','$this->logo_Derecha_Empresa','$this->telefono_Empresa','$this->celular_Empresa','$this->fecha_Alta_Empresa','$this->correo_Empresa','$this->directorio_Empresa','$this->rol_Empresa','$this->descripcion_Empresa',NULL,'Insertar')";
			$save=$this->db()->query($query);
			$mensaje = "Se ha creado la empresa " . $this->nombre_Empresa . "";
			//$this->db()->error;
			return $mensaje;
		}
	}
/*--- EMPRESAS: ACTUALIZAR EMPRESA POR ID ---*/
	public function modificarEmpresa($id,$empresas){
	$row_cnt = 1;
		$nombres = "";
		foreach($empresas as $empresa) {
			if(($empresa->nombre_Empresa == $this->nombre_Empresa)&&($empresa->id_Empresa != $id)){
				$row_cnt = $row_cnt + 1;
			}
		}
		if($row_cnt > 1) {
			$mensaje = "La empresa " . $this->nombre_Empresa . " ya existe";
			return 2;
		}else{ 
		$query= "CALL sp_Add_Up_Empresas('$this->nombre_Empresa','$this->logo_Izquierda_Empresa','$this->logo_Derecha_Empresa','$this->telefono_Empresa','$this->celular_Empresa',NULL,'$this->correo_Empresa','$this->directorio_Empresa','$this->rol_Empresa','$this->descripcion_Empresa',$id,'Modificar')";
			$save=$this->db()->query($query);
		//this->db()->next_result();
			$mensaje = "Se han actualizado los datos del la empresa "  . $this->nombre_Empresa . "";
			//$this->db()->error;
			return 3;
		}
	}
}
?>
