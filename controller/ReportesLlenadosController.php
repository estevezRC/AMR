<?php

class ReportesLlenadosController extends ControladorBase
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
        $this->estructura = [];
        require_once 'vendor/autoload.php';

    }

    /*------------------------------- VISTA DE TODOS LOS REPORTES LLENADOS ----------------------------------------*/
    public function index()
    {

    }

    /*public function getDatosCodigosLlenadosDocBim()
    {
        $reportesLlenados = new ReporteLlenado($this->adapter);
        $datosDocBim = $reportesLlenados->getAllDatosByReportePlanos($this->id_Proyecto_constant);

        $reporteDocBim = $reportesLlenados->getReporteByProyectoAndTipoReporteDocBim($this->id_Proyecto_constant);

        echo json_encode(["datosDocBim" => $datosDocBim, "reporteDocBim" => $reporteDocBim]);
    }*/

    public function visualizarPlanos()
    {
        $id_Reporte = $_GET['id_Reporte'];
        $titulo_Reporte = $_GET['titulo_Reporte'];
        $id_Empresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        $reportesLlenados = new ReporteLlenado($this->adapter);
        $idReporteMax = $reportesLlenados->getidReporteMaxPlanos($id_Reporte, $titulo_Reporte);
        $fotografia = new Fotografia($this->adapter);

        if ($idReporteMax != NULL) {
            $getPdf = $fotografia->getAllFotografiasById($idReporteMax, 1);
            if ($getPdf == null) {
                echo "<script>
                alert('El ultimo reporte no tiene archivos');
                //window.history.back();
                window.close();
              </script>";
            } else {
                foreach ($getPdf as $namepdf) {
                    $carpeta = $namepdf->fecha_Fotografia;
                    $carpeta = str_replace("-", "", $carpeta);
                    $carpeta = substr($carpeta, 0, -2);

                    $imagen = "img/reportes/" . $id_Empresa . '/' . $this->id_Proyecto_constant . '/' . $carpeta . "/" . $namepdf->nombre_Fotografia . "";
                }
                header("Location:" . $imagen);
            }
        } else {
            echo "<script>
                alert('No se han cargado archivos para este código');
                //window.history.back();
                window.close();
              </script>";
        }

    }


    /*------------------------------------- VISTA VER REPORTE LLENADO POR ID ----------------------------------*/
    public function verreportellenado()
    {
        // CAMBIO DE PROYECTO A TRAVES DE LAS NOTIFICACIONES
        $id_ProyectoNew = $_GET['id_Proyecto'];
        if ($id_ProyectoNew != null || $id_ProyectoNew != '' || !empty($id_ProyectoNew)) {
            // PROYECTO
            $proyecto = new Proyecto($this->adapter);
            $datosproyecto = $proyecto->getProyectoById($id_ProyectoNew);
            $_SESSION[NOMBRE_PROYECTO] = $datosproyecto->nombre_Proyecto;
            session_start();
            $_SESSION[ID_PROYECTO_SUPERVISOR] = $datosproyecto->id_Proyecto;
            $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
            // PERFIL
            $usuario = new Usuario($this->adapter);
            $proyecto = new Proyecto($this->adapter);
            $allproyectos = $proyecto->getAllProyectosByUserAndProyecto($_SESSION[ID_USUARIO_SUPERVISOR], $id_Proyecto);
            $permisos_user = $usuario->getPermisoByPuesto($allproyectos[0]->id_Perfil_Usuario);
            session_start();
            $_SESSION[PERMISOS_MENU] = $permisos_user;
        }


        $id_Padre = $_GET['id_Padre'];
        $id_notificacion = $_GET['id_notificacion'];
        $firma = $_GET['firma'];

        if (empty($firma) || $firma == '' || $firma != 1)
            $firma = 0;

        /* :::::::::::::::::::::::::::::::::::CAMBIAR STATUS NOTIFICACION ::::::::::::::::::::::::::::::::::::::::::::*/
        if ($id_notificacion != 0) {
            $notificacion = new Notificaciones($this->adapter);
            $notificacion->updateNotificacion($id_notificacion);
        }
        /* ::::::::::::::::::::::::::::::::::::::::: END NOTIFICACION ::::::::::::::::::::::::::::::::::::::::::::::::*/


        $id = $_REQUEST['id_Gpo_Valores_Reporte'];

        $idCatalogo = $_GET['idCatalogo'];
        $id_Reporte = $_GET["Id_Reporte"];
        //OBTENER LOS DATOS DEL REPORTE
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $fotografia = new Fotografia($this->adapter);
        $allreportellenado = $llenadoreporte->getReporteLlenadoById($id);
        $id_Reporte = $allreportellenado[0]->id_Reporte;


        $info_fotografia = $fotografia->getAllFotografiasById($id, 1);

        //OBTENER LOS COMENTARIOS
        $comentario = new Comentarios_Reportes($this->adapter);
        $allcomentarios = $comentario->getAllComentariosReporte($id);

        // -----------------------------------------CAMPOS ESPECIALES------------------------------------------------
        $id_usuario = 0;
        $monitoreo = null;
        $menucatalogo = null;
        $datosIdAndName = null;
        $allRegistrosTablas = null;
        $subcampos = null;
        $createData = function ($allreportellenado) use (
            &$monitoreo,
            &$menucatalogo,
            &$datosIdAndName,
            &$allRegistrosTablas,
            &$subcampos,
            &$id_usuario,
            &$createData,
            $comentario,
            $llenadoreporte
        ) {
            foreach ($allreportellenado as $reporte) {
                switch ($reporte->tipo_Reactivo_Campo) {
                    //SELECT MONITOREO
                    case "select-monitoreo":
                        $monitoreo = $comentario->getAllCatMonitoreo();
                        break;

                    case "select-catalogo":
                        $menucatalogo = $comentario->getCatCategoriaByIdCategoria($reporte->Valor_Default);
                        break;

                    case "check_list_asistencia":
                        $ids = str_replace('/', ',', $reporte->valor_Texto_Reporte);
                        switch ($reporte->Valor_Default) {
                            case 'empleados':
                                // OBTENER TODOS LOS EMPLEADOS
                                $datosIdAndName = $comentario->getAllEmpleadosByInIdEmpleados($ids);
                                break;
                            case 'participantes':
                                // OBTENER TODOS LOS PARTICIPANTES = id_Status_Usuario IN(1,2) AND participante = 1
                                $datosIdAndName = $comentario->getAllParticipantesIdAndNameByIds($ids);
                                break;
                            default;
                        }
                        break;

                    case "select-tabla":
                        $id = str_replace('/', ',', $reporte->valor_Texto_Reporte);

                        // OBTENER TODOS LOS REGISTROS DE LA TABLA X
                        switch ($reporte->Valor_Default) {
                            case 'proyecto':
                                // CONSULTAR TABLA DE PROYECTOS
                                $allRegistrosTablas = $comentario->getAllProyectosIdAndNameById($id);
                                break;
                            case 'participante':
                                if ($id)
                                    $allRegistrosTablas = $comentario->getAllParticipantesIdAndNameByIds($id);
                                // PARA CAMPO EN JSON(CAMPO MULTIPLE)
                                $reporte->Valor_Default = $comentario->getAllParticipantesIdAndName();
                                break;
                            default;
                        }
                        break;

                    case "multiple":
                        $IDSubcampos = explode("/", $reporte->Valor_Default);
                        $subcampos = array_map(function ($id) use ($llenadoreporte) {
                            return $llenadoreporte->getCampoById($id);
                        }, $IDSubcampos);

                        $reporte->Valor_Default = $createData($subcampos);
                        break;
                }

                $id_usuario = $reporte->id_Usuario;
            }

            return $allreportellenado;
        };

        $allreportellenado = $createData($allreportellenado);

        $usuario = new Usuario($this->adapter);
        $llaveE = $usuario->getFirmaUserById($_SESSION[ID_USUARIO_SUPERVISOR]);

        $nip = $usuario->getUserById($_SESSION[ID_USUARIO_SUPERVISOR]);
        $nipUser = $nip->nip_Usuario;

        $tipo_Reporte = $allreportellenado[0]->tipo_Reporte;
        $mensaje = $this->nombreReporteId($tipo_Reporte, 1, $tipo_Reporte);

        $catReportes = new Reporte($this->adapter);
        $allperfilFirma = $catReportes->getAllPerfilesById_Reporte($id_Reporte);

        $firma1 = new Firma($this->adapter);
        $firmaReporte = $firma1->getFirmaByIdUser($_SESSION[ID_USUARIO_SUPERVISOR], $id);

        $perfilesFirma = $allperfilFirma[0]->perfiles_firma;
        if ($firmaReporte == null)
            $reporteFirmado = 1; // Este valor significa que no esta firmado el reporte
        else
            $reporteFirmado = 0; //Si esta firmado el reporte

        //**************************************** DATOS PARA ENLAZAR REPORTES ****************************************
        $reporteLlenado = new ReporteLlenado($this->adapter);
        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

        $porcentaje = 0;
        if ($allreportellenado[0]->tipo_Reporte == 1) {
            // REPORTES ENLAZADOS A INCIDENCIAS
            $id_seguimiento = $reporteLlenado->getAllCatReportesByIdReporte($id_Reporte);
            $id_Reporte_Seguimiento = $id_seguimiento[0]->id_Reporte_Seguimiento;

            if ($id_Reporte_Seguimiento != 0) {
                $area = $_SESSION[ID_AREA_SUPERVISOR];
                $noreportes = 'AND rl.id_Reporte = ' . $id_Reporte_Seguimiento . ' AND rl.id_Gpo_Padre = ' . $id;
                $tipo_Reporte1 = '4';
                $allSeguimientosReportesIncidentes = $reporteLlenado->getAllSeguimientoReporteIncidencia($area, $id_Proyecto, $tipo_Reporte1, $noreportes);
            }

        } else {
            //OBTENER PROCENTAJE DE REPORTES ENLAZADOS AL REPORTE PADRE
            $allSeguimientosReportesIncidentes = $reporteLlenado->getAllSeguimientoProcesos($id);

            # Obtener id_nodo_padre
            $actividad = new AvanceActividad($this->adapter);
            $calculosCompartidos = new CalculosCompartidos();
            $datosAvances = $actividad->getRegistroAvanceActividad($id, $id_Proyecto);
            if ($datosAvances) {
                $idNodo = $datosAvances[0]->id_nodo;
                $porcentajeValor = $datosAvances[0]->porcentaje;

                # OBTENER ID_GANTT DEL PROYECTO
                $registroGantt = $actividad->getIdGanttByid_proyecto($id_Proyecto);
                $idGantt = $registroGantt[0]->id;
                $subNodos = $actividad->getSubNodos($idNodo);

                # Obtener datos generales del nodo
                $nodo = $actividad->getRegistroGanttValoresByid_ganttANDid_nodo($idGantt, $idNodo);

                //$porcentajes = $armarestructura($subNodos, $estructura, $nodo[0]->porcentaje);

                /*$porcentajes = $calculosCompartidos->calculo($subNodos, $this->estructura, $nodo[0]->porcentaje);
                $porcentaje = $porcentajes->perc_nodo;*/
            } else {
                // REPORTES ENLAZADOS A ESTRUCTURAS
                $funciones = new FuncionesCompartidas();
                $valor = $funciones->Estructura($id, $this->id_Proyecto_constant);
                if ($valor) {

                    $calculos = new CalculosCompartidos();
                    switch ($id) {
                        case 1:
                            $resultado = $calculos->calcularPorcentaje(1);
                            $porcentaje = $resultado[0];
                            break;

                        case 369:
                            $resultado = $calculos->calcularPorcentajeEdificio(369);
                            $porcentaje = $resultado[0];
                            break;

                        default:
                            $datos = $reporteLlenado->calcularPorcentajeEstructura($id);
                            foreach ($datos as $dato) {
                                $porcentaje += $dato->Porcentaje1;
                            }
                    }
                } else {
                    // REPORTES ENLAZADOS A PROCESOS
                    $allDatosReportesProcesos = $reporteLlenado->getAllProcesosAvancesVinculados($id);
                    foreach ($allDatosReportesProcesos as $proceso) {
                        $porcentaje += $proceso->Porcentaje;
                    }
                }
                $porcentaje = round($porcentaje, 2, PHP_ROUND_HALF_UP);

            }

            if ($porcentaje > 100)
                $porcentaje = 100;

        }

        if ($tipo_Reporte == 6) {
            $allEmpleados = $reporteLlenado->getAllAsistenciaByIdGpoValores($id);


            $horasTrabajadas = array();
            foreach ($allEmpleados as $empleado) {
                if ($empleado->hora_salida) {
                    $horaAsistencia = date("Y-m-d H:i:s", strtotime("$empleado->fecha $empleado->hora"));

                    $horaAsistencia = new DateTime($horaAsistencia);
                    $horaSalida = new DateTime($empleado->hora_salida);

                    $tiempo = $horaAsistencia->diff($horaSalida);
                    $horasTrabajadas[] = "$tiempo->d dias $tiempo->h horas $tiempo->i minutos";
                } else
                    $horasTrabajadas[] = "No tiene reporte de termino de jornada laboral";
            }
        }

        $proyectoCon = new Proyecto($this->adapter);
        $proyectoActual = $proyectoCon->getProyectoById($_SESSION[ID_PROYECTO_SUPERVISOR]);

        if ($proyectoActual->logos) {
            $logos = (array)json_decode($proyectoActual->logos);
        } else {
            $logos = NULL;
        }

        switch ($tipo_Reporte) {
            case '0':
                $tipo_Reporte1 = '0,6,7,9';
                break;
            case '2':
                $tipo_Reporte1 = '2';
                break;
            case '3':
                $tipo_Reporte1 = '3';
                break;
            case '1':
                $tipo_Reporte1 = '1';
                break;
            case '5':
                $tipo_Reporte1 = '5';
                break;
        }
        $reporteSiguiente = $llenadoreporte->getReporteLlenadoByIdNextReport($this->id_Proyecto_constant, $id, $tipo_Reporte1);
        $nextPage = $reporteSiguiente[0]->posterior;
        $previousPage = $reporteSiguiente[0]->anterior;

        // /*
        $this->view("index", array(
            "allreportellenado" => $allreportellenado, "id_usuario" => $id_usuario, "mensaje" => $mensaje,
            "info_fotografia" => $info_fotografia, "allcomentarios" => $allcomentarios, "monitoreo" => $monitoreo,
            "menucatalogo" => $menucatalogo, "idCatalogo" => $idCatalogo, "llaveE" => $llaveE, "firma" => $firma,
            "nipUser" => $nipUser, "reporteFirmado" => $reporteFirmado, "perfilesFirma" => $perfilesFirma,
            "allSeguimientosReportesIncidentes" => $allSeguimientosReportesIncidentes, "id_Padre" => $id_Padre,
            "logos" => $logos, "porcentajeReporte" => $porcentaje, "id_Reporte_Seguimiento" => $id_Reporte_Seguimiento,
            "datosIdAndName" => $datosIdAndName, "allRegistrosTablas" => $allRegistrosTablas,
            "allEmpleados" => $allEmpleados, "horasTrabajadas" => $horasTrabajadas, "subCamposMultiple" => $subcampos, "id_Reporte" => $id_Reporte,
            "anteriorPagina" => $previousPage, "siguientePagina" => $nextPage
        ));
        // */

    }

    /* ---------------------------------- EXPORTAR REPORTES LLENADOS --------------------------------------------*/
    public function exportarreportellenado()
    {
        $id = $_GET["id_Gpo_Valores_Reporte"];
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $allreportellenado = $llenadoreporte->getReporteLlenadoById($id);
        $this->view("index", array(
            "allreportellenado" => $allreportellenado
        ));
    }


    public function guardarcomentario()
    {
        $id_Reporte = $_POST['id_Reporte'];
        $id_Gpo = $_POST['id_Gpo_Valores_Reporte'];
        $comentarios = $_POST['comentarios'];
        $id_usuario = $_POST['id_usuario'] ?? 1;
        //CONSULTAR ULTIMO COMENTARIO

        $comentario = new Comentarios_Reportes($this->adapter);

        $comentario->set_id_Gpo($id_Gpo);
        $comentario->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
        $comentario->set_Comentario_reporte($comentarios);
        $guardarcomentario = $comentario->saveNewComentario();

        /* ::::::::::::::::::::::::::::::::::::::::: NOTIFICACION ::::::::::::::::::::::::::::::::::::::::::::::::*/
        $funciones = new FuncionesCompartidas();
        $funciones->guardarNotificacion($id_usuario, $_SESSION[ID_USUARIO_SUPERVISOR], $id_Gpo, 2);

//        $last_id = $guardarcomentario['Resultado'];
        //var_dump($guardarcomentario);
//        if ($last_id != 0 || $last_id != NULL) {
        //echo $last_id;

        //FOTOGRAFIA
        if (!empty($_FILES["img_comentario"]['name'])) {

            $fecha_mes = date("F");
            $fecha_mes = strtolower($fecha_mes);
            $fecha_mes = substr($fecha_mes, 0, 3);
            $año_dia = date("dy");
            $hora = date("His");

            // Obtener ultimo Comentario insertado
            $ultimoComentario = $comentario->getAllValorMaxComentarios();

            //CARGAR IMAGEN
            $nombre_img = $_FILES["img_comentario"]['name'];
            $tipo_img = $_FILES['img_comentario']['type'];
            $extension = explode(".", $nombre_img);
            $nombre_imagen = $id_Gpo . "_" . $fecha_mes . $año_dia . "_" . $hora . "." . $extension[1];
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
            $target_path = "img/comentarios/";

            if (!is_dir($target_path)) {
                mkdir($target_path, 0777, true);
            }

            $target_path = $target_path . basename($nombre_imagen);
            $img = "img/comentarios/" . $nombre_imagen;
            if (move_uploaded_file($_FILES["img_comentario"]['tmp_name'], $target_path)) {
                $foto = 1;

                //REGISTRAR FOTO EN TABLA FOTOGRAFIAS
                $llenadofotografia = new Fotografia($this->adapter);
                $llenadofotografia->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
                $llenadofotografia->set_id_Modulo(7);
                $llenadofotografia->set_identificador_Fotografia($ultimoComentario);
                $llenadofotografia->set_directorio_Fotografia('');
                $nombre_Fotografia = str_replace('img/comentarios/', '', $img);
                $llenadofotografia->set_nombre_Fotografia($nombre_Fotografia);
                $llenadofotografia->set_descripcion_Fotografia(NULL);
                $llenadofotografia->set_orientacion_Fotografia(0);
                $save_fotografia = $llenadofotografia->saveNewFotografia();

            } else {
                $foto = 0;
            }

        }


        $this->redirect("ReportesLlenados", "verreportellenado&id_Gpo_Valores_Reporte=$id_Gpo&Id_Reporte=$id_Reporte");

    }

    /* public function guardarfotocomentario()
     {
         $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
         $last_id = $_GET['last_id'];
         $img = $_GET['img'];
         $foto = $_GET['foto'];
         $id_Gpo = $_GET['id_Gpo_Valores_Reporte'];
         $id_Reporte = $_GET['Id_Reporte'];
         if ($last_id != 0 || $last_id != NULL) {
             if ($foto == 1) {
                 $llenadofotografia = new Fotografia($this->adapter);
                 $llenadofotografia->set_id_Usuario($id_Usuario);
                 $llenadofotografia->set_id_Modulo(7);
                 $llenadofotografia->set_identificador_Fotografia($last_id);
                 $llenadofotografia->set_directorio_Fotografia('');
                 $nombre_Fotografia = str_replace('img/comentarios/', '', $img);
                 $llenadofotografia->set_nombre_Fotografia($nombre_Fotografia);
                 $llenadofotografia->set_descripcion_Fotografia(NULL);
                 $llenadofotografia->set_orientacion_Fotografia(0);
                 $save_fotografia = $llenadofotografia->saveNewFotografia();
             }
         }

         $this->redirect("ReportesLlenados", "verreportellenado&id_Gpo_Valores_Reporte=$id_Gpo&Id_Reporte=$id_Reporte");
     }*/


    public function editarcomentario()
    {
        $id_Reporte = $_POST['id_Reporte'];
        $id_Gpo = $_POST['id_Gpo_Valores_Reporte'];
        $comentarios = $_POST['comentarios2'];
        $idcomentario = $_POST['idcomentario'];
        $comentario = new Comentarios_Reportes($this->adapter);
        $comentario->set_id_Gpo($id_Gpo);
        $comentario->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
        $comentario->set_Comentario_reporte($comentarios);
        $comentario->set_id_comentario($idcomentario);
        $guardarcomentario = $comentario->modificarComentario();
        $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];

        //FOTOGRAFIA

        if (!empty($_FILES["img_comentario2"]['name'])) {

            $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
            $fecha_mes = date("F");
            $fecha_mes = strtolower($fecha_mes);
            $fecha_mes = substr($fecha_mes, 0, 3);
            $año_dia = date("dy");
            $hora = date("His");
            //CARGAR IMAGEN
            $nombre_img = $_FILES["img_comentario2"]['name'];
            $tipo_img = $_FILES['img_comentario2']['type'];
            $extension = explode(".", $nombre_img);
            $nombre_imagen = $idcomentario . "_" . $fecha_mes . $año_dia . "_" . $hora . "." . $extension[1];
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
            $target_path = "img/comentarios/";

            if (!is_dir($target_path)) {
                mkdir($target_path, 0777, true);
            }

            $target_path = $target_path . basename($nombre_imagen);
            $img = "img/comentarios/" . $nombre_imagen;
            if (move_uploaded_file($_FILES["img_comentario2"]['tmp_name'], $target_path)) {
                //echo "The file ".  basename($_FILES[$nombre_foto]['tmp_name']). " has been uploaded";
            } else {
                //echo "Erro al cargar imagen!";
            }

            //REGISTRAR FOTO EN TABLA FOTOGRAFIAS
            $llenadofotografia = new Fotografia($this->adapter);
            $consultar_foto = $llenadofotografia->consultarFotoComentario($idcomentario);
            if ($consultar_foto == NULL) {
                $llenadofotografia->set_id_Usuario($id_Usuario);
                $llenadofotografia->set_id_Modulo(7);
                $llenadofotografia->set_identificador_Fotografia($idcomentario);
                $llenadofotografia->set_directorio_Fotografia('');
                $nombre_Fotografia = str_replace('img/comentarios/', '', $img);
                $llenadofotografia->set_nombre_Fotografia($nombre_Fotografia);
                $llenadofotografia->set_descripcion_Fotografia(NULL);
                $llenadofotografia->set_orientacion_Fotografia(0);
                $save_fotografia = $llenadofotografia->saveNewFotografia();
            } else {
                $llenadofotografia->set_identificador_Fotografia($idcomentario);
                $nombre_Fotografia = str_replace('img/comentarios/', '', $img);
                $llenadofotografia->set_nombre_Fotografia($nombre_Fotografia);
                $save_fotografia = $llenadofotografia->editFotografiaComentario();
            }
        }

        $this->redirect("ReportesLlenados", "verreportellenado&id_Gpo_Valores_Reporte=$id_Gpo&Id_Reporte=$id_Reporte");
    }

    public function guardarconf()
    {
        $dimensiones = $_POST['valores'];
        $id_Reporte = $_POST['id_Reporte'];
        $id_Gpo = $_POST['id_Gpo'];
        $conf = new conf_Formulario($this->adapter);
        $conf->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
        $conf->set_id_Reporte($id_Reporte);
        $conf->set_valores_Conf($dimensiones);

        $info_conf = $conf->get_AllConfById($id_Reporte, $_SESSION[ID_USUARIO_SUPERVISOR]);
        if (is_array($info_conf) || is_object($info_conf)) {
            $conf->set_id_Conf($info_conf[0]->id_Conf);
            $conf->set_id_Usuario($_SESSION[ID_USUARIO_SUPERVISOR]);
            $guardarconf = $conf->saveModificacionConf();
        } else {
            $guardarconf = $conf->saveNewConf();
        }

        //echo $guardarconf;
        $this->redirect("ReportesLlenados", "verreportellenado&id_Gpo_Valores_Reporte=$id_Gpo&Id_Reporte=$id_Reporte");
    }

    public function migrateinformationReports()
    {
        $reportesLlenados = new ReporteLlenado($this->adapter);
        $entidadBase = new EntidadBase('', $this->adapter);
        $llenadoreporte = new LlenadoReporte($this->adapter);
        $fotografia = new Fotografia($this->adapter);

        $OLD_id_Gpo = [2114,2113,2112,2108,2101,2099,2096,2068,2060,2057,2056,2048,2047,2046,2038,2037,2036,2035,2030,1955,1954,1938,1937,1936,1814,1813,1802,1794,1793,1207,1199,1187,1163,1162,1080,1032,1004,1003,998,947,946,814,813,805,804,738,693,692,691,690];
        $id_Reporte = 66;

        $arraNewIDs = [];

        for($i = 0; $i < count($OLD_id_Gpo); $i++) {
            sleep(2);
            // Se saca la informacion del campo especial para el cadenamiento inicial
            $jsonCadenamiento = $entidadBase->getCampoEspecialJsonByIdGpoAndIdConf($OLD_id_Gpo[$i], 56)[0];
            $json = $jsonCadenamiento->valor_Texto_Reporte;
            $json = json_decode($json, true);
            foreach ($json as $dataValores) {
                foreach ($dataValores[0]['Valor'] as $dataValor) {
                    if ($dataValor['idCampo'] == 29) {
                        $cadenamientoInicial = $dataValor['valorCampo'];
                    }
                }
            }

            $arraCamposOLD = [];
            // Se extraen los valores pasados del registro a migrar al nuevo
            $valoresReportesOld = $entidadBase->getReporteLlenadoById($OLD_id_Gpo[$i]);
            foreach ($valoresReportesOld as $dataOldReport) {
                if ($dataOldReport->id_Configuracion_Reporte != 56 && $dataOldReport->id_Configuracion_Reporte != 57) {
                    $arraCamposOLD[] = [
                        'reactivo' => $dataOldReport->tipo_Reactivo_Campo,
                        'valor' => $dataOldReport->valor_Texto_Reporte,
                        'descripcion' => $dataOldReport->descripcion_Campo
                    ];
                }
            }


            $arraCamposOLD[] = [
                'reactivo' => 'text-cadenamiento',
                'valor' => $cadenamientoInicial,
                'descripcion' => 'Cadenamiento_Inicial'
            ];
            $arraCamposOLD[] = [
                'reactivo' => 'select',
                'valor' => 'REGISTRO FO',
                'descripcion' => 'Tipo'
            ];


            $camposReporte = $entidadBase->getAllCampoReporteByIdReporte($id_Reporte);
            $NEW_id_Gpo_Valores = $entidadBase->getUltimoReporteLlenado();
            $NEW_id_Gpo_Valores += 1;


            foreach ($camposReporte as $campo) {
                $campoimagen = $campo->tipo_Reactivo_Campo;
                $idconfiguracionreporte = $campo->id_Configuracion_Reporte;
                $descripcionCampo = $campo->descripcion_Campo;
                foreach ($arraCamposOLD as $camposOld) {
                    $reactivo =  $camposOld['reactivo'];
                    $valorCampo = $camposOld['valor'];
                    $descriptiva = $camposOld['descripcion'];
                    if ($campoimagen == "date" && $reactivo == "date") {
                        echo $campoimagen . ' FECHA ' . $idconfiguracionreporte . '<br>';
                        $llenadoreporte->set_id_Proyecto('2');
                        $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                        $llenadoreporte->set_valor_Entero_Reporte('NULL');
                        $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                        $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                        $save = $llenadoreporte->saveNewLlenado();
                    }

                    if ($campoimagen == "time" && $reactivo == "time") {
                        echo $campoimagen . ' TIME ' . $idconfiguracionreporte . '<br>';
                        $llenadoreporte->set_id_Proyecto('2');
                        $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                        $llenadoreporte->set_valor_Entero_Reporte('NULL');
                        $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                        $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                        $save = $llenadoreporte->saveNewLlenado();
                    }

                    if ($campoimagen == "textarea" && $reactivo == "textarea") {
                        if($descripcionCampo == "Descripción" && $descriptiva == "Descripción") {
                            echo $campoimagen . ' Descripcion TxtArea ' . $idconfiguracionreporte . '<br>';
                            $llenadoreporte->set_id_Proyecto('2');
                            $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                            $llenadoreporte->set_valor_Entero_Reporte('NULL');
                            $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                            $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                            $save = $llenadoreporte->saveNewLlenado();
                        }

                        if($descripcionCampo == "Observaciones" && $descriptiva == "Observaciones") {
                            echo $campoimagen . ' Observaciones textarea ' . $idconfiguracionreporte . '<br>';
                            $llenadoreporte->set_id_Proyecto('2');
                            $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                            $llenadoreporte->set_valor_Entero_Reporte('NULL');
                            $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                            $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                            $save = $llenadoreporte->saveNewLlenado();
                        }
                    }

                    if ($campoimagen == "select" && $reactivo == "select") {
                        echo $campoimagen . ' SELECT ' . $idconfiguracionreporte . '<br>';
                        $llenadoreporte->set_id_Proyecto('2');
                        $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                        $llenadoreporte->set_valor_Entero_Reporte('NULL');
                        $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                        $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                        $save = $llenadoreporte->saveNewLlenado();
                    }

                    if ($campoimagen == "text-cadenamiento" && $reactivo == "text-cadenamiento") {
                        echo $campoimagen . ' text-cadenamiento ' . $idconfiguracionreporte . '<br>';
                        $llenadoreporte->set_id_Proyecto('2');
                        $llenadoreporte->set_id_Configuracion_Reporte($idconfiguracionreporte);
                        $llenadoreporte->set_valor_Entero_Reporte('NULL');
                        $llenadoreporte->set_valor_Texto_Reporte($valorCampo);
                        $llenadoreporte->set_id_Gpo_Valores_Reporte($NEW_id_Gpo_Valores);
                        $save = $llenadoreporte->saveNewLlenado();
                    }
                }
            }

            $fotografia->updateIdGpoValoresFoto($OLD_id_Gpo[$i], $NEW_id_Gpo_Valores);

            $reportesLlenados->changeTypeReportByIdGpoAndIdReport($OLD_id_Gpo[$i], $NEW_id_Gpo_Valores ,$id_Reporte, 2);

            $arraNewIDs[] = ['gpoValores' => $NEW_id_Gpo_Valores, 'status' => 'True'];
        }



        echo '<pre>';
        print_r($arraNewIDs);
        echo '<pre>';

    }

}

