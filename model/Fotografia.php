<?php

class Fotografia extends EntidadBase
{
    private $id_Fotografia;
    private $id_Usuario;
    private $id_Modulo;
    private $identificador_Fotografia;
    private $directorio_Fotografia;
    private $nombre_Fotografia;
    private $fecha_Fotografia;
    private $hora_Fotografia;
    private $latitud_Fotografia;
    private $altitud_Fotografia;
    private $longitud_Fotografia;
    private $descripcion_Fotografia;
    private $id_Status_Fotografia;
    private $orientacion_Fotografia;

    public function __construct($adapter)
    {
        $table = "Fotografias";
        parent::__construct($table, $adapter);
    }

    //ID
    public function get_id_Fotografia()
    {
        return $this->id_Fotografia;
    }

    public function set_id_Fotografia($id_Fotografia)
    {
        $this->id_Fotografia = $id_Fotografia;
    }

    //ID USUARIO
    public function get_id_Usuario()
    {
        return $this->id_Usuario;
    }

    public function set_id_Usuario($id_Usuario)
    {
        $this->id_Usuario = $id_Usuario;
    }

    //ID MODULO
    public function get_id_Modulo()
    {
        return $this->id_Modulo;
    }

    public function set_id_Modulo($id_Modulo)
    {
        $this->id_Modulo = $id_Modulo;
    }

    //ID FOTOGRAFIA
    public function get_identificador_Fotografia()
    {
        return $this->identificador_Fotografia;
    }

    public function set_identificador_Fotografia($identificador_Fotografia)
    {
        $this->identificador_Fotografia = $identificador_Fotografia;
    }

    //DIRECTORIO FOTOGRAFIA
    public function get_directorio_Fotografia()
    {
        return $this->directorio_Fotografia;
    }

    public function set_directorio_Fotografia($directorio_Fotografia)
    {
        $this->directorio_Fotografia = $directorio_Fotografia;
    }

    //NOMBRE FOTOGRAFIA
    public function get_nombre_Fotografia()
    {
        return $this->nombre_Fotografia;
    }

    public function set_nombre_Fotografia($nombre_Fotografia)
    {
        $this->nombre_Fotografia = $nombre_Fotografia;
    }

    //FECHA FOTOGRAFIA
    public function get_fecha_Fotografia()
    {
        return $this->fecha_Fotografia;
    }

    public function set_fecha_Fotografia($fecha_Fotografia)
    {
        $this->fecha_Fotografia = $fecha_Fotografia;
    }

    //HORA FOTOGRAFIA
    public function get_hora_Fotografia()
    {
        return $this->hora_Fotografia;
    }

    public function set_hora_Fotografia($hora_Fotografia)
    {
        $this->hora_Fotografia = $hora_Fotografia;
    }

    //LATITUD
    public function get_latitud_Fotografia()
    {
        return $this->latitud_Fotografia;
    }

    public function set_latitud_Fotografia($latitud_Fotografia)
    {
        $this->latitud_Fotografia = $latitud_Fotografia;
    }

    //ALTITUD
    public function get_altitud_Fotografia()
    {
        return $this->altitud_Fotografia;
    }

    public function set_altitud_Fotografia($altitud_Fotografia)
    {
        $this->altitud_Fotografia = $altitud_Fotografia;
    }

    //LONGITUD
    public function get_longitud_Fotografia()
    {
        return $this->longitud_Fotografia;
    }

    public function set_longitud_Fotografia($longitud_Fotografia)
    {
        $this->longitud_Fotografia = $longitud_Fotografia;
    }

    //DESCRIPCION
    public function get_descripcion_Fotografia()
    {
        return $this->descripcion_Fotografia;
    }

    public function set_descripcion_Fotografia($descripcion_Fotografia)
    {
        $this->descripcion_Fotografia = $descripcion_Fotografia;
    }

    //STATUS
    public function get_id_Status_Fotografia()
    {
        return $this->id_Status_Fotografia;
    }

    public function set_id_Status_Fotografia($id_Status_Fotografia)
    {
        $this->id_Status_Fotografia = $id_Status_Fotografia;
    }

    //	ORIENTACION
    public function get_orientacion_Fotografia()
    {
        return $this->orientacion_Fotografia;
    }

    public function set_orientacion_Fotografia($orientacion_Fotografia)
    {
        return $this->orientacion_Fotografia = $orientacion_Fotografia;
    }

    /*--- FOTOGRAFIA: REGISTRAR FOTOGRAFIA ---*/
    public function saveNewFotografia()
    {
        /*$query= "INSERT INTO Fotografias (
        id_Usuario)
         VALUES (
         $usuario)";*/

        $query = "CALL sp_General_Fotografias_Alterno(
			$this->id_Usuario,
			$this->id_Modulo,
			$this->identificador_Fotografia,
            '$this->directorio_Fotografia',
			'$this->nombre_Fotografia',
			NULL,
			NULL,
            NULL,
			'$this->descripcion_Fotografia',
			NULL,
			'Insertar',
			NULL,
			NULL,
			$this->orientacion_Fotografia)";
        $save = $this->db()->query($query);
        $mensaje = "Se ha cargado la fotografia " . $this->id_Usuario . " / " . $this->id_Modulo . " / " . $this->identificador_Fotografia .
            " / " . $this->directorio_Fotografia . " / " . $this->nombre_Fotografia . " / " . $this->descripcion_Fotografia . " / " . $this->orientacion_Fotografia;
        //$this->db()->error;
        return $mensaje;
    }

    /*--- FOTOGRAFIA: ACTUALIZAR FOTOGRAFIA ---*/
    public function modificarFotografia($desc)
    {

        /*$query= "CALL sp_General_Fotografias_Alterno(
            NULL,
            NULL,
            NULL,
            NULL,
            '$this->nombre_Fotografia',
            NULL,
            NULL,
            NULL,
            '$this->descripcion_Fotografia',
            $this->id_Fotografia,
            'Modificar',
            NULL,
            NULL,
            NULL)";*/
        $query = "UPDATE Fotografias SET descripcion_Fotografia = '$desc', 
        directorio_Fotografia = $this->directorio_Fotografia, orientacion_Fotografia = $this->orientacion_Fotografia  
        WHERE id_Fotografia = $this->id_Fotografia";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado la descripción" . $this->nombre_Fotografia . $this->descripcion_Fotografia . $this->id_Fotografia;
        //$this->db()->error;
        return $mensaje;
    }

    /*--- FOTOGRAFIA: ACTUALIZAR FOTOGRAFIA ---*/
    public function borrarFotografia($id_Fotografia)
    {
        $query = "UPDATE Fotografias SET id_Status_Fotografia = '0' WHERE id_Fotografia = $id_Fotografia;";

        return $this->db()->query($query) ? true : false;
    }


    /*--- FOTOGRAFIA: ACTUALIZAR FOTOGRAFIA DE COMENTARIO---*/
    public function editFotografiaComentario()
    {
        $query = "UPDATE Fotografias SET nombre_Fotografia = '$this->nombre_Fotografia', fecha_Fotografia = NOW(), hora_Fotografia = NOW() WHERE identificador_Fotografia = $this->identificador_Fotografia AND id_Modulo = 7;";
        $save = $this->db()->query($query);
        $mensaje = "Se ha modificado la fotografia";
        //$this->db()->error;
        return $mensaje;
    }


    /*--- FOTOGRAFIA: ACTUALIZAR FOTOGRAFIA DE COMENTARIO---*/
    public function likeFotografia($id, $suma)
    {
        $query = "UPDATE Fotografias SET puntuacion_Fotografia = $suma WHERE id_Fotografia = $id;";
        $save = $this->db()->query($query);
        $mensaje = "Gracias por tu like";
        //$this->db()->error;
        return $mensaje;
    }

    /*--- FOTOGRAFIA: ACTUALIZAR FOTOGRAFIA DE COMENTARIO---*/
    public function clasificacionFotografia($id, $clasificacion, $orientacion)
    {
        //$query= "UPDATE Fotografias SET directorio_Fotografia = '5', orientacion_Fotografia = $orientacion WHERE id_Fotografia = $id;";
        $query = "UPDATE Fotografias SET directorio_Fotografia = '$clasificacion', orientacion_Fotografia = $orientacion WHERE id_Fotografia = $id";
        $save = $this->db()->query($query);
        $mensaje = "Se guardo la clasificación";
        //$this->db()->error;
        return $mensaje;
    }

    public function updateIdGpoValoresFoto($idGpoOld, $id_GpoNew) {
        $query = "UPDATE Fotografias SET id_Fotografia = $id_GpoNew WHERE id_Fotografia = $idGpoOld";
        $save = $this->db()->query($query);
        $mensaje = "Se guardo la clasificación";
        //$this->db()->error;
        return $mensaje;
    }

}

?>
