<?php

class DataBaseGeneral
{

    private static $db = null;

    /**
     * Instancia de PDO
     */
    private static $pdo;

    final private function __construct()
    {
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

        if ($this->url == 'supervisor.uno') {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                    'mysql:dbname=supervis_supervisor_InnoDB_General' .
                    ';host=localhost' .
                    ';port:;', // Eliminar este elemento si se usa una instalación por defecto
                    'supervis_GENERAL_SYS',
                    'y;g3z~^7IkPD',
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
                // Habilitar excepciones
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }else {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                    'mysql:dbname=getitsup_supervisor_General' .
                    ';host=localhost' .
                    ';port:;', // Eliminar este elemento si se usa una instalación por defecto
                    'getitsup_supervisor_general_SYS',
                    '+3h^(mR4he*?',
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
                // Habilitar excepciones
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }

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
