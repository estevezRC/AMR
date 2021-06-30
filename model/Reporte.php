<?php

class Reporte extends EntidadBase
{
    private $id_Reporte;
    private $id_Proyecto;
    private $nombre_Reporte;
    private $descripcion_Reporte;
    private $id_Status_Reporte;
    private $Areas;
    private $tiempo_Reporte;
    private $tiempo_Alarma;
    private $tiempo_Revision;
    private $tipo_Reporte;
    private $Perfiles;
    private $id_Seguimiento;

    private $id_video;
    private $titulo;
    private $ruta_video;
    private $descripcion;
    private $palabrasClave;
    private $clasificacion;
    private $plataforma;
    private $version;
    private $status;



    public function __construct($adapter)
    {
        $table = "Cat_Reportes";
        parent::__construct($table, $adapter);
    }


    //ID
    public function get_id_Reporte()
    {
        return $this->id_Reporte;
    }

    public function set_id_Reporte($id_Reporte)
    {
        $this->id_Reporte = $id_Reporte;
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


    //NOMBRE
    public function get_nombre_Reporte()
    {
        return $this->nombre_Reporte;
    }

    public function set_nombre_Reporte($nombre_Reporte)
    {
        $this->nombre_Reporte = $nombre_Reporte;
    }


    //DESCRIPCION
    public function get_descripcion_Reporte()
    {
        return $this->descripcion_Reporte;
    }

    public function set_descripcion_Reporte($descripcion_Reporte)
    {
        $this->descripcion_Reporte = $descripcion_Reporte;
    }


    //ESTATUS
    public function get_id_Status_Reporte()
    {
        return $this->id_Status_Reporte;
    }

    public function set_id_Status_Reporte($id_Status_Reporte)
    {
        $this->id_Status_Reporte = $id_Status_Reporte;
    }


    //AREAS
    public function get_Areas()
    {
        return $this->Areas;
    }

    public function set_Areas($Areas)
    {
        $this->Areas = $Areas;
    }


    //Perfiles
    public function get_Perfiles()
    {
        return $this->Perfiles;
    }

    public function set_Perfiles($perfiles)
    {
        $this->Perfiles = $perfiles;
    }


    //TIEMPO REPORTE
    public function get_tiempo_Reporte()
    {
        return $this->tiempo_Reporte;
    }

    public function set_tiempo_Reporte($tiempo_Reporte)
    {
        $this->tiempo_Reporte = $tiempo_Reporte;
    }


    //TIEMPO ALARMA
    public function get_tiempo_Alarma()
    {
        return $this->tiempo_Alarma;
    }

    public function set_tiempo_Alarma($tiempo_Alarma)
    {
        $this->tiempo_Alarma = $tiempo_Alarma;
    }


    //TIEMPO REVISION
    public function get_tiempo_Revsion()
    {
        return $this->tiempo_Revision;
    }

    public function set_tiempo_Revision($tiempo_Revision)
    {
        $this->tiempo_Revision = $tiempo_Revision;
    }


    //TIPO REPORTE
    public function get_tipo_Reporte()
    {
        return $this->tipo_Reporte;
    }

    public function set_tipo_Reporte($tipo_Reporte)
    {
        $this->tipo_Reporte = $tipo_Reporte;
    }


    //id_Seguimiento
    public function get_id_Seguimiento()
    {
        return $this->id_Seguimiento;
    }

    public function set_id_Seguimiento($id_Seguimiento)
    {
        $this->id_Seguimiento = $id_Seguimiento;
    }


    //////////////////////////////////////////////////////////////////////////////////
    /// CARGAR VIDEO DE MANUALES
    //////////////////////////////////////////////////////////////////////////////////

    public function setIdVideo($id_video): Reporte
    {
        $this->id_video = $id_video;
        return $this;
    }

    public function setTitulo($titulo): Reporte
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function setRutaVideo($ruta_video): Reporte
    {
        $this->ruta_video = $ruta_video;
        return $this;
    }

    public function setDescripcion($descripcion): Reporte
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function setPalabrasClave($palabrasClave): Reporte
    {
        $this->palabrasClave = $palabrasClave;
        return $this;
    }

    public function setClasificacion($clasificacion): Reporte
    {
        $this->clasificacion = $clasificacion;
        return $this;
    }

    public function setPlataforma($plataforma): Reporte
    {
        $this->plataforma = $plataforma;
        return $this;
    }

    public function setVersion($version): Reporte
    {
        $this->version = $version;
        return $this;
    }

    public function setStatus($status): Reporte
    {
        $this->status = $status;
        return $this;
    }


    /*-------------------------------------------- REPORTES: REGISTRAR REPORTE ---------------------------------------*/
    public function saveNewReporte($reportes)
    {
        $row_cnt = 1;
        $nombres = "";
        if (is_array($reportes) || is_object($reportes)) {
            foreach ($reportes as $reporte) {
                if ($reporte->nombre_Reporte == $this->nombre_Reporte) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El reporte " . $this->nombre_Reporte . " ya existe";
            return 2;
        } else {
            $query = "CALL sp_Add_uP_Reportes(NULL,$this->id_Proyecto,'$this->nombre_Reporte','$this->descripcion_Reporte',1,
			'$this->Areas',$this->tiempo_Reporte,$this->tiempo_Alarma,$this->tiempo_Revision,
			$this->tipo_Reporte,'$this->Perfiles',$this->id_Seguimiento,'Insertar')";
            $save = $this->db()->query($query);
            /*$mensaje = $this->nombre_Reporte." / ".$this->descripcion_Reporte." / ".$this->Areas." / ".
                $this->tiempo_Reporte." / ".$this->tiempo_Alarma." / ".$this->tiempo_Revision." / ".$this->incidencia_Reporte;*/
            $mensaje = "Se ha guardado el elemento";
            return 1;
        }
    }


    /*------------------------------------------- REPORTES: ACTUALIZAR REPORTE POR ID ---------------------------------*/
    public function modificarReporte($id, $reportes)
    {
        $row_cnt = 1;
        foreach ($reportes as $reporte) {
            if (($reporte->nombre_Reporte == $this->nombre_Reporte) && ($reporte->id_Reporte != $id)) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "El reporte " . $this->nombre_Reporte . " ya existe";
            return 2;
        } else {
            $query = "CALL sp_Add_uP_Reportes($id,NULL,'$this->nombre_Reporte','$this->descripcion_Reporte',NULL,
			'$this->Areas',$this->tiempo_Reporte,$this->tiempo_Alarma,$this->tiempo_Revision,
			$this->tipo_Reporte,'$this->Perfiles',$this->id_Seguimiento,'Modificar')";
            $save = $this->db()->query($query);
            /*$mensaje = $id." / ".$this->nombre_Reporte." / ".$this->descripcion_Reporte." / ".$this->Areas." / ".
                $this->tiempo_Reporte." / ".$this->tiempo_Alarma." / ".$this->tiempo_Revision." / ".$this->incidencia_Reporte;*/
            $mensaje = "Se ha modificado el elemento";
            return 3;
        }
    }

    public function saveNewVideoManual()
    {
        $query = "INSERT INTO videos_manuales (titulo, ruta_video, descripcion, palabras_clave, clasificacion, plataforma, version, status) 
            VALUES ('$this->titulo', '$this->ruta_video', '$this->descripcion', '$this->palabrasClave', '$this->clasificacion', 
            '$this->plataforma', '$this->version', '1')";
        if ($this->db()->query($query))
            return true;
        else
            return false;

    }


    public function editDataVideo() {
        $query = "UPDATE videos_manuales SET titulo = '$this->titulo', descripcion = '$this->descripcion', 
            palabras_clave = '$this->palabrasClave', clasificacion = '$this->clasificacion', plataforma = '$this->plataforma', 
            version = '$this->version' WHERE id = '$this->id_video'";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }
}

?>
