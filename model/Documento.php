<?php
class Documento extends EntidadBase{
	private $id_Documento;
	private $docFolio;
	private $docFechaReg;
	private $docFechaRec;
	private $docDirigido;
	private $docAsunto;
	private $docDescripcion;
	private $docFirma;
	private $id_Empresa;
	private $id_Cat_Documento;
	//private $docNombre;
	private $id_Empresa_Local;
	private $id_Usuario;
	private $docPrivacidad;
	//private $id_Expediente;
	//private $id_Registro;
	private $id_Status;
	private $docRespuesta;

	public function __construct($adapter) {
		$table="Documentos";
		parent::__construct($table, $adapter);
	}

	//ID
	public function get_id_Documento(){
		return $this->id_Documento;
	}
	public function set_id_Documento($id_Documento){
		$this->id_Documento = $id_Documento;
	}
	//FOLIO
	public function get_docFolio(){
		return $this->docFolio;
	}
	public function set_docFolio($docFolio){
		$this->docFolio = $docFolio;
	}
	//FECHA REG
	public function get_docFechaReg(){
		return $this->docFechaReg;
	}
	public function set_docFechaReg($docFechaReg){
		$this->docFechaReg = $docFechaReg;
	}
	//FECHA REC
	public function get_docFechaRec(){
		return $this->docFechaRec;
	}
	public function set_docFechaRec($docFechaRec){
		$this->docFechaRec = $docFechaRec;
	}
	//DIRIGIDO
	public function get_docDirigido(){
		return $this->docDirigido;
	}
	public function set_docDirigido($docDirigido){
		$this->docDirigido = $docDirigido;
	}
	//ASUNTO
	public function get_docAsunto(){
		return $this->docAsunto;
	}
	public function set_docAsunto($docAsunto){
		$this->docAsunto = $docAsunto;
	}
	//DESCRIPCION
	public function get_docDescripcion(){
		return $this->docDescripcion;
	}
	public function set_docDescripcion($docDescripcion){
		$this->docDescripcion = $docDescripcion;
	}
	//FIRMA
	public function get_docFirma(){
		return $this->docFirma;
	}
	public function set_docFirma($docFirma){
		$this->docFirma = $docFirma;
	}
	//EMPRESA
	public function get_id_Empresa(){
		return $this->id_Empresa;
	}
	public function set_id_Empresa($id_Empresa){
		$this->id_Empresa = $id_Empresa;
	}
	//DOCUMENTO
	public function get_id_Cat_Documento(){
		return $this->id_Cat_Documento;
	}
	public function set_id_Cat_Documento($id_Cat_Documento){
		$this->id_Cat_Documento = $id_Cat_Documento;
	}
	//NOMBRE
	/*public function get_docNombre(){
		return $this->docNombre;
	}
	public function set_docNombre($docNombre){
		$this->docNombre = $docNombre;
	}*/
	//EMPRESA
	public function get_id_Empresa_Local(){
		return $this->id_Empresa_Local;
	}
	public function set_id_Empresa_Local($id_Empresa_Local){
		$this->id_Empresa_Local = $id_Empresa_Local;
	}
	//USUARIO
	public function get_id_Usuario(){
		return $this->id_Usuario;
	}
	public function set_id_Usuario($id_Usuario){
		$this->id_Usuario = $id_Usuario;
	}
	//PRIVACIDAD
	public function get_docPrivacidad(){
		return $this->docPrivacidad;
	}
	public function set_docPrivacidad($docPrivacidad){
		$this->docPrivacidad = $docPrivacidad;
	}
	//EXPEDIENTE
	/*
	public function get_id_Expediente(){
		return $this->id_Expediente;
	}
	public function set_id_Expediente($id_Expediente){
		$this->id_Expediente = $id_Expediente;
	}*/
	//REGISTRO
	/*public function get_id_Registro(){
		return $this->id_Registro;
	}
	public function set_id_Registro($id_Registro){
		$this->id_Registro = $id_Registro;
	}*/
	//STATUS
	public function get_id_Status(){
		return $this->id_Status;
	}
	public function set_id_Status($id_Status){
		$this->id_Status = $id_Status;
	}
	//RESPUESTA
	public function get_docRespuesta(){
		return $this->docRespuesta;
	}
	public function set_docRespuesta($docRespuesta){
		$this->docRespuesta = $docRespuesta;
	}
	
	/*--- REGISTRAR DOCUMENTO ---*/
	public function saveNewDocumento(){
		$row_cnt = 1;
		$correos = "";
			$query= "CALL SP_addUpDocumento(
			NULL,
			'$this->docFolio',
			'$this->docFechaReg',
			'$this->docFechaRec',
			'$this->docDirigido',
			'$this->docAsunto',
			'$this->docDescripcion',
			'$this->docFirma',
			$this->id_Empresa,
			$this->id_Cat_Documento,
			$this->id_Empresa_Local,
			$this->id_Usuario,
			$this->docPrivacidad,
			NULL,
			$this->docRespuesta,
			'Insertar'
			)";
			
			$save=$this->db()->query($query);
			$mensaje = "Se ha cargado el documento";
			//$mensaje = "folio: ".$this->docFolio."<br/>fechareg: ".$this->docFechaReg."<br>fecharec:".$this->docFechaRec."<br>Dirigido: ".$this->docDirigido."<br>Asunto: ".$this->docAsunto."<br>Descripcion: ".$this->docDescripcion."<br>Firma: ".$this->docFirma."<br>Empresa: ".$this->id_Empresa."<br>Documento: ".$this->id_Cat_Documento."<br>Nombre: ".$this->docNombre."<br>Empresa l: ".$this->id_Empresa_Local."<br>Usuario: ".$this->id_Usuario."<br>Privacidad: ".$this->docPrivacidad."<br>Expediente: ".$this->id_Expediente."<br>Grupo: ".$this->id_Registro."<br>Respuesta: ".$this->docRespuesta;
			//$this->db()->error;
			return $mensaje;
		
	}
	
	
	/*--- EDITAR DOCUMENTO ---*/
	public function editarDocumento(){
		$row_cnt = 1;
		$correos = "";
			$query= "CALL SP_addUpDocumento(
			$this->id_Documento,
			'$this->docFolio',
			'$this->docFechaReg',
			'$this->docFechaRec',
			'$this->docDirigido',
			'$this->docAsunto',
			'$this->docDescripcion',
			'$this->docFirma',
			$this->id_Empresa,
			$this->id_Cat_Documento,
			$this->id_Empresa_Local,
			$this->id_Usuario,
			$this->docPrivacidad,
			NULL,
			$this->docRespuesta,
			'Modificar'
			)";
			
			$save=$this->db()->query($query);
			$mensaje = "Se ha editado el documento";
			/*$mensaje = "id: ".$this->id_Documento."<br/>folio: ".$this->docFolio."<br/>fechareg: ".$this->docFechaReg."<br>fecharec:".$this->docFechaRec."<br>Dirigido: ".$this->docDirigido."<br>Asunto: ".$this->docAsunto."<br>Descripcion: ".$this->docDescripcion."<br>Firma: ".$this->docFirma."<br>Empresa: ".$this->id_Empresa."<br>Documento: ".$this->id_Cat_Documento."<br>Nombre: ".$this->docNombre."<br>Empresa l: ".$this->id_Empresa_Local."<br>Usuario: ".$this->id_Usuario."<br>Privacidad: ".$this->docPrivacidad."<br>Expediente: ".$this->id_Expediente."<br>Respuesta: ".$this->docRespuesta;*/
			//$this->db()->error;
			return $mensaje;
		
	}
	
	
	
	public function saveCatDoc($allcatdocs,$nombre){
		$row_cnt = 1;
		$correos = "";
		if (is_array($allcatdocs) || is_object($allcatdocs)){
		foreach($allcatdocs as $catdoc) {
			if(($catdoc->catDocNombre == $nombre)){
				$row_cnt = $row_cnt + 1;
			}
		}
		}
		if($row_cnt > 1) {
			$mensaje = "El documento ya existe";
			return $mensaje;
		}else{
			$query= "CALL SP_addUpCatDoc(
			NULL,
			'$nombre',
			'Insertar')";
			$save=$this->db()->query($query);
			$mensaje = "Se ha creado el documento";
			//$this->db()->error;
			
		}
		return $mensaje;
	}
	
	public function modifcarCatDoc($allcatdocs,$nombre,$id){
		$row_cnt = 1;
		$correos = "";
		if (is_array($allcatdocs) || is_object($allcatdocs)){
		foreach($allcatdocs as $catdoc) {
			if(($catdoc->catDocNombre == $nombre)&&($catdoc->id_Cat_Documento != $id)){
				$row_cnt = $row_cnt + 1;
			}
		}
		}
		if($row_cnt > 1) {
			$mensaje = "El documento ya existe";
			return $mensaje;
		}else{
			$query= "CALL SP_addUpCatDoc(
			$id,
			'$nombre',
			'Modificar')";
			$save=$this->db()->query($query);
			$mensaje = "Se ha modificado el documento";
			//$this->db()->error;
			
		}
		return $mensaje;
	}
	
	
	
	

	
	
	
}
?>