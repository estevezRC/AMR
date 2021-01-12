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

    public function procesarArrays($avanceJsonG,$arrayAvancesFOG,$posicion){
        foreach ($avanceJsonG as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->rellenofluido->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->registro->valor += $suma;
                        }

                    }
                }
            }
        }
       }

    public function procesarJson($resultadosgeneral)
    {
        $avanceJsonG = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadosgeneral);
        return $avanceJsonG;
    }

    public function meses($m){
        switch ($m) {
            case 1:
                $m = "Enero";
                break;
            case 2:
                $m = "Febrero";
                break;
            case 3:
                $m = "Marzo";
                break;
            case 4:
                $m = "Abril";
                break;
            case 5:
                $m = "Mayo";
                break;
            case 6:
                $m = "Junio";
                break;
            case 7:
                $m = "Julio";
                break;
            case 8:
                $m = "Agosto";
                break;
            case 9:
                $m = "Septiembre";
                break;
            case 10:
                $m = "Octubre";
                break;
            case 11:
                $m = "Noviembre";
                break;
            case 12:
                $m = "Diciembre";
                break;
        }
        return $m;
    }
    public function index()
    {
        $fechaInicio = $_REQUEST['fechainicio'];
        $fechaFinal = $_REQUEST['fechafin'];
        $idReporteInc = $_REQUEST['idReporteInc'];

        if (empty($fechaInicio) || empty($fechaFinal)) {
            $fechaactuali = date('Y-m') . '-01' . ' 00:00:00';
            $fechaactualf = date('Y-m-d') . ' 23:59:59';
        } else {
            $fechaactuali = $fechaInicio . ' 00:00:00';
            $fechaactualf = $fechaFinal . ' 23:59:59';
        }

        //PROYECTO
        /*$id_Proyecto_Actual = (int)$_POST["id_Proyecto"];
        if (!empty($id_Proyecto_Actual)) {
            $proyecto = new Proyecto($this->adapter);
            $datosproyecto = $proyecto->getProyectoById($_SESSION[ID_PROYECTO_SUPERVISOR]);
            $_SESSION[NOMBRE_PROYECTO] = $datosproyecto->nombre_Proyecto;
            session_start();
            $_SESSION[ID_PROYECTO_SUPERVISOR] = $datosproyecto->id_Proyecto;
        }
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];*/ // Estadísticas

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
            $idReporteInc = 2;
            $idReporte = 1;
        }
        if ($this->id_Proyecto_constant == 2) { // PROYECTO Tramo B. Cadereyta - Reynosa
            $idReportesInv = 68;
            $idReportesFO = 8;
            $idReporteInc = 9;
            $idReporte = 2;

        }
        if ($this->id_Proyecto_constant == 3) { // PROYECTO Tramo C. Libramiento de Reynosa Sur II
            $idReportesInv = 78;
            $idReportesFO = 13;
            $idReporteInc = 15;
            $idReporte = 3;
        }
        if ($this->id_Proyecto_constant == 4) { // PROYECTO Tramo D. Matamoros - Reynosa
            $idReportesInv = 84;
            $idReportesFO = 29;
            $idReporteInc = 30;
            $idReporte = 4;
        }
        if ($this->id_Proyecto_constant == 5) { // PROYECTO Tramo E. Puente Internacional Reynosa - Pharr
            $idReportesInv = 90;
            $idReportesFO = 24;
            $idReporteInc = 25;
            $idReporte = 5;
        }
        if ($this->id_Proyecto_constant == 6) { // PROYECTO Tramo F. Puente internacional Ignacio Zaragoza
            $idReportesInv = 96;
            $idReportesFO = 19;
            $idReporteInc = 20;
            $idReporte = 6;
        }
        if ($this->id_Proyecto_constant == 8) { // PROYECTO Entrenamiento
            $idReportesInv = 57;
            $idReportesFO = 59;
            $idReporteInc = '2,9,15,30,25,20';
            $idReporte = '1,2,3,4,5,6';

        }
        if ($this->id_Proyecto_constant == 10) { // PROYECTO Administración
            $idReportesInv = '41,68,78,84,90,96,57';
            $idReportesFO = '4,8,13,29,24,19,59';
            $idReporteInc = '2,9,15,30,25,20';
            $idReporte = '1,2,3,4,5,6';

        }

        // ************************************** DATO INCIDENCIA CERRADO/ABIERTO***************************************


        $fecha = " AND fecha_registro >= '$fechaactuali' AND fecha_registro <= '$fechaactualf'";
        $fechainv = " AND fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";
        $fechaFO = " fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";

        $totalabierto = $this->connectorDB->getAllIncidenciaAbierto($idReporteInc, $fecha)[0];
        $totalcerrado = $this->connectorDB->getAllIncidenciacerrado($idReporteInc, $fecha)[0];
        $totalincidentes = $totalabierto->abierto + $totalcerrado->cerrado;

        $tipoincidencia = $this->connectorDB->gettipoincidenciatotal($idReporteInc, $fecha)[0];

        //**************************+**************registros total******************************************************

        $idreportes = $this->connectorDB->getidsreporte($idReporte);

        if ($idreportes) {
            $res = array();
            for ($i = 0; $i < count($idreportes); $i++) {
                $res[$i] = $idreportes[$i]->id_Reporte;
            }
            $idsstring = implode(",", $res);

            $getregistros = $this->connectorDB->getregistros($idsstring, $fecha);

            $resgistropormes = 0;
            for ($i = 0; $i < count($getregistros); $i++) {
                $resgistropormes = $resgistropormes + $getregistros[$i]->id_Status_Elemento;
            }
            //**************************+**************registros por usuario************************************************
            $resgistroporusuario = $this->connectorDB->getregistrosporusuarios($idsstring, $fecha);

        }


        //*********************************Tiempo promedio de atenccion, tipo de insidencia*****************************
        //$tiempopromedio = $this->connectorDB->gettipoincidencia($idconfiguracion,$fecha);
        //*********************************Tiempo Transcurrido del proyecto*********************************************
        $fechaactual = date('Y-m-d');
        $tiempoproyecto = $this->connectorDB->gettipotranscurridoproyecto($fechaactual)[0];

        // ************************************** DATOS PARA TABLA DE INVENTARIO ***************************************
        $estadisticas = $this->connectorDB->getEstadisticasReportes($idReportesInv, $fechainv);

        // ******************************** DATOS PARA SECCION DE AVANCES DE FO ****************************************
        $resultadosgeneral = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);

        $resultadoA = $this->connectorDB->getJsonAvancesFO(4, $fechaFO);
        $resultadoB = $this->connectorDB->getJsonAvancesFO(8, $fechaFO);
        $resultadoC = $this->connectorDB->getJsonAvancesFO(13, $fechaFO);
        $resultadoD = $this->connectorDB->getJsonAvancesFO(29, $fechaFO);
        $resultadoE = $this->connectorDB->getJsonAvancesFO(24, $fechaFO);
        $resultadoF = $this->connectorDB->getJsonAvancesFO(19, $fechaFO);
        //*****************************
        $avanceJsonG = $this->procesarJson($resultadosgeneral);
        $avanceJsonA = $this->procesarJson($resultadoA);

        /*$avanceJsonG = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadosgeneral);
        $avanceJsonA = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoA);*/
        $avanceJsonB = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoB);
        $avanceJsonC = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoC);
        $avanceJsonD = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoD);
        $avanceJsonE = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoE);
        $avanceJsonF = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultadoF);
        $arrayAvancesFOG = (object)[
            'tritubo' => (object)[
                'nombre' => 'Tritubo', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'pruebas' => (object)[
                'nombre' => 'Pruebas', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'inmersionFO' => (object)[
                'nombre' => 'Inmersión FO', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'reposicionAsfalto' => (object)[
                'nombre' => 'Reposición de asfalto', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'rellenofluido' => (object)[
                'nombre' => 'Relleno fluido', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'cople' => (object)[
                'nombre' => 'Cople', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'registro' => (object)[
                'nombre' => 'Registro', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'zanjado' => (object)[
                'nombre' => 'Zanjado', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
        ];

        foreach ($avanceJsonG as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valor += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonA as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorA += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorA += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonB as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorB += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorB += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonC as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }

                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorC += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorC += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonD as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorD += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorD += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonE as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorE += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorE += $suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonF as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->tritubo->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->pruebas->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->inmersionFO->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->reposicionAsfalto->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOG->zanjado->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFOG->rellenofluido->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOG->cople->valorF += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFOG->registro->valorF += $suma;
                        }

                    }
                }
            }
        }

        //*****************************

        $resultados = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);

        $avanceJson = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $resultados);
        //print_r($avanceJson);
        //echo json_encode($avanceJson);
        //print_r($resultados);
        //die();
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
            'rellenofluido' => (object)[
                'nombre' => 'Relleno fluido', 'valor' => 0
            ],
            'cople' => (object)[
                'nombre' => 'Cople', 'valor' => 0
            ],
            'registro' => (object)[
                'nombre' => 'Registro', 'valor' => 0
            ],
            'zanjado' => (object)[
                'nombre' => 'Zanjado', 'valor' => 0
            ],
        ];

        foreach ($avanceJson as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFO->tritubo->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFO->pruebas->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFO->inmersionFO->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFO->reposicionAsfalto->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFO->zanjado->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                //print_r($valor->Valor[$i]);
                                //echo $valor->Valor[$i]->valorCampo;
                                //echo "<br> <br> ";

                                //echo $valor->Valor[$i]->idCampo;
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                    //echo $concat_final;
                                    //echo "<br> <br> ";
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                    //echo $valor->Valor[$i]->valorCampo;
                                }
                                //echo $valor->Valor[$i];
                            }
                            /*     echo $valor->Valor;
                                echo $concat_final;
                                echo "     ...     ";
                                echo $concat_inicial;
                                 echo "     ...     ";
                                 echo $concat_final - $concat_inicial;
                                 echo "     ---     ";*/
                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //echo $suma;
                            //echo "     suma:  ". $suma;
                            // $suma =  $concat_final - $concat_inicial;
                            $arrayAvancesFO->rellenofluido->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFO->cople->valor += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            //$arrayAvancesFO->cople->valor += $suma;
                            $arrayAvancesFO->registro->valor += $suma;
                        }

                    }
                }
            }
        }


        // *****************************************TABLA COMPARATIVA MENSUAL*******************************************
        $split_fechames = explode('-', $fechaactuali);
        $a = $split_fechames[0];
        $m = $split_fechames[1];
        $compamensualmp = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a, $m);
        $m = $this->meses($m);

        //*********************************************** Menos -1 Mes *************************************************
        $mesmenos1 = date("Y-m-d", strtotime($fechaactuali . "- 1 month"));
        $split_fechames = explode('-', $mesmenos1);
        $a1 = $split_fechames[0];
        $m1 = $split_fechames[1];
        $compamensualm1 = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a1, $m1);
        $m1 = $this->meses($m1);

        //*********************************************** Menos -2 Mes *************************************************
        $mesmenos2 = date("Y-m-d", strtotime($fechaactuali . "- 2 month"));
        $split_fechames = explode('-', $mesmenos2);
        $a2 = $split_fechames[0];
        $m2 = $split_fechames[1];
        $compamensualm2 = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a2, $m2);
        $m2 = $this->meses($m2);
        //*****************************************************+MESES***************************************************


        $avanceJsonMACTUAL = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $compamensualmp);
        $avanceJsonM1 = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $compamensualm1);
        $avanceJsonM2 = array_map(function ($resultado) {
            return json_decode($resultado->actividad); // $resultado->actividad;
        }, $compamensualm2);
        //print_r($avanceJsonM1);

        $arrayAvancesFOM = (object)[
            'tritubo' => (object)[
                'nombre' => 'Tritubo', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'pruebas' => (object)[
                'nombre' => 'Pruebas', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'inmersionFO' => (object)[
                'nombre' => 'Inmersión FO', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'reposicionAsfalto' => (object)[
                'nombre' => 'Reposición de asfalto', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'rellenofluido' => (object)[
                'nombre' => 'Relleno fluido', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'cople' => (object)[
                'nombre' => 'Cople', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'registro' => (object)[
                'nombre' => 'Registro', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'zanjado' => (object)[
                'nombre' => 'Zanjado', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
        ];

        foreach ($avanceJsonMACTUAL as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->tritubo->valorM += $suma;
                            $tritubo += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->pruebas->valorM += $suma;
                            $pruebas += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->inmersionFO->valorM += $suma;
                            $inmersionFO += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->reposicionAsfalto->valorM += $suma;
                            $reposicionAsfalto += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            //print_r($suma);
                            $arrayAvancesFOM->zanjado->valorM += $suma;
                            $zanjado +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {

                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->rellenofluido->valorM += $suma;
                            $rellenoFluido += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->cople->valorM += $suma;
                            $cople +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->registro->valorM += $suma;
                            $regis +=$suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonM1 as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        //print_r($opcionesCampos);

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->tritubo->valorM1 += $suma;

                            $tritubo1 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            //$suma = (float)$valor->Valor[2]->valorCampo - (float)$valor->Valor[0]->valorCampo;
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->pruebas->valorM1 += $suma;
                            $pruebas1 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->inmersionFO->valorM1 += $suma;
                            $inmersionFO1 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            //print_r($valor->Valor);
                           //print_r($opcionesCampos);

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->reposicionAsfalto->valorM1 += $suma;
                            $reposicionAsfalto1 += $suma;

                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            //print_r($valor->Valor);
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->zanjado->valorM1 += $suma;
                            $zanjado1 +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            //print_r($valor->Valor);

                            for ($i = 0; $i < count($valor->Valor); $i++) {

                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->rellenofluido->valorM1 += $suma;
                            $rellenoFluido1 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->cople->valorM1 += $suma;
                            $cople1 +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->registro->valorM1 += $suma;
                            $regis1 +=$suma;
                        }

                    }
                }
            }
        }
        foreach ($avanceJsonM2 as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {

                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->tritubo->valorM2 += $suma;
                            $tritubo2 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->pruebas->valorM2 += $suma;
                            $pruebas2 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->inmersionFO->valorM2 += $suma;
                            $inmersionFO2 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->reposicionAsfalto->valorM2 += $suma;
                            $reposicionAsfalto2 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }
                            $arrayAvancesFOM->zanjado->valorM2 += $suma;
                            $zanjado2 +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {

                            for ($i = 0; $i < count($valor->Valor); $i++) {

                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->rellenofluido->valorM2 += $suma;
                            $rellenoFluido2 += $suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->cople->valorM2 += $suma;
                            $cople2 +=$suma;
                        }
                        else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            for ($i = 0; $i < count($valor->Valor); $i++) {
                                if ($valor->Valor[$i]->idCampo == 67) {
                                    $concat_final = $valor->Valor[$i]->valorCampo;
                                }

                                if ($valor->Valor[$i]->idCampo == 29) {
                                    $concat_inicial = $valor->Valor[$i]->valorCampo;
                                }
                            }

                            if ($concat_final > $concat_inicial) {
                                $suma = $concat_final - $concat_inicial;
                            } else {
                                $suma = $concat_inicial - $concat_final;
                            }

                            $arrayAvancesFOM->registro->valorM2 += $suma;
                            $regis2 +=$suma;
                        }

                    }
                }
            }
        }

        $arrayAvancesFOM->tritubo->valor = $tritubo + $tritubo1 + $tritubo2;
        $arrayAvancesFOM->rellenofluido->valor = $rellenoFluido + $rellenoFluido1 + $rellenoFluido2;
        $arrayAvancesFOM->cople->valor = $cople + $cople1 + $cople2;
        $arrayAvancesFOM->zanjado->valor = $zanjado + $zanjado1 + $zanjado2;
        $arrayAvancesFOM->pruebas->valor = $pruebas + $pruebas1 +$pruebas2;
        $arrayAvancesFOM->reposicionAsfalto->valor = $reposicionAsfalto + $reposicionAsfalto1 + $reposicionAsfalto2;
        $arrayAvancesFOM->inmersionFO->valor = $inmersionFO + $inmersionFO1 + $inmersionFO2;
        $arrayAvancesFOM->registro->valor = $regis  + $regis1 + $regis2;


        // *************************************************************************************************************
        //print_r($arrayAvancesFOM);
        //die();
        // CAMBIO DE FORMATO DE FECHAS
        $originalDateI = $fechaactuali;
        $fechaactuali = date("d/m/Y", strtotime($originalDateI)) . ' ' . '00:00:00';
        $originalDateF = $fechaactualf;
        $fechaactualf = date("d/m/Y", strtotime($originalDateF)) . ' ' . ' 23:59:59';




        $datos = array('resul' => $resul, 'resulNombrel' => $resulNombrel, 'allReportes' => $arrayreportes,
            'mensaje' => $mensaje, 'nuevoResul' => $nuevoResultado, 'reportes' => $reportes, 'estadisticas' => $estadisticas,
            'arrayAvancesFO' => $arrayAvancesFO, 'totalabierto' => $totalabierto, 'totalincidentes' => $totalincidentes, 'totalcerrado' => $totalcerrado,
            'idReporteInc' => $idReporteInc, 'tipoincidencia' => $tipoincidencia, 'resgistropormes' => $resgistropormes, 'resgistroporusuario' => $resgistroporusuario,
            'tiempoproyecto' => $tiempoproyecto, 'arrayAvancesFOG' => $arrayAvancesFOG, 'fechaactuali' => $fechaactuali, 'fechaactualf' => $fechaactualf, 'arrayAvancesFOM' => $arrayAvancesFOM,
            'm' => $m, 'm1' => $m1, 'm2' => $m2, 'a' => $a, 'a1' => $a1,'a2' => $a2);


        if ($_REQUEST['bandera'])
            echo json_encode($datos);
        else
            $this->view("index", $datos);
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