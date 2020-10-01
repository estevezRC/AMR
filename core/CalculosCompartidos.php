<?php

require_once 'ControladorBase.php';


class CalculosCompartidos extends ControladorBase
{
    public $conectar;
    public $adapter;
    public $id_Proyecto_constant;
    public $url;
    private $connectorDB;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();

        $this->url = $_SERVER["SERVER_NAME"];
        $this->connectorDB = new AvanceActividad($this->adapter);
    }

    /*********************************** CALCULAR PORCENTAJE PARA PROCESOS ******************************************/
    public function calcularPorcentaje($id)
    {
        $reporteLlenado = new ReporteLlenado($this->adapter);
        $allregistrosCarriles = $reporteLlenado->calcularPorcentajeEstructuraGeneral($id);
        if (!empty($allregistrosCarriles)) {
            $x = 0;
            $porMixtos = 0;
            $porExclusivos = 0;
            $porM = 0;
            $porE = 0;
            $arrayM = array();
            $arrayE = array();
            foreach ($allregistrosCarriles as $registros) {
                if ($x <= 7) {
                    $datos = $reporteLlenado->calcularPorcentajeEstructura($registros->id_Gpo_Valores_Reporte);
                    foreach ($datos as $dato) {
                        $porM += $dato->Porcentaje1;
                    }
                    $porMixtos = $porMixtos + $porM;
                    array_push($arrayM, $porM);
                    $porM = 0;
                    $nombreReporte = $registros->nombre_Reporte;
                    $porcentajeProyecto = $registros->Porcentaje;
                } else {
                    $datos = $reporteLlenado->calcularPorcentajeEstructura($registros->id_Gpo_Valores_Reporte);
                    foreach ($datos as $dato) {
                        $porE += $dato->Porcentaje1;
                    }
                    $porExclusivos = $porExclusivos + $porE;
                    array_push($arrayE, $porE);
                    $porE = 0;
                    $nombreReporte1 = $registros->nombre_Reporte;
                    $porcentajeProyecto1 = $registros->Porcentaje;
                }
                $x++;
            }
            $porMixtos = ($porMixtos * 80) / (100 * 8);
            $porExclusivos = ($porExclusivos * 20) / (100 * 2);

            $suma = $porMixtos + $porExclusivos;

            $comArrays = array_merge($arrayM, $arrayE);

            $datosGenerales = (object)[
                (object)['nombreReporte' => $nombreReporte, "porcentaje" => $porMixtos, "porcentajeProyecto" => $porcentajeProyecto],
                (object)['nombreReporte' => $nombreReporte1, "porcentaje" => $porExclusivos, "porcentajeProyecto" => $porcentajeProyecto1]
            ];

            return [$suma, $comArrays, $datosGenerales];
        }
        return 0;
    }

    public function calcularPorcentajeEdificio($id)
    {
        $reporteLlenado = new ReporteLlenado($this->adapter);
        $allregistros = $reporteLlenado->calcularPorcentajeEstructuraGeneral($id);
        if (!empty($allregistros)) {
            $x = 0;
            $porcentajePisoA = 0;
            $porcentajePisoB = 0;
            $porcentajeFo = 0;
            $auxPorA = 0;
            $auxPorB = 0;
            $auxFo = 0;
            $arrayA = array();
            $arrayB = array();
            $arrayC = array();
            foreach ($allregistros as $registros) {
                if ($x == 0) {
                    $datos = $reporteLlenado->calcularPorcentajeEstructura($registros->id_Gpo_Valores_Reporte);
                    foreach ($datos as $dato) {
                        $auxPorA += $dato->Porcentaje1;
                    }
                    $porcentajePisoA = $porcentajePisoA + $auxPorA;

                    array_push($arrayA, $auxPorA);
                    $auxPorA = 0;
                    $nombreReporte = $registros->nombre_Reporte;
                    $porcentajeProyecto = $registros->Porcentaje;
                } else if ($x == 1){
                    $datos = $reporteLlenado->calcularPorcentajeEstructura($registros->id_Gpo_Valores_Reporte);
                    foreach ($datos as $dato) {
                        $auxPorB += $dato->Porcentaje1;
                    }
                    $porcentajePisoB = $porcentajePisoB + $auxPorB;
                    array_push($arrayB, $auxPorB);
                    $auxPorB = 0;
                    $nombreReporte1 = $registros->nombre_Reporte;
                    $porcentajeProyecto1 = $registros->Porcentaje;
                } else {
                    $datos = $reporteLlenado->calcularPorcentajeEstructura($registros->id_Gpo_Valores_Reporte);
                    foreach ($datos as $dato) {
                        $auxFo += $dato->Porcentaje1;
                    }
                    $porcentajeFo = $porcentajeFo + $auxFo;
                    array_push($arrayC, $auxFo);
                    $auxPorB = 0;
                    $nombreReporte2 = $registros->nombre_Reporte;
                    $porcentajeProyecto2 = $registros->Porcentaje;
                }
                $x++;
            }

            $porcentajePisoA = ($porcentajePisoA * $porcentajeProyecto) / (100);
            $porcentajePisoB = ($porcentajePisoB * $porcentajeProyecto1) / (100);
            $porcentajeFo = ($porcentajeFo * $porcentajeProyecto2) / (100);

            $suma = $porcentajePisoA + $porcentajePisoB + $porcentajeFo;

            $comArrays = array_merge($arrayA, $arrayB);
            $comArrays = array_merge($comArrays, $arrayC);

            $datosGenerales = (object)[
                (object)['nombreReporte' => $nombreReporte, "porcentaje" => $porcentajePisoA, "porcentajeProyecto" => $porcentajeProyecto],
                (object)['nombreReporte' => $nombreReporte1, "porcentaje" => $porcentajePisoB, "porcentajeProyecto" => $porcentajeProyecto1],
                (object)['nombreReporte' => $nombreReporte2, "porcentaje" => $porcentajeFo, "porcentajeProyecto" => $porcentajeProyecto2]
            ];

            return [$suma, $comArrays, $datosGenerales];
        }
        return 0;
    }

    public function calcularPorcentajePostes($id_Proyecto)
    {

        // OBTENER TODOS LOS REGISTROS DE POSTES
        $reporteLlenado = new ReporteLlenado($this->adapter);

        $tipoReporte = 2;
        $noReportes = '';
        $noRepit = '';
        $allRegistrosUbicaciones = $reporteLlenado->getAllReportesLlenadosByType($tipoReporte, $id_Proyecto, $noReportes, $noRepit);

        if (!empty($allRegistrosUbicaciones)) {

            $x = 1;
            $porcentaje = 0;
            $porcentajeubicacion = 0;
            $comArrays = array();
            foreach ($allRegistrosUbicaciones as $ubicaciones) {
                $datos = $reporteLlenado->calcularPorcentajeEstructura($ubicaciones->id_Gpo_Valores_Reporte);
                $porcentaje = $datos[0]->Porcentaje1;
                $porcentajeubicacion += $porcentaje;

                array_push($comArrays, $porcentaje);
                $porcentaje = 0;

                $nombreReporte = $ubicaciones->nombre_Reporte;
                $porcentajeProyecto = $ubicaciones->Porcentaje;

                $x++;

            }

            $porcentajeProyecto = ($porcentajeProyecto * 100) / $x;

            $datosGenerales = (object)[
                (object)['nombreReporte' => $nombreReporte, "porcentaje" => $porcentaje, "porcentajeProyecto" => $porcentajeProyecto]
            ];

            return [$porcentajeProyecto, $comArrays, $datosGenerales];

        }
        return 0;

    }

    /****************************************** CALCULAR PORCENTAJES DEL GANTT ***************************************
     * @param $subNodos
     * @param $actividad
     * @param $estructura
     * @param int $porcentajePadre
     * @return object
     */
    public function calculo($subNodos, &$estructura, $porcentajePadre = 0)
    {
        $sumaPorcentaje = $nodoPorcentaje = 0;
        foreach ($subNodos as $key => $subNodo) {
            $nodoAvance = $this->connectorDB->getGpoValoresByIdNodo($subNodo->id_nodo);
            $estructura[$key]['info'] = (object)((array)$subNodo + ['gpo_valores' => $nodoAvance[0]->gpo_valores]);
            $subNodos = $this->connectorDB->getSubNodos($subNodo->id_nodo);

            if ($subNodos) {
                $porcentaje = $this->calculo($subNodos, $estructura[$key]['children'], $subNodo->porcentaje);
                $sumaPorcentaje += $porcentaje->perc_proyecto;
                $nodoPorcentaje += ($porcentaje->perc_proyecto / $porcentajePadre * 100);
                //dump($porcentaje->perc_nodo, $porcentajePadre);
                $estructura[$key]['info']->porcentaje_avance = $porcentaje->perc_proyecto;
            } else {
                $avanceNodo = $this->connectorDB->getGpoValoresByIdNodo($subNodo->id_nodo);
                if ($avanceNodo) {
                    $sumaPorcentaje += $subNodo->porcentaje;
                    $nodoPorcentaje += (float)$porcentajePadre ? ($subNodo->porcentaje / $porcentajePadre * 100) : 0;
                    $estructura[$key]['info']->porcentaje_avance = $subNodo->porcentaje;
                }
            }
        }
        //dump($nodoPorcentaje);
        return (object)['perc_proyecto' => $sumaPorcentaje, 'perc_nodo' => round($nodoPorcentaje, 2, PHP_ROUND_HALF_UP)];
    }

    /****************************************** CALCULAR PORCENTAJES DEL GANTT ***************************************
     * @param $estructura
     * @param int $porcentajePadre
     * @return object
     */
    public function calculo2(&$estructura, &$porcentajePadre = 0)
    {
        $sumaPorcentaje = $nodoPorcentaje = 0;
        foreach ($estructura as $key => $subNodo) {

            if ($subNodo->children) {
                $porcentaje = $this->calculo2($estructura[$key]->children, $subNodo->info->porcentaje);
                $sumaPorcentaje += $porcentaje->perc_proyecto;
                $nodoPorcentaje += ($porcentaje->perc_proyecto / $porcentajePadre * 100);
                //dump($porcentaje->perc_nodo, $porcentajePadre);
                $estructura[$key]->info->porcentaje_avance = $porcentaje->perc_proyecto;
            } elseif ($subNodo->info->completado) {
                $sumaPorcentaje += $subNodo->info->porcentaje;
                $nodoPorcentaje += (float)$porcentajePadre ? ($subNodo->info->porcentaje / $porcentajePadre * 100) : 0;
                $estructura[$key]->info->porcentaje_avance = $subNodo->info->porcentaje;
            }
        }
        return (object)['perc_proyecto' => $sumaPorcentaje, 'perc_nodo' => round($nodoPorcentaje, 2, PHP_ROUND_HALF_UP)];
    }
}
