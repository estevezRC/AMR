<?php

class Campo extends EntidadBase
{
    private $id_Campo_Reporte;
    private $id_Proyecto;
    private $nombre_Campo;
    private $descripcion_Campo;
    private $tipo_Valor_Campo;
    private $tipo_Reactivo_Campo;
    private $id_Status_Campo;
    private $Valor_Default;

    public function __construct($adapter)
    {
        $table = "Cat_Campos_Reportes";
        parent::__construct($table, $adapter);
    }

    //ID
    public function get_id_Campo_Reporte()
    {
        return $this->id_Campo_Reporte;
    }

    public function set_id_Campo_Reporte($id_Campo_Reporte)
    {
        $this->id_Campo_Reporte = $id_Campo_Reporte;
    }

    //ID PROYECTO
    public function get_id_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function set_id_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
    }

    //NOMBRE CAMPO
    public function get_nombre_Campo()
    {
        return $this->nombre_Campo;
    }

    public function set_nombre_Campo($nombre_Campo)
    {
        $this->nombre_Campo = $nombre_Campo;
    }

    //DESCRIPCION CAMPO
    public function get_descripcion_Campo()
    {
        return $this->descripcion_Campo;
    }

    public function set_descripcion_Campo($descripcion_Campo)
    {
        $this->descripcion_Campo = $descripcion_Campo;
    }

    //TIPO VALOR CAMPO
    public function get_tipo_Valor_Campo()
    {
        return $this->tipo_Valor_Campo;
    }

    public function set_tipo_Valor_Campo($tipo_Valor_Campo)
    {
        $this->tipo_Valor_Campo = $tipo_Valor_Campo;
    }

    //TIPO REACTIVO CAMPO
    public function get_tipo_Reactivo_Campo()
    {
        return $this->tipo_Reactivo_Campo;
    }

    public function set_tipo_Reactivo_Campo($tipo_Reactivo_Campo)
    {
        $this->tipo_Reactivo_Campo = $tipo_Reactivo_Campo;
    }

    //ESTATUS CAMPO
    public function get_id_Status_Campo()
    {
        return $this->id_Status_Campo;
    }

    public function set_id_Status_Campo($id_Status_Campo)
    {
        $this->id_Status_Campo = $id_Status_Campo;
    }

    //VALOR DEFAULT
    public function get_Valor_Default()
    {
        return $this->Valor_Default;
    }

    public function set_Valor_Default($Valor_Default)
    {
        $this->Valor_Default = $Valor_Default;
    }

    /*------------------------------------------ CAMPOS: REGISTRAR CAMPO ---------------------------------------------*/
    public function saveNewCampo($campos)
    {
        $row_cnt = 1;
        $nombres = "";
        if (is_array($campos) || is_object($campos)) {
            foreach ($campos as $campo) {
                //areaid
                if ($campo->nombre_Campo == $this->nombre_Campo) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El campo " . $this->nombre_Campo . " ya existe";
            return 2;
        } else {
            /*EL 0 corresponde al id padre de la caracteristica de inventario*/
            $query = "CALL sp_AddData_CatCamRep_CatCarElem($this->id_Proyecto,'$this->nombre_Campo',
			'$this->descripcion_Campo','$this->tipo_Valor_Campo','$this->tipo_Reactivo_Campo','0','CamposReportes',
			'$this->Valor_Default')";
            $save = $this->db()->query($query);
            switch ($save) {
                case 0:
                    $mensaje = "Fallo la transacción";
                    break;
                case 1:
                    $mensaje = "Se ha creado el campo $this->nombre_Campo";
                    break;
                case 2:
                    $mensaje = "No existe el proyecto ID $this->id_Proyecto";
                    break;
            }

            //$this->db()->error;
            return 1;
        }
    }

    public function saveNewCampoPlantilla($allCampos)
    {
        $existeCampo = false;
        foreach ($allCampos as $campo) {
            if ($campo->nombre_Campo == $this->nombre_Campo) {
                $existeCampo = true;
            }
        }

        if (!$existeCampo) {
            $query = "INSERT INTO Cat_Campos_Reportes (id_Proyecto, 
            nombre_Campo,
            descripcion_Campo,
            tipo_Valor_Campo,
            tipo_Reactivo_Campo,
            id_Status_Campo,
            Valor_Default)
          VALUES ($this->id_Proyecto, 
          '$this->nombre_Campo',
           '$this->descripcion_Campo',
           '$this->tipo_Valor_Campo',
           '$this->tipo_Reactivo_Campo',
           1,
           '$this->Valor_Default' 
          )";

            return $this->db()->query($query);
        }

    }

    /*--------------------------------------- CAMPOS: ACTUALIZAR CAMPO POR ID ----------------------------------------*/
    public function modificarCampo($id, $campos)
    {
        $row_cnt = 1;
        $nombres = "";
        foreach ($campos as $campo) {
            //areaid
            if (($campo->nombre_Campo == $this->nombre_Campo) && ($campo->id_Campo_Reporte != $id)) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El campo " . $this->nombre_Campo . " ya existe";
            return 2;
        } else {
            $query = "CALL sp_UpdData_CatCamRep_CatCarElem($id,'$this->id_Proyecto','$this->nombre_Campo',
		'$this->descripcion_Campo','$this->tipo_Valor_Campo','$this->tipo_Reactivo_Campo','0','CamposReportes',
		'$this->Valor_Default')";
            $save = $this->db()->query($query);
            //this->db()->next_result();
            $mensaje = "Se han actualizado los datos del campo " . $this->nombre_Campo . "";
            //$this->db()->error;
            return 3;
        }
    }


    public function modificarValorDefault($id, $valor)
    {

        $query = "UPDATE Cat_Campos_Reportes SET Valor_Default = '$valor' WHERE id_Campo_Reporte = $id";
        $save = $this->db()->query($query);
        $mensaje = "Se han actualizado los datos del campo " . $this->nombre_Campo . "";
        return $mensaje;
    }


}

?>
