<?php


class EstadisticasController extends ControladorBase
{
    public $conectar;
    public $adapter;
    private $connectorDB;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $this->connectorDB = new EntidadBase('', $this->adapter);
    }

    // FUNCION PARA MOSTRAR LOS DATOS A NIVEL EMPRESA
    public function index()
    {
        $mensaje = '<i class="fas fa-table"></i> Estadisticas';

        $this->view("index", array(
            'mensaje' => $mensaje
        ));
    }

    // FUNCION PARA MOSTRAR LOS DATOS A NIVEL PROYECTO
    public function index2()
    {
        $mensaje = '<i class="fas fa-table"></i> Estadisticas';

        $datos = new ReporteLlenado($this->adapter);

        if ($this->id_Proyecto_constant == 1)
            $arrayConfiguraciones = [290, 291, 292, 293, 303, 294, 295];
        elseif ($this->id_Proyecto_constant == 2)
            $arrayConfiguraciones = [290, 291, 292, 293, 303, 294, 295];
        else
            $arrayConfiguraciones = [290, 291, 292, 293, 303, 294, 295];

        $registros = $datos->getAllDatosInventario($arrayConfiguraciones);

        $this->view("index", array(
            'mensaje' => $mensaje, 'registros' => $registros
        ));
    }

    public function estadisticas()
    {
        $mensaje = '<i class="fas fa-table"></i> Estadisticas';
        if ($this->id_Proyecto_constant == 1)
            $id_Reportes = "41,57,68,78,84,90,96";
        if ($this->id_Proyecto_constant == 2)
            $id_Reportes = "68";
        if ($this->id_Proyecto_constant == 3)
            $id_Reportes = "78";
        if ($this->id_Proyecto_constant == 4)
            $id_Reportes = "84";
        if ($this->id_Proyecto_constant == 5)
            $id_Reportes = "90";
        if ($this->id_Proyecto_constant == 6)
            $id_Reportes = "96";
        if ($this->id_Proyecto_constant == 8)
            $id_Reportes = "57";

        $estadisticas = $this->connectorDB->getEstadisticasReportes($id_Reportes);

        $this->view("index", array(
            'mensaje' => $mensaje, 'estadisticas' => $estadisticas
        ));
    }

}
