<?php
class MovilsController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function getUsuariosMovil()
    {
        $usuario = new Usuario($this->adapter);
        $allusers = $usuario->getAllUser();
        if(is_array($allusers) || is_object($allusers)){
        $datos['estado'] = 1;
        $datos['datos'] = $allusers;
        print json_encode($datos);
        }else{
        $datos['estado'] = 1;
        $datos['mensaje'] = "Error de consulta";
        print json_encode($datos);
        }
        
    }

    public function getCatReportes()
    {
        $id_Proyecto = $_GET['id_Proyecto'];
        $reporte = new Reporte($this->adapter);
        $allReportes = $reporte->getAllReporte($id_Proyecto);
        if(is_array($allReportes) || is_object($allReportes)){
        $datos['estado'] = 1;
        $datos['datos'] = $allReportes;
        print json_encode($datos);
        }else{
        $datos['estado'] = 1;
        $datos['mensaje'] = "Error de consulta";
        print json_encode($datos);
        }
        
    }

    public function getCamposReportes()
    {
        $campoReporte = new CampoReporte($this->adapter);
        $allCamposReportes = $campoReporte->getAllCampoReporte();
        if(is_array($allCamposReportes) || is_object($allCamposReportes)){
        $datos['estado'] = 1;
        $datos['datos'] = $allCamposReportes;
        print json_encode($datos);
        }else{
        $datos['estado'] = 1;
        $datos['mensaje'] = "Error de consulta";
        print json_encode($datos);
        }
        
    }

    public function getCatCamposReportes()
    {
        $catCampos = new Campo($this->adapter);
        $allCatCampos = $catCampos->getAllCampo();
        if(is_array($allCatCampos) || is_object($allCatCampos)){
        $datos['estado'] = 1;
        $datos['datos'] = $allCatCampos;
        print json_encode($datos);
        }else{
        $datos['estado'] = 1;
        $datos['mensaje'] = "Error de consulta";
        print json_encode($datos);
        }
        
    }

}
?>