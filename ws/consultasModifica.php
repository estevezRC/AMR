<?php
require_once 'DataBase.php';

class ConsultasModifica{
    function __construct()
    {
    }

    public static function getImagenesReporteGpo($idGpoValores,$modulo)
    {
        $consulta = "SELECT * FROM Fotografias fo 
                        WHERE fo.identificador_Fotografia = $idGpoValores
                        AND fo.id_Modulo = $modulo";

        $json1 = array();
        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                array_push($json1, $row);
            }
            return $json1;
        } catch (PDOException $e) {
            return false;
        }
    }//getCatReportes


    public static function getJsonReportesGpoValores($idGpoValores){
        $json = array();
        $json1 = array();
        $json2 = array();

        $consulta = "SELECT * FROM Reportes_Llenados rl WHERE rl.id_Gpo_Valores_Reporte = $idGpoValores";
        try {
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //$idGpo = $row['Id_Gpo'];
                //$idsReportes = self::getReportesUbicaciones($idGpo);
                $row["Valores"] = self::getValoresReportes($idGpoValores);
                $row["Imagenes"] = self::getImagenesReporteGpo($idGpoValores,1);
                $json2['Reporte'] = $row;
                //array_push($json1, $json2);
            }

            //$json['Reportes'] = $json2;
            return $json2;
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }//getJsonUbicaciones

    public static function getValoresReportes($idGpoValores)
    {
        $consulta = "SELECT * FROM Valores_Reportes_Campos vrc WHERE vrc.id_Gpo_Valores_Reporte = $idGpoValores";

        $json1 = array();
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                array_push($json1, $row);
            }

            return $json1;

        } catch (PDOException $e) {
            return false;
        }
    }//getCatReportes

}
