<?php

//CONECTAR A LA BASE DE DATOS
class Conectar
{
    private $driver;
    private $host, $user, $pass, $database, $charset;
    public $dominio;

    public function __construct()
    {

        $this->url = $_SERVER["SERVER_NAME"];
        $this->driver = "mysql";
        $this->host = "localhost";

        if ($this->url == 'get-s.dev') {
            $this->user = "getitsup_getitsupervisor";
            $this->pass = "9R\$AR(MVT-fd";
            $this->database = 'getitsup_supervisor_Amr_7';
        } else {
            $this->user = "supervis_superad";
            $this->pass = "B@se89*12_";
            $this->database = 'supervis_supervisor_InnoDB_Amr_7';
        }
        $this->charset = "utf8";
    }

    public function conexion()
    {
        if ($this->driver == "mysql" || $this->driver == null) {
            $con = new mysqli("localhost", "$this->user", "$this->pass",
                "$this->database");
            $con->query("SET NAMES '" . $this->charset . "'");
        }
        return $con;
    }

    public function startFluent()
    {
        require_once "FluentPDO/FluentPDO.php";

        if ($this->driver == "mysql" || $this->driver == null) {
            $pdo = new PDO($this->driver . ":dbname=" . $this->database, $this->user, $this->pass);
            $fpdo = new FluentPDO($pdo);
        }
        return $fpdo;
    }
}
