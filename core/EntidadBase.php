<?php

/*--- QUERIES A LA BASE DE DATOS ---*/

class EntidadBase
{
    private $table;
    private $db;
    private $conectar;
    public $var_compartida = "variable compartida";
    public $Proyecto_Id_Sesion;


    public function __construct($table, $adapter)
    {
        $this->table = (string)$table;
        $this->conectar = null;
        $this->db = $adapter;
        $this->Proyecto_Id_Sesion = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    public function getConectar()
    {
        return $this->conectar;
    }

    public function db()
    {
        return $this->db;
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::GENERALES::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    /*--- GENERAL: ALTA-BAJA DE REGISTRO ---*/
    public function deleteElementoById($id, $tabla)
    {
        $valor = 0;
        $query = "CALL sp_Modify_Status($id,$valor,'$tabla')";
        $this->db->query($query);
        return true;

    }


    /* ::::::::::::::::::::::::::::::::::::::::::::::: FORMATEAR FECHA :::::::::::::::::::::::::::::::::::::::::::::: */
    public function formatearFecha($fecha)
    {
        $text = str_replace('/', '-', $fecha);
        $date = new DateTime($text);
        //echo $calendario . " " . $date->format('d-m-Y');
        return $date->format('d-m-Y');
    }


    /*------------------------------------------------------ BITACORAS -----------------------------------------------*/
    /*--- BITACORAS: CONSULTAR BITACORAS ---*/
    public function getAllBitacora($id_proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllBitacora WHERE id_proyecto = $id_proyecto 
                    ORDER BY fecha_Bitacora DESC, hora_Bitacora DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- BITACORAS: CONSULTAR BITACORAS ---*/
    public function getAllBitacoraNoAdmin($id_proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllBitacora  where id_Usuario != 1 AND id_proyecto = $id_proyecto 
                        ORDER BY fecha_Bitacora DESC, hora_Bitacora DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- BITACORAS: CONSULTAR ID DE BITACORA LIKE FOTORAFIA---*/
    public function getBitacoraLike($id_Usuario, $id_Gpo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllBitacora WHERE id_Modulo = 1 AND id_Usuario = $id_Usuario 
                    AND id_Gpo = $id_Gpo AND accion_Bitacora = 15");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- BITACORAS: CONSULTAR ID DE BITACORA LIKE FOTORAFIA---*/
    public function getAllClasificacionFotografias($id_Area)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllClasificacionFotografias ORDER BY orden ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES: CONSULTAR ULTIMO REPORTES ---*/

    public function getUltimoRegistro()
    {
        $query = $this->db->query("SELECT MAX(id_Reporte) AS id FROM Cat_Reportes;");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::FOTOGRAFIAS::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- GENERAL: CONSULTAR TODAS LAS FOTOGRAFIAS DE UN REPORTE (ID)---*/
    public function getAllFotografias($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllFotografias");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- GENERAL: CONSULTAR TODAS LAS FOTOGRAFIAS DE UN REPORTE (ID)---*/
    public function getAllFotografiasById($id, $id_modulo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllFotografias WHERE identificador_Fotografia = $id AND id_Modulo = $id_modulo");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- FOTOGRAFIAS: CONSULTAR FOTOGRAFIA POR ID---*/
    public function getFotografiaById($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllFotografias WHERE id_Fotografia = $id");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- BUSQUEDA REPORTE---*/
    public function getBusquedaFoto($id_Gpo, $ids_reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllFotografias WHERE (identificador_Fotografia = $id_Gpo 
                            AND id_Modulo = 3) OR (identificador_Fotografia IN ($ids_reporte) AND id_Modulo = 1)");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::GRAFICAS AVANCES:::::::::::::::::::::::::::::::::::::::::::::::::*/

    public function getAllUsuariosReportesTotal($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT COUNT(rl.id_Gpo_Valores_Reporte) AS total, rl.id_Usuario, rl.id_Empresa, rl.nombre_Usuario, 
            rl.apellido_Usuario, rl.id_Proyecto,CASE WHEN rl.id_Empresa IN (0) THEN  'Registro migrado'
             ELSE CONCAT(rl.nombre_Usuario,' ' ,rl.apellido_Usuario) END AS nombre
            FROM VW_getAllReportesLlenados rl
                LEFT JOIN Usuarios usu ON usu.id_Usuario = rl.id_Usuario
                LEFT JOIN Usuarios_Proyectos up ON usu.id_Usuario = up.id_Usuario
            WHERE rl.id_Proyecto = $id_Proyecto AND up.id_Proyecto = $id_Proyecto AND rl.id_Empresa = 1 
            AND rl.tipo_Reporte IN(0,1) AND usu.id_Status_Usuario = 1 AND up.id_Status = 1
            GROUP BY nombre ORDER BY total DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*-----------------------------------------------CONSULTA GRAFICA INGRESO ------------------------------------------*/
    public function getGraficaReportes($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT COUNT(varl.id_Gpo_Valores_Reporte) AS cantidad,nombre_Reporte 
            FROM VW_getAllReportesLlenados varl WHERE id_Proyecto = $id_Proyecto AND tipo_Reporte IN (0,1) 
            GROUP BY varl.id_Reporte ORDER BY cantidad DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::USUARIOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- USUARIOS: LOGUEAR ---*/
    public function LogUser($correo, $password)
    {
        $query = $this->db->query("CALL SP_logUser('$correo', '$password')");
        if (!$query) {
            $row_cnt = 0;
        } else {
            $row_cnt = $query->num_rows;
        }
        if ($row_cnt == 0) {
            $resultSet = 0;
            return $resultSet;
        } else {
            while ($row = mysqli_fetch_assoc($query)) {
                $dbid = $row['id_Usuario'];
                $dbempresa = $row['id_Empresa'];
                $dbareaid = $row['id_Area'];
                $dbusuarionombre = utf8_encode($row['nombre']);
                $dbusuarioapellido = utf8_encode($row['apellido_paterno']) . ' ' . utf8_encode($row['apellido_materno']);
                $dbusuariocorreo = utf8_encode($row['correo_Usuario']);
                $dbareanombre = $row['nombre_Area'];
            }

            session_start();
            $_SESSION[TIME_SUPERVISOR] = time();
            $_SESSION[ID_USUARIO_SUPERVISOR] = $dbid;
            $_SESSION[ID_EMPRESA_SUPERVISOR] = $dbempresa;
            $_SESSION[ID_AREA_SUPERVISOR] = $dbareaid;
            $_SESSION[NOMBRE_USUARIO_SUPERVISOR] = $dbusuarionombre;
            $_SESSION[APELLIDO_USUARIO_SUPERVISOR] = $dbusuarioapellido;
            $_SESSION[CORREO_USUARIO_SUPERVISOR] = $dbusuariocorreo;
            $_SESSION[NOMBRE_AREA_SUPERVISOR] = $dbareanombre;
            $_SESSION[AUTENTICADO_SUPERVISOR] = TRUE;

            $resultSet = 1;
            $query->close();
            $this->db->next_result();
            return $resultSet;
        }
    }

    /*--- USUARIOS: LOGUEAR ---*/
    public function getPermisoByPuesto($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllPermisosUsuarios vapu WHERE id_Perfil_Usuario = $id");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getAllUser()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios ORDER BY nombre_Usuario, apellido_Usuario DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getAllUser2()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT u.id_Usuario, u.id_Empresa, u.id_Area, eu.nombre, eu.apellido_paterno,
            IFNULL(eu.apellido_materno, '') AS apellido_materno, u.correo_Usuario, 
            u.nip_Usuario, u.id_Status_Usuario, u.participante, ae.nombre_Area
            FROM Usuarios u
	            LEFT JOIN Areas_Empresas ae ON ae.id_Area = u.id_Area
                JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario = 1");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getAllUserActivosAndInactivos()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEmpleadoUsuario WHERE id_usuario != 0");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getAllUserRestaurar()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT u.*, eu.* FROM Usuarios u
                            LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
                        WHERE u.id_Status_Usuario = 0");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getExistEmpleadoToUser($idEmpleado)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT eu.*, u.correo_Usuario
            FROM empleados_usuarios eu
                LEFT JOIN Usuarios u ON u.id_Usuario = eu.id_usuario
            WHERE eu.id_empleado = $idEmpleado AND eu.id_usuario != 0;");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllUserOrderById()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios ORDER BY id_Usuario ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getUltimoIdUsuario()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT MAX(id_Usuario) as id FROM Usuarios");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR TODOS LOS USUARIOS ---*/
    public function getAllUserCorreo($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos WHERE id_Proyecto = $id_Proyecto ORDER BY correo_Usuario ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR USUARIO POR ID ---*/
    public function getUserById($id)
    {
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios WHERE id_Usuario = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR USUARIO POR ID ---*/
    public function getUserById2($id)
    {
        $query = $this->db->query("SELECT u.id_Usuario, u.id_Empresa, u.id_Area, eu.nombre, eu.apellido_paterno,
            IFNULL(eu.apellido_materno, '') AS apellido_materno, u.correo_Usuario, 
            u.nip_Usuario, u.id_Status_Usuario, u.participante, u.puesto, u.empresa, ae.nombre_Area
            FROM Usuarios u
	            LEFT JOIN Areas_Empresas ae ON ae.id_Area = u.id_Area
                JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario = 1 AND u.id_Usuario = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR LA FIRMA DE TODOS LOS USUARIOS ---*/
    public function getFirmaUserById($idUsuario)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Usuario, llave_privada, llave_publica, 
                                (CASE WHEN (ISNULL(llave_privada) OR ISNULL(llave_publica)) THEN 0 END) AS llave 
                                FROM Usuarios WHERE id_Usuario = $idUsuario");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    function getAllUsersNotEmployees()
    {
        $resultSet = [];
        $query = $this->db->query("select * from VW_getAllEmpleadoUsuario WHERE id_empleado = 0");
        if ($query) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }

        return $resultSet;
    }

    /*--- USUARIOS: CONSULTAR USUARIOS QUE HAYAN LLENADO REPORTES ---*/
    public function getAllUserLlenadosReportes($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllReportesLlenados where id_Proyecto = $id_Proyecto 
                                    AND tipo_Reporte = 0 GROUP BY id_Usuario");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::NOTIFICACIONES::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- NOTIFICACIONES: CONSULTAR TODAS LA NOTIFICACIONES POR ID_USUARIO ---*/
    public function getAllNotificaciones($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllNotificaciones WHERE id_usuarionotificacion = $id AND id_usuarionotifico NOT IN($id)");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- NOTIFICACIONES: CONSULTAR TODAS LA NOTIFICACIONES POR ID_USUARIO PARA WEB ---*/
    public function getAllNotificacionesWeb($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllNotificaciones WHERE id_usuarionotificacion = $id 
                AND id_usuarionotifico NOT IN($id) GROUP BY id_notificacion 
                ORDER BY id_status = 1 desc, Fecha desc LIMIT 50");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- NOTIFICACIONES: CONSULTAR TODOS LOS USUARIOS POR id_Gpo_Valores_Notificacion EN UN REPORTE ---*/
    public function getAllUserNotificacion($id_Gpo, $iduser)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT DISTINCT id_usuarionotifico FROM VW_getAllNotificaciones 
                        WHERE id_Gpo_Valores_Notificacion = $id_Gpo AND id_usuarionotifico NOT IN($iduser)");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::EMPRESAS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- EMPRESAS: CONSULTAR EMPRESAS ---*/
    public function getAllEmpresas()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEmpresas");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllEmpresasByProyecto($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEmpresasm WHERE id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- EMPRESAS: CONSULTAR EMPRESA POR ID ---*/
    public function getEmpresaById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllEmpresas WHERE id_Empresa = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::PROYECTOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    /*--- PROYECTOS: CONSULTAR PROYECTOS ID Y NOMBRE ---*/
    public function getAllProyectosIdAndName()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Proyecto as id, nombre_Proyecto as nombre FROM Proyectos");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROYECTOS: CONSULTAR PROYECTOS ID Y NOMBRE BY ID ---*/
    public function getAllProyectosIdAndNameById($id)
    {
        $resultSet = array();
        $query1 = "SELECT id_Proyecto as id, nombre_Proyecto as nombre FROM Proyectos WHERE id_Proyecto = $id";
        $query = $this->db->query($query1);
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROYECTOS: CONSULTAR PROYECTOS ---*/
    public function getAllProyecto()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_GetAllProyectos ORDER BY nombre_Proyecto ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROYECTOS: CONSULTAR PROYECTO POR ID ---*/
    public function getProyectoById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_GetAllProyectos WHERE id_Proyecto = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR -USUARIOS-PROYECTOS ---*/
    public function getAllUsuarioProyecto()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos WHERE id_Perfil_Usuario != 1 
                                ORDER BY nombre_Usuario ASC, nombre_Proyecto ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /*--- USUARIOS-PROYECTOS: CONSULTAR -USUARIOS-PROYECTOS ---*/
    public function getAllUsuarioProyectoByIdUsuarioAndIdProyecto($id_Usuario, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos where id_Usuario = $id_Usuario 
                                    AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- USUARIOS-PROYECTOS: CONSULTAR -USUARIOS-PROYECTOS ---*/
    public function getAllUsuarioProyectoSuperAdmin()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos ORDER BY nombre_Usuario ASC, nombre_Proyecto ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR -USUARIOS-PROYECTOS ---*/
    public function getUsuarioProyectoById($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos WHERE idUsuarios_Proyectos = $id 
                                    ORDER BY apellido_Usuario ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR PROYECTO POR USUARIO ---*/
    public function getAllProyectosByUser($id_Usuario)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos WHERE id_Usuario = $id_Usuario 
                                    ORDER BY nombre_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR POR PROYECTO y POR USUARIO ---*/
    public function getAllProyectosByUserAndProyecto($id_Usuario, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllUsuarios_Proyectos WHERE id_Usuario = $id_Usuario 
                                    AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR POR USUARIO ---*/
    public function getAllProyectosLibres($idProyectos)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Proyectos WHERE id_Proyecto NOT IN($idProyectos) 
                                    AND id_Status_Proyecto = 1 ORDER BY nombre_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- USUARIOS-PROYECTOS: CONSULTAR POR USUARIO ---*/
    public function getAllProyectosDuplicar()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Proyectos WHERE id_Status_Proyecto = 1 ORDER BY nombre_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::EMPRESAS - PROYECTOS:::::::::::::::::::::::::::::::::::::::::::::::::::*/
    public function getAllEmpresasProyectos()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEmpresas_Proyectos");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getEmpresaProyectoById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllEmpresas_Proyectos WHERE id_Empresas_Proyectos = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::AREAS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- AREAS: CONSULTAR AREAS ---*/
    public function getAllArea()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllAreasEmpresas");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- AREAS: CONSULTAR AREA POR ID ---*/
    public function getAreaById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllAreasEmpresas WHERE id_Area = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }



    /*:::::::::::::::::::::::::::::::::::::::::::::::PERFILES:::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- PUESTOS: CONSULTAR PUESTOS ---*/
    public function getAllPerfiles($id_Perfil)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllPerfilesUsuarios $id_Perfil ORDER BY nombre_Perfil ASC");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PUESTOS: CONSULTAR PUESTO POR ID ---*/
    public function getPerfilById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllPerfilesUsuarios WHERE id_Perfil_Usuario = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- OBTENER CUANTOS PERFILES EXISTEN ---*/
    public function getAllCountValoresPerfil()
    {
        $query = $this->db->query("SELECT count(id_Perfil_Usuario) id_Perfil_Usuario FROM Perfiles_Usuarios where id_Status_Perfil = 1;");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- OBTENER EL VALOR MAXIMO DEL PERFIL ---*/
    public function getAllValorMaxPerfil()
    {
        $query = $this->db->query("SELECT max(id_Perfil_Usuario) as id_Perfil FROM VW_getAllPerfilesUsuarios WHERE id_Status_Perfil = 1");
        if ($row = $query->fetch_row()) {
            $resultSet = trim($row[0]);
        }
        $query->close();
        return $resultSet;
    }


    /*--- PUESTOS: CONSULTAR PUESTOS ---*/
    public function getAllPerfilesById_Reporte($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes where id_Reporte = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::PERMISOS:::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- PERMISOS: CONSULTAR PERMISOS ---*/
    public function getAllPermiso($id_Perfil_Usuario)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllPermisosUsuarios WHERE id_Perfil_Usuario = $id_Perfil_Usuario");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- PERMISOS: CONSULTAR PERMISOS QUE NO SON ADMINISTRADORES---*/
    public function getAllPermisoNoSuperAdmin($id_Perfil_Usuario, $superAdmin)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllPermisosUsuarios WHERE id_Perfil_Usuario = $id_Perfil_Usuario AND id_Recurso_Sistema != $superAdmin");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PERMISOS: CONSULTAR PERMISO POR ID ---*/
    public function getPermisoById($id)
    {
        $resultSet = "";
        $query = $this->db->query("CALL SP_getPermisoById($id)");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::RECURSOS:::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- RECURSOS: CONSULTAR RECURSOS ---*/
    public function getAllRecurso()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllRecursosSistema");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- RECRUSOS: CONSULTAR RECURSO POR ID ---*/
    public function getRecursoById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllRecursosSistema WHERE id_Recurso_Sistema = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- OBTENER TODOS LOS IDS DE LOS RECURSOS CREADOS ---*/
    public function getAllIdRecSistema()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Recurso_Sistema FROM Recursos_Sistema;");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::ESTATUS:::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- ESTATUS: CONSULTAR ESTATUS ---*/
    public function getAllEstatus()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllStatus ORDER BY IDModulo AND Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTATUS: CONSULTAR ESTATUS POR ID ---*/
    public function getEstatusById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllStatus WHERE id_Status = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTATUS: CONSULTAR ESTATUS PARA UN MODULO---*/
    public function getEstatusByModulo($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllStatus WHERE IdModulo = $id ORDER BY Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTATUS: CONSULTAR ESTATUS PARA REPORTE---*/
    public function getEstatusByReporte($id, $estatusactual)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllStatus WHERE IdModulo = $id AND id_Status > $estatusactual ORDER BY Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::MODULOS:::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- MODULOS: CONSULTAR MODULOS ---*/
    public function getAllModulos()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatModulos ORDER BY Id_Modulo ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- MODULOS: CONSULTAR MODULO POR ID ---*/
    public function getModuloById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCatModulos WHERE Id_Modulo = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::MATRIZ COMUNICACION::::::::::::::::::::::::::::::::::::::::::::*/
    public function getAllMatriz($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT mc.mat_Id AS mat_Id, mc.mat_Id_Usuario AS mat_Id_Usuario, mc.mat_Id_Reporte AS mat_Id_Reporte,
		mc.mat_Id_Proyecto AS mat_Id_Proyecto, mc.mat_Correo AS mat_Correo, mc.mat_Telegram AS mat_Telegram,
		mc.mat_Whatsapp AS mat_Whatsapp, mc.mat_Push AS mat_Push,
		p.id_Proyecto AS id_Proyecto, p.nombre_Proyecto AS nombre_Proyecto,
		eu.nombre AS nombre_Usuario,
        CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
        u.correo_Usuario AS correo_Usuario, 
		u.id_telegram AS id_telegram, u.id_Status_Usuario AS id_Status_Usuario,
		cr.nombre_Reporte AS nombre_Reporte
	    FROM Matriz_Comunicacion mc
		    LEFT JOIN Usuarios u ON u.id_Usuario = mc.mat_Id_Usuario
            LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
		    LEFT JOIN Proyectos p ON p.id_Proyecto = mc.mat_Id_Proyecto
		    LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = mc.mat_Id_Reporte
	    WHERE u.id_Status_Usuario = 1 AND mc.mat_Id_Proyecto = $id_Proyecto AND mc.mat_Id_Status = 1
	    ORDER BY eu.apellido_paterno, cr.nombre_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    public function getAllMatrizById_UsuarioAndId_Proyecto($id_Usuario, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT 
        mc.mat_Id AS mat_Id, mc.mat_Id_Usuario AS mat_Id_Usuario, mc.mat_Id_Reporte AS mat_Id_Reporte,
        mc.mat_Id_Proyecto AS mat_Id_Proyecto, mc.mat_Correo AS mat_Correo, mc.mat_Telegram AS mat_Telegram,
        mc.mat_Whatsapp AS mat_Whatsapp, mc.mat_Push AS mat_Push,
        p.id_Proyecto AS id_Proyecto,
        p.nombre_Proyecto AS nombre_Proyecto,
        eu.nombre AS nombre_Usuario,
        CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario, 
        u.correo_Usuario AS correo_Usuario, 
        u.id_telegram AS id_telegram, u.id_Status_Usuario AS id_Status_Usuario,
		cr.nombre_Reporte AS nombre_Reporte
        FROM Matriz_Comunicacion mc
            LEFT JOIN Usuarios u ON u.id_Usuario = mc.mat_Id_Usuario
        LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            LEFT JOIN Proyectos p ON p.id_Proyecto = mc.mat_Id_Proyecto
            LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = mc.mat_Id_Reporte
	    WHERE u.id_Status_Usuario = 1 AND mc.mat_Id_Proyecto = $id_Proyecto AND mc.mat_Id_Status = 1 
	    AND mc.mat_Id_Usuario = $id_Usuario ORDER BY eu.apellido_paterno, cr.nombre_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllMatrizByReporte($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllMatriz_Comunicacion WHERE mat_Id_Reporte  = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllMatrizById($mat_Id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllMatriz_Comunicacion WHERE mat_Id  = $mat_Id");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllMatrizByIdUser($id_User)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllMatriz_Comunicacion WHERE mat_Id_Usuario = $id_User");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    // OBTENER TODOS LOS IDS DE LOS REPORTES QUE ESTAN EN LA MATRIZ DE COMUNICAICON
    public function getAllIdsMatriz()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT DISTINCT(mat_Id_Reporte) FROM Matriz_Comunicacion");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    // OBTENER TODOS LOS REPORTES DE TIPO 0 y 1 POR PROYECTO
    public function getAllReportesByIdProyecto($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllConfReportesCampos WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Status_Reporte = 1  AND tipo_Reporte IN(0,1)
        GROUP BY id_Reporte ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    // OBTENER TODOS LOS REPORTES POR ID USUARIO Y ID PROYECTO
    public function getAllReportesByIdUsuarioAndIdProyecto($id_Usuario, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Matriz_Comunicacion WHERE mat_Id_Usuario = $id_Usuario AND mat_Id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    // OBTENER TODOS LOS REPORTES POR ID USUARIO Y ID PROYECTO
    public function getAllReportesByNotIdReporte($ids_Reportes, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllConfReportesCampos WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Status_Reporte = 1 AND tipo_Reporte IN(0,1,4) AND id_Reporte NOT IN ($ids_Reportes) 
                                    GROUP BY id_Reporte ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::DISPOSITIVOS::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- DISPOSITIVOS: CONSULTAR DISPOSITIVOS ---*/
    public function getAllDispositivos($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatDispositivos ORDER BY nombre_Usuario ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- DISPOSITIVOS: CONSULTAR DISPOSITIVO POR ID ---*/
    public function getDispositivosById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCatDispositivos WHERE Id_Dispositivo = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::: REPORTES :::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- REPORTES: CONSULTAR REPORTES ---*/
    public function getAllReporte($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatReportes WHERE id_Proyecto = $id_Proyecto ORDER BY 
        nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES: CONSULTAR REPORTES ---*/
    public function getAllReporteSinIncidencias($id_Proyecto, $noReportes)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatReportes WHERE id_Proyecto = $id_Proyecto 
                                    AND tipo_Reporte IN (0,2,3) $noReportes ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES: CONSULTAR CANTIDAD DE REPORTES POR ID ---*/
    public function getAmountReports($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT COUNT(id_Reporte) AS amount FROM VW_getAllReportesLlenados WHERE id_Reporte = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES: CONSULTAR REPORTES ---*/
    public function getAllReporteByArea($id_Proyecto, $id_Area)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatReportes WHERE id_Proyecto = $id_Proyecto 
                                    AND Areas IN ($id_Area) ORDER BY id_Reporte DESC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES: CONSULTAR REPORTE POR ID ---*/
    public function getReporteById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCatReportes WHERE id_Reporte = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*----------------------------------------------- CAMPOS ---------------------------------------------------------*/
    /*--- CAMPOS: CONSULTAR CAMPOS ---*/
    public function getAllCampo()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCamposReportes ORDER BY nombre_Campo ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CAMPOS: CONSULTAR CAMPO POR ID ---*/
    public function getCampoById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCamposReportes WHERE id_Campo_Reporte = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CAMPOS: CONSULTAR CAMPOS POR NOMBRE ---*/
    public function getAllCampoByNombre($nombreCampo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Campos_Reportes where nombre_Campo = '$nombreCampo'");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*----------------------------------------------- CONF CAMPOS-REPORTES  ------------------------------------------*/
    /*--- CONF CAMPOS-REPORTES: CONSULTAR LOS REPORTES CONFIGURADOS ---*/
    public function getAllCampoReporte($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Proyecto = $id GROUP BY 
        id_Reporte ORDER BY nombre_Reporte ASC");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR LOS REPORTES CONFIGURADOS ---*/
    public function getCampoReporteByIdReporte($idReporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Reporte = $idReporte ORDER BY Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR LOS REPORTES CONFIGURADOS ---*/
    public function getAllCampoReporteByAreaTipo($id_Proyecto, $id_Area, $tipo_Reporte, $noreportes)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $id_Area . "[[:>:]]";
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Proyecto = $id_Proyecto 
        AND tipo_Reporte IN ($tipo_Reporte) $noreportes AND Areas RLIKE \"$area2\" GROUP BY id_Reporte ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    // OBTENER TODOS LOS REPORTES CONFIGURADOS CON CAMPOS
    public function getAllCampoReporteByIdProyectoAndArea($id_Proyecto, $id_Area)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $id_Area . "[[:>:]]";
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Proyecto = $id_Proyecto 
                                    AND Areas RLIKE \"$area2\" GROUP BY id_Reporte ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    // OBTENER TODOS LOS REPORTES SIN CONFIGURACION DE CAMPOS
    public function getAllReporteSinConfigurar($id_Proyecto, $idsConfigurados)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatReportes WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Reporte NOT IN($idsConfigurados) ORDER BY nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- ELEMENTOS DE INVETERIO Y UBICACIONES ---*/
    public function getAllElementosByInvAndUbi($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM `VW_getAllCatReportes` WHERE tipo_Reporte IN(2,3) AND id_Proyecto = $id_Proyecto 
        ORDER BY tipo_Reporte ASC, nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ELEMENTOS DE INVETERIO Y UBICACIONES By ID ---*/
    public function getAllElementosByInvAndUbiByID($id_Proyecto, $idReporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM `VW_getAllCatReportes` WHERE tipo_Reporte IN(2,3) 
                                    AND id_Proyecto = $id_Proyecto AND id_Reporte = $idReporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR CAMPOS DE UN REPORTE ---*/
    public function getAllCamposReporte($id, $id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Proyecto = $id AND 
        id_Reporte = $id_Reporte ORDER BY Secuencia ASC");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR CAMPOS DE UN REPORTE ---*/
    public function getCampoReporteByIdReporteAndIdConfiguracionReporte($id, $id_Reporte, $id_Configuracion_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos
            WHERE id_Proyecto = $id AND id_Reporte = $id_Reporte AND id_Configuracion_Reporte = $id_Configuracion_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR CAMPOS-REPORTES POR ID DE REPORTE ---*/
    public function getAllCampoReporteByIdReporte($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Reporte = $id ORDER BY Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- CONF CAMPOS-REPORTES: CONSULTAR CAMPOS-REPORTES POR ID_CONFIGURACION_REPORTE ---*/
    public function getCampoReporteById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllConfReportesCampos WHERE id_Configuracion_Reporte = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*---LLENADO RPORTES: CONSULTAR ULTIMO GRUPO ---*/
    public function getUltimoReporteLlenado()
    {
        $query = $this->db->query("SELECT MAX(id_Gpo_Valores_Reporte) AS id FROM Valores_Reportes_Campos");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES LLENADOS ---*/
    public function getAllReportesLlenados()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllReportesLlenados GROUP BY id_Gpo_Valores_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES LLENADOS ---*/
    public function getReportesLlenadosByIdReporte($idReporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Gpo_Valores_Reporte FROM VW_getAllReportesLlenados WHERE id_Reporte = $idReporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::::::::::: PROCESOS :::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- PROCESOS: CONSULTAR PROCESOS ---*/
    public function getAllProcesos($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE id_Proyecto = $id_Proyecto ORDER BY Id_Reporte_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROCESOS: CONSULTAR PROCESOS POR ID PROCESO ---*/
    public function getAllProcesosbyIdProceso($idProceso)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE Id_Proceso = $idProceso");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROCESOS: CONSULTAR PROCESOS BY ID REPORTE PADRE ---*/
    public function getAllProcesosById_Reporte_Padre($Id_Reporte_Padre)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE Id_Reporte_Padre = $Id_Reporte_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROCESOS: CONSULTAR PROCESOS BY ID REPORTE PADRE Y ID REPORTE HIJO ---*/
    public function getAllProcesosById_Reporte_PadreAndId_Reporte_Hijo($Id_Reporte_Padre, $Id_Reporte_Hijo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE Id_Reporte_Padre = $Id_Reporte_Padre 
                                    AND Id_Reporte_Hijo = $Id_Reporte_Hijo");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- PROCESOS: CONSULTAR PROCESOS LLENADOS PADRES ---*/
    public function getAllProcesosLlenadosPadre($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE id_Proyecto = $id_Proyecto GROUP BY Id_Reporte_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    // OBTENER DATOS DE SELECT DE UBICACIONES
    /*--- REPORTES LLENADOS: CONSULTAR REPORTES LLENADOS ---*/
    public function getElementosLlenadosByIdReporte($idReporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Gpo_Valores_Reporte, titulo_Reporte FROM VW_getAllReportesLlenados WHERE id_Reporte = $idReporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::::: PROCESOS AVANCES :::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- PROCESOS AVANCE: CONSULTAR PROCESOS BY Id_Gpo_Valores_Padre Y Id_Proceso ---*/
    public function getAllProcesosAvance($Id_Gpo_Valores_Padre, $Id_Proceso)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Procesos_Avances WHERE Id_Gpo_Valores_Padre = $Id_Gpo_Valores_Padre 
                                    AND Id_Proceso = $Id_Proceso AND Id_Status = 1");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    //**************************************** PROCESOS AVANCE *********************************************************
    /*--- PROCESOS AVANCE: CONSULTAR PROCESOS BY Id_Gpo_Valores_Padre---*/
    public function getAllProcesosAvancesVinculados($Id_Gpo_Valores_Padre)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesosAvances where Id_Gpo_Valores_Padre = $Id_Gpo_Valores_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*::::::::::::::::::::::::::::::::::::::::: ESTRUCTURA PROCESOS ::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- ESTRUCTURA PROCESOS: Consultar reportes Llenados de Ubicaciones e Inventarios ---*/
    public function getAllReportesLlenadosByType($tipoReporte, $id_Proyecto, $noReportes, $noRepit)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS id_Gpo_Valores_Reporte,
            rl.fecha_registro AS Fecha, rl.id_Usuario, rl.id_Reporte AS id_Reporte,
            rl.titulo_Reporte, rl.id_Etapa, cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, cr.tipo_Reporte
            FROM Reportes_Llenados rl 
	            LEFT JOIN Cat_Reportes cr ON ((`cr`.`id_Reporte` = rl.`id_Reporte`))
            WHERE rl.id_Status_Elemento = 1 $noReportes AND cr.id_Proyecto = $id_Proyecto 
            AND cr.tipo_Reporte IN ($tipoReporte) $noRepit");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar reportes Llenados de Ubicaciones e Inventarios ---*/
    public function getAllCatReportesByNotIdReporte($id_Reporte, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * from Cat_Reportes where id_Proyecto = $id_Proyecto AND id_Reporte != $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA ---*/
    public function getAllRegistrosProcesos($id_Proyecto, $id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllProcesos WHERE id_Proyecto = $id_Proyecto 
                            AND Id_Reporte_Padre != $id_Reporte GROUP BY Id_Reporte_Padre ORDER BY nombre_Reporte_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA ---*/
    public function getAllEstructuraProcesos($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proyecto = $id_Proyecto 
                                    ORDER BY titulo_Reporte, nombre_Reporte_Hijo");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA por id_Gpo_Valores_Padre ---*/
    public function getAllEstructuraProcesosByIdGpoValoresReporte($id_Proyecto, $id_Gpo_Valores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Gpo_Valores_Padre = $id_Gpo_Valores");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA por id_Proceso_Estructura ---*/
    public function getAllEstructuraProcesosByIdEstrcturaProceso($idProcesoEstructura)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proceso_Estructura = $idProcesoEstructura");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA GROUP BY id_Gpo_Valores_Padre ---*/
    public function getAllEstructuraProcesosLlenadosGpoValores($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proyecto = $id_Proyecto 
                                    GROUP BY id_Gpo_Valores_Padre");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA por IdGpoValoresReportePadre y IdReportePadre ---*/
    public function getAllEstructuraProcesosByIdGpoValoresReportePadre($id_Proyecto, $id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Reporte = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA por IdGpoValoresReportePadre y IdReportePadre ---*/
    public function getAllEstructuraProcesosByIdGpoValoresReportePadreAndIdReportePadre($id_Proyecto, $id_Gpo_Valores, $id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos WHERE id_Proyecto = $id_Proyecto 
                                    AND id_Gpo_Valores_Padre = $id_Gpo_Valores AND id_Reporte_Padre = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Consultar registros de ESTRUCTURA ---*/
    public function getAllEstructuraProcesosByIdReporte($id_Proyecto, $id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllEstructuraProcesos 
                        WHERE id_Proyecto = $id_Proyecto AND id_Gpo_Valores_Padre = $id_Reporte ORDER BY titulo_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Calcular Porcentaje de Estructuras ---*/
    public function calcularPorcentajeEstructura($id_Gpo_Valores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT pe.id_Gpo_Valores_Padre, cr.nombre_Reporte, pe.id_Reporte_Padre,pe.Cantidad, 
            pe.Porcentaje, rl.id_Gpo_Valores_Reporte,
            ROUND((SELECT SUM(pa1.Porcentaje) AS porcentaje FROM VW_getAllProcesosAvances pa1
                WHERE rl.id_Gpo_Valores_Reporte = pa1.Id_Gpo_Valores_Padre),2) SUMA,
            ROUND((SELECT SUM(pa1.Porcentaje) AS porcentaje FROM VW_getAllProcesosAvances pa1 
                WHERE rl.id_Gpo_Valores_Reporte = pa1.Id_Gpo_Valores_Padre)*pe.Porcentaje/(100*pe.Cantidad),2) AS Porcentaje1
            FROM Proceso_Estructura pe
                INNER JOIN Reportes_Llenados rl ON pe.id_Reporte_Padre = rl.id_Reporte
                INNER JOIN Cat_Reportes cr ON cr.id_Reporte = pe.id_Reporte_Padre
                INNER JOIN VW_getAllProcesosAvances pa ON pa.Id_Gpo_Valores_Padre = rl.id_Gpo_Valores_Reporte
            WHERE rl.id_Gpo_Padre = $id_Gpo_Valores GROUP BY rl.id_Gpo_Valores_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Calcular Porcentaje de Estructuras General---*/
    public function calcularPorcentajeEstructuraGeneral($id_Gpo_Valores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT pe.id_Gpo_Valores_Padre, rl.titulo_Reporte, pe.id_Reporte_Padre, 
                cr.nombre_Reporte, pe.Porcentaje, pe.Cantidad, rl.id_Gpo_Valores_Reporte
                    FROM Proceso_Estructura pe
                    INNER JOIN Reportes_Llenados rl ON pe.id_Reporte_Padre = rl.id_Reporte
                    INNER JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                    WHERE rl.id_Gpo_Padre = $id_Gpo_Valores AND pe.id_Gpo_Valores_Padre = $id_Gpo_Valores 
                    AND rl.id_Status_Elemento = 1 AND pe.id_Status = 1 ORDER BY id_Reporte_Padre, titulo_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- ESTRUCTURA PROCESOS: Calcular Porcentaje de Estructuras De Registros---*/
    public function calcularPorcentajeAvanceDetalle($id_Gpo_Valores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT pe.id_Gpo_Valores_Padre, cr.nombre_Reporte, pe.id_Reporte_Padre, pe.Porcentaje, 
            rl.id_Gpo_Valores_Reporte, rl.titulo_Reporte,
            IFNULL(ROUND((SELECT SUM(pa1.Porcentaje) AS porcentaje FROM VW_getAllProcesosAvances pa1
                WHERE rl.id_Gpo_Valores_Reporte = pa1.Id_Gpo_Valores_Padre),2), 0) SUMA,
            IFNULL(ROUND((SELECT SUM(pa1.Porcentaje) AS porcentaje FROM VW_getAllProcesosAvances pa1 
                WHERE rl.id_Gpo_Valores_Reporte = pa1.Id_Gpo_Valores_Padre)*pe.Porcentaje/(100*pe.Cantidad),2), 0) Porcentaje1
            FROM Proceso_Estructura pe
                INNER JOIN Reportes_Llenados rl ON pe.id_Reporte_Padre = rl.id_Reporte
                INNER JOIN Cat_Reportes cr ON cr.id_Reporte = pe.id_Reporte_Padre
            WHERE rl.id_Gpo_Padre = $id_Gpo_Valores AND pe.id_Gpo_Valores_Padre = $id_Gpo_Valores AND rl.id_Status_Elemento = 1 
            AND pe.id_Status = 1 GROUP BY rl.id_Gpo_Valores_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /* ::::::::::::::::::::::::::::::::::::::::::::::::::: BUSQUEDA ::::::::::::::::::::::::::::::::::::::::::::::::: */
    /*--------------------------------- REPORTES LLENADOS: CONSULTAR REPORTES LLENADOS -------------------------------*/
    public function getAllReportesBusqueda($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllReportesLlenados WHERE id_Proyecto = $id_Proyecto 
                                    GROUP BY id_Reporte ORDER BY tipo_Reporte,nombre_Reporte ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*------------------------------------------- BUSQUEDA PALABRA CLAVE EN TTTULO -----------------------------------*/
    public function getBusquedaPalabraClaveTitulo($palabra_clave, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte,
            rl.Comentarios_Reporte, rl.id_Status_Elemento,
            rl.fecha_registro AS Fecha, rl.id_Usuario,
            rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte,
            rl.reporte_Modificado, cr.id_Proyecto
            FROM Reportes_Llenados rl 
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
            WHERE rl.titulo_Reporte LIKE '%$palabra_clave%' AND cr.id_Proyecto = $id_Proyecto 
            ORDER BY fecha_registro DESC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--------------------------------------------- BUSQUEDA PALABRA CLAVE EN TEXO -----------------------------------*/
    public function getBusquedaPalabraClave($palabra_clave, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte,
            rl.Comentarios_Reporte, rl.id_Status_Elemento, rl.fecha_registro AS Fecha,
            rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte,
            rl.reporte_Modificado, cr.Areas, cr.nombre_Reporte, cr.id_Proyecto, u.correo_Usuario, vrc.valor_Texto_Reporte
            FROM Reportes_Llenados rl
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
                LEFT JOIN Valores_Reportes_Campos vrc ON vrc.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
            WHERE vrc.valor_Texto_Reporte LIKE '%$palabra_clave%' AND cr.id_Proyecto = $id_Proyecto GROUP BY Id_Reporte
            ORDER BY fecha_registro DESC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*---------------------------------------------- BUSQUEDA ELEMENTOS ---------------------------------------------*/
    public function getAllBusquedaReporte($nombre_reporte, $fecha_inicio, $fecha_final, $Id_Area, $identificador_reporte, $proyecto, $tipo_Reporte)
    {
        $resultSet = array();
        $consulta = "CALL sp_QueryDinamic_Reportes('$nombre_reporte','$fecha_inicio','$fecha_final','$Id_Area',$identificador_reporte,$proyecto,'$tipo_Reporte')";
        $query = $this->db->query("$consulta");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES LLENADOS ---*/
    public function getAllReportesLlenadosByIdReporte($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllReportesLlenados WHERE id_Reporte = $id_Reporte 
                                    GROUP BY id_Gpo_Valores_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function saveConfiguracionReporte($id_Reporte, $json = null)
    {
        $query = $this->db->query("UPDATE Cat_Reportes SET conf_fields = '$json' WHERE id_Reporte = $id_Reporte");
        return $query;
    }

    public function getConfigReporte($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT conf_fields FROM Cat_Reportes WHERE id_Reporte = $id_Reporte");
        if ($query) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }


    /*------------------------------------------------ REPORTES LLENADOS  --------------------------------------------*/
    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR IDGPO---*/
    public function getAllReportesLlenadosByIdGpo($id_Gpo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  Reportes_Llenados WHERE id_Gpo_Valores_Reporte IN ($id_Gpo)");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR ID BITACORA DE SUPERVISION---*/
    public function getReporteLlenadoById($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllValoresReportes WHERE id_Gpo_Valores_Reporte = $id 
            AND id_Status_Reporte = 1");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR ID BITACORA DE SUPERVISION---*/
    public function getExistCampoSelectStatus($idGrupovalores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT vavr.valor_Texto_Reporte, vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
            LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
            WHERE vacrc.tipo_Reactivo_Campo = 'select-status' AND vavr.id_Gpo_Valores_Reporte = $idGrupovalores");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR ID BITACORA DE SUPERVISION---*/
    public function getExistCampoTipoIncidente($idGrupovalores)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
            LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
            WHERE vacrc.nombre_Campo = 'Tipo de Incidente' AND vavr.id_Gpo_Valores_Reporte = $idGrupovalores");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR IDGPO---*/
    public function getAllReportesLlenadosByIdGpoNotificaciones($id_Gpo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte, rl.id_Reporte, rl.id_Usuario, 
            rl.fecha_registro, rl.titulo_Reporte, B.valor_Texto_Reporte as campo_Tipo_Incidencia, eu.nombre AS nombre_Usuario,
            CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario, cr.nombre_Reporte
            FROM  Reportes_Llenados rl
            LEFT JOIN (SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                         LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                        WHERE vacrc.nombre_Campo = 'Tipo de Incidente'
                        ) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE rl.id_Gpo_Valores_Reporte = $id_Gpo");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR ID BITACORA DE SUPERVISION---*/
    public function getIdReportesConCamposFaltantes($ids, $id_Reporte, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllConfReportesCampos WHERE id_Proyecto = $id_Proyecto 
                            AND id_Reporte = $id_Reporte AND id_Configuracion_Reporte IN($ids) ORDER BY Secuencia ASC");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /** ---------------------------------------- SEGUIMIENTO REPORTES  --------------------------------------------- **/
    /*--- REPORTES LLENADOS: CONSULTAR REPORTES DE SEGUIMIENTOS DE INCIDENCIAS POR TIPO_REPORTE = 4 ---*/
    public function getAllReportesSeguimientoByTipoReporte($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes where tipo_Reporte = 4 AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES DE SEGUIMIENTOS DE INCIDENCIAS---*/
    public function getAllReportesSeguimiento($ids, $id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE  tipo_Reporte = 0 AND id_Reporte IN ($ids) 
                                    AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES DE INCIDENCIAS---*/
    public function getAllReportesIncidencia($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE  tipo_Reporte = 1 AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES POR ID_REPORTE---*/
    public function getAllCatReportesByIdReporte($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE id_Reporte = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- REPORTES LLENADOS: CONSULTAR REPORTES POR ID_REPORTE---*/
    public function getAllCatReportesUltimoIdReporte()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes where id_Status_Reporte = 1 ORDER BY id_Reporte DESC");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /********************************** FUNCIONES PARA MATRIZ DE COMUNICACION *****************************************/
    public function getAllCatReportesByTipoReporte($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE id_Reporte_Seguimiento != 0 AND id_Proyecto = $id_Proyecto");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function getAllCatReportesByTipoReporteSeguimiento($ids_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE id_Reporte IN($ids_Reporte)");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- SEGUIMIENTO REPORTES: CONSULTAR REPORTES POR TIPO REPORTE---*/
    public function getAllSeguimientoIncidencia($area, $usuario, $proyecto, $tipo_Reporte, $noreportes)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte, rl.fecha_registro AS Fecha,
            rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa,rl.fecha_registro AS Fecha2, 
            cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, cr.tipo_Reporte,u.correo_Usuario,
            eu.nombre as nombre_Usuario, eu.apellido_paterno, eu.apellido_materno
            FROM Reportes_Llenados rl
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE rl.id_Status_Elemento = 1 AND cr.id_Status_Reporte = 1 $noreportes AND cr.id_Proyecto = $proyecto 
            AND cr.tipo_Reporte IN ($tipo_Reporte) AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha2 DESC, Id_Reporte DESC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- SEGUIMIENTO REPORTES: CONSULTAR REPORTES PARA MOSTRAR EN LA PAPELERA---*/
    public function getAllReportesLlenadosPapelera($area, $proyecto, $tipo_Reporte, $status)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS id_Gpo_Valores_Reporte, 
        rl.fecha_registro AS Fecha, rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa, 
            cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, cr.tipo_Reporte,u.correo_Usuario,
            eu.nombre as nombre_Usuario, eu.apellido_paterno, eu.apellido_materno
            FROM Reportes_Llenados rl
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE rl.id_Status_Elemento = $status AND cr.id_Status_Reporte = 1 AND cr.id_Proyecto = $proyecto 
            AND cr.tipo_Reporte IN ($tipo_Reporte) AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha DESC, id_Gpo_Valores_Reporte DESC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    // OBTENER TODOS LOS TIPOS DE PLANTILLAS LLENADAS
    public function getAllTiposPlantillasLenados()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT clas_Reporte FROM Reportes_Llenados GROUP BY clas_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*--- SEGUIMIENTO REPORTES: CONSULTAR SEGUIMIENTOS (A PROCESOS REPORTES LIGADOS)---*/
    public function getAllSeguimientoProcesos($id_Padre)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte, rl.fecha_registro AS Fecha,
            rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa, IFNULL(A.valor_Texto_Reporte,rl.fecha_registro) AS Fecha2,
            B.valor_Texto_Reporte as campo_Hora, cr.nombre_Reporte, cr.tipo_Reporte, u.correo_Usuario, eu.nombre AS nombre_Usuario,
            CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario
            FROM Reportes_Llenados rl
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.nombre_Campo = 'Fecha'
                ) A ON A.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.nombre_Campo = 'Hora'
                ) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
                LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
               LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE rl.id_Status_Elemento = 1 AND id_Gpo_Padre = $id_Padre");
        if ($query) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    public function getAllSeguimientoReporteIncidencia($area, $proyecto, $tipo_Reporte, $noreportes)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte, rl.fecha_registro AS Fecha,
            rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa, IFNULL(B.valor_Texto_Reporte,rl.fecha_registro) AS Fecha2,
            cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, cr.tipo_Reporte, cr.id_Reporte_Seguimiento, u.correo_Usuario,
            eu.nombre AS nombre_Usuario, CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
            IFNULL(C.valor_Texto_Reporte, rl.titulo_Reporte) as campo_TipoIncidente, D.valor_Texto_Reporte as campo_Hora,
            E.valor_Texto_Reporte as campo_EstadoReporte
            FROM Reportes_Llenados rl 
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.nombre_Campo = 'Fecha'
                ) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.nombre_Campo = 'Tipo de Incidente'
                ) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.nombre_Campo = 'Hora'
                ) D ON D.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN (
                    SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                        LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                    WHERE vacrc.tipo_Reactivo_Campo = 'select-status'
                ) E ON E.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                LEFT JOIN Cat_Reportes cr ON ((`cr`.`id_Reporte` = rl.`id_Reporte`))
                LEFT JOIN Usuarios u ON ((`u`.`id_Usuario` = rl.`id_Usuario`))
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE rl.id_Status_Elemento = 1 AND cr.id_Status_Reporte = 1 $noreportes AND cr.id_Proyecto = $proyecto 
            AND cr.tipo_Reporte IN ($tipo_Reporte) AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha2 DESC, Id_Reporte DESC");
        if ($query) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    public function getAllSeguimientoReporteIncidencia2($area, $proyecto, $tipo_Reporte)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte, rl.fecha_registro AS Fecha, 
        rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa, cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, 
        cr.tipo_Reporte, cr.id_Reporte_Seguimiento, u.correo_Usuario, eu.nombre AS nombre_Usuario,
        CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
        IFNULL(C.valor_Texto_Reporte, rl.titulo_Reporte) as campo_TipoIncidente
        FROM Reportes_Llenados rl 
            LEFT JOIN (
                SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                    LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                        WHERE vacrc.nombre_Campo = 'Tipo de Incidente'
                ) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
            LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
            LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
            LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
        WHERE rl.id_Status_Elemento = 1 AND cr.id_Status_Reporte = 1 AND cr.id_Proyecto = $proyecto AND cr.tipo_Reporte IN ($tipo_Reporte) 
        AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha DESC, Id_Reporte DESC");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    //***************************************** MODULO DOCUMENTOS BIM **************************************************
    public function getAllPlanosByTituloReporte($area, $proyecto, $tipo_Reporte, $tituloReporte)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte AS Id_Reporte, rl.fecha_registro AS Fecha,
        rl.id_Usuario, rl.id_Reporte AS id_Reporte2, rl.titulo_Reporte, rl.id_Etapa, IFNULL(B.valor_Texto_Reporte,rl.fecha_registro) AS Fecha2,
        C.valor_Texto_Reporte AS Hora, cr.id_Proyecto, cr.Areas, cr.nombre_Reporte, cr.tipo_Reporte, cr.id_Reporte_Seguimiento,
        u.correo_Usuario, e.nombre AS nombre_Usuario, CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
        D.valor_Entero_Reporte as numeroPlano
        FROM Reportes_Llenados rl 
            LEFT JOIN (
                SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                    LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                WHERE vacrc.nombre_Campo = 'Fecha'
            ) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
            LEFT JOIN (
                SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                    LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                WHERE vacrc.nombre_Campo = 'Hora'
            ) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
            LEFT JOIN (
                SELECT vavr.valor_Entero_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
                    LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
                WHERE vacrc.nombre_Campo = 'Número-Documento'
            ) D ON D.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
            LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
            LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
            LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
         WHERE rl.id_Status_Elemento = 1 AND cr.id_Proyecto = $proyecto AND cr.tipo_Reporte IN ($tipo_Reporte)
         AND rl.titulo_Reporte = '$tituloReporte' AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha2 DESC, Id_Reporte DESC");
        if ($query) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    public function getAllDatosByReportePlanos($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Gpo_Valores_Reporte as id_Gpo_Valores_Reporte,
                            rl.id_Reporte as id_Reporte,
                            rl.titulo_Reporte as titulo_Reporte,
                            rl.fecha_registro as fecha_registro,
                            rl.clas_Reporte as clas_Reporte,
                            cr.nombre_Reporte as nombre_Reporte,
                            cr.id_Proyecto as id_Proyecto
                            FROM Reportes_Llenados rl
                            LEFT JOIN Cat_Reportes cr
                            ON ((`cr`.`id_Reporte` = rl.`id_Reporte`)) 
                            WHERE clas_Reporte = 5 AND rl.id_Status_Elemento = 1 AND cr.id_Proyecto = $id_Proyecto
                            GROUP BY titulo_Reporte ORDER BY fecha_registro");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    //CONSULTA PARA OBTENER EL ULTIMO PDF DEL REPORTE BY ID AND TITUTLO
    public function getidReporteMaxPlanos($id_Reporte, $titulo_Reporte)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT max(id_Gpo_Valores_Reporte) FROM Reportes_Llenados 
                where clas_Reporte = 5 AND id_Status_Elemento = 1 AND id_Reporte = $id_Reporte 
                AND titulo_Reporte = '$titulo_Reporte'");
        if ($row = $query->fetch_row()) {
            $resultSet = trim($row[0]);
        }
        $query->close();
        return $resultSet;
    }

    //CONSULTA PARA OBTENER EL ULTIMO PDF DEL REPORTE BY ID AND TITUTLO
    public function getReporteByProyectoAndTipoReporteDocBim($id_Proyecto)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE id_Proyecto = $id_Proyecto AND tipo_Reporte = 5");
        if ($row = $query->fetch_row()) {
            $resultSet = trim($row[0]);
        }
        $query->close();
        return $resultSet;
    }

    //OBTENER REGISTRO DE PLANTILLA TIPO 6 (ASISTENCIA)
    public function getPlantillaByIdProyectoAndTipoReporte($id_Proyecto, $tipo_Reporte)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM Cat_Reportes WHERE id_Proyecto = $id_Proyecto AND tipo_Reporte = $tipo_Reporte");
        if ($row = $query->fetch_row()) {
            $resultSet = trim($row[0]);
        }
        $query->close();
        return $resultSet;
    }
    //***************************************** MODULO DOCUMENTOS BIM *********************************************** */


    /*--- REPORTES LLENADOS: CONSULTAR REPORTE LLENADO POR ID $id_Gpo_Valores_Reporte para Seguimiento Incidencia ---*/
    public function getAllDatosReporteLlenado($id_Gpo_Valores_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Registro_Reporte, rl.id_Gpo_Valores_Reporte, rl.id_Reporte, rl.id_Usuario, rl.fecha_registro,
rl.titulo_Reporte, rl.clas_Reporte, A.valor_Texto_Reporte as campo_Fecha, IFNULL(B.valor_Texto_Reporte, rl.titulo_Reporte) as campo_Tipo_Incidente, 
eu.nombre AS nombre_Usuario,
  CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
cr.nombre_Reporte, cr.id_Reporte_Seguimiento, C.valor_Texto_Reporte as campo_EstadoReporte
FROM  Reportes_Llenados rl
LEFT JOIN (
SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
  LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
  WHERE vacrc.nombre_Campo = 'Fecha'
) A ON A.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
LEFT JOIN (
SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
  LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
  WHERE vacrc.nombre_Campo = 'Tipo de Incidente'
) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
LEFT JOIN (
SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
  LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
  WHERE vacrc.tipo_Reactivo_Campo = 'select-status'
) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
    LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
    LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte 
    LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
WHERE rl.id_Gpo_Valores_Reporte = $id_Gpo_Valores_Reporte");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    //************************** OBTENER TODOS LOS ELEMENTOS DE UBI AND INVE*****************************************
    public function getAllSeguimientoElementosByUbiAndInv($area, $id_Proyecto, $id_Reporte)
    {
        $resultSet = array();
        $area2 = "[[:<:]]" . $area . "[[:>:]]";
        $query = $this->db->query("SELECT 
  rl.id_Registro_Reporte,
  rl.id_Gpo_Valores_Reporte AS Id_Reporte,
  rl.fecha_registro AS Fecha,
  rl.id_Usuario,
  rl.id_Reporte AS id_Reporte2,
  rl.titulo_Reporte,
  IFNULL(B.valor_Texto_Reporte,rl.fecha_registro) AS Fecha2,
  cr.id_Proyecto,
  cr.Areas,
  cr.nombre_Reporte,
  cr.tipo_Reporte,
  u.correo_Usuario,
  eu.nombre AS nombre_Usuario,
  CONCAT(eu.apellido_paterno,' ', IFNULL(eu.apellido_materno, '')) AS apellido_Usuario,
  C.valor_Texto_Reporte AS descripcion
  FROM Reportes_Llenados rl 
  LEFT JOIN (
SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
  LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
  WHERE vacrc.nombre_Campo = 'Fecha'
) B ON B.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
    LEFT JOIN (
SELECT vavr.valor_Texto_Reporte,vavr.id_Gpo_Valores_Reporte FROM VW_getAllValoresReportes vavr
  LEFT JOIN VW_getAllConfReportesCampos vacrc ON vacrc.id_Configuracion_Reporte = vavr.id_Configuracion_Reporte
  WHERE vacrc.nombre_Campo = 'Descripción'
) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
    LEFT JOIN Cat_Reportes cr ON cr.id_Reporte = rl.id_Reporte
    LEFT JOIN Usuarios u ON u.id_Usuario = rl.id_Usuario
LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
  WHERE rl.id_Status_Elemento = 1 AND cr.id_Proyecto = $id_Proyecto AND cr.tipo_Reporte IN (2,3) AND rl.id_Reporte = $id_Reporte
  AND cr.Areas RLIKE \"$area2\" ORDER BY Fecha2 DESC;");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    //************************** END OBTENER TODOS LOS ELEMENTOS DE UBI AND INVE*****************************************


    /*--- SEGUIMIENTO UBICACIONES: CONSULTAR SEGUIMIENTOS UBICACIONES---*/
    public function getAllSeguimientoUbicacion($proyecto, $area, $tipo_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllReportesLlenados WHERE id_Proyecto = $proyecto 
AND tipo_Reporte = 2 ORDER BY titulo_Reporte ASC");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }


    /*--- SEGUIMIENTO UBICACIONES: MAPA---*/
    public function getUbicacionesmapa($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM(
  SELECT
  rl.tipo_Reporte AS tipo_Reporte,
  rl.id_Proyecto AS id_Proyecto,
  rl.id_Gpo_Valores_Reporte AS id_Gpo_Valores_Reporte,
  rl.id_Reporte AS id_Reporte,
  rl.titulo_Reporte AS titulo_Reporte,
  rl.nombre_Reporte AS nombre_Reporte,
  rl.latitud_Reporte AS latitud_Reporte,
  rl.longitud_Reporte AS longitud_Reporte,
  rl.Identificador AS Identificador,
  rl.fecha_registro AS fecha_registro,
  F.valor_Texto_Reporte AS fechaValores,
  C.valor_Texto_Reporte AS cadenamientoValores,
  CP.valor_Texto_Reporte AS cuerpoValores,
  OB.valor_Texto_Reporte AS observacionesValores
    FROM VW_getAllReportesLlenados rl
   LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 1) F ON F.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
   LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 15) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
   LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 16) CP ON CP.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
   LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 17) OB ON OB.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
   ) RES WHERE RES.tipo_Reporte = 2 AND RES.latitud_Reporte != 0 AND RES.id_Proyecto = $id_Proyecto ORDER BY RES.id_Reporte");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- SEGUIMIENTO REPORTES: MAPA---*/
    public function getUbicacionesmapaReportes($id_Proyecto, $id_Usuario, $fecha)
    {
        $resultSet = array();
        $query1 = "SELECT * FROM(
            SELECT rl.tipo_Reporte AS tipo_Reporte, rl.id_Proyecto AS id_Proyecto,
            rl.id_Gpo_Valores_Reporte AS id_Gpo_Valores_Reporte, rl.id_Reporte AS id_Reporte,
            rl.titulo_Reporte AS titulo_Reporte, rl.id_Usuario AS id_Usuario, rl.nombre_Reporte AS nombre_Reporte,
            rl.nombre_Usuario AS nombre_Usuario, rl.apellido_Usuario AS apellido_Usuario,
            rl.latitud_Reporte AS latitud_Reporte, rl.longitud_Reporte AS longitud_Reporte,
            rl.Identificador AS Identificador, rl.fecha_registro AS fecha_registro,
            F.valor_Texto_Reporte AS fechaValores
            FROM VW_getAllReportesLlenados rl
                LEFT JOIN (
                    SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte 
                            FROM Valores_Reportes_Campos vrc 
                       LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte 
                    WHERE crc.id_Campo_Reporte = 1) F ON F.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                ) RES WHERE RES.tipo_Reporte = 0 AND RES.latitud_Reporte != 0 AND RES.id_Proyecto = $id_Proyecto 
            AND RES.id_Usuario IN ($id_Usuario) $fecha ORDER BY RES.fecha_registro";
        $query = $this->db->query($query1);
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    public function getAllSeguimientoUbicacionInventario($proyecto, $area, $tipo_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Gpo_Valores_Reporte,rl.id_Reporte,rl.titulo_Reporte,rl.clas_Reporte,rl.id_Status_Elemento,cr.id_Proyecto FROM Reportes_Llenados rl
 LEFT JOIN `Cat_Reportes` `cr`
    ON ((`cr`.`id_Reporte` = `rl`.`id_Reporte`))
     WHERE cr.id_Proyecto = $proyecto AND clas_Reporte IN (0,2,3,6) AND rl.id_Status_Elemento = 1
 ORDER BY titulo_Reporte ASC");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- SEGUIMIENTO UBICACIONES POR GRUPO: CONSULTAR SEGUIMIENTOS UBICACIONES POR GRUPO---*/
    public function getAllSeguimientoUbicacionInventarioGpoSistema($proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Reporte,rl.id_Status_Elemento,cr.id_Proyecto,cr.nombre_Reporte
FROM Reportes_Llenados rl
LEFT JOIN `Cat_Reportes` `cr`
    ON ((`cr`.`id_Reporte` = `rl`.`id_Reporte`))
WHERE cr.id_Proyecto = $proyecto 
AND clas_Reporte IN (0,2,3,6) AND rl.id_Status_Elemento = 1 GROUP BY id_Reporte ORDER BY nombre_Reporte ASC");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*--- SEGUIMIENTO ELEMENTOS: CONSULTAR UBICACIONES ---*/
    public function getAllUbicacionesKML($id_Proyecto)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllReportesLlenados WHERE tipo_Reporte = 2 
        AND id_Proyecto = $id_Proyecto");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        return $resultSet;
    }



    /*----------------------------------------------- SEGUIMIENTO REPORTES LLENADOS ------------------------------------------*/
    /*--- SEGUIMIENTO REPORTES: CONSULTAR SEGUIMIENTOS ---*/
    public function getAllSeguimientoReporteLlenado($area, $usuario, $proyecto, $estatus)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT id_Gpo_Valores_Reporte AS Id_Reporte, nombre_Reporte, titulo_Reporte, 
fecha_registro, correo_Usuario, id_Reporte AS id_Reporte2
		FROM  VW_getAllReportesLlenados WHERE id_Usuario = $usuario AND id_Status_Elemento = 0 
		GROUP BY id_Gpo_Valores_Reporte ORDER BY fecha_registro DESC");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }


    /*--- SEGUIMIENTO REPORTES: CONSULTAR SEGUIMIENTOS POR ID DE REPORTE ---*/
    public function getAllSeguimientoReporteById($id, $Id_Usuario)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT MAX(Id_Status) AS Id_Status FROM  VW_getAllSeguimientoReportes 
WHERE Id_Reporte IN ($id)");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
        //

        return $resultSet;
    }

    /*--- SEGUIMIENTO REPORTES: CONSULTAR SEGUIMIENTOS POR ID GPO ---*/
    public function getAllSeguimientoReporteByIdGpo($idGpo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM  VW_getAllSeguimientoReportes WHERE Id_Reporte IN ($idGpo)");
        //$query = $this->db->query("SELECT @first, @last");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::COMENTARIOS:::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*--- reportes: CONSULTAR COMENTARIOS DE REPORTES---*/
    public function getAllComentariosReporte($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllComentariosReportes WHERE id_Gpo = $id 
        ORDER BY Fecha_Comentario ASC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    // OBTENER ULTMO COMENTARIO REGISTRADO
    public function getAllValorMaxComentarios()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT count(id_comentario) as cantidadComentario FROM Comentarios_Reportes");
        if ($row = $query->fetch_row()) {
            $resultSet = trim($row[0]);
        }
        $query->close();
        return $resultSet;
    }

    /*--- reportes: CONSULTAR COMENTARIOS DE REPORTES---*/
    public function consultarFotoComentario($id)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllFotografias vaf WHERE vaf.identificador_Fotografia = $id 
            AND vaf.id_Modulo = 7");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }

    /*---------------------------------- REPORTES: CONSULTAR CAT MONITOREO DIARIO ------------------------------------*/
    public function getAllCatMonitoreo()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatMonitoreo");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    public function getLastCatMonitoreo()
    {
        $resultSet = "";
        $query = $this->db->query("SELECT MAX(idCatMonitoreo) AS id FROM Cat_Monitoreo_Diario;");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }

    public function getLastCatCategoria()
    {
        $resultSet = "";
        $query = $this->db->query("SELECT MAX(idCatalogo) AS id FROM Catalogo_categoria;");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }

    public function getLastProyecto()
    {
        $resultSet = "";
        $query = $this->db->query("SELECT MAX(id_Proyecto) AS id_Max FROM Proyectos;");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }

    public function getAllCatMonitoreoById($idCatMonitoreo)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCatMonitoreo WHERE idCatMonitoreo = $idCatMonitoreo");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet = $row;
            }
            $query->close();
        }
        return $resultSet;
    }

    /*------------------------------------ REPORTES: CONSULTAR CATALOGO CATEGORIA ------------------------------------*/
    public function getCatCategoriaByIdCategoria($ids)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatalogo_categoria WHERE id_Categoria IN ($ids) ORDER BY id_Categoria DESC, concepto ASC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    public function getCatCategoriaGroupByCategoria()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatalogo_categoria GROUP BY id_Categoria ORDER BY id_Categoria");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    public function getCatCategoriaByIdCategoriaAndCatalogo($ids, $idCatalogo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatalogo_categoria WHERE id_Categoria IN ($ids) and idCatalogo = $idCatalogo ORDER BY id_Categoria DESC, concepto ASC");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    /*---------------------------------- REPORTES: CONSULTAR NUMERO DE NOTA ------------------------------------*/
    public function getNumeroNota($id_Reporte, $id_Configuracion)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT MAX(vavr.valor_Texto_Reporte) FROM VW_getAllValoresReportes vavr 
        WHERE vavr.id_Reporte = $id_Reporte AND vavr.id_Configuracion_Reporte = $id_Configuracion 
        AND vavr.id_Status_Elemento = 1 
        ORDER BY vavr.id_Gpo_Valores_Reporte ASC");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }


    public function getCatCategoriaByIdCatalogo($ids)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatalogo_categoria WHERE idCatalogo = $ids");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }

    public function getAllDepositos($fechainicio, $fechafin)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Depositos WHERE depFecha >= '$fechainicio' AND depFecha <= '$fechafin' ");
        if ($query != FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
            $query->close();
        }
        //

        return $resultSet;
    }


    /*--- METER VALORES REPORTES--*/
    public function consultarValoresAEditar($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT rl.id_Gpo_Valores_Reporte,rl.fecha_registro FROM Reportes_Llenados rl
            WHERE rl.id_Status_Elemento = 0 AND rl.id_Reporte = $id_Reporte");
        //$query = $this->db->query("SELECT @first, @last");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();

        return $resultSet;
    }


    /* ::::::::::::::::::::::::::::::::::::::::::::: CAT DOCUMENTOS :::::::::::::::::::::::::::::::::::::::: */
    /*------------------------------------------ CONSULTAR CATALOGO -----------------------------------------*/
    public function getAllCatDocumentos()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllCatDocs");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*------------------------------------------ CONSULTAR GPO -----------------------------------------*/
    public function getAllRegistro()
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM VW_getAllRegistro");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    /*------------------------------------------ CONSULTAR CATALOGO -----------------------------------------*/
    public function getCatDocById($id)
    {
        $resultSet = "";
        $query = $this->db->query("SELECT * FROM VW_getAllCatDocs WHERE id_Cat_Documento = $id");
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*--- CONFIGURACION FORMULARIO: CONSULTAR CONFIGURACION DE FORMULARIO---*/
    public function get_AllConfById($id_Reporte)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM conf_Formulario WHERE id_Reporte = $id_Reporte");
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }


    /*------------------ OBTENER FIRMA POR ID DE USUARIO-----------------*/
    public function getFirmaByIdUser($id_user, $id_Gpo)
    {
        $resultSet = array();
        $query = $this->db->query("SELECT * FROM Firma WHERE id_Usuario = $id_user AND id_Gpo = $id_Gpo");

        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    public function obtenerDatosPorGantt()
    {
        $resultado = [];
        $query = "SELECT gv.actividad,gv.id_nodo,gv.id_nodo_padre,gv.porcentaje,gv.inicio,gv.fin,gv.connect_to
            from gantt g join gantt_valores gv on g.id = gv.id_gantt and g.id_proyecto = $this->Proyecto_Id_Sesion";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }


    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    // ****************************************** NUEVO ESQUEMA PARA ESTRUCTURA ****************************************
    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

    //OBTENER VALORES RELACIONADOS AL id_nodo_padre
    public function getAllReportesLlenadosAvanceActividad($id_nodo_padre, $idGantt)
    {
        $resultado = [];
        $query = "SELECT gv.actividad,gv.id_nodo,gv.id_nodo_padre,gv.id_reporte, aa.id_nodo, aa.gpo_valores, gv.porcentaje
                    FROM gantt_valores gv
                    INNER JOIN avance_actividad aa ON gv.id_nodo = aa.id_nodo
                    WHERE gv.id_nodo_padre = $id_nodo_padre AND aa.id_status = 1 AND gv.id_gantt = $idGantt";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES RELACIONADOS AL id_nodo_padre
    public function getSubNodos($idNodoPadre = 0)
    {
        $resultado = [];
        $query = "SELECT id_nodo, actividad, wbs, inicio, fin, id_nodo_padre, porcentaje, connect_to, id_reporte 
                    FROM gantt g 
                        JOIN gantt_valores gv ON g.id =gv.id_gantt AND g.id_proyecto = $this->Proyecto_Id_Sesion 
                    AND gv.id_nodo_padre = $idNodoPadre AND gv.id_status = 1;";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES RELACIONADOS AL id_nodo_padre
    public function getRegistroAvanceActividad($id_Gpo_Valores, $id_Proyecto)
    {
        $resultado = [];
        $query = "SELECT * FROM avance_actividad where gpo_valores = $id_Gpo_Valores AND id_proyecto = $id_Proyecto";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    public function getGpoValoresByIdNodo($idNodo)
    {
        $resultado = [];
        $query = "SELECT * FROM avance_actividad where id_nodo = $idNodo AND id_proyecto = $this->Proyecto_Id_Sesion";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES de GANTT BY $id_proyecto
    public function getIdGanttByid_proyecto($id_proyecto)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt WHERE id_proyecto = $id_proyecto AND status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES de gantt_valores BY id_gantt AND id_nodo_padre AND id_reporte
    public function getRegistroGanttValoresByid_ganttANDid_nodo_padreANDid_reporte($id_gantt, $id_nodo_padre, $id_reporte)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores where id_gantt = $id_gantt AND id_nodo_padre = $id_nodo_padre AND id_reporte = $id_reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }


    //OBTENER VALORES de gantt_valores BY id_gantt AND id_reporte
    public function getRegistroGanttValoresByid_ganttANDid_reporte($id_gantt, $id_reporte)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores where id_gantt = $id_gantt AND id_reporte = $id_reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES de gantt_valores BY id_gantt AND $id_nodo_Padre
    public function getRegistroGanttValoresByid_ganttANDid_nodo_Padre($id_gantt, $id_nodo_Padre, $paramExtra = '')
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores where id_gantt = $id_gantt AND id_nodo_Padre = $id_nodo_Padre $paramExtra ORDER BY id_reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    //OBTENER VALORES de gantt_valores BY id_gantt AND $id_nodo
    public function getRegistroGanttValoresByid_ganttANDid_nodo($id_gantt, $id_nodo)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores WHERE id_gantt = $id_gantt AND id_nodo = $id_nodo ORDER BY id_reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS ID_REPORTE DE GANTT_VALORES
    public function getAllRegistrosGanttValoresId_Reporte($id_gantt)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores where id_gantt = $id_gantt AND id_reporte GROUP BY id_reporte ORDER BY id_reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DE CAT_REPORTES POR NOMBRE REPORTE
    public function getRegistroCatReportesByNombreReporte($nombreReporte, $id_Proyecto)
    {
        $resultado = [];
        $query = "SELECT * FROM Cat_Reportes WHERE nombre_Reporte = '$nombreReporte' AND id_Proyecto = $id_Proyecto";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DE CAT_REPORTES POR NOMBRE REPORTE
    public function getAllReportesLlenadosByIdsReporteActivityOrOther($id_Proyecto, $idsReporte)
    {
        $resultado = [];
        $query = "SELECT rl.id_Reporte,rl.id_Status_Elemento,cr.id_Proyecto,cr.nombre_Reporte
                    FROM Reportes_Llenados rl
                        LEFT JOIN `Cat_Reportes` `cr` ON ((`cr`.`id_Reporte` = `rl`.`id_Reporte`))
                    WHERE cr.id_Proyecto = $id_Proyecto AND rl.id_Reporte IN ($idsReporte) AND rl.id_Status_Elemento = 1 
                    GROUP BY id_Reporte ORDER BY nombre_Reporte ASC";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // VALIDAR SI EXISTE REGISTRO A TRAVES DE PROCESO AUTOMATICO
    public function getRegistroLlenadoByTituloReporteAndidReporte($tituloReporte, $idReporte)
    {
        $resultado = [];
        $query = "SELECT * FROM Reportes_Llenados WHERE titulo_Reporte = '$tituloReporte' AND id_Reporte = $idReporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // VALIDAR SI EXISTE REGISTRO A TRAVES DE PROCESO AUTOMATICO
    public function getAllNodosLLenados($idReporte)
    {
        $resultado = [];
        $query = "SELECT * FROM avance_actividad aa
                    LEFT JOIN Reportes_Llenados rl ON rl.id_Gpo_Valores_Reporte = aa.gpo_valores 
                    where rl.id_Reporte = $idReporte GROUP BY id_Gpo_Valores_Reporte";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER INFORMACION DE ESTRUCTURA DE GANNT EN JSON
    public function getJson($idGantt)
    {
        $resultado = [];
        $query = "SELECT estructura FROM gantt WHERE id = $idGantt";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER INFORMACION DE REGISTROS DE gantt_valores A TRAVES DEL WBS
    public function getRegistroGanttValoresByIdGanttAndWBS($idGantt, $wbs)
    {
        $resultado = [];
        $query = "SELECT * FROM gantt_valores WHERE id_gantt = $idGantt AND wbs = '$wbs'";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER EL ULTIMO id_nodo DE gantt_valores DEL id_gantt
    public function getUltimoRegistroIdNodoByIdGantt($idGantt)
    {
        $resultado = [];
        $query = "SELECT MAX(id_nodo) AS id_nodo FROM gantt_valores WHERE id_gantt = $idGantt";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // ****************************************** NUEVO ESQUEMA PARA ESTRUCTURA ************************************* */


    // ********************************************* MODULO DE EMPLEADOS ***********************************************
    // OBTENER TODOS LOS REGISTROS DE EMPLEADOS
    public function getAllEmpleados()
    {
        $resultado = [];
        $query = "SELECT * FROM VW_getAllEmpleados ORDER BY nombre, apellido_paterno";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DE EMPLEADO BY ID_EMPLEADO
    public function getAllEmpleadosByIdEmp($id_empleado)
    {
        $resultado = [];
        $query = "SELECT * FROM VW_getAllEmpleados WHERE id_empleado = $id_empleado";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER ULTIMO id_empleado REGISTRADO DE EMPLEADOS
    public function getUltimoRegistroEmpleado()
    {
        $query = $this->db->query("SELECT MAX(id_empleado) AS id_empleado  FROM empleados WHERE status = 1 ORDER BY id_empleado DESC");
        if ($row = $query->fetch_row()) {
            $id = trim($row[0]);
        }
        return $id;
    }

    // OBTENER TODOS LOS REGISTROS DE EMPLEADOS SIN CUENTA PARA ACCEDER A LA PLATAFORMA
    public function getAllEmpleadosSinCuenta($idsEmpleadosBloquear)
    {
        $resultado = [];
        $query1 = "SELECT * FROM VW_getAllEmpleadoUsuario WHERE id_usuario NOT IN ($idsEmpleadosBloquear) ORDER BY nombre, apellido_paterno";
        $query = $this->db->query($query1);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS REGISTROS DE EMPLEADOS BY IN(id_empleado)
    public function getAllEmpleadosByInIdEmpleados($idsEmpleados)
    {
        $resultado = [];
        $query = "SELECT e.id_empleado, eu.nombre, CONCAT(eu.apellido_paterno, ' ', IFNULL(eu.apellido_materno, '')) AS apellidos  
            FROM empleados e
            LEFT JOIN empleados_usuarios eu ON eu.id_empleado = e.id_empleado 
            WHERE e.id_empleado IN($idsEmpleados)";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS REGISTROS DE EXPEDIENTES DEL EMPLEADO
    public function getAllExpedientesByIdEmp($idEmpleado)
    {
        $resultado = [];
        $query = "SELECT * FROM empleados_expediente WHERE id_emp = $idEmpleado AND status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS REGISTROS DE EXPEDIENTES DEL EMPLEADO
    public function getRegistroExpedienteById($id)
    {
        $resultado = [];
        $query = "SELECT * FROM empleados_expediente WHERE id = $id";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS REGISTROS DE EMPLEADOS
    public function getAllEmpleadosRestaurar()
    {
        $resultado = [];
        $query = "SELECT * FROM empleados e 
                LEFT JOIN empleados_usuarios eu ON eu.id_empleado = e.id_empleado 
            WHERE e.status = 0 ORDER BY eu.nombre, eu.apellido_paterno";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DE USUARIO SI EL EMPLEADO ES USUARIO
    public function getAllDatosEmpleadoUsuario($idEmpleado)
    {
        $resultado = [];
        $query = "SELECT * FROM empleados_usuarios WHERE id_empleado = $idEmpleado";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS EMPLEADOS CON ID Y NOMBRE UNICAMENTE
    public function getAllEmpleadosWithIdAndName()
    {
        $resultado = [];
        $query = "SELECT e.id_empleado as id, CONCAT(eu.nombre, ' ', eu.apellido_paterno, ' ', eu.apellido_materno) AS nombre 
            FROM empleados e
                LEFT JOIN empleados_usuarios eu ON eu.id_empleado = e.id_empleado
            WHERE e.status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // ********************************************* MODULO DE EMPLEADOS ********************************************* */


    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    // *********************************** FUNCIONES PARA SECCION DE ASISTENCIA ****************************************
    // OBTENER TODOS LOS REGISTROS DE EXPEDIENTES DEL EMPLEADO
    public function getAllEmpleadosByIdGpoValores($idGpoValores)
    {
        $resultado = [];
        $query = "SELECT * FROM asistencia WHERE id_gpo_valores_reporte = $idGpoValores";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DEL EMPLEADO (VERIFICYAR SI EXISTE)
    public function getEmpleadoByIdGpoValores($idGpoValores, $idEmpleado, $fechaAsistencia)
    {
        $resultado = [];
        $query = "SELECT * FROM asistencia WHERE id_gpo_valores_reporte = $idGpoValores 
                    AND id_emp = $idEmpleado AND fecha = '$fechaAsistencia'";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DEL EMPLEADO (VERIFICYAR SI EXISTE)
    public function getEmpleadoByIdEmpleadoAndFecha($idEmpleado, $fechaAsistencia)
    {
        $resultado = [];
        $query = "SELECT * FROM asistencia WHERE id_emp = $idEmpleado AND fecha = '$fechaAsistencia' AND id_status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DEL EMPLEADO (VERIFICAR SI EXISTE)
    public function getAllEmpleadosInAsistenciaByIdProyectoAndRangoFechas($idProyecto, $fechaInicial, $fechaFinal)
    {
        $resultado = [];
        $query = "SELECT a.id_emp, a.proyecto_asignado, e.id_proyecto, IFNULL(p.nombre_Proyecto, 'Sin proyecto') as nombre_Proyecto, 
            e.no_empleado, eu.nombre, CONCAT(eu.apellido_paterno, ' ', IFNULL(eu.apellido_materno, '')) AS apellidos
	        FROM asistencia a
		        LEFT JOIN empleados e ON e.id_empleado = a.id_emp 
		        LEFT JOIN empleados_usuarios eu ON eu.id_empleado = e.id_empleado
		        LEFT JOIN Proyectos p ON p.id_Proyecto = e.id_proyecto
	        WHERE proyecto_asignado IN($idProyecto) AND fecha >= '$fechaInicial' AND fecha <= '$fechaFinal' GROUP BY id_emp";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DEL EMPLEADO (VERIFICYAR SI EXISTE)
    public function getAllAsistenciaByIdProyectoAndRangoFechas($idEmpleado, $idProyecto, $fechaInicial, $fechaFinal)
    {
        $resultado = [];
        $query = "SELECT a.incidencia, a.fecha, a.proyecto_asignado, p.nombre_Proyecto as nombre_proyecto 
                FROM asistencia a 
                    LEFT JOIN Proyectos p ON p.id_Proyecto = a.proyecto_asignado
                WHERE id_emp = $idEmpleado AND proyecto_asignado IN($idProyecto) AND fecha >= '$fechaInicial' 
                AND fecha <= '$fechaFinal' AND id_status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER REGISTRO DE ASISTENCIA POR IDGRUPOVALORES (VERIFICYAR SI EXISTE)
    public function getAllAsistenciaByIdGpoValores($id_Gpo_Valores_Reporte)
    {
        $resultado = [];
        $query = "SELECT * FROM asistencia WHERE id_gpo_valores_reporte = $id_Gpo_Valores_Reporte AND id_status = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // *********************************** FUNCIONES PARA SECCION DE ASISTENCIA ************************************* */
    // *****************************************************************************************************************


    // *****************************************************************************************************************
    // ********************************************* SECCION DE PARTICIPANTES ******************************************
    // *****************************************************************************************************************

    // OBTENER TODOS LOS PORTICIPANTES
    public function getAllParticipantes()
    {
        $resultado = [];
        $query = "SELECT u.id_Usuario as id, CONCAT(eu.nombre, ' ', eu.apellido_paterno, ' ', eu.apellido_materno) AS nombre,
            u.correo_Usuario, u.puesto, u.empresa
            FROM Usuarios u
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario = 2 AND u.participante = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER UN PARTICIPANTES POR ID PARA VALIDAR QUE EXISTA
    public function getParticipanteExistById($id)
    {
        $resultado = [];
        $query = "SELECT u.id_Usuario as id FROM Usuarios u
            WHERE u.id_Status_Usuario != 0 AND u.participante = 1 AND u.id_Usuario = $id";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER UN PARTICIPANTES POR ID
    public function getParticipanteById($id)
    {
        $resultado = [];
        $query = "SELECT u.id_Usuario as id, eu.nombre, eu.apellido_paterno, eu.apellido_materno, u.correo_Usuario, 
            u.puesto, u.empresa
            FROM Usuarios u
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario != 0 AND u.participante = 1 AND u.id_Usuario = $id";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS PORTICIPANTES (UNICAMENTE ID Y NOMBRE)
    public function getAllParticipantesIdAndName()
    {
        $resultado = [];
        $query = "SELECT u.id_Usuario as id, CONCAT(eu.nombre, ' ', eu.apellido_paterno, ' ', eu.apellido_materno) AS nombre
            FROM Usuarios u
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario IN (1,2) AND u.participante = 1";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS PORTICIPANTES (UNICAMENTE ID Y NOMBRE) BY IDS
    public function getAllParticipantesIdAndNameByIds($ids)
    {
        $resultado = [];
        $query = "SELECT u.id_Usuario as id, CONCAT(eu.nombre, ' ', eu.apellido_paterno, ' ', eu.apellido_materno) AS nombre,
            u.correo_Usuario, u.puesto, u.empresa
            FROM Usuarios u
                LEFT JOIN empleados_usuarios eu ON eu.id_usuario = u.id_Usuario
            WHERE u.id_Status_Usuario IN (1,2) AND u.participante = 1 AND u.id_Usuario IN ($ids)";
        $query = $this->db->query($query);
        while ($row = $query->fetch_object()) {
            $resultado[] = $row;
        }
        $query->close();
        return $resultado;
    }

    // OBTENER TODOS LOS PARTICIPANTES ACTIVOS
    public function getAllParticipantesActivos()
    {
        $resultSet = array();
        $query1 = "SELECT eu.id_empleado_usuario, eu.nombre, eu.apellido_paterno, eu.apellido_materno, eu.id_empleado,
        eu.id_usuario, u.correo_Usuario
        FROM empleados_usuarios eu
            LEFT JOIN Usuarios u ON u.id_Usuario = eu.id_usuario
            LEFT JOIN empleados e ON e.id_empleado = eu.id_empleado
            WHERE eu.id_usuario != 0 AND u.id_Status_Usuario IN (1,2) OR e.status = 1";
        $query = $this->db->query($query1);
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        $query->close();
        return $resultSet;
    }

    // ******************************************* END SECCION DE PARTICIPANTES ****************************************
    // *****************************************************************************************************************

}

