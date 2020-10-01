<?php

class EmpleadosDatos extends EntidadBase
{


    private $id_empleados_datos;
    private $calle;
    private $numero;
    private $colonia;
    private $municipio;
    private $estado;
    private $cp;
    private $pais;
    private $grado_estudios;
    private $nombre_estudio;
    private $rfc;
    private $curp;
    private $nss;
    private $telefono;
    private $seguro_gastos_mayores;
    private $tipo_sangre;
    private $info_adicional;
    private $id_empleado;

    public function __construct($adapter)
    {
        $table = "empleados_datos";
        parent::__construct($table, $adapter);
    }


    // id_empledo_datos
    public function getIdEmpleadosDatos()
    {
        return $this->id_empleados_datos;
    }

    public function setIdEmpleadosDatos($id_empleados_datos)
    {
        $this->id_empleados_datos = $id_empleados_datos;
    }


    // calle
    public function getCalle()
    {
        return $this->calle;
    }

    public function setCalle($calle)
    {
        $this->calle = $calle;
    }


    // numero
    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }


    // colonia
    public function getColonia()
    {
        return $this->colonia;
    }

    public function setColonia($colonia)
    {
        $this->colonia = $colonia;
    }


    // municipio
    public function getMunicipio()
    {
        return $this->municipio;
    }

    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }


    // estado
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }


    // cp
    public function getCp()
    {
        return $this->cp;
    }

    public function setCp($cp)
    {
        $this->cp = $cp;
    }


    // pais
    public function getPais()
    {
        return $this->pais;
    }

    public function setPais($pais)
    {
        $this->pais = $pais;
    }


    // grado_estudio
    public function getGradoEstudios()
    {
        return $this->grado_estudios;
    }

    public function setGradoEstudios($grado_estudios)
    {
        $this->grado_estudios = $grado_estudios;
    }


    // nombre_estudio
    public function getNombreEstudio()
    {
        return $this->nombre_estudio;
    }

    public function setNombreEstudio($nombre_estudio)
    {
        $this->nombre_estudio = $nombre_estudio;
    }


    // rfc
    public function getRfc()
    {
        return $this->rfc;
    }

    public function setRfc($rfc)
    {
        $this->rfc = $rfc;
    }


    // curp
    public function getCurp()
    {
        return $this->curp;
    }

    public function setCurp($curp)
    {
        $this->curp = $curp;
    }


    // nss
    public function getNss()
    {
        return $this->nss;
    }

    public function setNss($nss)
    {
        $this->nss = $nss;
    }


    // telefono
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }


    // seguro_gastos_mayores
    public function getSeguroGastosMayores()
    {
        return $this->seguro_gastos_mayores;
    }

    public function setSeguroGastosMayores($seguro_gastos_mayores)
    {
        $this->seguro_gastos_mayores = $seguro_gastos_mayores;
    }


    // tipo_sangre
    public function getTipoSangre()
    {
        return $this->tipo_sangre;
    }

    public function setTipoSangre($tipo_sangre)
    {
        $this->tipo_sangre = $tipo_sangre;
    }


    // info_Adicional
    public function getInfoAdicional()
    {
        return $this->info_adicional;
    }

    public function setInfoAdicional($info_adicional)
    {
        $this->info_adicional = $info_adicional;
    }


    // id_empleado
    public function getIdEmpleado()
    {
        return $this->id_empleado;
    }

    public function setIdEmpleado($id_empleado)
    {
        $this->id_empleado = $id_empleado;
    }



    // ****************************************** INSERTAR NUEVOS DATOS EMPLEADOS **************************************
    public function saveNewEmpleadoDatos()
    {
        $query = "INSERT INTO empleados_datos (calle, numero, colonia, municipio, estado, cp, pais, 
        grado_estudios, nombre_estudio, rfc, curp, nss, telefono, seguro_gastos_mayores, tipo_sangre, info_adicional, 
        fecha_registro, status, id_empleado) 
        VALUES ('$this->calle', $this->numero, '$this->colonia', '$this->municipio', '$this->estado', '$this->cp', '$this->pais', 
        '$this->grado_estudios', '$this->nombre_estudio', '$this->rfc', '$this->curp', '$this->nss', '$this->telefono', 
        '$this->seguro_gastos_mayores', '$this->tipo_sangre', '$this->info_adicional', now(), 1, $this->id_empleado)";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


    // ****************************************** MODIFICACION DE DATOS EMPLEADOS **************************************
    public function modificarEmpleado()
    {
        $query = "UPDATE empleados_datos SET calle = '$this->calle', numero = $this->numero, colonia = '$this->colonia',
        municipio = '$this->municipio', estado = '$this->estado', cp = $this->cp, pais = '$this->pais', 
        grado_estudios = '$this->grado_estudios', nombre_estudio = '$this->nombre_estudio', rfc = '$this->rfc', 
        curp = '$this->curp', nss = '$this->nss', telefono = $this->telefono, seguro_gastos_mayores = '$this->seguro_gastos_mayores',
        tipo_sangre = '$this->tipo_sangre', info_adicional = '$this->info_adicional' 
        WHERE id_empleado = $this->id_empleado";

        if ($this->db()->query($query))
            return true;
        else
            return false;
    }


}

