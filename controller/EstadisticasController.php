<?php


class EstadisticasController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
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

}
