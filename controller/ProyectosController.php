<?php

class ProyectosController extends ControladorBase
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

    /*--- VISTA DE TODOS LOS PROYECTOS ---*/
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
            $mensaje = "<i class='fa fa-briefcase' aria-hidden='true'></i> Proyectos";
        }

        $proyecto = new Proyecto($this->adapter);
        $allproyectos = $proyecto->getAllProyecto();
        $this->view("index", array(
            "allproyectos" => $allproyectos, "mensaje" => $mensaje, "insercion" => $insercion,
            "newElemento" => $newElemento
        ));
    }

    /*--- METODO CREAR NUEVO PROYECTO---*/
    public function guardarnuevo()
    {
        $idEmpresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        $proyecto = new Proyecto($this->adapter);

        //COMPROBAR CAMPOS VACIOS
        if (!empty($_POST["proyectonombre"]) && !empty($_POST["proyectodescripcion"])) {
            $allproyectos = $proyecto->getAllProyecto();
            $nextId = (int)$proyecto->getLastProyecto() + 1;
            $respuestaLogos = $this->validarLogos($_FILES, $idEmpresa, $nextId);

            if ($respuestaLogos == 'NULL') {
                $respuestaLogos = [
                    'primary' => '',
                    'secondary' => ''
                ];
                $respuestaLogos = "'" . json_encode($respuestaLogos) . "'";
            }

            $proyecto->set_nombre_Proyecto($_POST["proyectonombre"]);
            $proyecto->set_descripcion_Proyecto($_POST["proyectodescripcion"]);
            $proyecto->set_logotipos($respuestaLogos);
            $save = $proyecto->saveNewProyecto($allproyectos);

            if ($save) {
                $insercion = 3;
                $mensaje = 'Has creado el Proyecto "' . $_POST["proyectonombre"] . '"';
                $target_path = "img/reportes/" . $idEmpresa . "/" . $nextId;
                if (!is_dir($target_path)) {
                    mkdir($target_path, 0777, true);
                }
            } else {
                $insercion = 4;
                $mensaje = '¡Parece que el nombre de proyecto que deseas crear ya existe!';
            }
        } else {
            $insercion = 4;
            $mensaje = "Debes llenar todos los campos";
        }

        $this->redirect("Proyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO GUARDAR MODIFICACION AREA ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (!empty($_POST["proyectonombre"]) && !empty($_POST["proyectodescripcion"])) {
            // Guardar Modificación
            $proyecto = new Proyecto($this->adapter);
            $allproyectos = $proyecto->getAllProyecto();
            $idProyecto = $_POST["proyectoid"];
            $idEmpresa = $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];

            $proyectoAnterior = $proyecto->getProyectoById($idProyecto);

            $respuestaLogos = $this->validarLogos($_FILES, $idEmpresa, $proyectoAnterior);

            if ($respuestaLogos == 'NULL') {
                $respuestaLogos = "'" . $proyectoAnterior->logos . "'";
            }

            $proyecto->set_nombre_Proyecto($_POST["proyectonombre"]);
            $proyecto->set_descripcion_Proyecto($_POST["proyectodescripcion"]);
            $proyecto->set_logotipos($respuestaLogos);
            $save = $proyecto->modificarProyecto($idProyecto, $allproyectos);

            //echo $save;
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save) {
                $insercion = 3;
                $mensaje = 'Se ha modificado el proyecto: "' . $proyectoAnterior->nombre_Proyecto . '"';
            } else {
                $insercion = 4;
                $mensaje = 'No se pudo modificar el proyecto.';
            }
        } else {
            $insercion = 4;
            $mensaje = 'Debes llenar todos los campos';
        }
        $this->redirect("Proyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    public function validarLogos($files, $idEmpresa, $proyectoActual)
    {
        $idProyecto = $proyectoActual->id_Proyecto;
        $nameLogos = ['primary', 'secondary'];
        $json_logos = [
            'primary' => '',
            'secondary' => ''
        ];

        $index = 0;
        $folderProyecto = 'img/logos_empresas/E' . $idEmpresa . '/P' . $idProyecto . '/';

        if (!is_null($proyectoActual->logos)) {
            $logos = json_decode($proyectoActual->logos);
        }

        foreach ($files as $logo) {
            if (isset($logos) && $index == 0) {
                $nameLogo = $logos->primary;
            } else if (isset($logos) && $index == 1) {
                $nameLogo = $logos->secondary;
            } else {
                $nameLogo = '';
            }

            if ($logo['name'] != '') {
                if (!is_dir($folderProyecto)) {
                    mkdir($folderProyecto, 0777, true);
                }

                // Eliminar Logo Anterior
                if (is_file($nameLogo)) {
                    unlink($nameLogo);
                }

                $logoSpl = new SplFileInfo($logo['name']);

                $extensionLogo = $logoSpl->getExtension();

                $rutaDestino = $folderProyecto . $idEmpresa . '_' . $idProyecto . '_' . $nameLogos[$index] . '.' . $extensionLogo;

                if (move_uploaded_file($logo['tmp_name'], $rutaDestino)) {
                    $json_logos[$nameLogos[$index]] = $rutaDestino;
                }
            } else if (isset($logos)) {
                $json_logos[$nameLogos[$index]] = $nameLogo;
            } else {
                $json_logos[$nameLogos[$index]] = '';
            }
            $index++;
        }

        if ($json_logos['primary'] == '' && $json_logos['secondary'] == '') {
            return 'NULL';
        } else {
            return "'" . json_encode($json_logos) . "'";
        }

    }

    /*--- METODO BORRAR AREA ---*/
    public function borrar()
    {
        if (isset($_GET["proyectoid"])) {
            $id = (int)$_GET["proyectoid"];
            $proyecto = new Proyecto($this->adapter);

            $allproyectos = $proyecto->getProyectoById($id);
            $nombreProyecto = $allproyectos->nombre_Proyecto;

            $proyecto->deleteElementoById($id, 'Proyectos');

            $insercion = 4;
            $mensaje = 'Se elimino el Proyecto "' . $nombreProyecto . '"';
        }
        $this->redirect("Proyectos", "index&insercion=$insercion&newElemento=$mensaje");
    }

    public function getLogos()
    {
        if (isset($_POST['idProyecto'])) {
            $idProyecto = $_POST['idProyecto'];
            $proyecto = new Proyecto($this->adapter);
            $proyectoActual = $proyecto->getProyectoById($idProyecto);

            if (!is_null($proyectoActual->logos)) {
                $logos = json_decode($proyectoActual->logos);

                // Logo Primario
                if (file_exists($logos->primary)) {
                    $rutaPrimary = $logos->primary;
                    $sizePrimary = filesize($rutaPrimary);
                    $namePrimary = basename($rutaPrimary);
                }

                //Logo Secundario
                if (file_exists($logos->secondary)) {
                    $rutaSecondary = $logos->secondary;
                    $sizeSecondary = filesize($rutaSecondary);
                    $nameSecondary = basename($rutaSecondary);
                }

                $configPrimary = [
                    'key' => "{'ruta':'" . $rutaPrimary . "', 'proyecto':'" . $idProyecto . "', 'logo':'primary'}",
                    'caption' => 'Logotipo Uno',
                    'size' => $sizePrimary,
                    'downloadUrl' => $rutaPrimary, // the url to download the file
                    'url' => './index.php?controller=Proyectos&action=deleteLogos', // server api to delete the file based on key
                ];

                $configSecondary = [
                    'key' => "{'ruta':'" . $rutaSecondary . "', 'proyecto':'" . $idProyecto . "', 'logo':'secondary'}",
                    'caption' => 'Logotipo Dos',
                    'size' => $sizeSecondary,
                    'downloadUrl' => $rutaSecondary, // the url to download the file
                    'url' => './index.php?controller=Proyectos&action=deleteLogos', // server api to delete the file based on key
                ];
            }

            echo json_encode([
                'proyecto' => $proyectoActual,
                'fileInput' => [
                    'primary' => [
                        'initialPreview' => $rutaPrimary,
                        'initialPreviewConfig' => $configPrimary,
                        'initialPreviewAsData' => true
                    ],
                    'secondary' => [
                        'initialPreview' => $rutaSecondary,
                        'initialPreviewConfig' => $configSecondary,
                        'initialPreviewAsData' => true
                    ]
                ]
            ]);
        }
    }

    public function deleteLogos()
    {
        if ($_POST['key']) {
            $proyecto = new Proyecto($this->adapter);
            $json = str_replace("'", '"', $_POST['key']);

            $json = json_decode($json);
            $proyectoActual = $proyecto->getProyectoById($json->proyecto);

            $logosBD = json_decode($proyectoActual->logos);

            if ($json->logo == 'primary') {
                if ($logosBD->secondary == '') {
                    $logoFinal = 'NULL';
                } else {
                    $logoFinal = [
                        'primary' => '',
                        'secondary' => $logosBD->secondary
                    ];
                }
            } else {
                if ($logosBD->primary == '') {
                    $logoFinal = 'NULL';
                } else {
                    $logoFinal = [
                        'primary' => $logosBD->primary,
                        'secondary' => ''
                    ];
                }
            }
            if ($logoFinal != 'NULL') {
                $logoFinal = "'" . json_encode($logoFinal) . "'";
                unlink($json->ruta);
            } else {
                if (is_dir(dirname($json->ruta))) {
                    $this->delete_directory(dirname($json->ruta));
                }
            }

            $resultado = $proyecto->updateLogos($logoFinal, $json->proyecto);
            if ($resultado) {
                echo true;
            } else {
                echo false;
            }
        } else {
            echo false;
        }
    }

    public function is_dir_empty($dir)
    {
        if (!is_readable($dir)) return NULL;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function delete_directory($dir)
    {
        if (is_dir($dir)) {
            $dir_handle = opendir($dir);
            if ($dir_handle) {
                while ($file = readdir($dir_handle)) {
                    if ($file != "." && $file != "..") {
                        if (!is_dir($dir . "/" . $file)) {
                            unlink($dir . "/" . $file);
                        } else {
                            $this->delete_directory($dir . '/' . $file);
                        }
                    }
                }
                closedir($dir_handle);
            }
            rmdir($dir);
            return true;
        }
        return false;
    }
}
