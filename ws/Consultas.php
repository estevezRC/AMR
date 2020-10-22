<?php

require_once 'DataBase.php';

class Consultas
{

    function __construct()
    {}

    public static function getCatReportes($idUsuario)
    {
        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);

        $consulta = "SELECT * FROM VW_getAllCatReportes_Movil cr WHERE cr.id_Proyecto IN($idsProyectos)";
        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getAllParticipantesByArea($area, $empresa)
    {
        $resultset = "";
        $consulta = "SELECT correo_Participante,id_Usuario,id_Area FROM VW_getAllParticipantesProyecto 
                      WHERE id_Area = $area AND Flag_Notificacion = 0 AND id_Usuario !='null' AND id_Empresa = $empresa";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Participante"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }

        /* while($row = $query->fetch_object())
         {
             $resultSet[]=$row;
         }
         $query->close();
         //$this->db->next_result();
         return $resultSet;*/
    }

    public static function getAllParticipantesByIds($notifica)
    {
        $resultset = "";
        $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,
                      apellido_Participante,cargo_Participante,nombre_Empresa,id_Empresa as empresa
                     FROM VW_getAllParticipantesProyecto WHERE id_Participante IN($notifica)";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Participante"];
                $resultset[] = $row["nombre_Participante"];
                $resultset[] = $row["apellido_Participante"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }

        /* while($row = $query->fetch_object())
         {
             $resultSet[]=$row;
         }
         $query->close();
         //$this->db->next_result();
         return $resultSet;*/
    }

    public static function getAllParticipantesCoordinadores($areas, $filtro, $conip)
    {
        $resultset = "";

        $roles = "1,24";
        if ($areas == "1,6") {
            if ($conip) {
                $roles = "1,24,10,12,30";
                $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_Empresa !=2";
            } else {
                $roles = "1,24,10,12";
                $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0";
            }
        } else if ($areas == "2,6") {
            if ($conip) {
                $roles = "6,24,12,11,30";
                if ($filtro == "0") {
                    $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_empresa NOT IN(7,2)";
                } else {
                    $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_Empresa !=2";
                }
            } else {
                $roles = "6,24,12,11";
                if ($filtro == "0") {
                    $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_empresa !=7";
                } else {
                    $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0";
                }
            }
        } else if ($areas == "3,6") {
            if ($conip) {
                $roles = "5,24,12,26,30";
                $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_Participante != 108 AND pp.id_Empresa !=2";
            } else {
                $roles = "5,24,12,26";
                $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0 AND pp.id_Participante != 108";
            }
        }

        /*$consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,cargo_Participante,pp.id_Area,pp.id_Rol
                     FROM Participantes_Proyecto pp WHERE pp.id_Area IN($areas) AND pp.id_Rol IN($roles) AND pp.id_Status_Participante = 0";*/

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Participante"];
                $resultset[] = $row["nombre_Participante"];
                $resultset[] = $row["apellido_Participante"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }

        /* while($row = $query->fetch_object())
         {
             $resultSet[]=$row;
         }
         $query->close();
         //$this->db->next_result();
         return $resultSet;*/
    }

    public static function getCoordinadoresCNC($idsUsuarios)
    {
        $resultset = "";

        $consulta = "SELECT id_Participante,correo_Participante,id_Usuario,nombre_Participante,apellido_Participante,
                            cargo_Participante,pp.id_Area,pp.id_Rol
                            FROM Participantes_Proyecto pp WHERE pp.id_Participante IN ($idsUsuarios) AND pp.id_Status_Participante = 0";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Participante"];
                $resultset[] = $row["nombre_Participante"];
                $resultset[] = $row["apellido_Participante"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }

        /* while($row = $query->fetch_object())
         {
             $resultSet[]=$row;
         }
         $query->close();
         //$this->db->next_result();
         return $resultSet;*/
    }

    public static function getReportesById($id_reporte)
    {
        $consulta = "SELECT * FROM VW_getAllCatReportes WHERE id_Reporte = $id_reporte";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*foreach ($resultado as $row) {
                return $row["Areas"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }

        /*try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }*/
    }

    public static function getCatCamposReportes()
    {
        $consulta = "SELECT * FROM VW_getAllCamposReportes";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getConfCamposReportes($idUsuario)
    {
        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);

        $consulta = "SELECT * FROM VW_getAllConfReportesCampos cc WHERE cc.id_Proyecto IN($idsProyectos)";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerUltimosReportes($area, $empresa, $idUsuario)
    {

        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);

        $resultset = "";
        $consulta = "SELECT * FROM VW_getReportesLlenados_Movil rl WHERE rl.idProyecto IN($idsProyectos) LIMIT 1000";
        //AND vrl.id_Empresa = $empresa
        //WHERE vrl.Areas LIKE '%$area%'

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset = $resultset . ',' . $row["id_Gpo_Valores_Reporte"];
                //return $row["Areas"];
            }
            return substr($resultset, 1);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerUltimosReportesUbicacion($area, $empresa, $ubicacion, $fecha)
    {

        $resultset = "";
        if ($fecha == "") {
            $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado FROM R_Reporte_InvUbi rriu 
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte 
                      WHERE rriu.Id_InvUbi = $ubicacion ORDER BY rl.fecha_registro DESC LIMIT 150";

            /*SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      WHERE riu.Id_Ubicacion = 1877
                      ORDER BY rl.fecha_registro DESC*/
        } else {
            $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado FROM R_Reporte_InvUbi rriu 
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte 
                      WHERE rriu.Id_InvUbi = $ubicacion AND rl.reporte_Modificado > '$fecha'";
        }

        //echo $consulta;

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset = $resultset . ',' . $row["Id_Reporte"];
                //return $row["Areas"];
            }
            return substr($resultset, 0);
            //echo substr($resultset,1);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerUltimosReportesUbicacionPadre($area, $empresa, $ubicacion, $fecha)
    {

        $idsReportesCNC = self::obtenerUltimosReportesUbicacion($area, $empresa, "23", $fecha);
        $resultset = "";
        if ($fecha == "") {
            $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      WHERE sl.Id_Gpo_Padre = 1877
                      ORDER BY rl.fecha_registro DESC LIMIT 150";

            /*SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      WHERE sl.Id_Gpo_Padre = 1877
                      ORDER BY rl.fecha_registro*/
        } else {
            $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      WHERE sl.Id_Gpo_Padre = 1877 AND rl.reporte_Modificado > '$fecha'
                      ORDER BY rl.fecha_registro DESC ";
        }

        //echo $consulta;

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $idsReportesCNC = $idsReportesCNC . ',' . $row["Id_Reporte"];
                //return $row["Areas"];
            }
            return substr($idsReportesCNC, 1);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerUltimosReportesUbicacionMexIr($area, $empresa, $ubicacion, $fecha)
    {

        $idsReportesMI = self::obtenerUltimosReportesIncidentesMexIr($area, $empresa, $ubicacion, $fecha);//"";//self::obtenerUltimosReportesUbicacion($area,$empresa,"23",$fecha);
        $resultset = "";
        if ($fecha == "") {
            if ($area == "1") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,cr.Areas,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE cr.Areas LIKE '%$area%' AND riu.Id_InvUbi !=23 AND sl.Id_Gpo_Padre != 1877
                      AND rl.id_Reporte NOT IN(26,25,13)
                      ORDER BY rl.fecha_registro DESC LIMIT 150";
            } else {
                $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,rl.titulo_Reporte,cr.Areas,rl.id_Status_Elemento FROM Reportes_Llenados rl
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE cr.Areas LIKE '%$area%' AND rl.id_Reporte NOT IN(26,25,13) 
                      ORDER BY rl.fecha_registro DESC LIMIT 150";
            }
        } else {
            if ($area == "1") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE sl.Id_Gpo_Padre != 1877 AND riu.Id_InvUbi != 23 
                      AND cr.Areas LIKE '%$area%' AND rl.reporte_Modificado > '$fecha'
                      AND rl.id_Reporte NOT IN(26,25,13)
                      ORDER BY rl.reporte_Modificado DESC";
            } else {
                $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,rl.titulo_Reporte,rl.id_Status_Elemento 
                              FROM Reportes_Llenados rl INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte 
                              WHERE cr.Areas LIKE '%$area%' AND rl.reporte_Modificado > '$fecha' AND rl.id_Reporte NOT IN(26,25,13) 
                              ORDER BY rl.reporte_Modificado DESC";
            }
        }

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $idsReportesMI = $idsReportesMI . ',' . $row["Id_Reporte"];
                //return $row["Areas"];
            }
            return substr($idsReportesMI, 1);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerUltimosReportesIncidentesMexIr($area, $empresa, $ubicacion, $fecha)
    {

        $idsReportesMI = "";//self::obtenerUltimosReportesUbicacion($area,$empresa,"23",$fecha);
        $resultset = "";
        if ($fecha == "") {
            if ($area == "1") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,cr.Areas,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE cr.Areas LIKE '%$area%' AND riu.Id_InvUbi !=23 AND sl.Id_Gpo_Padre != 1877
                      AND rl.id_Reporte IN(2) AND rl.id_Status_Elemento = 0
                      ORDER BY rl.fecha_registro DESC";//" LIMIT 150";
            } else if ($area == "6") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,cr.Areas,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE cr.Areas LIKE '%$area%' AND riu.Id_InvUbi !=23 AND sl.Id_Gpo_Padre != 1877
                      AND rl.id_Reporte IN(2,34,43) AND rl.id_Status_Elemento = 0
                      ORDER BY rl.fecha_registro DESC";
            } else {
                $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,rl.titulo_Reporte,cr.Areas,rl.id_Status_Elemento FROM Reportes_Llenados rl
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE cr.Areas LIKE '%$area%' AND rl.id_Reporte IN(43,34) AND rl.id_Status_Elemento = 0
                      ORDER BY rl.fecha_registro DESC";//LIMIT 150";
            }
        } else {
            if ($area == "1") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE sl.Id_Gpo_Padre != 1877 AND riu.Id_InvUbi != 23 
                      AND cr.Areas LIKE '%$area%' AND rl.reporte_Modificado > '$fecha'
                      AND rl.id_Reporte IN(2)
                      ORDER BY rl.reporte_Modificado DESC";
            } else if ($area == "6") {
                $consulta = "SELECT rriu.Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,sl.Id_Gpo_Padre,rl.titulo_Reporte,rl.id_Status_Elemento FROM R_Reporte_InvUbi rriu
                      INNER JOIN Reportes_Llenados rl ON rriu.Id_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN R_Inventario_Ubicacion riu ON rriu.Id_InvUbi = riu.Id_InvUbi
                      INNER JOIN Sistemas_Llenados sl ON riu.Id_Ubicacion = sl.Id_Gpo
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      WHERE sl.Id_Gpo_Padre != 1877 AND riu.Id_InvUbi != 23 
                      AND cr.Areas LIKE '%$area%' AND rl.reporte_Modificado > '$fecha'
                      AND rl.id_Reporte IN(2,34,43)
                      ORDER BY rl.reporte_Modificado DESC";
            } else {
                $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS Id_Reporte,rl.fecha_registro,rl.reporte_Modificado,rl.titulo_Reporte,rl.id_Status_Elemento 
                              FROM Reportes_Llenados rl INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte 
                              WHERE cr.Areas LIKE '%$area%' AND rl.reporte_Modificado > '$fecha' AND rl.id_Reporte IN(43,34) 
                              ORDER BY rl.reporte_Modificado DESC";
            }
        }

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $idsReportesMI = $idsReportesMI . ',' . $row["Id_Reporte"];
                //return $row["Areas"];
            }
            return substr($idsReportesMI, 1);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getReportesLlenados()
    {
        $consulta = "SELECT * FROM Reportes_Llenados rl";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getReportesLlenadosFechaEmpesa($fecha, $area, $idUsuario)
    {
        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);

        if ($fecha == "") {
            $consulta = "SELECT * FROM VW_getReportesLlenados_Movil rl WHERE rl.idProyecto IN($idsProyectos) LIMIT 1000";
            //    AND vrl.id_Empresa = $empresa LIMIT 100;";
            //WHERE vrl.Areas LIKE '%$area%'
        } else {
            $consulta = "SELECT * FROM VW_getReportesLlenados_Movil rl WHERE  rl.reporte_Modificado > '$fecha' AND rl.idProyecto IN($idsProyectos)";
        }
        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getReportesLlenadosFecha($fecha)
    {
        $consulta = "SELECT * FROM Reportes_Llenados rl WHERE rl.reporte_Modificado > '$fecha'";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresReportes()
    {
        $consulta = "SELECT * FROM VW_getAllValoresReportes";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresReportesFechaEmpresa($fecha, $area, $idsReportes, $idUsuario)
    {
        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);

        if ($fecha == "") {
            $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil vr WHERE vr.fecha_modificado > '$fecha' 
                         AND id_Gpo_Valores_Reporte IN($idsReportes)";
        } else {
            $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil vr WHERE vr.fecha_modificado > '$fecha' AND vr.id_Proyecto IN($idsProyectos)";
        }

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresReportesFechaEmpresaAnt($fecha, $empresa, $area)
    {
        if ($empresa == "1" || $empresa == "") {
            $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil WHERE fecha_modificado > '$fecha' 
                         AND id_Area = $area AND id_Status = 0";
        } else {
            $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil WHERE fecha_modificado > '$fecha' 
                         AND id_Area = $area AND id_Empresa = $empresa AND id_Status = 0";
        }

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresReportesFecha($fecha)
    {
        $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil WHERE fecha_modificado > '$fecha'";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getSeguimientoReportes()
    {
        $consulta = "SELECT * FROM Seguimiento_Reportes";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getSeguimientoReportesFechaEmpresaAnt($fecha, $area)
    {
        //if($empresa == "1" || $empresa ==""){
        $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha';
                         //AND Id_Area = $area";
        /*}else{
            $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha' 
                         AND Id_Area = $area AND id_Empresa = $empresa";
        }*/

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getSeguimientoReportesFechaEmpresa($fecha, $empresa, $area, $idsReportes)
    {
        //echo $fecha,$empresa,$area;
        /*if($empresa == "" || $empresa =="") {
            if ($fecha == "") {
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Area LIKE '%$area%' 
                          AND Id_Reporte IN($idsReportes)";
            } else {
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha' 
                         AND Id_Area  LIKE '%$area%'";
            }
            //echo "ok";
        }else if($empresa=="2"){
            $idsReportesMI = self::obtenerUltimosReportesUbicacionMexIr($area,$empresa,"",$fecha);

            $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Reporte IN($idsReportesMI)";
            //echo $consulta;
        }else if($empresa=="16"){
            $idsReportesMI = self::obtenerUltimosReportesUbicacionMexIr($area,$empresa,"",$fecha);

            $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Reporte IN($idsReportesMI)";
            //echo $consulta;
        } else if($empresa=="9"){
            if ($fecha == "") {
                $idsReportesCNC = self::obtenerUltimosReportesUbicacionPadre($area,$empresa,"23",$fecha);
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Area LIKE '%$area%' 
                          AND Id_Reporte IN($idsReportesCNC)";
            } else {
                $idsReportesCNC = self::obtenerUltimosReportesUbicacionPadre($area,$empresa,"23",$fecha);
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Area LIKE '%$area%' 
                          AND Id_Reporte IN($idsReportesCNC)";
            }
            //echo 'ok';
        }else{*/
        //echo $idsReportes;
        if ($fecha == "") {
            $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Reporte IN($idsReportes) AND id_Empresa = $empresa";
            //getSeguimientoReportesFechaEmpresa
        } else {
            $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha' AND id_Empresa = $empresa";
            //AND Id_Area LIKE '%$area%'
            //AND Id_Reporte IN($idsReportes)
        }
        //}

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getRelacionReporteUbicacion($fecha, $empresa, $area, $idsReportes)
    {
        //if($empresa == "1" || $empresa =="") {
        if ($fecha == "") {
            $consulta = "SELECT * FROM VW_getAllRelacionReportesUbicacion_Movil varrum 
                              WHERE varrum.areas LIKE '%$area%' AND varrum.gpoValores IN($idsReportes)";
        } else {
            $consulta = "SELECT * FROM VW_getAllRelacionReportesUbicacion_Movil varrum 
                              WHERE varrum.areas LIKE '%$area%' AND varrum.reporte_Modificado > '$fecha'";
        }
        /*}else{
            if($fecha == "") {
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE Id_Area LIKE '%$area%'
                              AND Id_Reporte IN($idsReportes) AND id_Empresa = $empresa";
            }else{
                $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha' 
                              AND Id_Area LIKE '%$area%' AND Id_Reporte IN($idsReportes) AND id_Empresa = $empresa";
            }
        }*/

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getSeguimientoReportesFecha($fecha)
    {
        $consulta = "SELECT * FROM VW_getAllSeguimientos_Movil WHERE fecha_modificado > '$fecha'";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatIndicadores()
    {
        $consulta = "SELECT * FROM Cat_Indicadores_Avances";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresIndicadores($fecha)
    {
        $consulta = "SELECT * FROM Valores_Indicadores vi
                      INNER JOIN CT_Sistema cs ON vi.id_Sistema = cs.Id_Sistema
                      WHERE fecha_Avance > '$fecha'
                      ORDER BY fecha_Avance";// DESC LIMIT 150";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getMaxGpoValoresReportes()
    {
        $consulta = "SELECT MAX(id_Gpo_Valores_Reporte) AS idGpo FROM Valores_Reportes_Campos";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idGpo"] + 1;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getMaxGpoValoresInventario()
    {
        $consulta = "SELECT MAX(id_Gpo_Valores_Elemento) AS idGpo FROM Valores_Elementos_Caractersiticas";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idGpo"] + 1;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores


    public static function insertaValoresReportes(
        $idPreyecto,
        $valor_texto,
        $valor_entero,
        $idConf,
        $tabla,
        $idGpoVal
    )
    {
        $comando = "CALL sp_AddData_General_ConfValores(?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $idPreyecto,
                $valor_texto,
                $valor_entero,
                $idConf,
                $tabla,
                $idGpoVal
            )
        );
    }

    public static function insertaReportesLlenados(
        $idGrupoVal,
        $nombre,
        $comentario,
        $fecha,
        $tabla,
        $usuario,
        $idReporte,
        $remplazo,
        $idPadre
    )
    {
        $comando = "CALL sp_AddData_General(?,?,?,?,?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $nombre,
                $comentario,
                $idGrupoVal,
                $tabla,
                $usuario,
                $idReporte,
                $fecha,
                $remplazo,
                $idPadre,
                0, "nada"
            )
        );

    }//fin insert

    public static function insertaSeguimientoReportes(
        $idReporte,
        $idUsuario,
        $idArea,
        $fecha
    )
    {
        $comando = "CALL sp_Crear_Seguimientos_Alterno(?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $idReporte,
                $idUsuario,
                $idArea,
                $fecha
            )
        );

    }//fin insert

    public static function insertaFotografias(
        $idUsuario,
        $idModulo,
        $idGpo,
        $directorio,
        $nombreFoto,
        $latitud,
        $altitud,
        $longitud,
        $descripcion,
        $busqueda,
        $operacion,
        $fecha,
        $hora,
        $orientacion,
        $status,
        $cadenamiento,
        $cuerpo
    )
    {
        $comando = "CALL sp_General_Fotografias_Movil(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $idUsuario,
                $idModulo,
                $idGpo,
                $directorio,
                $nombreFoto,
                $latitud,
                $altitud,
                $longitud,
                $descripcion,
                $busqueda,
                $operacion,
                $fecha,
                $hora,
                $orientacion,
                $status,
                $cadenamiento,
                $cuerpo
            )
        );

    }//fin insert

    public static function insertaFotografiasAlterno(
        $idUsuario,
        $idModulo,
        $idGpo,
        $directorio,
        $nombreFoto,
        $latitud,
        $altitud,
        $longitud,
        $descripcion,
        $busqueda,
        $operacion,
        $fecha,
        $hora,
        $orientacion,
        $status,
        $cadenamiento,
        $cuerpo
    )
    {

        $descripcion = str_replace("'", "\'", $descripcion);

        $comando = "INSERT INTO Fotografias
          (
           id_Usuario
           ,id_Modulo
           ,identificador_Fotografia
           ,directorio_Fotografia
           ,nombre_Fotografia
           ,fecha_Fotografia
           ,hora_Fotografia
           ,latitud_Fotografia
           ,altitud_Fotografia
           ,longitud_Fotografia
           ,descripcion_Fotografia
           ,id_Status_Fotografia
           ,orientacion_Fotografia
           ,cad_Fotografia
           ,cuerpo_Fotografia
          )
          VALUES
          (
           $idUsuario -- id_Usuario - INT(11) NOT NULL
           ,$idModulo -- id_Modulo - SMALLINT(6)
           ,$idGpo -- identificador_Fotografia - INT(11)
           ,'$directorio' -- directorio_Fotografia - VARCHAR(255)
           ,'$nombreFoto' -- nombre_Fotografia - VARCHAR(100)
           ,'$fecha' -- fecha_Fotografia - DATE
           ,'$hora' -- hora_Fotografia - TIME
           ,$latitud -- latitud_Fotografia - DOUBLE
           ,$altitud -- altitud_Fotografia - DOUBLE
           ,$longitud -- longitud_Fotografia - DOUBLE
           ,'$descripcion' -- descripcion_Fotografia - VARCHAR(500)
           ,$status -- id_Status_Fotografia - TINYINT(1)
           ,$orientacion -- orientacion
           ,$cadenamiento -- cadenamiento
           ,'$cuerpo' -- cuerpo
          )";

        //echo $comando;
        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
        /*array(
            $idUsuario,
            $idModulo,
            $idGpo,
            $directorio,
            $nombreFoto,
            $latitud,
            $altitud,
            $longitud,
            $descripcion,
            $busqueda,
            $operacion,
            $fecha,
            $hora,
            $orientacion,
            $status,
            $cadenamiento,
            $cuerpo
        )
    );*/

    }//fin insert

    public static function getCatElementos()
    {
        $consulta = "SELECT * FROM VW_getAllCatElementos";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatCaracteristicasElementos()
    {
        $consulta = "SELECT * FROM VW_getAllCatCaracteristicasElementos";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getConfElementosCaracteristicas()
    {
        $consulta = "SELECT * FROM VW_getAllConfElementosCaracteristicas";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaValoresElementos(
        $idPreyecto,
        $valor_texto,
        $valor_entero,
        $idConf,
        $tabla,
        $idGpoVal
    )
    {
        $comando = "CALL sp_AddData_General_ConfValores(?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $idPreyecto,
                $valor_texto,
                $valor_entero,
                $idConf,
                $tabla,
                $idGpoVal
            )
        );
    }

    public static function insertaElementosInventariados(
        $idGrupoVal,
        $nombre,
        $comentario,
        $fecha,
        $tabla,
        $usuario,
        $idReporte,
        $idRemplazo
    )
    {
        $comando = "CALL sp_AddData_General(?,?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $nombre,
                $comentario,
                $idGrupoVal,
                $tabla,
                $usuario,
                $idReporte,
                $fecha,
                $idRemplazo
            )
        );

    }//fin insert


    public static function getAreasEmpresas()
    {
        $consulta = "SELECT * FROM Areas_Empresas";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getUsuarios()
    {
        $consulta = "SELECT * FROM VW_getAllUsuarios";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getParticipantes()
    {
        $consulta = "SELECT * FROM VW_getAllParticipantesProyecto";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getFotografias($modulo)
    {
        if ($modulo == 1) {
            $consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
        } else if ($modulo == 3) {
            //$consulta = "SELECT * FROM Fotografias";
            $consulta = "SELECT * FROM Fotografias WHERE id_Modulo=3 AND identificador_Fotografia IN(SELECT vic1.Id_Gpo FROM Valores_Items_Conf vic1 WHERE vic1.Valor_Texto='MEXICO-IRAPUATO')";
        }

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getFotografiasFechaEmpresa($modulo, $fecha, $area, $idsReportes, $idUsuario)
    {

        $idsProyectos = self::ObtenerUsuariosProyectos($idUsuario);
        if ($fecha == "") {
            //if($empresa == "1" || $empresa == "") {
            $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area LIKE '%$area%' 
                             AND identificador_Fotografia IN ($idsReportes)";
            /*}else{
                $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area LIKE '%$area%' AND id_Empresa = $empresa
                             AND identificador_Fotografia IN ($idsReportes)";
            }*/
        } else {
            //if($empresa == "1" || $empresa == "") {
            $consulta = "SELECT * FROM VW_getAllFotografias_Movil fo WHERE fo.id_Area LIKE '%4%' 
                             AND fo.fecha_modificado > '$fecha' AND fo.idProyecto IN($idsProyectos)";
            /*}else{
                $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area LIKE '%$area%' AND id_Empresa = $empresa
                             AND id_Empresa = $empresa AND fecha_modificado > '$fecha'";
            }*/
        }

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getFotografiasFechaEmpresaAnt($modulo, $fecha, $empresa, $area)
    {
        if ($modulo == 1) {
            if ($fecha == "") {
                if ($empresa == "1" || $empresa == "") {
                    //$consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia
                    //           IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area = $area 
                                 AND identificador_Fotografia IN (SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                } else {
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area = $area AND id_Empresa = $empresa 
                                 AND identificador_Fotografia IN (SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                }
            } else {
                if ($empresa == "1" || $empresa == "") {
                    //$consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia
                    //           IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area = $area 
                                 AND fecha_modificado > '$fecha'";
                } else {
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE id_Modulo=1 AND id_Area = $area AND id_Empresa = $empresa
                                 AND id_Empresa = $empresa AND fecha_modificado > '$fecha'";
                }
                //$consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE fecha_modificado > '$fecha' AND id_Modulo=1
                //           AND id_Area = $area";
            }
            //SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
        } else if ($modulo == 3) {
            //$consulta = "SELECT * FROM Fotografias";
            //$consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE fecha_modificado > '$fecha' AND id_Modulo=3";
            //"SELECT * FROM Fotografias WHERE id_Modulo=3 AND identificador_Fotografia IN(SELECT vic1.Id_Gpo FROM Valores_Items_Conf vic1 WHERE vic1.Valor_Texto='MEXICO-IRAPUATO')";
            if ($fecha == "") {
                if ($empresa == "1" || $empresa == "") {
                    //$consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia
                    //           IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil_Geos WHERE id_Modulo=3 AND id_Area = $area";
                    //AND identificador_Fotografia IN (SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                } else {
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil_Geos WHERE id_Modulo=3 AND id_Area = $area AND id_Empresa = $empresa";
                    //AND identificador_Fotografia IN (SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                }
            } else {
                if ($empresa == "1" || $empresa == "") {
                    //$consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia
                    //           IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil_Geos WHERE id_Modulo=3 AND id_Area = $area 
                                 AND fecha_modificado > '$fecha'";
                } else {
                    $consulta = "SELECT * FROM VW_getAllFotografias_Movil_Geos WHERE id_Modulo=3 AND id_Area = $area AND id_Empresa = $empresa
                                 AND id_Empresa = $empresa AND fecha_modificado > '$fecha'";
                }
                //$consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE fecha_modificado > '$fecha' AND id_Modulo=1
                //           AND id_Area = $area";
            }
        }

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getFotografiasFecha($modulo, $fecha)
    {
        if ($modulo == 1) {
            if ($fecha == "") {
                $consulta = "SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
            } else {
                $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE fecha_modificado > '$fecha' AND id_Modulo=1";
            }
            //SELECT * FROM Fotografias WHERE id_Modulo=1 AND identificador_Fotografia IN(SELECT id_Gpo_Valores_Reporte FROM Reportes_Llenados)";
        } else if ($modulo == 3) {
            //$consulta = "SELECT * FROM Fotografias";
            $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE fecha_modificado > '$fecha' AND id_Modulo=3";
            //"SELECT * FROM Fotografias WHERE id_Modulo=3 AND identificador_Fotografia IN(SELECT vic1.Id_Gpo FROM Valores_Items_Conf vic1 WHERE vic1.Valor_Texto='MEXICO-IRAPUATO')";
        }
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getDispositivos()
    {
        $consulta = "SELECT * FROM Cat_Dispositivos";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatAvancesPeaje()
    {
        $consulta = "SELECT * FROM Cat_Avances_Peaje";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatClasificacionFotografias()
    {
        $consulta = "SELECT * FROM clasificacion_Fotografias";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatMonitoreoDiario()
    {
        $consulta = "SELECT * FROM Cat_Monitoreo_Diario";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCatalogoITS()
    {
        $consulta = "SELECT * FROM Catalogo_ITS";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaValoresIndicador(
        $gpoUbicaicon,
        $idSistema,
        $idIndicador,
        $idStatus,
        $contador,
        $area,
        $usuario
    )
    {
        $comando = "CALL SP_Inserta_Valores_Indicadores(?,?,?,?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $gpoUbicaicon,
                $idSistema,
                $idIndicador,
                $idStatus,
                $contador,
                $area,
                $usuario
            )
        );

    }//fin insert

    public static function insertaRelacionReporteUbicacion($gpoReporte, $gpoUbicacion)
    {

        $comando = "INSERT INTO R_Reporte_Ubicacion(gpo_Reporte,gpo_Ubicacion)
                    VALUES(?,?)";

        //$comando = "CALL SP_Inserta_Valores_Indicadores(?,?,?,?)";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array($gpoReporte, $gpoUbicacion)
        );
    }

    public static function modificaValoresReportes(
        $valor_texto,
        $valor_entero,
        $idConf,
        $idGpoVal
    )
    {
        $comando = "CALL sp_Modifica_Valores_Reportes(?,?,?,?)";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute(
            array(
                $valor_texto,
                $valor_entero,
                $idConf,
                $idGpoVal
            )
        );
    }

    public static function obtenerValoresReportes($gpoValores)
    {

        $consulta = "SELECT * FROM VW_getAllValoresReportes WHERE id_Gpo_Valores_Reporte = $gpoValores";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerValoresReportesAlterno($gpoValores)
    {

        $consulta = "SELECT vr.nombre_Campo AS campo,vr.valor_Texto_Reporte AS valor,vr.tipo_Reactivo_Campo AS reactivo 
                      FROM VW_getAllValoresReportes vr WHERE id_Gpo_Valores_Reporte = $gpoValores";
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function InsertaR_Inventario_Ubicacion($gpoUbicacion, $gpoReporte)
    {

        $consulta = "SELECT Id_InvUbi as id FROM R_Inventario_Ubicacion WHERE Id_Ubicacion = $gpoUbicacion";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $row) {
                    $idRelacion = $row["id"];
                }
                return self::InsertaR_Reporte_InvUbi($gpoReporte, $idRelacion);

            } else {
                $insertaRelacion = "CALL SP_Insertar_R_Inventario_Ubicacion(?,?)";

                $sentencia = Database::getInstance()->getDb()->prepare($insertaRelacion);

                $inserto = $sentencia->execute(array(null, $gpoUbicacion));

                if ($inserto == true) {
                    $idRelacion = self::getMaxR_Inventario_Ubicacion();

                    $insertaR_reportes = self::InsertaR_Reporte_InvUbi($gpoReporte, $idRelacion);

                    return $insertaR_reportes;
                } else {
                    return false;//"no se inserto la primera";
                }
            }
        } catch (PDOException $e) {
            return false;//"fatal error";
        }

    }

    public static function Modifica_Reporte_Ubicacion($gpoUbicacion, $gpoReporte)
    {

        $consulta = "SELECT Id_InvUbi as id FROM R_Inventario_Ubicacion WHERE Id_Ubicacion = $gpoUbicacion";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $row) {
                    $idRelacion = $row["id"];
                }
                //echo $idRelacion.'<br/>';
                return self::Modifica_Reporte_InvUbi($gpoReporte, $idRelacion);

            } else {
                $insertaRelacion = "CALL SP_Insertar_R_Inventario_Ubicacion(?,?)";

                $sentencia = Database::getInstance()->getDb()->prepare($insertaRelacion);

                $inserto = $sentencia->execute(array(null, $gpoUbicacion));

                if ($inserto == true) {
                    $idRelacion = self::getMaxR_Inventario_Ubicacion();

                    $insertaR_reportes = self::InsertaR_Reporte_InvUbi($gpoReporte, $idRelacion);

                    return $insertaR_reportes;
                } else {
                    return false;//"no se inserto la primera";
                }
            }
        } catch (PDOException $e) {
            return false;//"fatal error";
        }

    }

    public static function InsertaR_Reporte_InvUbi($gpoReporte, $idRelacion)
    {

        $comando = "CALL SP_Insertar_R_Reporte_InvUbi(?,?)";

        $sentencia1 = Database::getInstance()->getDb()->prepare($comando);

        $inserta = $sentencia1->execute(array($gpoReporte, $idRelacion));

        return $inserta;//"insercion de datos";
    }

    public static function Modifica_Reporte_InvUbi($gpoReporte, $idRelacion)
    {

        $comando = "UPDATE R_Reporte_InvUbi set Id_InvUbi = $idRelacion WHERE Id_Reporte = $gpoReporte";
        //echo $comando;

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getMaxR_Inventario_Ubicacion()
    {
        $consulta = "SELECT MAX(Id_InvUbi) AS id FROM R_Inventario_Ubicacion";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["id"];
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function ObtenerAreaSistema($idSistema)
    {
        $consulta = "SELECT cs.Id_Area FROM CT_Sistema cs WHERE cs.Id_Sistema = $idSistema";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["Id_Area"];
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function modificaReportes(
        $idGpoVal,
        $titulo
    )
    {
        $comando = "UPDATE Reportes_Llenados set 
                    fecha_modificacion = NOW(),
                    tituloReporte = '$titulo'
                    WHERE id_Gpo_Valores_Reporte = $idGpoVal";

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function modificaFotografias(
        $idGpoVal,
        $nameFoto,
        $status,
        $descripcion,
        $clasificacion,
        $cadenamiento,
        $cuerpo
    )
    {
        $descripcion = str_replace("'", "\'", $descripcion);

        $comando = "UPDATE Fotografias set 
                    descripcion_Fotografia = '$descripcion',
                    id_Status_Fotografia = $status,
                    directorio_Fotografia = '$clasificacion',
                    cad_Fotografia = $cadenamiento,
                    cuerpo_Fotografia = '$cuerpo' 
                    WHERE nombre_Fotografia = '$nameFoto' AND identificador_Fotografia = $idGpoVal";

        //echo $comando;
        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function InsertaReportesLlenadosAlterno(
        $idGrupoVal,
        $comentario,
        $statusElemento,
        $fecha,
        $usuario,
        $idReporte,
        $titulo, $etapa, $tiempo, $idPadre, $latitud, $longitud, $clasReporte)
    {

        $titulo = str_replace("'", "\'", $titulo);

        $comando = "INSERT INTO Reportes_Llenados(
                    id_Gpo_Valores_Reporte
                    ,Comentarios_Reporte
                    ,id_Status_Elemento
                    ,fecha_registro
                    ,id_Usuario
                    ,id_Reporte
                    ,titulo_Reporte
                    ,reporte_Modificado
                    ,id_Tiempo
                    ,id_Etapa
                    ,id_Gpo_Padre,
                    latitud_Reporte,
                    longitud_Reporte,
                    clas_Reporte
                    )
                    VALUES($idGrupoVal,'$comentario',$statusElemento,'$fecha',$usuario,
                    $idReporte,'$titulo',NOW(),$tiempo,$etapa,$idPadre,$latitud,$longitud,$clasReporte)";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function ModificaInfoReportes(
        $idGrupoVal,
        $titulo,
        $statusElemento,
        $etapa,
        $idPadre,
        $latitud,
        $longitud,
        $clasReporte
    )
    {

        $titulo = str_replace("'", "\'", $titulo);

        $comando = "UPDATE Reportes_Llenados rl set
                    rl.titulo_Reporte = '$titulo'
                    ,rl.id_Status_Elemento = $statusElemento
                    ,rl.reporte_Modificado = NOW()
                    ,rl.id_Etapa = $etapa
                    ,rl.id_Gpo_Padre = $idPadre
                    ,rl.latitud_Reporte = $latitud
                    ,rl.longitud_Reporte = $longitud
                    ,rl.clas_Reporte = $clasReporte
                    WHERE id_Gpo_Valores_Reporte = $idGrupoVal";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getExisteFotografia($nombreFotografia, $gpo)
    {
        $consulta = "SELECT nombre_Fotografia FROM Fotografias 
                    WHERE nombre_Fotografia = '$nombreFotografia' 
                    AND identificador_Fotografia = $gpo";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return "ok";
            } else {
                return "no";
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getExisteSeguimiento($gpo, $user)
    {
        $consulta = "SELECT * FROM Seguimiento_Reportes sr WHERE sr.Id_Reporte = $gpo AND sr.Id_Usuario = $user";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getAreaUsuario($idUser)
    {
        $consulta = "SELECT u.id_Area AS idArea FROM Usuarios u WHERE u.id_Usuario = $idUser";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idArea"];
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getEmpresaUsuario($idUser)
    {
        $consulta = "SELECT id_Empresa AS empresa FROM Usuarios u WHERE u.id_Usuario = $idUser";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["empresa"];
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function obtenerIdsTipoReporte($tipo)
    {
        $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS gpo,rriu.Id_InvUbi AS ubi,rl.fecha_registro AS fecha FROM Reportes_Llenados rl 
                      LEFT JOIN R_Reporte_InvUbi rriu ON rl.id_Gpo_Valores_Reporte = rriu.Id_Reporte
                      WHERE rl.id_Reporte = $tipo AND rl.id_Status_Elemento = 0";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*foreach ($resultado as $row) {
                return $row["idGpo"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerValoresConfiguracion($idConf)
    {
        $consulta = "SELECT vrc.id_Valor_Reporte_Campo AS gpo,vrc.valor_Texto_Reporte AS valor FROM Valores_Reportes_Campos vrc 
                      WHERE vrc.id_Configuracion_Reporte = $idConf";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*foreach ($resultado as $row) {
                return $row["idGpo"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function VerificaSiTieneIdPadre($idGpo)
    {
        $consulta = "SELECT sl.Id_Gpo_Padre AS idPadre FROM Sistemas_Llenados sl WHERE sl.Id_Gpo = $idGpo";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*if(count($resultado)>0){
                return 1;
            }else{
                return 0;
            }*/

            if (count($resultado) > 0) {
                $retorno = 0;
                foreach ($resultado as $row) {
                    if ($row["idPadre"] == "") {
                        $retorno = 0;
                    } else if ($row["idPadre"] == "1877") {
                        $retorno = 1;
                    } else {
                        $retorno = 0;//$row["idPadre"];
                    }
                }
            } else {
                $retorno = 0;
            }

            return $retorno;

        } catch (PDOException $e) {
            return false;
        }
    }

    //SELECT * FROM Valores_Reportes_Campos vrc WHERE vrc.id_Gpo_Valores_Reporte = 4240 AND vrc.id_Configuracion_Reporte = 382

    public static function VerificaExisteCampo($idGpo, $idConf)
    {
        $consulta = "SELECT * FROM Valores_Reportes_Campos vrc 
                      WHERE vrc.id_Gpo_Valores_Reporte = $idGpo AND vrc.id_Configuracion_Reporte = $idConf";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return 1;
            } else {
                return 0;
            }

            /*foreach ($resultado as $row) {
                return $row["idGpo"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaReporteNuevo($idGpo, $valorTexto, $conf)
    {
        $comando = "INSERT INTO Valores_Reportes_Campos
                    (id_Proyecto,id_Gpo_Valores_Reporte ,valor_Texto_Reporte,valor_Entero_Reporte,id_Configuracion_Reporte)
                    VALUES( 1,$idGpo,'$valorTexto',NULL,$conf)";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function actualizaCampoReporte($idValor, $texto)
    {
        $comando = "UPDATE Valores_Reportes_Campos set valor_Texto_Reporte = '$texto' 
                    WHERE id_Valor_Reporte_Campo = $idValor";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function actualizaConfReporte($idValor, $texto)
    {
        $comando = "UPDATE Valores_Reportes_Campos set valor_Texto_Reporte = '$texto' 
                    WHERE id_Valor_Reporte_Campo = $idValor";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function RegistraToken($usuario, $token)
    {

        $existe = false;
        $consulta = "SELECT * FROM Token_Dispositivos td WHERE td.id_Usuario = $usuario AND td.Token = '$token'";

        //echo $consulta;
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            //echo "Contador: ".count($comando);

            if (count($resultado) > 0) {
                $existe = true;
            } else {
                $existe = false;
            }

            if ($existe) {
                return "2";
            } else {
                $comando = "INSERT INTO Token_Dispositivos(id_Usuario,Token,Fecha_Token,Status_Token)
                        VALUES($usuario ,'$token',NOW(),0)";

                $sentencia = Database::getInstance()->getDb()->prepare($comando);

                $inserto = $sentencia->execute();

                if ($inserto) {
                    return "1";
                } else {
                    return "0";
                }
            }

        } catch (PDOException $e) {
            return "0";
        }

    }

    public static function ObtenerTokens($usuarios)
    {
        $resultset = "";
        $consulta = "SELECT td.id_Usuario AS usuario,td.Token AS token FROM Token_Dispositivos td
                      WHERE td.id_Usuario IN($usuarios)";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["token"];
                //return $row["Areas"];
            }
            return $resultset;

        } catch (PDOException $e) {
            return false;
        }
    }//

    public static function obtenerFotografiasReportes($idGpo)
    {
        $consulta = "SELECT fo.nombre_Fotografia as nombre,fo.fecha_Fotografia,
                            fo.identificador_Fotografia FROM Fotografias AS fo 
                            WHERE fo.identificador_Fotografia=$idGpo AND fo.id_Modulo = 1
                            AND fo.id_Status_Fotografia = 0 LIMIT 4";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*foreach ($resultado as $row) {
                return $row["idGpo"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDetallesReporte($gpoValores)
    {
        $consulta = "SELECT ccr.nombre_Campo AS nombreC, 
                          ccr.tipo_Reactivo_Campo AS reactivo,
                          vrc.valor_Texto_Reporte AS valText,
                          vrc.valor_Entero_Reporte AS valEnt,
                          crc.id_Configuracion_Reporte AS idConf,
                          ccr.tipo_Valor_Campo AS tipoValor,
                          ccr.Valor_Default FROM Valores_Reportes_Campos vrc 
                          INNER JOIN Conf_Reportes_Campos crc ON vrc.id_Configuracion_Reporte = crc.id_Configuracion_Reporte
                          INNER JOIN Cat_Campos_Reportes ccr ON crc.id_Campo_Reporte = ccr.id_Campo_Reporte
                          WHERE vrc.id_Gpo_Valores_Reporte = $gpoValores GROUP BY idConf ORDER BY crc.Secuencia ASC";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerImagenes($idsGpo)
    {
        $consulta_fotos = "SELECT f.nombre_Fotografia AS nombre,
                            f.descripcion_Fotografia AS descripcion,
                            f.orientacion_Fotografia AS orientacion 
                            FROM Fotografias f WHERE f.id_Modulo = 1 
                            AND f.identificador_Fotografia IN ($idsGpo)
                            AND f.id_Status_Fotografia = 0";

        //echo $consulta_fotos;

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta_fotos);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDatosReporte($idGpo)
    {
        $consulta_fotos = "SELECT cr.nombre_Reporte AS nombreReporte,
                            rl.titulo_Reporte AS titulo,
                            DATE_FORMAT(rl.fecha_registro,'%d-%m-%Y') AS fecha,
                            DATE_FORMAT(rl.fecha_registro,'%r') AS hora 
                            FROM Reportes_Llenados rl 
                           INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                           WHERE rl.id_Gpo_Valores_Reporte = $idGpo";

        //echo $consulta_fotos;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta_fotos);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerComentariosReportes($idGpo)
    {
        $consulta_fotos = "SELECT cr.id_comentario AS idComentario, cr.id_Usuario AS idUsuario, 
                            CONCAT(eu.nombre,' ',eu.apellido_paterno,' ',ifnull(eu.apellido_materno,'')) AS nombreUsuario, 
                            cr.Comentario_reporte AS comentario, cr.Fecha_Comentario AS fecha 
                            FROM Comentarios_Reportes cr 
                            INNER JOIN Usuarios u ON cr.id_Usuario = u.id_Usuario 
                            INNER JOIN empleados_usuarios eu ON u.`id_Usuario` = eu.`id_usuario` 
                            WHERE cr.id_Gpo = $idGpo AND cr.id_Status = 1 ORDER BY fecha ASC";

        //echo $consulta_fotos;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta_fotos);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            $arr = array();
            $cont = 0;
            foreach ($resultado as $res) {
                $arrComentarios = array(
                    "idComentario" => $res['idComentario'],
                    "idUsuario" => $res['idUsuario'],
                    "nombreUsuario" => $res['nombreUsuario'],
                    "comentario" => $res['comentario'],
                    "fecha" => $res['fecha'],
                    "hora" => $res['hora'],
                    "imagenes" => self::ConsultaImagenesComentarios($res["idComentario"])
                );
                $arr [$cont] = $arrComentarios;
                $cont++;
            }

            return $arr;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function ConsultaImagenesComentarios($idGpo)
    {
        $resultset = "";
        $consulta = "SELECT f.nombre_Fotografia AS nombre,f.fecha_Fotografia AS fecha FROM Fotografias f 
                     WHERE f.identificador_Fotografia = $idGpo AND f.id_Status_Fotografia = 1 AND f.id_Modulo = 7";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $row) {
                    $resultset = $row["nombre"] . ',' . $row['fecha'];

                    //return $row["Areas"];
                }

                //$resultset = substr($resultset, 1);
            }

            return $resultset;

        } catch (PDOException $e) {
            return false;
        }

    }

    public static function InsertaComentariosReportes(
        $idGpoVal,
        $comentario,
        $usuario,
        $fecha, $estatus
    )
    {

        $comando = "INSERT INTO Comentarios_Reportes(
                      id_Gpo
                      ,id_Usuario
                      ,Comentario_reporte
                      ,Fecha_Comentario
                      ,id_Status
                    )VALUES($idGpoVal,$usuario,'$comentario','$fecha',$estatus)";

        //echo $comando;

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getMaxComentarios()
    {
        $consulta = "SELECT MAX(cr.id_comentario) AS id FROM Comentarios_Reportes cr";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["id"];
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function obtenerIdsTipoReportesSeguimiento($area, $empresa)
    {
        $consulta = "SELECT rl.id_Gpo_Valores_Reporte AS gpo, rl.fecha_registro AS fecha FROM Reportes_Llenados rl 
                      INNER JOIN Usuarios u ON rl.`id_Usuario` = u.`id_Usuario`
                      WHERE u.`id_Empresa` = $empresa AND rl.id_Status_Elemento = 0 AND u.`id_Area` = $area";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            /*foreach ($resultado as $row) {
                return $row["idGpo"];
            }*/
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerImagenesUsuario($usuario)
    {
        $consulta = "SELECT * FROM Fotografias f WHERE f.`id_Usuario` = $usuario 
                    AND f.`id_Modulo` = 1 AND f.`id_Status_Fotografia` = 1 ORDER BY f.`fecha_Fotografia` DESC";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerInformacionUbicacion($idGpo, $area)
    {

        if ($area == "2,6") {
            $consulta = "SELECT * FROM(
select sl.id_Gpo as gpo,cs.Id_Sistema as Id_Sistema,C.Valor_Texto AS Tramo,CAST(D.Valor_Texto AS DECIMAL(9,3)) AS Cadenamiento,
cs.Id_Area,sl.Id_status,sl.Identificador AS identificador
FROM Sistemas_Llenados sl
LEFT JOIN (
	SELECT vic.Id_Gpo, MAX(vic.Id_Conf) as idConf FROM Valores_Items_Conf vic
GROUP BY vic.Id_Gpo
) A ON A.Id_Gpo = sl.Id_Gpo
LEFT JOIN (
SELECT vic.Valor_Texto,vic.Id_Gpo FROM Valores_Items_Conf vic
  LEFT JOIN Conf_Items ci ON ci.Id_Conf = vic.Id_Conf
  WHERE ci.Id_Item_Sistema = 175
) C ON C.Id_Gpo = sl.Id_Gpo
LEFT JOIN (
  SELECT vic.Valor_Texto,vic.Id_Gpo FROM Valores_Items_Conf vic
  LEFT JOIN Conf_Items ci ON ci.Id_Conf = vic.Id_Conf
  WHERE ci.Id_Item_Sistema = 1
) D ON D.Id_Gpo = sl.Id_Gpo
LEFT JOIN Conf_Items ci ON ci.Id_Conf = A.idConf
LEFT JOIN CT_Sistema cs ON cs.Id_Sistema = ci.Id_Sistema
) RES WHERE RES.Id_Sistema NOT IN(62,60) AND Id_Status = 0 AND Id_Area = 2 AND gpo = $idGpo";
        } else if ("1,6") {
            $consulta = "SELECT * FROM(
select sl.id_Gpo as gpo,cs.Id_Sistema as Id_Sistema,C.Valor_Texto AS Tramo,CAST(D.Valor_Texto AS DECIMAL(9,3)) AS Cadenamiento,
cs.Id_Area,sl.Id_status,sl.Identificador AS identificador
FROM Sistemas_Llenados sl
LEFT JOIN (
	SELECT vic.Id_Gpo, MAX(vic.Id_Conf) as idConf FROM Valores_Items_Conf vic
GROUP BY vic.Id_Gpo
) A ON A.Id_Gpo = sl.Id_Gpo
LEFT JOIN (
SELECT vic.Valor_Texto,vic.Id_Gpo FROM Valores_Items_Conf vic
  LEFT JOIN Conf_Items ci ON ci.Id_Conf = vic.Id_Conf
  WHERE ci.Id_Item_Sistema = 12
) C ON C.Id_Gpo = sl.Id_Gpo
LEFT JOIN (
  SELECT vic.Valor_Texto,vic.Id_Gpo FROM Valores_Items_Conf vic
  LEFT JOIN Conf_Items ci ON ci.Id_Conf = vic.Id_Conf
  WHERE ci.Id_Item_Sistema = 1
) D ON D.Id_Gpo = sl.Id_Gpo
LEFT JOIN Conf_Items ci ON ci.Id_Conf = A.idConf
LEFT JOIN CT_Sistema cs ON cs.Id_Sistema = ci.Id_Sistema
) RES WHERE RES.Id_Sistema NOT IN(62,60) AND Id_Status = 0 AND Id_Area = 1 AND gpo = $idGpo";
        }

        if ($area == "1,6") {
            $tramo1 = "QUERETARO-IRAPUATO";
            $tramo2 = "LIBRAMIENTO NOROESTE DE QUERETARO";
        } else if ($area == "2,6") {
            $tramo1 = "Queretaro-Irapuato";
            $tramo2 = "Libramiento Nte Qro";
        }

        $cadInicialt1 = 7.0;
        $cadFinalt1 = 78.300;

        $cadInicialt2 = 0.0;
        $cadFinalt2 = 36.400;

        $retorno = "0";
        //echo $consulta."<br/>";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $res) {
                    $tramo = $res['Tramo'];
                    $cadenamiento = $res['Cadenamiento'];

                    if ($tramo1 == $tramo) {
                        // echo $tramo1."---".$tramo."<br/>";
                        if ($cadenamiento >= $cadInicialt1 && $cadenamiento <= $cadFinalt1) {
                            // echo $cadenamiento."---".$cadInicialt1."---".$cadFinalt1."<br/>";
                            $retorno = "1";
                        }
                    } else if ($tramo2 == $tramo) {
                        //echo $tramo2."---".$tramo."<br/>";
                        if ($cadenamiento >= $cadInicialt2 && $cadenamiento <= $cadFinalt2) {
                            //echo $cadenamiento."---".$cadInicialt2."---".$cadFinalt2."<br/>";
                            $retorno = "1";
                        }
                    } else {
                        $retorno = "0";
                    }
                    //echo $tramo2;
                }
            }

            return $retorno;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaInventarioIts(
        $id,
        $marca,
        $modelo,
        $serie,
        $parte,
        $descripcion
    )
    {
        $comando = "INSERT INTO Catalogo_ITS (ID,NO,MARCA,MODELO,SERIE,PARTE,MAC,CANTIDAD,DESCRIPCION,OBSERVACIONES) 
                    VALUES('$id',1,'$marca','$modelo','$serie','$parte','',1,'$descripcion','')";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();

    }//fin insert

    public static function obtenerCorreosUsuarios($idsReporte)
    {
        $resultset = "";

        $consulta = "SELECT mc.mat_Id_Usuario,mc.mat_Correo,mc.mat_Telegram,us.nombre_Usuario,us.apellido_Usuario,us.correo_Usuario,us.id_telegram FROM Matriz_Comunicacion mc 
INNER JOIN Usuarios us ON mc.mat_Id_Usuario = us.id_Usuario
WHERE mc.mat_Id_Reporte = $idsReporte AND mc.mat_Correo = 1";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Usuario"];
                $resultset[] = $row["nombre_Usuario"];
                $resultset[] = $row["apellido_Usuario"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerIdChatUsuarios($idsReporte)
    {
        $resultset = "";

        $consulta = "SELECT mc.mat_Id_Usuario,mc.mat_Correo,mc.mat_Telegram,us.nombre_Usuario,us.apellido_Usuario,us.correo_Usuario,us.id_telegram FROM Matriz_Comunicacion mc 
INNER JOIN Usuarios us ON mc.mat_Id_Usuario = us.id_Usuario
WHERE mc.mat_Id_Reporte = $idsReporte AND mc.mat_Telegram = 1 AND us.id_telegram != ''";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[] = $row["correo_Usuario"];
                $resultset[] = $row["nombre_Usuario"];
                $resultset[] = $row["apellido_Usuario"];
                //return $row["Areas"];
            }
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerReporteIncidencia($idReporte)
    {

        $resultset = '0';

        $consulta = "SELECT cr.incidencia_Reporte FROM Cat_Reportes cr WHERE cr.id_Reporte = $idReporte";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset = $row["incidencia_Reporte"];
            }

            if ($resultset == '0') {
                return false;
            } else {
                return true;
            }

            //return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDatosReporteAlterno($gpo)
    {
        $resultset[] = "";

        $consulta = "SELECT cr.nombre_Reporte AS nombre,
                      rl.titulo_Reporte AS titulo,
                       rl.id_Gpo_Valores_Reporte AS gpo, 
                       CONCAT(u.nombre_Usuario,' ',u.apellido_Usuario) AS nombreUsuario,
                       rl.id_Reporte AS idReporte,
                       rl.fecha_registro AS fecha
                       FROM Reportes_Llenados rl 
                      INNER JOIN Cat_Reportes cr ON rl.id_Reporte = cr.id_Reporte
                      INNER JOIN Usuarios u ON rl.id_Usuario = u.id_Usuario
                      WHERE rl.id_Gpo_Valores_Reporte = $gpo";

        //echo $consulta;

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $resultset[0] = $row["nombre"];
                $resultset[1] = $row["titulo"];
                $resultset[2] = $row["gpo"];
                $resultset[3] = $row["nombreUsuario"];
                $resultset[4] = $row["idReporte"];
                $resultset[5] = $row["fecha"];
            }
            return $resultset;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDatosValores($gpo)
    {
        $consulta = "SELECT vrc.valor_Texto_Reporte AS valor,crc.Secuencia FROM Valores_Reportes_Campos vrc
                      INNER JOIN Conf_Reportes_Campos crc ON vrc.id_Configuracion_Reporte = crc.id_Configuracion_Reporte
                      WHERE vrc.id_Gpo_Valores_Reporte = $gpo ORDER BY crc.Secuencia";

        //echo $consulta;

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            $i = 0;
            foreach ($resultado as $row) {
                $resultset[$i][0] = $row["valor"];
                $resultset[$i][1] = $row["titulo"];

                $i++;
            }
            return $resultset;
            //return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function existeReporte($fechaRegistro, $idUsuario)
    {
        $consulta = "SELECT * FROM Reportes_Llenados rl WHERE rl.fecha_registro = '$fechaRegistro' 
                      AND rl.id_Usuario = $idUsuario";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
                //return true;
            } else {
                //return "0";
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function compruebaDatosUsuario($usuario, $password)
    {
        $consulta = "SELECT CONCAT(us.nombre_Usuario,' ',us.apellido_Usuario) AS nombre,id_Usuario AS usuario FROM VW_getAllUsuarios_Movil us WHERE us.correo_Usuario = '$usuario' AND us.password_Usuario = '$password'";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado AS $row) {
                    $nombre = $row['usuario'];
                }
                return $nombre;
            } else {
                //return "0";
                return "";
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function buscaDispositivoSerie($serie)
    {
        $consulta = "SELECT * FROM Cat_Dispositivos cd WHERE cd.NumeroSerie = '$serie'";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
            } else {
                //return "0";
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaDispositivo($usuario, $marca, $serie)
    {

        $statusSerie = self::buscaDispositivoSerie($serie);

        if (!$statusSerie) {
            $comando = "INSERT INTO Cat_Dispositivos(Marca,id_Usuario,NumeroSerie,Id_Status,Id_Proyecto) VALUES('$marca','$usuario','$serie',1,1)";

            $sentencia = Database::getInstance()->getDb()->prepare($comando);

            if ($sentencia->execute()) {
                return "exito";
            } else {
                return "error";
            }
            //return $sentencia->execute();
        } else {
            $comando = "UPDATE Cat_Dispositivos cd SET cd.Id_Status = 1 WHERE cd.NumeroSerie = '$serie'";
            $sentencia = Database::getInstance()->getDb()->prepare($comando);

            $sentencia->execute();
            return "existe";
        }
    }//insertaDispositivo

    public static function existeCampoReporte($gpoValores, $idConfiguracion)
    {
        $consulta = "SELECT * FROM Valores_Reportes_Campos vr WHERE vr.id_Gpo_Valores_Reporte = $gpoValores AND vr.id_Configuracion_Reporte = $idConfiguracion";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
                //return true;
            } else {
                //return "0";
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function existeConfiguracionCampoReporte($idConfiguracion,$idReporte)
    {
        $consulta = "SELECT * FROM VW_getAllConfReportesCampos vrc WHERE vrc.id_Configuracion_Reporte = $idConfiguracion AND vrc.id_Reporte = $idReporte";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
                //return true;
            } else {
                //return "0";
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getConfCamposReportesIdReporte($idReporte)
    {
        $consulta = "SELECT * FROM VW_getAllConfReportesCampos crc WHERE crc.id_Reporte = $idReporte";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerIdMonitoreo($valor)
    {
        $consulta = "SELECT cmd.idCatMonitoreo AS id,cmd.Concepto AS concepto FROM Cat_Monitoreo_Diario cmd 
                      WHERE cmd.Concepto LIKE '%$valor%'";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado AS $row) {
                    $nombre = $row['id'];
                }
                return $nombre;
            } else {
                //return "0";
                return "0";
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDatosMonitoreoMensual($fecha)
    {
        $consulta = "SELECT * FROM(
                      SELECT
                      rl.id_Gpo_Valores_Reporte AS idGpo,
                      F.valor_Texto_Reporte AS fecha,
                      AC.valor_Texto_Reporte AS actividad,
                      C.valor_Texto_Reporte AS cuerpo,
                      S.valor_Texto_Reporte AS segmento,
                      CI.valor_Texto_Reporte AS catInicial,
                      CF.valor_Texto_Reporte AS catFinal,
                      UA.valor_Texto_Reporte AS ubicacion,
                      MH.valor_Texto_Reporte AS maquinaria,
                      P.valor_Entero_Reporte AS nPersonas,
                      cmd.Concepto AS concepto,
                      cmd.IdPadreCatMonitorio AS idPadre
                    FROM Reportes_Llenados rl
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 1) F ON F.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 31) AC ON AC.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 8) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 9) S ON S.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 6) CI ON CI.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 7) CF ON CF.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 32) UA ON UA.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 33) MH ON MH.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Entero_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 34) P ON P.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN Cat_Monitoreo_Diario cmd ON AC.valor_Texto_Reporte = cmd.idCatMonitoreo
                    WHERE rl.`id_Reporte` = 16) RES WHERE RES.fecha >= '$fecha-01' AND RES.fecha <= '$fecha-31' ORDER BY RES.idPadre,RES.segmento ASC";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerDatosMonitoreoDiario($fecha, $segmento, $idPadre)
    {
        $consulta = "SELECT * FROM(
                      SELECT
                      rl.id_Gpo_Valores_Reporte AS idGpo,
                      F.valor_Texto_Reporte AS fecha,
                      AC.valor_Texto_Reporte AS actividad,
                      C.valor_Texto_Reporte AS cuerpo,
                      S.valor_Texto_Reporte AS segmento,
                      CI.valor_Texto_Reporte AS catInicial,
                      CF.valor_Texto_Reporte AS catFinal,
                      UA.valor_Texto_Reporte AS ubicacion,
                      MH.valor_Texto_Reporte AS maquinaria,
                      P.valor_Entero_Reporte AS nPersonas,
                      cmd.Concepto AS concepto,
                      cmd.IdPadreCatMonitorio AS idPadre
                    FROM Reportes_Llenados rl
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 1) F ON F.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 31) AC ON AC.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 8) C ON C.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 9) S ON S.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 6) CI ON CI.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 7) CF ON CF.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 32) UA ON UA.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Texto_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 33) MH ON MH.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      LEFT JOIN (SELECT vrc.valor_Entero_Reporte,vrc.id_Gpo_Valores_Reporte FROM Valores_Reportes_Campos vrc LEFT JOIN Conf_Reportes_Campos crc ON crc.id_Configuracion_Reporte = vrc.id_Configuracion_Reporte WHERE crc.id_Campo_Reporte = 34) P ON P.id_Gpo_Valores_Reporte = rl.id_Gpo_Valores_Reporte
                      INNER JOIN Cat_Monitoreo_Diario cmd ON AC.valor_Texto_Reporte = cmd.idCatMonitoreo
                    WHERE rl.id_Reporte = 16) RES WHERE RES.fecha = '$fecha' AND RES.Segmento = '$segmento' AND RES.idPadre = $idPadre ORDER BY RES.idPadre,RES.segmento ASC";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function convierteCadenamiento($cadenamiento)
    {
        $cadNuevo = str_replace(".", "+", $cadenamiento);
        $separaCadenamiento = explode("+", $cadNuevo);

        if (count($separaCadenamiento) > 0) {
            $kms = $separaCadenamiento[0];
            $mts = $separaCadenamiento[1];

            if (strlen($kms) == 3) {
                $kms = $kms;
            } else if (strlen($kms) == 2) {
                $kms = "0" . $kms;
            } else if (strlen($kms) == 1) {
                $kms = "00" . $kms;
            }

            if (strlen($mts) == 3) {
                $mts = $mts;
            } else if (strlen($mts) == 2) {
                $mts = "0" . $mts;
            } else if (strlen($mts) == 1) {
                $mts = "00" . $mts;
            }

            $cadenamiento = $kms . "+" . $mts;
        }


        return $cadenamiento;
    }

    public static function obteneridPadreMonitoreoDiario()
    {
        $consulta = "SELECT cmd.idCatMonitoreo AS id,cmd.Acronimo AS acronimo, cmd.Concepto AS concepto FROM Cat_Monitoreo_Diario cmd WHERE cmd.IdPadreCatMonitorio IS NULL";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function obtenerNombreMonitoreo($id)
    {
        $consulta = "SELECT cmd.idCatMonitoreo AS id,cmd.Concepto AS concepto,cmd.Acronimo AS acronimo FROM Cat_Monitoreo_Diario cmd 
                      WHERE cmd.idCatMonitoreo = $id";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado AS $row) {
                    $concepto = $row['concepto'];
                    $acronimo = $row['acronimo'];
                }
                return $acronimo . " " . $concepto;
            } else {
                //return "0";
                return "0";
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getExisteAforo($fecha, $clasAuto, $plaza)
    {
        $consulta = "SELECT * FROM Aforo_copy WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function InsertaAforo(
        $fecha, $clasAuto, $efectivo, $tag, $residente, $eludidos, $vsc, $aforo, $plaza)
    {

        $existe = self::getExisteAforo($fecha, $clasAuto, $plaza);

        if ($existe) {
            switch ($aforo) {
                case 1:
                    $comando = "UPDATE Aforo_copy SET Efectivo = $efectivo WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
                    break;
                case 2:
                    $comando = "UPDATE Aforo_copy SET TAG = $tag WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
                    break;
                case 3:
                    $comando = "UPDATE Aforo_copy SET Residentes = $residente WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
                    break;
                case 4:
                    $comando = "UPDATE Aforo_copy SET Eludidos = $eludidos WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
                    break;
                case 5:
                    $comando = "UPDATE Aforo_copy SET VSC = $vsc WHERE fecha_Aforo = '$fecha' AND id_Clas_Autos = $clasAuto AND id_Plaza = $plaza";
                    break;
            }
        } else {
            $comando = "INSERT INTO Aforo_copy(fecha_Aforo, id_Clas_Autos, Efectivo, TAG, Residentes, Eludidos, VSC, 
                  fecha_Subida, id_Status, id_Plaza, id_Carril) 
                  VALUES ('$fecha',$clasAuto,$efectivo,$tag,$residente,$eludidos,$vsc,DATE(NOW()),1,$plaza,0);";
        }

        echo $comando . '<br>';
        //return "ok";
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getCatalogoCategoria()
    {
        $consulta = "SELECT * FROM Catalogo_categoria";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }


    public static function getNotaBitacora($idReporte)
    {

        $consulta = "SELECT COUNT(rl.id_Gpo_Valores_Reporte) AS nNota FROM Reportes_Llenados rl WHERE rl.id_Reporte = $idReporte AND rl.id_Status_Elemento = 1";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["nNota"] + 1;
            }

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresCampo($idConf)
    {
        $consulta = "SELECT vrc.Valor_Default AS valores,vrc.id_Campo_Reporte AS idCampo FROM VW_getAllConfReportesCampos vrc WHERE vrc.id_Configuracion_Reporte = $idConf";

        $result = "";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $result[0] = $row["valores"];
                $result[1] = $row["idCampo"];
            }

            return $result;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function actualizaValorDefault($idCampo, $valor)
    {

        $comando = "UPDATE Cat_Campos_Reportes vrc SET vrc.Valor_Default = '$valor' WHERE vrc.id_Campo_Reporte = $idCampo";
        //echo $comando;
        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getExisteConceptoCatalogo($concepto)
    {
        $consulta = "SELECT * FROM Catalogo_categoria cc WHERE cc.concepto = '$concepto'";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function InsertaCatalogoCategoria(
        $concepto,
        $categoria
    )
    {

        $comando = "INSERT INTO Catalogo_categoria 
                        (concepto,descripcion,id_Categoria,id_Status) 
                        VALUES ('$concepto','nuevo concepto',$categoria,1)";

        //echo $comando;

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getIdConceptoCatalogo($concepto)
    {
        $consulta = "SELECT cc.idCatalogo AS idCat FROM Catalogo_categoria cc WHERE cc.concepto = '$concepto'";

        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $row) {
                    $result = $row["idCat"];
                }
                return $result;
            } else {
                return "1";
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getElementosById($elemento)
    {

        $consulta = "SELECT * FROM VW_getReportesLlenados_Movil vrl WHERE vrl.id_Reporte = $elemento 
                            AND vrl.id_Status_Elemento = 1";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    /* -------------------nueva informacion -----------------------*/

    public static function getValoresReporteGpo($idReporte)
    {
        $json = array();
        $json1 = array();
        $json2 = array();

        $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil WHERE id_Gpo_Valores_Reporte = $idReporte";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $row);
            }

            $json['Valores'] = $json1;
            $arrayImage = self::getImagenesReporteGpo($idReporte);
            $json['Imagenes'] = $arrayImage;
            //print_r($result);

            return [$json];
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getImagenesReporteGpo($idReporte)
    {
        $consulta = "SELECT * FROM VW_getAllFotografias_Movil WHERE identificador_Fotografia = $idReporte";
        //SELECT * FROM Fotografias f WHERE f.identificador_Fotografia = $idReporte";

        $json1 = array();
        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $row);
            }

            //$json['Valores'] = $json1;
            //print_r($result);

            return $json1;
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getCountNotificacionesUser($user)
    {
        //$consulta = "SELECT COUNT(*) AS total FROM VW_getAllNotificaciones nt WHERE nt.id_usuario_notificacion = $user AND nt.id_status = 1";

        $consulta = "SELECT COUNT(*) AS total FROM Notificacion nt where nt.id_usuarionotificacion = $user 
                        AND nt.id_usuarionotifico NOT IN($user) AND nt.id_Status = 1";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            //echo $resultado;
            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return "0";
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getNotificacionesUser($user)
    {
        $consulta = "SELECT nt.id_notificacion AS idNotificacion,
                            nt.id_notificacion_detalle AS tipoNotificacion,
	                        CONCAT(nt.nombre_usuario_notifico,' ',nt.apellido_usuario_notifico) AS nombreUsuario,
	                        nt.Descripcion AS accion,
	                        nt.titulo_Reporte AS tituloReporte,
	                        nt.id_status AS estatusNotificacion,
	                        nt.Fecha AS fechaNotificacion,
	                        nt.id_Gpo_Valores_ReportesLlenados AS idGpoReporte,
	                        nt.id_Reporte AS idReporte,
	                        nt.id_UsuarioNotificacion AS idUsuario
	                FROM VW_getAllNotificacionesMovil nt WHERE nt.id_UsuarioNotificacion = $user AND nt.id_UsuarioNotifico != $user
	                GROUP BY nt.id_notificacion
	                ORDER BY nt.Fecha DESC";//" GROUP BY nt.id_notificacion";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            //echo $resultado;
            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return "0";
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getCountComentariosReporte($idReporte)
    {
        //$consulta = "SELECT COUNT(*) AS total FROM VW_getAllNotificaciones nt WHERE nt.id_usuario_notificacion = $user AND nt.id_status = 1";

        $consulta = "SELECT COUNT(*) AS tComentarios FROM Comentarios_Reportes cr WHERE cr.id_Gpo = $idReporte 
                        AND cr.id_Status = 1";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            //echo $resultado;
            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return "0";
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function modificaNotificacionEstatus($idNotificacion)
    {

        $comando = "UPDATE Notificacion SET id_status = 0 where id_notificacion = $idNotificacion";
        //echo $comando;

        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getReportesLllenadosBusqueda($palabra, $modulo, $area, $idProyecto,$fechas,$cadReportes)
    {
        $consulta = "SELECT * FROM VW_getReportesLlenados_Movil rl 
                        WHERE rl.clas_Reporte IN($modulo) AND rl.Areas LIKE '%$area%'
                    AND CONCAT(rl.id_Gpo_Valores_Reporte,' ',rl.titulo_Reporte) LIKE '%$palabra%'
                    AND rl.id_Status_Elemento = 1 AND rl.idProyecto = $idProyecto";

        if($cadReportes != ""){
            $consulta .= " AND rl.id_Reporte IN ($cadReportes)";
        }

        if($fechas != ""){
            $fecha1 = substr($fechas,0,10);
            $fecha2 = substr($fechas,11,21);

            $consulta .= " AND rl.fecha_registro >= '$fecha1 00:00:00' AND rl.fecha_registro <= '$fecha2 23:59:59'";
        }

        $consulta .= " ORDER BY fecha_registro DESC LIMIT 30";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getDetallesReporteGpo($idGpo)
    {
        $json = array();
        $json1 = array();
        $json2 = array();

        $consulta = "SELECT * FROM VW_getReportesLlenados_Movil vrl WHERE vrl.id_Gpo_Valores_Reporte = $idGpo";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $row);
            }

            $json['Reporte'] = $json1;
            $json['Valores'] = self::getValoresReportesOnline($idGpo);
            $arrayImage = self::getImagenesReporteGpo($idGpo);
            $json['Imagenes'] = $arrayImage;
            //print_r($result);

            return [$json];
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getValoresReportesOnline($idGpo)
    {
        $consulta = "SELECT * FROM VW_getAllValoresReportes_Movil WHERE id_Gpo_Valores_Reporte = $idGpo";
        //SELECT * FROM Fotografias f WHERE f.identificador_Fotografia = $idReporte";

        $json1 = array();
        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $row);
            }

            //$json['Valores'] = $json1;
            //print_r($result);

            return $json1;
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getProyectos()
    {
        $consulta = "SELECT * FROM Proyectos";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getUsuariosProyectos()
    {
        $consulta = "SELECT * FROM Usuarios_Proyectos";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function ObtenerUsuariosProyectos($idUsuario)
    {

        $result = "";
        $consulta = "SELECT * FROM Usuarios_Proyectos up WHERE up.id_Usuario = $idUsuario AND up.id_Status = 1";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                foreach ($resultado as $row) {
                    $result = $result . ',' . $row['id_Proyecto'];
                }
                //return substr($result,1);
            } else {
                $result = ",0";
                //return $result;
            }
            //echo substr($result,1);
            return substr($result, 1);

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getProcesos()
    {

        $result = "";
        $consulta = "SELECT * FROM Procesos p";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function registrarIdChatUsuario($idUsuario, $idChat)
    {
        $comando = "UPDATE Usuarios us SET us.id_telegram = '$idChat' 
                    WHERE us.id_Usuario = $idUsuario";

        //echo $comando;
        // Preparar la sentencia
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        return $sentencia->execute();
    }

    public static function getGantt()
    {
        $consulta = "SELECT * FROM gantt";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getGanttValores($fecha)
    {
        if($fecha != "") {
            $consulta = "SELECT gv.id,gv.actividad,
                                gv.id_nodo,gv.id_nodo_padre,
                                gv.id_status,gv.id_gantt,gv.wbs,
                                gv.id_reporte FROM gantt_valores gv 
                            INNER JOIN gantt g ON gv.id_gantt = g.id
                            WHERE g.fecha >= '$fecha'";
        }else{
            $consulta = "SELECT gv.id,gv.actividad,
                                gv.id_nodo,gv.id_nodo_padre,
                                gv.id_status,gv.id_gantt,gv.wbs,
                                gv.id_reporte FROM gantt_valores gv";
        }

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getActividadAvance($fecha)
    {

        if($fecha == ""){
            $consulta = "SELECT * FROM avance_actividad aa";
        }else{
            $consulta = "SELECT * FROM avance_actividad aa WHERE aa.fecha > '$fecha'";
        }

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getEmpleados()
    {
         $consulta = "SELECT em.id_empleado,em.nombre,
	                         em.apellido_paterno,
	                         IFNULL(em.apellido_materno,'') AS apilledo_materno,
	                         em.status 
	                        FROM empleados em";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getGpoActividadAvance($idNodo,$idProyecto)
    {

        $consulta = "SELECT * FROM avance_actividad aa WHERE aa.id_nodo = $idNodo AND aa.id_proyecto = $idProyecto";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

}
