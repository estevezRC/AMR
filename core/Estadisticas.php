<?php

require_once 'ControladorBase.php';

class Estadisticas extends ControladorBase
{

    public $conectar;
    public $adapter;
    private $connectorDB;
    public $id_Proyecto_constant;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->connectorDB = new EntidadBase('', $this->adapter);
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
    }

    // FUNCION PARA OBTENER LA DIFERENCIA ENTRE CADENAMIENTOS
    public function diffCadenamiento($valor)
    {
        for ($i = 0; $i < count($valor); $i++) {
            if ($valor[$i]->idCampo == 67)
                $concat_final = (float)$valor[$i]->valorCampo * 1000;

            if ($valor[$i]->idCampo == 29)
                $concat_inicial = (float)$valor[$i]->valorCampo * 1000;
        }

        if ($concat_final > $concat_inicial)
            $resta = $concat_final - $concat_inicial;
        else
            $resta = $concat_inicial - $concat_final;

        return $resta;
    }

    // FUNCION PARA PROCESAR JSON DE AVANCE DE FO POR PROYECTO
    public function procesarJsonByProyectoFO($avanceJson)
    {
        $arrayAvancesFO = (object)[
            'tritubo' => (object)['nombre' => 'Tritubo', 'valor' => 0],
            'pruebas' => (object)['nombre' => 'Pruebas', 'valor' => 0],
            'inmersionFO' => (object)['nombre' => 'Inmersión FO', 'valor' => 0],
            'reposicionAsfalto' => (object)['nombre' => 'Reposición de asfalto', 'valor' => 0],
            'rellenofluido' => (object)['nombre' => 'Relleno fluido', 'valor' => 0],
            'cople' => (object)['nombre' => 'Cople', 'valor' => 0],
            'registro' => (object)['nombre' => 'Registro', 'valor' => 0],
            'zanjado' => (object)['nombre' => 'Zanjado', 'valor' => 0],
        ];

        foreach ($avanceJson as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFO->tritubo->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFO->pruebas->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFO->inmersionFO->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFO->reposicionAsfalto->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFO->zanjado->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFO->rellenofluido->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFO->cople->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFO->registro->valor += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        return $arrayAvancesFO;
    }


    // FUNCION PARA PROCESAR JSON DE AVANCE DE FO PARA EL PROYECTO VISTA GENERAL
    public function procesarJsonProyectoVG($avanceJsonG, $avanceJsonA, $avanceJsonB, $avanceJsonC, $avanceJsonD, $avanceJsonE, $avanceJsonF)
    {
        $arrayAvancesFOG = (object)[
            'tritubo' => (object)[
                'nombre' => 'Tritubo', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'pruebas' => (object)[
                'nombre' => 'Pruebas', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'inmersionFO' => (object)[
                'nombre' => 'Inmersión FO', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'reposicionAsfalto' => (object)[
                'nombre' => 'Reposición de asfalto', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'rellenofluido' => (object)[
                'nombre' => 'Relleno fluido', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'cople' => (object)[
                'nombre' => 'Cople', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'registro' => (object)[
                'nombre' => 'Registro', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
            'zanjado' => (object)[
                'nombre' => 'Zanjado', 'valor' => 0, 'valorA' => 0, 'valorB' => 0, 'valorC' => 0, 'valorD' => 0, 'valorE' => 0, 'valorF' => 0
            ],
        ];

        foreach ($avanceJsonG as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valor += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valor += $this->diffCadenamiento($valor->Valor);

                        }
                    }
                }
            }
        }

        foreach ($avanceJsonA as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorA += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorA += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonB as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorB += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorB += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonC as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorC += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorC += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonD as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorD += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorD += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonE as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorE += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorE += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonF as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOG->tritubo->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOG->pruebas->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOG->inmersionFO->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOG->reposicionAsfalto->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOG->zanjado->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOG->rellenofluido->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOG->cople->valorF += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOG->registro->valorF += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        return $arrayAvancesFOG;
    }


    // FUNCION PARA PROCESAR JSON ESTADISTICAS TRIMESTAL
    public function procesarJsonMensual($avanceJsonMACTUAL, $avanceJsonM1, $avanceJsonM2)
    {
        $arrayAvancesFOM = (object)[
            'tritubo' => (object)[
                'nombre' => 'Tritubo', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'pruebas' => (object)[
                'nombre' => 'Pruebas', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'inmersionFO' => (object)[
                'nombre' => 'Inmersión FO', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'reposicionAsfalto' => (object)[
                'nombre' => 'Reposición de asfalto', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'rellenofluido' => (object)[
                'nombre' => 'Relleno fluido', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'cople' => (object)[
                'nombre' => 'Cople', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'registro' => (object)[
                'nombre' => 'Registro', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
            'zanjado' => (object)[
                'nombre' => 'Zanjado', 'valor' => 0, 'valorM' => 0, 'valorM1' => 0, 'valorM2' => 0
            ],
        ];


        foreach ($avanceJsonMACTUAL as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOM->tritubo->valorM += $this->diffCadenamiento($valor->Valor);
                            $tritubo += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOM->pruebas->valorM += $this->diffCadenamiento($valor->Valor);
                            $pruebas += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOM->inmersionFO->valorM += $this->diffCadenamiento($valor->Valor);
                            $inmersionFO += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOM->reposicionAsfalto->valorM += $this->diffCadenamiento($valor->Valor);
                            $reposicionAsfalto += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOM->zanjado->valorM += $this->diffCadenamiento($valor->Valor);
                            $zanjado += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOM->rellenofluido->valorM += $this->diffCadenamiento($valor->Valor);
                            $rellenoFluido += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOM->cople->valorM += $this->diffCadenamiento($valor->Valor);
                            $cople += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOM->registro->valorM += $this->diffCadenamiento($valor->Valor);
                            $regis += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonM1 as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOM->tritubo->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $tritubo1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOM->pruebas->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $pruebas1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOM->inmersionFO->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $inmersionFO1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOM->reposicionAsfalto->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $reposicionAsfalto1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOM->zanjado->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $zanjado1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOM->rellenofluido->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $rellenoFluido1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOM->cople->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $cople1 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOM->registro->valorM1 += $this->diffCadenamiento($valor->Valor);
                            $regis1 += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        foreach ($avanceJsonM2 as $registro) {
            if (is_array($registro->Valores) || is_object($registro->Valores)) {
                foreach ($registro->Valores as $valor) {
                    foreach ($valor->Valor as $opcionesCampos) {
                        if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Tritubo') {
                            $arrayAvancesFOM->tritubo->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $tritubo2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Pruebas') {
                            $arrayAvancesFOM->pruebas->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $pruebas2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Inmersión FO') {
                            $arrayAvancesFOM->inmersionFO->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $inmersionFO2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Reposición de asfalto') {
                            $arrayAvancesFOM->reposicionAsfalto->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $reposicionAsfalto2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Zanjado') {
                            $arrayAvancesFOM->zanjado->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $zanjado2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Relleno fluido') {
                            $arrayAvancesFOM->rellenofluido->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $rellenoFluido2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Cople') {
                            $arrayAvancesFOM->cople->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $cople2 += $this->diffCadenamiento($valor->Valor);

                        } else if ($opcionesCampos->idCampo == 35 && $opcionesCampos->valorCampo == 'Registro') {
                            $arrayAvancesFOM->registro->valorM2 += $this->diffCadenamiento($valor->Valor);
                            $regis2 += $this->diffCadenamiento($valor->Valor);
                        }
                    }
                }
            }
        }

        $arrayAvancesFOM->tritubo->valor = $tritubo + $tritubo1 + $tritubo2;
        $arrayAvancesFOM->pruebas->valor = $pruebas + $pruebas1 + $pruebas2;
        $arrayAvancesFOM->rellenofluido->valor = $rellenoFluido + $rellenoFluido1 + $rellenoFluido2;
        $arrayAvancesFOM->cople->valor = $cople + $cople1 + $cople2;
        $arrayAvancesFOM->zanjado->valor = $zanjado + $zanjado1 + $zanjado2;
        $arrayAvancesFOM->reposicionAsfalto->valor = $reposicionAsfalto + $reposicionAsfalto1 + $reposicionAsfalto2;
        $arrayAvancesFOM->inmersionFO->valor = $inmersionFO + $inmersionFO1 + $inmersionFO2;
        $arrayAvancesFOM->registro->valor = $regis + $regis1 + $regis2;

        return $arrayAvancesFOM;
    }

    public function tratamientoTiempo($valor, $texto1, $texto2)
    {
        if ($valor == 0)
            $valor = '';
        else {
            if ($valor < 10) {
                if ($valor < 2)
                    $valor = "0$valor $texto1";
                else
                    $valor = "0$valor $texto2";
            } else
                $valor = "$valor $texto2";
        }

        return $valor;
    }

    public function conversorSegundosHoras($tiempo_en_segundos)
    {
        $dias = floor($tiempo_en_segundos / 86400);
        $horas = floor(($tiempo_en_segundos - ($dias * 86400)) / 3600);
        $minutos = floor(($tiempo_en_segundos - ($dias * 86400) - ($horas * 3600)) / 60);
        $segundos = floor($tiempo_en_segundos - ($dias * 86400) - ($horas * 3600) - ($minutos * 60));

        // TRATAMIENTO PARA LOS DIAS
        $dias = $this->tratamientoTiempo($dias, 'Día', 'Días');

        // TRATAMIENTO PARA LAS HORAS
        $horas = $this->tratamientoTiempo($horas, 'Hora', 'Horas');

        // TRATAMIENTO PARA LOS MINUTOS
        $minutos = $this->tratamientoTiempo($minutos, 'Minuto', 'Minutos');

        // TRATAMIENTO PARA LOS SEGUNDOS
        $segundos = $this->tratamientoTiempo($segundos, 'Segundo', 'Segundos');

        return "$dias $horas $minutos $segundos";
    }

    public function tiempoPromedioIncidente($fechaIncidente, $idProyecto)
    {
        $registro = new ReporteLlenado($this->adapter);

        // OBETENER LOS TIPOS DE INCIDENTES
        $tipoIncidentes = $registro->getTiposIncidentes()[0]->Valor_Default;
        $tipoIncidentes = explode('/', $tipoIncidentes);

        $cantidadIncidencia = 0;
        $arrayIncidentes = [];
        $incidente_seguimiento = [];
        foreach ($tipoIncidentes as $tipo) {
            // OBTENER TODOS LOS INCIDENTES POR TIPO
            $registrosIncidentes = $registro->getAllIncidentesByTipo($tipo, $idProyecto, $fechaIncidente);
            if ($registrosIncidentes) {
                foreach ($registrosIncidentes as $incidente) {
                    $cantidadIncidencia++;
                    // OBTENER ULTIMO SEGUIMIENTO (ES QUE VALIDO EL INCIDENTE)
                    $seguimiento = $registro->getSeguimientoValidadoByIncidente($incidente->id_Gpo_Valores_Reporte)[0];
                    $incidente_seguimiento[] = ['incidente' => $incidente, 'seguimiento' => $seguimiento];

                    // REALIZAR RESTA ENTRE FECHA/HORA INCIDENTE  -  FECHA/HORA SEGUIMIENTO
                    $date1 = new DateTime("{$incidente->fecha_reporte}");
                    $date2 = new DateTime("{$seguimiento->fecha_reporte_seguimiento}");
                    $diff = $date1->diff($date2);

                    // REALIZAR SUMA POR CADA RESULTADO DE LA RESTA
                    if ($diff->d > 28)
                        $dias = $diff->d;
                    else
                        $dias = $diff->days;

                    $sumaIncidencia += ($dias * 24 * 60 * 60) + ($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s;
                }

                // REALIZAR LA DIVISION DE LA SUMA/CANTIDAD-INCIDENTE POR CADA TIPO DE INCIDENTE
                $division = $sumaIncidencia / $cantidadIncidencia;
                $resultado = $this->conversorSegundosHoras($division);

                $arrayIncidentes[] = (object)[
                    'incidente' => $incidente->valor_Texto_Reporte,
                    'total' => $cantidadIncidencia,
                    'tiempo_promedio_atencion' => $resultado
                ];
            } else {
                $arrayIncidentes[] = (object)[
                    'incidente' => $tipo,
                    'total' => 0,
                    'tiempo_promedio_atencion' => 0
                ];
            }
            $cantidadIncidencia = 0;
            $incidente_seguimiento = [];
            $sumaIncidencia = 0;
        }

        return $arrayIncidentes;
    }

}
