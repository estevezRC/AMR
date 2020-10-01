<?php

//require 'lib/PHPMailer-master/PHPMailerAutoload.php';
class ReporteLlenado extends EntidadBase
{
    private $id_Registro_Reporte;
    private $id_Gpo_Valores_Reporte;
    private $Comentarios_Reporte;
    private $id_Status_Elemento;
    private $fecha_registro;
    private $id_Usuario;
    private $id_Reporte;
    private $titulo_Reporte;
    private $reporte_Modificado;
    private $id_Tiempo;
    private $id_Etapa;
    private $id_Gpo_Padre;
    private $latitud_Reporte;
    private $longitud_Reporte;

    public function __construct($adapter)
    {
        $table = "Reportes_Llenados";
        parent::__construct($table, $adapter);
    }

//ID
    public function get_id_Registro_Reporte()
    {
        return $this->id_Registro_Reporte;
    }

    public function set_id_Registro_Reporte($id_Registro_Reporte)
    {
        $this->id_Registro_Reporte = $id_Registro_Reporte;
    }

//GRUPO VALORES
    public function get_id_Gpo_Valores_Reporte()
    {
        return $this->id_Gpo_Valores_Reporte;
    }

    public function set_id_Gpo_Valores_Reporte($id_Gpo_Valores_Reporte)
    {
        $this->id_Gpo_Valores_Reporte = $id_Gpo_Valores_Reporte;
    }

//COMENTARIOS REPORTE
    public function get_Comentarios_Reporte()
    {
        return $this->Comentarios_Reporte;
    }

    public function set_Comentarios_Reporte($Comentarios_Reporte)
    {
        $this->Comentarios_Reporte = $Comentarios_Reporte;
    }

//ESTATUS
    public function get_id_Status_Elemento()
    {
        return $this->id_Status_Elemento;
    }

    public function set_id_Status_Elemento($id_Status_Elemento)
    {
        $this->id_Status_Elemento = $id_Status_Elemento;
    }

//FECHA REGISTRO
    public function get_fecha_registro()
    {
        return $this->fecha_registro;
    }

    public function set_fecha_registro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }

//ID USUARIO
    public function get_id_Usuario()
    {
        return $this->id_Usuario;
    }

    public function set_id_Usuario($id_Usuario)
    {
        $this->id_Usuario = $id_Usuario;
    }

//ID REPORTE
    public function get_id_Reporte()
    {
        return $this->id_Reporte;
    }

    public function set_id_Reporte($id_Reporte)
    {
        $this->id_Reporte = $id_Reporte;
    }

//TITULO REPORTE
    public function get_titulo_Reporte()
    {
        return $this->titulo_Reporte;
    }

    public function set_titulo_Reporte($titulo_Reporte)
    {
        $this->titulo_Reporte = $titulo_Reporte;
    }

//REPORTE MODIFCADO
    public function get_reporte_Modifcado()
    {
        return $this->reporte_Modifcado;
    }

    public function set_reporte_Modifcado($reporte_Modifcado)
    {
        $this->reporte_Modifcado = $reporte_Modifcado;
    }

    //ID TIEMPO
    public function get_id_Tiempo()
    {
        return $this->id_Tiempo;
    }

    public function set_id_Tiempo($id_Tiempo)
    {
        $this->id_Tiempo = $id_Tiempo;
    }

    //ID ETAPA
    public function get_id_Etapa()
    {
        return $this->id_Etapa;
    }

    public function set_id_Etapa($id_Etapa)
    {
        $this->id_Etapa = $id_Etapa;
    }

    //ID GPO PADRE
    public function get_id_Gpo_Padre()
    {
        return $this->id_Gpo_Padre;
    }

    public function set_id_Gpo_Padre($id_Gpo_Padre)
    {
        $this->id_Gpo_Padre = $id_Gpo_Padre;
    }

    //LATITUD
    public function get_latitud_Reporte()
    {
        return $this->latitud_Reporte;
    }

    public function set_latitud_Reporte($latitud_Reporte)
    {
        $this->latitud_Reporte = $latitud_Reporte;
    }

    //LONGITUD
    public function get_longitud_Reporte()
    {
        return $this->longitud_Reporte;
    }

    public function set_longitud_Reporte($longitud_Reporte)
    {
        $this->longitud_Reporte = $longitud_Reporte;
    }

    //CLASIFICACION
    public function get_clas_Reporte()
    {
        return $this->clas_Reporte;
    }

    public function set_clas_Reporte($clas_Reporte)
    {
        $this->clas_Reporte = $clas_Reporte;
    }

    /*--- REPORTE LLENADO: REGISTRAR REPORTE ---*/
    public function saveNewReporteLlenado($reportesllenados)
    {
        $row_cnt = 1;
        if (is_array($reportesllenados) || is_object($reportesllenados)) {
            foreach ($reportesllenados as $reportellenado) {
                if ($reportellenado->id_Gpo_Valores_Reporte == $this->id_Gpo_Valores_Reporte) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El grupo de valores " . $this->id_Gpo_Valores_Reporte . " ya existe";
            return $mensaje;
        } else {
            $query = "INSERT INTO Reportes_Llenados 
			(id_Registro_Reporte,id_Gpo_Valores_Reporte,comentarios_Reporte,id_Status_Elemento,fecha_registro,
			id_Usuario,id_Reporte,titulo_Reporte,reporte_Modificado,id_Tiempo,id_Etapa,id_Gpo_Padre,latitud_Reporte,
			longitud_Reporte,clas_Reporte) 
			VALUES 
			(NULL,$this->id_Gpo_Valores_Reporte,NULL,1,NOW(),$this->id_Usuario,$this->id_Reporte,'$this->titulo_Reporte',
			NOW(),1,2,$this->id_Gpo_Padre,$this->latitud_Reporte,$this->longitud_Reporte,$this->clas_Reporte)";
            if ($this->db()->query($query))
                return true;
            else
                return false;
        }
    }


    /*--- REPORTE: MODIFCAR TITULO ---*/
    public function modificarTituloReporteLlenado()
    {
        $query = "UPDATE Reportes_Llenados SET 
		titulo_Reporte = '$this->titulo_Reporte',
		reporte_Modificado = NOW(),
		id_Gpo_Padre = $this->id_Gpo_Padre,
		latitud_Reporte = $this->latitud_Reporte,
			longitud_Reporte = $this->longitud_Reporte
		WHERE id_Gpo_Valores_Reporte = $this->id_Gpo_Valores_Reporte";
        $save = $this->db()->query($query);
        return $query;
    }


    /*--- REPORTE: INSERTAR TITULO ---*/
    /*public function insertarrTituloReporteLlenado(){
        $query="INSERT INTO Reportes_Llenados
        (titulo_Reporte, fecha_registro, reporte_Modificado)
        VALUES ('$this->titulo_Reporte', NOW() NOW())";
        $save=$this->db()->query($query);
        $mensaje = "";

    }*/

    /*--- REPORTE: MODIFCAR TITULO ---*/
    public function modificarFechaReporteLlenado()
    {
        $query = "UPDATE Reportes_Llenados SET 
		reporte_Modificado = NOW()
		WHERE id_Gpo_Valores_Reporte = $this->id_Gpo_Valores_Reporte";
        $save = $this->db()->query($query);
        $mensaje = "";

    }


    /*--- REPORTE: MODIFCAR ETAPA ---*/
    public function modificarEtapaReporteLlenado()
    {
        $query = "UPDATE Reportes_Llenados SET 
	  id_Etapa = $this->id_Etapa
		WHERE id_Gpo_Valores_Reporte = $this->id_Gpo_Valores_Reporte";
        $save = $this->db()->query($query);
        $mensaje = "";

    }

}

?>
