<?php
class Cat_Dispositivos extends EntidadBase{
	private $Id_Dispositivo;
    private $Marca;
    private $id_Usuario;
    private $NumeroSerie;
    private $Id_Status;
	private $Id_Proyecto;


	public function __construct($adapter) {
		$table="Cat_Dispositivos";
		parent::__construct($table,$adapter);
	}
    //Id_Dispositivo
	public function get_Id_Dispositivo(){
		return $this->Id_Dispositivo;
	}
	public function set_Id_Dispositivo($Id_Dispositivo){
		$this->Id_Dispositivo = $Id_Dispositivo;
	}
    //Marca
    public function get_Marca(){
        return $this->Marca;
    }
    public function set_Marca($Marca){
        $this->Marca = $Marca;
    }
    //id_Usuario
    public function get_id_Usuario(){
        return $this->id_Usuario;
    }
    public function set_id_Usuario($id_Usuario){
        $this->id_Usuario = $id_Usuario;
    }
    //NumeroSerie
    public function get_NumeroSerie(){
        return $this->NumeroSerie;
    }
    public function set_NumeroSerie($NumeroSerie){
        $this->NumeroSerie = $NumeroSerie;
    }
    //Id_Status
    public function get_Id_Status(){
        return $this->Id_Status;
    }
    public function set_Id_Status($Id_Status){
        $this->Id_Status = $Id_Status;
    }
	//Id_Proyecto
    public function get_Id_Proyecto(){
        return $this->Id_Proyecto;
    }
    public function set_Id_Proyecto($Id_Proyecto){
        $this->Id_Proyecto = $Id_Proyecto;
    }

	/*-------------------------------------------- Cat_Dispositivos: ALTA ---------------------------------------------*/
	public function saveNew($alldispositivos){
		
		
		
		$row_cnt = 1;
		if (is_array($alldispositivos) || is_object($alldispositivos)){
		foreach($alldispositivos as $dispositivo) {
			if(($dispositivo->NumeroSerie == $this->NumeroSerie)){
				$row_cnt = $row_cnt + 1;
			}
			}
			}
			
		if($row_cnt > 1) {
			$mensaje = "Ya hay un dispositivo con el numero de serie: " . $this->NumeroSerie . "";
			return $mensaje;
		}
		
		if($row_cnt == 1){
			$query= "CALL sp_General_CatDispositivos('$this->Marca',$this->id_Usuario,'$this->NumeroSerie','Insertar',NULL,$this->Id_Proyecto)";
			$save=$this->db()->query($query);
			$mensaje = "Se ha ingresado el dispositivo número de serie " . $this->NumeroSerie . "";
            //$mensaje = $this->Marca." ".$this->id_Usuario." ".$this->NumeroSerie." ".$this->Id_Proyecto;
			return $mensaje;
		}
		
		
		return $mensaje;
		}
	/*---------------------------------------- Cat_Dispositivos: MODIFICACION ----------------------------------------*/
	public function modificar($Id_Dispositivo,$alldispositivos){
	$row_cnt = 1;
        if (is_array($alldispositivos) || is_object($alldispositivos)){
            foreach($alldispositivos as $dispositivo) {
                if(($dispositivo->NumeroSerie == $this->NumeroSerie)&&($dispositivo->Id_Dispositivo != $this->Id_Dispositivo)){
				$row_cnt = $row_cnt + 1;
			}}}
		if($row_cnt > 1) {
            $mensaje = "Ya hay un dispositivo con el numero de serie: " . $this->NumeroSerie . "";
			return $mensaje;
		}else{
            $query= "CALL sp_General_CatDispositivos('$this->Marca',$this->id_Usuario,'$this->NumeroSerie','Modificar',$Id_Dispositivo,NULL)";
            $save=$this->db()->query($query);
			$mensaje = "Se ha modificado el dispositivo ID" . $this->NumeroSerie . "";
			return $mensaje;
		}}}
?>