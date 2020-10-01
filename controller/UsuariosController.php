<?php

class UsuariosController extends ControladorBase
{
    public $conectar;
    public $adapter;

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

    /*--- VISTA DE TODOS LOS USUARIOS ---*/
    public function index()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $savekey = $_GET['llave'];

        if (empty($mensaje)) {
            $mensaje = "<i class='fa fa-users' aria-hidden='true'></i> Usuarios";
        }

        // ********** OBTENER TODOS LOS USUARIOS ***************
        $usuario = new Usuario($this->adapter);
        $allusers = $usuario->getAllUser();

        // ********** OBTENER TODAS LAS AREAS **********
        $area = new Area($this->adapter);
        $allareas = $area->getAllArea();

        // ********** OBTENER TODOS LOS PROYECTOS **********
        $proyecto = new Proyecto($this->adapter);
        $allProyectos = $proyecto->getAllProyecto();

        // ********** OBTENER TODOS LOS PERFILES **********
        $perfil = new Perfil($this->adapter);
        if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
            $noId_Perfil_User = '';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        } else {
            $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
            $allPerfiles = $perfil->getAllPerfiles($noId_Perfil_User);
        }

        //echo 'key: '. $savekey;
        if ($savekey != 5 || $savekey == '' || empty($savekey) || $savekey == null)
            $savekey = 0;

        $notify = 0;
        $registrarNip = 0;


        //************************************** SECCION RESTAURAR USUARIOS ***********************
        $mensajeRes = "<i class='fa fa-retweet' aria-hidden='true'></i> Restaurar Usuarios";
        $allUserRes = $usuario->getAllUserRestaurar();


        // ****************** OBTENER EMPLEADOS QUE NO ESTEN REGISTRADOS **************************
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
                $allUserSinRegistrar = $usuario->getAllEmpleadosSinCuenta($idsCombinados);
            } else {
                $allUserSinRegistrar = $usuario->getAllEmpleadosSinCuenta($idsEmpleadosSTR);
            }
        }

        $this->view("index", array(
            "allusers" => $allusers, "allareas" => $allareas, "allProyectos" => $allProyectos, "allPerfiles" => $allPerfiles,
            "mensaje" => $mensaje, "savekey" => $savekey, "notify" => $notify, "registrarNip" => $registrarNip,
            "insercion" => $insercion, "newElemento" => $newElemento, "mensajeRes" => $mensajeRes, "allUserRes" => $allUserRes,
            "allUserSinRegistrar" => $allUserSinRegistrar
        ));
    }


    /*--- VISTA MODIFICAR USUARIO ---*/
    public function modificar()
    {
        if (isset($_GET["usuarioid"])) {

            $mensaje = "<i class='fa fa-users' aria-hidden='true'></i> Usuarios";

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = $_GET['insercion'];
            $newElemento = $_GET['newElemento'];
            if ($insercion != 0 && !empty($newElemento)) {
                $insercion = 0;
                $newElemento = '';
            }

            $id = (int)$_GET["usuarioid"];
            $area = new Area($this->adapter);
            $allarea = $area->getAllArea();

            $puestos = new Perfil($this->adapter);
            // PERFILES DE LA EMPRESA
            $perfil = new Perfil($this->adapter);
            if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) {
                $noId_Perfil_User = '';
                $allpuestos = $perfil->getAllPerfiles($noId_Perfil_User);
            } else {
                $noId_Perfil_User = ' where id_Perfil_Usuario NOT IN (1)';
                $allpuestos = $perfil->getAllPerfiles($noId_Perfil_User);
            }


            $usuario = new Usuario($this->adapter);
            $datosusuario = $usuario->getUserById2($id);
            $allusers = $usuario->getAllUser();
            $empresa = new Empresa($this->adapter);
            $allempresas = $empresa->getAllEmpresas();
            $modificar = 1;
            $savekey = 0;
            $notify = 0;
            $registrarNip = 0;

            //******************************************* SECCION RESTAURAR USUARIOS ***************************************
            $mensajeRes = "<i class='fa fa-retweet' aria-hidden='true'></i> Restaurar Usuarios";
            $allUserRes = $usuario->getAllUserRestaurar();
        }
        $this->view("index", array(
            "allusers" => $allusers, "datosusuario" => $datosusuario, "allareas" => $allarea, "allpuestos" => $allpuestos,
            "allempresas" => $allempresas, "modificar" => $modificar, "savekey" => $savekey, "notify" => $notify,
            "registrarNip" => $registrarNip, "insercion" => $insercion, "newElemento" => $newElemento, "mensaje" => $mensaje,
            "mensajeRes" => $mensajeRes, "allUserRes" => $allUserRes
        ));
    }

    /*--- METODO CREAR NUEVO USUARIO ---*/
    public function guardarnuevo()
    {

        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["usuarioemail"]) || empty($_POST["usuariopassword"]) || empty($_POST["usuarioarea"])) {
            $insercion = 4;
            $mensaje = "Llenar todos los campos";
        } else {
            $usuario = new Usuario($this->adapter);
            if (empty($_POST["name"]) || empty($_POST["surnameP"]) || empty($_POST["surnameM"])) {
                $id_emp = $_POST['id_emp'];
                if (empty($id_emp)) {
                    $insercion = 4;
                    $mensaje = "Llenar todos los campos";
                    $this->redirect("Usuarios", "index&insercion=$insercion&newElemento=$mensaje");
                } else {
                    $datosEmpleado = $usuario->getAllEmpleadosByIdEmp($id_emp);
                    $nombreUser = $datosEmpleado[0]->nombre;
                    $apellidoP = $datosEmpleado[0]->apellido_paterno;
                    $apellidoM = $datosEmpleado[0]->apellido_materno;
                    $apellidoUSer = "$apellidoP $apellidoM";
                }
            } else {
                $nombreUser = $_POST["name"];
                $apellidoP = $_POST["surnameP"];
                $apellidoM = $_POST["surnameM"];
                $apellidoUSer = "$apellidoP $apellidoM";
            }

            $correoUser = $_POST["usuarioemail"];
            $pwdUser = $_POST["usuariopassword"];


            $usuariotodos = new Usuario($this->adapter);
            $allusers = $usuariotodos->getAllUserActivosAndInactivos();

            $validate = true;
            foreach ($allusers as $usu) {
                if ($usu->nombre == $nombreUser && $usu->apellido_paterno = $apellidoP && $usu->apellido_materno == $apellidoM)
                    $validate = false;
            }

            if ($validate) {
                $usuario->setCorreo($correoUser);
                $usuario->setPassword($pwdUser);
                $fecha_hora = $this->fecha();
                $usuario->setFecha($fecha_hora);
                $usuario->setArea($_POST["usuarioarea"]);
                $usuario->set_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);
                $usuario->set_participante($_POST["participante"]);
                $save = $usuario->save($allusers);

                // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
                if ($save == 1) {
                    $insercion = 1;
                    $mensaje = 'Se ha creado el usuario: "' . $nombreUser . ' ' . $apellidoUSer . '"';

                    // ESTA SECCION OBTIENE EL ULTIMO ID DE USUARIO INSERTADO
                    $alluser = $usuariotodos->getAllUserOrderById();
                    $total_usuarios = count($alluser);
                    $id_ultimo = $total_usuarios + 1;


                    // ********************* INSERTAR EN LA TABLA DE EMPLEADOS_USUARIOS ************************
                    $empleado_usuario = new EmpleadoUsuario($this->adapter);
                    $empleado_usuario->setNombre($nombreUser);
                    $empleado_usuario->setApellidoPaterno($apellidoP);
                    $empleado_usuario->setApellidoMaterno($apellidoM);
                    $empleado_usuario->setIdUsuario($id_ultimo);

                    if ($id_emp) {
                        $empleado_usuario->setIdEmpleado($id_emp);
                        $empleado_usuario->updateExisteEmpleadoUsuario();
                    } else {
                        $empleado_usuario->setIdEmpleado(0);
                        $empleado_usuario->saveNewEmpleadoUsuario();
                    }


                    // ********************* INSERTAR EN LA TABLA DE USUARIOS-PROYECTOS ************************
                    $usuariosProyecto = new UsuarioProyecto($this->adapter);
                    $allUsuariosProyectos = $usuariosProyecto->getAllUsuarioProyecto();

                    $usuariosProyecto->set_id_Usuario($id_ultimo);
                    $usuariosProyecto->set_id_Proyecto($_POST["usuarioproyecto"]);
                    $usuariosProyecto->set_id_Perfil_Usuario($_POST["usuarioperfil"]);
                    $usuariosProyecto->saveNewUsuarioProyecto($allUsuariosProyectos);

                    // ENVIAR CORREO DE BIENVENIDA Y PARA QUE SE REGISTRE EN EL BOT DE TELEGRAM
                    $this->nuevoUsuario($id_ultimo, $correoUser, $pwdUser, $nombreUser, $apellidoUSer);

                    // ******************************** GUARDAR EN LA BASE GENERAL *****************************************
                    $userGral = new ConsultasGeneral();
                    $userGral->InsertarDatosUser($id_ultimo, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombreUser, $apellidoUSer, $correoUser, $pwdUser);

                } else {
                    $insercion = 2;
                    $mensaje = 'El correo "' . $correoUser . '" ya existe. No se puede registrar';
                }
            } else {
                $insercion = 2;
                $mensaje = "El usuario $nombreUser $apellidoP $apellidoM ya existe. No se puede registrar";
            }
        }

        $this->redirect("Usuarios", "index&insercion=$insercion&newElemento=$mensaje");
    }


    /*--- METODO GUARDAR MODIFICACION USUARIO ---*/
    public function guardarmodificacion()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["name"]) || empty($_POST["surnameP"]) || empty($_POST["surnameM"]) ||
            empty($_POST["usuarioemail"]) || empty($_POST["usuarioarea"])) {
            $insercion = 5;
            $mensaje = "Llenar todos los campos";
        } else {
            $usuario = new Usuario($this->adapter);
            $allusers = $usuario->getAllUser2($this->id_Proyecto_constant);

            $id = $_POST["usuarioid"];
            $nombreUser = $_POST["name"];
            $apellidoP = $_POST["surnameP"];
            $apellidoM = $_POST["surnameM"];
            $apellidoUSer = "$apellidoP $apellidoM";

            $validate = true;
            foreach ($allusers as $usu) {
                if ($usu->nombre == $nombreUser && $usu->apellido_paterno = $apellidoP && $usu->apellido_materno == $apellidoM
                        && $usu->id_Usuario != $id)
                    $validate = false;
            }

            if ($validate) {
                // ACTUALIZAR TABLA DE EMPLEADOS-USUARIOS
                $empleado_usuario = new EmpleadoUsuario($this->adapter);
                $empleado_usuario->setIdUsuario($id);
                $empleado_usuario->setNombre($nombreUser);
                $empleado_usuario->setApellidoPaterno($apellidoP);
                $empleado_usuario->setApellidoMaterno($apellidoM);
                $empleado_usuario->modificarEmpleadoUsuario();


                //ACTUALIZAR TABLA DE USUARIOS
                $correoUser = $_POST["usuarioemail"];
                $usuario->setCorreo($correoUser);
                $usuario->setArea($_POST["usuarioarea"]);
                $usuario->set_Empresa($_SESSION[ID_EMPRESA_SUPERVISOR]);
                $usuario->set_participante($_POST["participante"]);
                $save = $usuario->modificarUsuario($id, $allusers);

                // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
                if ($save == 3) {
                    $insercion = 3;
                    $mensaje = 'Se ha modificado el usuario: "' . $nombreUser . ' ' . $apellidoUSer . '"';

                    // ******************************** ACTUALIZAR EN LA BASE GENERAL **********************************************
                    $userGral = new ConsultasGeneral();
                    $userGral->ActualizarDatosUser($id, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombreUser, $apellidoUSer, $correoUser);

                } else {
                    $insercion = 2;
                    $mensaje = 'El correo "' . $correoUser . '" ya existe. No se puede actualizar';
                }
            } else {
                $insercion = 2;
                $mensaje = "El usuario $nombreUser $apellidoP $apellidoM ya existe. No se puede actualizar";
            }

        }

        $this->redirect("Usuarios", "index&insercion=$insercion&newElemento=$mensaje");
    }


    /*--- METODO BORRAR USUARIO ---*/
    public function borrar()
    {
        if (isset($_GET["usuarioid"])) {
            $id = (int)$_GET["usuarioid"];

            //************** ACTUALIZAR LA TABLA DE USUARIO - PROYECTOS EN COLUMNA "id_Status" *************************
            //$usuarioProyecto = new UsuarioProyecto($this->adapter);
            //$usuarioProyecto->modificarStatusByIdUsuario($id);

            $usuario = new Usuario($this->adapter);
            $datosUser = $usuario->getUserById($id);
            $name = $datosUser->nombre_Usuario . ' ' . $datosUser->apellido_Usuario;
            $insercion = 4;
            $mensaje = 'Se eliminó el usuario "' . $name . '"';


            $usuario->deleteElementoById($id, "Usuarios");


            // ******************************** ACTUALIZAR EN LA BASE GENERAL ******************************************
            $userGral = new ConsultasGeneral();
            $userGral->ModificarStatusUser($id, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], 0);

        }
        $this->redirect("Usuarios", "index&insercion=$insercion&newElemento=$mensaje");
    }


    public function restaurar()
    {
        $id_Usuario = $_GET['id_Usuario'];
        $usuario = new Usuario($this->adapter);
        $usuario->restaurarUsuario($id_Usuario, 1);
        $datosUser = $usuario->getUserById($id_Usuario);
        $name = $datosUser->nombre_Usuario . ' ' . $datosUser->apellido_Usuario;
        $insercion = 1;
        $mensaje = 'Se restauro el usuario: "' . $name . '"';

        // ******************************** ACTUALIZAR EN LA BASE GENERAL ******************************************
        $userGral = new ConsultasGeneral();
        $respuesta = $userGral->ModificarStatusUser($id_Usuario, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], 1);

        $this->redirect("Usuarios", "index&insercion=$insercion&newElemento=$mensaje");
    }


    public function nuevoUsuario($id_Usuario, $correo_Usuario, $pwd, $nombre_Usuario, $apellido_Usuario)
    {
        $funciones = new FuncionesCompartidas();

        $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR];
        $_SESSION[NOMBRE_EMPRESA_SUPERVISOR];

        $titulo = " <h4> ¡Hola $nombre_Usuario $apellido_Usuario! </h4> <br>";

        $cuerpo = "Es un gusto para nosotros el ser parte de tus emprendimientos, nuestro compromiso contigo es poner 
        nuestro mayor esfuerzo e ingenio en ofrecerte siempre productos confiables e innovadores que te simplifiquen 
        las actividades laborales diarias.
        Te enviamos el usuario y contraseña de administrador de " . NAMEAPP . " para que ingreses desde web a través de 
        tu navegador favorito (recomendamos Chrome y Firefox).  Para la versión móvil, descárgala desde Google Play 
        buscandonos como " . NAMEAPP . " <br> <br>";

        $datosUser = "Usuario: " . $correo_Usuario . "<br> Contraseña: " . $pwd . "<br> <br>";

        $botTelegram = "Valida tu cuenta en el siguiente enlace: 
        https://t.me/SupervisorUnoBot?start=" . $id_Usuario . "-" . $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR] . "<br> <br>";


        $instruccionesInstalacion = "
        <h4>INSTALACIÓN DE LA PLATAFORMA MÓVIL.</h4>
        1. Descargar Aplicación <br>
        2. En la primer ocasión que ingresan al sistema, les solicitará su usuario y contraseña para registrar el 
            número de serie de su dispositivo en la base de datos del sistema. <br>
        3. Una vez sale el mensaje de “Dispositivo registrado”, cerrar la ventana e ingresar datos en el login del 
            sistema. <br>
        4. Al cargar por primera vez la interfaz, les mostrará un menú vacío respecto a los proyectos que tiene el 
            sistema cargados, se requiere que den clic en continuar o cancelar y el sistema iniciará con la carga 
            de los proyectos definidos en el ambiente web. <br> <br>";

        $despedida = "Estamos atentos a cualquier duda: <br> 
        mail:  contacto@getitcompany.com <br>
        móvil: 55 3412 5304 <br> <br>
        Saludos! <br>
        Equipo Get IT!";

        $mensaje = $titulo . $cuerpo . $datosUser . $botTelegram . $instruccionesInstalacion . $despedida;

        $funciones->sendMail($correo_Usuario, $nombre_Usuario, $apellido_Usuario, 'Nuevo registro ' . NAMEAPP, $mensaje);
    }


    public function verpass()
    {
        if (isset($_GET["usuarioid"])) {
            $id = (int)$_GET["usuarioid"];
            $usuario = new Usuario($this->adapter);
            $datosusuario = $usuario->getUserById($id);
            $allusers = $usuario->getAllUser($this->id_Proyecto_constant);
            $modificar = 2;

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
            $insercion = $_GET['insercion'];
            $newElemento = $_GET['newElemento'];
            if (empty($insercion) && empty($newElemento)) {
                $insercion = 0;
                $newElemento = '';
            }

            $mensaje = "<i class='fa fa-users' aria-hidden='true'></i> Usuarios";

            //******************************************* SECCION RESTAURAR USUARIOS ***************************************
            $mensajeRes = "<i class='fa fa-retweet' aria-hidden='true'></i> Restaurar Usuarios";
            $allUserRes = $usuario->getAllUserRestaurar();

        }
        $this->view("index", array(
            "datosusuario" => $datosusuario, "allusers" => $allusers, "modificar" => $modificar, "insercion" => $insercion,
            "newElemento" => $newElemento, "mensajeRes" => $mensajeRes, "allUserRes" => $allUserRes, "mensaje" => $mensaje
        ));
    }

    public function generakey()
    {
        if (isset($_GET["usuarioid"])) {
            $id = (int)$_GET["usuarioid"];
            $usuario = new Usuario($this->adapter);

            //GENERAR PUBLIC, PRIVATE KEY
            $new_key_pair = openssl_pkey_new(array(
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ));

            openssl_pkey_export($new_key_pair, $private_key_pem);
            $details = openssl_pkey_get_details($new_key_pair);
            $public_key_pem = $details['key'];


            //SAVE BD
            $usuario->guardarkey($id, $public_key_pem, $private_key_pem);
            $savekey = 5;
        }

        $this->redirect("Usuarios", "index&llave=$savekey");
    }


    /*--- VISTA DE TODOS LOS USUARIOS ---*/
    public function perfil()
    {
        // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY d
        $insercion = $_GET['insercion'];
        $newElemento = $_GET['newElemento'];
        if (empty($insercion) && empty($newElemento)) {
            $insercion = 0;
            $newElemento = '';
        }

        $notify = $_GET['notify'];
        $registrarNip = $_GET['registrarNip'];


        $mensaje = "<i class='fa fa-user' aria-hidden='true'></i> Mi Perfil";
        $mensaje1 = "<i class='fa fa-bell' aria-hidden='true'></i>  Notificaciones";


        if ($notify != 6 || $notify == '' || empty($notify) || $notify == null)
            $notify = 0;

        if ($registrarNip != 7 || $registrarNip == '' || empty($registrarNip) || $registrarNip == null)
            $registrarNip = 0;

        $savekey = 0;


        // ************************************ OBTENER INFORMACION DEL EMPLEADO ***************************************
        $usuario = new Usuario($this->adapter);
        $allusers = $usuario->getUserById($_SESSION[ID_USUARIO_SUPERVISOR]);


        $perfil = new Perfil($this->adapter);
        $idPerfil = $_SESSION[ID_PERFIL_USER_SUPERVISOR];
        $noId_Perfil_User = "WHERE id_Perfil_Usuario = $idPerfil ";
        $perfilEmpleado = $perfil->getAllPerfiles($noId_Perfil_User)[0]->nombre_Perfil;


        $this->view("index", array(
            "allusers" => $allusers, "mensaje" => $mensaje, "mensaje1" => $mensaje1, "perfilEmpleado" => $perfilEmpleado,
            "savekey" => $savekey, "notify" => $notify, "registrarNip" => $registrarNip,
            "insercion" => $insercion, "newElemento" => $newElemento
        ));

    }


    public function guardarNip()
    {
        $nip = $_POST['nip'];
        //echo $nip;
        $usuario = new Usuario($this->adapter);
        $usuario->guardarNip($_SESSION[ID_USUARIO_SUPERVISOR], $nip);
        $save = 6;
        $this->redirect("Usuarios", "perfil&notify=$save");
    }


    public function guardarDatos()
    {
        //COMPROBAR CAMPOS VACIOS
        if (empty($_POST["usuarionombre"]) || empty($_POST["usuarioapellido"]) || empty($_POST["usuarioemail"])) {
            $mensaje = "Llenar todos los campos";
            $insercion = 3;
        } else {
            $usuario = new Usuario($this->adapter);
            $empleado = new Empleados($this->adapter);

            $allusers = $usuario->getAllUser($this->id_Proyecto_constant);
            $allEmpleados = $empleado->getAllEmpleados();

            $id = $_SESSION[ID_USUARIO_SUPERVISOR];

            // ACTUALIZAR TABLA DE EMPLEADOS
            $id_emp = $_REQUEST['id_emp'];
            $nombreUser = $_POST["usuarionombre"];
            $apellidoUSer = $_POST["usuarioapellido"];
            $empleado->set_Nombre($nombreUser);
            $empleado->set_Apellido_Paterno($apellidoUSer);
            $empleado->set_Id_Empleado($id_emp);
            $saveEmpleado = $empleado->modificarEmpleadoNomAndApe($allEmpleados);


            // ACTUALIZAR TABLA DE USUARIOS
            $correoUser = $_POST["usuarioemail"];
            $usuario->setCorreo($correoUser);
            $save = $usuario->modificarUsuarioDatos($id, $allusers);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            if ($save == 1 || $saveEmpleado) {
                $insercion = 1;
                $mensaje = 'Se actualizaron correctamente los datos personales';

                // ******************************** ACTUALIZAR EN LA BASE GENERAL **********************************************
                $userGral = new ConsultasGeneral();
                $userGral->ActualizarDatosUser($id, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $nombreUser, $apellidoUSer, $correoUser);
            } else {
                $insercion = 2;
                $mensaje = 'El usuario ya existe';
            }

        }

        $this->redirect("Usuarios", "perfil&insercion=$insercion&newElemento=$mensaje");
    }


    public function guardarPwd()
    {
        $usuario = new Usuario($this->adapter);
        $id = $_SESSION[ID_USUARIO_SUPERVISOR];

        $newPwd = $_POST["usuariopassword"];
        $usuario->setPassword($newPwd);
        $resultado = $usuario->modificarUsuarioPwd($id);
        if ($resultado) {
            // ******************************** GUARDAR EN LA BASE GENERAL *********************************************
            $userGral = new ConsultasGeneral();
            $userGral->ActualizarPwd($id, $_SESSION[ID_EMPRE_GENERAL_SUPERVISOR], $newPwd);

            // SECCION PARA EL MODULO DE MENSAJES CON ALERTIFY
            $insercion = 1;
            $mensaje = 'Se ha modificado la contraseña de forma exitosa';
        } else {
            $insercion = 2;
            $mensaje = 'Error al cambiar la contraseña';
        }

        $this->redirect("Usuarios", "perfil&insercion=$insercion&newElemento=$mensaje");
    }


    /*--- METODO CERRAR SESSION ---*/
    public function salir()
    {
        //BITACORA
        $funcionesCom = new FuncionesCompartidas();
        $funcionesCom->guardarBitacora('NULL', $_SESSION[ID_USUARIO_SUPERVISOR], 'NULL', 13, NULL, $this->id_Proyecto_constant);
        session_destroy();
        $url = $_SERVER['SERVER_NAME'];
        header("Location: https://{$url}");

    }


}
