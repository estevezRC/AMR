<?php

class Procesos extends EntidadBase
{
    private $Id_Proceso;
    private $Id_Reporte_Padre;
    private $Id_Reporte_Hijo;
    private $Porcentaje;

    public function __construct($adapter)
    {
        $table = "Procesos";
        parent::__construct($table, $adapter);
    }

    //Id_Proceso
    public function get_Id_Proceso()
    {
        return $this->Id_Proceso;
    }

    public function set_Id_Proceso($Id_Proceso)
    {
        $this->Id_Proceso = $Id_Proceso;
    }


    //Id_Reporte_Padre
    public function get_Id_Reporte_Padre()
    {
        return $this->Id_Reporte_Padre;
    }

    public function set_Id_Reporte_Padre($Id_Reporte_Padre)
    {
        $this->Id_Reporte_Padre = $Id_Reporte_Padre;
    }


    //Id_Reporte_Hijo
    public function get_Id_Reporte_Hijo()
    {
        return $this->Id_Reporte_Hijo;
    }

    public function set_Id_Reporte_Hijo($Id_Reporte_Hijo)
    {
        $this->Id_Reporte_Hijo = $Id_Reporte_Hijo;
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


    /*--- REGISTRAR NUEVO PROCESO ---*/
    public function saveNewProceso($procesos)
    {
        $validate = true;
        foreach ($procesos as $proceso) {
            if (($proceso->Id_Reporte_Padre == $this->Id_Reporte_Padre) && ($proceso->Id_Reporte_Hijo == $this->Id_Reporte_Hijo)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_Procesos(NULL, $this->Id_Reporte_Padre, $this->Id_Reporte_Hijo, $this->Porcentaje, 'Insertar')";
            if ($this->db()->query($query)) {
                return 1;
            }
        } else {
            return 2;
        }
    }

    /*--- USUARIOS: ACTUALIZAR PROCESO POR ID ---*/
    public function modificarProceso($idProceso, $procesos)
    {
        $validate = true;
        foreach ($procesos as $proceso) {
            //if (($proyecto->nombre_Proyecto == $this->nombre_Proyecto) && ($proyecto->id_Proyecto != $id)) {
            if (($proceso->Id_Reporte_Padre == $this->Id_Reporte_Padre) &&
                ($proceso->Id_Reporte_Hijo == $this->Id_Reporte_Hijo) &&
                ($idProceso != $proceso->Id_Proceso)) {
                $validate = false;
            }
        }

        if ($validate) {
            $query = "CALL sp_Add_Up_Procesos($idProceso, NULL, $this->Id_Reporte_Hijo, $this->Porcentaje, 'Modificar')";
            if ($this->db()->query($query)) {
                return 3;
            }
        } else {
            return 2;
        }
    }
}
