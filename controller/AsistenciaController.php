<?php

class AsistenciaController extends ControladorBase
{
    public $conectar;
    public $adapter;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    /*--- VISTA DE TODOS LOS AREAS ---*/
    public function index()
    {
        $mensaje = '<i class="fas fa-address-book" aria-hidden="true"></i> Concentrado de Personal';

        $asistencia = new Asistencia($this->adapter);
        $funciones = new FuncionesCompartidas();

        // OBTENER TODOS LOS PROYECTOS
        $usuario = $_SESSION[ID_USUARIO_SUPERVISOR];
        if ($usuario == 1)
            $allProyectos = $asistencia->getAllProyecto();
        else
            $allProyectos = $asistencia->getAllProyectosLibres(7);

        $idsProyectos = implode(",", array_map(function ($ids) {
            return $ids->id_Proyecto;
        }, $allProyectos));


        // VALIDAR DATOS, SINO VIENE ID_PROYECTOS OBTENER TODOS LOS PROYECTOS
        $proyecto = $_REQUEST['id_proyecto'];
        if ($proyecto) {
            if ($proyecto == 0) {
                $idProyecto = $idsProyectos;
                $nombreProyecto = 'Todos';
            } else {
                $idProyecto = $proyecto;
                $nombreProyecto = $asistencia->getProyectoById($proyecto)->nombre_Proyecto;
            }
        } else {
            $idProyecto = $idsProyectos;
            $nombreProyecto = 'Todos';
        }

        // VALIDAR DATOS, SI NO VIENEN FECHAS, OBTENER QUINCENA ACTUAL
        $fechaInicial = $_REQUEST['fecha_inicial'];
        $fechaFinal = $_REQUEST['fecha_final'];

        $diaInical = explode('-', $fechaInicial)[2];
        $diaFinal = explode('-', $fechaFinal)[2];
        $rangoDias = [(int)$diaInical, (int)$diaFinal];

        if (empty($fechaInicial) && empty($fechaFinal)) {
            $fechaActual = date('Y-m');
            $dia = date('d');

            if ($dia <= 15) {
                $fechaInicial = "$fechaActual-01";
                $fechaFinal = "$fechaActual-15";
                $rangoDias = [1, 15];
            } else {
                $split_fechames = explode('-', $fechaActual);
                $diaFinalMes = cal_days_in_month(CAL_GREGORIAN, $split_fechames[1], $split_fechames[0]);
                $fechaInicial = "$fechaActual-16";
                $fechaFinal = "$fechaActual-$diaFinalMes";
                $rangoDias = [16, $diaFinalMes];
            }
        }

        // OBTENER TODOS LOS REGISTROS DE ASISTENCIA BY EMPLEADO
        $allEmpleados = $asistencia->getAllEmpleadosInAsistenciaByIdProyectoAndRangoFechas($idProyecto, $fechaInicial, $fechaFinal);
        if ($allEmpleados) {
            $infoEmpleadosAsistencia = array();
            foreach ($allEmpleados as $key => $empleado) {
                $incidencias = [];
                $allAsistencia = $asistencia->getAllAsistenciaByIdProyectoAndRangoFechas($empleado->id_emp, $idProyecto, $fechaInicial, $fechaFinal);

                for ($inicio = $rangoDias[0]; $inicio <= $rangoDias[1]; $inicio++) {
                    $fecha = date('Y-m-d', strtotime(date('Y-m', strtotime($fechaInicial)) . '-' . $inicio));

                    if (!empty($allAsistencia)) {
                        if (in_array($fecha, array_map(function ($registro) {
                            return $registro->fecha;
                        }, $allAsistencia))) {
                            $incidencia = array_map(function ($registro) use ($empleado) {
                                return [
                                    'tipo' => (function () use ($registro, $empleado) {
                                        if ($registro->incidencia == "Asistencia" && $empleado->id_proyecto !== $registro->proyecto_asignado) {
                                            return 'Cambio';
                                        }
                                        return $registro->incidencia;
                                    })(),
                                    'proyecto' => $registro->nombre_proyecto
                                ];
                            },
                                array_filter($allAsistencia, function ($registro) use ($fecha) {
                                    return $registro->fecha == $fecha;
                                })
                            );
                            $incidencias[] = $funciones->formatearIncidencia(reset($incidencia));
                        } elseif (!$funciones->validarFechaDomingo($fecha)) {
                            if ($fecha > date('Y-m-d'))
                                $incidencias[] = (object)['tipo' => ''];
                            else
                                $incidencias[] = (object)['tipo' => 'F'];
                        } else
                            $incidencias[] = (object)['tipo' => 'D'];
                    } else
                        $incidencias[] = (object)['tipo' => 'F'];
                }

                $infoEmpleadosAsistencia[] = ["infoEmpleado" => $empleado, "infoAsistencia" => $incidencias];
            }

        }

        $this->view("index", array(
            "mensaje" => $mensaje, "infoEmpleadosAsistencia" => $infoEmpleadosAsistencia, "allProyectos" => $allProyectos,
            "fechaInicial" => $fechaInicial, "fechaFinal" => $fechaFinal, "rangoDias" => $rangoDias,
            "nombreProyecto" => $nombreProyecto
        ));

    }
}
