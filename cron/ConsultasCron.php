<?php

require_once __DIR__.'/../ws/DataBase.php';

class ConsultasCron
{

    function __construct()
    {
    }

    public static function contadorReportes($fecha)
    {
        $consulta = "SELECT p.id_Proyecto,p.nombre_Proyecto as nombreProyecto, (SELECT COUNT(vwrl.id_Gpo_Valores_Reporte) FROM VW_getAllReportesLlenados vwrl 
                        WHERE vwrl.id_Proyecto = p.id_Proyecto AND vwrl.id_Status_Elemento = 1 AND vwrl.tipo_Reporte IN (0,1,4,6) 
                        AND vwrl.fecha_registro >= '$fecha 00:00:00' AND vwrl.fecha_registro <= '$fecha 23:59:59') total
                        FROM Proyectos p
                        WHERE p.id_Status_Proyecto = 1
                    group by p.id_Proyecto";
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