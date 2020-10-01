<?php

class PlantillaController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    /*--- VISTA DE TODAS LAS ACTIVIDADES ---*/
    public function index()
    {
        $mensaje = $_GET['mensaje'];
        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-list-alt' aria-hidden='true'></i> Modela tu primer Reporte";
        }

        $this->view("index", array(
            "mensaje" => $mensaje
        ));

    }

    public function guardarPlantilla()
    {
        $id_Plantilla = $_POST['id_Plantilla'];
        $fecha = date('d-m-Y');

        date_default_timezone_set("America/Mexico_City");
        $hora = date('G:i:s');

        $id_Usuario = $_SESSION[ID_USUARIO_SUPERVISOR];

        //OBTENER TODAS LAS AREAS DE LA EMPRESA
        $areas = new Area($this->adapter);
        $allAreas = $areas->getAllArea();
        $id_Areas = array();
        foreach ($allAreas as $area) {
            $id_Areas[] = $area->id_Area;
        }
        $id_AreasStr = implode(",", $id_Areas);

        // PERFILES DE LA EMPRESA
        $perfil = new Perfil($this->adapter);
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $noId_Perfil_User = '';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allperfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        /*$id_Perfiles = array();
        foreach ($allperfiles as $perfil) {
            $id_Perfiles[] = $perfil->id_Perfil_Usuario;
        }
        $id_PerfilesStr = implode(",", $id_Perfiles);*/

        $id_PerfilesStr = '';

        switch ($id_Plantilla) {
            case 1:
                // *********************************************CREAR ELEMENTOS ****************************************
                $reporte = new Reporte($this->adapter);
                $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
                $reporte->set_id_Proyecto($this->id_Proyecto_constant);
                $reporte->set_nombre_Reporte('Reporte de trabajo');
                $reporte->set_descripcion_Reporte('Aqui va la descripción de tu reporte');
                $reporte->set_Areas($id_AreasStr);
                $reporte->set_tiempo_Reporte(0);
                $reporte->set_tiempo_Alarma(0);
                $reporte->set_tiempo_Revision(0);
                $reporte->set_tipo_Reporte(0);
                $reporte->set_Perfiles($id_PerfilesStr);
                $reporte->set_id_Seguimiento(0);
                $save = $reporte->saveNewReporte($allreportes);
                // *********************************************CREAR ELEMENTOS ****************************************


                /******************************************************** *********************************************/


                // *********************************************INSERTAR CAMPOS*****************************************
                $campo = new Campo($this->adapter);
                $allcampos = $campo->getAllCampo();
                $campo->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo = 'Fecha';
                $campo->set_nombre_Campo($nombreCampo);
                $campo->set_descripcion_Campo($nombreCampo);
                $campo->set_tipo_Valor_Campo('varchar');
                $campo->set_tipo_Reactivo_Campo('date');
                $campo->set_Valor_Default('');
                $save = $campo->saveNewCampoPlantilla($allcampos);

                $campo1 = new Campo($this->adapter);
                $campo1->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo1 = 'Hora';
                $campo1->set_nombre_Campo($nombreCampo1);
                $campo1->set_descripcion_Campo($nombreCampo1);
                $campo1->set_tipo_Valor_Campo('varchar');
                $campo1->set_tipo_Reactivo_Campo('time');
                $campo1->set_Valor_Default('');
                $save = $campo1->saveNewCampoPlantilla($allcampos);

                $campo2 = new Campo($this->adapter);
                $campo2->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo2 = 'Descripción';
                $campo2->set_nombre_Campo($nombreCampo2);
                $campo2->set_descripcion_Campo($nombreCampo2);
                $campo2->set_tipo_Valor_Campo('varchar');
                $campo2->set_tipo_Reactivo_Campo('text');
                $campo2->set_Valor_Default('');
                $save = $campo2->saveNewCampoPlantilla($allcampos);

                $campo3 = new Campo($this->adapter);
                $campo3->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo3 = 'Se concluyo el trabajo';
                $campo3->set_nombre_Campo($nombreCampo3);
                $nombreCampo3 = str_replace(' ', '_', $nombreCampo3);
                $campo3->set_descripcion_Campo($nombreCampo3);
                $campo3->set_tipo_Valor_Campo('varchar');
                $campo3->set_tipo_Reactivo_Campo('radio');
                $campo3->set_Valor_Default('Si/No');
                $save = $campo3->saveNewCampoPlantilla($allcampos);

                $campo4 = new Campo($this->adapter);
                $campo4->set_id_Proyecto($this->id_Proyecto_constant);
                $nombreCampo4 = 'Fotografías(3)';
                $campo4->set_nombre_Campo($nombreCampo4);
                $campo4->set_descripcion_Campo($nombreCampo4);
                $campo4->set_tipo_Valor_Campo('varchar');
                $campo4->set_tipo_Reactivo_Campo('file');
                $campo4->set_Valor_Default('Foto1/Foto2/Foto3');
                $save = $campo4->saveNewCampoPlantilla($allcampos);
                // *********************************************INSERTAR CAMPOS*****************************************


                /******************************************************** *********************************************/


                // *************************************** INSERTAR CAMPOS A REPORTES **********************************

                $camporeporte = new CampoReporte($this->adapter);
                //$campos = $camporeporte->getAllCampoByIdProyecto($this->id_Proyecto_constant);

                //OBTENER ULTIMO ID DE REPORTE DEL PROYECTO ACTUAL
                $ultimoId = $camporeporte->getAllCatReportesUltimoIdReporte();
                $id_Reporte = $ultimoId[0]->id_Reporte;

                // Fecha
                $campoFecha = $camporeporte->getAllCampoByNombre('Fecha');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoFecha[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(1);
                $save = $camporeporte->saveNewConfiguracionPlantilla();

                // Hora
                $campoHora = $camporeporte->getAllCampoByNombre('Hora');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoHora[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(2);
                $save = $camporeporte->saveNewConfiguracionPlantilla();

                // Descripción
                $campoDescripcion = $camporeporte->getAllCampoByNombre('Descripción');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoDescripcion[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(3);
                $save = $camporeporte->saveNewConfiguracionPlantilla();

                // Se concluyo el trabajo
                $campoTrabajo = $camporeporte->getAllCampoByNombre('Se concluyo el trabajo');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoTrabajo[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(1);
                $camporeporte->set_Secuencia(4);
                $save = $camporeporte->saveNewConfiguracionPlantilla();

                // Fotografías(3)
                $campoFoto = $camporeporte->getAllCampoByNombre('Fotografías(3)');
                $camporeporte->set_id_Proyecto($this->id_Proyecto_constant);
                $camporeporte->set_id_Reporte($id_Reporte);
                $camporeporte->set_id_Campo_Reporte($campoFoto[0]->id_Campo_Reporte);
                $camporeporte->set_Campo_Necesario(0);
                $camporeporte->set_Secuencia(5);
                $save = $camporeporte->saveNewConfiguracionPlantilla();

                // *************************************** INSERTAR CAMPOS A REPORTES **********************************


                /******************************************************** *********************************************/


                // ************************************* INSERTAR EN TABLA VALORES *************************************
                //OBTENER EL ID GRUPO DE LOS CAMPOS
                $llenadoreporte = new LlenadoReporte($this->adapter);
                $allreportesllenados = $llenadoreporte->getAllReportesLlenados();
                if (count($allreportesllenados) == 0) {
                    $grupovalores = 1;
                }
                if (count($allreportesllenados) != 0) {
                    $ultimogrupo = $llenadoreporte->getUltimoReporteLlenado();
                    $grupovalores = (int)$ultimogrupo + 1;
                }

                $configuracion = $llenadoreporte->getCampoReporteByIdReporte($id_Reporte);

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($this->id_Proyecto_constant);
                $llenadoreporte->set_id_Configuracion_Reporte($configuracion[0]->id_Configuracion_Reporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($fecha);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenadoPlantilla();

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($this->id_Proyecto_constant);
                $llenadoreporte->set_id_Configuracion_Reporte($configuracion[1]->id_Configuracion_Reporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte($hora);
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenadoPlantilla();

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($this->id_Proyecto_constant);
                $llenadoreporte->set_id_Configuracion_Reporte($configuracion[2]->id_Configuracion_Reporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte('Aqui va tu descripción');
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenadoPlantilla();

                $llenadoreporte = new LlenadoReporte($this->adapter);
                $llenadoreporte->set_id_Proyecto($this->id_Proyecto_constant);
                $llenadoreporte->set_id_Configuracion_Reporte($configuracion[3]->id_Configuracion_Reporte);
                $llenadoreporte->set_valor_Entero_Reporte('NULL');
                $llenadoreporte->set_valor_Texto_Reporte('Si');
                $llenadoreporte->set_id_Gpo_Valores_Reporte($grupovalores);
                $save = $llenadoreporte->saveNewLlenadoPlantilla();
                // ************************************* INSERTAR EN TABLA VALORES *************************************


                /******************************************************** *********************************************/


                // ************************************* INSERTAR EN REPORTES LLENADOS *********************************
                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];
                if (empty($latitud) && empty($longitud)) {
                    $latitud = 0;
                    $longitud = 0;
                }

                $registrarreportellenado = new ReporteLlenado($this->adapter);
                $registrarreportellenado->set_id_Gpo_Valores_Reporte($grupovalores);
                $registrarreportellenado->set_id_Usuario($id_Usuario);
                $registrarreportellenado->set_id_Reporte($id_Reporte);
                $registrarreportellenado->set_titulo_Reporte('Aquí va el título del reporte');
                $registrarreportellenado->set_id_Gpo_Padre(0);
                $registrarreportellenado->set_latitud_Reporte($latitud);
                $registrarreportellenado->set_longitud_Reporte($longitud);
                $registrarreportellenado->set_clas_Reporte(0);
                $registrarreportellenado->saveNewReporteLlenado($allreportesllenados);
                // ************************************* INSERTAR EN REPORTES LLENADOS *********************************


                $this->redirect('SeguimientosReporte', 'index&tipo=0,1');

                break;
            case 2:
                // *********************************************CREAR ELEMENTOS ****************************************
                $reporte = new Reporte($this->adapter);
                $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
                $reporte->set_id_Proyecto($this->id_Proyecto_constant);
                $reporte->set_nombre_Reporte('Reporte de bitácora');
                $reporte->set_descripcion_Reporte('Aqui va la descripción de tu reporte');
                $reporte->set_Areas($id_AreasStr);
                $reporte->set_tiempo_Reporte(0);
                $reporte->set_tiempo_Alarma(0);
                $reporte->set_tiempo_Revision(0);
                $reporte->set_tipo_Reporte(0);
                $reporte->set_Perfiles('2');
                $reporte->set_id_Seguimiento(0);
                $save = $reporte->saveNewReporte($allreportes);
                // *********************************************CREAR ELEMENTOS ****************************************

                //$this->redirect('SeguimientosReporte', 'action=index&tipo=0,1');
                break;
            case 3:
                // *********************************************CREAR ELEMENTOS ****************************************
                /*
                $reporte = new Reporte($this->adapter);
                $allreportes = $reporte->getAllReporte($this->id_Proyecto_constant);
                $reporte->set_id_Proyecto($this->id_Proyecto_constant);
                $reporte->set_nombre_Reporte('Reporte de ubicación');
                $reporte->set_descripcion_Reporte('Aqui va la descripción de tu reporte');
                $reporte->set_Areas('1');
                $reporte->set_tiempo_Reporte(0);
                $reporte->set_tiempo_Alarma(0);
                $reporte->set_tiempo_Revision(0);
                $reporte->set_tipo_Reporte(2);
                $reporte->set_Perfiles('2');
                $reporte->set_id_Seguimiento(0);
                $save = $reporte->saveNewReporte($allreportes);
                */
                // *********************************************CREAR ELEMENTOS ****************************************

                //$this->redirect('SeguimientosReporte', 'action=index&tipo=0,1');
                break;
            case 4:
                $this->redirect('Reportes', 'index');
                break;
        }
    }


}

?>
