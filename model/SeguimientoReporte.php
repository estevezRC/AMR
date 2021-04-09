<?php

//require 'lib/PHPMailer-master/PHPMailerAutoload.php';
class SeguimientoReporte extends EntidadBase
{
    private $Id_Registro;
    private $Id_Reporte;
    private $Id_Status;
    private $Fecha;
    private $Id_Usuario;
    private $Notas;
    private $Revisado;

    // DATOS DE ACTUALIZACION  DE GRUPO PADRE A REPORTE
    private $id_Plantilla;
    private $componente;

    public function __construct($adapter)
    {
        $table = "Seguimiento_Reportes";
        parent::__construct($table, $adapter);
    }

    //ID REGISTRO
    public function get_Id_Registro()
    {
        return $this->Id_Registro;
    }

    public function set_Id_Registro($Id_Registro)
    {
        $this->Id_Registro = $Id_Registro;
    }

    //ID REPORTE
    public function get_Id_Reporte()
    {
        return $this->Id_Reporte;
    }

    public function set_Id_Reporte($Id_Reporte)
    {
        $this->Id_Reporte = $Id_Reporte;
    }

    //ID STATUS
    public function get_Id_Status()
    {
        return $this->Id_Status;
    }

    public function set_Id_Status($Id_Status)
    {
        $this->Id_Status = $Id_Status;
    }

    //FECHA
    public function get_Fecha()
    {
        return $this->Fecha;
    }

    public function set_Fecha($Fecha)
    {
        $this->Fecha = $Fecha;
    }

    //ID USUARIO
    public function get_Id_Usuario()
    {
        return $this->Id_Usuario;
    }

    public function set_Id_Usuario($Id_Usuario)
    {
        $this->Id_Usuario = $Id_Usuario;
    }

    //NOTAS
    public function get_Notas()
    {
        return $this->Notas;
    }

    public function set_Notas($Notas)
    {
        $this->Notas = $Notas;
    }

    //REVISADO
    public function get_Revisado()
    {
        return $this->Revisado;
    }

    public function set_Revisado($Revisado)
    {
        $this->Revisado = $Revisado;
    }

    //AREA
    public function get_Id_Area()
    {
        return $this->Id_Area;
    }

    public function set_Id_Area($Id_Area)
    {
        $this->Id_Area = $Id_Area;
    }

    public function set_Id_Plantilla($id_Plantilla) : SeguimientoReporte
    {
        $this->id_Plantilla = $id_Plantilla;
        return $this;
    }

    public function setComponente($componente) : SeguimientoReporte
    {
        $this->componente = $componente;
        return $this;
    }


    /*------------------------------- SEGUIMIENTO REPORTE: REGISTRAR NUEVO SEGUIMIENTO -------------------------------*/
    public function saveNew($grupo_valores, $id_Usuario, $area)
    {
        $row_cnt = 1;
        //SEGUIMIENTO
        $query = "INSERT INTO Seguimiento_Reportes (Id_Reporte, Id_Status, Fecha, Id_Usuario, Revisado, Id_Area) VALUES ($grupo_valores, 2, now(), $id_Usuario, 0, $area)";
        //$query = "CALL sp_Crear_Seguimientos_Alterno($grupo_valores,$id_Usuario,$area,NULL)";
        //$query = "CALL sp_AddData_General($datos_reporte,$email,$area)";
        $save = $this->db()->query($query);
        $valor_vuelta = "1";
        return $valor_vuelta;
    }

    /*------------------------------ SEGUIMIENTO REPORTE: ACTUALIZAR NUEVO SEGUIMIENTO ------------------------------*/
    public function modificar()
    {
        $query = "CALL sp_UpdData_CatGeneral_Alterno($this->Id_Registro,'SeguimientoReportes','$this->Notas',$this->Id_Status,$this->Id_Usuario,$this->Id_Reporte)";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado el seguimiento " . $id . "";
        return $mensaje;
    }

    /*------------------------------ SEGUIMIENTO REPORTE: ACTIVAR NUEVAMENTE ------------------------------*/
    public function activar($id_Gpo_Reporte)
    {
        $query = "UPDATE Seguimiento_Reportes SET Id_Status = 3 WHERE Id_Reporte = $id_Gpo_Reporte";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado el seguimiento con id gpo " . $id_Gpo_Reporte . "";
        return $mensaje;
    }

    /*------------------------------ SEGUIMIENTO REPORTE: REGISTRAR NUEVOS GPO PADRES ------------------------------*/
    public function saveInventariosUbicacion()
    {
        $query1 = "UPDATE Reportes_Llenados SET id_Gpo_Padre = '$this->id_Plantilla' WHERE id_Gpo_Valores_Reporte = '$this->componente'";

        $save = $this->db()->query($query1);
        if ($save)
            return true;
        else
            return false;
    }
}


?>