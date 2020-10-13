<?php
require_once 'ControladorBase.php';

use Mail\Email;
use Mail\Templates\Minuta\Minuta;

class FormatosCorreo extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $url;
    private $connectorDB;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->connectorDB = new EntidadBase('', $this->adapter);
        $this->url = $_SERVER["SERVER_NAME"];
    }

    // FUNCION PARA OBTENER LOS DATOS DE LOS PARTICIPANTES
    public function obtenerDatosParticipantesMinuta($ids)
    {
        $participantes = $this->connectorDB->getAllParticipantesIdAndNameByIds($ids);
        $datos = array_map(function ($participante) {
            return ["nombre" => $participante->nombre
                //"empresa" => $participante->empresa,
                //"puesto" => $participante->puesto
            ];
        }, $participantes);

        return $datos;
    }

    // FUNCION PARA OBTENER LOS CORREOS DE LOS PARTICIPANTES
    public function obtenerCorreosParticipantesMinuta($ids)
    {

        $participantes = $this->connectorDB->getAllParticipantesIdAndNameByIds($ids);
        $correos = array_map(function ($participante) {
            return $participante->correo_Usuario;
        }, $participantes);

        return $correos;
    }

    // FUNCION PARA MANIPULAR Y CAMBIAR LA INFORMACION DEL JSON DEL CAMPO ESPECIAL
    public function cambiarJson($datos, $valorDefault)
    {
        $ids = explode('/', $valorDefault);

        $entidadBase = &$this->connectorDB;

        // OBTENER DATOS DE LOS CAMPOS
        $datosCampos = array_map(function ($id) use ($entidadBase) {
            return $entidadBase->getCampoById($id);
        }, $ids);

        // DATOS DEL JSON
        $campos = json_decode(str_replace("\n", "<br>", $datos))->Valores;

        $datosModificados = array_map(function ($dato) use ($entidadBase, $datosCampos) {
            return array_map(function ($multiple) use ($entidadBase, $datosCampos) {
                foreach ($datosCampos as $campo) {
                    if ($multiple->idCampo == $campo->id_Campo_Reporte) {
                        switch ($campo->tipo_Reactivo_Campo) {
                            case 'select-tabla':
                                switch ($campo->Valor_Default) {
                                    case 'participante':
                                        $multiple->valorCampo = $entidadBase->getAllParticipantesIdAndNameByIds($multiple->valorCampo)[0]->nombre;
                                        $multiple->nombreCampo = $campo->nombre_Campo;
                                        break;
                                    default;
                                }
                                break;
                            default:
                                $multiple->nombreCampo = $campo->nombre_Campo;
                        }
                    }
                }
                return $multiple;
            }, $dato->Valor);
        }, $campos);

        return $datosModificados;
    }

    // FUNCION PARA OBTENER LA INFORMACION DE CUALQUIER REPORTE
    public function obtenerValoresReporteLlenado($idGpoValores)
    {
        $entidadBase = &$this->connectorDB;
        $valoresReporte = $entidadBase->getReporteLlenadoById($idGpoValores);

        $titulo = $entidadBase->getAllReportesLlenadosByIdGpo($idGpoValores)[0]->titulo_Reporte;

        $datoTitulo = ['nombre' => 'Motivo', 'valor' => $titulo];

        $campos = array_map(function ($valores) {
            if ($valores->tipo_Reactivo_Campo == 'multiple')
                $info = ['nombre' => $valores->nombre_Campo, 'valor' => $this->cambiarJson($valores->valor_Texto_Reporte, $valores->Valor_Default)];
            elseif ($valores->tipo_Reactivo_Campo == 'check_list_asistencia') {
                $ids = str_replace('/', ',', $valores->valor_Texto_Reporte);
                $info = ['nombre' => $valores->nombre_Campo, 'valor' => $this->obtenerDatosParticipantesMinuta($ids)];
            } else
                $info = ['nombre' => $valores->nombre_Campo, 'valor' => $valores->valor_Texto_Reporte];

            return $info;
        }, $valoresReporte);

        array_push($campos, $datoTitulo);

        return $campos;
    }

    // FUNCION PARA ENVIO DE CORREO A DIVERSAS CUENTAS CON PLANTILLA MINUTA
    public function enviarMinuta($nombreReporte, $datos, $destinatarios, $carpeta)
    {
        if ($this->url == 'get-s.dev') {
            $host = 'get-s.dev';
            $correo = 'soporte@get-s.dev';
            $password = 'ch,11gI$TO}#';
        } else {
            $host = 'supervisor.uno';
            $correo = 'contacto@supervisor.uno';
            $password = '$ContactoSupervisor$';
        }

        $minuta = new Minuta($datos, "https://$host/supervisor/$carpeta/mail/Templates/Minuta/logo.png");

        $email = new Email($minuta, $host, $correo, $password);
        $email->configure();
        $email->addRecipients($destinatarios);
        $email->send($nombreReporte);

        //$body = $minuta->getContent();
        //echo $body;
    }

}
