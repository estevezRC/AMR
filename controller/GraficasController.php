<?php

/**
 * Class GraficasController
 */
class GraficasController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public $estructura;
    private $connectorDB;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $this->url = $_SERVER["REQUEST_URI"];
        $this->estructura = [];
        $this->connectorDB = new EntidadBase('', $this->adapter);

        require_once 'vendor/autoload.php';
    }

    public function index()
    {
        //PROYECTO
        /*$id_Proyecto_Actual = (int)$_POST["id_Proyecto"];
        if (!empty($id_Proyecto_Actual)) {
            $proyecto = new Proyecto($this->adapter);
            $datosproyecto = $proyecto->getProyectoById($_SESSION[ID_PROYECTO_SUPERVISOR]);
            $_SESSION[NOMBRE_PROYECTO] = $datosproyecto->nombre_Proyecto;
            session_start();
            $_SESSION[ID_PROYECTO_SUPERVISOR] = $datosproyecto->id_Proyecto;
        }
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];*/


        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-bar-chart' aria-hidden='true'></i> Dashboard ";
        }


        // ************************************* GRAFICA DE USUARIOS ***************************************************
        $allUser = $this->connectorDB->getAllUsuariosReportesTotal($this->id_Proyecto_constant);
        $resul = array();
        $i = 0;
        $n = 0;
        $nuevoResultado = [];
        if (is_array($allUser) || is_object($allUser)) {
            foreach ($allUser as $user) {
                $resul[$i][0] = $user->nombre;
                $resul[$i][1] = $user->total;
                array_push($nuevoResultado, ['user' => $user->nombre, 'reportes' => $user->total]);
                $i++;
            }
        }
        $resulNombre = array();
        if (is_array($allUser) || is_object($allUser)) {
            foreach ($allUser as $username) {
                $resulNombrel[$n][0] = $username->nombre;
                $n++;
            }
        }


        // *************************************** GRAFICA DE REPORTES *************************************************
        $allReportes = $this->connectorDB->getGraficaReportes($this->id_Proyecto_constant);
        //var_dump($allReportes);
        $reportes = [];
        if (is_array($allReportes) || is_object($allReportes)) {
            foreach ($allReportes as $key => $reporte) {
                if ($key < 7) {
                    $arrayreportes[$key][0] = $reporte->nombre_Reporte;
                    $arrayreportes[$key][1] = $reporte->cantidad;
                    array_push($reportes, ['nombre' => $reporte->nombre_Reporte, 'cantidad' => $reporte->cantidad]);
                } else {
                    break;
                }
            }
        }


        // *************************************************************************************************************
        // ***************************** SECCION DE ASIGNACION DE IDS DE REPORTES POR PROYECTO *************************
        if ($this->id_Proyecto_constant == 1) { // PROYECTO Tramo A. Monterrey - Nuevo Laredo
            $idReportesInv = 41;
            $idReportesFO = 4;
        }
        if ($this->id_Proyecto_constant == 2) { // PROYECTO Tramo B. Cadereyta - Reynosa
            $idReportesInv = 68;
            $idReportesFO = 8;
        }
        if ($this->id_Proyecto_constant == 3) { // PROYECTO Tramo C. Libramiento de Reynosa Sur II
            $idReportesInv = 78;
            $idReportesFO = 13;
        }
        if ($this->id_Proyecto_constant == 4) { // PROYECTO Tramo D. Matamoros - Reynosa
            $idReportesInv = 84;
            $idReportesFO = 29;
        }
        if ($this->id_Proyecto_constant == 5) { // PROYECTO Tramo E. Puente Internacional Reynosa - Pharr
            $idReportesInv = 90;
            $idReportesFO = 24;
        }
        if ($this->id_Proyecto_constant == 6) { // PROYECTO Tramo F. Puente internacional Ignacio Zaragoza
            $idReportesInv = 96;
            $idReportesFO = 19;
        }
        if ($this->id_Proyecto_constant == 8) { // PROYECTO Entrenamiento
            $idReportesInv = 57;
            $idReportesFO = 59;
        }
        if ($this->id_Proyecto_constant == 10) { // PROYECTO Administración
            $idReportesInv = '41,68,78,84,90,96,57';
            $idReportesFO = '4,8,13,29,24,19,59';
        }


        // ************************************** DATOS PARA TABLA DE INVENTARIO ***************************************
        $estadisticas = $this->connectorDB->getEstadisticasReportes($idReportesInv);


        // ******************************** DATOS PARA SECCION DE AVANCES DE FO ****************************************
        $resultados = $this->connectorDB->getJsonAvancesFO($idReportesFO);

        $avanceJson = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultados);

        $arrayAvancesFO = (object)[
            'tritubo' => (object)[
                'nombre' => 'Tritubo', 'valor' => 0
            ],
            'pruebas' => (object)[
                'nombre' => 'Pruebas', 'valor' => 0
            ],
            'inmersionFO' => (object)[
                'nombre' => 'Inmersión FO', 'valor' => 0
            ],
            'reposicionAsfalto' => (object)[
                'nombre' => 'Reposición de asfalto', 'valor' => 0
            ],
        ];

        $getValor = function ($arrayValores) {
            $coincidencia = array_filter($arrayValores, function ($element) {
                return $element->idCampo == 36;

            });
            return is_numeric(array_values($coincidencia)[0]->valorCampo) ? array_values($coincidencia)[0]->valorCampo : 0;

        };

        foreach ($avanceJson as $registro) {
            foreach ($registro->Valores as $valor) {
                foreach ($valor->Valor as $opcionesCampos) {
                    if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo')
                        $arrayAvancesFO->tritubo->valor += $getValor($valor->Valor);
                    else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas')
                        $arrayAvancesFO->pruebas->valor += $getValor($valor->Valor);
                    else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO')
                        $arrayAvancesFO->inmersionFO->valor += $getValor($valor->Valor);
                    else if ($opcionesCampos->idCampo->valor == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto')
                        $arrayAvancesFO->reposicionAsfalto->valor += $getValor($valor->Valor);
                }
            }
        }
        //print_r($arrayAvancesFO);
        //die();
        // *************************************************************************************************************


        $this->view("index", array('resul' => $resul, 'resulNombrel' => $resulNombrel, 'allReportes' => $arrayreportes,
            'mensaje' => $mensaje, 'nuevoResul' => $nuevoResultado, 'reportes' => $reportes, 'estadisticas' => $estadisticas,
            'arrayAvancesFO' => $arrayAvancesFO
        ));
    }

    public function avances()
    {
        $calculos = new CalculosCompartidos();
        $avanceActividad = new AvanceActividad($this->adapter);

        # Obtener id del nodo
        $idNodo = $_GET['nodo'] ? $_GET['nodo'] : 0;

        # OBTENER ID_GANTT DEL PROYECTO
        $registroGantt = $avanceActividad->getIdGanttByid_proyecto($this->id_Proyecto_constant);
        $idGantt = $registroGantt[0]->id;

        if (empty($registroGantt)) {

            if ($_GET['id_Gpo_Valores']) {
                $idGpoValores = $_GET['id_Gpo_Valores'];
                $calculos = new CalculosCompartidos();

                $reportes = new ReporteLlenado($this->adapter);

                switch ($idGpoValores) {
                    case 1:
                        $resultado = $calculos->calcularPorcentaje(1);
                        break;
                    case 369:
                        $resultado = $calculos->calcularPorcentajeEdificio(369);
                        break;

                    case 'poste':
                        $resultado = $calculos->calcularPorcentajePostes($this->id_Proyecto_constant);
                        break;
                }

                $plazaPorcentaje = $resultado[0];

                if ($idGpoValores == 'poste') {
                    $tipoReporte = 2;
                    $noReportes = '';
                    $noRepit = '';

                    $children = [$reportes->getAllReportesLlenadosByType($tipoReporte, $this->id_Proyecto_constant, $noReportes, $noRepit), $resultado[1]];
                    //dump($resultado);
                    //$allreportellenado[0]->titulo_Reporte = 'Postes';
                } else {
                    $children = [$reportes->calcularPorcentajeEstructuraGeneral($idGpoValores), $resultado[1]];
                    //OBTENER LOS DATOS DEL REPORTE
                    $llenadoreporte = new LlenadoReporte($this->adapter);
                    $allreportellenado = $llenadoreporte->getReporteLlenadoById($idGpoValores);
                }


                if ($plazaPorcentaje > 100)
                    $porcentaje = 100;
                else
                    $porcentaje = round($plazaPorcentaje, 2, PHP_ROUND_HALF_UP);


                $datosFinales = [
                    ['elemento' => $allreportellenado[0]->titulo_Reporte, 'porcentaje' => $porcentaje],
                    ['elemento' => 'Faltante', 'porcentaje' => 100 - $porcentaje]];

                $datosFinales = json_encode($datosFinales, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

                $this->view("index", [
                    "datosFinales" => $datosFinales, "datosGenerales" => $allreportellenado, "desglose" => $children,
                    "gruposEstructuras" => $resultado[2], "existeGantt" => false
                ]);

            } else {
                $this->redirect("Graficas");
            }

        } else {
            if (!$idNodo) {
                $nodo = $avanceActividad->getRegistroGanttValoresByid_ganttANDid_nodo_Padre($idGantt, $idNodo);
            } else {
                $nodo = $avanceActividad->getRegistroGanttValoresByid_ganttANDid_nodo($idGantt, $idNodo);
            }
            /*
            $subNodos = $avanceActividad->getSubNodos($idNodo);

            $resultado = $calculos->calculo($subNodos, $this->estructura, $nodo[0]->porcentaje); */

            $rutaAcceso = array_map(function ($value) {
                return max((int)$value - 1, 0);
            }, explode(".", $nodo[0]->wbs));

            $ruta = "";
            if (sizeof($rutaAcceso) > 0) {
                foreach ($rutaAcceso as $key => $nivel) {
                    $ruta .= "[{$nivel}]->children";
                }
            }
            // nuevo
            $infoJson = $avanceActividad->getJson($idGantt)[0]->estructura;
            $infoJson = !$infoJson ?: json_decode($infoJson);

            //var_dump("\$test = \$infoJson$ruta;");
            eval("\$extractoEstructura = \$infoJson$ruta;");

            $resultado = $calculos->calculo2($extractoEstructura, $nodo[0]->porcentaje);

            $datosGenerales = (object)((array)$nodo[0] + (array)$resultado);
            $datosFinales = [
                ['elemento' => $nodo[0]->actividad, 'porcentaje' => $resultado->perc_nodo],
                ['elemento' => 'Faltante', 'porcentaje' => 100 - $resultado->perc_nodo]
            ];

            $datosFinales = json_encode($datosFinales, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

            $this->view("index", [
                "datosFinales" => $datosFinales, "datosGenerales" => $datosGenerales, "desglose" => $extractoEstructura,
                "existeGantt" => true
            ]);
        }
    }

    public function avancesDetalle()
    {
        if ($_GET['id_Gpo_Valores']) {
            $idGpoValores = $_GET['id_Gpo_Valores'];
            $calculos = new CalculosCompartidos();
            $reportes = new ReporteLlenado($this->adapter);

            $datos = $reportes->calcularPorcentajeAvanceDetalle($idGpoValores);
            $porcentaje = 0;
            foreach ($datos as $dato) {
                $porcentaje += $dato->Porcentaje1;
            }

            //var_dump($datos, $porcentaje);


            if ($porcentaje > 100)
                $porcentaje = 100;
            else
                $porcentaje = round($porcentaje, 2, PHP_ROUND_HALF_UP);

            //OBTENER LOS DATOS DEL REPORTE
            $llenadoreporte = new LlenadoReporte($this->adapter);
            $allreportellenado = $llenadoreporte->getReporteLlenadoById($idGpoValores);

            $datosFinales = [
                ['elemento' => $allreportellenado[0]->titulo_Reporte, 'porcentaje' => $porcentaje],
                ['elemento' => 'Faltante', 'porcentaje' => 100 - $porcentaje]];

            $datosFinales = json_encode($datosFinales, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

            $this->view("index", ["datosFinales" => $datosFinales, "datosGenerales" => $allreportellenado, "desglose" => $datos]);
        } else {
            $this->redirect("Graficas");
        }
    }

    public function mapa()
    {
        $seguimientoreporte = new ReporteLlenado($this->adapter);
        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
        $area = $_SESSION[ID_AREA_SUPERVISOR];
        $noreportes = '';
        $ubicaciones = $seguimientoreporte->getUbicacionesmapa($_SESSION[ID_PROYECTO_SUPERVISOR]);
        //var_dump($allseguimientosreportes);
        $x = 0;

        $ubis = array();
        foreach ($ubicaciones as $ubicacion) {

            //TRATAR CADENAMIENTO
            /*  $cadenamiento = str_replace(".", "+", $ubicacion->cadenamientoValores);
              $size = strlen($cadenamiento);
              if ($size == 5) {$cadenamiento = "00" . $cadenamiento;}
              if ($size == 6) {$cadenamiento = "0" . $cadenamiento;}*/

            switch ($ubicacion->id_Reporte) {
                case(7):
                    $icon = "pc";
                    break;
                case(8):
                    $icon = "carril";
                    break;
                case(9):
                    $icon = "its";
                    break;
                case(15):
                    $icon = "wc";
                    break;
                case(16):
                    $icon = "restaurant";
                    break;
                case(41):
                    $icon = "area";
                    break;
                default:
                    $icon = "ubicacion";
                    break;
            }

            $book[] = array(
                'type' => 'Feature',
                'properties' => [
                    'description' => "<strong>" . $ubicacion->titulo_Reporte .
                        " <br> " . $ubicacion->nombre_Reporte . "<br>" .
                        "<a href='http://maps.google.com/maps?q=&layer=c&cbll=$ubicacion->latitud_Reporte,$ubicacion->longitud_Reporte&cbp=11,0,0,0,0' target='_blank'> Ver en Google Maps </a>" . "</strong>",
                    "icon" => "" . $icon . ""],
                'geometry' => ['type' => 'Point', 'coordinates' => [$ubicacion->longitud_Reporte, $ubicacion->latitud_Reporte]]
            );
        }

        $this->view("index", array(
            "book" => $book
        ));
    }


    public function getAllUserLlenadosReportes()
    {
        $usuario = new Usuario($this->adapter);
        $allUsers = $usuario->getAllUserLlenadosReportes($this->id_Proyecto_constant);

        echo json_encode($allUsers);
    }

    public function mapaReportes()
    {
        //********************* QUITAR ERROR err_cache_miss "REENVIO DEL FORMULARIO" ***********************************
        header('Cache-Control: no-cache'); //no cache
        session_start();

        $id_Usuario = $_POST['id_UsuarioReportes'];

        $fechaInicio = $_POST['fechainicio'];
        $fechaFinal = $_POST['fechafin'];

        if ($fechaInicio && $fechaFinal) {
            //$fecha = "HAVING fecha_registro BETWEEN '" . $fechaInicio . "' AND '" . $fechaFinal . "'";
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
        $ubicaciones = $seguimientoreporte->getUbicacionesmapaReportes($this->id_Proyecto_constant, $id_Usuario, $fecha);

        foreach ($ubicaciones as $ubicacion) {

            switch ($ubicacion->id_Reporte) {
                case(7):
                    $icon = "pc";
                    break;
                case(8):
                    $icon = "carril";
                    break;
                case(9):
                    $icon = "its";
                    break;
                case(15):
                    $icon = "wc";
                    break;
                case(16):
                    $icon = "restaurant";
                    break;
                case(41):
                    $icon = "area";
                    break;
                default:
                    $icon = "reportes";
                    break;
            }

            $book[] = array(
                'type' => 'Feature',
                'properties' => [
                    'description' => "<strong> " . $ubicacion->titulo_Reporte . "</strong> &nbsp; <a target='_blank' href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=$ubicacion->id_Gpo_Valores_Reporte&Id_Reporte=$ubicacion->id_Reporte'> <i class='fa fa-search'></i> </a> <br>" .
                        $ubicacion->nombre_Reporte . "<br>" .
                        $this->formatearFecha($ubicacion->fecha_registro) . " " . $this->formatearHora($ubicacion->fecha_registro) . "<br>" .
                        $ubicacion->nombre_Usuario . " " . $ubicacion->apellido_Usuario,
                    "icon" => "" . $icon . ""],
                'geometry' => ['type' => 'Point', 'coordinates' => [$ubicacion->longitud_Reporte, $ubicacion->latitud_Reporte]]
            );
        }

        // /*
        $this->view("index", array(
            "book" => $book
        ));
        // */
    }

    public function diagrama()
    {
        if ($_GET['id_Gpo_Valores']) {
            $idGpoValores = $_GET['id_Gpo_Valores'];
            $reporteLlenado = new ReporteLlenado($this->adapter);
            $allRegistros = $reporteLlenado->calcularPorcentajeEstructuraGeneral($idGpoValores);
            //OBTENER LOS DATOS DEL REPORTE
            $allreportellenado = $reporteLlenado->getReporteLlenadoById($idGpoValores);


            foreach ($allRegistros as $registro) {
                unset($registro->id_Gpo_Valores_Padre);
                unset($registro->Porcentaje);
                unset($registro->Cantidad);
                unset($registro->nombre_Reporte);
                $registros = $reporteLlenado->calcularPorcentajeAvanceDetalle($registro->id_Gpo_Valores_Reporte);

                foreach ($registros as $hijo) {
                    unset($hijo->nombre_Reporte);
                    unset($hijo->Porcentaje);
                    unset($hijo->SUMA);
                    unset($hijo->Porcentaje1);
                }
                $registro->datos = $registros;
            }
            $this->view("index", ["diagrama" => $allRegistros, "datosReporte" => $allreportellenado]);
        } else {
            $this->redirect("Graficas");
        }
    }
}
