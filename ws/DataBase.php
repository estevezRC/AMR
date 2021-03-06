<?php

class DataBase
{

    private static $db = null;
    private $idEmpresa = 1;

    /**
     * Instancia de PDO
     */
    private static $pdo;

    final private function __construct()
    {
        $this->idEmpresa = $_SESSION['id_empresa_movil'];
        try {
            // Crear nueva conexión PDO
            self::getDb();
        } catch (PDOException $e) {
            // Manejo de excepciones
            print $e->getMessage();
        }

    }

    /**
     * Retorna en la única instancia de la clase
     * @return Database|null
     */
    public static function getInstance()
    {
        if (self::$db === null) {
            self::$db = new self();
        }
        return self::$db;
    }

    /**
     * Crear una nueva conexión PDO basada
     * en los datos de conexión
     * @return PDO Objeto PDO
     */

    public function getDb()
    {

        $this->url = $_SERVER["SERVER_NAME"];
        $this->driver = "mysql";
        $this->host = "localhost";

        if ($this->url == 'get-s.dev') {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                    'mysql:dbname=getitsup_supervisor_Amr_7' .
                    ';host=localhost' .
                    ';port:;', // Eliminar este elemento si se usa una instalación por defecto
                    'getitsup_AMR_SYS',
                    '2&4dWJQ?I2}d',
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );

                // Habilitar excepciones
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }else {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                    'mysql:dbname=supervis_supervisor_InnoDB_Amr_7' .
                    ';host=localhost' .
                    ';port:;', // Eliminar este elemento si se usa una instalación por defecto
                    'supervis_AMR_SYS',
                    'S^hLi_3;I-N!',
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
                // Habilitar excepciones
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }
        //echo $url;

        return self::$pdo;
    }

    final protected function __clone()
    {
    }

    function _destructor()
    {
        self::$pdo = null;
    }

}
