<?php

class MatrizComunicacionController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        if (empty($_SESSION[ID_PROYECTO_SUPERVISOR])) {
            $this->id_Proyecto_constant = 'id_Proyecto';
        }
    }

    /* --------------------------------------------------VISTA PRINCIPAL* --------------------------------------------*/
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

        $id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];

        $matriz = new MatrizComunicacion($this->adapter);
        //MATRIZ
        $allmatriz = $matriz->getAllMatriz($id_Proyecto);
        //USUARIOS
        $alluser = $matriz->getAllUserCorreo($id_Proyecto);

        //OBTENER TODOS LOS REPORTES POR PROYECTO
        $allreportes = $matriz->getAllReportesByIdProyecto($id_Proyecto);

        /*
          $allreportes = $matriz->getAllCatReportesByTipoReporte($_SESSION[ID_PROYECTO_SUPERVISOR]);

             if ($allreportes != null) {
                 // OBTENER REPORTES DE SEGUIMIENTO
                 $id_Seguimiento = array();
                 foreach ($allreportes as $reporte) {
                     $id_Seguimiento[] = $reporte->id_Reporte_Seguimiento;
                 }
                 $id_SeguimientoStr = implode(",", $id_Seguimiento);
                 $seguimiento = new Reporte($this->adapter);
                 $datosSeguimiento = $seguimiento->getAllCatReportesByTipoReporteSeguimiento($id_SeguimientoStr);
                 $arrayDatos = array_merge($allreportes, $datosSeguimiento);
             }
        */

        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-volume-control-phone' aria-hidden='true'></i> Matriz de comunicación";
        }

        $this->view("index", array(
            "allmatriz" => $allmatriz, "alluser" => $alluser, "allreportes" => $allreportes, "mensaje" => $mensaje,
            "insercion" => $insercion, "newElemento" => $newElemento
        ));

    }


    public function getReportesByIdUsuario()
    {
        $id_Usuario = $_POST['id_Usuario'];

        $matriz = new MatrizComunicacion($this->adapter);
        $allReportes = $matriz->getAllReportesByIdUsuarioAndIdProyecto($id_Usuario, $this->id_Proyecto_constant);

        if ($allReportes != null) {
            $ids_Reportes = array();
            foreach ($allReportes as $reporte) {
                $ids_Reportes[] = $reporte->mat_Id_Reporte;
            }
            $ids_ReportesStr = implode(",", $ids_Reportes);
            $allReportesByIdUsuario = $matriz->getAllReportesByNotIdReporte($ids_ReportesStr, $this->id_Proyecto_constant);
        } else
            $allReportesByIdUsuario = $matriz->getAllReportesByNotIdReporte(0, $this->id_Proyecto_constant);

        echo json_encode($allReportesByIdUsuario);
    }


    /*------------------------------------------------ GUARDAR NUEVO ------------------------------------------------*/
    public function guardarnuevo()
    {
        $arrayDatos = $_POST["arrayDatos"];
        $arrayDatos = json_decode($arrayDatos);

        $matriz = new MatrizComunicacion($this->adapter);
        //MATRIZ
        $allmatriz = $matriz->getAllMatriz($this->id_Proyecto_constant);

        $id_User = $_POST["mat_Id_Usuario"];
        $contador = 0;

        foreach ($arrayDatos as $datos) {

            if ($datos[1] != 0 || $datos[2] != 0 || $datos[3] != 0) {

                $matriz->set_mat_Id_Usuario($id_User);
                $matriz->set_mat_Id_Reporte($datos[0]);
                $matriz->set_mat_Id_Proyecto($this->id_Proyecto_constant);
                $matriz->set_mat_Correo($datos[1]);
                $matriz->set_mat_Telegram($datos[2]);
                $matriz->set_mat_Whatsapp(0);
                $matriz->set_mat_Push($datos[3]);
                $save = $matriz->saveNewMatriz($allmatriz);
                $contador++;
            }
        }

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $allDatosUserById = $matriz->getAllMatrizByIdUser($id_User);
        $user = $allDatosUserById[0]->nombre_Usuario . ' ' . $allDatosUserById[0]->apellido_Usuario;

        if ($save == 1) {
            $insercion = 1;
            $mensaje = 'Se ha creado la configuración para el usuario: "' . $user . '" en ' . $contador . '  reporte(s)';
        }

        $this->redirect("MatrizComunicacion", "index&insercion=$insercion&newElemento=$mensaje");
    }


    /*--------------------------------------------------- MODIFICAR -------------------------------------------------*/
    public function modificar()
    {
        if (isset($_GET["mat_Id"])) {

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = $_GET['insercion'];
            $newElemento = $_GET['newElemento'];
            if ($insercion != 0 && !empty($newElemento)) {
                $insercion = 0;
                $newElemento = '';
            }

            $mensaje = "Matriz de comunicación";

            $matriz = new MatrizComunicacion($this->adapter);
            $allmatriz = $matriz->getAllMatriz($this->id_Proyecto_constant);

            //USUARIOS
            $alluser = $matriz->getAllUserCorreo($this->id_Proyecto_constant);

            //OBTENER TODOS LOS REPORTES POR PROYECTO
            $allreportes = $matriz->getAllReportesByIdProyecto($this->id_Proyecto_constant);

            $id = (int)$_GET["mat_Id"];
            $datosmatriz = $matriz->getAllMatrizById($id);
            $modificar = 1;
        }
        $this->view("index", array(
            "allmatriz" => $allmatriz, "alluser" => $alluser, "allreportes" => $allreportes, "modificar" => $modificar,
            "datosmatriz" => $datosmatriz, "insercion" => $insercion, "newElemento" => $newElemento, "mensaje" => $mensaje
        ));
    }


    public function obtenerInformacionMatrizComunicacionByUsuario()
    {
        $id_Usuario = $_POST['id_Usuario'];

        $matriz = new MatrizComunicacion($this->adapter);
        $alldatosMatriz = $matriz->getAllMatrizById_UsuarioAndId_Proyecto($id_Usuario, $this->id_Proyecto_constant);

        echo json_encode($alldatosMatriz);

    }


    /*--------------------------------------------- GUARDAR MODIFICACION --------------------------------------------*/
    public function guardarmodificacion()
    {

        $arrayDatos = $_POST["arrayDatos"];
        $arrayDatos = json_decode($arrayDatos);

        $matriz = new MatrizComunicacion($this->adapter);
        //MATRIZ
        $allmatriz = $matriz->getAllMatriz($this->id_Proyecto_constant);

        $id_User = $_POST["mat_Id_Usuario"];

        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
        $allDatosUserById = $matriz->getAllMatrizByIdUser($id_User);
        $user = $allDatosUserById[0]->nombre_Usuario . ' ' . $allDatosUserById[0]->apellido_Usuario;

        foreach ($arrayDatos as $datos) {
            $matriz->set_mat_Id($datos[0]);
            $matriz->set_mat_Id_Usuario($id_User);
            $matriz->set_mat_Id_Reporte($datos[1]);
            $matriz->set_mat_Id_Proyecto($this->id_Proyecto_constant);
            $matriz->set_mat_Correo($datos[2]);
            $matriz->set_mat_Telegram($datos[3]);
            $matriz->set_mat_Whatsapp(0);
            $matriz->set_mat_Push($datos[4]);
            $save = $matriz->saveModificaMatriz($allmatriz);
        }


        if ($save == 3) {
            $insercion = 3;
            $mensaje = 'Se ha Modificado la configuración para el usuario: "' . $user;
        }

        $this->redirect("MatrizComunicacion", "index&insercion=$insercion&newElemento=$mensaje");
    }

    /*----------------------------------------------------- BORRAR --------------------------------------------------*/
    public function borrar()
    {
        if (isset($_GET["mat_Id"])) {
            $id = (int)$_GET["mat_Id"];

            $matriz = new MatrizComunicacion($this->adapter);
            $allDatosMatrizById = $matriz->getAllMatrizById($id);
            $user = $allDatosMatrizById[0]->nombre_Usuario . ' ' . $allDatosMatrizById[0]->apellido_Usuario;
            $reporte = $allDatosMatrizById[0]->nombre_Reporte;

            //$matriz->deleteElementoById($id, 'Matriz_Comunicacion');
            $matriz->set_mat_Id($id);
            $matriz->changeStatus_C_T_P();

            $matriz->deleteElementoById($id, 'Matriz_Comunicacion');

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 4;
            $mensaje = 'Se elimino la configuración para el usuario: "' . $user . '" en el reporte: "' . $reporte . '""';
        }
        $this->redirect("MatrizComunicacion", "index&insercion=$insercion&newElemento=$mensaje");
    }
}
