<?php
/**
 * Created by PhpStorm.
 * User: Bassó
 * Date: 31/05/2019
 * Time: 05:15 PM
 */
class Cat_Categoria extends EntidadBase{
    private $idCatalogo;
    private $concepto;
    private $descripcion;
    private $id_Categoria;
    private $id_Status;


    public function __construct($adapter) {
        $table="Cat_Categoria";
        parent::__construct($table,$adapter);
    }
    //Id_Modulo
    public function get_idCatalogo(){
        return $this->idCatalogo;
    }
    public function set_idCatalogo($idCatalogo){
        $this->idCatalogo = $idCatalogo;
    }
    //Nombre
    public function get_concepto(){
        return $this->concepto;
    }
    public function set_concepto($concepto){
        $this->conceptoe = $concepto;
    }
    //Descripcion
    public function get_descripcion(){
        return $this->descripcion;
    }
    public function set_descripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    //id_Categoria
    public function get_id_Categoria(){
        return $this->id_Categoria;
    }
    public function set_id_Categoria($id_Categoria){
        $this->id_Categoria = $id_Categoria;
    }
    //id_Status
    public function get_id_Status(){
        return $this->id_Status;
    }
    public function set_id_Status($id_Status){
        $this->id_Status = $id_Status;
    }

    /*---------------------------------------------- Cat_Modulos: ALTA ----------------------------------------------*/
    public function saveNewCatCategoria($concepto,$desc,$categoria){
                $query= "INSERT INTO Catalogo_categoria (idCatalogo,concepto,descripcion,id_Categoria,id_Status) 
                VALUES (NULL,'$concepto','$desc',$categoria,1)";
                $save=$this->db()->query($query);
                $mensaje = "";
                return $mensaje;
            }
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