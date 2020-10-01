<?php

class PrincipalController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->url = $_SERVER["SERVER_NAME"];
    }

    /*--- VISTA PRINCIPAL ---*/
    public function index()
    {
        $url = $_SERVER["REQUEST_URL"];

        $url = substr($url, 73);
        if (!isset($_SESSION[AUTENTICADO_SUPERVISOR]) &&
            (!isset($_SESSION[NOMBRE_USUARIO_SUPERVISOR]))) {
            $mensaje = $_GET['mensaje'];
            header("Location: index.php?controller=Usuarios&action=login&mensaje=$mensaje");
        } else {

            $proyecto = new Proyecto($this->adapter);
            $allproyectos = $proyecto->getAllProyectosByUser($_SESSION[ID_USUARIO_SUPERVISOR]);

            //echo 'idUser: ' . $_SESSION[ID_USUARIO_SUPERVISOR];
            //echo '<br>';
            //echo 'Proyectos:';
            //print_r($allproyectos);

            $_SESSION[PROYECTOS_SUPERVISOR] = $allproyectos;

            if ($url == "index.php?controller=Usuarios&action=login") {
                $this->redirect("SeguimientosReporte", "index");
            } elseif ($url == "index.php?controller=Usuarios&action=login&mensaje=Ingrese%20sus%20datos%20de%20logueo") {
                $this->redirect("SeguimientosReporte", "index");
            } elseif ($url == "index.php?controller=Usuarios&action=login&mensaje=") {
                $this->redirect("SeguimientosReporte", "index");
            } elseif ($url == "") {
                $this->redirect("SeguimientosReporte", "index");
            } else {
                header("Location: " . $url . "");
            }
        }

    }

    public function getDatosProyectos()
    {
        $proyecto = new Proyecto($this->adapter);
        $allproyectos = $proyecto->getAllProyectosByUser($_SESSION[ID_USUARIO_SUPERVISOR]);

        echo json_encode($allproyectos);
    }

    /*--- VISTA PRINCIPAL ---*/
    public function busqueda()
    {
        $mensaje = "<i class='fa fa-search' aria-hidden='true'></i> Buscador";
        $reportesllenados = new ReporteLlenado($this->adapter);
        $allreportesllenados = $reportesllenados->getAllReportesLlenados();

        $reporte = new Reporte($this->adapter);
        $allreportes = $reporte->getAllReportesBusqueda($_SESSION[ID_PROYECTO_SUPERVISOR]);

        $estatus = new Cat_Status($this->adapter);
        $allestatus = $estatus->getAllEstatus();

        $this->view("index", array(
            "allreportes" => $allreportes, "allreportesllenados" => $allreportesllenados, "allestatus" => $allestatus,
            "mensaje" => $mensaje
        ));

    }

    /*--- METODO INICIAR SESION ---*/
    public function logueo()
    {
        $carpeta = $_SESSION[CARPETA_SUPERVISOR];

        $correo = $_POST['correo'];
        $pwd = $_POST['pwd'];

        if (!empty($correo) && !empty($pwd)) {
            $usuario = new Usuario($this->adapter);
            $logueo_user = $usuario->LogUser($correo, $pwd);

            if ($logueo_user != 0) {
                if (!empty($carpeta)) {
                    echo json_encode([
                        'ruta' => "https://{$this->url}/supervisor/{$carpeta}/index.php?controller=Principal&action=index"
                    ]);
                } else {
                    echo json_encode([
                        'ruta' => "https://{$this->url}"
                    ]);
                }
            } else {
                echo json_encode([
                    'ruta' => "https://{$this->url}"
                ]);
            }
        } else {
            header("Location: https://{$this->url}");
        }
    }

}
