<?php

class ReportesController extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];

        require_once 'core/FuncionesCompartidas.php';
    }

    /*------------------------------------------ VISTA DE TODOS LOS REPORTES -----------------------------------------*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-cogs' aria-hidden='true'></i> Configuración de Plantillas";
        }
        $reporte = new Reporte($this->adapter);
        $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
        $area = new Area($this->adapter);
        $allareas = $area->getAllArea();

        $perfil = new perfil($this->adapter);
        // PERFILES DE LA EMPRESA
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $noId_Perfil_User = '';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        // *************** OBTENER LOS REPORTES DE SIGUIMIENTOS ********************
        $allReportesSeguimiento = $reporte->getAllReportesSeguimientoByTipoReporte($this->id_Proyecto_constant);


        // ******************** VALIDAR SI YA EXISTE UN REPORTE DE TIPO 5 EN EL PROYECTO X ********************
        $reporteTipoFive = $reporte->getReporteByProyectoAndTipoReporteDocBim($this->id_Proyecto_constant);


        // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 6 (ASISTENCIA) EN EL PROYEYCTO ******************
        $reporteTipoSix = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 6);


        // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 7 (ASISTENCIA) EN EL PROYEYCTO ******************
        $reporteTipoSeven = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 7);

        // *********** VALIDAR SI YA EXISTE UN REPORTE DE TIPO 8 (DOCUMENTOS ENTREGABLES) EN EL PROYEYCTO **************
        $reporteTipoOcho = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 8);

        // ***************** VALIDAR SI YA EXISTE UN REPORTE DE TIPO 9 (MINUTA ) EN EL PROYEYCTO ***********************
        $reporteTipoNueve = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 9);


        // *******************SECCION DE DUPLICAR CONFIGRACION DE REPORTES DE OTROS PROYECTOS ********************
        $allProyectos = $reporte->getAllProyectosDuplicar();

        $this->view("index", array(
            "allreportes" => $allreportes, "allareas" => $allareas, "mensaje" => $mensaje, "allProyectos" => $allProyectos,
            "allperfiles" => $allperfiles, "allReportesSeguimiento" => $allReportesSeguimiento, "insercion" => $insercion,
            "newElemento" => $newElemento, "reporteTipoFive" => $reporteTipoFive, "reporteTipoSix" => $reporteTipoSix,
            "reporteTipoSeven" => $reporteTipoSeven, "reporteTipoOcho" => $reporteTipoOcho, "reporteTipoNueve" => $reporteTipoNueve
        ));

    }

    /*------------------------------------------ DESCARGABLES -----------------------------------------*/
    public function descargables()
    {
        $mensaje = "<i class='fa fa-download' aria-hidden='true'></i> Descargables";

        $this->view("index", array(
            "mensaje" => $mensaje
        ));
    }

    /*-------------------------------------- MANUALES DEL SISTEMA -----------------------------------------*/
    public function ayuda()
    {
        $mensaje = "Material de Ayuda";

        $this->view("index", array(
            "mensaje" => $mensaje
        ));
    }

    /*-------------------------------------------- VISTA MODIFICAR REPORTE -------------------------------------------*/
    /*public function modificar()
    {
        $idReporte = $_POST["idReporte"];

        if (isset($idReporte)) {
            $reporte = new Reporte($this->adapter);
            //$allreportes = $reporte->getAllReporte($id_Proyecto);
            $area = new Area($this->adapter);
            $allareas = $area->getAllArea();

            $perfil = new Perfil($this->adapter);
            // PERFILES DE LA EMPRESA
            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $noId_Perfil_User = '';
                $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            } else {
                $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
                $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            }

            $datosreporte = $reporte->getReporteById($idReporte);
            $areas = explode(",", $datosreporte->Areas);

            $perfiles = explode(",", $datosreporte->perfiles_firma);

            // VALIDAR SI YA EXISTE UN REPORTE DE TIPO 5 EN EL PROYECTO X
            $reporteTipoFive = $reporte->getReporteByProyectoAndTipoReporteDocBim($this->id_Proyecto_constant);

            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 6 (ASISTENCIA) EN EL PROYEYCTO ******************
            $reporteTipoSix = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 6);


            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 7 (ASISTENCIA) EN EL PROYEYCTO ******************
            $reporteTipoSeven = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 7);

            //OBTENER LOS REPORTES DE SIGUIMIENTOS
            $allReportesSeguimiento = $reporte->getAllReportesSeguimientoByTipoReporte($this->id_Proyecto_constant);
        }

        echo json_encode([
            "datosreporte" => $datosreporte, "allAreas" => $allareas, "allPerfiles" => $allPerfiles,
            "areas" => $areas, "perfiles" => $perfiles, "reporteTipoFive" => $reporteTipoFive,
            "allReportesSeguimiento" => $allReportesSeguimiento, "reporteTipoSix" => $reporteTipoSix,
            "reporteTipoSeven" => $reporteTipoSeven
        ]);

    }*/

    /*-------------------------------------------- VISTA MODIFICAR REPORTE -------------------------------------------*/
    public function modificar()
    {
        $idReporte = $_POST["idReporte"];

        if (isset($idReporte)) {
            $reporte = new Reporte($this->adapter);
            //$allreportes = $reporte->getAllReporte($id_Proyecto);
            $area = new Area($this->adapter);
            $allareas = $area->getAllArea();

            $perfil = new Perfil($this->adapter);
            // PERFILES DE LA EMPRESA
            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $noId_Perfil_User = '';
                $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            } else {
                $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
                $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
            }

            $datosreporte = $reporte->getReporteById($idReporte);
            $areas = explode(",", $datosreporte->Areas);

            $perfiles = explode(",", $datosreporte->perfiles_firma);

            $tipoDatos = [
                "Reporte",
                "Incidencia",
                "Ubicación",
                "Inventario",
                "Seguimiento a Incidencia",
                "Documento BIM",
                "Asistencia",
                "Termino de jornada laboral",
                "Documento Entregable",
                "Minuta"
            ];

            // VALIDAR SI YA EXISTE UN REPORTE DE TIPO 5 EN EL PROYECTO X
            $reporteTipoFive = $reporte->getReporteByProyectoAndTipoReporteDocBim($this->id_Proyecto_constant);
            if ($reporteTipoFive) {
                if ($datosreporte->tipo_Reporte == 5)
                    $tipoDatos[5] = "Documento BIM";
                else
                    $tipoDatos[5] = "";
            }

            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 6 (ASISTENCIA) EN EL PROYEYCTO ******************
            $reporteTipoSix = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 6);
            if ($reporteTipoSix) {
                if ($datosreporte->tipo_Reporte == 6)
                    $tipoDatos[6] = "Asistencia";
                else
                    $tipoDatos[6] = "";
            }

            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 7 (ASISTENCIA) EN EL PROYEYCTO ******************
            $reporteTipoSeven = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 7);
            if ($reporteTipoSeven) {
                if ($datosreporte->tipo_Reporte == 7)
                    $tipoDatos[7] = "Termino de jornada laboral";
                else
                    $tipoDatos[7] = "";
            }

            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 7 (ASISTENCIA) EN EL PROYEYCTO ******************
            $reporteTipoOcho = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 8);
            if ($reporteTipoOcho) {
                if ($datosreporte->tipo_Reporte == 8)
                    $tipoDatos[8] = "Documento Entregable";
                else
                    $tipoDatos[8] = "";
            }

            // ************* VALIDAR SI YA EXISTE UN REPORTE DE TIPO 9 (MINUTA) EN EL PROYEYCTO ******************
            $reporteTipoNueve = $reporte->getPlantillaByIdProyectoAndTipoReporte($this->id_Proyecto_constant, 9);
            if ($reporteTipoNueve) {
                if ($datosreporte->tipo_Reporte == 9)
                    $tipoDatos[9] = "Minuta";
                else
                    $tipoDatos[9] = "";
            }


            //OBTENER LOS REPORTES DE SIGUIMIENTOS
            $allReportesSeguimiento = $reporte->getAllReportesSeguimientoByTipoReporte($this->id_Proyecto_constant);
        }

        echo json_encode([
            "datosreporte" => $datosreporte, "allAreas" => $allareas, "allPerfiles" => $allPerfiles,
            "areas" => $areas, "perfiles" => $perfiles, "allReportesSeguimiento" => $allReportesSeguimiento,
            'tipoDatos' => $tipoDatos, 'reporteTipoNueve' => $reporteTipoNueve
        ]);

    }



    /*------------------------------------------ METODO CREAR NUEVO REPORTE ------------------------------------------*/
    public function guardarnuevo()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["nombre_Reporte"])) {
            $save = "Ingresar nombre de reporte";
        } else if (empty($_POST["area"])) {
            $save = "Seleccione un área para el reporte";
        } //SE CREA NUEVO REPORTE
        else {

            if ($_POST['seguimientoIncidencia'] != 0)
                $id_Seguimieto = $_POST['seguimientoIncidencia'];
            else
                $id_Seguimieto = 0;

            $tiempo = $_POST["tiempo_Reporte"];
            $alarma = $_POST["tiempo_Alarma"];
            $revision = $_POST["tiempo_Revision"];
            $tipo_Reporte = $_POST["tipo_Reporte"];

            if (empty($tiempo) && empty($alarma) && empty($revision)) {
                $tiempo = 0;
                $alarma = 0;
                $revision = 0;
            }

            $areas = implode(",", $_POST["area"]);

            $perfiles = $_POST["perfil"];

            if (empty($perfiles))
                $perfiles = '';
            else
                $perfiles = implode(",", $perfiles);

            $tiempo_Reporte = ($tiempo * 60);
            $tiempo_Alarma = ($alarma * 60);
            $tiempo_Revision = ($revision * 60);

            $id_Proyecto = $_POST['id_Proyecto'];

            $reporte = new Reporte($this->adapter);
            $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
            $reporte->set_id_Proyecto($id_Proyecto);
            $nombreReporte = $_POST["nombre_Reporte"];
            $reporte->set_nombre_Reporte($nombreReporte);
            $reporte->set_descripcion_Reporte($_POST["descripcion_Reporte"]);
            $reporte->set_Areas($areas);
            $reporte->set_tiempo_Reporte($tiempo_Reporte);
            $reporte->set_tiempo_Alarma($tiempo_Alarma);
            $reporte->set_tiempo_Revision($tiempo_Revision);
            $reporte->set_tipo_Reporte($tipo_Reporte);
            $reporte->set_Perfiles($perfiles);
            $reporte->set_id_Seguimiento($id_Seguimieto);
            $save = $reporte->saveNewReporte($allreportes);

            if ($save == 1) {

                $insercion = 1;
                $mensaje = 'Se ha creado el reporte: "' . $nombreReporte . '"';

                //OBTENER EL ID de los reportes
                $reporte = new Reporte($this->adapter);
                $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);

                if (count($allreportes) == 0) {
                    $id_Reporte = 1;
                }
                if (count($allreportes) != 0) {
                    $ultimogrupo = $reporte->getUltimoRegistro();
                    $id_Reporte = $ultimogrupo;
                }

                // **************************** INSERTAR CAMPOS DE MANERA GENERAL TIPO 0, 2, 3 *************************
                //if ($tipo_Reporte == 0 || $tipo_Reporte == 2 || $tipo_Reporte == 3) {
                // ********************************INSERTAR CAMPOS POR SI NO EXISTEN *******************************
                $campo = new Campo($this->adapter);
                $allcampos = $campo->getAllCampo();
                $campo->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo = 'Fecha';
                $campo->set_nombre_Campo($nombreCampo);
                $campo->set_descripcion_Campo($nombreCampo);
                $campo->set_tipo_Valor_Campo('varchar');
                $campo->set_tipo_Reactivo_Campo('date');
                $campo->set_Valor_Default('');
                $campo->saveNewCampoPlantilla($allcampos);

                $campo1 = new Campo($this->adapter);
                $campo1->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo1 = 'Hora';
                $campo1->set_nombre_Campo($nombreCampo1);
                $campo1->set_descripcion_Campo($nombreCampo1);
                $campo1->set_tipo_Valor_Campo('varchar');
                $campo1->set_tipo_Reactivo_Campo('time');
                $campo1->set_Valor_Default('');
                $campo1->saveNewCampoPlantilla($allcampos);

                $campo2 = new Campo($this->adapter);
                $campo2->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo2 = 'Observaciones';
                $campo2->set_nombre_Campo($nombreCampo2);
                $campo2->set_descripcion_Campo($nombreCampo2);
                $campo2->set_tipo_Valor_Campo('varchar');
                $campo2->set_tipo_Reactivo_Campo('textarea');
                $campo2->set_Valor_Default('');
                $campo2->saveNewCampoPlantilla($allcampos);

                // ************************************* INSERTAR CAMPOS A REPORTES ********************************
                // Fecha
                $camporeporte = new CampoReporte($this->adapter);
                $campoFecha = $camporeporte->getAllCampoByNombre('Fecha');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoFecha[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(1);
                $camporeporte->saveNewConfiguracionPlantilla();

                // Hora
                $campoHora = $camporeporte->getAllCampoByNombre('Hora');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoHora[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(2);
                $camporeporte->saveNewConfiguracionPlantilla();

                // Observaciones
                $campoDescripcion = $camporeporte->getAllCampoByNombre('Observaciones');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoDescripcion[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(0);
                $camporeporte->set_Secuencia(3);
                $save = $camporeporte->saveNewConfiguracionPlantilla();
                //}

                /* ::::::::::::::::::::::::::::::::::::::::::::::::: BITÁCORA ::::::::::::::::::::::::::::::::::::::::*/
                $funciones = new FuncionesCompartidas();
                $funciones->guardarBitacora(1, $_SESSION[ID_USUARIO_SUPERVISOR], '', 16, '', $this->id_Proyecto_constant);

            } else {
                $insercion = 2;
                $mensaje = 'El reporte "' . $nombreReporte . '" ya existe';
            }
        }

        $this->redirect("Reportes", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*----------------------------------------- METODO GUARDAR MODIFICACION REPORTE ----------------------------------*/
    public function guardarmodificacion()
    {

        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["nombre_Reporte"])) {
            $save = "Ingresar nombre de reporte";
        } else if (empty($_POST["area"])) {
            $save = "Seleccione un área para el reporte";
        }//SE GUARDA MODIFICACION
        else {

            if ($_POST['seguimientoIncidenciaMod'] != 0)
                $id_Seguimieto = $_POST['seguimientoIncidenciaMod'];
            else {
                $id_Seguimieto = 0;
                //echo 'Valor: null <br>';
            }

            $tiempo = $_POST["tiempo_Reporte"];
            $alarma = $_POST["tiempo_Alarma"];
            $revision = $_POST["tiempo_Revision"];

            $tipoReporte = $_POST["tipo_Reporte"];

            if ($tipoReporte != 1) {
                $tiempo = 0;
                $alarma = 0;
                $revision = 0;
            }

            if (empty($tiempo) && empty($alarma) && empty($revision)) {
                $tiempo = 0;
                $alarma = 0;
                $revision = 0;
            }

            $areas = implode(",", $_POST["area"]);

            $perfiles = $_POST["perfil"];

            if (empty($perfiles))
                $perfiles = '';
            else
                $perfiles = implode(",", $perfiles);

            $tiempo_Reporte = ($tiempo * 60);
            $tiempo_Alarma = ($alarma * 60);
            $tiempo_Revision = ($revision * 60);
            $reporte = new Reporte($this->adapter);
            $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
            $id = $_POST["id_Reporte"];
            $reporte->set_id_Reporte($_POST["id_Reporte"]);
            $reporte->set_id_Proyecto($this->id_Proyecto_constant);
            $nombreReporte = $_POST["nombre_Reporte"];
            $reporte->set_nombre_Reporte($nombreReporte);
            $reporte->set_descripcion_Reporte($_POST["descripcion_Reporte"]);
            $reporte->set_Areas($areas);
            $reporte->set_tiempo_Reporte($tiempo_Reporte);
            $reporte->set_tiempo_Alarma($tiempo_Alarma);
            $reporte->set_tiempo_Revision($tiempo_Revision);
            $reporte->set_tipo_Reporte($tipoReporte);
            $reporte->set_Perfiles($perfiles);
            $reporte->set_id_Seguimiento($id_Seguimieto);
            $save = $reporte->modificarReporte($id, $allreportes);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 3) {
                $insercion = 3;
                $mensaje = 'Se ha modificado el reporte: "' . $nombreReporte . '"';

                $funciones = new FuncionesCompartidas();
                $funciones->guardarBitacora(1, $_SESSION[ID_USUARIO_SUPERVISOR], '', 17, NULL, $this->id_Proyecto_constant);
            } else {
                $insercion = 2;
                $mensaje = 'El reporte "' . $nombreReporte . '" ya existe';
            }
        }

        $this->redirect("Reportes", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--------------------------------------------- METODO ALTA-BAJA REPORTE -----------------------------------------*/
    public function borrar()
    {
        $id = (int)$_GET["id_Reporte"];
        if (isset($id)) {
            $camporeporte = new CampoReporte($this->adapter);
            $reporte = new Reporte($this->adapter);
            $camporeporte->borrarConf($id);
            $reporte->deleteElementoById($id, 'Reportes');
            $save = "Se elmino el reporte con ID " . $id . "";
            $mensaje = "<i class='fa fa-cogs' aria-hidden='true'></i> " . $save . "";
        }
        $this->redirect("Reportes", "index&mensaje=$mensaje");
    }


    // FUNCION PARA OBTENER REPORTES POR PROYECTO PARA DUPLICAR
    public function getAllReportesByIdProyecto()
    {
        $id_Proyecto = $_POST['id_Proyecto'];

        $reportes = new Reporte($this->adapter);
        $getAllReportes = $reportes->getAllReporte($id_Proyecto);

        echo json_encode($getAllReportes);
    }
}

?>
