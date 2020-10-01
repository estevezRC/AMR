<?php

class EmpresasController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];

        require_once 'ws/ConsultasGeneral.php';
    }

    /*--- VISTA DE TODOS LAS EMPRESAS ---*/
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
            $mensaje = "<i class='fa fa-building-o' aria-hidden='true'></i> Datos Empresa";
        }
        $empresa = new Empresa($this->adapter);
        $allempresas = $empresa->getAllEmpresas($this->id_Proyecto_constant);
        $this->view("index", array(
            "allempresas" => $allempresas, "mensaje" => $mensaje, "insercion" => $insercion, "newElemento" => $newElemento
        ));
    }

    /*--- VISTA MODIFICAR EMPRESA ---*/
    public function modificar()
    {
        if (isset($_GET["empresaid"])) {
            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = $_GET['insercion'];
            $newElemento = $_GET['newElemento'];
            if ($insercion != 0 && !empty($newElemento)) {
                $insercion = 0;
                $newElemento = '';
            }

            $empresa = new Empresa($this->adapter);
            $id = (int)$_GET["empresaid"];
            $datosempresa = $empresa->getEmpresaById($id);
            $allempresas = $empresa->getAllEmpresas($this->id_Proyecto_constant);
            $modificar = 1;
        }
        $mensaje = "<i class='fa fa-building-o' aria-hidden='true'></i> Datos Empresa";
        $this->view("index", array(
            "allempresas" => $allempresas, "datosempresa" => $datosempresa, "modificar" => $modificar,
            "mensaje" => $mensaje, "insercion" => $insercion, "newElemento" => $newElemento
        ));
    }

    /*--- METODO CREAR NUEVO EMPRESA---*/
    public function guardarnuevo()
    {

        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["nombre_Empresa"])
        ) {
            $save = "Llenar todos los campos";
        } //SE CREA NUEVA EMPRESA
        else {
            $empresa = new Empresa($this->adapter);
            $allempresas = $empresa->getAllEmpresas($this->id_Proyecto_constant);
            $empresa->setNombre($_POST["nombre_Empresa"]);

            //CARGAR IMAGEN IZQUIERDA
            $nombre_img = $_FILES['logo_Izquierda_Empresa']['name'];
            $tipo_img = $_FILES['logo_Izquierda_Empresa']['type'];
            $extension = explode(".", $nombre_img);
            $nombre_imagen = $_POST["nombre_Empresa"] . "izq." . $extension[1];
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
            $target_path = "img/empresas/";
            $target_path = $target_path . basename($nombre_imagen);
            if (move_uploaded_file($_FILES['logo_Izquierda_Empresa']['tmp_name'], $target_path)) {
                /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
            } else {
                /*echo "Erro al cargar imagen!";*/
            }
            $img = "img/empresas/" . $nombre_imagen;
            $empresa->setLogoIzquierdo($img);

            //CARGAR IMAGEN DERECHA
            $nombre_img2 = $_FILES['logo_Derecha_Empresa']['name'];
            $tipo_img2 = $_FILES['logo_Derecha_Empresa']['type'];
            $extension2 = explode(".", $nombre_img2);
            $nombre_imagen2 = $_POST["nombre_Empresa"] . "der." . $extension2[1];
            $nombre_imagen2 = str_replace(' ', '', $nombre_imagen2);
            $target_path2 = "img/empresas/";
            $target_path2 = $target_path2 . basename($nombre_imagen2);
            if (move_uploaded_file($_FILES['logo_Derecha_Empresa']['tmp_name'], $target_path2)) {
                /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
            } else {
                /*echo "Erro al cargar imagen!";*/
            }
            $img2 = "img/empresas/" . $nombre_imagen2;
            $empresa->setLogoDrecho($img2);

            $empresa->setTelefono($_POST["telefono_Empresa"]);
            $empresa->setCelular($_POST["celular_Empresa"]);
            $fecha_hora = $this->fecha();
            $empresa->setFecha($fecha_hora);
            $empresa->setCorreo($_POST["correo_Empresa"]);
            $empresa->setDirectorio($_POST["directorio_Empresa"]);
            $empresa->setRol($_POST["rol_Empresa"]);
            $empresa->setDescripcion($_POST["descripcion_Empresa"]);
            $save = $empresa->saveNewEmpresa($allempresas);
            $mensaje = "<i class='fa fa-building-o' aria-hidden='true'></i> " . $save . "";
        }
        $this->redirect("Empresas", "index&mensaje=$mensaje");
    }

    /*--- METODO GUARDAR MODIFICACION EMPRESA ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["nombre_Empresa"])
        ) {
            $save = "Llenar todos los campos";
        } //SE GUARDA MODIFICACION
        else {
            $empresa = new Empresa($this->adapter);
            $allempresas = $empresa->getAllEmpresas($this->id_Proyecto_constant);

            $id = $_POST["empresaid"];
            $nombre_Empresa = $_POST["nombre_Empresa"];
            $empresa->setNombre($nombre_Empresa);

            //CARGAR IMAGEN IZQUIERDA
            if ($_FILES['logo_Izquierda_Empresa']['tmp_name'] == "") {
                $img = $_POST["logo_Izquierda_Empresa2"];
                //$img = "el anterior";
            }
            if ($_FILES['logo_Izquierda_Empresa']['tmp_name'] != "") {
                $nombre_img = $_FILES['logo_Izquierda_Empresa']['name'];
                $tipo_img = $_FILES['logo_Izquierda_Empresa']['type'];
                $extension = explode(".", $nombre_img);
                $nombre_imagen = $_POST["nombre_Empresa"] . "izq." . $extension[1];
                $nombre_imagen = str_replace(' ', '', $nombre_imagen);
                $target_path = "img/empresas/";
                $target_path = $target_path . basename($nombre_imagen);
                if (move_uploaded_file($_FILES['logo_Izquierda_Empresa']['tmp_name'], $target_path)) {
                    /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
                } else {
                    /*echo "Erro al cargar imagen!";*/
                }
                $img = "img/empresas/" . $nombre_imagen;
            }
            $empresa->setLogoIzquierdo($img);
            //CARGAR IMAGEN DERECHA
            if ($_FILES['logo_Derecha_Empresa']['tmp_name'] == "") {
                $img2 = $_POST["logo_Derecha_Empresa2"];
                //$img = "el anterior";
            }
            if ($_FILES['logo_Derecha_Empresa']['tmp_name'] != "") {
                $nombre_img2 = $_FILES['logo_Derecha_Empresa']['name'];
                $tipo_img2 = $_FILES['logo_Derecha_Empresa']['type'];
                $extension2 = explode(".", $nombre_img2);
                $nombre_imagen2 = $_POST["nombre_Empresa"] . "der." . $extension2[1];
                $nombre_imagen2 = str_replace(' ', '', $nombre_imagen2);
                $target_path2 = "img/empresas/";
                $target_path2 = $target_path2 . basename($nombre_imagen2);
                if (move_uploaded_file($_FILES['logo_Derecha_Empresa']['tmp_name'], $target_path2)) {
                    /*echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";*/
                } else {
                    /*echo "Erro al cargar imagen!";*/
                }
                $img2 = "img/empresas/" . $nombre_imagen2;
            }

            $empresa->setLogoDrecho($img2);

            $telefono = $_POST["telefono_Empresa"];
            $empresa->setTelefono($telefono);

            $celular = $_POST["celular_Empresa"];
            $empresa->setCelular($celular);

            $fecha_hora = $this->fecha();
            $empresa->setFecha($fecha_hora);

            $correo = $_POST["correo_Empresa"];
            $empresa->setCorreo($correo);

            $empresa->setDirectorio($_POST["directorio_Empresa"]);

            $rol = $_POST["rol_Empresa"];
            $empresa->setRol($rol);

            $descripcion = $_POST["descripcion_Empresa"];
            $empresa->setDescripcion($descripcion);
            $save = $empresa->modificarEmpresa($id, $allempresas);

            //ACTUALIZAR DATOS EMPRESA BASE DE DATOS GENERAL
            $userGral = new ConsultasGeneral();
            $userGral->ActualizarDatosEmpresa($_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombre_Empresa, $telefono, $celular, $correo, $rol, $descripcion);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 3) {
                $insercion = 3;
                $mensaje = 'Se ha modificado la empresa: "' . $nombre_Empresa . '"';
            } else {
                $insercion = 2;
                $mensaje = 'La empresa "' . $nombre_Empresa . '" ya existe';
            }
        }
        $this->redirect("Empresas", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*--- METODO BORRAR EMPRESA ---*/
    public function borrar()
    {
        if (isset($_GET["empresaid"])) {
            $id = (int)$_GET["empresaid"];
            $area = new Area($this->adapter);
            $area->deleteElementoById($id, 'Empresas');
            $save = "Se elminó la empresa con id " . $id . "";
            $mensaje = "<i class='fa fa-building-o' aria-hidden='true'></i> " . $save . "";
        }
        $this->redirect("Empresas", "index&mensaje=$mensaje");
    }
}

?>
