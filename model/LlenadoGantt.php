<?php

class LlenadoGantt extends EntidadBase
{
    private $id;
    private $actividad;
    private $inicio;
    private $fin;
    private $idNodo;
    private $porcentaje;
    private $costo;
    private $wbs;
    private $connectTo;
    private $idNodoPadre;
    private $idStatus;
    private $idGantt;
    private $idReporte;

    public function __construct($adapter)
    {
        $table = "gantt_valores";
        parent::__construct($table, $adapter);
    }

    //ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    //NOMBRE
    public function getActividad()
    {
        return $this->actividad;
    }

    public function setActividad($actividad)
    {
        $this->actividad = $actividad;
    }

    //APELLIDO
    public function getInicio()
    {
        return $this->inicio;
    }

    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    //CORREO
    public function getFin()
    {
        return $this->fin;
    }

    public function setFin($fin)
    {
        $this->fin = $fin;
    }

    public function getIdNodo()
    {
        return $this->idNodo;
    }

    public function setIdNodo($idNodo)
    {
        $this->idNodo = $idNodo;
    }

    //PASSWORD
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;
    }

    //costo
    public function getCosto()
    {
        return $this->costo;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    public function getWbs()
    {
        return $this->wbs;
    }

    public function setWbs($wbs)
    {
        $this->wbs = $wbs;
    }

    public function getConnectTo()
    {
        return $this->connectTo;
    }

    public function setConnectTo($connectTo)
    {
        $this->connectTo = $connectTo;
    }

    //FECHA
    public function getIdNodoPadre()
    {
        return $this->idNodoPadre;
    }

    public function setIdNodoPadre($idNodoPadre)
    {
        $this->idNodoPadre = $idNodoPadre;
    }

    //FOOGRAFIA
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    public function setIdStatus($idStatus)
    {
        $this->idStatus = $idStatus;
    }

    public function getIdGantt()
    {
        return $this->idGantt;
    }

    public function setIdGantt($idGantt)
    {
        $this->idGantt = $idGantt;
    }

    public function getIdReporte()
    {
        return $this->idReporte;
    }

    public function setIdReporte($idReporte)
    {
        $this->idReporte = $idReporte;
    }

    /*--- LLENADO REPORTE: REGISTRAR LLENADO---*/
    public function guardar()
    {
        $query = "INSERT INTO gantt_valores (actividad, inicio, fin,id_nodo, id_nodo_padre, porcentaje, costo, wbs,
                connect_to, id_status, id_gantt, id_reporte, fecha_registro)
            VALUES ('$this->actividad', '$this->inicio', '$this->fin', $this->idNodo, $this->idNodoPadre, $this->porcentaje,
                    $this->costo,'$this->wbs', $this->connectTo ,$this->idStatus, $this->idGantt, $this->idReporte, NOW())";

//        $query = "INSERT INTO gantt (actividad, inicio, fin, id_padre, porcentaje, id_status, id_proyecto)
//            VALUES ('hola', '2020-02-27 12:00:00', '2020-02-28 12:00:00', 0, 10,
//                    1, 1)";

        return $this->db()->query($query);
    }

    /*--- USUARIOS: ACTUALIZAR USUARIO POR ID ---*/
    public function guardarConector($nodo, $nodoEnlace, $idGantt)
    {
        $query = "UPDATE gantt_valores SET connect_to = $nodoEnlace WHERE id_nodo = $nodo AND id_gantt = $idGantt;";

        return $this->db()->query($query);
    }

    /*--- USUARIOS: ACTUALIZAR USUARIO POR ID ---*/
    public function guardarPorcentaje($WBS, $porcentaje, $idGantt)
    {
        // $query = "UPDATE gantt_valores SET porcentaje = $porcentaje WHERE id_nodo = $nodo AND id_gantt = $idGantt";
        $query = "UPDATE gantt_valores SET porcentaje = $porcentaje WHERE wbs = '$WBS' AND id_gantt = $idGantt";
        return $this->db()->query($query);
    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function modificarUsuarioDatos($id, $usuarios)
    {
        $row_cnt = 1;
        $correos = "";
        foreach ($usuarios as $usuario) {
            if (($usuario->correo_Usuario == $this->usuariocorreo) && ($usuario->id_Usuario != $id)) {
                $row_cnt = $row_cnt + 1;
            }
        }
        if ($row_cnt > 1) {
            return 2;
        } else {
            $query = "CALL SP_AddUpUsuario(
  					$id,
  					NULL,
  					NULL,
  					'$this->usuarionombre',
  					'$this->usuarioapellido',
  					'$this->usuariocorreo',
  					NULL,
  					NULL,
  					NULL,
  					NULL,
  					'Modificardatos')";
            $this->db()->query($query);
            return 3;
        }
    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function modificarUsuarioPwd($id)
    {
        $query = "CALL SP_AddUpUsuario(
  					$id,
  					NULL,
  					NULL,
  					NULL,
  					NULL,
  					NULL,
  					'$this->usuariopassword',
  					NULL,
  					NULL,
  					NULL,
  					'Modificarpwd')";
        $this->db()->query($query);
        $mensaje = "Se han actualizado los datos del usuario " . $this->usuariocorreo . "";
        return $mensaje;

    }

    /*--- USUARIOS: ACTUALIZAR DATOS PERSONALES DE USUARIO POR ID ---*/
    public function guardarkey($id, $publickey, $privatekey)
    {
        $query = "UPDATE Usuarios SET llave_privada='$privatekey',llave_publica='$publickey'
WHERE id_Usuario = $id";
        $this->db()->query($query);
        $mensaje = 5;
        return $mensaje;

    }

    /*--- USUARIOS: ACTUALIZAR NIP DE USUARIO ---*/
    public function guardarNip($id, $nip)
    {
        $query = "UPDATE Usuarios SET nip_Usuario='$nip' WHERE id_Usuario = $id";
        $this->db()->query($query);
        $mensaje = 6;
        return $mensaje;
    }

    public function restaurarUsuario($id_Usuario)
    {
        $query = "UPDATE Usuarios SET id_Status_Usuario = 1 WHERE id_Usuario = $id_Usuario";
        $this->db()->query($query);
        $mensaje = 1;
        return $mensaje;

    }

}

