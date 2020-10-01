<?php

class Monitoreo extends EntidadBase{
    private $idCatMonitoreo;
    private $Acronimo;
    private $Concepto;
    private $IdPadreCatMonitorio;
    private $Descriptiva;
    private $idEstatus;

    public function __construct($adapter) {
        $table="Cat_Monitoreo_Diario";
        parent::__construct($table,$adapter);
    }

    //ID
    public function getId() {
        return $this -> idCatMonitoreo;
    }
    public function setId($idCatMonitoreo) {
        $this -> idCatMonitoreo = $idCatMonitoreo;
    }

    //ACRONIMO
    public function getAcronimo() {
        return $this -> Acronimo;
    }
    public function setAcronimo($Acronimo) {
        $this -> Acronimo = $Acronimo;
    }

    //CONCEPTO
    public function getConcepto() {
        return $this -> Concepto;
    }
    public function setConcepto($Concepto) {
        $this -> Concepto = $Concepto;
    }

    //IDPADRE
    public function getIdPadre() {
        return $this -> IdPadreCatMonitorio;
    }
    public function setIdPadre($IdPadreCatMonitorio) {
        $this -> IdPadreCatMonitorio = $IdPadreCatMonitorio;
    }

    //DESCRIPCION
    public function getDescriptiva() {
        return $this -> Descriptiva;
    }
    public function setDescriptiva($Descriptiva) {
        $this -> Descriptiva = $Descriptiva;
    }

    //IDESTATUS
    public function getIdEstatus() {
        return $this -> idEstatus;
    }

    public function setIdEstatus($idEstatus) {
        $this -> idEstatus = $idEstatus;
    }

    /*--- Monitore: REGISTRAR Monitoreo ---*/
    public function saveNewMonitoreo($allmonitoreos){
        $row_cnt = 1;
        if (is_array($allmonitoreos) || is_object($allmonitoreos)){
            foreach($allmonitoreos as $monitoreo) {
                if(($monitoreo->Concepto == $this->Concepto)){
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if($row_cnt > 1) {
            $mensaje = 2;
            return $mensaje;
        }else{
            $query= "CALL sp_Add_Up_Monitoreo(null,'$this->Acronimo', '$this->Concepto', $this->IdPadreCatMonitorio, '$this->Descriptiva', 'null', 'Insertar')";
            $save=$this->db()->query($query);
            $mensaje = 1;
            //$mensaje = $this->Acronimo."-".$this->Concepto."-".$this->IdPadreCatMonitorio."-".$this->Descriptiva;
            //$this->db()->error;
            return $mensaje;
        }
        return $mensaje;
    }

    /*--- AREAS: ACTUALIZAR AREA POR ID ---*/
    public function modificarMonitoreo($id,$allmonitoreos){
        $row_cnt = 1;
        if (is_array($allmonitoreos) || is_object($allmonitoreos)){
            foreach($allmonitoreos as $monitoreo) {
                if(($monitoreo->Concepto == $this->Concepto)&&($monitoreo->idCatMonitoreo != $id)){
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if($row_cnt > 1) {
            $mensaje = "El monitoreo " . $this -> Concepto . " ya existe";
            return $mensaje;
        }else{
            $query= "CALL sp_Add_Up_Monitoreo($id,'$this->Acronimo', '$this->Concepto', $this->IdPadreCatMonitorio, '$this->Descriptiva', 'null', 'Modificar')";
            $save=$this->db()->query($query);
            //this->db()->next_result();
            $mensaje = "Se han actualizado los datos del monitoreo con ID: "  . $id;
            //$this->db()->error;
            return $mensaje;
        }
        return $mensaje;
    }


}
?>
