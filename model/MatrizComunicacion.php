<?php

class MatrizComunicacion extends EntidadBase
{
    private $mat_Id;
    private $mat_Id_Usuario;
    private $mat_Id_Reporte;
    private $mat_Id_Proyecto;
    private $mat_Correo;
    private $mat_Telegram;
    private $mat_Whatsapp;
    private $mat_Push;

    public function __construct($adapter)
    {
        $table = "Matriz_Comunicacion";
        parent::__construct($table, $adapter);
    }

//ID
    public function get_mat_Id()
    {
        return $this->mat_Id;
    }

    public function set_mat_Id($mat_Id)
    {
        $this->mat_Id = $mat_Id;
    }

//USUARIO
    public function get_mat_Id_Usuario()
    {
        return $this->mat_Id_Usuario;
    }

    public function set_mat_Id_Usuario($mat_Id_Usuario)
    {
        $this->mat_Id_Usuario = $mat_Id_Usuario;
    }

//REPORTE
    public function get_mat_Id_Reporte()
    {
        return $this->mat_Id_Reporte;
    }

    public function set_mat_Id_Reporte($mat_Id_Reporte)
    {
        $this->mat_Id_Reporte = $mat_Id_Reporte;
    }

    //PROYECTO
    public function get_mat_Id_Proyecto()
    {
        return $this->mat_Id_Proyecto;
    }

    public function set_mat_Id_Proyecto($mat_Id_Proyecto)
    {
        $this->mat_Id_Proyecto = $mat_Id_Proyecto;
    }

//CORREO
    public function get_mat_Correo()
    {
        return $this->mat_Correo;
    }

    public function set_mat_Correo($mat_Correo)
    {
        $this->mat_Correo = $mat_Correo;
    }

//TELEGRAM
    public function get_mat_Telegram()
    {
        return $this->mat_Telegram;
    }

    public function set_mat_Telegram($mat_Telegram)
    {
        $this->mat_Telegram = $mat_Telegram;
    }

    //WHATSAPP
    public function get_mat_Whatsapp()
    {
        return $this->mat_Whatsapp;
    }

    public function set_mat_Whatsapp($mat_Whatsapp)
    {
        $this->mat_Whatsapp = $mat_Whatsapp;
    }

    //WHATSAPP
    public function get_mat_Push()
    {
        return $this->mat_Push;
    }

    public function set_mat_Push($mat_Push)
    {
        $this->mat_Push = $mat_Push;
    }

    /* ------------------------------------------------GUARDAR NUEVO-------------------------------------------------*/
    public function saveNewMatriz($allmatriz)
    {
        $row_cnt = 1;
        if (is_array($allmatriz) || is_object($allmatriz)) {
            foreach ($allmatriz as $matriz) {
                //areaid
                if ($matriz->mat_Id_Usuario == $this->mat_Id_Usuario && $matriz->mat_Id_Reporte == $this->mat_Id_Reporte && $matriz->mat_Id_Proyecto == $this->mat_Id_Proyecto) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "Ya se configuro previamente el usuario y el reporte";
            return 2;
        } else {
            $query = "CALL sp_Add_Up_MatrizComunicacion(
            NULL,
            $this->mat_Id_Usuario,
            $this->mat_Id_Reporte,
            $this->mat_Id_Proyecto,
            '$this->mat_Correo',
            $this->mat_Telegram,
            $this->mat_Whatsapp,
            $this->mat_Push,
            'Insertar')";
            $save = $this->db()->query($query);
            $mensaje = "Se ha creado la configuración correctamente";
            //$this->db()->error;
            return 1;
        }
    }


    /* ----------------------------------------------GUARDAR MODIFICAION---------------------------------------------*/
    public function saveModificaMatriz($allmatriz)
    {
        $row_cnt = 1;
        if (is_array($allmatriz) || is_object($allmatriz)) {
            foreach ($allmatriz as $matriz) {
                if ($matriz->mat_Id_Usuario == $this->mat_Id_Usuario
                    && $matriz->mat_Id_Reporte == $this->mat_Id_Reporte
                    && $matriz->mat_Id_Proyecto == $this->mat_Id_Proyecto
                    && $matriz->mat_Id != $this->mat_Id) {
                    $row_cnt = $row_cnt + 1;
                }
            }
        }
        if ($row_cnt > 1) {
            $mensaje = "Ya se configuro previamente el usuario y el reporte";
            return 2;
        } else {
            $query = "UPDATE Matriz_Comunicacion
            SET mat_Id_Usuario = $this->mat_Id_Usuario,
                mat_Id_Reporte = $this->mat_Id_Reporte,
                mat_Correo = $this->mat_Correo,
                mat_Telegram = $this->mat_Telegram,
                mat_Whatsapp = $this->mat_Whatsapp,
                mat_Push = $this->mat_Push
            WHERE mat_Id = $this->mat_Id";
            $this->db()->query($query);
            return 3;
        }

    }


    /* ----------------------------------------------GUARDAR MODIFICAION---------------------------------------------*/
    public function changeStatus_C_T_P()
    {
            $query = "UPDATE Matriz_Comunicacion
            SET mat_Correo = 0,
                mat_Telegram = 0,
                mat_Push = 0
            WHERE mat_Id = $this->mat_Id";
            $this->db()->query($query);
    }

}
