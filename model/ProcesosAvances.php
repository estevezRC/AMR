<?php

class ProcesosAvances extends EntidadBase
{

private $Id_Avance_Proceso;
private $Id_Gpo_Valores_Padre;
private $Id_Proceso;
private $Id_Gpo_Valores_Hijo;
private $Fecha_Registro;
private $Id_Status;

    public function __construct($adapter)
    {
        $table = "Procesos_Avances";
        parent::__construct($table, $adapter);
    }

    //Id_Avance_Proceso
    public function get_Id_Avance_Proceso()
    {
        return $this->Id_Avance_Proceso;
    }

    public function set_Id_Avance_Proceso($Id_Avance_Proceso)
    {
        $this->Id_Avance_Proceso = $Id_Avance_Proceso;
    }


    //Id_Gpo_Valores_Padre
    public function get_Id_Gpo_Valores_Padre()
    {
        return $this->Id_Gpo_Valores_Padre;
    }

    public function set_Id_Gpo_Valores_Padre($Id_Gpo_Valores_Padre)
    {
        $this->Id_Gpo_Valores_Padre = $Id_Gpo_Valores_Padre;
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


    //Id_Gpo_Valores_Hijo
    public function get_Id_Gpo_Valores_Hijo()
    {
        return $this->Id_Gpo_Valores_Hijo;
    }

    public function set_Id_Gpo_Valores_Hijo($Id_Gpo_Valores_Hijo)
    {
        $this->Id_Gpo_Valores_Hijo = $Id_Gpo_Valores_Hijo;
    }



    /*--- REGISTRAR NUEVO PROCESO_AVANCES ---*/
    public function saveProcesoAvance() {
        $query = "INSERT INTO Procesos_Avances (
            Id_Gpo_Valores_Padre, Id_Proceso, Id_Gpo_Valores_Hijo, Fecha_Registro, Id_Status) 
        VALUES ($this->Id_Gpo_Valores_Padre, $this->Id_Proceso, $this->Id_Gpo_Valores_Hijo, NOW(), 1)";
        $this->db()->query($query);
    }

    /*--- USUARIOS: ACTUALIZAR PROCESOS_AVANCES POR Id_Gpo_Valores_Padre Y Id_Proceso CON Id_Status 1 ---*/
    public function modificarProcesoAvance() {
        $query = "UPDATE Procesos_Avances SET Id_Status = 0 WHERE Id_Gpo_Valores_Padre = $this->Id_Gpo_Valores_Padre AND Id_Proceso = $this->Id_Proceso AND Id_Status = 1";
        $this->db()->query($query);
    }
}
