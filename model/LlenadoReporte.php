<?php

class LlenadoReporte extends EntidadBase
{
    private $id_Valor_Reporte_Campo;
    private $id_Proyecto;
    private $id_Gpo_Valores_Reporte;
    private $id_Configuracion_Reporte;
    private $valor_Entero_Reporte;
    private $valor_Texto_Reporte;

    public function __construct($adapter)
    {
        $table = "Valores_Reportes_Caracteristicas";
        parent::__construct($table, $adapter);
    }

//ID VALOR
    public function get_id_Valor_Reporte_Campo()
    {
        return $this->id_Valor_Reporte_Campo;
    }

    public function set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo)
    {
        $this->id_Valor_Reporte_Campo = $id_Valor_Reporte_Campo;
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

//ID GRUPO
    public function get_id_Gpo_Valores_Reporte()
    {
        return $this->id_Gpo_Valores_Reporte;
    }

    public function set_id_Gpo_Valores_Reporte($id_Gpo_Valores_Reporte)
    {
        $this->id_Gpo_Valores_Reporte = $id_Gpo_Valores_Reporte;
    }

//ID CONFIGURACION
    public function get_id_Configuracion_Reporte()
    {
        return $this->id_Configuracion_Reporte;
    }

    public function set_id_Configuracion_Reporte($id_Configuracion_Reporte)
    {
        $this->id_Configuracion_Reporte = $id_Configuracion_Reporte;
    }

//VALOR ENTERO
    public function get_valor_Entero_Reporte()
    {
        return $this->valor_Entero_Reporte;
    }

    public function set_valor_Entero_Reporte($valor_Entero_Reporte)
    {
        $this->valor_Entero_Reporte = $valor_Entero_Reporte;
    }

//VALOR TEXTO
    public function get_valor_Texto_Reporte()
    {
        return $this->valor_Texto_Reporte;
    }

    public function set_valor_Texto_Reporte($valor_Texto_Reporte)
    {
        $this->valor_Texto_Reporte = $valor_Texto_Reporte;
    }

    /*--- LLENADO REPORTE: REGISTRAR LLENADO---*/
    public function saveNewLlenado()
    {
        $query = "CALL sp_AddData_General_ConfValoresAlterno($this->id_Proyecto,'$this->valor_Texto_Reporte',
			$this->valor_Entero_Reporte,$this->id_Configuracion_Reporte,'Valores_Reportes',$this->id_Gpo_Valores_Reporte)";
        $save = $this->db()->query($query);
        $mensaje = "Se ha llenado el campo " . $this->id_Gpo_Valores_Reporte . "";
        //$query->close();
        //$this->db()->error;
        return $mensaje;
    }

    /*--- LLENADO REPORTE: REGISTRAR LLENADO---*/
    public function saveNewLlenadoPlantilla()
    {
        //$query = "CALL sp_AddData_General_ConfValoresAlterno($this->id_Proyecto,'$this->valor_Texto_Reporte',
        //	$this->valor_Entero_Reporte,$this->id_Configuracion_Reporte,'Valores_Reportes',$this->id_Gpo_Valores_Reporte)";
        $query = " INSERT INTO Valores_Reportes_Campos (id_Proyecto,
            id_Gpo_Valores_Reporte,
            valor_Texto_Reporte,
            valor_Entero_Reporte,
            id_Configuracion_Reporte)
            VALUES ($this->id_Proyecto,
            $this->id_Gpo_Valores_Reporte,
            '$this->valor_Texto_Reporte',
            $this->valor_Entero_Reporte,
            $this->id_Configuracion_Reporte)";
        $save = $this->db()->query($query);
        return $save;
    }

    /*--- CAMPOS: ACTUALIZAR CAMPO POR ID ---*/
    public function modificarLlenado()
    {
        $row_cnt = 1;
        $nombres = "";

        $query = "UPDATE Valores_Reportes_Campos SET valor_Texto_Reporte = '$this->valor_Texto_Reporte', 
		valor_Entero_Reporte = $this->valor_Entero_Reporte WHERE id_Valor_Reporte_Campo = $this->id_Valor_Reporte_Campo";
        $save = $this->db()->query($query);
        //this->db()->next_result();
        $mensaje = $this->valor_Texto_Reporte . " " . $this->valor_Entero_Reporte . " " . $this->id_Valor_Reporte_Campo;
        //$mensaje = "Se ha modificado la configuración "  . $this->id_Valor_Reporte_Campo . "";
        //$this->db()->error;
        return $mensaje;

    }


    /*--- INSERTAR VALOR EXTRA ---*/
    public function InsertarValorExtra($id_Gpo_Valores, $fecha)
    {
        $row_cnt = 1;
        $nombres = "";

        $query = "INSERT INTO Valores_Reportes_Campos 
  		(id_Proyecto,id_Gpo_Valores_Reporte,valor_Texto_Reporte,valor_Entero_Reporte,id_Configuracion_Reporte) 
  		VALUES (1,$id_Gpo_Valores,'$fecha',NULL,466)";
        $save = $this->db()->query($query);
        //this->db()->next_result();
        $mensaje = $id_Gpo_Valores;
        //$this->db()->error;
        return "algo";

    }

    // CAMBIAR STATUS DEL REPORTE LLENADO
    public function cambiarStatusReporteLlenado($status, $id_Gpo_Valores) {
        $query = "UPDATE Reportes_Llenados SET id_Status_Elemento = $status WHERE id_Gpo_Valores_Reporte = $id_Gpo_Valores";
        return $this->db()->query($query);
    }
}

?>
