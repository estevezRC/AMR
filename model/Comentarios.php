<?php
class Comentarios_Reportes extends EntidadBase{
	private $id_comentario;
	private $id_Gpo;
	private $id_Usuario;
	private $Comentario_reporte;
	private $Fecha_Comentario;

	public function __construct($adapter) {
		$table="Comentarios_Reportes";
		parent::__construct($table,$adapter);
	}
	//ID
	public function get_id_comentario(){
		return $this->id_comentario;
	}
	public function set_id_comentario($id_comentario){
		$this->id_comentario = $id_comentario;
	}
	//ID GPO
	public function get_id_Gpo(){
		return $this->id_Gpo;
	}
	public function set_id_Gpo($id_Gpo){
		$this->id_Gpo = $id_Gpo;
	}
	//ID USUARIO
	public function get_id_Usuario(){
		return $this->id_Usuario;
	}
	public function set_id_Usuario($id_Usuario){
		$this->id_Usuario = $id_Usuario;
	}
	//COMENTARIO REPORTE
	public function get_Comentario_reporte(){
		return $this->Comentario_reporte;
	}
	public function set_Comentario_reporte($Comentario_reporte){
		$this->Comentario_reporte = $Comentario_reporte;
	}
	//FECHA COMENTARIO
	public function get_Fecha_Comentario(){
		return $this->Fecha_Comentario;
	}
	public function set_Fecha_Comentario($Fecha_Comentario){
		$this->Fecha_Comentario = $Fecha_Comentario;
	}
	//STATUS
	public function get_id_Status(){
		return $this->id_Status;
	}
	public function set_id_Status($id_Status){
		$this->id_Status = $id_Status;
	}
	/*-------------------------------------------- REPORTES: REGISTRAR REPORTE ---------------------------------------*/
	public function saveNewComentario(){

		$query1 = "CALL sp_Add_Up_Data_Comentarios_Reportes(NULL,$this->id_Gpo,$this->id_Usuario,'$this->Comentario_reporte','Insertar')";
		$query = "INSERT INTO Comentarios_Reportes(id_Gpo , id_Usuario, Comentario_Reporte, Fecha_Comentario, id_Status) VALUES($this->id_Gpo,$this->id_Usuario,'$this->Comentario_reporte', NOW(), 1)";
		$save=$this->db()->query($query);
		//$mensaje = mysqli_fetch_assoc($save);
		//$save->close();
		return $mensaje;
		}
	/*------------------------------------------- REPORTES: ACTUALIZAR REPORTE POR ID ---------------------------------*/
	public function modificarComentario(){
	$row_cnt = 1;
		$query= "CALL sp_Add_Up_Data_Comentarios_Reportes ($this->id_comentario,NULL,NULL,'$this->Comentario_reporte','Modificar')";
			$save=$this->db()->query($query);
			return $mensaje;
		}}
?>
