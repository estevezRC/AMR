<?php
class Cat_Modulos extends EntidadBase{
	private $Id_Modulo;
	private $Nombre;
	private $Id_Status;


	public function __construct($adapter) {
		$table="Cat_Modulos";
		parent::__construct($table,$adapter);
	}
    //Id_Modulo
	public function get_Id_Modulo(){
		return $this->Id_Modulo;
	}
	public function set_Id_Modulo($Id_Modulo){
		$this->Id_Modulo = $Id_Modulo;
	}
    //Nombre
    public function get_Nombre(){
        return $this->Nombre;
    }
    public function set_Nombre($Nombre){
        $this->Nombre = $Nombre;
    }
    //Id_Status
    public function get_Id_Status(){
        return $this->Id_Status;
    }
    public function set_Id_Status($Id_Status){
        $this->Id_Status = $Id_Status;
    }

	/*---------------------------------------------- Cat_Modulos: ALTA ----------------------------------------------*/
	public function saveNew($allmodulos){
		$row_cnt = 1;
		if (is_array($allmodulos) || is_object($allmodulos)){
		foreach($allmodulos as $modulo) {
			if(($modulo->Nombre == $this->Nombre)){
				$row_cnt = $row_cnt + 1;
			}}
		if($row_cnt > 1) {
			$mensaje = "El Módulo " . $this->Nombre . " ya existe";
			return $mensaje;
		}else{
			$query= "CALL sp_General_CatModulos('$this->Nombre','Insertar',NULL)";
			$save=$this->db()->query($query);
			$mensaje = "Se ha creado el modulo " . $this->Nombre . "";
			return $mensaje;
		}}}
	/*----------------------------------------------- Cat_Modulos: MODIFICACION -------------------------------------*/
	public function modificar($Id_Modulo,$allmodulos){
	$row_cnt = 1;
		if (is_array($allmodulos) || is_object($allmodulos)){
		foreach($allmodulos as $modulo) {
            if(($modulo->Nombre == $this->Nombre)&&($modulo->Id_Modulo != $Id_Modulo)){
				$row_cnt = $row_cnt + 1;
			}}}
		if($row_cnt > 1) {
            $mensaje = "El Módulo " . $this->Nombre . " ya existe " ;
			return $mensaje;
		}else{
            $query= "CALL sp_General_CatModulos('$this->Nombre','Modificar',$Id_Modulo)";
            $save=$this->db()->query($query);
			$mensaje = "Se ha modificado el módulo ". $this->Nombre . "";
			return $mensaje;
		}}}
?>