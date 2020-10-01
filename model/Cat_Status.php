<?php
class Cat_Status extends EntidadBase{
	private $id_Status;
    private $Status;
    private $IdModulo;
    private $Secuencia;
    private $Imagen;

	public function __construct($adapter) {
		$table="Cat_Status";
		parent::__construct($table,$adapter);
	}
    //id_Status
	public function get_id_Status(){
		return $this->id_Status;
	}
	public function set_id_Status($id_Status){
		$this->id_Status = $id_Status;
	}
    //Status
    public function get_Status(){
        return $this->Status;
    }
    public function set_Status($Status){
        $this->Status = $Status;
    }
    //IdModulo
    public function get_IdModulo(){
        return $this->IdModulo;
    }
    public function set_IdModulo($IdModulo){
        $this->IdModulo = $IdModulo;
    }
    //Secuencia
    public function get_Secuencia(){
        return $this->Secuencia;
    }
    public function set_Secuencia($Secuencia){
        $this->Secuencia = $Secuencia;
    }
    //Imagen
    public function get_Imagen(){
        return $this->Imagen;
    }
    public function set_Imagen($Imagen){
        $this->Imagen = $Imagen;
    }

	/*----------------------------------------------- CAT_STATUS: ALTA ----------------------------------------------*/
	public function saveNew($all_Cat_Status){
		$row_cnt = 1;
		if (is_array($all_Cat_Status) || is_object($all_Cat_Status)){
		foreach($all_Cat_Status as $Status) {
			if(($Status->Status == $this->Status)&&($Status->IdModulo == $this->IdModulo)){
				$row_cnt = $row_cnt + 1;
			}}
		if($row_cnt > 1) {
			$mensaje = "El módulo " . $this->IdModulo . " ya cuenta con el Status " . $this->Status . "";
			return $mensaje;
		}else{
			$query= "CALL sp_General_Status($this->IdModulo,'$this->Status',$this->Secuencia,'$this->Imagen','Insertar',NULL)";
			$save=$this->db()->query($query);
			$mensaje = "Se ha creado el estatus " . $this->Status . "";
			return $mensaje;
		}}}
	/*------------------------------------------- CAT_STATUS: MODIFICACION ------------------------------------------*/
	public function modificar($id,$all_Cat_Status){
	$row_cnt = 1;
		if (is_array($all_Cat_Status) || is_object($all_Cat_Status)){
		foreach($all_Cat_Status as $Status) {
            if(($Status->Status == $this->Status)&& ($Status->IdModulo == $this->IdModulo)&& ($Status->id_Status != $id)){
				$row_cnt = $row_cnt + 1;
			}}}
		if($row_cnt > 1) {
            $mensaje = "El MODULO " . $this->IdModulo . " ya cuenta con el Status " . $this->Status . "";
			return $mensaje;
		}else{
            $query= "CALL sp_General_Status($this->IdModulo,'$this->Status',$this->Secuencia,'$this->Imagen','Modificar',$id)";
            $save=$this->db()->query($query);
			$mensaje = "Se ha modificado el estatus " . $this->Status . "";
			return $mensaje;
		}}}
?>