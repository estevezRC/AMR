<?php
ob_start();
ini_set('serialize_precision', -1);
date_default_timezone_set("America/Mexico_City");

class ControladorBase
{

    public function __construct()
    {
        require_once 'Conectar.php';
        require_once 'EntidadBase.php';
        require_once 'ModeloBase.php';
        require_once 'FuncionesCompartidas.php';
        require_once 'CalculosCompartidos.php';
        //Incluir todos los modelos
        foreach (glob("model/*.php") as $file) {
            require_once $file;
        }

    }


    //Plugins y funcionalidades
    /*
    * Este método lo que hace es recibir los datos del controlador en forma de array
    * los recorre y crea una variable dinámica con el indice asociativo y le da el
    * valor que contiene dicha posición del array, luego carga los helpers para las
    * vistas y carga la vista que le llega como parámetro. En resumen un método para
    * renderizar vistas.
    */

    public function view($vista, $datos)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc} = $valor;
        }

        require_once 'core/AyudaVistas.php';
        $helper = new AyudaVistas();


        require_once 'view/' . $vista . 'View.php';
        //require_once 'view/PrincipalView.php';
    }

    public function redirect($controlador = CONTROLADOR_DEFECTO, $accion = ACCION_DEFECTO)
    {
        header("Location:index.php?controller=" . $controlador . "&action=" . $accion);

        //header("Location:index.php?controller=Principal&action=index");
    }

    /* :::::::::::::::::::::::::::::::FUNCION FECHA MENOS 5 HORAS:::::::::::::::::::::::::::::::::::::::::::::::::.: */
    public function fecha()
    {
        $fecha_hora = date("Y-m-d H:i:s");
        $fecha_hora2 = date("Y-m-d H:i:s", strtotime("-5 hour"));
        return $fecha_hora2;
    }

    public function hora()
    {
        $hora = time("H:i:s");
        //$fecha_hora2 = date("Y-m-d H:i:s",strtotime("-5 hour"));
        return $hora;
    }

    /* ::::::::::::::::::::::::::::::::::::::: TIPO REPORTE :::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    public function nombreReporteId($tipo_Reporte, $icono)
    {
        switch ($tipo_Reporte) {
            case 0:
            case "0,1":
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-list' aria-hidden='true'></i> Reporte";
                } elseif ($icono == 2) {
                    $reporteNombre = "<i class='fa fa-list' aria-hidden='true'></i> Título";
                } else {
                    $reporteNombre = "Reporte";
                }
                break;
            case 1:
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Incidencia";
                } elseif ($icono == 2) {
                    $reporteNombre = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Título";
                } else {
                    $reporteNombre = "Incidencia";
                }
                break;
            case 2:
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-map-marker' aria-hidden='true'></i> Ubicaciones";
                } elseif ($icono == 2) {
                    $reporteNombre = "<i class='fa fa-map-marker' aria-hidden='true'></i>Nombre Ubicación";
                } else {
                    $reporteNombre = "Elemento";
                }
                break;
            case 3:
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-list-alt' aria-hidden='true'></i> Inventario";
                } elseif ($icono == 2) {
                    $reporteNombre = "<i class='fa fa-list-alt' aria-hidden='true'></i> Identificador";
                } else {
                    $reporteNombre = "Elemento";
                }
                break;
            case 4:
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-clone' aria-hidden='true'></i> Seguimiento a Incidencia";
                } else {
                    $reporteNombre = "Seguimiento a Incidencia";
                }
                break;
            case 5:
                if ($icono == 1) {
                    $reporteNombre = "<i class='fa fa-file-o' aria-hidden='true'></i> Documento Bim";
                } elseif ($icono == 2) {
                    $reporteNombre = "<i class='fa fa-list-alt' aria-hidden='true'></i> Título";
                } else {
                    $reporteNombre = "Documento Bim";
                }
                break;

                case 6:
                if ($icono == 1)
                    $reporteNombre = "<i class='fas fa-clipboard-list'></i> Asistencia";
                elseif ($icono == 2)
                    $reporteNombre = "<i class='fas fa-clipboard-list'></i> Título";
                else
                    $reporteNombre = "Asistencia";
                break;

            case 7:
                if ($icono == 1)
                    $reporteNombre = "<i class='fas fa-clipboard-list'></i> Termino de jornada laboral";
                elseif ($icono == 2)
                    $reporteNombre = "<i class='fas fa-clipboard-list'></i> Título";
                else
                    $reporteNombre = "Termino de jornada laboral";
                break;

        }
        return $reporteNombre;
    }

    /* ::::::::::::::::::::::::::::::::::::::::: ICONO TIPO REPORTE :::::::::::::::::::::::::::::::::::::::::::::::: */
    public function iconoReporteId($numero)
    {
        switch ($numero) {
            case 0:
                $reporteNombre = "<i class='fa fa-list' aria-hidden='true'></i>";
                break;
            case 1:
                $reporteNombre = "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>";
                break;
            case 2:
                $reporteNombre = "<i class='fa fa-map-marker' aria-hidden='true'></i>";
                break;
            case 3:
                $reporteNombre = "<i class='fa fa-list-alt' aria-hidden='true'></i>";
                break;
            case 4:
                $reporteNombre = "<i class='fa fa-file' aria-hidden='true'></i>";
                break;
        }
        return $reporteNombre;
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::: AREAS ::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function areas($idareas, $allareas)
    {
        $areas = explode(",", $idareas);
        foreach ($allareas as $area) {
            if (in_array($area->id_Area, $areas)) {
                $gpoareas = $gpoareas . "," . $area->nombre_Area;
            }
        }
        $gpoareas = substr($gpoareas, 1);
        return $gpoareas;
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::: PERFILES ::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function perfiles($idperfil, $allperfiles)
    {
        $gpoperfiles = '';
        $perfiles = explode(",", $idperfil);
        if (is_object($allperfiles) || is_array($allperfiles)) {
            foreach ($allperfiles as $perfil) {
                if (in_array($perfil->id_Perfil_Usuario, $perfiles)) {
                    $gpoperfiles = $gpoperfiles . ", " . $perfil->nombre_Perfil;
                }
            }
            $gpoperfiles = substr($gpoperfiles, 1);
        }
        return $gpoperfiles;
    }


    /* ::::::::::::::::::::::::::::::::::::::::::::CAMPOS NOMBRE CAMPO:::::::::::::::::::::::::::::::::::::::::::::: */
    public function nombreCampo($tipo_Reactivo)
    {
        switch ($tipo_Reactivo) {
            case "text":
                $nombre_reactivo = "Texto corto";
                break;
            case "textarea":
                $nombre_reactivo = "Texto largo";
                break;
            case "radio":
                $nombre_reactivo = "Una opción";
                break;
            case "checkbox":
                $nombre_reactivo = "Check list";
                break;
            case "number":
                $nombre_reactivo = "Número";
                break;
            case "date":
                $nombre_reactivo = "Fecha";
                break;
            case "time":
                $nombre_reactivo = "Hora";
                break;
            case "select":
                $nombre_reactivo = "Menú";
                break;
            case "file":
                $nombre_reactivo = "Imagen";
                break;
            case "label":
                $nombre_reactivo = "Etiqueta";
                break;
            case "checkbox-incidencia":
                $nombre_reactivo = "Incidencia";
                break;
            case "check_list_asistencia":
                $nombre_reactivo = "Asistencia";
                break;
            case "text-cadenamiento":
                $nombre_reactivo = "Cadenamiento";
                break;
            case "select-catalogo":
                $nombre_reactivo = "Catalogo";
                break;
            case "rango_fechas":
                $nombre_reactivo = "Rango de Fechas";
                break;
            case "select-tabla":
                $nombre_reactivo = "General(Tabla)";
                break;
            case "decimal":
                $nombre_reactivo = "Decimal";
                break;
        }
        return $nombre_reactivo;
    }


    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::: SI/NO ::::::::::::::::::::::::::::::::::::::::::::::: */
    public function replaceSiNo($valor)
    {
        if ($valor == 1) {
            $texto = "<i class='fa fa-check' aria-hidden='true'></i>";
        } else {
            $texto = "<i class='fa fa-close' aria-hidden='true'></i>";
        }
        return $texto;
    }

    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::: SI/NO ::::::::::::::::::::::::::::::::::::::::::::::: */
    public function formatearFecha($fecha)
    {
        $text = str_replace('/', '-', $fecha);
        $date = new DateTime($text);
        //echo $calendario . " " . $date->format('d-m-Y');
        return $date->format('d-m-Y');
    }

    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::: SI/NO ::::::::::::::::::::::::::::::::::::::::::::::: */
    public function formatearHora($fecha)
    {
        $text = str_replace('/', ':', $fecha);
        $date = new DateTime($text);
        //echo $calendario . " " . $date->format('d-m-Y');
        return $date->format('H:i:s');
    }

    // *********************************** VALIDAR HORA EN FORMATO HH:MM:SS ********************************************
    function validateTime($time)
    {
        $pattern = "/^([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])[\:]([0-5][0-9])$/";
        if (preg_match($pattern, $time))
            return true;
        return false;
    }

    // ****************************** FUNCION PARA VALIDAR DATOS OBLIGATORIOS Y TIPO DE DATO ***************************
    public function validate($campos)
    {
        foreach ($campos as $campo) {
            if ($campo['obligatorio'] && empty($campo['valorCampo'])) {
                $data = array(
                    'mensaje' => 'Ingresar todos los campos que son obligatorios',
                    'status' => false
                );
                break;
            } elseif ($campo['nombreCampo']) {
                if (!preg_match($campo["validacion"], $campo['valorCampo'])) {
                    $data = array(
                        'mensaje' => "Formato no valido para el campo {$campo['nombreCampo']}",
                        'status' => false
                    );
                    break;
                } else {
                    $data = array(
                        'mensaje' => "Continuar",
                        'status' => true
                    );
                }
            }
        }

        return $data;
    }


    // ***************************** OBTENER TODOS LOS REGISTROS DE REPORTE TIPO 5 (BIM) *******************************
    public function getDatosCodigosLlenadosDocBim($id_Proyecto)
    {

        $conectar = new Conectar();
        $adapter = $conectar->conexion();
        $entidadBase = new EntidadBase('tabla', $adapter);

        $datosDocBim = $entidadBase->getAllDatosByReportePlanos($id_Proyecto);

        $reporteDocBim = $entidadBase->getReporteByProyectoAndTipoReporteDocBim($id_Proyecto);

        return [$datosDocBim, $reporteDocBim];
    }


    // ************************************ VERIFICAR SI EL PROYECTO TIENE GANNT ***************************************
    function existeGantt($idProyecto)
    {
        $conectar = new Conectar();
        $adapter = $conectar->conexion();
        $entidadBase = new EntidadBase('tabla', $adapter);

        $registroGantt = $entidadBase->getIdGanttByid_proyecto($idProyecto);

        return $registroGantt ? true : false;
    }


    // *********************************** CONVERTIR NOMBRE DE ARCHIVO (EXPEDIENTES) ***********************************
    public function convertirNombreExpedientes($nombre)
    {
        switch ($nombre) {
            case 'acta':
                $newNombre = 'Acta de Nacimiento';
                break;
            case 'curp':
                $newNombre = 'CURP';
                break;
            case 'rfc':
                $newNombre = 'RFC';
                break;
            case 'cv':
                $newNombre = 'CV';
                break;
            case 'cbte_domicilio':
                $newNombre = 'Comprobante de Domicilio';
                break;
            case 'examen_medico':
                $newNombre = 'Examen médico';
                break;
            case 'nss':
                $newNombre = 'Número de Seguro Social';
                break;
            case 'id_oficial':
                $newNombre = 'Identificacion Oficial';
                break;
            case 'cons_estudio':
                $newNombre = 'Constancia de Estudio';
                break;
            case 'cedula_prof':
                $newNombre = 'Cédula Profesional';
                break;
            case 'carta_recomend':
                $newNombre = 'Carta de Recomendación';
                break;
            case 'contrato_laboral':
                $newNombre = 'Contrato Laboral';
                break;
            case 'constancia_laboral':
                $newNombre = 'Constancia Laboral';
                break;
            case 'doc_internos':
                $newNombre = 'Documentos Internos';
                break;
            case 'fmi':
                $newNombre = 'FMP - Formato Múltiple de Incidencias de Personal';
                break;
            case 'fmp':
                $newNombre = 'Formato Múltiple de Movimiento de Personal';
                break;
            default:
                $newNombre = '';
        }

        return $newNombre;
    }

}

ob_end_flush();
