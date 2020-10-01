<?php
class Empresas_Proyectos extends EntidadBase{
    private $id_Empresas_Proyectos;
    private $id_Empresa;
    private $id_Proyecto;
    private $id_Status;

    public function __construct($adapter) {
        $table="Empresas_Proyectos";
        parent::__construct($table,$adapter);
    }
//ID
    public function get_id_Empresas_Proyectos(){
        return $this->id_Empresas_Proyectos;
    }
    public function set_id_Empresas_Proyectos($id_Empresas_Proyectos){
        $this->id_Empresas_Proyectos = $id_Empresas_Proyectos;
    }
//EMPRESA
    public function get_id_Empresa(){
        return $this->id_Empresa;
    }
    public function set_id_Empresa($id_Empresa){
        $this->id_Empresa = $id_Empresa;
    }
//PROYECTO
    public function get_id_Proyecto(){
        return $this->id_Proyecto;
    }
    public function set_id_Proyecto($id_Proyecto){
        $this->id_Proyecto = $id_Proyecto;
    }
    /*--- PROYECTOS: REGISTRAR PROYECTOS ---*/
    public function saveNewEmpresaProyecto($empresasproyectos){
        $row_cnt = 1;
        $nombres = "";
        foreach($empresasproyectos as $empresaproyecto) {
            //areaid
            if(($empresaproyecto->id_Empresa == $this->id_Empresa)&&($empresaproyecto->id_Proyecto == $this->id_Proyecto)){
                $row_cnt = $row_cnt + 1;
            }
        }
        if($row_cnt > 1) {
            $mensaje = "Ya se han asigando la empresa y el proyecto anteriormente";
            return $mensaje;
        }else{
            $query= "CALL sp_Add_Up_Empresas_Proyectos(NULL,$this->id_Empresa,$this->id_Proyecto,'Insertar')";
            $save=$this->db()->query($query);
            $mensaje = "Se ha relacionado el proyecto y la empresa";
            //$this->db()->error;
            return $mensaje;
        }
    }
    /*--- AREAS: ACTUALIZAR AREA POR ID ---*/
    public function modificarEmpresaProyecto($id,$empresasproyectos){
        $row_cnt = 1;
        $nombres = "";
        foreach($empresasproyectos as $empresaproyecto) {
            //areaid
            if(($empresaproyecto->id_Empresa == $this->id_Empresa)&&($empresaproyecto->id_Proyecto == $this->id_Proyecto)
                &&($empresaproyecto->id_Proyecto != $id)){
                $row_cnt = $row_cnt + 1;
            }
        }
        if($row_cnt > 1) {
            $mensaje = "Ya se han asigando la empresa y el proyecto anteriormente";
            return $mensaje;
        }else{
            $query= "CALL sp_Add_Up_Empresas_Proyectos($id,$this->id_Empresa,$this->id_Proyecto,'Modificar')";
            $save=$this->db()->query($query);
            //this->db()->next_result();
            $mensaje = "Se ha actualizado la relacion de el proyecto y la empresa";
            //$this->db()->error;
            return $mensaje;
        }
    }
}
?>