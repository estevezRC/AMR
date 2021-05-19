<?php

/**
 * Class MAPASCONTROLLER
 */
class MapasController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public $estructura;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $this->url = $_SERVER["REQUEST_URI"];
        $this->estructura = [];

        require_once AUTOLOAD;
    }

    public function index()
    {
        $this->view("index", array());
    }

    public function reportes()
    {
        header('Cache-Control: no-cache'); //no cache

        $this->view("index", []);
    }

    public function getAllReports() {
        $id_Usuario = $_POST['id_UsuarioReportes'];

        $fechaInicio = $_POST['fechainicio'];
        $fechaFinal = $_POST['fechafin'];
        $tipoReport = $_REQUEST['tipoReporte'];

        $fecha = '';

        if ($fechaInicio && $fechaFinal) {
            $fecha = " AND RES.fecha_registro >= '{$fechaInicio} 00:00:00' AND RES.fecha_registro <= '{$fechaFinal} 23:59:59'";
        }

        $seguimientoreporte = new ReporteLlenado($this->adapter);
        if (!$id_Usuario) {
            $id_Usuario = 0;
            // OBTENER USUARIOS QUE HAN LLENADO REPORTES
            $allUsers = $seguimientoreporte->getAllUserLlenadosReportes($this->id_Proyecto_constant);
            if ($allUsers) {
                $idsUsers = array();
                foreach ($allUsers as $user) {
                    $idsUsers[] = $user->id_Usuario;
                }
                $id_Usuario = implode(',', $idsUsers);
            }
        }

        // OBTENER TODAS LAS UBICACIONES DE LOS REPORTES LLENADOS
        $ubicaciones = $seguimientoreporte->getUbicacionesmapaReportes($this->id_Proyecto_constant, $id_Usuario, $fecha,$tipoReport);


        $arraUbicaciones = [];


        foreach ($ubicaciones as $ubicacion) {

            switch ($ubicacion->nombre_Reporte) {
                case('Sistema'):
                    $icon = "sistema";
                    break;
                case('Ubicación ITS'):
                    $icon = "its";
                    break;
                case('Oficina'):
                    $icon = "oficina";
                    break;
                default:
                    $icon = "reportes";
                    break;
            }

            $arraUbicaciones[] = [
                'latlng' => [$ubicacion->longitud_Reporte, $ubicacion->latitud_Reporte],
                'description' => "<strong> " . $ubicacion->titulo_Reporte . "</strong> &nbsp; <a target='_blank' href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=$ubicacion->id_Gpo_Valores_Reporte&Id_Reporte=$ubicacion->id_Reporte'> <i class='fa fa-search'></i> </a> <br>" .
                    $ubicacion->nombre_Reporte . "<br>" .
                    $this->formatearFecha($ubicacion->fecha_registro) . " " . $this->formatearHora($ubicacion->fecha_registro) . "<br>" .
                    $ubicacion->nombre_Usuario . " " . $ubicacion->apellido_Usuario,
                "icon" => "" . $icon . ""
            ];
        }

        echo json_encode($arraUbicaciones);
    }

    public function getAllUbicaciones() {
        $id_Usuario = $_POST['id_UsuarioReportes'];

        $fechaInicio = $_POST['fechainicio'];
        $fechaFinal = $_POST['fechafin'];
        $tipoReport = $_REQUEST['tipoReporte'];



        $fecha = '';

        if ($fechaInicio && $fechaFinal) {
            $fecha = " AND RES.fecha_registro >= '{$fechaInicio} 00:00:00' AND RES.fecha_registro <= '{$fechaFinal} 23:59:59'";
        }

        $seguimientoreporte = new ReporteLlenado($this->adapter);
        if (!$id_Usuario) {
            $id_Usuario = 0;
            // OBTENER USUARIOS QUE HAN LLENADO REPORTES
            $allUsers = $seguimientoreporte->getAllUserLlenadosReportes($this->id_Proyecto_constant);
            if ($allUsers) {
                $idsUsers = array();
                foreach ($allUsers as $user) {
                    $idsUsers[] = $user->id_Usuario;
                }
                $id_Usuario = implode(',', $idsUsers);
            }
        }


        // OBTENER TODAS LAS UBICACIONES DE LOS REPORTES LLENADOS
        $ubicaciones = $seguimientoreporte->getUbicacionesmapaReportes($this->id_Proyecto_constant, $id_Usuario, $fecha, $tipoReport);


        $arraUbicaciones = [];


        foreach ($ubicaciones as $ubicacion) {

            switch ($ubicacion->nombre_Reporte) {
                case('Sistema'):
                        switch ($ubicacion->tipoSistema) {
                            case('PTZ'):
                                $icon = "ptz";
                                break;
                            case('DO'):
                                $icon = "adosamiento";
                                break;
                            case('COPLE'):
                                $icon = "cople";
                                break;
                            case('REGISTRO FO'):
                                $icon = "registrofo";
                                break;
                            default:
                                $icon = "sistema";
                        }

                    break;
                case('Ubicación ITS'):
                    $icon = "its";
                    break;
                case('Oficina'):
                    $icon = "oficina";
                    break;
                default:
                    $icon = "reportes";
                    break;
            }

            $arraUbicaciones[] = [
                'latlng' => [$ubicacion->longitud_Reporte, $ubicacion->latitud_Reporte],
                'description' => "<strong> " . $ubicacion->titulo_Reporte . "</strong> &nbsp; <a target='_blank' href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=$ubicacion->id_Gpo_Valores_Reporte&Id_Reporte=$ubicacion->id_Reporte'> <i class='fa fa-search'></i> </a> <br><br>" .
                    $ubicacion->nombre_Reporte . "<br> <br>" .
                    $this->formatearFecha($ubicacion->fecha_registro) . " " . $this->formatearHora($ubicacion->fecha_registro) . "<br> <br>" .
                    $ubicacion->nombre_Usuario . " " . $ubicacion->apellido_Usuario . "<br> <br>" .
                    "<table style='width: 100%'><tr><td align='center'><img src='img/leaflet/Thales_Logo.png' class='imgThales'></td></tr></table>",
                "icon" => "" . $icon . ""
            ];
        }

        echo json_encode($arraUbicaciones);
    }
}

?>