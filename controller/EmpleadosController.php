<?php

class EmpleadosController extends ControladorBase
{
    private $conectar;
    private $adapter;
    private $id_Proyecto_constant;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];

        require_once 'core/FuncionesCompartidas.php';
        require_once AUTOLOAD;
        require_once 'ws/ConsultasGeneral.php';
    }


    /* ----------------------------------- VISTA DE TODOS LOS EMPLEADOS ------------------------------- */
    public function index()
    {
        $mensaje = "<i class='fas fa-users-cog'></i> Empleados";

        // OBTENER TODOS LOS EMPLEADOS
        $empleado = new Empleados($this->adapter);
        $allEmpleados = $empleado->getAllEmpleados();

        // SECCION DE RESTAURAR EMPLEADOS
        $mensajeRes = "<i class='fas fa-retweet'></i> Restaurar Empleados";
        $allEmpleadosRes = $empleado->getAllEmpleadosRestaurar();

        $this->view("index", [
            'titulo' => $mensaje, "allEmpleados" => $allEmpleados, "mensajeRes" => $mensajeRes,
            "allEmpleadosRes" => $allEmpleadosRes
        ]);
    }


    /* ----------------------------------- VISTA DE REGISTRAR NUEVO EMPLEADO -------------------------- */
    public function registro()
    {
        $mensaje = "<i class='fas fa-user-plus'></i> Nuevo Empleado";

        // OBTENER TODOS LOS PROYECTOS
        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];

        $proyecto = new Proyecto($this->adapter);

        if ($usuario == 1)
            $allProyectos = $proyecto->getAllProyecto();
        else
            $allProyectos = $proyecto->getAllProyectosLibres(7);

        $allUsers = $proyecto->getAllUsersNotEmployees();
        $this->view("index", [
            'titulo' => $mensaje, "proyectos" => $allProyectos, "usersNotEmployees" => $allUsers
        ]);
    }


    // ***************** FUNCION PARA OBTENER Y PASAR DATOS A LOS DIVERSOS MODELOS DE EMPLEADOS ************************
    public function guardarNuevoEmpleado()
    {
        // ************************************* INVOCAR MODELO DE EMPLEADOS *******************************************
        $idProyecto = $_REQUEST['proyecto'];
        $puesto = $_REQUEST['puesto'];
        $noEmpleado = $_REQUEST['no_empleado'];
        $nombre = $_REQUEST['nombre'];
        $apellidoPaterno = $_REQUEST['apellido_paterno'];
        $apellidoMaterno = $_REQUEST['apellido_materno'];
        $edad = $_REQUEST['edad'];
        $genero = $_REQUEST['genero'];

        $idUsuario = (int)$_REQUEST['id_usuario'] ?? 0;

        // VALIDACION DE DATOS OBLIGATORIOS
        if (empty($idProyecto) || empty($puesto) || empty($noEmpleado) || empty($nombre) || empty($apellidoPaterno) ||
            empty($apellidoMaterno) || empty($edad)) {
            $insercion = 2;
            $mensaje = "Ingresar todos los campos obligatorios";
        } else {
            $empleado = new Empleados($this->adapter);
            $allEmpleados = $empleado->getAllEmpleados();

            $validate = true;
            foreach ($allEmpleados as $emple) {
                if (($emple->nombre == $nombre and $emple->apellido_paterno == $apellidoPaterno
                        and $emple->apellido_materno == $apellidoMaterno) || $emple->no_empleado == $noEmpleado) {
                    $validate = false;
                }
            }

            if ($validate) {
                $empleado->setIdProyecto($idProyecto);
                $empleado->setPuesto($puesto);
                $empleado->setNoEmpleado($noEmpleado);
                $empleado->set_Edad($edad);
                $empleado->set_Genero($genero);
                $saveEmpleado = $empleado->saveNewEmpleado();
                // ********************************** INVOCAR MODELO DE EMPLEADOS DATOS ************************************
                if ($saveEmpleado) {
                    $calle = $_REQUEST['calle'];
                    $numero = $_REQUEST['numero'];
                    $colonia = $_REQUEST['colonia'];
                    $municipio = $_REQUEST['municipio'];
                    $estado = $_REQUEST['estado'];
                    $cp = $_REQUEST['cp'];
                    $pais = $_REQUEST['pais'];
                    $grado_estudios = $_REQUEST['nivel_estudios'];
                    $nombre_estudio = $_REQUEST['nombre_estudio'];
                    $rfc = $_REQUEST['rfc'];
                    $curp = $_REQUEST['curp'];
                    $nss = $_REQUEST['nss'];
                    $telefono = $_REQUEST['telefono'];
                    $seguro_gastos_mayores = $_REQUEST['gastos_mayores'];
                    $tipo_sangre = $_REQUEST['tipo_sangre'];
                    $info_adicional = $_REQUEST['informacion_adicional'];

                    $calle = $calle ? $calle : "";
                    $numero = $numero ? $numero : 'null';
                    $colonia = $colonia ? $colonia : "";
                    $municipio = $municipio ? $municipio : '';
                    $estado = $estado ? $estado : '';
                    $cp = $cp ? $cp : 'null';
                    $pais = $pais ? $pais : '';
                    $grado_estudios = $grado_estudios ? $grado_estudios : "";
                    $nombre_estudio = $nombre_estudio ? $nombre_estudio : "";
                    $rfc = $rfc ? $rfc : "";
                    $curp = $curp ? $curp : "";
                    $nss = $nss ? $nss : "";
                    $telefono = $telefono ? $telefono : "";
                    $seguro_gastos_mayores = $seguro_gastos_mayores ? $seguro_gastos_mayores : "";
                    $tipo_sangre = $tipo_sangre ? $tipo_sangre : "";
                    $info_adicional = $info_adicional ? $info_adicional : "";

                    // OBTENER EL REGISTRO ULTIMO REGISTRO INSERTADO
                    $empleadoDatos = new EmpleadosDatos($this->adapter);
                    $id_empleado = $empleadoDatos->getUltimoRegistroEmpleado();

                    $empleadoDatos->setCalle($calle);
                    $empleadoDatos->setNumero($numero);
                    $empleadoDatos->setColonia($colonia);
                    $empleadoDatos->setMunicipio($municipio);
                    $empleadoDatos->setEstado($estado);
                    $empleadoDatos->setCp($cp);
                    $empleadoDatos->setPais($pais);
                    $empleadoDatos->setGradoEstudios($grado_estudios);
                    $empleadoDatos->setNombreEstudio($nombre_estudio);
                    $empleadoDatos->setRfc($rfc);
                    $empleadoDatos->setCurp($curp);
                    $empleadoDatos->setNss($nss);
                    $empleadoDatos->setTelefono($telefono);
                    $empleadoDatos->setSeguroGastosMayores($seguro_gastos_mayores);
                    $empleadoDatos->setTipoSangre($tipo_sangre);
                    $empleadoDatos->setInfoAdicional($info_adicional);
                    $empleadoDatos->setIdEmpleado($id_empleado);
                    $empleadoDatos->saveNewEmpleadoDatos();


                    // Instanciar Modelo de Empleados Usuarios
                    $empleadosUsuarios = new EmpleadoUsuario($this->adapter);
                    $empleadosUsuarios->setNombre($nombre);
                    $empleadosUsuarios->setApellidoPaterno($apellidoPaterno);
                    $empleadosUsuarios->setApellidoMaterno($apellidoMaterno);
                    $empleadosUsuarios->setIdEmpleado($id_empleado);
                    $empleadosUsuarios->setIdUsuario($idUsuario);

                    if ($idUsuario) {
                        $empleadosUsuarios->updateExisteUsuarioEmpleado();
                    } else {
                        $empleadosUsuarios->saveNewEmpleadoUsuario();
                    }

                    // ************************************ INVOCAR MODELO DE SALARIOS *************************************
                    $salario = $_REQUEST['salario'];
                    $salario = $salario ? $salario : 'null';

                    $salarios = new EmpleadosSalarios($this->adapter);
                    $salarios->setSalario($salario);
                    $salarios->setIdEmp($id_empleado);
                    $salarios->saveSalarioEmpleado();

                    // *************************** VALIDAR SI VIENEN ARCHIVOS DE EXPEDINETE DE EMPLEADO ********************
                    $x = 0;
                    foreach ($_FILES['files']['tmp_name'] as $file) {
                        if ($_FILES['files']['tmp_name'][$x]) {
                            $tipo_archivo = json_decode($_REQUEST['tipo_archivo']);

                            $newNombre = $this->convertirNombreExpedientes($tipo_archivo[$x]);
                            $newNombre = str_replace(' ', '_', $newNombre);


                            $nombre_archivo = $_FILES['files']['name'][$x];
                            $extension = new SplFileInfo($nombre_archivo);
                            $nombre_archivo = $noEmpleado . "_$newNombre" . '_' . date('d-m-Y') . '.' . $extension->getExtension();

                            $ruta = 'img/expedientes_empleados/' . $id_empleado . '/';

                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0777, true);
                            }

                            $ruta = $ruta . basename($nombre_archivo);
                            if (move_uploaded_file($_FILES['files']['tmp_name'][$x], $ruta)) {
                                // ****************************** INVOCAR MODELO DE EXPEDIENTES ****************************
                                $expediente = new EmpleadosExpedientes($this->adapter);
                                $expediente->setTipoArchivo($tipo_archivo[$x]);
                                $expediente->setNombreArchivo($nombre_archivo);
                                $expediente->setIdEmp($id_empleado);
                                $expediente->saveExpedienteEmpleado();
                            }
                        }
                        $x++;
                    }

                    $insercion = 1;
                    $mensaje = "Se ha creado el empleado $nombre $apellidoPaterno $apellidoMaterno";
                } else {
                    $insercion = 2;
                    $mensaje = "Error al guardar el empleado $nombre $apellidoPaterno $apellidoMaterno";
                }
            } else {
                $insercion = 2;
                $mensaje = "El empleado $nombre $apellidoPaterno $apellidoMaterno o el número de empleado $noEmpleado ya existe";
            }
        }

        echo json_encode([$insercion, $mensaje, $idUsuario]);
    }


    // ********************************* FUNCION PARA MOSTRAR LOS DATOS A MODIFICAR ************************************
    public function modificar()
    {
        $mensaje = "<i class='fa fa-pencil-square-o'></i> Modificar Empleado";

        $this->view("index", [
            'titulo' => $mensaje
        ]);
    }


    // ********************************* FUNCION PARA OBTENER LOS DATOS A MODIFICAR ************************************
    public function datosEmpleadoByIdEmpleado()
    {
        $idEmpleado = $_REQUEST['id_empleado'];

        $empleado = new Empleados($this->adapter);
        $getDatosEmpleado = $empleado->getAllEmpleadosByIdEmp($idEmpleado);

        $allExpedientes = $empleado->getAllExpedientesByIdEmp($idEmpleado);

        // OBTENER TODOS LOS PROYECTOS
        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
        if ($usuario == 1)
            $allProyectos = $empleado->getAllProyecto();
        else
            $allProyectos = $empleado->getAllProyectosLibres(7);

        echo json_encode([
            "datosGenerales" => $getDatosEmpleado, "expedientes" => $allExpedientes, "proyectos" => $allProyectos
        ]);
    }


    // **************************** FUNCION PARA OBTENER LOS DATOS NUEVOS DATOS A GUARDAR ******************************
    public function guardarModificacion()
    {
        // ************************************** INVOCAR MODELO DE EMPLEADOS ******************************************
        $idEmpleado = $_REQUEST['id_empleado'];

        $idProyecto = $_REQUEST['proyecto'];
        $puesto = $_REQUEST['puesto'];
        $noEmpleado = $_REQUEST['no_empleado'];
        $nombre = $_REQUEST['nombre'];
        $apellidoPaterno = $_REQUEST['apellido_paterno'];
        $apellidoMaterno = $_REQUEST['apellido_materno'];
        $edad = $_REQUEST['edad'];
        $genero = $_REQUEST['genero'];

        // VALIDACION DE DATOS OBLIGATORIOS
        if (empty($idEmpleado) || empty($idProyecto) || empty($puesto) || empty($noEmpleado) || empty($nombre) ||
            empty($apellidoPaterno) || empty($apellidoMaterno) || empty($edad)) {
            $insercion = 2;
            $mensaje = "Ingresar todos los campos obligatorios";

            echo json_encode([$insercion, $mensaje]);
        } else {
            $empleado = new Empleados($this->adapter);
            $allEmpleados = $empleado->getAllEmpleados();

            $validate = true;
            foreach ($allEmpleados as $emple) {
                if (($emple->nombre == $nombre and $emple->apellido_paterno == $apellidoPaterno
                        and $emple->apellido_materno == $apellidoMaterno and $emple->id_empleado != $idEmpleado) ||
                    ($emple->no_empleado == $noEmpleado and $emple->id_empleado != $idEmpleado)) {
                    $validate = false;
                }
            }

            if ($validate) {
                $empleado->set_Id_Empleado($idEmpleado);
                $empleado->setIdProyecto($idProyecto);
                $empleado->setPuesto($puesto);
                $empleado->setNoEmpleado($noEmpleado);
                $empleado->set_Edad($edad);
                $empleado->set_Genero($genero);
                $saveEmpleado = $empleado->modificarEmpleado();

                // Instanciar Empleados Usuarios
                $empleadosUsuarios = new EmpleadoUsuario($this->adapter);
                $empleadosUsuarios->setIdEmpleado($idEmpleado);
                $empleadosUsuarios->setNombre($nombre);
                $empleadosUsuarios->setApellidoPaterno($apellidoPaterno);
                $empleadosUsuarios->setApellidoMaterno($apellidoMaterno);
                $empleadosUsuarios->modificarEmpleadoUsuario2();

                // **************************** ACTUALIZAR DATOS EN BD GENERAL (NOMBRE Y APELLIDOS) ************************
                if ($saveEmpleado) {
                    $userGral = new ConsultasGeneral();
                    // ******* CONSULTAR INFORMACION DEL EMPLEADO PARA VERIFICAR SI EXISTE COMO USUARIO DE PLATAFORA *******
                    $datosUsuario = $empleado->getExistEmpleadoToUser($idEmpleado);
                    if ($datosUsuario) {
                        $id = $datosUsuario[0]->id_usuario;
                        $apellidoUSer = $apellidoMaterno ? "{$apellidoPaterno} {$apellidoMaterno}" : $apellidoPaterno;
                        $correoUser = $datosUsuario[0]->correo_Usuario;
                        $userGral->ActualizarDatosUser($id, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombre, $apellidoUSer, $correoUser);
                    }
                }

                // ************************************ INVOCAR MODELO DE EMPLEADOS DATOS **********************************
                $calle = $_REQUEST['calle'];
                $numero = $_REQUEST['numero'];
                $colonia = $_REQUEST['colonia'];
                $municipio = $_REQUEST['municipio'];
                $estado = $_REQUEST['estado'];
                $cp = $_REQUEST['cp'];
                $pais = $_REQUEST['pais'];
                $grado_estudios = $_REQUEST['nivel_estudios'];
                $nombre_estudio = $_REQUEST['nombre_estudio'];
                $rfc = $_REQUEST['rfc'];
                $curp = $_REQUEST['curp'];
                $nss = $_REQUEST['nss'];
                $telefono = $_REQUEST['telefono'];
                $seguro_gastos_mayores = $_REQUEST['gastos_mayores'];
                $tipo_sangre = $_REQUEST['tipo_sangre'];
                $info_adicional = $_REQUEST['informacion_adicional'];

                $calle = $calle ? $calle : "";
                $numero = $numero ? $numero : 'null';
                $colonia = $colonia ? $colonia : "";
                $municipio = $municipio ? $municipio : '';
                $estado = $estado ? $estado : '';
                $cp = $cp ? $cp : 'null';
                $pais = $pais ? $pais : '';
                $grado_estudios = $grado_estudios ? $grado_estudios : "";
                $nombre_estudio = $nombre_estudio ? $nombre_estudio : "";
                $rfc = $rfc ? $rfc : "";
                $curp = $curp ? $curp : "";
                $nss = $nss ? $nss : "";
                $telefono = $telefono ? $telefono : "";
                $seguro_gastos_mayores = $seguro_gastos_mayores ? $seguro_gastos_mayores : "";
                $tipo_sangre = $tipo_sangre ? $tipo_sangre : "";
                $info_adicional = $info_adicional ? $info_adicional : "";

                $empleadoDatos = new EmpleadosDatos($this->adapter);
                $empleadoDatos->setCalle($calle);
                $empleadoDatos->setNumero($numero);
                $empleadoDatos->setColonia($colonia);
                $empleadoDatos->setMunicipio($municipio);
                $empleadoDatos->setEstado($estado);
                $empleadoDatos->setCp($cp);
                $empleadoDatos->setPais($pais);
                $empleadoDatos->setGradoEstudios($grado_estudios);
                $empleadoDatos->setNombreEstudio($nombre_estudio);
                $empleadoDatos->setRfc($rfc);
                $empleadoDatos->setCurp($curp);
                $empleadoDatos->setNss($nss);
                $empleadoDatos->setTelefono($telefono);
                $empleadoDatos->setSeguroGastosMayores($seguro_gastos_mayores);
                $empleadoDatos->setTipoSangre($tipo_sangre);
                $empleadoDatos->setInfoAdicional($info_adicional);
                $empleadoDatos->setIdEmpleado($idEmpleado);
                $empleadoDatos->modificarEmpleado();


                // ********************************** INVOCAR MODELO DE SALARIOS *******************************************
                $salario = $_REQUEST['salario'];
                $salario = $salario ? $salario : 'null';
                $salarios = new EmpleadosSalarios($this->adapter);
                $salarios->setSalario($salario);
                $salarios->setIdEmp($idEmpleado);
                $salarios->modificarSalarioEmpleado();


                // *************** SECCION DE EXPEDIENTES (VALLIDAR ARCHIVOS E INVOCAR MODELO DE EXPEDIENTES) **************
                $idsArchivos = json_decode($_REQUEST['ids_archivos']);
                $tipo_archivo = json_decode($_REQUEST['tipo_archivo']);
                $nombresArchivo = json_decode($_REQUEST['nombres_archivos']);

                // OBTENER TODOS LOS REGISTROS DE EXPDENIENTES DEL EMPLEADO
                $allExpedientes = $empleado->getAllExpedientesByIdEmp($idEmpleado);

                // ****** DEVOLVER ID QUE NO VENGA DESDE EL FORMULARIO VS TODOS LOS REGISTROS DE EXPEDIENTES *******
                $ids = array_diff(array_map(function ($expediente) {
                    return $expediente->id;
                }, $allExpedientes), $idsArchivos);

                foreach ($ids as $id) {
                    $this->borrarArchivoExpediente($id);
                }

                foreach ($tipo_archivo as $x => $archivo) {
                    $newNombre = $this->convertirNombreExpedientes($archivo);
                    $newNombre = str_replace(' ', '_', $newNombre);

                    if (isset($idsArchivos[$x])) {
                        // ACTUALIZAR ARCHIVOS GUARDADOS
                        $expedienteMod = new EmpleadosExpedientes($this->adapter);
                        if ($_FILES['files']['tmp_name'][$x]) {
                            $nombre_archivo = $_FILES['files']['name'][$x];
                            // OBTENER NOMBRE DE ARCHIVO DEL REGISTRO
                            $nombreArchivoRegistro = $expedienteMod->getRegistroExpedienteById($idsArchivos[$x])[0]->nombre_archivo;
                            $ruta = 'img/expedientes_empleados/' . $idEmpleado . '/';
                            if ($nombre_archivo != $nombreArchivoRegistro) {
                                $rutaArchivo = $ruta . $nombreArchivoRegistro;
                                $this->borrarArchivo($rutaArchivo);

                                $extension = new SplFileInfo($nombre_archivo);
                                $nombre_archivo = $noEmpleado . "_$newNombre" . '_' . date('d-m-Y') . '.' . $extension->getExtension();

                            }
                            $ruta = $ruta . basename($nombre_archivo);
                            move_uploaded_file($_FILES['files']['tmp_name'][$x], $ruta);
                        } else {
                            $nombre_archivo = $nombresArchivo[$x];
                        }
                        // *************************** INVOCAR MODELO DE EXPEDIENTES *******************************
                        $expedienteMod->setId($idsArchivos[$x]);
                        $expedienteMod->setTipoArchivo(str_replace(' ', '', $archivo));
                        $expedienteMod->setNombreArchivo($nombre_archivo);
                        $expedienteMod->modificarExpedienteEmpleado();
                    } else {
                        if ($_FILES['files']['tmp_name'][$x]) {
                            // INSERTAR NUEVOS ARCHIVOS
                            $nombre_archivo = $_FILES['files']['name'][$x];
                            $extension = new SplFileInfo($nombre_archivo);
                            $nombre_archivo = $noEmpleado . "_$newNombre" . '_' . date('d-m-Y') . '.' . $extension->getExtension();

                            $ruta = 'img/expedientes_empleados/' . $idEmpleado . '/';

                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0777, true);
                            }

                            $ruta = $ruta . basename($nombre_archivo);
                            if (move_uploaded_file($_FILES['files']['tmp_name'][$x], $ruta)) {
                                // ***************************** INVOCAR MODELO DE EXPEDIENTES *****************************
                                $expediente = new EmpleadosExpedientes($this->adapter);
                                $expediente->setTipoArchivo($archivo);
                                $expediente->setNombreArchivo($nombre_archivo);
                                $expediente->setIdEmp($idEmpleado);
                                $expediente->saveExpedienteEmpleado();
                            }
                        }
                    }
                }

                if ($saveEmpleado) {
                    $insercion = 1;

                    $mensaje = "Se ha modificado el empleado $nombre $apellidoPaterno $apellidoMaterno";
                } else {
                    $insercion = 2;
                    $mensaje = "Error al Actualizar el empleado $nombre $apellidoPaterno $apellidoMaterno";
                }
            } else {
                $insercion = 2;
                $mensaje = "El empleado $nombre $apellidoPaterno $apellidoMaterno o el número de empleado $noEmpleado ya existe";
            }

        }
        echo json_encode([$insercion, $mensaje]);
    }


    // ******************************* FUNCION PARA BORRAR EMPLEADOS (CAMBIAR STATUS) **********************************
    public function borrar()
    {
        $idEmpleado = $_REQUEST['id_empleado'];

        if (isset($idEmpleado)) {
            $empleado = new Empleados($this->adapter);
            // CONSULTAR DATOS DE EMPLEADO A TRAVES DEL id_empleado
            $datosEmpleado = $empleado->getAllEmpleadosByIdEmp($idEmpleado);
            $nombre = $datosEmpleado[0]->nombre;
            $apellidoP = $datosEmpleado[0]->apellido_paterno;
            $apellidoM = $datosEmpleado[0]->apellido_materno;

            if (empty($apellidoM))
                $nombreCompletoEmpleado = "$nombre $apellidoP";
            else
                $nombreCompletoEmpleado = "$nombre $apellidoP $apellidoM";

            $empleado->set_Id_Empleado($idEmpleado);
            $result = $empleado->modificarEstatus(0);
            if ($result) {
                // BUSCAR AL EMPLEADO COMO USUARIO
                $idUsuario = $empleado->getAllDatosEmpleadoUsuario($idEmpleado)[0]->id_usuario;

                // VERIFICAR SI EL EMPLEADO ES USUARIO
                if ($idUsuario) {
                    $empleado->deleteElementoById($idUsuario, "Usuarios");
                    // ***************************** ACTUALIZAR EN LA BASE GENERAL *********************************
                    $userGral = new ConsultasGeneral();
                    $userGral->ModificarStatusUser($idUsuario, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], 0);
                }

                $status = true;
                $mensaje = 'Se eliminó el empleado "' . $nombreCompletoEmpleado . '"';
            } else {
                $status = false;
                $mensaje = 'Ocurrio un error, no se elimino el empleado "' . $nombreCompletoEmpleado . '"';
            }
        } else {
            $status = true;
            $mensaje = 'Ocurrio un error, vuelve a intentarlo';
        }

        $ruta = 'index.php?controller=Empleados&action=index';

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status, 'user' => $idUsuario
        ]);

    }

    // ****************************** FUNCION PARA RESTAURAR EMPLEADOS (CAMBIAR STATUS) ********************************
    public function restaurar()
    {
        $idEmpleado = $_REQUEST['id_empleado'];

        if (isset($idEmpleado)) {
            $empleado = new Empleados($this->adapter);
            // CONSULTAR DATOS DE EMPLEADO A TRAVES DEL id_empleado
            $datosEmpleado = $empleado->getAllEmpleadosByInIdEmpleados($idEmpleado);
            $nombre = $datosEmpleado[0]->nombre;
            $apellidos = $datosEmpleado[0]->apellidos;

            $nombreCompletoEmpleado = "$nombre $apellidos";

            $empleado->set_Id_Empleado($idEmpleado);
            $result = $empleado->modificarEstatus(1);

            if ($result) {
                // BUSCAR AL EMPLEADO COMO USUARIO
                $idUsuario = $empleado->getAllDatosEmpleadoUsuario($idEmpleado)[0]->id_Usuario;

                // VERIFICAR SI EL EMPLEADO ES USUARIO
                if ($idUsuario) {
                    $usuario = new Usuario($this->adapter);
                    $usuario->restaurarUsuario($idUsuario, 1);
                    // ***************************** ACTUALIZAR EN LA BASE GENERAL *********************************
                    $userGral = new ConsultasGeneral();
                    $userGral->ModificarStatusUser($idUsuario, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], 1);
                }

                $status = true;
                $mensaje = 'Se restauro el empleado "' . $nombreCompletoEmpleado . '"';
            } else {
                $status = false;
                $mensaje = 'Ocurrio un error, no se restauro el empleado "' . $nombreCompletoEmpleado . '"';
            }
        } else {
            $status = false;
            $mensaje = 'Ocurrio un error, vuelve a intentarlo';
        }

        $ruta = 'index.php?controller=Empleados&action=index';

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status
        ]);
    }


    // ********************** FUNCION PARA BORRAR EMPLEADOS DEFINITIVAMENTE (CAMBIAR STATUS) ***************************
    public function borrarDefinitivamente()
    {
        $idEmpleado = $_REQUEST['id_empleado'];

        if (isset($idEmpleado)) {
            $empleado = new Empleados($this->adapter);
            // CONSULTAR DATOS DE EMPLEADO A TRAVES DEL id_empleado
            $datosEmpleado = $empleado->getAllEmpleadosByInIdEmpleados($idEmpleado);
            $nombre = $datosEmpleado[0]->nombre;
            $apellidos = $datosEmpleado[0]->apellidos;

            $nombreCompletoEmpleado = "$nombre $apellidos";

            $empleado->set_Id_Empleado($idEmpleado);
            $result = $empleado->modificarEstatus(2);

            if ($result) {
                $status = true;
                $mensaje = 'Se borro definitivamente el empleado "' . $nombreCompletoEmpleado . '"';
            } else {
                $status = false;
                $mensaje = 'Ocurrio un error, no se borro definitivamente el empleado "' . $nombreCompletoEmpleado . '"';
            }
        } else {
            $status = false;
            $mensaje = 'Ocurrio un error, vuelve a intentarlo';
        }

        $ruta = 'index.php?controller=Empleados&action=index';

        echo json_encode([
            'ruta' => $ruta, 'mensaje' => $mensaje, 'estado' => $status
        ]);
    }


    // *************************** FUNCION PARA MOSTRAR EL DETALLE DEL EMPLEADO ****************************************
    public function mostrarDetalleEmpleado()
    {
        $idEmpleado = $_REQUEST['id_empleado'];

        $mensaje = "<i class='fas fa-user'></i> Detalles del Empleado";

        $empleado = new Empleados($this->adapter);
        $getDatosEmpleado = $empleado->getAllEmpleadosByIdEmp($idEmpleado);

        $allExpedientes = $empleado->getAllExpedientesByIdEmp($idEmpleado);

        $this->view("index", [
            "titulo" => $mensaje, "datosGenerales" => $getDatosEmpleado, "expedientes" => $allExpedientes
        ]);

    }


    // ******************************* FUNCION AUXILIARES PARA EXPEDIENTE DE EMPLEADO **********************************
    public function borrarArchivoExpediente($idArchivo)
    {
        $expediente = new EmpleadosExpedientes($this->adapter);
        $expediente->setId($idArchivo);
        $expediente->borrarArchivoExpediente();
    }

    public function borrarArchivo($rutaArchivo)
    {
        unlink($rutaArchivo);
    }

    public function descargarExpediente()
    {
        $archivo = $_REQUEST['archivo'];
        $idEmpleado = $_REQUEST['id_empleado'];

        $fichero = 'img/expedientes_empleados/' . $idEmpleado . '/' . $archivo;

        if (file_exists($fichero)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fichero) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fichero));
            readfile($fichero);
            exit;
        }
    }


}
