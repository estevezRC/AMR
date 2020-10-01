<?php

class Asistencia extends EntidadBase
{
    private $id_asistencia;
    private $fecha;
    private $hora;
    private $turno;
    private $proyecto_asignado;
    private $id_gpo_valores_reporte;
    private $id_emp;


    public function __construct($adapter)
    {
        $table = "asistencia";
        parent::__construct($table, $adapter);
    }


    // id_asistencia
    public function getIdAsistencia()
    {
        return $this->id_asistencia;
    }

    public function setIdAsistencia($id_asistencia)
    {
        $this->id_asistencia = $id_asistencia;
    }


    // fecha
    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }


    // hora
    public function getHora()
    {
        return $this->hora;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }


    // turno
    public function getTurno()
    {
        return $this->turno;
    }

    public function setTurno($turno)
    {
        $this->turno = $turno;
    }


    // proyecto_asignado
    public function getProyectoAsignado()
    {
        return $this->proyecto_asignado;
    }

    public function setProyectoAsignado($proyecto_asignado)
    {
        $this->proyecto_asignado = $proyecto_asignado;
    }


    // id_Gpo_Valores_Reporte
    public function getIdGpoValoresReporte()
    {
        return $this->id_gpo_valores_reporte;
    }

    public function setIdGpoValoresReporte($id_gpo_valores_reporte)
    {
        $this->id_gpo_valores_reporte = $id_gpo_valores_reporte;
    }


    // id_emp
    public function getIdEmp()
    {
        return $this->id_emp;
    }

    public function setIdEmp($id_emp)
    {
        $this->id_emp = $id_emp;
    }


    // ****************************************** INSERTAR DATOS ASISTENCIA ********************************************
    public function saveNewAsistencia()
    {
        $query = "INSERT INTO asistencia (fecha, hora, incidencia, proyecto_asignado, fecha_registro, 
            id_gpo_valores_reporte, id_status, id_emp) 
            VALUES ('$this->fecha', '$this->hora', '$this->turno', $this->proyecto_asignado, now(), 
            $this->id_gpo_valores_reporte, 1, $this->id_emp)";

        return $this->db()->query($query);
    }

    public function updateAsistencia($status)
    {
        $query = "UPDATE asistencia SET hora = '$this->hora', id_status = $status, incidencia = '$this->turno',
                    proyecto_asignado = $this->proyecto_asignado WHERE id_gpo_valores_reporte = $this->id_gpo_valores_reporte 
                    AND id_emp = $this->id_emp AND fecha = '$this->fecha'";
        return $this->db()->query($query);
    }

    public function modificarStatusAsistencia($status)
    {
        $query = "UPDATE asistencia SET id_status = $status WHERE id_gpo_valores_reporte = $this->id_gpo_valores_reporte 
                    AND id_emp = $this->id_emp AND fecha = '$this->fecha'";
        return $this->db()->query($query);
    }

    public function updateRegisterHoraSalida($valor) {
        $query = "UPDATE asistencia SET hora_salida = '$valor' WHERE id_gpo_valores_reporte = $this->id_gpo_valores_reporte 
                    AND id_emp = $this->id_emp";
        return $this->db()->query($query);
    }


}

