<?php

require_once '../ws/DataBase.php';

class Consultas
{

    function __construct()
    {
    }

    public static function getReporteJsonId($idReporte)
    {
        $json = array();
        $json1 = array();
        $json2 = array();

        $consulta = "SELECT * FROM Cat_Reportes cr WHERE cr.id_Reporte = $idReporte";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                $idReporte = $row['id_Reporte'];
                $config = self::getConfiguracionesReportesJson($idReporte);
                $row["configuraciones"] = $config;
                $json2['Reporte'] = $row;
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $json2);
            }

            $json['CT_Reportes'] = $json1;
            //$json['Valores'] = self::getValoresReportesOnline($idGpo);
            //print_r($result);

            return $json;
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getConfiguracionesReportesJson($idReporte)
    {
        $json = array();
        $json1 = array();
        $json2 = array();

        $consulta = "SELECT * FROM VW_getAllConfReportesCampos crc WHERE crc.id_Reporte = $idReporte";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                //$idSistema = $row['Id_Sistema'];
                //print_r($row);
                //echo '<br/><br/>';
                array_push($json1, $row);
            }

            $json['Configuracion'] = $json1;
            //$json['Valores'] = self::getValoresReportesOnline($idGpo);
            //print_r($result);

            return $json1;
            //$comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaReporte(
        $idProyecto,
        $nombreReporte,
        $descripcionReporte,
        $areas,
        $tipoReporte
    )
    {

        $consulta = "SELECT cr.id_Reporte as idReporte FROM Cat_Reportes cr 
                            WHERE cr.nombre_Reporte = '$nombreReporte' AND cr.id_Proyecto = $idProyecto";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();
            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                $return = "0";
                foreach ($resultado AS $res) {
                    $return = $res['idReporte'];
                }
                //return self::insertaCatReportes($idProyecto,$nombreReporte.'_Copy',$descripcionReporte,$areas,$tipoReporte);
                return $return;
            } else {
                return self::insertaCatReportes($idProyecto, $nombreReporte, $descripcionReporte, $areas, $tipoReporte);
            }

        } catch (PDOException $e) {
            return false;
        }
    }//fin insertaReporte

    public static function insertaCatReportes(
        $idProyecto,
        $nombreReporte,
        $descripcionReporte,
        $areas,
        $tipoReporte
    )
    {

        $idReporte = self::getMaxIdReporte();
        $comando = "INSERT INTO Cat_Reportes(
                    id_Proyecto
                    ,nombre_Reporte
                    ,descripcion_Reporte
                    ,id_Status_Reporte
                    ,Areas
                    ,tiempo_Reporte
                    ,tiempo_Alarma
                    ,tiempo_Revision
                    ,tipo_Reporte
                    ,perfiles_firma
                    ,id_Reporte_Seguimiento
                    )VALUES($idProyecto,'$nombreReporte','$descripcionReporte',1,'$areas',0,0,0,$tipoReporte,'1',0)";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        if ($sentencia->execute()) {
            return $idReporte;
        } else {
            return false;
        }
    }//InsertaCatReportes

    public static function insertaCampos(
        $idProyecto,
        $nombreCampo,
        $descripcion,
        $tipoValor,
        $tipoReactivo,
        $status,
        $valorDefault
    )
    {

        $consulta = "SELECT ccr.id_Campo_Reporte AS idCampo FROM Cat_Campos_Reportes ccr 
                        WHERE ccr.nombre_Campo = '$nombreCampo' 
                        AND ccr.tipo_Valor_Campo = '$tipoValor'
                        AND ccr.tipo_Reactivo_Campo = '$tipoReactivo'
                        AND ccr.Valor_Default = '$valorDefault'";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();
            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                $return = "0";
                foreach ($resultado AS $res) {
                    $return = $res['idCampo'];
                }

                return $return;
            } else {
                return self::insertaCamposReportes($idProyecto, $nombreCampo, $descripcion, $tipoValor, $tipoReactivo, $status, $valorDefault);
            }

        } catch (PDOException $e) {
            return false;
        }
    }//fin insertaReporte

    public static function insertaCamposReportes(
        $idProyecto,
        $nombreCampo,
        $descripcion,
        $tipoValor,
        $tipoReactivo,
        $status,
        $valorDefault
    )
    {
        $idCampo = self::getMaxIdCampos();
        $comando = "INSERT INTO Cat_Campos_Reportes(
                    id_Proyecto
                    ,nombre_Campo
                    ,descripcion_Campo
                    ,tipo_Valor_Campo
                    ,tipo_Reactivo_Campo
                    ,id_Status_Campo
                    ,Valor_Default
                    )VALUES($idProyecto,'$nombreCampo','$descripcion','$tipoValor','$tipoReactivo',$status,'$valorDefault')";

        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        if ($sentencia->execute()) {
            return $idCampo;
        } else {
            return false;
        }
    }//insertaCamposReportes

    public static function getMaxIdCampos()
    {
        $consulta = "SELECT MAX(ccr.id_Campo_Reporte) AS idCampo FROM Cat_Campos_Reportes ccr";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idCampo"] + 1;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function insertaConfiguraciones(
        $idProyecto,
        $idReporte,
        $idCampoReporte,
        $status,
        $necesario,
        $secuencia
    )
    {

        $consulta = "SELECT crc.id_Configuracion_Reporte AS idConf FROM Conf_Reportes_Campos crc 
                WHERE crc.id_Reporte = $idReporte
                AND crc.id_Campo_Reporte = $idCampoReporte";

        //echo $consulta;
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);
            $comando->execute();
            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                $return = "0";
                foreach ($resultado AS $res) {
                    $return = $res['idConf'];
                }
                return $return;
            } else {
                return self::insertaConfiguracionReporte($idProyecto, $idReporte, $idCampoReporte, $status, $necesario, $secuencia);
                //return "inserta conf";
            }
        } catch (PDOException $e) {
            return false;
        }
    }//fin insertaReporte

    public static function insertaConfiguracionReporte(
        $idProyecto,
        $idReporte,
        $idCampoReporte,
        $status,
        $necesario,
        $secuencia)
    {

        $idConfiguracion = self::getMaxConfiguracion();
        $comando = "INSERT INTO Conf_Reportes_Campos(
                    id_Proyecto,
                    id_Reporte,
                    id_Campo_Reporte,
                    id_Status_Reporte,
                    Campo_Necesario,
                    Secuencia
                    )VALUES ($idProyecto,$idReporte,$idCampoReporte,$status,$necesario,$secuencia)";

        //echo $comando;
        $sentencia = Database::getInstance()->getDb()->prepare($comando);

        if ($sentencia->execute()) {
            return $idConfiguracion;
        } else {
            return false;
        }
    }//insertaConfiguracion

    public static function getMaxConfiguracion()
    {
        $consulta = "SELECT MAX(crc.id_Configuracion_Reporte) AS idConfiguracion FROM Conf_Reportes_Campos crc";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idConfiguracion"] + 1;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

    public static function getMaxIdReporte()
    {
        $consulta = "SELECT MAX(cr.id_Reporte) AS idReporte FROM Cat_Reportes cr";
        try {
            $comando = DataBase::getInstance()->getDb()->prepare($consulta);

            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                return $row["idReporte"] + 1;
            }
        } catch (PDOException $e) {
            return false;
        }
    }//getMaxGpoValores

}
