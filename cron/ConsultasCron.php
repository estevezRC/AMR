<?php

require_once __DIR__.'/../ws/DataBase.php';

class ConsultasCron
{

    function __construct()
    {
    }

    public static function sendMessageTelegram($chatId, $text)
    {
        $TOKEN = "1070413462:AAH_vUE3xMnYVtqJVwnxrJWh5fd8LDT58Go";
        $TELEGRAM = "https://api.telegram.org:443/bot$TOKEN";

        $query = http_build_query(array(
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => "HTML"));
        $response = file_get_contents("$TELEGRAM/sendMessage?$query");
        return $response;
    }

    public static function contadorReportes($fecha)
    {
        $consulta = "SELECT p.id_Proyecto,p.nombre_Proyecto as nombreProyecto, COUNT(vwrl.id_Gpo_Valores_Reporte) as total 
                    FROM Proyectos p
                    INNER JOIN VW_getAllReportesLlenados as vwrl on vwrl.id_Proyecto = p.id_Proyecto
                    INNER JOIN Usuarios as us on vwrl.id_Usuario = us.id_usuario
                    INNER JOIN empleados_usuarios as eus on us.id_Usuario = eus.id_usuario
                    WHERE p.id_Status_Proyecto = 1 AND eus.id_empleado_usuario NOT IN(1,2,18)  
                    AND vwrl.id_Status_Elemento = 1  AND vwrl.tipo_Reporte IN (0,1,2,3,4,9) 
                    AND vwrl.fecha_registro >= '2020-01-01 00:00:00' AND vwrl.fecha_registro <= '2020-12-12 23:59:59' group by p.id_Proyecto";
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

    public static function contadorReportesByUsuario($fecha)
    {
        $consulta = "SELECT us.id_Usuario, CONCAT(eus.nombre , ' ', eus.apellido_paterno, ' ', eus.apellido_materno) as nombre, us.correo_Usuario,
                    (SELECT COUNT(vwrl.id_Gpo_Valores_Reporte) FROM VW_getAllReportesLlenados vwrl 
                    WHERE vwrl.id_Usuario = us.id_Usuario AND vwrl.id_Status_Elemento = 1 AND vwrl.tipo_Reporte IN (0,1,2,3) 
                    AND vwrl.fecha_registro >= '2020-01-01 00:00:00' AND vwrl.fecha_registro <= '2020-11-30 23:59:59') as total
                    FROM Usuarios as us
                    INNER JOIN empleados_usuarios as eus on us.id_Usuario = eus.id_usuario";
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

    public static function contadorReportesByReporte($fecha)
    {
        $consulta = "SELECT vwrl.id_Usuario,
                    CONCAT(vwrl.nombre_Usuario, ' ', vwrl.apellido_Usuario) as nombre,
                    COUNT(vwrl.id_Gpo_Valores_Reporte) as total,
                    SUM(IF(vwrl.tipo_Reporte = '0',1,0)) as Reportes,
                    SUM(IF(vwrl.tipo_Reporte = '1',1,0)) as Incidencias,
                    SUM(IF(vwrl.tipo_Reporte = '2',1,0)) as Ubicaciones,
                    SUM(IF(vwrl.tipo_Reporte = '3',1,0)) as Inventarios,
                    SUM(IF(vwrl.tipo_Reporte = '4', 1, 0)) AS Seguimientos_Incidentes,
                    SUM(IF(vwrl.tipo_Reporte = '9', 1, 0)) AS Minuta
                    FROM VW_getAllReportesLlenados vwrl
                    INNER JOIN Usuarios as us on vwrl.id_Usuario = us.id_usuario
                    INNER JOIN empleados_usuarios as eus on us.id_Usuario = eus.id_usuario
                    INNER JOIN Proyectos as p on vwrl.id_Proyecto = p.id_Proyecto
                    WHERE vwrl.id_Usuario = us.id_Usuario AND vwrl.id_Status_Elemento = 1 AND vwrl.tipo_Reporte IN (0,1,2,3,4,9) 
                    AND p.id_Status_Proyecto = 1  AND eus.id_empleado_usuario NOT IN(1,2,18)
                    AND vwrl.fecha_registro >= '2020-01-01 00:00:00' AND vwrl.fecha_registro <= '2020-12-12 23:59:59' GROUP BY vwrl.id_Usuario";
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



}