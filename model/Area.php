<?php

class Area extends EntidadBase
{
    private $areaid;
    private $areanombre;
    private $arearegistro;
    private $areaborrado;

    public function __construct($adapter)
    {
        $table = "Areas_Empresas";
        parent::__construct($table, $adapter);
    }

//ID
    public function getId()
    {
        return $this->areaid;
    }

    public function setId($areaid)
    {
        $this->areaid = $areaid;
    }

//NOMBRE
    public function getNombre()
    {
        return $this->areanombre;
    }

    public function setNombre($areanombre)
    {
        $this->areanombre = $areanombre;
    }

//REGISTRO
    public function getFecha()
    {
        return $this->areafecharegistro;
    }

    public function setFecha($areafecharegistro)
    {
        $this->areafecharegistro = $areafecharegistro;
    }

//EMPRESA
    public function get_id_Empresa()
    {
        return $this->id_Empresa;
    }

    public function set_id_Empresa($id_Empresa)
    {
        $this->id_Empresa = $id_Empresa;
    }

//BORRADO
    public function getBorrado()
    {
        return $this->areaborrado;
    }

    public function setBorrado($areaborrado)
    {
        $this->areaborrado = $areaborrado;
    }

    /*--- AREAS: REGISTRAR AREA ---*/
    public function saveNewArea($areas)
    {
        $row_cnt = 1;
        $nombres = "";
        if (is_array($areas) || is_object($areas)) {
            foreach ($areas as $area) {
                //areaid
                if ($area->nombre_Area == $this->areanombre && $area->id_Empresa == $this->id_Empresa) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El area " . $this->areanombre . " ya existe";
            return 2;
        } else {
            $query = "CALL SP_crearArea('$this->areanombre','$this->areafecharegistro',$this->id_Empresa)";
            $save = $this->db()->query($query);
            $mensaje = "Se ha creado el area " . $this->areanombre . "";
            //$this->db()->error;
            return 1;
        }
    }

    /*--- AREAS: ACTUALIZAR AREA POR ID ---*/
    public function modificarArea($id, $areas)
    {
        $row_cnt = 1;
        $nombres = "";
        foreach ($areas as $area) {
            //areaid
            if ($area->nombre_Area == $this->areanombre && $area->id_Empresa == $this->id_Empresa) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El área " . $this->areanombre . " ya existe";
            return 2;
        } else {
            $query = "CALL SP_modificarArea('$this->areanombre',$id)";
            $save = $this->db()->query($query);
            $mensaje = "Se han actualizado los datos del area ID" . $id . "";
            return 3;
        }
    }
}

