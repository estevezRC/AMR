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

        require_once 'core/Estadisticas.php';
        require_once 'vendor/autoload.php';
    }


    public function getActividadFromRegistro($registro)
    {
        $avanceJson = array_map(function ($resultado) {
            return json_decode($resultado->actividad);
        }, $registro);
        return $avanceJson;
    }

    public function indexBP()
    {

        $estadisticasExt = new Estadisticas();

        //date_default_timezone_set('America/Mexico_City');
        $fechaInicio = $_REQUEST['fechainicio'];
        $fechaFinal = $_REQUEST['fechafin'];
        $idReporteInc = $_REQUEST['idReporteInc'];
        $fecha_inicio_proyecto = '2020-10-19';

        if (empty($fechaInicio) || empty($fechaFinal)) {
            $fechaactuali = $fecha_inicio_proyecto . ' 00:00:00';//date('Y-m') . '-01' . ' 00:00:00';
            $fechaactualf = date('Y-m-d') . ' 23:59:59';
        } else {
            $fechaactuali = $fechaInicio . ' 00:00:00';
            $fechaactualf = $fechaFinal . ' 23:59:59';
        }


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
        // *************************************************************************************************************
        // ***************************** SECCION DE ASIGNACION DE IDS DE REPORTES POR PROYECTO *************************
        // *************************************************************************************************************

        if ($this->id_Proyecto_constant == 1) { // PROYECTO Tramo A. Monterrey - Nuevo Laredo
            $idReportesInv = 41;
            $idReportesFO = 4;
            $idReporteInc = 2;
            $idProyecto = 1;
        }
        if ($this->id_Proyecto_constant == 2) { // PROYECTO Tramo B. Cadereyta - Reynosa
            $idReportesInv = 68;
            $idReportesFO = 8;
            $idReporteInc = 9;
            $idProyecto = 2;
        }
        if ($this->id_Proyecto_constant == 3) { // PROYECTO Tramo C. Libramiento de Reynosa Sur II
            $idReportesInv = 78;
            $idReportesFO = 13;
            $idReporteInc = 15;
            $idProyecto = 3;
        }
        if ($this->id_Proyecto_constant == 4) { // PROYECTO Tramo D. Matamoros - Reynosa
            $idReportesInv = 84;
            $idReportesFO = 29;
            $idReporteInc = 30;
            $idProyecto = 4;
        }
        if ($this->id_Proyecto_constant == 5) { // PROYECTO Tramo E. Puente Internacional Reynosa - Pharr
            $idReportesInv = 90;
            $idReportesFO = 24;
            $idReporteInc = 25;
            $idProyecto = 5;
        }
        if ($this->id_Proyecto_constant == 6) { // PROYECTO Tramo F. Puente internacional Ignacio Zaragoza
            $idReportesInv = 96;
            $idReportesFO = 19;
            $idReporteInc = 20;
            $idProyecto = 6;
        }
        if ($this->id_Proyecto_constant == 8) { // PROYECTO Entrenamiento
            $idReportesInv = 57;
            $idReportesFO = 59;
            $idReporteInc = '2,9,15,30,25,20';
            $idProyecto = '1,2,3,4,5,6';
        }
        if ($this->id_Proyecto_constant == 10) { // PROYECTO Administración
            $idReportesInv = '41,68,78,84,90,96,57';
            $idReportesFO = '4,8,13,29,24,19,59';
            $idReporteInc = '2,9,15,30,25,20';
            $idProyecto = '1,2,3,4,5,6';
        }

        // ************************************ FECHAS PARA DIVERSOS CASOS *********************************************
        $fecha = " AND fecha_registro >= '$fechaactuali' AND fecha_registro <= '$fechaactualf'";
        $fechainv = " AND fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";
        $fechaFO = " fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";
        $fechaIncidente = "AND CONCAT(A.valor_Texto_Reporte,' ', B.valor_Texto_Reporte) >= '$fechaactuali' AND CONCAT(A.valor_Texto_Reporte,' ', B.valor_Texto_Reporte) <= '$fechaactualf'";

        // ************************************** DATOS PARA TABLA DE INVENTARIO ***************************************
        $estadisticas = $this->connectorDB->getEstadisticasReportes($idReportesInv, $fechainv);

        // ********************************** AVANCES DEL REPORTE DE FO POR PORYECTO ***********************************
        $resultados = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);
        $avanceJson = $this->getActividadFromRegistro($resultados);
        $arrayAvancesFO = $estadisticasExt->procesarJsonByProyectoFO($avanceJson);

        // ******************************** DATOS PARA SECCION DE AVANCES DE FO ****************************************
        $resultadosgeneral = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);
        $resultadoA = $this->connectorDB->getJsonAvancesFO(4, $fechaFO);
        $resultadoB = $this->connectorDB->getJsonAvancesFO(8, $fechaFO);
        $resultadoC = $this->connectorDB->getJsonAvancesFO(13, $fechaFO);
        $resultadoD = $this->connectorDB->getJsonAvancesFO(29, $fechaFO);
        $resultadoE = $this->connectorDB->getJsonAvancesFO(24, $fechaFO);
        $resultadoF = $this->connectorDB->getJsonAvancesFO(19, $fechaFO);
        
        // OBTENER JSON DE REGISTROS
        $avanceJsonG = $this->getActividadFromRegistro($resultadosgeneral);
        $avanceJsonA = $this->getActividadFromRegistro($resultadoA);
        $avanceJsonB = $this->getActividadFromRegistro($resultadoB);
        $avanceJsonC = $this->getActividadFromRegistro($resultadoC);
        $avanceJsonD = $this->getActividadFromRegistro($resultadoD);
        $avanceJsonE = $this->getActividadFromRegistro($resultadoE);
        $avanceJsonF = $this->getActividadFromRegistro($resultadoF);
        $arrayAvancesFOG = $estadisticasExt->procesarJsonProyectoVG($avanceJsonG, $avanceJsonA, $avanceJsonB, $avanceJsonC, $avanceJsonD, $avanceJsonE, $avanceJsonF);

        // *****************************************TABLA COMPARATIVA MENSUAL*******************************************
        setlocale(LC_TIME, 'es_ES');
        //******************** MES ACTUAL ****************
        $split_fechames = explode('-', $fechaactualf);
        $a = $split_fechames[0];
        $m = $split_fechames[1];
        $compamensualmp = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a, $m);
        $nameMes = DateTime::createFromFormat('!m', $m);
        $m = strtoupper(strftime("%B", $nameMes->getTimestamp()));

        //********************* MENOS 1 MES *****************
        $fechaDias = date("Y-m-d", strtotime($fechaactualf . "- 3 days"));
        $mesmenos1 = date("Y-m-d", strtotime($fechaDias . "- 1 month"));
        $split_fechames = explode('-', $mesmenos1);
        $a1 = $split_fechames[0];
        $m1 = $split_fechames[1];
        $compamensualm1 = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a1, $m1);
        $nameMes1 = DateTime::createFromFormat('!m', $m1);
        $m1 = strtoupper(strftime("%B", $nameMes1->getTimestamp()));

        //******************** MENOS 2 MESES ****************
        $mesmenos2 = date("Y-m-d", strtotime($fechaDias . "- 2 month"));
        $split_fechames = explode('-', $mesmenos2);
        $a2 = $split_fechames[0];
        $m2 = $split_fechames[1];
        $compamensualm2 = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a2, $m2);
        $nameMes2 = DateTime::createFromFormat('!m', $m2);
        $m2 = strtoupper(strftime("%B", $nameMes2->getTimestamp()));

        // ******************* OBTENER INFORMACION DE ACTIVIDADES POR MES *********************
        $avanceJsonMACTUAL = $this->getActividadFromRegistro($compamensualmp);
        $avanceJsonM1 = $this->getActividadFromRegistro($compamensualm1);
        $avanceJsonM2 = $this->getActividadFromRegistro($compamensualm2);
        $arrayAvancesFOM = $estadisticasExt->procesarJsonMensual($avanceJsonMACTUAL, $avanceJsonM1, $avanceJsonM2);

        //*********************************Tiempo Transcurrido del proyecto*********************************************
        $fechaactual = date('Y-m-d');
        $tiempoproyecto = $this->connectorDB->gettipotranscurridoproyecto($fechaactual)[0];

        // ************************************** DATO INCIDENCIA CERRADO/ABIERTO***************************************
        // INCIDENTES ABIERTOS - CERADOS
        $totalabierto = $this->connectorDB->getAllIncidentesByStatus($idReporteInc, $fecha, 2)[0];
        $totalcerrado = $this->connectorDB->getAllIncidentesByStatus($idReporteInc, $fecha,5)[0];
        $totalincidentes = $totalabierto->registro_incidentes + $totalcerrado->registro_incidentes;

        // OBTENER TIEMPO PROMEDIO DE ATENCION DE INCIDENCIA
        $tiempoPromedioIncidencia = $estadisticasExt->tiempoPromedioIncidente($fechaIncidente, $idProyecto);

        //*************************************** TOTAL DE REGISTROS ***************************************************
        $idreportes = $this->connectorDB->getidsreporte($idProyecto);
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
            //*****************registros por usuario******************
            $resgistroporusuario = $this->connectorDB->getregistrosporusuarios($idsstring, $fecha);
        }

        // ************************************* CAMBIO DE FORMATO DE FECHAS *******************************************
        $originalDateI = $fechaactuali;
        $fechaactuali = date("d/m/Y", strtotime($originalDateI)) . ' ' . '00:00:00';
        $originalDateF = $fechaactualf;
        $fechaactualf = date("d/m/Y", strtotime($originalDateF)) . ' ' . ' 23:59:59';

        // ***************************** ARRAY DE DATOS CON TODOS LOS CALCULOS *****************************************
        $datos = array(
            'resul' => $resul, 'resulNombrel' => $resulNombrel, 'allReportes' => $arrayreportes, 'mensaje' => $mensaje,
            'nuevoResul' => $nuevoResultado, 'reportes' => $reportes, 'estadisticas' => $estadisticas,
            'arrayAvancesFO' => $arrayAvancesFO, 'totalabierto' => $totalabierto, 'totalincidentes' => $totalincidentes,
            'totalcerrado' => $totalcerrado, 'idReporteInc' => $idReporteInc, 'resgistropormes' => $resgistropormes,
            'resgistroporusuario' => $resgistroporusuario, 'tiempoproyecto' => $tiempoproyecto,
            'arrayAvancesFOG' => $arrayAvancesFOG, 'fechaactuali' => $fechaactuali, 'fechaactualf' => $fechaactualf,
            'arrayAvancesFOM' => $arrayAvancesFOM, 'm' => $m, 'm1' => $m1, 'm2' => $m2, 'a' => $a, 'a1' => $a1, 'a2' => $a2,
            'tiempoPromedioIncidencia' => $tiempoPromedioIncidencia
        );

        if ($_REQUEST['bandera'])
            echo json_encode($datos);
        else
            $this->view("index", $datos);
    }

    public function index()
    {

        $estadisticasExt = new Estadisticas();

        //date_default_timezone_set('America/Mexico_City');
        $fechaInicio = $_REQUEST['fechainicio'];
        $fechaFinal = $_REQUEST['fechafin'];
        $idReporteInc = $_REQUEST['idReporteInc'];

        if (empty($fechaInicio) || empty($fechaFinal)) {
            $fechaactuali = '2020-10-19 00:00:00';//date('Y-m') . '-01' . ' 00:00:00';
            $fechaactualf = date('Y-m-d') . ' 23:59:59';
        } else {
            $fechaactuali = $fechaInicio . ' 00:00:00';
            $fechaactualf = $fechaFinal . ' 23:59:59';
        }

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
        // *************************************************************************************************************
        // ***************************** SECCION DE ASIGNACION DE IDS DE REPORTES POR PROYECTO *************************
        // *************************************************************************************************************
        $idProyecto = $this->id_Proyecto_constant;
        switch ($this->id_Proyecto_constant) {
            case 1:
                // PROYECTO Tramo A. Monterrey - Nuevo Laredo
                $idReportesInv = 41;
                $idReportesFO = 4;
                $idReporteInc = 2;
                break;
            case 2:
                // PROYECTO Tramo B. Cadereyta - Reynosa
                $idReportesInv = 68;
                $idReportesFO = 8;
                $idReporteInc = 9;
                break;
            case 3:
                // PROYECTO Tramo C. Libramiento de Reynosa Sur II
                $idReportesInv = 78;
                $idReportesFO = 13;
                $idReporteInc = 15;
                break;
            case 4:
                // PROYECTO Tramo D. Matamoros - Reynosa
                $idReportesInv = 84;
                $idReportesFO = 29;
                $idReporteInc = 30;
                break;
            case 5:
                // PROYECTO Tramo E. Puente Internacional Reynosa - Pharr
                $idReportesInv = 90;
                $idReportesFO = 24;
                $idReporteInc = 25;
                break;
            case 6:
                // PROYECTO Tramo F. Puente internacional Ignacio Zaragoza
                $idReportesInv = 96;
                $idReportesFO = 19;
                $idReporteInc = 20;
                break;
            case 8:
                // PROYECTO Entrenamiento
                $idReportesInv = 57;
                $idReportesFO = 59;
                $idReporteInc = '2,9,15,30,25,20';
                $idProyecto = '1,2,3,4,5,6';
                break;
            case 10:
                // PROYECTO Administración
                $idReportesInv = '41,68,78,84,90,96,57';
                $idReportesFO = '4,8,13,29,24,19,59';
                $idReporteInc = '2,9,15,30,25,20';
                $idProyecto = '1,2,3,4,5,6';
                break;
        }


        // ************************************ FECHAS PARA DIVERSOS CASOS *********************************************
        $fecha = " AND fecha_registro >= '$fechaactuali' AND fecha_registro <= '$fechaactualf'";
        $fechainv = " AND fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";
        $fechaFO = " fecha >= '$fechaactuali' AND fecha <= '$fechaactualf'";
        $fechaIncidente = "AND CONCAT(A.valor_Texto_Reporte,' ', B.valor_Texto_Reporte) >= '$fechaactuali' AND CONCAT(A.valor_Texto_Reporte,' ', B.valor_Texto_Reporte) <= '$fechaactualf'";

        // ************************************** DATOS PARA TABLA DE INVENTARIO ***************************************
        $estadisticas = $this->connectorDB->getEstadisticasReportes($idReportesInv, $fechainv);

        // ********************************** AVANCES DEL REPORTE DE FO POR PORYECTO ***********************************
        $resultados = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);
        $avanceJson = $this->getActividadFromRegistro($resultados);
        $arrayAvancesFO = $estadisticasExt->procesarJsonByProyectoFO($avanceJson);


        // ******************************** DATOS PARA SECCION DE AVANCES DE FO ****************************************
        $resultadosgeneral = $this->connectorDB->getJsonAvancesFO($idReportesFO, $fechaFO);
        $resultadoA = $this->connectorDB->getJsonAvancesFO(4, $fechaFO);
        $resultadoB = $this->connectorDB->getJsonAvancesFO(8, $fechaFO);
        $resultadoC = $this->connectorDB->getJsonAvancesFO(13, $fechaFO);
        $resultadoD = $this->connectorDB->getJsonAvancesFO(29, $fechaFO);
        $resultadoE = $this->connectorDB->getJsonAvancesFO(24, $fechaFO);
        $resultadoF = $this->connectorDB->getJsonAvancesFO(19, $fechaFO);

        // OBTENER JSON DE REGISTROS
        $avanceJsonG = $this->getActividadFromRegistro($resultadosgeneral);
        $avanceJsonA = $this->getActividadFromRegistro($resultadoA);
        $avanceJsonB = $this->getActividadFromRegistro($resultadoB);
        $avanceJsonC = $this->getActividadFromRegistro($resultadoC);
        $avanceJsonD = $this->getActividadFromRegistro($resultadoD);
        $avanceJsonE = $this->getActividadFromRegistro($resultadoE);
        $avanceJsonF = $this->getActividadFromRegistro($resultadoF);
        $arrayAvancesFOG = $estadisticasExt->procesarJsonProyectoVG($avanceJsonG, $avanceJsonA, $avanceJsonB, $avanceJsonC, $avanceJsonD, $avanceJsonE, $avanceJsonF);

        // *****************************************TABLA COMPARATIVA MENSUAL*******************************************
        setlocale(LC_TIME, 'es_ES');
        //******************** MES ACTUAL ****************

        // SE ARMA PARA VER LA DIFERENCIA ENTRE DOS FECHAS
        $fechainicial = new DateTime($fechaactuali);
        $fechafinal = new DateTime($fechaactualf);
        $diferencia = $fechainicial->diff($fechafinal);
        $meses = $diferencia->format('%m');

        $arrayInformacion = [];
        for ($i = 0; $i <= $meses; $i++) {
            $mesmenos1 = date("Y-m-d", strtotime($fechaactualf . "- $i month"));
            $split_fechames = explode('-', $mesmenos1);
            $a = $split_fechames[0];
            $m = $split_fechames[1];
            $compamensualm1 = $this->connectorDB->getJsonAvancesMensual($idReportesFO, $a, $m);
            $nameMes1 = DateTime::createFromFormat('!m', $m);
            $m1 = strtoupper(strftime("%B", $nameMes1->getTimestamp()));
            $avanceJson = $this->getActividadFromRegistro($compamensualm1);

            $arrayInformacion[] = ['meses' => $m1 . ' ' . $a, 'identificador' => $m.$a,  'avanceJson' => $avanceJson];
        }


        $arrayAvancesFOM = $estadisticasExt->procesarJsonMensualBackup($arrayInformacion);



        //*********************************Tiempo Transcurrido del proyecto*********************************************
        $fechaactual = date('Y-m-d');
        $tiempoproyecto = $this->connectorDB->gettipotranscurridoproyecto($fechaactual)[0];

        // ************************************** DATO INCIDENCIA CERRADO/ABIERTO***************************************
        // INCIDENTES ABIERTOS - CERADOS
        $totalabierto = $this->connectorDB->getAllIncidentesByStatus($idReporteInc, $fecha, 2)[0];
        $totalcerrado = $this->connectorDB->getAllIncidentesByStatus($idReporteInc, $fecha,5)[0];
        $totalincidentes = $totalabierto->registro_incidentes + $totalcerrado->registro_incidentes;

        // OBTENER TIEMPO PROMEDIO DE ATENCION DE INCIDENCIA
        $tiempoPromedioIncidencia = $estadisticasExt->tiempoPromedioIncidente($fechaIncidente, $idProyecto);

        //*************************************** TOTAL DE REGISTROS ***************************************************
        $idreportes = $this->connectorDB->getidsreporte($idProyecto);
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
            //*****************registros por usuario******************
            $resgistroporusuario = $this->connectorDB->getregistrosporusuarios($idsstring, $fecha);
        }

        // ************************************* CAMBIO DE FORMATO DE FECHAS *******************************************
        $originalDateI = $fechaactuali;
        $fechaactuali = date("d/m/Y", strtotime($originalDateI)) . ' ' . '00:00:00';
        $originalDateF = $fechaactualf;
        $fechaactualf = date("d/m/Y", strtotime($originalDateF)) . ' ' . ' 23:59:59';

        // ***************************** ARRAY DE DATOS CON TODOS LOS CALCULOS *****************************************

        $datos = array(
            'resul' => $resul, 'resulNombrel' => $resulNombrel, 'allReportes' => $arrayreportes, 'mensaje' => $mensaje,
            'nuevoResul' => $nuevoResultado, 'reportes' => $reportes, 'estadisticas' => $estadisticas,
            'arrayAvancesFO' => $arrayAvancesFO, 'totalabierto' => $totalabierto, 'totalincidentes' => $totalincidentes,
            'totalcerrado' => $totalcerrado, 'idReporteInc' => $idReporteInc, 'resgistropormes' => $resgistropormes,
            'resgistroporusuario' => $resgistroporusuario, 'tiempoproyecto' => $tiempoproyecto,
            'arrayAvancesFOG' => $arrayAvancesFOG, 'fechaactuali' => $fechaactuali, 'fechaactualf' => $fechaactualf,
            'arrayAvancesFOM' => $arrayAvancesFOM, 'totalMeses' => $meses, 'tiempoPromedioIncidencia' => $tiempoPromedioIncidencia
        );



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
