<?php

class EstructuraProcesos extends EntidadBase
{

private $id_Proceso_Estructura;
private $id_Gpo_Valores_Padre;
private $id_Reporte_Padre;
private $Cantidad;
private $Porcentaje;


    public function __construct($adapter)
    {
        $table = "Proceso_Estructura";
        parent::__construct($table, $adapter);
    }

    //id_Proceso_Estructura
    public function get_id_Proceso_Estructura()
    {
        return $this->id_Proceso_Estructura;
    }

    public function set_id_Proceso_Estructura($id_Proceso_Estructura)
    {
        $this->id_Proceso_Estructura = $id_Proceso_Estructura;
    }


    //id_Gpo_Valores_Padre
    public function get_id_Gpo_Valores_Padre()
    {
        return $this->id_Gpo_Valores_Padre;
    }

    public function set_id_Gpo_Valores_Padre($id_Gpo_Valores_Padre)
    {
        $this->id_Gpo_Valores_Padre = $id_Gpo_Valores_Padre;
    }


    //id_Reporte_Padre
    public function get_id_Reporte_Padre()
    {
        return $this->id_Reporte_Padre;
    }

    public function set_id_Reporte_Padre($id_Reporte_Padre)
    {
        $this->id_Reporte_Padre = $id_Reporte_Padre;
    }


    //Porcentaje
    public function get_Porcentaje()
    {
        return $this->Porcentaje;
    }

    public function set_Porcentaje($Porcentaje)
    {
        $this->Porcentaje = $Porcentaje;
    }

    //Cantidad
    public function get_Cantidad()
    {
        return $this->Cantidad;
    }

    public function set_Cantidad($Cantidad)
    {
        $this->Cantidad = $Cantidad;
    }


    /*--- REGISTRAR NUEVO PROCESO ---*/
    public function saveNewEstructura($estructuraProceso)
    {
        $validate = true;
        foreach ($estructuraProceso as $proceso) {
            if (($proceso->id_Gpo_Valores_Padre == $this->id_Gpo_Valores_Padre) &&
                ($proceso->id_Reporte_Padre == $this->id_Reporte_Padre)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_EstructuraProcesos(NULL, $this->id_Gpo_Valores_Padre, $this->id_Reporte_Padre, $this->Porcentaje, $this->Cantidad, 'Insertar')";
            if ($this->db()->query($query)) {
                return 1;
            }
        } else {
            return 2;
        }
    }

    /*--- USUARIOS: ACTUALIZAR PROCESO POR ID ---*/
    public function modificarEstructura($id_Proceso_Estructura, $estructuraProceso)
    {
        $validate = true;
        foreach ($estructuraProceso as $proceso) {
            if (($proceso->id_Gpo_Valores_Padre == $this->id_Gpo_Valores_Padre) &&
                ($proceso->id_Reporte_Padre == $this->id_Reporte_Padre) &&
                ($id_Proceso_Estructura != $proceso->id_Proceso_Estructura)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_EstructuraProcesos($id_Proceso_Estructura, NULL, $this->id_Reporte_Padre, $this->Porcentaje, $this->Cantidad, 'Modificar')";
            if ($this->db()->query($query)) {
                return 3;
            }
        } else {
            return 2;
        }
    }
}
