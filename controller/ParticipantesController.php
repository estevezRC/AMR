<?php

class ParticipantesController extends ControladorBase
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

    // ********************************** METODO PARA OBTENER TODOS LOS PARTICIPANTES **********************************
    public function index()
    {
        $mensaje = '<i class="fas fa-user-friends"></i> Participantes';

        $participante = new Participante($this->adapter);
        $participantes = $participante->getAllParticipantes();

        // ****************** OBTENER EMPLEADOS QUE NO ESTEN REGISTRADOS **************************
        $allusers = $participante->getAllUser();

        $idsEmpleados = array();
        if (!empty($allusers)) {
            foreach ($allusers as $user) {
                $idsEmpleados[] = $user->id_Usuario;
            }
            $idsEmpleadosSTR = implode(',', $idsEmpleados);

            if (!empty($allUserRes)) {
                $idsEmpleadosRes = array();
                foreach ($allUserRes as $userres) {
                    $idsEmpleadosRes[] = $userres->id_Usuario;
                }
                $idsEmpleadosResSRT = implode(',', $idsEmpleadosRes);
                $idsCombinados = $idsEmpleadosSTR . ',' . $idsEmpleadosResSRT;
                $allUserSinRegistrar = $participante->getAllEmpleadosSinCuenta($idsCombinados);
            } else {
                $allUserSinRegistrar = $participante->getAllEmpleadosSinCuenta($idsEmpleadosSTR);
            }
        }

        $this->view("index", array(
            'mensaje' => $mensaje, 'participantes' => $participantes, 'allUserSinRegistrar' => $allUserSinRegistrar
        ));
    }


    // ********************************** METODO PARA GUARDAR A UN NUEVO PARTICIPANTE **********************************
    public function store()
    {
        // ****************** RECIBIR DATOS DESDE EL FORMULARIO ******************
        $nombre = $_REQUEST['nombre'];
        $apellidoPaterno = $_REQUEST['apellido_paterno'];
        $apellidoMaterno = $_REQUEST['apellido_materno'];
        $correo = $_REQUEST['correo_Usuario'];
        $puesto = $_REQUEST['puesto'];
        $empresa = $_REQUEST['empresa'];
        $id_empleado = (int)$_REQUEST['id_empleado'] ?? 0;

        // ***************************** DEFINIR ARRAY DE DATOS PARA VALIDAR *****************************
        $datos = array(
            ['nombreCampo' => 'Nombre', 'valorCampo' => $nombre, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
            ['nombreCampo' => 'Apellido Paterno', 'valorCampo' => $apellidoPaterno, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
            ['nombreCampo' => 'Apellido Materno', 'valorCampo' => $apellidoMaterno, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
            ['nombreCampo' => 'Correo', 'valorCampo' => $correo, 'obligatorio' => true, 'validacion' => '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/'],
            ['nombreCampo' => 'Puesto', 'valorCampo' => $puesto, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
            ['nombreCampo' => 'Empresa', 'valorCampo' => $empresa, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/']
        );

        // *********************** VALIDAR DATOS OBLIGATORIOS Y FORMATO DE LOS DATOS ***********************
        $validateDatos = $this->validate($datos);

        if ($validateDatos['status']) {
            $participante = new Participante($this->adapter);
            // ****************** PROCESAR LOS DATOS PARA GUARDAR EN LA TABLA USUARIOS ******************

            // OBTENER TODOS LOS REGISTROS DE USUARIOS
            $usuarios = $participante->getAllParticipantesActivos();

            $validate = true;
            foreach ($usuarios as $usu) {
                if ($usu->nombre == $nombre && $usu->apellido_paterno = $apellidoPaterno
                        && $usu->apellido_materno == $apellidoMaterno)
                    $validate = false;
            }

            //  ****************** VALIDAR QUE EL NOMBRE Y APELLIDOS NO EXISTAN ******************
            if ($validate) {
                $pwd = "Participante1";
                // ****************** SETTEAR VALORES DEL MODULO ******************
                $resultado = $participante->setCorreoUsuario($correo)
                    ->setPasswordUsuario($pwd)
                    ->setPuesto($puesto)
                    ->setEmpresa($empresa)
                    ->save($usuarios);

                // ****************** VALIDAR QUE EL REGISTRO SE HAYA INSERTADO CORRECTAMENTE ******************
                if ($resultado) {
                    // EN ESTA SECCION SE OBTIENE EL ULTIMO ID(ID_USUARIO) DE PARTICIPANTE INSERTADO
                    $id_ultimo = (int)$participante->getUltimoIdUsuario()[0]->id;

                    // ********************* INSERTAR EN LA TABLA DE EMPLEADOS_USUARIOS ************************
                    $empleado_usuario = new EmpleadoUsuario($this->adapter);
                    $empleado_usuario->setNombre($nombre);
                    $empleado_usuario->setApellidoPaterno($apellidoPaterno);
                    $empleado_usuario->setApellidoMaterno($apellidoMaterno);
                    $empleado_usuario->setIdUsuario($id_ultimo);
                    $empleado_usuario->setIdEmpleado($id_empleado);

                    if ($id_empleado)
                        $empleado_usuario->updateExisteEmpleadoUsuario();
                    else
                        $empleado_usuario->saveNewEmpleadoUsuario();

                    // *************************** GUARDAR EN LA BASE GENERAL *****************************
                    $userGral = new ConsultasGeneral();
                    $apellidos = "$apellidoPaterno $apellidoMaterno";
                    $userGral->InsertarDatosUser($id_ultimo, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombre, $apellidos, $correo, $pwd);

                    $data = array(
                        'mensaje' => "Se ha creado el participante $nombre $apellidoPaterno $apellidoMaterno",
                        'status' => true
                    );
                } else
                    $data = array(
                        'mensaje' => "El correo $correo ya existe. No se puede registrar",
                        'status' => false
                    );
            } else
                $data = array(
                    'mensaje' => "El participante $nombre $apellidoPaterno $apellidoMaterno ya existe. No se puede registrar",
                    'status' => false
                );
        } else
            $data = $validateDatos;

        echo json_encode($data);
    }


    // ********************************** METODO PARA OBTENER A UN NUEVO PARTICIPANTE **********************************
    public function show()
    {
        // ********************* RECIBIR ID PARA OBTENER LOS DATOS DEL PARTICIPANTE *********************
        $idParticipante = (int)$_REQUEST['id'] ?? 0;
        $datosParticipante = [];
        if ($idParticipante) {
            $participante = new Participante($this->adapter);
            // VALIDAR QUE EXISTA EL PARTICIPANTE
            $existe = $participante->getParticipanteExistById($idParticipante);
            if ($existe) {
                $datosParticipante = $participante->getParticipanteById($idParticipante)[0];
                $data = array(
                    'participante' => $datosParticipante,
                    'mensaje' => "El participante con id $idParticipante no existe",
                    'status' => true
                );
            } else
                $data = array(
                    'mensaje' => "El participante con id $idParticipante no existe",
                    'status' => false
                );
        } else
            $data = array(
                'mensaje' => 'Error, se debe enviar un identificador de participante valido',
                'status' => false
            );

        echo json_encode($data);
    }


    // ************************************ METODO PARA ACTUALIZAR A UN PARTICIPANTE ***********************************
    public function update()
    {
        // ****************** RECIBIR DATOS DESDE EL FORMULARIO ******************
        $idParticipante = (int)$_REQUEST['id'] ?? 0;
        $nombre = $_REQUEST['nombre'];
        $apellidoPaterno = $_REQUEST['apellido_paterno'];
        $apellidoMaterno = $_REQUEST['apellido_materno'];
        $correo = $_REQUEST['correo_Usuario'];
        $puesto = $_REQUEST['puesto'];
        $empresa = $_REQUEST['empresa'];

        // VERIFICAR QUE VENGA SETTEADO EL ID DEL PARTICIPANTE
        if (!$idParticipante)
            $data = array(
                'mensaje' => 'Error, se debe enviar un identificador de participante valido',
                'status' => false
            );
        else {
            // ***************************** DEFINIR ARRAY DE DATOS PARA VALIDAR *****************************
            $datos = array(
                ['nombreCampo' => 'Nombre', 'valorCampo' => $nombre, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
                ['nombreCampo' => 'Apellido Paterno', 'valorCampo' => $apellidoPaterno, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
                ['nombreCampo' => 'Apellido Materno', 'valorCampo' => $apellidoMaterno, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
                ['nombreCampo' => 'Correo', 'valorCampo' => $correo, 'obligatorio' => true, 'validacion' => '/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/'],
                ['nombreCampo' => 'Puesto', 'valorCampo' => $puesto, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/'],
                ['nombreCampo' => 'Empresa', 'valorCampo' => $empresa, 'obligatorio' => true, 'validacion' => '/^(?!-+)[a-zA-Z-ñáéíóú\s]*$/']
            );

            // *********************** VALIDAR DATOS OBLIGATORIOS Y FORMATO DE LOS DATOS ***********************
            $validateDatos = $this->validate($datos);

            // ****************** VALIDAR TIPOS DE DATOS PARA CADA CAMPO ******************
            if ($validateDatos['status']) {

                // ****************** PROCESAR LOS DATOS PARA GUARDAR EN LA TABLA USUARIOS ******************
                $participante = new Participante($this->adapter);

                // OBTENER TODOS LOS REGISTROS DE USUARIOS
                $usuarios = $participante->getAllParticipantesActivos();

                $validate = true;
                foreach ($usuarios as $usu) {
                    if ($usu->nombre == $nombre && $usu->apellido_paterno = $apellidoPaterno
                            && $usu->apellido_materno == $apellidoMaterno && $usu->id_usuario != $idParticipante)
                        $validate = false;
                }

                //  ****************** VALIDAR QUE EL NOMBRE Y APELLIDOS NO EXISTAN ******************
                if ($validate) {
                    // VALIDAR QUE EL ID EXISTA
                    $existe = $participante->getParticipanteExistById($idParticipante);
                    if ($existe) {
                        // **************************** SETTEAR VALORES DEL MODULO ************************

                        // ********** ACTUALIZAR TABLA DE EMPLEADOS-USUARIOS Y USUARIOS **********
                        $empleado_usuario = new EmpleadoUsuario($this->adapter);
                        $empleado_usuario->setIdUsuario($idParticipante);
                        $empleado_usuario->setNombre($nombre);
                        $empleado_usuario->setApellidoPaterno($apellidoPaterno);
                        $empleado_usuario->setApellidoMaterno($apellidoMaterno);
                        $empleado_usuario->modificarEmpleadoUsuario();

                        $resultado = $participante->setIdUsuario($idParticipante)
                            ->setCorreoUsuario($correo)
                            ->setPuesto($puesto)
                            ->setEmpresa($empresa)
                            ->update($usuarios);

                        // ****************** VALIDAR QUE EL REGISTRO SE HAYA INSERTADO CORRECTAMENTE ******************
                        if ($resultado) {
                            // ******************************** ACTUALIZAR EN LA BASE GENERAL **********************************************
                            $userGral = new ConsultasGeneral();
                            $apellidos = "$apellidoPaterno $apellidoMaterno";
                            $userGral->ActualizarDatosUser($idParticipante, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombre, $apellidos, $correo);

                            $data = array(
                                'mensaje' => "Se ha actualizado el participante $nombre $apellidoPaterno $apellidoMaterno",
                                'status' => true
                            );
                        } else
                            $data = array(
                                'mensaje' => "El correo $correo ya existe. No se puede actualizar",
                                'status' => false
                            );
                    } else
                        $data = array(
                            'mensaje' => "El identificador $idParticipante no existe",
                            'status' => false
                        );
                } else
                    $data = array(
                        'mensaje' => "El participante $nombre $apellidoPaterno $apellidoMaterno ya existe. No se puede actualizar",
                        'status' => false
                    );
            } else
                $data = $validateDatos;
        }

        echo json_encode($data);
    }


    // ************************************* METODO PARA ELIMINAR A UN PARTICIPANTE ************************************
    public function destroy()
    {
        // ********************* RECIBIR ID PARA OBTENER LOS DATOS DEL PARTICIPANTE *********************
        $idParticipante = (int)$_REQUEST['id'] ?? 0;
        $ruta = 'index.php?controller=Participantes&action=index';
        // **************** VALIDAR QUE EL ID SEA VALIDO ****************
        if ($idParticipante) {
            $participante = new Participante($this->adapter);
            $datosParticipante = $participante->getParticipanteById($idParticipante);
            // **************** VALIDAR QUE EXISTA EL ID ****************
            if ($datosParticipante) {
                $resultado = $participante->updateStatus($idParticipante, 0);
                $nombre = $datosParticipante[0]->nombre;
                $apellidos = "{$datosParticipante[0]->apellido_paterno} {$datosParticipante[0]->apellido_materno}";
                if ($resultado)
                    $data = array(
                        'ruta' => $ruta,
                        'mensaje' => "Se elimino el participante $nombre $apellidos",
                        'estado' => true
                    );
                else
                    $data = array(
                        'ruta' => $ruta,
                        'mensaje' => "Error al eliminar al participante $nombre $apellidos, vuelve a intentarlo",
                        'estado' => false
                    );
            } else
                $data = array(
                    'ruta' => $ruta,
                    'mensaje' => "El participante con id $idParticipante no existe",
                    'estado' => false
                );
        } else
            $data = array(
                'ruta' => $ruta,
                'mensaje' => 'Error, se debe enviar un identificador de participante valido',
                'estado' => false
            );

        echo json_encode($data);

    }
}

