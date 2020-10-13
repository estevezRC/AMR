<?php

class LlenadosReporteController extends ControladorBase
{
    public $conectar;
    public $adapter;
    private $estructuraDepurada;
    private $id_Proyecto_constant;
    private $id_Area_constant;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->estructuraDepurada = [];
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $this->id_Area_constant = $_SESSION[ID_AREA_SUPERVISOR];
        require_once 'core/FuncionesCompartidas.php';
        require_once 'core/FormatosCorreo.php';
        require_once 'vendor/autoload.php';
    }

    /*--------------------------------------- VISTA DEl CATALOGO DE REPORTES -----------------------------------------*/
    public function index()
    {
        $tipo_Reporte = $_GET['tipo'];

        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

        $camporeporte = new CampoReporte($this->adapter);

        switch ($tipo_Reporte) {
            case '0,1':
                $tipo_Reporte = '0,6,7,9';
                // VERIFICAR SI EL PROYECTO TIENE GANNT
                $registroGantt = $camporeporte->getIdGanttByid_proyecto($this->id_Proyecto_constant);
                if (empty($registroGantt))
                    $existeGantt = false;
                else {
                    $existeGantt = true;

                    // OBTENER ID_GANTT DEL PROYECTO
                    $idGantt = $registroGantt[0]->id;

                    // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
                    $getAllRegistroId_Reporte = $camporeporte->getAllRegistrosGanttValoresId_Reporte($idGantt);
                    $noreportes = '';
                    if (!empty($getAllRegistroId_Reporte)) {
                        $ids_Reporte = array();
                        foreach ($getAllRegistroId_Reporte as $registro) {
                            $ids_Reporte[] = $registro->id_reporte;
                        }
                        $ids_ReporteSTR = implode(',', $ids_Reporte);
                        $noreportes = 'AND id_Reporte NOT IN (' . $ids_ReporteSTR . ')';
                    }
                    $allreporteNoEsquema = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, $tipo_Reporte, $noreportes);
                    if (empty($allreporteNoEsquema))
                        $allreporteNoEsquema = [];

                    // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
                    $noreportes = '';
                    $getAllRegistroId_Reporte = $camporeporte->getAllRegistrosGanttValoresId_Reporte($idGantt);
                    if (!empty($getAllRegistroId_Reporte)) {
                        $ids_Reporte = array();
                        foreach ($getAllRegistroId_Reporte as $registro) {
                            $ids_Reporte[] = $registro->id_reporte;
                        }
                        $ids_ReporteSTR = implode(',', $ids_Reporte);
                        $noreportes = 'AND id_Reporte IN (' . $ids_ReporteSTR . ')';
                    }
                    $allreportes = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, $tipo_Reporte, $noreportes);

                }

                break;
            case 1:
                // VERIFICAR SI EL PROYECTO TIENE GANNT
                $registroGantt = $camporeporte->getIdGanttByid_proyecto($this->id_Proyecto_constant);
                if (empty($registroGantt))
                    $existeGantt = false;
                else
                    $existeGantt = true;
                break;
            default:
                $noreportes = '';
                break;
        }

        if (empty($allreportes)) {
            $allreportes = $camporeporte->getAllCampoReporteByAreaTipo($id_Proyecto, $_SESSION[ID_AREA_SUPERVISOR], $tipo_Reporte, $noreportes);
        }

        $reporte = $this->nombreReporteId($tipo_Reporte, 0);
        $mensaje = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> Llenar " . $reporte;

        // /*
        $this->view("index", array(
            "allreportes" => $allreportes, "mensaje" => $mensaje, "existeGantt" => $existeGantt, "allreporteNoEsquema" => $allreporteNoEsquema
        ));
        // */
    }


    public function getAllTiposReportes()
    {
        $camporeporte = new CampoReporte($this->adapter);
        $idReportePadre = $_POST['idReportePadre'];
        $id_Gpo_Valores = $_POST['id_Gpo_Valores'];

        // OBTENER ID_GANTT DEL PROYECTO
        $registroGantt = $camporeporte->getIdGanttByid_proyecto($this->id_Proyecto_constant);
        $idGantt = $registroGantt[0]->id;


        // OBTENER REGISTROS (ACTIVIDADES) YA REALIZADOS
        $allSeguimientosReportesIncidentes = $camporeporte->getAllSeguimientoProcesos($id_Gpo_Valores);
        if (!empty($allSeguimientosReportesIncidentes)) {
            $ids_ReporteRealizados = array();
            foreach ($allSeguimientosReportesIncidentes as $registro) {
                $ids_ReporteRealizados[] = $registro->id_Reporte2;
            }
        }

        /* CONSULTAR AVANCE_ACTIVIDAD PARA SABER EL NODO DEL REPORTE ACTUAL */
        $registroAvanceActividad = $camporeporte->getRegistroAvanceActividad($id_Gpo_Valores, $this->id_Proyecto_constant);

        // SABER SI EL REGISTRO EXISTE EN AVANCE ACTIVIDAD
        if (!empty($registroAvanceActividad)) {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            //+++++++++++++++++++++++++++++++ OBTENER REPORTES DE ESQUEMA A LLENAR (ACTIVIDADES) +++++++++++++++++++++++
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $id_nodo_padre = $registroAvanceActividad[0]->id_nodo;
            // OBTENER TODOS LOS ID_REPORTE A LLENAR
            $registrosGanttValores = $camporeporte->getRegistroGanttValoresByid_ganttANDid_nodo_Padre($idGantt, $id_nodo_padre, ' AND id_reporte');
            if (!empty($registrosGanttValores)) {
                $ids_ReportesLlenar = array();
                foreach ($registrosGanttValores as $registro) {
                    $ids_ReportesLlenar[] = $registro->id_reporte;
                }

                if (!empty($ids_ReporteRealizados))
                    $ids_ReportesLlenar = array_diff($ids_ReportesLlenar, $ids_ReporteRealizados);

                $ids_ReportesLlenarSTR = implode(',', $ids_ReportesLlenar);

                $noreportes = 'AND id_Reporte IN (' . $ids_ReportesLlenarSTR . ')';
                if (empty($ids_ReportesLlenarSTR))
                    $allreportes = [];
                else
                    $allreportes = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, '0,1,2,3,6,7', $noreportes);
                $msj = 'a';

                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++ OBTENER REPORTES QUE NO ESTAN EN EL ESQUEMA +++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
                $getAllRegistroId_Reporte = $camporeporte->getAllRegistrosGanttValoresId_Reporte($idGantt);
                if (!empty($getAllRegistroId_Reporte)) {
                    $ids_Reporte = array();
                    foreach ($getAllRegistroId_Reporte as $registro) {
                        $ids_Reporte[] = $registro->id_reporte;
                    }
                    $ids_ReporteSTR = implode(',', $ids_Reporte);
                    $noreportes = 'AND id_Reporte NOT IN (' . $ids_ReporteSTR . ')';
                }

                $allreporteNoEsquema = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, '0,1,2,3,6,7', $noreportes);

            } else {

                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++ OBTENER REPORTES QUE NO ESTAN EN EL ESQUEMA +++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
                $getAllRegistroId_Reporte = $camporeporte->getAllRegistrosGanttValoresId_Reporte($idGantt);
                if (!empty($getAllRegistroId_Reporte)) {
                    $ids_Reporte = array();
                    foreach ($getAllRegistroId_Reporte as $registro) {
                        $ids_Reporte[] = $registro->id_reporte;
                    }
                    $ids_ReporteSTR = implode(',', $ids_Reporte);
                    $noreportes = 'AND id_Reporte NOT IN (' . $ids_ReporteSTR . ')';
                }
                $allreporteNoEsquema = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, '0,1,2,3,6,7', $noreportes);
                $allreportes = [];
                $msj = 'b';

            }
        } else {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            //++++++++++++++++++++++++++++ OBTENER REPORTES QUE NO ESTAN EN EL ESQUEMA +++++++++++++++++++++++++++++++++
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
            if (!empty($idGantt)) {
                $getAllRegistroId_Reporte = $camporeporte->getAllRegistrosGanttValoresId_Reporte($idGantt);
                if (!empty($getAllRegistroId_Reporte)) {
                    $ids_Reporte = array();
                    foreach ($getAllRegistroId_Reporte as $registro) {
                        $ids_Reporte[] = $registro->id_reporte;
                    }
                    $ids_ReporteSTR = implode(',', $ids_Reporte);
                    $noreportes = 'AND id_Reporte NOT IN (' . $idReportePadre . ',' . $ids_ReporteSTR . ')';
                }
                $allreporteNoEsquema = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, '0,1,2,3,6,7', $noreportes);
                $allreportes = [];
                $msj = 'c';
            } else {
                $noreportes = 'AND id_Reporte NOT IN (' . $idReportePadre . ')';
                $allreporteNoEsquema = $camporeporte->getAllCampoReporteByAreaTipo($this->id_Proyecto_constant, $this->id_Area_constant, '0,1,2,3,6,7', $noreportes);
                $allreportes = [];
                $msj = 'd';
            }
        }

        echo json_encode([
            "allreportes" => $allreportes, "allreportesNoEsquema" => $allreporteNoEsquema, "msj" => $msj
        ]);
    }

    public function getAllReportesGeneralEspecifico()
    {
        $idReporte = $_REQUEST['idReporte'];
        $avanceActividad = new AvanceActividad($this->adapter);


        # OBTENER ID_GANTT DEL PROYECTO
        $registroGantt = $avanceActividad->getIdGanttByid_proyecto($this->id_Proyecto_constant);
        $idGantt = $registroGantt[0]->id;


        //Validar que el reporte este en el Esquema
        $getRegistrosEsquema = $avanceActividad->getRegistroGanttValoresByid_ganttANDid_reporte($idGantt, $idReporte);
        $infoJson = $avanceActividad->getJson($idGantt)[0]->estructura;
        $infoJson = !$infoJson ?: json_decode($infoJson)[0]->children;
        if (empty($getRegistrosEsquema)) {
            $infoJson = (object)['estructura' => $infoJson, 'estaDepurada' => false];
            echo json_encode($infoJson);
        } else {
            $depurarEstructura = function (&$estructura) use (&$depurarEstructura, $idReporte) {
                //dump($estructura);
                foreach ($estructura as $key => $subNodo) {
                    if ($subNodo->children) {
                        $depurarEstructura($estructura[$key]->children);

                        if (!$estructura[$key]->children) {
                            unset($estructura[$key]);
                        } else {
                            $estructura[$key]->children = array_values($estructura[$key]->children);
                        }

                    } elseif ($subNodo->info->id_reporte == $idReporte) {
                        if ($subNodo->info->completado) {
                            unset($estructura[$key]);
                        }
                    } else {
                        //var_dump($estructura[$key]);
                        unset($estructura[$key]);
                    }
                }
            };

            $depurarEstructura($infoJson);
            $infoJson = (object)['estructura' => $infoJson, 'estaDepurada' => true];
            echo json_encode($infoJson);
        }
    }

    /*----------------------------------------- BORRAR IMAGEN ----------------------------------------------*/
    public function borrarimg()
    {
        $id_Fotografia = $_GET['Id_Fotografia'];
        //echo $id_Fotografia;

        $fotografia = new Fotografia($this->adapter);
        echo $fotografia->borrarFotografia($id_Fotografia);
    }

    /*---------------------------------- MOSTRAR REPORTE SELECCIONADO -------------------------------------------*/
    public function mostrarreportenuevo()
    {
        //********************* QUITAR ERROR err_cache_miss "REENVIO DEL FORMULARIO" ***********************************
        header('Cache-Control: no-cache'); //no cache
        session_start();

        $id = $_REQUEST['Id_Reporte'];

        //CONSULTAR FORMULARIO
        $camporeporte = new CampoReporte($this->adapter);
        $allcamposreportes = $camporeporte->getAllCampoReporteByIdReporte($id);

        $tipo_Reporte = $allcamposreportes[0]->tipo_Reporte;

        //$allcamposreportes['Id_Sistema_Llenado'] = $Id_Sistema;
        //$allcamposreportes['id_Gpo_Sistema'] = $id_Gpo_Sistema;
        $_SESSION['arrayformulario'] = $allcamposreportes;

        //CONSULTAR UBICACIONES
        $gruposubicaciones = $camporeporte->getAllSeguimientoUbicacionInventarioGpoSistema($this->id_Proyecto_constant);
        $allsistemas = $camporeporte->getAllSeguimientoUbicacionInventario($this->id_Proyecto_constant, $this->id_Area_constant, $tipo_Reporte);

        //CLASIFICACION FOTOGRAFIAS
        $clasificacion = $camporeporte->getAllClasificacionFotografias($this->id_Area_constant);

        // CAMPOS DE PLANOS TIPO_REPORTE = 5 y ID_REPORTE = 7
        // ************************************************** SECCION DE PLANOS **********************************
        $titulo_ReportePlanos = $_GET['codigo'];
        if (!empty($titulo_ReportePlanos)) {
            $tituloReporte = $_GET['codigo'];
            $reportesPlano = new ReporteLlenado($this->adapter);
            $allseguimientosreportes = $reportesPlano->getAllPlanosByTituloReporte($this->id_Area_constant, $this->id_Proyecto_constant, 5, $tituloReporte);
            $infoReporteId_Gpo = $allseguimientosreportes[0]->Id_Reporte;
            $valoresReportePlanos = $reportesPlano->getReporteLlenadoById($infoReporteId_Gpo);

            $allcamposreportes[2]->Valor_Default = $valoresReportePlanos[2]->valor_Texto_Reporte;
            $allcamposreportes[3]->Valor_Default = $valoresReportePlanos[3]->valor_Texto_Reporte;
            $allcamposreportes[4]->Valor_Default = $valoresReportePlanos[4]->valor_Texto_Reporte;
            $allcamposreportes[5]->Valor_Default = $valoresReportePlanos[5]->valor_Texto_Reporte;
            $allcamposreportes[6]->Valor_Default = $valoresReportePlanos[6]->valor_Texto_Reporte;
            $allcamposreportes[7]->Valor_Default = $valoresReportePlanos[7]->valor_Texto_Reporte;
            $allcamposreportes[8]->Valor_Default = $valoresReportePlanos[8]->valor_Texto_Reporte;
            $allcamposreportes[9]->Valor_Default = (int)$valoresReportePlanos[9]->valor_Entero_Reporte + 1;
        }

        //CAMPOS ESPECIALES
        $datosIdAndName = null;
        $allRegistrosTablas = null;
        $createData = function ($allcamposreportes) use (
            $camporeporte,
            &$allRegistrosTablas,
            &$datosIdAndName,
            &$no_nota,
            &$no_contrato,
            &$createData
        ) {
            foreach ($allcamposreportes as $key => $reporte) {
                switch ($reporte->tipo_Reactivo_Campo) {
                    case "text-nota":
                        $no_nota = $camporeporte->getNumeroNota($reporte->id_Reporte, $reporte->id_Configuracion_Reporte);
                        $no_nota = (int)$no_nota + 1;
                        $no_contrato = $allcamposreportes[1]->nombre_campo;
                        break;
                    case "select-status":
                        // ************************** Mostrar estados del reportes *****************************************
                        $id_Gpo_Valores_Reporte = $_GET['id_Gpo_Valores_Reporte'];
                        $reportesLlenados = new ReporteLlenado($this->adapter);
                        $datosReporte = $reportesLlenados->getAllReportesLlenadosByIdGpo($id_Gpo_Valores_Reporte);

                        if ($datosReporte == '' || empty($datosReporte))
                            $valores = 'En proceso/Atendido';
                        else {
                            switch ($datosReporte[0]->id_Etapa) {
                                case 2: // Abierto
                                    $allcamposreportes[$key]->Valor_Default = 'En proceso/Atendido';
                                    break;
                                case 7: // En proceso
                                    $allcamposreportes[$key]->Valor_Default = 'En proceso/Atendido';
                                    break;
                                case 3: // Atendido
                                    $allcamposreportes[$key]->Valor_Default = 'Abierto/Validado';
                                    break;
                            }
                        }
                        break;
                    case "check_list_asistencia":
                        switch ($reporte->Valor_Default) {
                            case 'empleados':
                                // OBTENER TODOS LOS EMPLEADOS
                                $datosIdAndName = $camporeporte->getAllEmpleadosWithIdAndName();
                                break;
                            case 'participantes':
                                // OBTENER TODOS LOS PARTICIPANTES = id_Status_Usuario IN(1,2) AND participante = 1
                                $datosIdAndName = $camporeporte->getAllParticipantesIdAndName();
                                break;
                            default;
                        }
                        break;
                    case "select-tabla":
                        // OBTENER TODOS LOS REGISTROS DE LA TABLA X
                        switch ($reporte->Valor_Default) {
                            case 'proyecto':
                                // CONSULTAR TABLA DE PROYECTOS
                                $allRegistrosTablas = $camporeporte->getAllProyectosIdAndName();
                                break;
                            case 'participante':
                                // CONSULTAR TODOS LOS PARTICIPANTES CAMPO MULTIPLE
                                $reporte->Valor_Default = $camporeporte->getAllParticipantesIdAndName();
                                // CONSULTAR TODOS LOS PARTICIPANTES GRAL
                                $allRegistrosTablas = $camporeporte->getAllParticipantesIdAndName();
                                break;
                            default;
                        }
                        break;
                    case "multiple":
                        $IDCampos = explode("/", $reporte->Valor_Default);
                        $multipleSubcampos = [];

                        foreach ($IDCampos as $IDCampo) {
                            $multipleSubcampos[] = $camporeporte->getCampoById($IDCampo);
                        }

                        $multipleSubcampos = $createData($multipleSubcampos);
                        $reporte->Valor_Default = $multipleSubcampos;
                        break;
                }
            }
            return $allcamposreportes;
        };

        $allcamposreportes = $createData($allcamposreportes);


        // SECCION PARA CONTROLAR LOS SEGUIMIENTO DE INCIDENCIAS CON EL CAMPO SELECT-STATUS Y TIPO DE INCIDENTE
        $id_Gpo_Valores_Reporte = $_GET['id_Gpo_Valores_Reporte'];
        if (!empty($id_Gpo_Valores_Reporte)) {
            $reportesLlenados = new ReporteLlenado($this->adapter);
            $allDatosReporte = $reportesLlenados->getAllDatosReporteLlenado($id_Gpo_Valores_Reporte);
        }
        // SECCION PARA CONTROLAR LOS SEGUIMIENTO DE INCIDENCIAS CON EL CAMPO SELECT-STATUS Y TIPO DE INCIDENTE


        // SECCION PARA PINTAR EL ID_REPORTE Y EL ID_GPO_VALORES DEL REPORTE A VINCULAR
        $idReportePadreVincular = $_REQUEST['idReportePadreVincular'];
        $idGpoValoresPadreVincular = $_REQUEST['idGpoValoresPadreVincular'];
        $nombre_ReportePadreVincular = $_REQUEST['nombre_ReportePadreVincular'];
        $titulo_ReportePadreVincular = $_REQUEST['titulo_ReportePadreVincular'];

        if ($nombre_ReportePadreVincular == $titulo_ReportePadreVincular && $idReportePadreVincular) {
            // OBTENER NOMBRE DEL REPORTE A TRAVES DE SU ID
            $nombre_ReportePadreVincular = $camporeporte->getAllCatReportesByIdReporte($idReportePadreVincular)[0]->nombre_Reporte;
        }

        if (empty($nombre_ReportePadreVincular) && empty($idGpoValoresPadreVincular)) {
            $idReportePadreVincular = 0;
            $idGpoValoresPadreVincular = 0;
            $nombre_ReportePadreVincular = 0;
            $titulo_ReportePadreVincular = 0;
        }

        // SECCION PARA PINTAR EL ID_REPORTE Y EL ID_GPO_VALORES DEL REPORTE A VINCULAR


        //$reporte = $this->nombreReporteId($tipo_Reporte, 0);
        $nombre_reporte = $allcamposreportes[0]->nombre_Reporte;
        $mensaje = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> Llenar " . $nombre_reporte;

        // /*
        $this->view("index", array(
            "allcamposreportes" => $allcamposreportes, "allsistemas" => $allsistemas, "clasificacion" => $clasificacion,
            "gruposubicaciones" => $gruposubicaciones, "mensaje" => $mensaje, "contrato" => $no_contrato,
            "datosReporte" => $allDatosReporte, "idReportePadreVincular" => $idReportePadreVincular, "nota" => $no_nota,
            "idGpoValoresPadreVincular" => $idGpoValoresPadreVincular, "nombre_ReportePadreVincular" => $nombre_ReportePadreVincular,
            "titulo_ReportePadreVincular" => $titulo_ReportePadreVincular, "titulo_ReportePlanos" => $titulo_ReportePlanos,
            "datosIdAndName" => $datosIdAndName, "allRegistrosTablas" => $allRegistrosTablas
        ));
        // */

    }


    /*--------------------------------- MODIFICAR REPORTE SELECCIONADO ------------------------------------------*/
    public function modificarreporte()
    {
        //CAMPOS PARA LLENAR EL REPORTE
        $id = (int)$_GET["Id_Reporte"];
        $tipo_Reporte = $_GET['tipo_Reporte'];
        $camporeporte = new CampoReporte($this->adapter);
        $allcamposreportes = $camporeporte->getAllCampoReporteByIdReporte($id);


        // VERIFICAR SI EL PROYECTO TIENE GANNT
        $registroGantt = $camporeporte->getIdGanttByid_proyecto($this->id_Proyecto_constant);
        if (empty($registroGantt))
            $existeGantt = false;
        else
            $existeGantt = true;


        //DATOS DEL REPORTE LLENADO
        $id_reportellenado = $_GET["id_Gpo_Valores_ReportePadre"];
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportellenado = $llenadoreporte->getReporteLlenadoById($id_reportellenado);
        $fotografia = new Fotografia($this->adapter);
        $info_fotografia = $fotografia->getAllFotografiasById($id_reportellenado, 1);
        //CONSULTAR UBICACIONES
        $gruposubicaciones = $camporeporte->getAllSeguimientoUbicacionInventarioGpoSistema($this->id_Proyecto_constant);
        $allsistemas = $camporeporte->getAllSeguimientoUbicacionInventario($this->id_Proyecto_constant, $this->id_Area_constant, $tipo_Reporte);
        //CLASIFICACION FOTOGRAFIAS
        $clasificacion = $camporeporte->getAllClasificacionFotografias($this->id_Area_constant);

        //CAMPOS ESPECIALES
        $allRegistrosTablas = null;
        $allEmpleados = null;
        $datosIdAndName = null;
        $multipleSubCampos = null;
        $tipificacion = null;
        $createData = function ($allcamposreportes) use (
            $camporeporte,
            $id_reportellenado,
            &$allRegistrosTablas,
            &$allEmpleados,
            &$datosIdAndName,
            &$multipleSubcampos,
            &$allreportellenado,
            &$createData,
            &$tipificacion
        ) {
            foreach ($allcamposreportes as $reporte) {
                switch ($reporte->tipo_Reactivo_Campo) {
                    case "select-status":
                        // ************************** Mostrar estados del reportes *****************************************
                        $reportesLlenados = new ReporteLlenado($this->adapter);
                        /*$id_Gpo_Valores_Reporte = $_GET['id_Padre'];
                        $datosReporte = $reportesLlenados->getAllReportesLlenadosByIdGpo($id_Gpo_Valores_Reporte);*/
                        // ************************** Mostrar estados del reportes *********************************************
                        $allDatosReporteSeguimiento = $reportesLlenados->getAllDatosReporteLlenado($id_reportellenado);
                        $estadoReporte = $allDatosReporteSeguimiento[0]->campo_EstadoReporte;
                        switch ($estadoReporte) {
                            case 'Abierto':
                                $reporte->Valor_Default = 'Abierto';
                                break;
                            case 'En proceso':
                                $reporte->Valor_Default = 'En proceso';
                                break;
                            case 'Atendido':
                                $reporte->Valor_Default = 'Atendido';
                                break;
                            case 'Validado':
                                $reporte->Valor_Default = 'Validado';
                                break;
                        }
                        break;
                    case "check_list_asistencia":
                        switch ($reporte->Valor_Default) {
                            case 'empleados':
                                // OBTENER TODOS LOS EMPLEADOS
                                $datosIdAndName = $camporeporte->getAllEmpleadosWithIdAndName();
                                break;
                            case 'participantes':
                                // OBTENER TODOS LOS PARTICIPANTES = id_Status_Usuario IN(1,2) AND participante = 1
                                $datosIdAndName = $camporeporte->getAllParticipantesIdAndName();
                                break;
                            default;
                        }
                        break;
                    case "select-tabla":
                        // OBTENER TODOS LOS REGISTROS DE LA TABLA X
                        switch ($reporte->Valor_Default) {
                            case 'proyecto':
                                // CONSULTAR TABLA DE PROYECTOS
                                $allRegistrosTablas = $camporeporte->getAllProyectosIdAndName();
                                break;
                            case 'participante':
                                // CONSULTAR TODOS LOS PARTICIPANTES CAMPO MULTIPLE
                                $reporte->Valor_Default = $camporeporte->getAllParticipantesIdAndName();
                                // CONSULTAR TODOS LOS PARTICIPANTES GRAL
                                $allRegistrosTablas = $camporeporte->getAllParticipantesIdAndName();
                                break;
                            default;
                        }
                        break;
                    case "multiple":
                        $IDCampos = explode("/", $reporte->Valor_Default);

                        $multipleSubcampos = [];
                        foreach ($IDCampos as $IDCampo) {
                            $multipleSubcampos[] = $camporeporte->getCampoById($IDCampo);
                        }

                        $multipleSubcampos = $createData($multipleSubcampos);

                        foreach ($allreportellenado as $valor) {
                            if ($valor->tipo_Reactivo_Campo === "multiple") {
                                $valor->valor_Texto_Reporte = str_replace("\n", "<br>", $valor->valor_Texto_Reporte);
                                $valor->valor_Texto_Reporte = json_decode($valor->valor_Texto_Reporte);
                                $valor->Valor_Default = $multipleSubcampos;
                            }
                        }
                        break;
                }
            }

            return $allcamposreportes;
        };

        $tipo_Reporte = $allreportellenado[0]->tipo_Reporte;

        $allcamposreportes = $createData($allcamposreportes);

        $retornar = $_GET['return'];
        $codigoDocBim = $_GET['codigoDocBim'];

        $id_Gpo_Valores_Reporte = $_GET['id_Gpo_Valores_Reporte'];
        if (!empty($id_Gpo_Valores_Reporte)) {

            $id_ReporteP = $_GET['id_ReporteP'];

            $reportesLlenados = new ReporteLlenado($this->adapter);
            $allDatosReporte = $reportesLlenados->getAllDatosReporteLlenado($id_Gpo_Valores_Reporte);

            $urlAnterior = 'index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' . $id_reportellenado . '&Id_Reporte=' . $id . '&id_Padre=' . $id_Gpo_Valores_Reporte;
            if ($retornar == 3)
                $urlAnterior = 'index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' . $id_Gpo_Valores_Reporte . '&Id_Reporte=' . $id_ReporteP;

        } else {
            switch ($tipo_Reporte) {
                case 0:
                case 6:
                    $tipo_Reporte1 = '0,1';
                    break;
                case 1:
                    $tipo_Reporte1 = 'reportesIncidencia';
                    break;
                case 2:
                    $tipo_Reporte1 = 2;
                    break;
                case 3:
                    $tipo_Reporte1 = 3;
                    break;
            }

            if ($retornar == 0 || $retornar == 1 || $retornar == 6)
                $urlAnterior = 'index.php?controller=SeguimientosReporte&action=index&tipo=' . $tipo_Reporte1;
            if ($retornar == 2)
                $urlAnterior = 'index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=' . $id_reportellenado . '&Id_Reporte=' . $id;
        }

        $reporte = $this->nombreReporteId($tipo_Reporte, 0);
        $mensaje = "<i class='fa fa-pencil-square-o' aria-hidden='true'></i> Modificar " . $reporte;

        // /*
        $this->view("index", array(
            "allcamposreportes" => $allcamposreportes, "allreportellenado" => $allreportellenado, "mensaje" => $mensaje,
            "info_fotografia" => $info_fotografia, "allsistemas" => $allsistemas, "clasificacion" => $clasificacion,
            "gruposubicaciones" => $gruposubicaciones, "datosReporte" => $allDatosReporte, "urlAnterior" => $urlAnterior,
            "datosIdAndName" => $datosIdAndName, "allRegistrosTablas" => $allRegistrosTablas,"camposMultiple" => $multipleSubcampos,
            "existeGantt" => $existeGantt
        ));
        // */

    }

    public function getChildren()
    {
        $idReporte = $_POST["id"];
        $reportes = new ReporteLlenado($this->adapter);
        $gruposubicaciones = $reportes->getElementosLlenadosByIdReporte($idReporte);

        $listado = array();
        $i = 0;
        foreach ($gruposubicaciones as $listelements) {
            $listado[$i] = array(
                'id' => $listelements->id_Gpo_Valores_Reporte,
                'name' => $listelements->titulo_Reporte
            );
            $i++;
        }


        echo json_encode($listado);
    }

    /*---------------------------------- FIRMA ELECTRONICA -------------------------------*/
    public function firmar()
    {
        //DATOS DEL REPORTE
        $id_Gpo = $_GET['id_Gpo_Valores_Reporte'];
        $Id_Reporte = $_GET['Id_Reporte'];
        $usuario = new Usuario($this->adapter);
        $camporeporte = new CampoReporte($this->adapter);
        $fotografia = new Fotografia($this->adapter);
        $datosUsuario = $usuario->getFirmaUserById($_SESSION[ID_USUARIO_SUPERVISOR]);
        $datosReporte = $camporeporte->getReporteLlenadoById($id_Gpo);
        $info_fotografia = $fotografia->getAllFotografiasById($id_Gpo, 1);
        date_default_timezone_set('America/Mexico_City');
        $fecha = date("Y-m-d");
        $hora = date("g:i:s", time());
        $valorReporte = '';
        $datos1 = '';
        $fechaFoto = '';
        $nombreFoto = '';
        if (empty($imageData) || $imageData == '' || $imageData == null) {
            foreach ($datosReporte as $datos) {
                if ($datos->tipo_Valor_Campo == 'int') {
                    $valorReporte = $datos->valor_Entero_Reporte;
                } else {
                    $valorReporte = $datos->valor_Texto_Reporte;
                }
                $datos1 .= $datos->id_Valor_Reporte_Campo . '|' . $datos->id_Configuracion_Reporte . '|' . $valorReporte . '/';
            }
            $dato = $datosReporte[0]->id_Gpo_Valores_Reporte . '/' . $datos1;

        } else {
            foreach ($info_fotografia as $foto) {
                $fechaFoto = $foto->fecha_Fotografia;
                $fechaAno = substr($fechaFoto, 0, 4);
                $fechaMes = substr($fechaFoto, 5, 2);
                $fechaFoto = $fechaAno . $fechaMes;
                $nombreFoto = $foto->nombre_Fotografia;
                $imagen = file_get_contents('img/reportes/' . $fechaFoto . '/' . $nombreFoto);
                $imageData = base64_encode($imagen);
                $imageData = gzcompress($imageData);
            }

            foreach ($datosReporte as $datos) {
                if ($datos->tipo_Valor_Campo == 'int') {
                    $valorReporte = $datos->valor_Entero_Reporte;
                } else {
                    $valorReporte = $datos->valor_Texto_Reporte;
                }
                $datos1 .= $datos->id_Valor_Reporte_Campo . '|' . $datos->id_Configuracion_Reporte . '|' . $valorReporte . '/';
            }
            $dato = $datosReporte[0]->id_Gpo_Valores_Reporte . '/' . $datos1 . '/' . $imageData;
        }

        //DATOS DEL USUARIO
        $private_key_pem = $datosUsuario[0]->llave_privada;
        $public_key_pem = $datosUsuario[0]->llave_publica;

        //FIRMAR REPORTE
        openssl_sign($dato, $firma, $private_key_pem, OPENSSL_ALGO_SHA256);
        file_put_contents('firmas/' . $datosReporte[0]->id_Gpo_Valores_Reporte . '_' . $datosReporte[0]->id_Usuario . '_' . $fecha . '_' . $hora . '.dat', $firma);
        $r = openssl_verify($dato, $firma, $public_key_pem, "sha256WithRSAEncryption");

        // 1 es correcto
        // 0 es incorrecto
        // -1 existe algun error
        if ($r == 1) {
            $firma = addslashes($firma);

            //Insertar Datos de la firma en la tabla firma
            $firmas = new Firma($this->adapter);
            $firmas->set_id_Gpo($id_Gpo);
            $firmas->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
            $firmas->set_fecha_hora($fecha . ' ' . $hora);
            $firmas->set_firma($firma);
            $firmas->set_id_Status(1);
            $firmas->saveNewFirma();

            //CAMBIAR ESTATUS DE REPORTE LLENADO
            $reportes = new ReporteLlenado($this->adapter);
            $reportes->set_id_Etapa(23);
            $reportes->set_id_Gpo_Valores_Reporte($id_Gpo);
            $reportes->modificarEtapaReporteLlenado();
        }

        $this->redirect('ReportesLlenados', 'verreportellenado&id_Gpo_Valores_Reporte=' . $id_Gpo . '&Id_Reporte=' . $Id_Reporte . '&firma=1');
    }


    /*----------------------------------------------------------------------------------------------------------------*/
    /*------------------------------------------- GUARDAR REPORTE LLENADO --------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/
    public function guardarreportellenado()
    {
        $registrarseguimiento = new SeguimientoReporte($this->adapter);
        $reporte = new Reporte($this->adapter);
        //CONSTANTES DEL REPORTE
        $idproyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $id_Modulo = $_SESSION['id_Modulo'];
        $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
        $Id_Gpo_Sistema = $_POST['Id_Gpo_Sistema'];
        $Id_Reporte = $_POST['id_Reporte'];
        $arrayformulario = $reporte->getAllCampoReporteByIdReporte($Id_Reporte);
        $id_Ubicacion = $_POST['IdUbicacion'];
        $id_Area_Reporte = $arrayformulario[0]->Areas;

        $nombreReporte = $arrayformulario[0]->nombre_Reporte;

        $tipo_Reporte = $arrayformulario[0]->tipo_Reporte;

        //OBTENER EL ID GRUPO DE LOS CAMPOS
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportesllenados = $llenadoreporte->getAllReportesLlenados();
        if (count($allreportesllenados) == 0) {
            $grupovalores = 1;
        }
        if (count($allreportesllenados) != 0) {
            $ultimogrupo = $llenadoreporte->getUltimoReporteLlenado();
            $grupovalores = $ultimogrupo + 1;
        }

        $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];

        // ************************ VARIABLE ID_REPORTE PARA EL REPORTE CONTROL DE ASISTENCIA **************************
        $idReporteControlAsistencia = 496;

        //RECORRER EL ARRAY DEL FORMULARIO
        foreach ($arrayformulario as $campo) {
            $idconfiguracionreporte = $campo->id_Configuracion_Reporte;
            $valor = $campo->descripcion_Campo;
            $valorelemento = $campo->tipo_Valor_Campo;
            $campoimagen = $campo->tipo_Reactivo_Campo;
            $idcampo = $campo->id_Campo_Reporte;
            $opcionesvaldefault = explode("/", $campo->Valor_Default);

            /* :::::::::::::::::::::::::::::::::::::::::::::::: IMAGEN :::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "file") {
                $opciones = explode("/", $campo->Valor_Default);
                $contadorfile = 1;
                foreach ($opciones as $opcion) {
                    //NOMBRE DE LA FOTO
                    $fecha_mes = date("F");
                    $fecha_mes = strtolower($fecha_mes);
                    $fecha_mes = substr($fecha_mes, 0, 3);
                    $año_dia = date("dy");
                    $hora = date("His");
                    $carpeta = date("Ym");
                    $nombre_foto = str_replace(' ', '', $opcion);
                    //echo $nombre_foto;
                    if ($_FILES[$nombre_foto]['tmp_name'] != "") {
                        //CARGAR IMAGEN
                        $nombre_img = $_FILES[$nombre_foto]['name'];
                        $tipo_img = $_FILES[$nombre_foto]['type'];
                        $extension = explode(".", $nombre_img);
                        //$nombre_imagen = $nombre_foto."_".$fecha_mes.$año_dia."_".$hora.".".$extension[1];
                        $nombre_imagen = $grupovalores . "_" . $contadorfile . "_" . $nombre_img;
                        $nombre_imagen = str_replace(' ', '_', $nombre_imagen);
                        $target_path = "img/reportes/" . $id_Empresa . "/" . $_SESSION[ID_PROYECTO_SUPERVISOR] . "/" . $carpeta . "/";

                        if (!is_dir($target_path)) {
                            mkdir($target_path, 0777, true);
                        }

                        $target_path = $target_path . basename($nombre_imagen);
                        $img = "img/reportes/" . $id_Empresa . "/" . $_SESSION[ID_PROYECTO_SUPERVISOR] . "/" . $nombre_imagen;
                        $nombre_des = "desc" . $nombre_foto;
                        $nombre_clas = "clas" . $nombre_foto;
                        $nombre_ori = "ori" . $nombre_foto;
                        $descripcion = $_POST[$nombre_des];
                        $clasificacion = $_POST[$nombre_clas];
                        $orientacion = $_POST[$nombre_ori];

                        if (move_uploaded_file($_FILES[$nombre_foto]['tmp_name'], $target_path)) {
                            //echo "The file ".  basename($_FILES[$nombre_foto]['tmp_name']). " has been uploaded";
                        } else {
                            //echo "Erro al cargar imagen!";
                        }
                        //REGISTRAR FOTO EN TABLA FOTOGRAFIAS
                        $llenadofotografia = new Fotografia($this->adapter);
                        $llenadofotografia->set_id_Usuario($id_Usuario);
                        $llenadofotografia->set_id_Modulo(1);
                        $llenadofotografia->set_identificador_Fotografia($grupovalores);
                        $llenadofotografia->set_directorio_Fotografia($clasificacion);
                        $nombre_Fotografia = str_replace('img/reportes/' . $id_Empresa . "/" . $_SESSION[ID_PROYECTO_SUPERVISOR] . "/", '', $img);
                        $llenadofotografia->set_nombre_Fotografia($nombre_Fotografia);
                        $llenadofotografia->set_descripcion_Fotografia($descripcion);
                        $llenadofotografia->set_orientacion_Fotografia(1);
                        $save_fotografia = $llenadofotografia->saveNewFotografia();

                    }
                    $contadorfile++;
                }
            }

            /* ::::::::::::::::::::::::::::::::::::::::::::::::::: CHECKBOX ::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "checkbox") {
                $valores = "";
                if (isset($_POST[$valor])) {
                    foreach ($_POST[$valor] as $valor) {
                        $valores = $valores . $valor . "/";
                    }
                }
                $valores = substr($valores, 0, -1);
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valores);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* :::::::::::::::::::::::::::::::::::::::::: CHECK_LIST_ASISTENCIA ::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "check_list_asistencia") {
                if (isset($_POST[$valor])) {
                    $idsEmpleados = implode('/', $_POST[$valor]);
                    if ($tipo_Reporte == 6 || ($tipo_Reporte == 0 && $idReporteControlAsistencia == $Id_Reporte) || $tipo_Reporte == 7)
                        $arrayIdsEmpleados = $_POST[$valor];
                    elseif ($tipo_Reporte == 9)
                        $idsParticipantes = implode(',', $_POST[$valor]);
                }
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($idsEmpleados);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* ::::::::::::::::::::::::::::::::::::::::::::::::::: FECHA :::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "date") {
                if (isset($_POST[$valor])) {
                    $fecha = new DateTime($_POST[$valor]);;
                    $valores = $fecha->format('Y-m-d');
                    if ($tipo_Reporte == 6 || $tipo_Reporte == 7)
                        $fechaAsistencia = $valores;
                }
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valores);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* ::::::::::::::::::::::::::::::::::::::::::::::: CHECK INCIDENCIA ::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "checkbox-incidencia") {
                if ($_POST[$valor] == "Sí") {
                    $tipo_Reporte = 1;
                }
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($_POST[$valor]);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* :::::::::::::::::::::::::::::::::::::: TEXT CADENAMIENTO ::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "text-cadenamiento") {
                $valorkml = $_POST[$valor . "1"] . "." . $_POST[$valor . "2"];
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorkml);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* :::::::::::::::::::::::::::::::::::::: RANGO DE FECHAS ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "rango_fechas") {
                $fechaInicial = $_POST[$valor . "1"];
                $fechaFinal = $_POST[$valor . "2"];
                $fechaInicialFinal = $fechaInicial . "." . $fechaFinal;

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($fechaInicialFinal);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* :::::::::::::::::::::::::::::::::::::::::: LABEL ::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "label") {
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte('');
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* ::::::::::::::::::::::::::::::::::: CAMPO ESPECIAL MULTIPLE :::::::::::::::::::::::::::::::::::::::::: */
            if ($campoimagen == "multiple") {
                $valorTexto = $_POST[$valor];
                $valorTexto = str_replace('\\"', '\\\\"', $valorTexto);
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorTexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }
            
            /* :::::::::::::::::::::::::::::::::::::::: GENERAL(TABLA) :::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "select-tabla") {
                $valorguardar = $_POST[$valor];
                $valor_texto = str_replace("'", "\'", $valorguardar);
                $idProyectoControlPersonal = $valorelementotexto = $valor_texto;

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }

            /* :::::::::::::::::::::::::::::::::::::::::: SELECT :::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if (($campoimagen == "select") || ($campoimagen == "select-catalogo")) {
                $valorguardar = $_POST[$valor];
                switch ($valorguardar) {

                    case "otroselect":
                        $valorguardar = $_POST['otroselect' . $valor];
                        //echo $valorguardar;
                        if (in_array($valorguardar, $opcionesvaldefault) == FALSE) {
                            $newvalor = $campo->Valor_Default . "/" . $valorguardar;
                            $campo = new Campo($this->adapter);
                            $savevaldefault = $campo->modificarValorDefault($idcampo, $newvalor);
                        }
                        break;
                    case "otrocatalogo":
                        $nuevocategoria = 0;
                        $camporeporte = new CampoReporte($this->adapter);
                        $valorguardar = $_POST['otrocatalogo' . $valor];
                        $categorias = $camporeporte->getCatCategoriaByIdCategoria('id_Categoria');

                        //echo $valorguardar;
                        foreach ($categorias as $categoria) {
                            if ($categoria->concepto == $valorguardar) {
                                $nuevocategoria = 1;
                                $valorguardar = $categoria->idCatalogo;
                                //echo "id registrado".$valorguardar ;
                            }
                        }
                        if ($nuevocategoria < 1) {
                            $camporeporte = new Cat_Categoria($this->adapter);
                            $valor_categoria = explode(",", $campo->Valor_Default);
                            $savecategoria = $camporeporte->saveNewCatCategoria($valorguardar, $valorguardar, $valor_categoria[0]);
                            $valorguardar = $camporeporte->getLastCatCategoria();

                        }
                        break;
                }
                $valorelementoentero = 'NULL';
                //TRATAR CARACTERES ESPECIALES
                $valor_texto = str_replace("'", "\'", $valorguardar);
                $valorelementotexto = $valor_texto;

                if ($tipo_Reporte == 6 && $valor == 'Estatus_asistencia')
                    $estatus_asistencia = $valorelementotexto;

                if ($tipo_Reporte == 0 && $idReporteControlAsistencia == $Id_Reporte && $valor == "Motivo")
                    $motivo = $valorelementotexto;

                //SE REGISTRAN LOS CAMPOS
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte($valorelementoentero);
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            } /* ::::::::::::::::::::::::::::::::::::::::::: OTROS CAMPOS ::::::::::::::::::::::::::::::::::::::::::::*/
            else if ($campoimagen != "file" && $campoimagen != "checkbox" && $campoimagen != "check_list_asistencia"
                && $campoimagen != "date" && $campoimagen != "text-cadenamiento" && $campoimagen != "checkbox-incidencia"
                && $campoimagen != "label" && $campoimagen != "select" && $campoimagen != "select-monitoreo"
                && $campoimagen != "select-catalogo" && $campoimagen != "rango_fechas" && $campoimagen != "select-tabla"
                && $campoimagen != "multiple") {
                if ($valorelemento == "varchar") {
                    $valorelementoentero = 'NULL';
                    //TRATAR CARACTERES ESPECIALES
                    $valor_texto = str_replace("'", "\'", $_POST[$valor]);
                    $valorelementotexto = $valor_texto;
                    if (($campoimagen == 'time' && ($tipo_Reporte == 6 || $tipo_Reporte == 7)) || ($campoimagen == 'time' && $tipo_Reporte == 0 && $idReporteControlAsistencia == $Id_Reporte))
                        $horaAsistencia = $valorelementotexto;

                }
                if ($valorelemento == "int") {
                    if (empty($_POST[$valor])) {
                        $valorelementoentero = 0;
                        $valorelementotexto = NULL;
                    } else {
                        $valorelementoentero = $_POST[$valor];
                        $valorelementotexto = NULL;
                    }
                }
                //SE REGISTRAN LOS CAMPOS
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte($valorelementoentero);
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenado();
            }
        }

        /* ::::::::::::::::::::::::::::::::::::::::::::::: GUARDAR REPORTE LLENADO :::::::::::::::::::::::::::::::::::*/
        $registrarreportellenado = new ReporteLlenado($this->adapter);
        $registrarreportellenado->set_id_Gpo_Valores_Reporte($grupovalores);
        $registrarreportellenado->set_id_Usuario($id_Usuario);

        $id_Reporte = $_POST['id_Reporte'];
        $id_Gpo_Padre = $_POST['id_Gpo_Padre'];
        $titulo_Reporte = $_POST['titulo_Reporte'];
        $latitud_Reporte = $_POST['latitud'];
        $longitud_Reporte = $_POST['longitud'];

        if ($tipo_Reporte == 5) {
            $id_Gpo_Padre = 0;
            $latitud_Reporte = 0;
            $longitud_Reporte = 0;
        }

        $registrarreportellenado->set_id_Reporte($id_Reporte);
        $registrarreportellenado->set_titulo_Reporte($titulo_Reporte);
        $registrarreportellenado->set_id_Gpo_Padre($id_Gpo_Padre);
        $registrarreportellenado->set_latitud_Reporte($latitud_Reporte);
        $registrarreportellenado->set_longitud_Reporte($longitud_Reporte);
        $registrarreportellenado->set_clas_Reporte($tipo_Reporte);
        $saveReporteLlenado = $registrarreportellenado->saveNewReporteLlenado($allreportesllenados);

        $id_EmpresaGral = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        $nombreCarpeta = $_SESSION[CARPETA_SUPERVISOR];

        $funciones = new FuncionesCompartidas();
        $datosFormato = new FormatosCorreo();

        // **************************** INVOCAR MODELO DE ASISTENCIA PARA GUARDAR DATOS ********************************
        if ($saveReporteLlenado && $tipo_Reporte == 6)
            $funciones->InsertarAsistencia($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $estatus_asistencia, $this->id_Proyecto_constant, $grupovalores);

        //$saveReporteLlenado = true;
        if ($saveReporteLlenado && $tipo_Reporte == 0 && $idReporteControlAsistencia == $Id_Reporte) {
            $funciones->procesarInformacionControlAsistencia($arrayIdsEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $idProyectoControlPersonal, $grupovalores);
        }
        // *************************************************************************************************************

        // ********************* OBTENER INFORMACION PARA ENVIAR POR CORREO (REPORTE MINUTA) ***************************
        if ($saveReporteLlenado && $tipo_Reporte == 9) {
            $datosReporte = $datosFormato->obtenerValoresReporteLlenado($grupovalores);
            $destinatarios = $datosFormato->obtenerCorreosParticipantesMinuta($idsParticipantes);
            $datosFormato->enviarMinuta($nombreReporte, $datosReporte, $destinatarios, $nombreCarpeta);
        }

        unset($_SESSION['arrayformulario']);

        /* ::::::::::: NOTIFICACIONES (TELEGRAM, CORREO, PUSH, ETC) DEPENDIENDO DE LA MATRIZ DE COMUNICACION :::::::::*/
        $matriz = new MatrizComunicacion($this->adapter);

        /* :::::::::::::: CAMBIAR ESTADO DE REPORTE PADRE SI EL REPORTE TIENE EL CAMPO SELECT-STATUS  ::::::::::::::::*/
        $reportesLlenado = new ReporteLlenado($this->adapter);
        $valores = new Campo($this->adapter);
        $datoSelectStatus = $valores->getExistCampoSelectStatus($grupovalores);
        if (!empty($datoSelectStatus)) {
            $id_Padre = $_POST['id_Gpo_Padre'];
            $getAllDatosReporteLlenadoSeguimientoIncidencia = $reportesLlenado->getAllDatosReporteLlenado($grupovalores);
            switch ($getAllDatosReporteLlenadoSeguimientoIncidencia[0]->campo_EstadoReporte) {
                // Abierto/En Proceso/Atendido/Validado
                case 'Abierto':
                    $estadoReporte = 2;
                    break;
                case 'En proceso':
                    $estadoReporte = 7;
                    break;
                case 'Atendido':
                    $estadoReporte = 3;
                    break;
                case 'Validado':
                    $estadoReporte = 5;
                    break;
            }
            $reportesLlenado->set_id_Etapa($estadoReporte);
            $reportesLlenado->set_id_Gpo_Valores_Reporte($id_Padre);
            $reportesLlenado->modificarEtapaReporteLlenado();
            /* :::::::: NOTIFICACIONES (TELEGRAM, CORREO, PUSH, ETC) DEPENDIENDO DE LA MATRIZ DE COMUNICACION ::::::::*/
            if ($estadoReporte == 3) {
                $funciones->enviarNotificaciones($grupovalores, $idproyecto, $id_EmpresaGral, $nombreCarpeta);
            }
        } else {
            $datosMatriz = $matriz->getAllIdsMatriz();
            if ($datosMatriz != '' || !empty($datosMatriz)) {
                foreach ($datosMatriz as $dato) {
                    if ($Id_Reporte == $dato->mat_Id_Reporte) {
                        $funciones->enviarNotificaciones($grupovalores, $idproyecto, $id_EmpresaGral, $nombreCarpeta);
                    }

                }
            }
        }


        if (!empty($id_Gpo_Padre)) {
            // ACTUALIZAR REGISTRO EN TABLA DE ASISTENCIA A TRAVES DEL REPORTE TIPO 7 (Termino de jornada laboral)
            if ($tipo_Reporte == 7) {
                $funciones->updateAsistenciaHoraSalida($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $id_Gpo_Padre);
            }


            // VERIFICAR SI EL PROYECTO TIENE GANNT
            $registroGantt = $reportesLlenado->getIdGanttByid_proyecto($this->id_Proyecto_constant);
            if (empty($registroGantt)) {
                /* ::::::::::: GUARDAR EN LA TABLA DE PROCESOS_VALORES SI EL ID_GPO_VALORES Y id_Gpo_Padre EXISTEN  ::::::::::*/
                // Consultar id_Reporte a traves del id_Gpo_Valores del reportePadre           522
                $datosReporteLlenadoPadre = $reportesLlenado->getAllReportesLlenadosByIdGpo($id_Gpo_Padre);
                $id_Reporte_Padre = $datosReporteLlenadoPadre[0]->id_Reporte;
                $proceso = new Procesos($this->adapter);
                $allProcesos = $proceso->getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($id_Reporte_Padre, $id_Reporte);
                $idProceso = $allProcesos[0]->Id_Proceso;
                if ($allProcesos != '' || !empty($allProcesos)) {
                    if ($idProceso != null)
                        $funciones->procesosAvance($id_Gpo_Padre, $grupovalores, $idProceso);
                }
            } else
                $funciones->guardarAvanceActividad($id_Gpo_Padre, $grupovalores, $id_Reporte, $idproyecto);
        }


        /* ::::::::::::::::::::::::::::::::::::::::::::::: BITÁCORA ::::::::::::::::::::::::::::::::::::::::::::::::::*/
        $funciones->guardarBitacora(1, $_SESSION[ID_USUARIO_SUPERVISOR], $grupovalores, 16, '', $idproyecto);

        $this->redirect("ReportesLlenados",
            "verreportellenado&id_Gpo_Valores_Reporte=$grupovalores&Id_Gpo_Sistema=$Id_Gpo_Sistema&Id_Reporte=$Id_Reporte");

    }

    /*----------------------------------------------------------------------------------------------------------------*/
    /*------------------------------- GUARDAR MODIFICACION REPORTE LLENADO -------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/
    public function guardarmodificacionreportellenado()
    {
        $registrarseguimiento = new SeguimientoReporte($this->adapter);
        $reporte = new Reporte($this->adapter);
        //CONSTANTES DEL REPORTE
        $idproyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $id_Modulo = $_SESSION['id_Modulo'];
        $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];

        // ************************ VARIABLE ID_REPORTE PARA EL REPORTE CONTROL DE ASISTENCIA **************************
        $idReporteControlAsistencia = 496;

        //CAMPOS PARA LLENAR EL REPORTE
        $Id_NEW = (int)$_POST["id_Reporte_NEW"];
        $camporeporte = new CampoReporte($this->adapter);
        $allcamposreportes = $camporeporte->getAllCampoReporteByIdReporte($Id_NEW);
        $arrayformulario = $allcamposreportes;

        //DATOS DEL REPORTE LLENADO
        $id_Gpo_Valores_Reporte_NEW = $_POST["id_Gpo_Valores_Reporte_NEW"];
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportellenado = $llenadoreporte->getReporteLlenadoById($id_Gpo_Valores_Reporte_NEW);
        $tipo_Reporte = $arrayformulario[0]->tipo_Reporte;

        $idUsuarioNotificacion = 0;
        $id_gpo_Valores_Reporte1 = 0;
        foreach ($allreportellenado as $iduser) {
            $idUsuarioNotificacion = $iduser->id_Usuario;
            $id_gpo_Valores_Reporte1 = $iduser->id_Gpo_Valores_Reporte;
        }

        $arrayreportellenado = $allreportellenado;
        //OBTENER EL ID GRUPO DE LOS CAMPOS
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportellenado = $llenadoreporte->getAllReportesLlenados();
        $grupovalores = $arrayreportellenado[0]->id_Gpo_Valores_Reporte;
        $id_Reporte = $arrayreportellenado[0]->id_Reporte;
        $contador = 0;
        $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        //RECORRER EL ARRAY DEL FORMULARIO
        foreach ($arrayformulario as $campo) {
            $idconfiguracionreporte = $campo->id_Configuracion_Reporte;
            $valor = $campo->descripcion_Campo;
            $valorelemento = $campo->tipo_Valor_Campo;
            $campoimagen = $campo->tipo_Reactivo_Campo;
            $id_Valor_Reporte_Campo = $arrayreportellenado[$contador]->id_Valor_Reporte_Campo;
            $idcampo = $campo->id_Campo_Reporte;
            $opcionesvaldefault = explode("/", $campo->Valor_Default);
            /* :::::::::::::::::::::::::::::::::::::::::::::: IMAGEN :::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "file") {
                $carpeta = date("Ym");
                $cantidad_tomadas = $_POST['fotos_tomadas'];
                $cantidad_restantes = $_POST['fotos_restantes'];
                $llenadofoto = new Fotografia($this->adapter);
                //------------------------------------------- ACTUALIZAR FOTO ------------------------------------------
                for ($x = 1; $x <= $cantidad_tomadas; $x++) {
                    $nombre_foto = "nombreimg" . $x;
                    $nombre_img = "nombre" . $x;
                    $id_foto = $_POST["id" . $x];
                    $descripcion = $_POST["desc" . $x];
                    $clasificacion = $_POST["clas" . $x];
                    $orientacion = $_POST["ori" . $x];

                    if ($_FILES[$nombre_foto]['tmp_name'] != "") {
                        //CARGAR IMAGEN
                        $nombre_img = $_POST[$nombre_img];
                        $tipo_img = $_FILES[$nombre_foto]['type'];
                        $extension = explode(".", $nombre_img);
                        $nombre_imagen = $nombre_img;
                        $nombre_imagen = str_replace(' ', '_', $nombre_imagen);
                        $target_path = "img/reportes/" . $id_Empresa . "/" . $_SESSION[ID_PROYECTO_SUPERVISOR] . "/" . $carpeta . "/";
                        $target_path = $target_path . basename($nombre_imagen);
                        if (move_uploaded_file($_FILES[$nombre_foto]['tmp_name'], $target_path)) {
                        } else {
                        }
                    }
                    $llenadofoto->set_id_Fotografia($id_foto);
                    $llenadofoto->set_directorio_Fotografia($clasificacion);
                    $llenadofoto->set_orientacion_Fotografia(1);
                    $save_fotografia = $llenadofoto->modificarFotografia($descripcion);
                }

                //--------------------------------------------- NUEVA FOTO ---------------------------------------------
                $siguiente_foto = $cantidad_tomadas + 1;
                $fecha_mes = date("F");
                $fecha_mes = strtolower($fecha_mes);
                $fecha_mes = substr($fecha_mes, 0, 3);
                $año_dia = date("dy");
                $hora = date("His");
                for ($y = 1; $y <= $cantidad_restantes; $y++) {
                    $fotos_restantes = $_POST['fotos_restantes'];
                    $nombre_foto_restante = "fotorestante" . $y;
                    $descripcion = $_POST["descrestante" . $y];
                    $clasificacion = $_POST["clasrestante" . $y];
                    $orientacion = $_POST["orirestante" . $y];
                    $tipo_img = $_FILES["fotorestante" . $y]['type'];
                    $nombre_img = $_FILES["fotorestante" . $y]['name'];
                    //REGISTRAR MODIFICACION FOTO EN TABLA FOTOGRAFIAS
                    if ($_FILES[$nombre_foto_restante]['tmp_name'] != "") {
                        $extension = explode("/", $tipo_img);
                        //$nombre_fotografia = "Foto".$siguiente_foto."_".$fecha_mes.$año_dia."_".$hora.".".$extension[1];
                        $nombre_fotografia = $grupovalores . "_" . $siguiente_foto . "_" . $nombre_img;
                        $nombre_imagen = $nombre_fotografia;
                        $nombre_imagen = str_replace(' ', '_', $nombre_imagen);
                        $target_path = "img/reportes/" . $id_Empresa . "/" . $_SESSION[ID_PROYECTO_SUPERVISOR] . "/" . $carpeta . "/";

                        if (!is_dir($target_path)) {
                            mkdir($target_path, 0777, true);
                        }

                        $target_path = $target_path . basename($nombre_imagen);
                        if (move_uploaded_file($_FILES[$nombre_foto_restante]['tmp_name'], $target_path)) {
                        } else {
                        }
                        $llenadofoto->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
                        $llenadofoto->set_id_Modulo(1);
                        $llenadofoto->set_identificador_Fotografia($grupovalores);
                        $llenadofoto->set_directorio_Fotografia($clasificacion);
                        $llenadofoto->set_nombre_Fotografia($nombre_imagen);
                        $llenadofoto->set_descripcion_Fotografia($descripcion);
                        $llenadofoto->set_orientacion_Fotografia(1);
                        $save_fotografia = $llenadofoto->saveNewFotografia();
                    }
                    $siguiente_foto++;
                }
            }

            /* ::::::::::::::::::::::::::::::::::::::::::::::: CHECKBOX ::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "checkbox") {
                $valores = "";
                if (isset($_POST[$valor])) {
                    foreach ($_POST[$valor] as $valor) {
                        $valores = $valores . $valor . "/";
                    }
                }
                $valores = substr($valores, 0, -1);
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valores);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }

            /* :::::::::::::::::::::::::::::::::::::::::: CHECK_LIST_ASISTENCIA ::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "check_list_asistencia") {
                if (isset($_POST[$valor])) {
                    $idsEmpleados = implode('/', $_POST[$valor]);
                    // OBTENER NUEVOS IDS A GUARDAR
                    if ($tipo_Reporte == 6 || ($tipo_Reporte == 0 && $idReporteControlAsistencia == $id_Reporte) || $tipo_Reporte == 7)
                        $arrayIdsEmpleados = $_POST[$valor];
                }
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($idsEmpleados);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                // ***** QUITAR COMENTARIO
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }


            /* :::::::::::::::::::::::::::::::::::::::::::::::::::: FECHA ::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "date") {
                $valores = "";
                if (isset($_POST[$valor])) {
                    $fecha = new DateTime($_POST[$valor]);
                    $valores = $fecha->format('Y-m-d');
                    if ($tipo_Reporte == 6 || $tipo_Reporte == 7)
                        $fechaAsistencia = $valores;
                }
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valores);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                // ***** QUITAR COMENTARIO       $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }

            /* ::::::::::::::::::::::::::::::::::::::::::: CHECK INCIDENCIA ::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "checkbox-incidencia") {
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($_POST[$valor]);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }

            /* :::::::::::::::::::::::::::::::::::::: TEXT CADENAMIENTO ::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "text-cadenamiento") {
                $valorkml = $_POST[$valor . "1"] . "." . $_POST[$valor . "2"];
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorkml);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }


            /* :::::::::::::::::::::::::::::::::::::: RANGO DE FHECAS ::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "rango_fechas") {
                $fechaInicial = $_POST[$valor . "1"];
                $fechaFinal = $_POST[$valor . "2"];
                $fechaInicialFinal = $fechaInicial . "." . $fechaFinal;
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($fechaInicialFinal);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }


            /* :::::::::::::::::::::::::::::::::::::::::::: LABEL ::::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "label") {
                //if($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte){
                $contador++;
                //}
            }

            /* :::::::::::::::::::::::::::::::::::::::: GENERAL(TABLA) :::::::::::::::::::::::::::::::::::::::::::::::*/
            if ($campoimagen == "select-tabla") {
                $valorguardar = $_POST[$valor];
                $valor_texto = str_replace("'", "\'", $valorguardar);
                $idProyectoControlPersonal = $valorelementotexto = $valor_texto;

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }

            /* :::::::::::::::::::::::::::::::::::::::::::: SELECT :::::::::::::::::::::::::::::::::::::::::::::::::::*/
            if (($campoimagen == "select") || ($campoimagen == "select-catalogo")) {
                $valorguardar = $_POST[$valor];
                switch ($valorguardar) {

                    case "otroselect":
                        $valorguardar = $_POST['otroselect' . $valor];
                        //echo $valorguardar;
                        if (in_array($valorguardar, $opcionesvaldefault) == FALSE) {
                            $newvalor = $campo->Valor_Default . "/" . $valorguardar;
                            $campo = new Campo($this->adapter);
                            $savevaldefault = $campo->modificarValorDefault($idcampo, $newvalor);
                        }
                        break;
                    case "otrocatalogo":
                        $nuevocategoria = 0;
                        $camporeporte = new CampoReporte($this->adapter);
                        $valorguardar = $_POST['otrocatalogo' . $valor];
                        $categorias = $camporeporte->getCatCategoriaByIdCategoria('id_Categoria');

                        //echo $valorguardar;
                        foreach ($categorias as $categoria) {
                            if ($categoria->concepto == $valorguardar) {
                                $nuevocategoria = 1;
                                $valorguardar = $categoria->idCatalogo;
                            }
                        }

                        if ($nuevocategoria < 1) {
                            $camporeporte = new Cat_Categoria($this->adapter);
                            $valor_categoria = explode(",", $campo->Valor_Default);
                            $savecategoria = $camporeporte->saveNewCatCategoria($valorguardar, $valorguardar, $valor_categoria[0]);
                            $valorguardar = $camporeporte->getLastCatCategoria();

                        }
                        break;
                }
                $valorelementoentero = 'NULL';
                //TRATAR CARACTERES ESPECIALES
                $valor_texto = str_replace("'", "\'", $valorguardar);
                $valorelementotexto = $valor_texto;

                if ($tipo_Reporte == 6 && $valor == 'Estatus_asistencia')
                    $estatus_asistencia = $valorelementotexto;

                if ($tipo_Reporte == 0 && $idReporteControlAsistencia == $id_Reporte && $valor == "Motivo")
                    $motivo = $valorelementotexto;


                //SE REGISTRAN LOS CAMPOS
                $llenadoreporte = new LlenadoReporte($this->adapter);

                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();

                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            } /* ::::::::::::::::::::::::::::::::::::::::: OTROS CAMPOS ::::::::::::::::::::::::::::::::::::::::::::::*/
            elseif ($campoimagen != "file" && $campoimagen != "checkbox" && $campoimagen != "date"
                && $campoimagen != "text-cadenamiento" && $campoimagen != "checkbox-incidencia" && $campoimagen != "label"
                && $campoimagen != "select" && $campoimagen != "select-monitoreo" && $campoimagen != "select-catalogo"
                && $campoimagen != "check_list_asistencia" && $campoimagen != "rango_fechas" && $campoimagen != "select-tabla") {

                $valorelementoentero = 'NULL';
                //TRATAR CARACTERES ESPECIALES
                $valor_texto = str_replace("'", "", $_POST[$valor]);
                $valorelementotexto = $valor_texto;

                if (($campoimagen == 'time' && $tipo_Reporte == 0 && $idReporteControlAsistencia == $id_Reporte) || ($campoimagen == 'time' && $tipo_Reporte == 7))
                    $horaAsistencia = $valorelementotexto;


                if ($valorelemento == "int") {
                    $valorelementoentero = $_POST[$valor];
                    $valorelementotexto = NULL;
                }
                //SE REGISTRAN LOS CAMPOS
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Valor_Reporte_Campo($id_Valor_Reporte_Campo);
                $llenadoreporte->set_id_Proyecto($idproyecto);
                $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                $llenadoreporte->set_valor_Entero_Reporte($valorelementoentero);
                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->modificarLlenado();
                if ($idconfiguracionreporte == $arrayreportellenado[$contador]->id_Configuracion_Reporte) {
                    $contador++;
                }
            }
        }

        $funciones = new FuncionesCompartidas();

        // **************************** INVOCAR MODELO DE ASISTENCIA PARA GUARDAR DATOS ********************************
        if ($tipo_Reporte == 6)
            $funciones->ModificarAsistencia($grupovalores, $estatus_asistencia, $arrayIdsEmpleados);


        if ($tipo_Reporte == 0 && $idReporteControlAsistencia == $id_Reporte) {
            $funciones->modificarInformacionControlAsistencia($arrayIdsEmpleados, $fechaInicial, $fechaFinal, $horaAsistencia, $motivo, $idProyectoControlPersonal, $grupovalores);
        }
        // *************************************************************************************************************


        $id_Gpo_Padre = $_POST['id_Gpo_Padre'];

        //REGISTRAR MODIFICACION DEL TITULO DEL REPORTE
        $llenadotitulo = new ReporteLlenado($this->adapter);
        $llenadotitulo->set_id_Gpo_Valores_Reporte($grupovalores);
        $llenadotitulo->set_titulo_Reporte($_POST['titulo_Reporte']);
        $llenadotitulo->set_id_Gpo_Padre($id_Gpo_Padre);
        $llenadotitulo->set_latitud_Reporte($_POST['latitud']);
        $llenadotitulo->set_longitud_Reporte($_POST['longitud']);
        $save_titulo = $llenadotitulo->modificarTituloReporteLlenado();

        /* ::::::::::::::::::::::::::::::::::::::::: NOTIFICACION ::::::::::::::::::::::::::::::::::::::::::::::::*/
        $funciones->guardarNotificacion($idUsuarioNotificacion, $_SESSION[ID_USUARIO_SUPERVISOR], $id_gpo_Valores_Reporte1, 1);


        if (!empty($id_Gpo_Padre)) {
            // ACTUALIZAR REGISTRO EN TABLA DE ASISTENCIA A TRAVES DEL REPORTE TIPO 7 (Termino de jornada laboral)
            if ($tipo_Reporte == 7) {
                $funciones->updateAsistenciaHoraSalida($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $id_Gpo_Padre);
            }

            // VERIFICAR SI EL PROYECTO TIENE GANNT
            $registroGantt = $llenadotitulo->getIdGanttByid_proyecto($this->id_Proyecto_constant);
            if (empty($registroGantt)) {
                /* ::::::::::: GUARDAR EN LA TABLA DE PROCESOS_VALORES SI EL ID_GPO_VALORES Y id_Gpo_Padre EXISTEN  ::::::::::*/
                // Consultar id_Reporte a traves del id_Gpo_Valores del reportePadre           522
                $datosReporteLlenadoPadre = $llenadotitulo->getAllReportesLlenadosByIdGpo($id_Gpo_Padre);
                $id_Reporte_Padre = $datosReporteLlenadoPadre[0]->id_Reporte;

                $proceso = new Procesos($this->adapter);
                $allProcesos = $proceso->getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($id_Reporte_Padre, $id_Reporte);
                $idProceso = $allProcesos[0]->Id_Proceso;
                if ($allProcesos != '' || !empty($allProcesos)) {
                    if ($idProceso != null)
                        $funciones->procesosAvance($id_Gpo_Padre, $grupovalores, $idProceso);
                }

            } else {
                $id_nodoAnterior = $funciones->obtenerIdNodoAnterior($grupovalores, $this->id_Proyecto_constant);
                $funciones->modificarGantt($id_Gpo_Padre, $grupovalores, $id_Reporte, $idproyecto, $id_nodoAnterior);
            }

        }

        /* ::::::::::::::::::::::::::::::::::::::::::: BITÁCORA ::::::::::::::::::::::::::::::::::::::::::::::::::*/
        $funciones->guardarBitacora(1, $_SESSION[ID_USUARIO_SUPERVISOR], $grupovalores, 17, '', $idproyecto);

        $this->redirect("ReportesLlenados", "verreportellenado&id_Gpo_Valores_Reporte=$grupovalores&Id_Reporte=$id_Reporte&id_Padre=$id_Gpo_Padre");
    }


    public function saveConfig()
    {
        $datosRecibidos = json_decode(file_get_contents('php://input'));
        $json = $datosRecibidos->json;
        $idReporte = $datosRecibidos->id_reporte;
        $reportes = new ReporteLlenado($this->adapter);
        $resultado = $reportes->saveConfiguracionReporte($idReporte, $json);

        $mensaje = $resultado ? 'Actualización Exitosa' : 'Actualización Fallida';
        $error = $resultado ? 'success' : 'error';

        echo json_encode([
            'code' => 200,
            'status' => $error,
            'message' => $mensaje
        ]);
    }

    public function getConfig()
    {
        $datosRecibidos = json_decode(file_get_contents('php://input'));
        $idReporte = $datosRecibidos->id_reporte;
        $reportes = new ReporteLlenado($this->adapter);
        $resultado = $reportes->getConfigReporte($idReporte);

        echo !is_null($resultado[0]->conf_fields) ? $resultado[0]->conf_fields : '{}';
    }

    public function borrar()
    {
        $id_Gpo_Valores = $_REQUEST['id_gpo_valores'];

        $reporteLlenado = new LlenadoReporte($this->adapter);
        $tipo_Reporte = $reporteLlenado->getAllReportesLlenadosByIdGpo($id_Gpo_Valores)[0]->clas_Reporte;

        switch ($tipo_Reporte) {
            case 0:
            case 6:
                $tipo_Reporte = '0,1';
                break;
            case 1:
                $tipo_Reporte = 'reportesIncidencia';
                break;
            case 2:
                $tipo_Reporte = '2';
                break;
            case 3:
                $tipo_Reporte = '3';
                break;
            case 4:
                // RUTA DEL REPORTE PADRE (DETALLE DE LA INCIDENCIA)
                $id_Gpo_Padre = $reporteLlenado->getAllReportesLlenadosByIdGpo($id_Gpo_Valores)[0]->id_Gpo_Padre;
                $ruta = "index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte=$id_Gpo_Padre";
                break;
            case 5:
                // CONSULTAR REPORTE PARA SABER SU TITULO (CODIGO)
                $codigo = $reporteLlenado->getAllReportesLlenadosByIdGpo($id_Gpo_Valores)[0]->titulo_Reporte;
                $tipo_Reporte = "5&codigo=$codigo";
                break;
        }

        if ($tipo_Reporte != 4)
            $ruta = "index.php?controller=SeguimientosReporte&action=index&tipo={$tipo_Reporte}";

        if (isset($id_Gpo_Valores)) {
            $response = $reporteLlenado->deleteElementoById($id_Gpo_Valores, 'ReportesLlenados');
            if ($response) {
                $status = $response;
                $mensaje = 'Se eliminó el reporte correctamente';
                /* ::::::::::::::::::::::::::::::::::::::::::::::: BITÁCORA ::::::::::::::::::::::::::::::::::::::::::::::::::*/
                $funciones = new FuncionesCompartidas();
                $funciones->guardarBitacora(1, $_SESSION[ID_USUARIO_SUPERVISOR], $id_Gpo_Valores, 19, '', $this->id_Proyecto_constant);
            } else {
                $status = $response;
                $mensaje = 'No se pudo eliminar el reporte';
            }
        } else {
            $status = false;
            $mensaje = 'No se pudo eliminar el reporte';
        }

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status
        ]);
    }

    public function borrarDef()
    {
        $id_Gpo_Valores = $_REQUEST['id_gpo_valores'];

        // VERIFICAR QUE EL DATO VENGA
        if (isset($id_Gpo_Valores)) {
            // CAMBIAR EL STATUS DEL REPORTE LLENADO
            $reporte = new LlenadoReporte($this->adapter);
            $result = $reporte->cambiarStatusReporteLlenado(2, $id_Gpo_Valores);
            if ($result) {
                $mensaje = 'El reporte se ha borrado definitivamente';
                $status = true;
            } else {
                $status = false;
                $mensaje = 'No se pudo eliminar el reporte';
            }
        } else {
            $status = false;
            $mensaje = 'No se pudo eliminar el reporte';
        }

        $ruta = 'index.php?controller=SeguimientosReporte&action=index&tipo=papelera';

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status
        ]);
    }

    public function restaurarReporteLlenado()
    {
        $id_Gpo_Valores = $_REQUEST['id_gpo_valores'];

        // VERIFICAR QUE EL DATO VENGA
        if (isset($id_Gpo_Valores)) {
            // CAMBIAR EL STATUS DEL REPORTE LLENADO
            $reporte = new LlenadoReporte($this->adapter);
            $result = $reporte->cambiarStatusReporteLlenado(1, $id_Gpo_Valores);
            if ($result) {
                $mensaje = 'El reporte se ha restaurado correctamente';
                $status = true;
            } else {
                $status = false;
                $mensaje = 'No se pudo eliminar el reporte';
            }
        } else {
            $status = false;
            $mensaje = 'No se pudo eliminar el reporte';
        }

        $ruta = 'index.php?controller=SeguimientosReporte&action=index&tipo=papelera';

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status
        ]);
    }


}
