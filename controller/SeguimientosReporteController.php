<?php

class SeguimientosReporteController extends ControladorBase
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
        $this->url = $_SERVER["REQUEST_URI"];
    }

    /*----------------------------------- VISTA DE TODAS LOS SEGUIMIENTOS ------------------------------------------*/
    public function index()
    {

        //$actual_link = $_SERVER['REQUEST_URI'];

        $tipo_Reporte = $_GET['tipo'];
        $id_Reporte = $_GET['id_Reporte'];

        $logout = $_REQUEST['logout'];

        //********************* QUITAR ERROR err_cache_miss "REENVIO DEL FORMULARIO" ***********************************
        header('Cache-Control: no-cache'); //no cache
        session_start();

        //PROYECTO
        $id_Proyecto_Actual = (int)$_POST["id_Proyecto"];

        if (!empty($id_Proyecto_Actual)) {
            $proyecto = new Proyecto($this->adapter);
            $datosproyecto = $proyecto->getProyectoById($id_Proyecto_Actual);

            session_start();
            $_SESSION[NOMBRE_PROYECTO] = $datosproyecto->nombre_Proyecto;
            $_SESSION[ID_PROYECTO_SUPERVISOR] = $datosproyecto->id_Proyecto;

        }

        // PERFIL Y PERMISOS DEL USUARIO
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
        if (!empty($id_Proyecto)) {
            $usuario = new Usuario($this->adapter);
            $proyecto = new Proyecto($this->adapter);
            $allproyectos = $proyecto->getAllProyectosByUserAndProyecto($_SESSION[ID_USUARIO_SUPERVISOR], $id_Proyecto);

            $permisos_user = $usuario->getPermisoByPuesto($allproyectos[0]->id_Perfil_Usuario);
            session_start();
            $_SESSION[ID_PERFIL_USER_SUPERVISOR] = $allproyectos[0]->id_Perfil_Usuario;
            $_SESSION[PERMISOS_MENU] = $permisos_user;
        }


        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];

        // **************************************** BITACORA LOGOUT BY PROYECTO ****************************************
        if ($logout) {
            $funciones = new FuncionesCompartidas();
            $funciones->guardarBitacora('NULL', $usuario, 'NULL', 12, null, $id_Proyecto);
        }
        // **************************************** BITACORA LOGOUT BY PROYECTO ****************************************


        $seguimientoreporte = new ReporteLlenado($this->adapter);

        $area = $_SESSION[ID_AREA_SUPERVISOR];


        $mensaje = $this->nombreReporteId($tipo_Reporte, 1);
        //EXCLUIR REPORTES
        switch ($tipo_Reporte) {
            case '0,1':
                /*$valores = new Campo($this->adapter);
                $reportesIncidencia = $valores->getAllCatReportesByTipoReporte($id_Proyecto);

                if ($reportesIncidencia == '' || empty($reportesIncidencia)) {
                    $noreportes = '';
                } else {
                    $id_Seguimiento = array();
                    foreach ($reportesIncidencia as $reporte) {
                        $id_Seguimiento[] = $reporte->id_Reporte_Seguimiento;
                    }

                    $id_SeguimientoStr = implode(",", $id_Seguimiento);
                    $noreportes = 'AND rl.id_Reporte NOT IN(' . $id_SeguimientoStr . ')';
                }*/
                $noreportes = '';
                $tipo_Reporte1 = '0,6,7,9';
                $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
                break;
            case '2':
                $noreportes = '';
                $tipo_Reporte1 = '2';
                $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
                break;
            case '3':
                $noreportes = '';
                $tipo_Reporte1 = '3';
                $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoIncidencia($area, $usuario, $id_Proyecto, $tipo_Reporte1, $noreportes);
                break;
            case 'reportesIncidencia':
                $noreportes = '';
                $tipo_Reporte1 = '1';
                $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoReporteIncidencia2($area, $id_Proyecto, $tipo_Reporte1);
                $mensaje = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Incidentes";
                // OBTENER REPORTES DE INCIDENCIA CONFIGURADOS
                $reportesSinConfigurar = $seguimientoreporte->getAllCampoReporteByAreaTipo($id_Proyecto, $area, 1, $noreportes);
                break;
            case '5':
                $tipo_Reporte1 = '5';
                $tituloReporte = $_GET['codigo'];
                $allseguimientosreportes = $seguimientoreporte->getAllPlanosByTituloReporte($area, $id_Proyecto, $tipo_Reporte1, $tituloReporte);

                $mensaje = "<i class='fa fa-file-o' aria-hidden='true'></i> Documentos BIM";
                break;
            case 'papelera':

                $allseguimientosreportes = '';

                // OBTENER TODOS LOS TIPOS DE REPORTES LLENADOS
                $allTiposReporteLlenado = $seguimientoreporte->getAllTiposPlantillasLenados();

                // VALIDAR QUE LA CONSULTA DEVUELVA DATOS
                if ($allTiposReporteLlenado) {
                    $tipoPlantilla = array();
                    foreach ($allTiposReporteLlenado as $reporte) {
                        $tipoPlantilla[] = $reporte->clas_Reporte;
                    }

                    // CONVERTIR EL ARRAY DE DATOS A STRING
                    $tipoPlantillaSTR = implode(",", $tipoPlantilla);

                    // OBTENER TODOS LOS REGISTROS CON STATUS = 0
                    $allseguimientosreportes = $seguimientoreporte->getAllReportesLlenadosPapelera($area, $id_Proyecto, $tipoPlantillaSTR, 0);
                }

                $mensaje = "<i class='fas fa-trash-restore-alt'></i> Papelera";

                break;
            default:
                break;
        }


        if ($id_Proyecto != '') {
            $noElementos = $seguimientoreporte->getAllCampoReporte($id_Proyecto);
        }


        if (empty($noElementos)) {
            $this->redirect('Plantilla', 'index');
        } else {
            // /*
            $this->view("index", array(
                "allseguimientosreporte" => $allseguimientosreportes, "tipo_Reporte" => $tipo_Reporte, "mensaje" => $mensaje,
                "id_Reporte" => $id_Reporte, "reportesSinConfigurar" => $reportesSinConfigurar, "codigoPlano" => $tituloReporte
            ));

            // */
        }

    }


    /*----------------------------------- VISTA DE BUSQEUDA DE ELEMENTOS ------------------------------------------*/
    public function busquedaInactiva()
    {
        /* ************************************* BUSQUEDA PALBRA CLAVE ***************************************** */
        $palabras_clave = $_GET["palabras_clave"];
        $c_palabras_clave = "' '";
        if (!empty($palabras_clave)) {
            $seguimientoreporte = new LlenadoReporte($this->adapter);

            //PALABRA CLAVE
            $c_palabras_clave = "'" . $palabras_clave . "'";

            //*********************************** Reportes_Llenados (TITULO) *******************************************
            $getAllDatosPalabraClave = $seguimientoreporte->getBusquedaPalabraClaveTitulo($palabras_clave, $this->id_Proyecto_constant);
            $ids = array();
            if (is_array($getAllDatosPalabraClave) || is_object($getAllDatosPalabraClave)) {
                foreach ($getAllDatosPalabraClave as $id_titulo) {
                    $ids[] = $id_titulo->id_Gpo_Valores_Reporte;
                }
                $idsStr = implode(",", $ids);

                if (!empty($idsStr) || $idsStr != '')
                    $ids_titulo = $idsStr;
                else
                    $ids_titulo = '';
            }

            //***************************** Valores_Reportes_Campos (valor_Texto_Reporte) ******************************
            $ids_palabra_clave = $seguimientoreporte->getBusquedaPalabraClave($palabras_clave, $this->id_Proyecto_constant);
            $idsT = array();
            if (is_array($ids_palabra_clave) || is_object($ids_palabra_clave)) {
                foreach ($ids_palabra_clave as $id_palabra_clave) {
                    $idsT[] = $id_palabra_clave->Id_Reporte;
                }
                $idsTStr = implode(",", $idsT);

                if (!empty($idsTStr) || $idsTStr != '')
                    $idsTe = $idsTStr;
                else
                    $idsTe = '';
            }


            if ($ids_titulo == '') {
                $ids_final1 = $idsTe;
            } else {
                if ($idsTe == '') {
                    $ids_final1 = $ids_titulo;
                } else {
                    $ids_final1 = $idsTe . ',' . $ids_titulo;
                }
            }

            $ids_final = $ids_final1;

            /*
                $idsA = explode(',', $ids_final1);
                $size = count($idsA);
                $ids_final = implode(',', $idsA);
                if ($size == 2)
                    $ids_final = substr($ids_final, 1);
            */

        }


        /* ***************************************** TIPO DE REPORTE ******************************************* */
        $id_Reporte = $_GET["id_Reporte"];

        if (empty($id_Reporte)) {
            $nombre_reporte = -1;
            $c_nombre_reporte = "Todos";

            /* ***************************************** FECHA INICIO ******************************************* */
            $fi = $_GET["fecha_Inicio"];
            if (empty($fi)) {
                $fecha_inicio = '';
                $c_fecha_inicio = "Todas";
            } else {
                $fecha_inicio = $fi;
                $c_fecha_inicio = $fecha_inicio;
            }

            /* ***************************************** FECHA FIN ******************************************* */
            $fn = $_GET["fecha_Final"];
            if (empty($fn)) {
                $fecha_final = '';
                $c_fecha_final = "Todas";
            } else {
                $fecha_final = $fn;
                $c_fecha_final = $fecha_final;
            }

        } else {
            $name_report = explode("|", $_GET["id_Reporte"]);
            $nombre_reporte = $name_report[0];

            $c_nombre_reporte = $name_report[1];
            $tipo_Reporte = "tipo_Reporte";
            //echo $nombre_reporte[0];
            switch ($name_report[0]) {
                case('t0'):
                    $tipo_Reporte = "0";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t1'):
                    $tipo_Reporte = "1";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t2'):
                    $tipo_Reporte = "2";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t3'):
                    $tipo_Reporte = "3";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t4'):
                    $tipo_Reporte = "4";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t5'):
                    $tipo_Reporte = "5";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('tipo_Reporte'):
                    $tipo_Reporte = "tipo_Reporte";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
            }

            /* ***************************************** FECHA INICIO ******************************************* */
            $fi = $_GET["fecha_Inicio"];
            if (empty($fi)) {
                $fecha_inicio = '2019-01-01';
                $c_fecha_inicio = "Todas";
            } else {
                $fecha_inicio = $fi;
                $c_fecha_inicio = $fecha_inicio;
            }

            /* ***************************************** FECHA FIN ******************************************* */
            $fn = $_GET["fecha_Final"];
            if (empty($fn)) {
                $fecha_final = $this->fecha();
                $date = new DateTime($fecha_final);
                $fecha_final = $date->format('Y-m-d');
                $c_fecha_final = "Todas";
            } else {
                $fecha_final = $fn;
                $c_fecha_final = $fecha_final;
            }
        }

        /* ***************************************** ESTADO DE REPORTE ******************************************* */
        $name_estado = explode("|", $_GET["estado_reporte"]);
        $estado_reporte = $name_estado[0];
        $c_estado_reporte = $name_estado[1];
        if ($_GET["estado_reporte"] == "Estatus") {
            $c_estado_reporte = "Todos";
        }

        /* ***************************************** IDENTIFICADOR ******************************************* */
        if (empty($_GET["identificador_reporte"])) {
            $identificador_reporte = "'" . $ids_final . "'";
            $c_identificador_reporte = "' '";
        } else {
            $identificador_reporte = "'" . $_GET["identificador_reporte"] . "'";
            $c_identificador_reporte = $_GET["identificador_reporte"];
        }
        $seguimientoreporte = new SeguimientoReporte($this->adapter);


        $allseguimientosreportes = $seguimientoreporte->getAllBusquedaReporte($nombre_reporte, $fecha_inicio,
            $fecha_final, $_SESSION[ID_AREA_SUPERVISOR], $identificador_reporte, $_SESSION[ID_PROYECTO_SUPERVISOR], $tipo_Reporte);

        $c_nombre_reporte = "Tipo reporte: <span style='color:#C9302C'>" . $c_nombre_reporte . "</span>";
        $c_estado_reporte = "Estado reporte: <span style='color:#C9302C'>" . $c_estado_reporte . "</span>";
        $c_fecha_inicio = "Fecha de inicio: <span style='color:#C9302C'>" . $c_fecha_inicio . "</span>";
        $c_fecha_final = "Fecha final: <span style='color:#C9302C'>" . $c_fecha_final . "</span>";
        $c_palabras_clave = "Palabras clave: <span style='color:#C9302C'>" . $c_palabras_clave . "</span>";
        $c_identificador_reporte = "Identificador reporte: <span style='color:#C9302C'>" . $c_identificador_reporte . "</span>";
        $mensaje_seguimiento = "Búsqueda <h4> " . $c_nombre_reporte . ", " . $c_estado_reporte . ", " . $c_fecha_inicio . ", " . $c_fecha_final . ", " . $c_palabras_clave . ", " . $c_identificador_reporte . $cadena_incidencias . "</h4>";

       // /*
       $this->view("index", array(
           "allseguimientosreporte" => $allseguimientosreportes, "mensaje" => $mensaje_seguimiento,
           "tipo_Reporte" => "busqueda"
       ));
       // */

    }

    public function busqueda()
    {
        //********************* QUITAR ERROR err_cache_miss "REENVIO DEL FORMULARIO" ***********************************
        header('Cache-Control: no-cache'); //no cache
        session_start();

        $ids = "NULL";
        $ids_titulo = "NULL";

        $palabras_clave = $_GET["palabras_clave"];
        /* ************************************* BUSQUEDA PALBRA CLAVE ***************************************** */
        $c_palabras_clave = "' '";
        if (!empty($_GET["palabras_clave"])) {
            $seguimientoreporte = new LlenadoReporte($this->adapter);
            //PALABRA CLAVE
            $palabras_clave = $_GET["palabras_clave"];
            $c_palabras_clave = "'" . $palabras_clave . "'";
            //EN TITULO
            $ids_titulo = $seguimientoreporte->getBusquedaPalabraClaveTitulo($palabras_clave, $this->id_Proyecto_constant);

            if ((is_array($ids_titulo) || is_object($ids_titulo)) && count($ids_titulo) > 0) {
                foreach ($ids_titulo as $id_titulo) {
                    $ids_titulo = $ids_titulo . "," . $id_titulo->id_Gpo_Valores_Reporte;
                }
                if (!empty($ids_titulo))
                    $ids_titulo = substr($ids_titulo, 5);
                else
                    $ids_titulo = "";
            } else
                $ids_titulo = "";

            //EN TEXTO
            $ids_palabra_clave = $seguimientoreporte->getBusquedaPalabraClave($palabras_clave, $this->id_Proyecto_constant);
            if ((is_array($ids_palabra_clave) || is_object($ids_palabra_clave)) && count($ids_palabra_clave) > 0) {
                foreach ($ids_palabra_clave as $id_palabra_clave) {

                    $ids = $ids . "," . $id_palabra_clave->Id_Reporte;
                }
                $ids = substr($ids, 5);
            }
            if (empty($ids_palabra_clave)) {
                $ids = 11111111;
            }
        }

        $ids_final = "'" . $ids . $ids_titulo . "'";

        /* ***************************************** TIPO DE REPORTE ******************************************* */
        if (empty($_GET["id_Reporte"])) {
            $nombre_reporte = -1;
            $c_nombre_reporte = "Todos";
        } else {

            $name_report = explode("|", $_GET["id_Reporte"]);
            $nombre_reporte = $name_report[0];
            $c_nombre_reporte = $name_report[1];
            $tipo_Reporte = "tipo_Reporte";
            switch ($name_report[0]) {
                case('t0'):
                    $tipo_Reporte = "0";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t1'):
                    $tipo_Reporte = "1";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t2'):
                    $tipo_Reporte = "2";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t3'):
                    $tipo_Reporte = "3";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t4'):
                    $tipo_Reporte = "4";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('t5'):
                    $tipo_Reporte = "5";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
                case('tipo_Reporte'):
                    $tipo_Reporte = "tipo_Reporte";
                    $nombre_reporte = "rl.id_Reporte";
                    break;
            }

        }

        /* ***************************************** FECHA INICIO ******************************************* */
        if (empty($_GET["fecha_Inicio"])) {
            $fecha_inicio = '2010-01-01';
            $c_fecha_inicio = "Todas";
        } else {
            $fecha_inicio = $_GET["fecha_Inicio"];
            $c_fecha_inicio = $fecha_inicio;
        }

        /* ***************************************** FECHA FIN ******************************************* */
        if (empty($_GET["fecha_Final"])) {
            $fecha_final = $this->fecha();
            $c_fecha_final = "Todas";
        } else {
            $fecha_final = $_GET["fecha_Final"];
            $c_fecha_final = $fecha_final;
        }

        /* ***************************************** ESTADO DE REPORTE ******************************************* */
        $name_estado = explode("|", $_GET["estado_reporte"]);
        $estado_reporte = $name_estado[0];
        $c_estado_reporte = $name_estado[1];
        if ($_GET["estado_reporte"] == "Estatus") {
            $c_estado_reporte = "Todos";
        }

        /* ***************************************** IDENTIFICADOR ******************************************* */
        if (empty($_GET["identificador_reporte"])) {
            $identificador_reporte = $ids_final;
            $c_identificador_reporte = "' '";
        } else {
            $identificador_reporte = "'" . $_GET["identificador_reporte"] . "'";
            $c_identificador_reporte = $_GET["identificador_reporte"];
        }
        $seguimientoreporte = new SeguimientoReporte($this->adapter);

        $allseguimientosreportes = $seguimientoreporte->getAllBusquedaReporte($nombre_reporte, $fecha_inicio,
            $fecha_final, $_SESSION[ID_AREA_SUPERVISOR], $identificador_reporte, $this->id_Proyecto_constant, $tipo_Reporte);

        $c_nombre_reporte = "Tipo reporte: <span style='color:#C9302C'>" . $c_nombre_reporte . "</span>";
        $c_estado_reporte = "Estado reporte: <span style='color:#C9302C'>" . $c_estado_reporte . "</span>";
        $c_fecha_inicio = "Fecha de inicio: <span style='color:#C9302C'>" . $c_fecha_inicio . "</span>";
        $c_fecha_final = "Fecha final: <span style='color:#C9302C'>" . $c_fecha_final . "</span>";
        $c_palabras_clave = "Palabras clave: <span style='color:#C9302C'>" . $c_palabras_clave . "</span>";
        $c_identificador_reporte = "Identificador reporte: <span style='color:#C9302C'>" . $c_identificador_reporte . "</span>";
        $mensaje_seguimiento = $c_nombre_reporte . ", " . $c_estado_reporte . ", " . $c_fecha_inicio . ", " . $c_fecha_final . ", " . $c_palabras_clave . ", " . $c_identificador_reporte . $cadena_incidencias;

        // /*
        $this->view("index", array(
            "allseguimientosreporte" => $allseguimientosreportes, "mensaje" => $mensaje_seguimiento,
            "tipo_Reporte" => "busqueda"
        ));
        // */

        /*
       echo $fecha_final . '<br>';
       echo 'id_R: ' . $id_Reporte . '<br>';
       echo $allseguimientosreportes . '<br>';
       echo 'nom: ' . $nombre_reporte . '<br>';
       echo 'fi: ' . $fecha_inicio . '<br>';
       echo 'fn: ' . $fecha_final . '<br>';
       echo 'area: ' . $_SESSION[ID_AREA_SUPERVISOR] . '<br>';
       echo 'id: ' . $identificador_reporte . '<br>';
       echo 'pro: ' . $_SESSION[ID_PROYECTO_SUPERVISOR] . '<br>';
       echo 'tipo: ' . $tipo_Reporte . '<br>';
       // */
    }


    /*---------------------------------------- METODO CREAR NUEVO SEGUIMIENTO --------------------------------------*/
    public function filtrarestatus()
    {
        if ($_POST['Id_Status'] == "todos") {
            $Id_Status = 'Id_Status';
        } else {
            $Id_Status = $_POST['Id_Status'];
        }
        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
        $area = $_SESSION[ID_AREA_SUPERVISOR];
        //echo $estatus;

        $estatus = new Cat_Status($this->adapter);
        $estatusreporte = $estatus->getEstatusByModulo(1);

        $seguimientoreporte = new SeguimientoReporte($this->adapter);
        $allseguimientosreportes = $seguimientoreporte->getAllSeguimientoReporte($area, $usuario, $this->id_Proyecto_constant, $Id_Status);

        $this->view("index", array(
            "allseguimientosreporte" => $allseguimientosreportes, "estatusreporte" => $estatusreporte
        ));
    }

    /*------------------------------------ METODO GUARDAR MODIFICACION SEGUIMIENTO ---------------------------------*/
    public function guardarmodificacion()
    {
        $seguimientoreporte = new SeguimientoReporte($this->adapter);
        $Id_Registro = $_POST["id_Registro_Reporte_Generado"];
        $id_Gpo_Valores_Reporte = $_POST["id_Reporte_Generado"];
        $id_Reporte = $_POST["id_Reporte"];
        $Id_Status = $_POST["Id_Status"];
        $Notas = $_POST["Notas"];
        $seguimientoreporte->set_Id_Registro($Id_Registro);
        $seguimientoreporte->set_Id_Status($Id_Status);
        $seguimientoreporte->set_Id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
        $seguimientoreporte->set_Notas($Notas);
        $seguimientoreporte->set_Id_Reporte($id_Gpo_Valores_Reporte);


        //VALIDAR SEGUIMIENTOS REPORTE DE AVANCE DE PEAJE

        $allSeguimientos = $seguimientoreporte->getAllSeguimientoReporteByIdGpo($id_Gpo_Valores_Reporte);
        foreach ($allSeguimientos as $seguimiento) {
            $seguimientoreporte->set_Id_Registro($seguimiento->Id_Registro);
            $seguimientoreporte->set_Id_Status($Id_Status);
            $seguimientoreporte->set_Id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
            $seguimientoreporte->set_Notas($Notas);
            $seguimientoreporte->set_Id_Reporte($id_Gpo_Valores_Reporte);
            $guardarseguimiento = $seguimientoreporte->modificar();
        }
        if (($Id_Status == 4) && ($id_Reporte == 14)) {
            //GUARDAR EL AVANCE
            $id_Gpo_Sistema = $_POST["id_Gpo_Sistema"];
            $id_Sistema = $_POST["id_Sistema"];
            $id_Indicador = $_POST["id_Indicador"];
            $avance = new Valor_Indicador($this->adapter);
            //CONSULTAR SI HAY AVANCE PREVIO
            $avance_previo = $avance->getAllAvancesIndicadoresByIdGpo($id_Gpo_Sistema, $id_Sistema, $id_Indicador);
            //PRIMER AVANCE
            if ($avance_previo == NULL) {
                $avance->set_id_Gpo_Sistema($id_Gpo_Sistema);
                $avance->set_id_Sistema($id_Sistema);
                $avance->set_id_Indicador($id_Indicador);
                $avance->set_contador_Avance(1);
                //$guardar_avance = $avance->saveNewValorIndicador_Alterno();
            }
            if ($avance_previo != NULL) {
                $valor_avance = $avance_previo[0]->contador_Avance;
                $valor_avance = $valor_avance + 1;
                $avance->set_id_Gpo_Sistema($id_Gpo_Sistema);
                $avance->set_id_Sistema($id_Sistema);
                $avance->set_id_Indicador($id_Indicador);
                $avance->set_contador_Avance($valor_avance);
                //$guardar_avance = $avance->modificarValorIndicador();
            }
        }
        //ACTUALIZAR REPORTE LLENADO
        $llenadotitulo = new ReporteLlenado($this->adapter);
        $llenadotitulo->set_id_Gpo_Valores_Reporte($id_Gpo_Valores_Reporte);
        $save_titulo = $llenadotitulo->modificarFechaReporteLlenado();


        $this->redirect("ReportesLlenados", "verreportellenado&mensaje=$guardarseguimiento&id_Gpo_Valores_Reporte=$id_Gpo_Valores_Reporte&Id_Reporte=$id_Reporte");
    }


    public function activarReporte()
    {
        $seguimientoreporte = new SeguimientoReporte($this->adapter);
        $id_Gpo_Valores_Reporte = $_GET["id_Gpo_Valores_Reporte"];
        $id_Reporte = $_GET["Id_Reporte"];
        $activar = $seguimientoreporte->activar($id_Gpo_Valores_Reporte);
        //echo $activar;
        $this->redirect("LlenadosReporte", "modificarreporte&mensaje=$guardarseguimiento&id_Gpo_Valores_Reporte=$id_Gpo_Valores_Reporte&Id_Reporte=$id_Reporte");
    }


}

?>
