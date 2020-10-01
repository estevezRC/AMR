<?php

require_once 'DataBaseGeneral.php';

class ConsultasGeneral
{

    function __construct()
    {
    }

    public static function getUsuarios()
    {
        $consulta = "SELECT * FROM VW_getAllUsuarios_Movil";

        //echo $consulta;
        try {
            // Preparar sentencia
            $comando = DatabaseGeneral::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }//getCatReportes

    public static function compruebaDatosUsuario($usuario, $password)
    {
        $consulta = "SELECT * FROM VW_getAllUsuarios_Movil us WHERE us.correo_Usuario = '$usuario' AND us.password_Usuario = '$password'";

        echo $consulta;
        try {
            $comando = DatabaseGeneral::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function datosUsuario($idUsuario, $idEmpresa)
    {
        $consulta = "SELECT * FROM VW_getAllUsuarios_Movil us WHERE us.id_Usuario_Empresa = $idUsuario AND us.id_Empresa = $idEmpresa";

        //echo $consulta;
        try {
            $comando = DatabaseGeneral::getInstance()->getDb()->prepare($consulta);
            $comando->execute();

            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultado) > 0) {
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertaDispositivo($usuario, $empresa, $marca, $modelo, $serie)
    {

        $statusSerie = self::buscaDispositivoSerie($serie, false);

        if (!$statusSerie) {
            $comando = "INSERT INTO Dispositivos(id_Usuario,id_Empresa,Marca,Modelo,NumeroSerie,Id_Status) VALUES($usuario,$empresa,'$marca','$modelo','$serie',1)";

            //echo $comando;

            $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($comando);

            if ($sentencia->execute()) {
                return "exito";
            } else {
                return "error";
            }
            //return $sentencia->execute();
        } else {
            $comando = "UPDATE Dispositivos cd SET cd.Id_Status = 1 WHERE cd.NumeroSerie = '$serie'";
            $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($comando);

            $sentencia->execute();
            return "existe";
        }
    }//insertaDispositivo

    public static function buscaDispositivoSerie($serie, $estatus)
    {
        $consulta = "SELECT * FROM Dispositivos cd WHERE cd.NumeroSerie = '$serie' AND cd.id_Status = 1";

        if (!$estatus) {
            $consulta = "SELECT * FROM Dispositivos cd WHERE cd.NumeroSerie = '$serie'";
        } else {
            $consulta = "SELECT * FROM Dispositivos cd WHERE cd.NumeroSerie = '$serie' AND cd.id_Status = 1";
        }

        //echo $consulta;
        try {
            $comando = DatabaseGeneral::getInstance()->getDb()->prepare($consulta);
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

    /******************************************************************************************************************/
    /***************************** INSERTAR O MODIFICAR DATOS DESDE ESPECIFICA A GENERAL ******************************/
    /******************************************************************************************************************/

    /***************************************************** USUARIOS ***************************************************/
    public function InsertarDatosUser($id_Usuario_Empresa, $id_Empresa, $Nombre, $Apellido, $Correo, $Password)
    {
        $query = "CALL SP_Add_Up_Usuario(
  					NULL, $id_Usuario_Empresa, $id_Empresa, '$Nombre', '$Apellido', '$Correo', '$Password', 0,now(),'Insertar')";

        $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($query);

        if ($sentencia->execute()) {
            return "exito";
        } else {
            return "error";
        }
    }

    public function ActualizarDatosUser($id_Usuario_Empresa, $id_Empresa, $Nombre, $Apellido, $Correo)
    {
        $query = "CALL SP_Add_Up_Usuario(
        NULL, $id_Usuario_Empresa, $id_Empresa, '$Nombre', '$Apellido', '$Correo', '', 0,now(),'Modificardatos')";

        //echo $query;
        // /*
        $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($query);

        if ($sentencia->execute()) {
            return "exito";
        } else {
            return "error";
        }
        // */

    }

    public function ActualizarPwd($id_Usuario_Empresa, $id_Empresa, $valor)
    {
        $query = "CALL SP_Add_Up_Usuario(
        NULL, $id_Usuario_Empresa, $id_Empresa, '', '', '', '$valor', 0,now(),'Modificarpwd')";

        // /*
        $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($query);

        if ($sentencia->execute()) {
            return "exito";
        } else {
            return "error";
        }
        // */
        //return $query;

    }

    public function ModificarStatusUser($id_Usuario_Empresa, $id_Empresa, $status)
    {
        $query = "UPDATE Usuarios SET Status = $status WHERE id_Usuario_Empresa = $id_Usuario_Empresa AND id_Empresa = $id_Empresa";

        $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($query);

        if ($sentencia->execute()) {
            return "exito";
        } else {
            return "error";
        }
    }
    /***************************************************** USUARIOS ***************************************************/


    /***************************************************** EMPRESAS ***************************************************/
    public function ActualizarDatosEmpresa($id_EmpresaGral, $nombre, $telefono, $celular, $correo, $rol, $descripcion)
    {
        $query = "CALL sp_Add_Up_Empresas($id_EmpresaGral, NULL, '$nombre', '$telefono', '$celular', '$correo', '$rol', '$descripcion', 'Modificar')";

        $sentencia = DatabaseGeneral::getInstance()->getDb()->prepare($query);

        if ($sentencia->execute()) {
            return "exito";
        } else {
            return "error";
        }

    }

    /***************************************************** EMPRESAS ***************************************************/

    /******************************************************************************************************************/
    /***************************** INSERTAR O MODIFICAR DATOS DESDE ESPECIFICA A GENERAL ******************************/
    /******************************************************************************************************************/


}
