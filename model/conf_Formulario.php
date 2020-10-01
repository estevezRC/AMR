<?php
class conf_Formulario extends EntidadBase{
    private $id_Conf;
    private $id_Usuario;
    private $id_Reporte;
    private $valores_Conf;
    private $id_Status;

    public function __construct($adapter) {
        $table="conf_Formulario";
        parent::__construct($table,$adapter);
    }
    //ID
    public function get_id_Conf(){
        return $this->id_Conf;
    }
    public function set_id_Conf($id_Conf){
        $this->id_Conf = $id_Conf;
    }
    //ID USUARIO
    public function get_id_Usuario(){
        return $this->id_Usuario;
    }
    public function set_id_Usuario($id_Usuario){
        $this->id_Usuario = $id_Usuario;
    }
    //ID REPORTE
    public function get_id_Reporte(){
        return $this->id_Reporte;
    }
    public function set_id_Reporte($id_Reporte){
        $this->id_Reporte = $id_Reporte;
    }
    //VALORES
    public function get_valores_Conf(){
        return $this->valores_Conf;
    }
    public function set_valores_Conf($valores_Conf){
        $this->valores_Conf = $valores_Conf;
    }
    //ESTATUS
    public function get_id_Status(){
        return $this->id_Status;
    }
    public function set_id_Status($id_Status){
        $this->id_Status = $id_Status;
    }
    /*-------------------------------------------- REPORTES: REGISTRAR REPORTE ---------------------------------------*/
    public function saveNewConf(){
        $query= "INSERT INTO conf_Formulario (id_Conf, id_Usuario, id_Reporte, valores_Conf, id_Status) VALUES (NULL, $this->id_Usuario, $this->id_Reporte, '$this->valores_Conf',1)";
        $save=$this->db()->query($query);
        $mensaje = $this->id_Usuario." - ".$this->id_Reporte." - ".$this->valores_Conf;
        return $mensaje;
    }
    /*------------------------------------------- REPORTES: ACTUALIZAR REPORTE POR ID ---------------------------------*/
    public function saveModificacionConf(){
        $query= "UPDATE conf_Formulario SET valores_Conf = '$this->valores_Conf' WHERE id_Conf = $this->id_Conf";
        $save=$this->db()->query($query);
        $mensaje = "modificación correcta";
        return $mensaje;
    }

}
?>