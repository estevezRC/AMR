<?php

class CampoReporte extends EntidadBase
{
    private $id_Configuracion_Reporte;
    private $id_Proyecto;
    private $id_Reporte;
    private $id_Campo_Reporte;
    private $id_Status_Reporte;
    private $Campo_Necesario;
    private $Secuencia;

    public function __construct($adapter)
    {
        $table = "Conf_Reportes_Campos";
        parent::__construct($table, $adapter);
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

//ID PROYECTO
    public function get_id_Proyecto()
    {
        return $this->id_Proyecto;
    }

    public function set_id_Proyecto($id_Proyecto)
    {
        $this->id_Proyecto = $id_Proyecto;
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

//ID CAMPO REPORTE
    public function get_id_Campo_Reporte()
    {
        return $this->id_Campo_Reporte;
    }

    public function set_id_Campo_Reporte($id_Campo_Reporte)
    {
        $this->id_Campo_Reporte = $id_Campo_Reporte;
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

//CAMPO NECESARIO
    public function get_Campo_Necesario()
    {
        return $this->Campo_Necesario;
    }

    public function set_Campo_Necesario($Campo_Necesario)
    {
        $this->Campo_Necesario = $Campo_Necesario;
    }

//SECUENCIA
    public function get_Secuencia()
    {
        return $this->Secuencia;
    }

    public function set_Secuencia($Secuencia)
    {
        $this->Secuencia = $Secuencia;
    }

    /*------------------------------- CONFIGURACION: REGISTRAR CONFIGURACION -----------------------------------------*/
    public function saveNewConfiguracion($configuraciones, $datoscampo, $datosreporte)
    {
        $row_cnt = 1;
        $nombres = "";
        $campoFile = true;
        if (is_array($configuraciones) || is_object($configuraciones)) {
            foreach ($configuraciones as $configuracion) {

                if (($configuracion->id_Reporte == $this->id_Reporte) && ($configuracion->id_Campo_Reporte == $this->id_Campo_Reporte)) {
                    $row_cnt = $row_cnt + 1;
                    $mensaje ['msg'] = "El reporte ya cuenta con el campo " . $datoscampo->nombre_Campo . "";
                    $mensaje ['idmsg'] = 1;
                }

                if ($configuracion->id_Reporte == $this->id_Reporte && $configuracion->tipo_Reactivo_Campo == 'file' && $datoscampo->tipo_Reactivo_Campo == 'file')
                    $campoFile = false;

                if (($datosreporte->tipo_Reporte == 1) && ($datoscampo->tipo_Reactivo_Campo == "checkbox-incidencia")) {
                    $row_cnt = $row_cnt + 1;
                    $mensaje ['msg'] = "No es necesario configurar notificación a un reporte de incidencia";
                    $mensaje ['idmsg'] = 2;
                }

                if ($datoscampo->tipo_Reactivo_Campo == "checkbox-incidencia" AND
                    $configuracion->tipo_Reactivo_Campo == "checkbox-incidencia") {
                    $row_cnt = $row_cnt + 1;
                    $mensaje ['msg'] = "Ya se ha configurado notificación de incidencia previamente";
                    $mensaje ['idmsg'] = 3;
                }
            }
        }

        if ($campoFile) {
            if ($row_cnt > 1) {
                return 2;
            } else {
                $query = "CALL sp_AddData_General_Conf($this->id_Proyecto,$this->id_Reporte,$this->id_Campo_Reporte,'Conf_Reportes',$this->Campo_Necesario)";
                $save = $this->db()->query($query);
                $mensaje ['msg'] = "Se ha guardado la configuración para el campo " . $datoscampo->nombre_Campo;
                $mensaje ['idmsg'] = 4;
                return 1;
            }
        } else {
            return 3;
        }
    }


    /*------------------------------- CONFIGURACION: REGISTRAR CONFIGURACION -----------------------------------------*/
    public function saveNewConfiguracionPlantilla()
    {
        //$query = "CALL sp_AddData_General_Conf($this->id_Proyecto,$this->id_Reporte,$this->id_Campo_Reporte,'Conf_Reportes',$this->Campo_Necesario)";
        $query = "INSERT INTO Conf_Reportes_Campos (id_Proyecto,
            id_Reporte,
            id_Campo_Reporte,
            id_Status_Reporte,
            Campo_Necesario,
            Secuencia)
            VALUES ($this->id_Proyecto,
            $this->id_Reporte,
            $this->id_Campo_Reporte,
            1,
            $this->Campo_Necesario,
            $this->Secuencia)";
        $save = $this->db()->query($query);
    }

    /*---------------------------------- CONFIGURACION: ACTUALIZAR CONFIGURACION POR ID ------------------------------*/
    public function modificarConfiguracion($id, $secuencia, $necesario)
    {
        $query = "UPDATE Conf_Reportes_Campos SET Secuencia = $secuencia, Campo_Necesario = $necesario WHERE id_Configuracion_Reporte = $id";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado la configuración " . $id . "";
        return $mensaje;
        //}
    }

    /*-------------------------- CONFIGURACIÓN: ACTUALIZA LA CONFIGURACIÓN POR EL SELECT DINÁMICO ------*/
    public function modificarConfiguracionVarios($id, $configuraciones)
    {
        $row_cnt = 1;
        if (is_array($configuraciones) || is_object($configuraciones)) {
            foreach ($configuraciones as $configuracion) {
                if (
                    (($configuracion->id_Reporte == $this->id_Reporte) &&
                        ($configuracion->id_Campo_Reporte == $this->id_Campo_Reporte) &&
                        ($configuracion->id_Configuracion_Reporte == $this->$id)) ||
                    (($configuracion->id_Reporte == $this->id_Reporte) && ($configuracion->Secuencia == $this->Secuencia))
                ) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }

        $query = "CALL sp_UpdData_General_Conf($id,'$this->id_Reporte','$this->id_Campo_Reporte','Conf_Reportes',
		$this->Secuencia)";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado la configuración " . $id . "";
        return $mensaje;
    }

    /*-------------------------- BORRAR TODA LA CONFIGURACIÓN ------*/
    public function borrarConf($id_Reporte)
    {
        $row_cnt = 1;
        $query = "UPDATE Conf_Reportes_Campos SET id_Status_Reporte = 0 WHERE id_Reporte = $id_Reporte";
        $save = $this->db()->query($query);
        $mensaje = "Se ha eliminado la configuracion";
        //return $mensaje;
    }
} ?>
