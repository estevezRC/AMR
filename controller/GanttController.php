<?php
require 'vendor/autoload.php';
date_default_timezone_set('UTC');

class GanttController extends ControladorBase
{
    private $conectar;
    private $adapter;
    private $id_Proyecto_constant;
    private $url;
    private $conectores;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        $this->id_Proyecto_constant = $_SESSION[ID_PROYECTO_SUPERVISOR];
        $this->url = $_SERVER["REQUEST_URI"];
    }

    public function index()
    {
        $gantt = new Gantt($this->adapter);
        $datosGantt = $gantt->obtenerDatosPorGantt();

        //dump($datosGantt);
        $datosGantt = array_map(function ($registro) use (&$datosGantt) {
            $registro = (array)$registro;
            $porcentajeAsignado = $registro['porcentaje'];
            $porcentajeAsignado = !$porcentajeAsignado ? 'Sin asignar' : "{$porcentajeAsignado}%";

            $valores = [
                'id' => $registro['id_nodo'],
                'name' => $registro['actividad'],
                'parent' => (function ($registro) {
                    return $registro['id_nodo_padre'] !== '0' ? $registro['id_nodo_padre'] : null;
                })($registro),
                'porcentajeAsignado' => $porcentajeAsignado,
                'progressValue' => "{$registro['porcentaje']}%",
                'actualStart' => strtotime($registro['inicio']) * 1000,
                'actualEnd' => strtotime($registro['fin']) * 1000,
                'connectTo' => (function ($registro) {
                    return $registro['connect_to'] ? $registro['connect_to'] : null;
                })($registro),
                'connectorType' => (function ($registro) {
                    return $registro['connect_to'] ? 'FinishStart' : null;
                })($registro)
            ];

            foreach ($valores as $key => $propiedad) {
                if (!$propiedad) {
                    unset($valores[$key]);
                }
            }

            return $valores;
        }, $datosGantt);

        $datosGantt = json_encode($datosGantt);
        $this->view("index", ["mensaje" => 'Diagrama de Gantt', 'datosGantt' => $datosGantt]);
    }


    // FUNCION PARA MOSTRAR VISTA DE CARGA DE XML
    public function nuevo()
    {
        $mensaje = "<i class='fas fa-file-code'></i> &nbsp; Cargar nuevo XML";

        $this->view("index", array(
            "mensaje" => $mensaje
        ));
    }

    // FUNCION PARA SUBIR ARCHIVO AL SERVIDOR
    public function subirArchivo()
    {
        if ($_FILES['file']['tmp_name']) {
            $nombre_archivo = $_FILES['file']['name'];
            $nombre_archivo = strtolower(str_replace(' ', '_', $nombre_archivo));

            $extenion = new SplFileInfo($nombre_archivo);

            if ($extenion->getExtension() != 'xml') {
                $data = array(
                    'carga' => false,
                    'mensaje' => 'El archivo debe ser un XML',
                    'code' => 400
                );
            } else {
                $ruta = 'gantt/';

                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }

                $ruta = $ruta . basename($nombre_archivo);
                if (move_uploaded_file($_FILES['file']['tmp_name'], $ruta)) {
                    $data = array(
                        'carga' => true,
                        'nombre_archivo' => $nombre_archivo,
                        'code' => 200
                    );
                } else {
                    $data = array(
                        'carga' => false,
                        'mensaje' => 'El archivo contiene algun error',
                        'code' => 400
                    );
                }
            }
        } else {
            $data = array(
                'carga' => false,
                'mensaje' => 'Error, se debe enviar un archivo XML',
                'code' => 400
            );
        }

        echo json_encode($data);
    }

    // FUNCION PARA BORRAR ARCHIVO
    public function borrarArchivo()
    {
        $nombreArchivo = $_REQUEST['nombre_archivo'];
        if (isset($nombreArchivo)) {
            $rutaArchivo = 'gantt/' . $nombreArchivo;
            unlink($rutaArchivo);
            $status = true;
        } else
            $status = false;

        echo json_encode([
            'status' => $status
        ]);
    }

    public function nuevoGantt()
    {
        # 0. Instanciación de clases
        $gantt = new LlenadoGantt($this->adapter);
        $funcionesCompartidas = new FuncionesCompartidas();

        # 1. Leer archivo XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file("descargables/RCO_v5-2_1.xml");
        $tareas = $xml->Tasks->Task;

        dump(libxml_get_errors());
        # 2. Crear la estrucura del proyecto (arreglos y sub arreglos)
        $estructura = [];
        $idGantt = 2;

        # 2.1 Obtener todas las actividades del GANTT
        for ($key = 0; $key < count($tareas); $key++) {
            $WBS = explode('.', $tareas[$key]->WBS);
            for ($index = 0; $index < count($WBS); $index++) {
                $WBS[$index] = max($WBS[$index] - 1, 0);
            }

            $posiciones = implode("][", $WBS);
            $espacioEnEstructura = "[" . $posiciones . "]['info']";
            $valor = &$tareas[$key];

            # 2.2 Obtener las propiedades necesarias de cada actividad del GANTT
            $properties = [
                'name' => preg_replace('/\s+/', ' ', trim((string)$tareas[$key]->Name)),
                'UID' => (int)$tareas[$key]->UID,
                'fecha_inicio' => str_replace("T", " ", (string)$tareas[$key]->Start),
                'fecha_fin' => str_replace("T", " ", (string)$tareas[$key]->Finish),
                'percent_complete' => (int)$tareas[$key]->PercentComplete,
                'percent_work_complete' => (int)$tareas[$key]->PercentWorkComplete,
                'percent_assigned' => (function () use (&$valor) {
                    foreach ($valor->ExtendedAttribute as $column) {
                        if (strpos($column->Value, '%')) {
                            return (float)str_replace('%', '', $column->Value);
                        }
                    }
                    return 0;
                })(),
                'tipo_reporte' => (function () use (&$valor) {
                    return $valor->TipoReporte ? (int)$valor->TipoReporte : 0;
                })(),
                'tipo_registro' => (function () use (&$valor) {
                    return $valor->TipoRegistro ? (int)$valor->TipoRegistro : 0;
                })(),
                'agrupador' => (function () use (&$valor) {
                    return $valor->Agrupador ? (int)$valor->Agrupador : 0;
                })(),
                'wbs' => (string)$tareas[$key]->WBS
            ];

            # 2.3 Guardar los valores por cada actividad
            eval("\$estructura$espacioEnEstructura = \$properties;");
        }

        //echo '<pre>'.var_export($estructura, true). '</pre>';
        //dump($estructura);

        # 3. Crear Registros en la Base de Datos
        $conectores = &$this->conectores;
        $crearRegistros = function ($info, $idReporte = null, $gpoPadre = null) use ($funcionesCompartidas) {
            $tipoReporte = $info['tipo_reporte'];
            $tipoRegistro = $info['tipo_registro'];
            //dump([$tipoRegistro, $tipoReporte, $idReporte]);

            if ($tipoReporte) {
                if ($tipoReporte == 1) $tipoReporte = 0;

                $idReporte = !$idReporte ? $funcionesCompartidas->crearRegistrarReportes(
                    $info['name'],
                    'Migrado desde XML',
                    $this->id_Proyecto_constant,
                    $tipoReporte) : $idReporte;
            }

            if ($tipoRegistro) {
                $gpoPadre = $gpoPadre ? $gpoPadre : 0;

                return (object)[
                    'id_gpo' => (int)$funcionesCompartidas->insertarValoresReporte($idReporte, $info['name'], $gpoPadre, $tipoReporte, $this->id_Proyecto_constant),
                    'id_reporte' => (int)$idReporte
                ];
            } else {
                return (object)['id_reporte' => (int)$idReporte];
            }
        };

        # 5. Guardar la información en la base de datos
        $armarEstructura = function ($estructura, $idNodoPadre, $porcentajePadre = null, $idReporte = null, $gpoPadre = null) use (
            &$armarEstructura,
            &$conectores,
            $idGantt,
            $gantt,
            $funcionesCompartidas,
            $crearRegistros
        ) {
            $idsRegistros = [];
            # 5.1 Guardar los nodos y crear registros de plantillas en la BD por nivel
            foreach ($estructura as $key => $item) {
                dump($item['info']['name']);
                if ($item['info']['tipo_reporte'] || $item['info']['tipo_registro']) {
                    $resultado = $idReporte ? $crearRegistros($item['info'], $idReporte, $gpoPadre) : $crearRegistros($item['info'], null, $gpoPadre);

                    if ($resultado->id_gpo) {
                        $funcionesCompartidas->insertarAvanceActividad($resultado->id_gpo, $item['info']['UID'], $this->id_Proyecto_constant);
                        $idsRegistros[$key]['id_gpo'] = $resultado->id_gpo;
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                    } else {
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                    }

                    $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $idNodoPadre], $idGantt, $resultado->id_reporte);
                } else {
                    $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $idNodoPadre], $idGantt, 'NULL');
                }

                if (!$respuesta) break;
            }

            dump($idsRegistros);

            # 5.2 Recorrer sub elementos de la estructura hasta su fin
            $conectorTemporal = [];
            $sumaPorcentaje = 0;
            foreach ($estructura as $key => $item) { # Elementos con sub elementos
                if (is_array($item) && count($item) > 1) {
                    $idNodoPadre = $item['info']['UID'];
                    $porcentajeNodo = $item['info']['percent_assigned'];
                    $agrupador = $item['info']['agrupador'];
                    $gpoPadre = $idsRegistros[$key]['id_gpo'] ? $idsRegistros[$key]['id_gpo'] : null;
                    //dump($item['info']['name'], $agrupador, $gpoPadre);
                    unset($item['info']);

                    # 5.2.1 Calcular porcentajes en orden ascendente a través del árbol
                    if (count($item) == 1 && $porcentajeNodo > 0) {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, $porcentajeNodo, $idsRegistros[$key]['id_reporte'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, $porcentajeNodo, null, $gpoPadre);
                    } else {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, null, $idsRegistros[$key]['id_reporte'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, null, null, $gpoPadre);
                    }
                    # 5.2.2 Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($idNodoPadre, $porcentaje, $idGantt);

                    # 5.2.3 Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $porcentaje;
                } else { # Elementos sin sub elementos (ultimo nivel)
                    # 5.2.4 Guardar nodos de ultimo Nivel
                    $conectorTemporal[] = $item['info']['UID'];

                    if (!is_null($porcentajePadre)) {
                        $item['info']['percent_assigned'] = $porcentajePadre;
                    }

                    # Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($item['info']['UID'], $item['info']['percent_assigned'], $idGantt);

                    # Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $item['info']['percent_assigned'];
                }
            }

            if (!empty($conectorTemporal)) {
                # 5.2.5 Agregar los conectores captados al contenedor general de nodos
                $conectores[] = $conectorTemporal;
            }

            return $sumaPorcentaje;
        };

        $armarEstructura($estructura, '0');

        # 6. Guardar todos los conectores en la base de datos
        foreach ($conectores as $grupo) {
            for ($key = 0; $key < count($grupo) - 1; $key++) {
                $gantt->guardarConector($grupo[$key], $grupo[$key + 1], $idGantt);
            }
        }
    }

    public function crear()
    {
        $nombreArchivo = $_POST['nombre_archivo'];
        
        # 0. Instanciación de clases
        $gantt = new LlenadoGantt($this->adapter);
        $funcionesCompartidas = new FuncionesCompartidas();

        $idGantt = $gantt->getIdGanttByid_proyecto($this->id_Proyecto_constant)[0]->id;
        # 1. Leer archivo XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file("gantt/$nombreArchivo");
        $tareas = $xml->Registro;

        $estructura = $this->crearEstructuradelXML($tareas);

        //echo '<pre>' . var_export($estructura, true) . '</pre>';
        //echo '<pre>'.var_export($estructura, true). '</pre>';
        //dump($estructura);

        # 3. Crear Registros en la Base de Datos
        $conectores = &$this->conectores;
        $crearRegistros = function ($info, $idReporte = null, $tipoReportePadre = null, $gpoPadre = null) use ($funcionesCompartidas) {
            $tipoReporte = $info['tipo_reporte'];
            $tipoRegistro = $info['tipo_registro'];

            if ($tipoReporte) {
                if ($tipoReporte == 1) $tipoReporte = 0;

                $idReporte = !$idReporte ? $funcionesCompartidas->crearRegistrarReportes(
                    $info['name'],
                    'Migrado desde XML',
                    $this->id_Proyecto_constant,
                    $tipoReporte) : $idReporte;
            }

            if ($tipoRegistro) {
                $gpoPadre = $gpoPadre ? $gpoPadre : 0;
                $tipoReporte = $tipoReportePadre ? $tipoReportePadre : $tipoReporte;

                return (object)[
                    'id_gpo' => (int)$funcionesCompartidas->insertarValoresReporte($idReporte, $info['name'], $gpoPadre, $tipoReporte, $this->id_Proyecto_constant),
                    'id_reporte' => (int)$idReporte
                ];
            } else {
                return (object)['id_reporte' => (int)$idReporte, 'tipo_reporte' => $tipoReporte];
            }
        };

        # 5. Guardar la información en la base de datos
        $armarEstructura = function ($estructura, $idNodoPadre, $porcentajePadre = null, $idReporte = null, $tipoReportePadre = null, $gpoPadre = null) use (
            &$armarEstructura,
            &$conectores,
            $idGantt,
            $gantt,
            $funcionesCompartidas,
            $crearRegistros
        ) {
            $idsRegistros = [];
            # 5.1 Guardar los nodos y crear registros de plantillas en la BD por nivel
            foreach ($estructura as $key => $item) {
                if ($item['info']['tipo_reporte'] || $item['info']['tipo_registro']) {
                    $resultado = $idReporte ? $crearRegistros($item['info'], $idReporte, $tipoReportePadre, $gpoPadre) :
                        $crearRegistros($item['info'], null, null, $gpoPadre);

                    if ($resultado->id_gpo) {
                        $funcionesCompartidas->insertarAvanceActividad($resultado->id_gpo, $item['info']['UID'], $this->id_Proyecto_constant);
                        $idsRegistros[$key]['id_gpo'] = $resultado->id_gpo;
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                    } else {
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                        $idsRegistros[$key]['tipo_reporte_padre'] = $resultado->tipo_reporte;
                    }

                    $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $idNodoPadre], $idGantt, $resultado->id_reporte);
                } else {
                    $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $idNodoPadre], $idGantt, 'NULL');
                }

                if (!$respuesta) break;
            }

            # 5.2 Recorrer sub elementos de la estructura hasta su fin
            $conectorTemporal = [];
            $sumaPorcentaje = 0;
            foreach ($estructura as $key => $item) { # Elementos con sub elementos
                if (is_array($item) && count($item) > 1) {
                    $idNodoPadre = $item['info']['UID'];
                    $porcentajeNodo = $item['info']['percent_assigned'];
                    $agrupador = $item['info']['agrupador'];
                    $gpoPadre = $idsRegistros[$key]['id_gpo'] ? $idsRegistros[$key]['id_gpo'] : null;
                    //dump($item['info']['name'], $agrupador, $gpoPadre);
                    $wbs = $item['info']['wbs'];
                    unset($item['info']);

                    # 5.2.1 Calcular porcentajes en orden ascendente a través del árbol
                    if (count($item) == 1 && $porcentajeNodo > 0) {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, $porcentajeNodo, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, $porcentajeNodo, null, null, $gpoPadre);
                    } else {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, null, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, null, null, null, $gpoPadre);
                    }
                    # 5.2.2 Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($wbs, $porcentaje, $idGantt);

                    # 5.2.3 Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $porcentaje;
                } else { # Elementos sin sub elementos (ultimo nivel)
                    # 5.2.4 Guardar nodos de ultimo Nivel
                    $conectorTemporal[] = $item['info']['UID'];

                    if (!is_null($porcentajePadre)) {
                        $item['info']['percent_assigned'] = $porcentajePadre;
                    }

                    # Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($item['info']['wbs'], $item['info']['percent_assigned'], $idGantt);

                    # Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $item['info']['percent_assigned'];
                }
            }

            if (!empty($conectorTemporal)) {
                # 5.2.5 Agregar los conectores captados al contenedor general de nodos
                $conectores[] = $conectorTemporal;
            }

            return $sumaPorcentaje;
        };

        $armarEstructura($estructura, '0');

        # 6. Guardar todos los conectores en la base de datos
        foreach ($conectores as $grupo) {
            for ($key = 0; $key < count($grupo) - 1; $key++) {
                $gantt->guardarConector($grupo[$key], $grupo[$key + 1], $idGantt);
            }
        }

        # 7. Crear JSON en tabla gantt
        $this->armarEstructura();

        echo json_encode(["status" => "success", "code" => 200]);
    }

    function actualizar()
    {
        $idGantt = 8;
        # 0. Instanciación de clases
        $gantt = new LlenadoGantt($this->adapter);
        $funcionesCompartidas = new FuncionesCompartidas();

        # 1. Leer archivo XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file("gantt/pier_v1.xml");
        $tareas = $xml->Registro;

        //dump(libxml_get_errors());

        $estructura = $this->crearEstructuradelXML($tareas);

        # 3. Crear Registros en la Base de Datos
        $conectores = &$this->conectores;
        $crearRegistros = function ($info, $idReporte = null, $tipoReportePadre = null, $gpoPadre = null) use ($funcionesCompartidas) {
            $tipoReporte = $info['tipo_reporte'];
            $tipoRegistro = $info['tipo_registro'];

            if ($tipoReporte) {
                if ($tipoReporte == 1) $tipoReporte = 0;

                $idReporte = !$idReporte ? $funcionesCompartidas->crearRegistrarReportes(
                    $info['name'],
                    'Migrado desde XML',
                    $this->id_Proyecto_constant,
                    $tipoReporte) : $idReporte;
            }

            if ($tipoRegistro) {
                $gpoPadre = $gpoPadre ? $gpoPadre : 0;
                $tipoReporte = $tipoReportePadre ? $tipoReportePadre : $tipoReporte;

                return (object)[
                    'id_gpo' => (int)$funcionesCompartidas->insertarValoresReporte($idReporte, $info['name'], $gpoPadre, $tipoReporte, $this->id_Proyecto_constant),
                    'id_reporte' => (int)$idReporte
                ];
            } else {
                return (object)['id_reporte' => (int)$idReporte, 'tipo_reporte' => $tipoReporte];
            }
        };

        # 5. Guardar la información en la base de datos
        $armarEstructura = function (
            $estructura,
            $idNodoPadre,
            $porcentajePadre = null,
            $idReporte = null,
            $tipoReportePadre = null,
            $gpoPadre = null
        ) use (
            &$armarEstructura,
            &$conectores,
            $idGantt,
            $gantt,
            $funcionesCompartidas,
            $crearRegistros
        ) {
            $idsRegistros = [];
            # 5.1 Guardar los nodos y crear registros de plantillas en la BD por nivel

            foreach ($estructura as $key => $item) {
                $nodoConsultado = $gantt->getRegistroGanttValoresByIdGanttAndWBS($idGantt, $item['info']['wbs']);
                if (!$nodoConsultado) {
                    # Armar WBS padre
                    $arrayWBS = explode(".", $item['info']['wbs']);
                    count($arrayWBS) < 1 ?: array_pop($arrayWBS);
                    $wbs = implode(".", $arrayWBS);

                    $registroPadre = $gantt->getRegistroGanttValoresByIdGanttAndWBS($idGantt, $wbs);
                    $nodoPadre = $registroPadre[0]->id_nodo;

                    //dump($item['info']['name']);
                    $item['info']['UID'] = (int)$gantt->getUltimoRegistroIdNodoByIdGantt($idGantt)[0]->id_nodo + 1;
                    //dump($nodoConsultado, $item, $idReporte, $tipoReportePadre);

                    dump($item['info']);
                    if ($item['info']['tipo_reporte'] || $item['info']['tipo_registro']) {
                        //dump($item['info']);

                        # Obtener avance actividad
                        $registroAvanceActividad = $gantt->getGpoValoresByIdNodo($nodoPadre);
                        if ($registroAvanceActividad) {
//                            $datosReporteLlenado = $gantt->getAllReportesLlenadosByIdGpo($registroAvanceActividad[0]->gpo_valores);
//                            $idReporteLlenado = $datosReporteLlenado[0]->id_Reporte;
//                            $tipoReporte = $datosReporteLlenado[0]->clas_Reporte;
                            $gpoPadre = $registroAvanceActividad[0]->gpo_valores;
                        } else {
                            $datosReportePadre = $gantt->getAllCatReportesByIdReporte($registroPadre[0]->id_reporte);
                            $idReporteLlenado = $datosReportePadre[0]->id_Reporte;
                            $tipoReporte = $datosReportePadre[0]->tipo_Reporte;
                            $gpoPadre = 0;
                        }


                        $resultado = $idReporteLlenado && $tipoReporte ? $crearRegistros($item['info'], $idReporteLlenado, $tipoReporte, $gpoPadre) :
                            $crearRegistros($item['info'], null, null, $gpoPadre);

                        if ($resultado->id_gpo) {
                            $funcionesCompartidas->insertarAvanceActividad($resultado->id_gpo, $item['info']['UID'], $this->id_Proyecto_constant);
                            $idsRegistros[$key]['id_gpo'] = $resultado->id_gpo;
                            $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                        } else {
                            $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                            $idsRegistros[$key]['tipo_reporte_padre'] = $resultado->tipo_reporte;
                        }
                        $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $nodoPadre], $idGantt, $resultado->id_reporte);
                    } else {
                        $respuesta = $this->guardarNodo($item['info'] + ['nodoPadre' => $nodoPadre], $idGantt, 'NULL');
                    }

                    if (!$respuesta) break;
                }

            }


            # 5.2 Recorrer sub elementos de la estructura hasta su fin
            $conectorTemporal = [];
            $sumaPorcentaje = 0;
            foreach ($estructura as $key => $item) { # Elementos con sub elementos
                //$nodoConsultado = $gantt->getRegistroGanntValoresByIdGanttAndBWS($idGantt, $item['info']['wbs']);
                if (is_array($item) && count($item) > 1) {
                    $idNodoPadre = $item['info']['UID'];
                    $porcentajeNodo = $item['info']['percent_assigned'];
                    if ($porcentajeNodo > 0)
                        dump($porcentajeNodo);
                    $agrupador = $item['info']['agrupador'];
                    $gpoPadre = $idsRegistros[$key]['id_gpo'] ? $idsRegistros[$key]['id_gpo'] : null;
                    $wbs = $item['info']['wbs'];
                    //dump($item['info']['name'], $agrupador, $gpoPadre);
                    unset($item['info']);

                    # 5.2.1 Calcular porcentajes en orden ascendente a través del árbol
                    if (count($item) == 1 && $porcentajeNodo > 0) {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, $porcentajeNodo, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, $porcentajeNodo, null, null, $gpoPadre);
                    } else {
                        $porcentaje = $agrupador ? $armarEstructura($item, $idNodoPadre, null, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, $idNodoPadre, null, null, null, $gpoPadre);
                    }
                    # 5.2.2 Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($wbs, $porcentaje, $idGantt);

                    # 5.2.3 Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $porcentaje;
                } else { # Elementos sin sub elementos (ultimo nivel)
                    # 5.2.4 Guardar nodos de ultimo Nivel
                    $conectorTemporal[] = $item['info']['UID'];

                    if (!is_null($porcentajePadre)) {
                        $item['info']['percent_assigned'] = $porcentajePadre;
                    }

                    //dump($item['info']);
                    # Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($item['info']['wbs'], $item['info']['percent_assigned'], $idGantt);

                    # Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $item['info']['percent_assigned'];
                }
            }

            if (!empty($conectorTemporal)) {
                # 5.2.5 Agregar los conectores captados al contenedor general de nodos
                $conectores[] = $conectorTemporal;
            }

            return $sumaPorcentaje;
        };

        $armarEstructura($estructura, '0');

        # 6. Guardar todos los conectores en la base de datos
        foreach ($conectores as $grupo) {
            for ($key = 0; $key < count($grupo) - 1; $key++) {
                $gantt->guardarConector($grupo[$key], $grupo[$key + 1], $idGantt);
            }
        }

        # 7. Crear JSON en tabla gantt
        $this->armarEstructura();
    }

    function guardarNodo($nodo, $idGantt, $idReporte)
    {
        //dump($nodo);
        $gantt = new LlenadoGantt($this->adapter);
        $gantt->setActividad($nodo['name']);
        $gantt->setInicio($nodo['fecha_inicio']);
        $gantt->setFin($nodo['fecha_fin']);
        $gantt->setIdNodo($nodo['UID']);
        $gantt->setIdNodoPadre($nodo['nodoPadre']);
        $gantt->setPorcentaje('NULL');
        $gantt->setCosto($nodo['costo']);
        $gantt->setWbs($nodo['wbs']);
        $gantt->setConnectTo('NULL');
        $gantt->setIdStatus(1);
        $gantt->setIdReporte($idReporte);
        $gantt->setIdGantt($idGantt);

        //$gantt->guardar();

        return $gantt->guardar();
    }

    /************* Crea JSON a partir de la tabla de gantt_valores y Guarda en la tabla de gantt *********************/
    function armarEstructura()
    {
        $gantt = new Gantt($this->adapter);
        $funcionesCompartidas = new FuncionesCompartidas();
        $estructura = [];

        # Obtener id gantt
        $idGantt = $gantt->getIdGanttByid_proyecto($this->id_Proyecto_constant)[0]->id;

        # Obtener sub nodos
        $subNodos = $gantt->getSubNodos(0);

        # Armar estructura
        $funcionesCompartidas->armarEstructura($subNodos, $estructura);

        # Reemplazar slash por dobles
        $gantt->setEstructura(str_replace('\\"', '\\\\"', json_encode($estructura, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

        //echo $gantt->getEstructura(); // Testing

        $gantt->updateEstructura($idGantt); // Actualizar estructura
    }


    function crearEstructuradelXML($tareas)
    {
        $estructura = [];

        # 2.1 Obtener todas las actividades del GANTT
        for ($key = 0; $key < count($tareas); $key++) {
            $valor = &$tareas[$key];

            $WBS = explode('.', $valor->WBS);
            for ($index = 0; $index < count($WBS); $index++) {
                $WBS[$index] = max($WBS[$index] - 1, 0);
            }

            $posiciones = implode("][", $WBS);
            $espacioEnEstructura = "[" . $posiciones . "]['info']";

            # 2.2 Obtener las propiedades necesarias de cada actividad del GANTT
            $properties = [
                'name' => preg_replace('/\s+/', ' ', trim((string)$valor->Name)),
                'UID' => (int)$valor->UID,
                'fecha_inicio' => str_replace("T", " ", (string)$valor->Start),
                'fecha_fin' => str_replace("T", " ", (string)$valor->Finish),
                'percent_complete' => (int)$valor->PercentComplete,
                'percent_work_complete' => (int)$valor->PercentWorkComplete,
                'percent_assigned' => (function () use ($valor) {
                    if ($valor->Percent_Assigned !== "") {
                        return (float)$valor->Percent_Assigned;
                    }
                    return 0;
                })(),
                'costo' => (float)$valor->Cost,
                'tipo_reporte' => (function () use ($valor) {
                    return $valor->TipoReporte ? (int)$valor->TipoReporte : 0;
                })(),
                'tipo_registro' => (function () use ($valor) {
                    return $valor->TipoRegistro ? (int)$valor->TipoRegistro : 0;
                })(),
                'agrupador' => (function () use ($valor) {
                    return $valor->Agrupador ? (int)$valor->Agrupador : 0;
                })(),
                'wbs' => trim((string)$valor->WBS)
            ];

            # 2.3 Guardar los valores por cada actividad
            eval("\$estructura$espacioEnEstructura = \$properties;");
        }

        return $estructura;
    }

    function actualizarPorcentaje()
    {
        $idGantt = 3;
        # 0. Instanciación de clases
        $gantt = new LlenadoGantt($this->adapter);

        # 1. Leer archivo XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file("descargables/rcov9_2.xml");
        $tareas = $xml->Registro;

        //dump(libxml_get_errors());

        $estructura = $this->crearEstructuradelXML($tareas);

        # 5. Guardar la información en la base de datos
        $armarEstructura = function (
            $estructura,
            $porcentajePadre = null
        ) use (
            &$armarEstructura,
            $idGantt,
            $gantt
        ) {
            # 5.2 Recorrer sub elementos de la estructura hasta su fin
            $sumaPorcentaje = 0;
            foreach ($estructura as $key => $item) { # Elementos con sub elementos
                if (is_array($item) && count($item) > 1) {
                    $porcentajeNodo = $item['info']['percent_assigned'];

                    $wbs = $item['info']['wbs'];
                    //dump($item['info']['name'], $agrupador, $gpoPadre);
                    unset($item['info']);

                    # 5.2.1 Calcular porcentajes en orden ascendente a través del árbol
                    if (count($item) == 1 && $porcentajeNodo > 0) {
                        $porcentaje = $armarEstructura($item, $porcentajeNodo);
                    } else {
                        $porcentaje = $armarEstructura($item);
                    }

                    # 5.2.2 Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($wbs, $porcentaje, $idGantt);

                    # 5.2.3 Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $porcentaje;
                } else { # Elementos sin sub elementos (ultimo nivel)
                    # 5.2.4 Guardar nodos de ultimo Nivel

                    if (!is_null($porcentajePadre)) {
                        $item['info']['percent_assigned'] = $porcentajePadre;
                    }

                    # Guardar el porcentaje en la BD
                    $gantt->guardarPorcentaje($item['info']['wbs'], $item['info']['percent_assigned'], $idGantt);

                    # Sumar el acumulado de los porcentajes por nivel
                    $sumaPorcentaje += $item['info']['percent_assigned'];
                }
            }
            return $sumaPorcentaje;
        };

        $armarEstructura($estructura, '0');

        $gantt = new Gantt($this->adapter);
        $infoJson = $gantt->getIdGanttByid_proyecto($this->id_Proyecto_constant)[0]->estructura;

        $infoJson = !$infoJson ?: json_decode($infoJson);

        $wbsNodos = [];
        $recorrer = function ($item) use (&$recorrer, &$wbsNodos) {
            $item = (array)$item;

            if ($item['info']->completado) {
                $wbsNodos[] = ["wbs" => $item['info']->wbs, "gpo_valores" => $item['info']->gpo_valores];
            }
            unset($item['info']);
            if (count($item) == 1) {
                $item['children'] = (array)$item['children'];
                array_walk_recursive($item['children'], $recorrer);
            }
        };

        array_walk_recursive($infoJson, $recorrer);

        # 7. Crear JSON en tabla gantt
        $this->armarEstructura();

        //die();
        $infoJson = $gantt->getIdGanttByid_proyecto($this->id_Proyecto_constant)[0]->estructura;

        $infoJson = !$infoJson ?: json_decode($infoJson);

        foreach ($wbsNodos as $wbsNodo) {
            $rutaAcceso = array_map(function ($value) {
                return (int)$value - 1;
            }, explode(".", $wbsNodo['wbs']));

            var_dump($rutaAcceso);

            $ruta = '[0]->children';
            foreach (array_slice($rutaAcceso, 1, count($rutaAcceso) - 2) as $nivel) {
                $ruta .= "[{$nivel}]->children";
            }

            $ruta .= "[" . end($rutaAcceso) . "]->info";
            eval("\$infoJson{$ruta}->completado = true;");
            eval("\$infoJson{$ruta}->gpo_valores = \$wbsNodo['gpo_valores'];");
        }

        $gantt->setEstructura(str_replace('\\"', '\\\\"', json_encode($infoJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));

        $gantt->updateEstructura($idGantt);
    }

    function previsualizar()
    {
        # 1. Leer archivo XML
        $ruta = $_POST['nombre_archivo'];

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file("gantt/$ruta");
        $tareas = $xml->Registro;

        //dump(libxml_get_errors());

        $concentrado = new stdClass();
        $concentrado->plantillas = [];
        /*      $concentrado->plantillas[0] = [
                  'actividad' => "hola",
                  'descripcion' => "hola",
                  'tipo_reporte' => 2
              ];
              $concentrado->plantillas[1] = [
                  'actividad' => "hola2",
                  'descripcion' => "hola",
                  'tipo_reporte' => 2
              ];*/

        $concentrado->registros = [];

        $creador = new stdClass();
        $creador->catalogoReportes = [
            0 => "Reporte",
            2 => "Ubicación",
            3 => "Inventario"
        ];

        $creador->crearRegistrarReportes = function (string $actividad, string $descripcion, int $tipoReporte) use (
            $creador,
            &$concentrado
        ) {
            $idPlantilla = array_search($actividad, array_column($concentrado->plantillas, 'actividad'));
            if ($idPlantilla === false) {
                $concentrado->plantillas[] = [
                    'actividad' => $actividad,
                    'descripcion' => $descripcion,
                    'tipo_reporte' => $creador->catalogoReportes[$tipoReporte]
                ];

                end($concentrado->plantillas);
                return key($concentrado->plantillas);
            }
            return $idPlantilla;
        };

        $creador->insertarValoresReporte = function (int $idReporte, string $actividad, int $gpoPadre, int $tipoReporte) use (
            $creador,
            &$concentrado
        ) {
            $concentrado->registros[] = [
                'id_reporte' => $idReporte,
                'actividad' => $actividad,
                'gpo_padre' => $gpoPadre,
                'tipo_reporte' => $creador->catalogoReportes[$tipoReporte]
            ];

            end($concentrado->registros);
            return key($concentrado->registros);
        };

        //var_dump($creador->crearRegistrarReportes->__invoke("hola2", "hola", 2));

        //var_dump($concentrado->plantillas);

        $estructura = $this->crearEstructuradelXML($tareas);

        # 3. Crear Registros en la Base de Datos
        $conectores = &$this->conectores;
        $crearRegistros = function ($info, $idReporte = null, $tipoReportePadre = null, $gpoPadre = null) use (&$creador) {
            $tipoReporte = $info['tipo_reporte'];
            $tipoRegistro = $info['tipo_registro'];

            if ($tipoReporte) {
                if ($tipoReporte == 1) $tipoReporte = 0;

                $idReporte = !$idReporte ? $creador->crearRegistrarReportes->__invoke(
                    $info['name'],
                    'Migrado desde XML',
                    $tipoReporte) : $idReporte;
            }

            if ($tipoRegistro) {
                $gpoPadre = $gpoPadre ? $gpoPadre : 0;
                $tipoReporte = $tipoReportePadre ? $tipoReportePadre : $tipoReporte;

                return (object)[
                    'id_gpo' => (int)$creador->insertarValoresReporte->__invoke($idReporte, $info['name'], $gpoPadre, $tipoReporte),
                    'id_reporte' => (int)$idReporte
                ];
            } else {
                return (object)['id_reporte' => (int)$idReporte, 'tipo_reporte' => $tipoReporte];
            }
        };

        # 5. Guardar la información en la base de datos
        $armarEstructura = function ($estructura, $idReporte = null, $tipoReportePadre = null, $gpoPadre = null) use (
            &$armarEstructura,
            &$conectores,
            $crearRegistros
        ) {
            $idsRegistros = [];
            # 5.1 Guardar los nodos y crear registros de plantillas en la BD por nivel
            foreach ($estructura as $key => $item) {
                if ($item['info']['tipo_reporte'] || $item['info']['tipo_registro']) {
                    $resultado = $idReporte ? $crearRegistros($item['info'], $idReporte, $tipoReportePadre, $gpoPadre) :
                        $crearRegistros($item['info'], null, null, $gpoPadre);

                    if ($resultado->id_gpo) {
                        // $funcionesCompartidas->insertarAvanceActividad($resultado->id_gpo, $item['info']['UID'], $this->id_Proyecto_constant);
                        $idsRegistros[$key]['id_gpo'] = $resultado->id_gpo;
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                    } else {
                        $idsRegistros[$key]['id_reporte'] = $resultado->id_reporte;
                        $idsRegistros[$key]['tipo_reporte_padre'] = $resultado->tipo_reporte;
                    }
                }
            }

            # 5.2 Recorrer sub elementos de la estructura hasta su fin
            foreach ($estructura as $key => $item) { # Elementos con sub elementos
                if (is_array($item) && count($item) > 1) {
                    $porcentajeNodo = $item['info']['percent_assigned'];
                    $agrupador = $item['info']['agrupador'];
                    $gpoPadre = $idsRegistros[$key]['id_gpo'] ? $idsRegistros[$key]['id_gpo'] : null;
                    //dump($item['info']['name'], $agrupador, $gpoPadre);
                    unset($item['info']);

                    # 5.2.1 Calcular porcentajes en orden ascendente a través del árbol
                    if (count($item) == 1 && $porcentajeNodo > 0) {
                        $agrupador ? $armarEstructura($item, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, null, null, $gpoPadre);
                    } else {
                        $agrupador ? $armarEstructura($item, $idsRegistros[$key]['id_reporte'], $idsRegistros[$key]['tipo_reporte_padre'], $gpoPadre) :
                            $armarEstructura($item, null, null, $gpoPadre);
                    }
                }
            }
        };

        $armarEstructura($estructura, '0');

        //var_dump($concentrado);
        echo json_encode($concentrado, true);
    }
}
