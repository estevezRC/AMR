<?php
class Bitacora extends EntidadBase{
	private $id_Bitacora;
	private $fecha_Bitacora;
	private $hora_Bitacora;
	private $id_Modulo;
	private $id_Usuario;
	private $id_Gpo;
	private $accion_Bitacora;
	private $id_proyecto;

	public function __construct($adapter) {
		$table="Bitacora";
		parent::__construct($table,$adapter);
	}
	//ID BITACORA
	public function get_id_Bitacora(){
		return $this->id_Bitacora;
	}

	public function set_id_Bitacora($id_Bitacora){
		$this->id_Bitacora = $id_Bitacora;
	}
	//FECHA BITACORA
	public function get_fecha_Bitacora(){
		return $this->fecha_Bitacora;
	}

	public function set_fecha_Bitacora($fecha_Bitacora){
		$this->fecha_Bitacora = $fecha_Bitacora;
	}
	//HORA BITACORA
	public function get_hora_Bitacora(){
		return $this->hora_Bitacora;
	}

	public function set_hora_Bitacora($hora_Bitacora){
		$this->hora_Bitacora = $hora_Bitacora;
	}
	//ID MODULO
	public function get_id_Modulo(){
		return $this->id_Modulo;
	}

	public function set_id_Modulo($id_Modulo){
		$this->id_Modulo = $id_Modulo;
	}
	//ID USUARIO
	public function get_id_Usuario(){
		return $this->id_Usuario;
	}

	public function set_id_Usuario($id_Usuario){
		$this->id_Usuario = $id_Usuario;
	}
	//ID GPO
	public function get_id_Gpo(){
		return $this->id_Gpo;
	}

	public function set_id_Gpo($id_Gpo){
		$this->id_Gpo = $id_Gpo;
	}
	//ACCION
	public function get_accion_Bitacora(){
		return $this->accion_Bitacora;
	}

	public function set_accion_Bitacora($accion_Bitacora){
		$this->accion_Bitacora = $accion_Bitacora;
	}
    //ACCION
    public function get_accion_Bitacora2(){
        return $this->accion_Bitacora2;
    }

    public function set_accion_Bitacora2($accion_Bitacora2){
        $this->accion_Bitacora2 = $accion_Bitacora2;
    }


    // id_proyecto
    public function getIdProyecto()
    {
        return $this->id_proyecto;
    }

    public function setIdProyecto($id_proyecto)
    {
        $this->id_proyecto = $id_proyecto;
    }



	
	/*--- REGISTRAR BITACORA ---*/
	public function saveBitacora(){
        $query= "INSERT INTO Bitacora (id_Bitacora,fecha_Bitacora,hora_Bitacora,id_Modulo,id_Usuario,id_Gpo,
            accion_Bitacora,accion_Bitacora2, id_proyecto)
            VALUES(NULL,NOW(),NOW(), $this->id_Modulo, $this->id_Usuario, $this->id_Gpo, $this->accion_Bitacora,
                '$this->accion_Bitacora2', $this->id_proyecto)";
			if ($this->db()->query($query))
			    return true;
			else
			    return $query;
	}
	
}
