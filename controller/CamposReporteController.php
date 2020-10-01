<?php

class CamposReporteController extends ControladorBase
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

    /*------------------------------------------ ORDENAR LOS CAMPOS --------------------------------------------------*/
    public function ordenar()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $id_Reporte = $_GET["Id_Reporte"];
        $camporeporte = new CampoReporte($this->adapter);
        $orden = $_GET['orden'];
        $orden = substr($orden, 2);
        $arrayorden = explode(",", $orden);

        $necessaryFields = $_GET['necesario'];
        $necessaryFields = substr($necessaryFields, 2);
        $arrayNecessaryFields = explode(",", $necessaryFields);

        $numero = 0;
        foreach ($arrayorden as $orden) {
            $id = $arrayorden[$numero];
            $secuencia = $numero + 1;
            $numero++;
            $save = $camporeporte->modificarConfiguracion($id, $secuencia, $arrayNecessaryFields[$numero]);
            $this->redirect("CamposReporte", "getCamposByReporte&Id_Reporte=$id_Reporte&insercion=$insercion&newElemento=$newElemento");
        }
    }

    /*----------------------------------- VISTA DE LOS CAMPOS DE UNA CONFIGURACION DE REPORTE ------------------------*/
    public function getCamposByReporte()
    {

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        if (empty($_POST["Id_Reporte"])) {
            $id_Reporte = $_GET["Id_Reporte"];
        } else if (empty($_GET["Id_Reporte"])) {
            $id_Reporte = $_POST["Id_Reporte"];
        }

        $camporeporte = new CampoReporte($this->adapter);
        $allcamposreportes = $camporeporte->getAllCampoReporte($this->id_Proyecto_constant);
        $camposreporteById = $camporeporte->getAllCamposReporte($this->id_Proyecto_constant, $id_Reporte);

        if (empty($camposreporteById)) {
            $camposreporteById = $camporeporte->getAllCatReportesByIdReporte($id_Reporte);
        }


        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "Campos del Reporte <u>" . $camposreporteById[0]->nombre_Reporte . "</u>";
        }

        $campo = new Campo($this->adapter);
        $allcampos = $campo->getAllCampo();
        $reporte = new Reporte($this->adapter);
        $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);

        $proyectoCon = new Proyecto($this->adapter);
        $proyectoActual = $proyectoCon->getProyectoById($this->id_Proyecto_constant);

        if ($proyectoActual->logos) {
            $logos = (array)json_decode($proyectoActual->logos);
        } else {
            $logos = NULL;
        }

        $this->view("index", array(
            "allcamposreportes" => $allcamposreportes, "allcamposreporteById" => $camposreporteById, "allcampos" => $allcampos,
            "allreportes" => $allreportes, "mensaje" => $mensaje, "insercion" => $insercion, "newElemento" => $newElemento, "logos" => $logos
        ));
    }

    /*-------------------------------- AÑADIR ELEMENTOS A REPORTES ANTERIORES POR ID----------------------------------*/
    public function anadirCampos()
    {
        if (isset($_POST['valores'])) {
            $resultado = " ";
            $idReporte = $_POST['valores'];
            $campoReporte = new CampoReporte($this->adapter);
            $campoReporte->set_id_Reporte($idReporte);

            //ARRAYCONFIGURACION
            $arraconfiguracion = $campoReporte->getAllCamposReporte($this->id_Proyecto_constant, $idReporte);
            $indice = 0;
            foreach ($arraconfiguracion as $camposDelReporte) {
                $configReporte[$indice] = $camposDelReporte->id_Configuracion_Reporte;
                $indice++;
            }

            //ARRAYGRUPOS
            $arraygpos = $campoReporte->getReportesLlenadosByIdReporte($idReporte);

            $contadorReportes = 0;
            $contadorCampos = 0;
            if (is_array($arraygpos) || is_object($arraygpos)) {
                foreach ($arraygpos as $idGpo) {
                    $idgpo = $idGpo->id_Gpo_Valores_Reporte;

                    //ARRAYVALORES
                    $arrayvalores = $campoReporte->getReporteLlenadoById($idgpo);

                    $indice = 0;
                    foreach ($arrayvalores as $valoresLlenados) {
                        $configReporteLlenado[$indice] = $valoresLlenados->id_Configuracion_Reporte;
                        $indice++;
                    }

                    //COMPARAR IDS CONF VALORES-CONFIGURACIONES
                    $arrayidsfaltantes = array_diff($configReporte, $configReporteLlenado);
                    //print_r($configReporte);
                    //print_r($configReporteLlenado);

                    if (!empty($arrayidsfaltantes)) {
                        //$resultado=array_values($arrayidsfaltantes);
                        $resultado = implode(",", $arrayidsfaltantes);

                        $infoFaltante = $campoReporte->getIdReportesConCamposFaltantes($resultado, $idReporte, $this->id_Proyecto_constant);
                        //CONSULTAR CONFIGURACION-REPORTE-CAMPO POR ID CONFIGURACION
                        //IN $idfaltantes;

                        //print_r($resultado);
                        $contadorCampos = 0;
                        foreach ($infoFaltante as $camposFaltantes) {
                            $valorelemento = $camposFaltantes->tipo_Valor_Campo;

                            if ($valorelemento == "varchar") {
                                $valor = explode("/", $camposFaltantes->Valor_Default);

                                $valorelementoentero = 'NULL';
                                $valorelementotexto = $valor[0];
                            } else {
                                $valorelementoentero = 0;
                                $valorelementotexto = NULL;
                            }

                            //LLENADO DE CAMPOS FALTANTES
                            if ($camposFaltantes->tipo_Reactivo_Campo != "file") {
                                $llenadoreporte = new LlenadoReporte($this->adapter);
                                $llenadoreporte->set_id_Proyecto($this->id_Proyecto_constant);
                                $llenadoreporte->set_id_Configuracion_Reporte($camposFaltantes->id_Configuracion_Reporte);
                                $llenadoreporte->set_valor_Entero_Reporte($valorelementoentero);
                                $llenadoreporte->set_valor_Texto_Reporte($valorelementotexto);
                                $llenadoreporte->set_id_Gpo_Valores_Reporte($idgpo);
                                $save = $llenadoreporte->saveNewLlenado();
                                $contadorCampos++;
                            }
                        }
                    }
                    $contadorReportes++;
                }
            }

            if ($contadorCampos != 0)
                echo "Se insertaron " . $contadorCampos . " campos en " . $contadorReportes . " reportes.";
            else
                echo "Los campos ya se encuentran en todos los reportes.";
            //echo "Se insertaron ".$contadorCampos." campos en ".$contadorReportes." reportes.";
        } else {
            echo "No se encuentra el Id del Reporte";
        }

    }

    /*---------------------------------------- METODO CREAR NUEVA CONFIGURACION --------------------------------------*/
    public function guardarnuevo()
    {
        if (!empty($_POST["Id_Reporte"])) {
            $id_Reporte = $_POST["Id_Reporte"];
        } else {
            $id_Reporte = $_POST["id_Reporte"];
        }

        $idCampo = $_POST["Id_Campo_Reporte"];

        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["id_Proyecto"]) || empty($id_Reporte) || empty($idCampo)
        ) {
            $save = "Llenar todos los campos";

            $this->redirect("CamposReporte", "index&mensaje=$save");
        } //SE CREA NUEVA CONFIGURACION
        else {
            $camporeporte = new CampoReporte($this->adapter);

            $allcamposreportes = $camporeporte->getAllCamposReporte($this->id_Proyecto_constant, $id_Reporte);
            $camporeporte->set_id_Proyecto($_POST["id_Proyecto"]);
            $camporeporte->set_id_Reporte($id_Reporte);
            $camporeporte->set_id_Campo_Reporte($idCampo);
            $camporeporte->set_Campo_Necesario($_POST["Campo_Necesario"]);
            //CONSULTAR DATOS DEL CAMPO
            $datoscampo = $camporeporte->getCampoById($_POST["Id_Campo_Reporte"]);
            //CONSULTAR DATOS DEL REPORTE
            $datosreporte = $camporeporte->getReporteById($id_Reporte);
            $save = $camporeporte->saveNewConfiguracion($allcamposreportes, $datoscampo, $datosreporte);

            // print_r([$allcamposreportes, $datoscampo]);
            // die();

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 1) {
                $insercion = 1;
                $mensaje = 'Se ha agregado el campo: "' . $datoscampo->nombre_Campo . '"';
            } elseif ($save == 2) {
                $insercion = 2;
                $mensaje = 'El campo "' . $datoscampo->nombre_Campo . '" ya existe en este reporte';
            } else {
                $insercion = 2;
                $mensaje = 'El reporte ya tiene un campo tipo Fotografía(Archivo)';
            }

            $this->redirect("CamposReporte", "getCamposByReporte&Id_Reporte=$id_Reporte&insercion=$insercion&newElemento=$mensaje");
        }
    }

    /*----------------------------------METODO GUARDA VARIAS MODIFICACIONES-----------------------------*/
    public function savemods()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["inp"])) {
            $save = "No está pasando el inp";
            $id_Reporte = 1;
        } //SE GUARDA MODIFICACION
        else {
            $sepvar = explode("|", $_POST["inp"]);
            foreach ($sepvar as $varsep) {
                $spa = explode(",", $ps);
                $names = ["n_secuencia", "secuencia", "id_Configuracion_Reporte", "Id_Reporte", "Id_Campo_Reporte", "Campo_Necesario"];
                $id_Reporte = $spa[3];
                $id = $spa[2];
                $camporeporte = new CampoReporte($this->adapter);
                $allcamposreportes = $camporeporte->getAllCamposReporte($this->id_Proyecto_constant, $id_Reporte);
                $camporeporte->set_id_Proyecto($_POST["id_Proyecto"]);
                $camporeporte->set_Id_Reporte($id_Reporte);
                $camporeporte->set_Id_Campo_Reporte($spa[4]);
                $camporeporte->set_Campo_Necesario($spa[5]);
                $camporeporte->set_Secuencia($spa[0]);
                $save = $camporeporte->modificarConfiguracionVarios($id, $allcamposreportes);

            }
            $mensaje = "<i class='fa fa-list' aria-hidden='true'></i> Se ha guardado la modificación";

        }
        $this->redirect("CamposReporte", "getCamposByReporte&mensaje=$mensaje&Id_Reporte=$id_Reporte");
    }

    /*---------------------------------------- METODO BORRAR CONFIGURACION -------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["id_Configuracion_Reporte"])) {
            $id_Reporte = $_GET["Id_Reporte"];
            $id = (int)$_GET["id_Configuracion_Reporte"];
            $camporeporte = new CampoReporte($this->adapter);
            $nombreCampo = $camporeporte->getCampoReporteByIdReporteAndIdConfiguracionReporte($this->id_Proyecto_constant, $id_Reporte, $id)[0]->nombre_Campo;

            $camporeporte->deleteElementoById($id, 'Conf_Reportes_Campos');
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se eliminó el campo "' . $nombreCampo . '"';
        }
        $this->redirect("CamposReporte", "getCamposByReporte&Id_Reporte=$id_Reporte&insercion=$insercion&newElemento=$mensaje");
    }
}

?>
