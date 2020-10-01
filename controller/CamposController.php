<?php

class CamposController extends ControladorBase
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
    }

    /*-------------------------------------- VISTA DE TODOS LOS CAMPOS -----------------------------------------------*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-check-square-o' aria-hidden='true'></i> Configuración Campo";
        }
        $campo = new Campo($this->adapter);
        $allcampos = $campo->getAllCampo($this->id_Proyecto_constant);
        //OBTNER CATEGORIAS DE CATALOGO
        $catalogo = new Cat_Categoria($this->adapter);
        $allcategorias = $catalogo->getCatCategoriaGroupByCategoria();
        //var_dump($allcategorias);
        $this->view("index", array(
            "allcampos" => $allcampos, "allcategorias" => $allcategorias, "mensaje" => $mensaje, "insercion" => $insercion,
            "newElemento" => $newElemento
        ));
    }

    /*----------------------------------------- VISTA MODIFICAR CAMPO ------------------------------------------------*/
    public function modificar()
    {
        $id = $_POST["id_Campo_Reporte"];
        if (isset($id)) {
            $campo = new Campo($this->adapter);
            $allcampos = $campo->getAllCampo($this->id_Proyecto_constant);
            $datoscampo = $campo->getCampoById($id);

            $valoresDefault = explode("/", $datoscampo->Valor_Default);

            //OBTNER CATEGORIAS DE CATALOGO
            $catalogo = new Cat_Categoria($this->adapter);
            $allcategorias = $catalogo->getCatCategoriaGroupByCategoria();
        }

        echo json_encode(['datosCampo' => $datoscampo, 'valoresDefault' => $valoresDefault]);
    }

    /*------------------------------------------- METODO CREAR NUEVO CAMPO -------------------------------------------*/
    public function guardarnuevo()
    {
        //DESCRIPCION
        $cadena = trim($_POST["nombre_Campo"]);
        $descripcion = str_replace(" ", "_", $cadena);
        $descripcion = str_replace(".", "", $descripcion);
        //VALOR DEFAUL
        $conteo = $_POST["conteo_default"];

        if ($conteo == "" && $_POST["mitexto_1"] != NULL) {
            $valor_default = "//" . $_POST["mitexto_1"];
        } else if ($conteo != NULL) {
            for ($x = 1; $x <= $conteo; $x++) {
                if ($_POST["mitexto_" . $x] != null || $_POST["mitexto_" . $x] != '') {
                    $valor_default = $valor_default . "/" . $_POST["mitexto_" . $x . ""];
                }
            }
        }

        $valor_default = substr($valor_default, 1);
        //TIPO VALOR
        switch ($_POST["tipo_Reactivo_Campo"]) {
            case "decimal";
            case "check_list_asistencia":
            case "rango_fechas":
            case "text-cadenamiento":
            case "label":
            case "select-monitoreo":
            case "time":
            case "date":
            case "textarea":
            case "text":
            case "select-tabla":
                $valor_campo = "varchar";
                $valor_default2 = "";
                break;
            case "select":
            case "checkbox":
            case "radio":
                $valor_campo = "varchar";
                $valor_default2 = $valor_default;
                break;
            case "number":
                $valor_campo = "int";
                $valor_default2 = "";
                break;
            case "file":
                $valor_default = "";
                $valor_campo = "varchar";
                $cantidad = $_POST["cantImg"];
                for ($x = 1; $x <= $cantidad; $x++) {
                    $valor_default = $valor_default . "/" . "Foto" . $x;
                }
                $valor_default = substr($valor_default, 1);
                $valor_default2 = $valor_default;
                break;
            case "checkbox-incidencia":
                $valor_campo = "varchar";
                $valor_default2 = "Sí/No";
                break;
            case "select-catalogo":
                $valor_campo = "varchar";
                $valor_default2 = implode(",", $_POST['categorias']);
                break;
        }
        //SE LLENA EL FORMULARIO
        $campo = new Campo($this->adapter);
        $allcampos = $campo->getAllCampo();
        $campo->set_id_Proyecto($this->id_Proyecto_constant);
        $nombreCampo = $_POST["nombre_Campo"];
        $campo->set_nombre_Campo($nombreCampo);
        $campo->set_descripcion_Campo($descripcion);
        $campo->set_tipo_Valor_Campo($valor_campo);
        $campo->set_tipo_Reactivo_Campo($_POST["tipo_Reactivo_Campo"]);
        $campo->set_Valor_Default($valor_default2);
        $save = $campo->saveNewCampo($allcampos);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado el campo: "' . $nombreCampo . '"';
        } else {
            $insercion = 2;
            $mensaje = 'El campo "' . $nombreCampo . '" ya existe';
        }

        $this->redirect("Campos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*---------------------------------------- METODO GUARDAR MODIFICACION AREA --------------------------------------*/
    public function guardarmodificacion()
    {
        //DESCRIPCION
        $cadena = trim($_POST["nombre_Campo"]);
        $descripcion = str_replace(" ", "_", $cadena);
        $descripcion = str_replace(".", "", $descripcion);
        //VALOR DEFAUL

        for ($x = 0; $x <= $_POST["conteo_defaultNoMod"]; $x++) {
            if ($_POST["mitexto_" . $x] != null || $_POST["mitexto_" . $x] != '') {
                $valor_default = $valor_default . "/" . $_POST["mitexto_" . $x . ""];
            }
        }

        $valor_default = substr($valor_default, 1);
        $patrones = array('@/{2,}@i');
        $sustituciones = array('');
        $valor_default = preg_replace($patrones, $sustituciones, $valor_default);
        //TIPO VALOR
        switch ($_POST["tipo_Reactivo_Campo"]) {
            case "decimal":
            case "textarea":
            case "time":
            case "date":
            case "text-cadenamiento":
            case "select-monitoreo":
            case "check_list_asistencia":
            case "label":
            case "text":
                $valor_campo = "varchar";
                $valor_default2 = "";
                break;
            case "checkbox":
            case "select":
            case "radio":
                $valor_campo = "varchar";
                $valor_default2 = $valor_default;
                break;
            case "number":
                $valor_campo = "int";
                $valor_default2 = "";
                break;
            case "file":
                $valor_default = "";
                $valor_campo = "varchar";
                $cantidad = $_POST["cantImg"];
                for ($x = 1; $x <= $cantidad; $x++) {
                    $valor_default = $valor_default . "/" . "Foto" . $x;
                }
                $valor_default = substr($valor_default, 1);
                $valor_default2 = $valor_default;
                break;
            case "checkbox-incidencia":
                $valor_campo = "varchar";
                $valor_default2 = "Sí/No";
                break;
            case "select-catalogo":
                $valor_campo = "varchar";
                $valor_default2 = implode(",", $_POST['categorias']);
                break;
            case 'select-tabla':
                break;
        }
        //SE LLENA EL FORMULARIO
        $campo = new Campo($this->adapter);
        $allcampos = $campo->getAllCampo();
        $id = $_POST["id_Campo_Reporte"];
        $campo->set_id_Proyecto($this->id_Proyecto_constant);
        $nombreCampo = $_POST["nombre_Campo"];
        $campo->set_nombre_Campo($nombreCampo);
        $campo->set_descripcion_Campo($descripcion);
        $campo->set_tipo_Valor_Campo($valor_campo);
        $campo->set_tipo_Reactivo_Campo($_POST["tipo_Reactivo_Campo"]);
        $campo->set_Valor_Default($valor_default2);
        $save = $campo->modificarCampo($id, $allcampos);

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        if ($save == 3) {
            $insercion = 3;
            $mensaje = 'Se ha modificado el campo: "' . $nombreCampo . '"';
        } else {
            $insercion = 2;
            $mensaje = 'El campo "' . $nombreCampo . '" ya existe';
        }

        $this->redirect("Campos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*---------------------------------------------- METODO BORRAR CAMPO ---------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["id_Campo_Reporte"])) {
            $id = (int)$_GET["id_Campo_Reporte"];
            $campo = new Campo($this->adapter);

            $allCampos = $campo->getCampoById($id);
            $nombreCampo = $allCampos->nombre_Campo;

            $campo->deleteElementoById($id, 'Campos');

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se borro el campo "' . $nombreCampo . '"';
        }

        $this->redirect("Campos", "index&insercion=$insercion&newElemento=$mensaje");

    }
}

