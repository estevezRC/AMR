<?php

require 'Consultas.php';

require_once '../config/global.php';
require_once '../core/EntidadBase.php';
require_once '../core/FuncionesCompartidas.php';
require_once '../model/ReporteLlenado.php';
require_once '../model/MatrizComunicacion.php';
require_once '../model/Fotografia.php';
require_once '../model/Campo.php';
require_once '../model/Notificaciones.php';
require_once '../vendor/autoload.php';
require_once '../model/Proyecto.php';
require_once '../model/ProcesosAvances.php';
require_once '../model/Procesos.php';
require_once '../model/AvanceActividad.php';
require_once '../model/Gantt.php';
require_once '../model/Asistencia.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);

    //print_r($body);

    $array=$body['Reportes'];

    $UsuerAndroid = $body['UsuarioAndroid'];
    $idEmpresa = $body['idEmpresa'];
    $nombreCarpeta = $body['nombreCarpeta'];

    session_start();
    $_SESSION['id_empresa_movil'] = $idEmpresa;
    $_SESSION['id_EmpresaGeneral_Supervisor'] = $idEmpresa;

    $statusValores=false;
    $statusFotografia=false;
    $statusReportesLlenados=false;
    $statusRelacion=false;

    //print_r($array);

    $idAnteriorAndroid = 0;
    $idGpoAnteriorWeb = 0;
    for($i=0;$i<count($array);$i++){

        $arrayReportes = $array[$i]['Reporte'];

        $idGpoAndoid = $arrayReportes['idRepLlenado'];

        $idGpo = $arrayReportes['id_Gpo_Valores_Reporte'];//$maxGpoValores;
        $comentariosRep = $arrayReportes['Comentarios_Reporte'];
        $statusElemento = $arrayReportes['id_Status_Elemento'];
        $fechaRegistro = $arrayReportes['fecha_registro'];
        $idUsuario = $arrayReportes['id_Usuario'];
        $idReporte = $arrayReportes['id_Reporte'];
        $idGpoUbicacion = $arrayReportes['Id_Gpo_Ubicacion'];
        $modoStatus = $arrayReportes['modoStatus'];
        $tituloReporte = $arrayReportes['tituloReporte'];
        $notificaReporte = $arrayReportes['notificaReporte'];
        $etapaReporte = $arrayReportes['idEtapa'];
        $tiempoReporte = $arrayReportes['idTiempo'];
        $idPadreReporte = $arrayReportes['idGpoPadreReporte'];
        $latitudReporte = $arrayReportes['latitudReporte'];
        $longitudReporte = $arrayReportes['longitudReporte'];
        $classReporte = $arrayReportes['class_reporte'];
        $idProcesoReporte = $arrayReportes['id_Proceso'];

        if($idProcesoReporte == " " || $idProcesoReporte == null){
            //echo "idProceso nulo o vacio <br>";
            $idProcesoReporte = "0";
        }

        if($modoStatus=="0"){
            $idGpo = Consultas::getMaxGpoValoresReportes();
        }else if($modoStatus=="1"){
            $idGpo = $idGpo;
        }

        //echo 'gpo '.$idGpo;

        if($idAnteriorAndroid!=$idGpoAndoid){
            $resCompara = true;
        }else{
            $resCompara = false;
        }



        /*echo "idAnterior android...".$idAnteriorAndroid;
        echo "...actual android...".$idGpoAndoid;
        echo "<br />"."gpoUicacion: ".$idGpoUbicacion.",";
        echo $idGpo.", ";
        echo $comentariosRep.", ";
        echo $statusElemento.", ";
        echo $fechaRegistro.", ";
        echo $idUsuario.", ";
        echo $idReporte.",";
        echo $tituloReporte."<br />";*/

        $consultaExiste = Consultas::existeReporte($fechaRegistro,$idUsuario);

        if($consultaExiste && $modoStatus == "0"){
            $statusValores = true;
            $statusFotografia = true;
            $statusReportesLlenados = true;
            $statusRelacion = true;

            //echo "existe".$idGpo."<br />";
        }else {
            //echo "no modifica ".$idGpo."<br />";
            $arrayValores = $arrayReportes['Valores'];
            $arrayImagenes = $arrayReportes['Imagenes'];

            for ($j = 0; $j < count($arrayValores); $j++) {

                $idval = $arrayValores[$j]['idValores'];
                //$idProyecto = "1";//$arrayValores[$j]['id_Proyecto'];

                if ($modoStatus == "0") {
                    $idGpoVal = $idGpo;//$maxGpoValores;//$arrayValores[$j]['id_Gpo_Valores_Reporte'];
                } else if ($modoStatus == "1") {
                    $idGpoVal = $arrayValores[$j]['id_Gpo_Valores_Reporte'];
                }

                $idProyecto = $arrayValores[$j]['id_Proyecto'];
                $val_texto = $arrayValores[$j]['valor_Texto_Reporte'];
                $val_entero = $arrayValores[$j]['valor_Entero_Reporte'];
                $configuracion_rep = $arrayValores[$j]['id_Configuracion_Reporte'];
                $tipoValor = $arrayValores[$j]['tipoValor'];
                $tipoReactivo = $arrayValores[$j]['tipoReactivo'];


                if ($tipoValor == "varchar") {
                    $val_entero = NULL;
                }

                /************************ nuevo codigo ***************************
                if($tipoReactivo == "select" || $tipoReactivo == "radio"){
                $datosCampo = Consultas::getValoresCampo($configuracion_rep);

                $idCampo = $datosCampo[1];
                $valoresCampo = $datosCampo[0];
                $arryValores = explode("/",$valoresCampo);

                $existe = "no";
                for($z=0;$z<count($arryValores);$z++) {
                if ($arryValores[$z] == $val_texto) {
                $existe = "si";
                }
                }

                if($existe == "no"){
                $valoresCampo = $valoresCampo."/".$val_texto;
                $actualiza = Consultas::actualizaValorDefault($idCampo,$valoresCampo);

                if($actualiza){
                }
                }
                }//select radio

                if($tipoReactivo == "select-catalogo"){
                if(is_numeric($val_texto)) {
                } else {
                $exiteConcepto = Consultas::getExisteConceptoCatalogo($val_texto);
                if(!$exiteConcepto){
                $datosCampo = Consultas::getValoresCampo($configuracion_rep);
                $idCampo = $datosCampo[1];
                $valoresCampo = $datosCampo[0];
                $arryValores = explode(",",$valoresCampo);
                $categoria = $arryValores[0];
                $insertaCatalogo = Consultas::InsertaCatalogoCategoria($val_texto,$categoria);
                if($insertaCatalogo){
                $idConceptoCatalogo = Consultas::getIdConceptoCatalogo($val_texto);
                $val_texto = $idConceptoCatalogo;
                }
                }else{
                $idConceptoCatalogo = Consultas::getIdConceptoCatalogo($val_texto);
                $val_texto = $idConceptoCatalogo;
                }
                }
                }//select catalogo

                /************************ nuevo codigo ****************************/
                /*echo "___".$idval.",";
                echo $idProyecto.",";
                echo $idGpoVal.",";
                echo $val_texto.",";
                echo $val_entero.",";
                echo $configuracion_rep."<br/>";*/

                if($classReporte == "6" || $classReporte == "7"){
                    if($tipoReactivo == "date"){
                        $fechaAsistencia = $val_texto;
                    }else if($tipoReactivo == "time"){
                        $horaAsistencia = $val_texto;
                    }else if($tipoReactivo == "check_list_asistencia"){
                        $arrayIdsEmpleados = explode("/",$val_texto);
                    }else if($tipoReactivo == "select"){
                        $turnoAsistencia = $val_texto;
                    }
                }

                if ($modoStatus == "0") {
                    if ($resCompara) {
                        $insertaValores = Consultas::insertaValoresReportes(
                            $idProyecto,
                            $val_texto,
                            $val_entero,
                            $configuracion_rep,
                            "Valores_Reportes",
                            $idGpoVal
                        );
                    } else {
                        $insertaValores = true;
                        //echo "no inserta valores ".$idAnteriorAndroid." == ".$idGpoAndoid;
                    }
                } else if ($modoStatus == "1") {
                    //echo "modifica".$val_texto.",".$val_entero.",".$configuracion_rep.",".$idGpoVal."</br>";
                    if ($tipoReactivo == "file") {
                        $insertaValores = true;
                    } else {
                        if(Consultas::existeCampoReporte($idGpoVal,$configuracion_rep)) {
                            $insertaValores = Consultas::modificaValoresReportes($val_texto, $val_entero, $configuracion_rep, $idGpoVal);

                        }else{

                            if(Consultas::existeConfiguracionCampoReporte($configuracion_rep,$idReporte)) {
                                echo 'insertacampo...';
                                $insertaValores = Consultas::insertaValoresReportes(
                                    $idProyecto,
                                    $val_texto,
                                    $val_entero,
                                    $configuracion_rep,
                                    "Valores_Reportes",
                                    $idGpoVal
                                );
                            }else{
                                $insertaValores = false;
                            }
                        }

                        /*if($insertaValores) {
                            echo 'Inserta valores ' . $insertaValores;
                        }else{
                            echo 'No inserta valores ' . $insertaValores;
                        }*/
                    }
                }

                if ($insertaValores) {
                    $statusValores = true;
                } else {
                    $statusValores = false;
                }

                //echo $statusValores.'</br>';
            }//fin forValores

            if (count($arrayImagenes) <= 0) {
                $statusFotografia = true;
            } else {
                if($statusValores) {
                    for ($k = 0; $k < count($arrayImagenes); $k++) {

                        $idval = $arrayImagenes[$k]['idImagen'];
                        $idUsuario = $arrayImagenes[$k]['id_Usuario'];
                        $idModulo = $arrayImagenes[$k]['id_Modulo'];
                        $idGpoImagenes = $idGpo;//$maxGpoValores;//$arrayImagenes[$k]['identificador_Fotografia'];
                        $nombreImagen = $arrayImagenes[$k]['nombre_Fotografia'];
                        $descripcionFotografia = $arrayImagenes[$k]['descripcion_fotografia'];
                        $latitudFotografia = $arrayImagenes[$k]['latitud_Fotografia'];
                        $longitudFotografia = $arrayImagenes[$k]['longitud_Fotografia'];
                        $altitudFotografia = $arrayImagenes[$k]['altitud_Fotografia'];
                        $fechaFotografia = $arrayImagenes[$k]['fecha_Fotografia'];
                        $horaFotografia = $arrayImagenes[$k]['hora_Fotografia'];
                        $idStatusFotografia = $arrayImagenes[$k]['id_Status_Fotografia'];
                        $orientacion = $arrayImagenes[$k]['fotOrientacion'];
                        $clasificacion = $arrayImagenes[$k]['fotClasificacion'];
                        $cadenamientoFotografia = $arrayImagenes[$k]['fotCadenamiento'];
                        $cuerpoFotografia = $arrayImagenes[$k]['fotCuerpo'];

                        //echo $clasificacion;
                        /*   echo "___".$idval.",";
                           echo $idProyecto.",";
                           echo $idGpoVal.",";
                           echo $val_texto.",";
                           echo $val_entero.",";
                           echo $configuracion_rep."<br/>";*/

                        //echo Consultas::getExisteFotografia($nombreImagen,$idGpoImagenes);

                        $existeFoto = Consultas::getExisteFotografia($nombreImagen, $idGpoImagenes);
                        if ($existeFoto == "no") {
                            //if ($resCompara) {
                            $insertaFotografia = Consultas::insertaFotografiasAlterno(
                                $idUsuario,
                                $idModulo,
                                $idGpoImagenes,
                                $clasificacion,
                                $nombreImagen,
                                $latitudFotografia,
                                $altitudFotografia,
                                $longitudFotografia,
                                $descripcionFotografia,
                                1,
                                "Agregar",
                                $fechaFotografia,
                                $horaFotografia,
                                $orientacion,
                                $idStatusFotografia,
                                $cadenamientoFotografia,
                                $cuerpoFotografia
                            );

                            //echo 'inserta '.$nombreImagen;
                            /*} else {
                                $insertaFotografia = true;
                            }*/
                        } else if ($existeFoto == "ok") {
                            $insertaFotografia = Consultas::modificaFotografias(
                                $idGpoImagenes,
                                $nombreImagen,
                                $idStatusFotografia,
                                $descripcionFotografia,
                                $clasificacion,
                                $cadenamientoFotografia,
                                $cuerpoFotografia);

                            //echo 'modifica '.$nombreImagen;
                        }

                        if ($insertaFotografia) {
                            $statusFotografia = true;
                        } else {
                            $statusFotografia = false;
                        }
                    }//fin forImagenes
                }//if statusValores
            }//fin elseArray Imagenes

            if($statusValores) {
                if ($modoStatus == "0") {
                    $insertaReportes = true;
                    //echo "inserta con titulo ".$tituloReporte;
                    if ($resCompara) {
                        $insertaReportes = Consultas::InsertaReportesLlenadosAlterno(
                            $idGpo,
                            $comentariosRep,
                            $statusElemento,
                            $fechaRegistro,
                            $idUsuario,
                            $idReporte,
                            $tituloReporte,
                            $etapaReporte,
                            $tiempoReporte,
                            $idPadreReporte,
                            $latitudReporte,
                            $longitudReporte,
                            $classReporte
                        );
                    } else {
                        $insertaReportes = true;
                    }
                    //echo "inserta con titulo ".$tituloReporte." con gpoVal ".$idGpoVal;
                } else if ($modoStatus == "1") {
                    $insertaReportes = true;
                    //echo "no inserta";
                    $insertaReportes = Consultas::ModificaInfoReportes($idGpo, $tituloReporte, $statusElemento, $etapaReporte, $idPadreReporte, $latitudReporte, $longitudReporte, $classReporte);
                    //echo "modifica Titulo ".$tituloReporte." del gpo: ".$idGpoVal;
                    //$insertaReportes=true;
                }
            }

            if ($insertaReportes) {
                $statusReportesLlenados = true;
                if ($modoStatus == "0") {
                    if ($resCompara) {
                        //Funciones::EnviaCorreo($idReporte, $idGpo, $fechaRegistro, $idUsuario, $tituloReporte, $idGpoUbicacion, $statusElemento,$classReporte);
                        //echo "inserta seguimiento: " . $idGpo . "<br/>";
                        $funcion = new FuncionesCompartidas();
                        $funcion->enviarNotificaciones($idGpo,$idProyecto,$idEmpresa,$nombreCarpeta);
                        $idGpoWeb = $idGpo;

                        /*if($idProcesoReporte != "0"){// || $idProcesoReporte != null || $idProcesoReporte != "null" || $idProcesoReporte != NULL){
                            $funcion->procesosAvance($idPadreReporte,$idGpo,$idProcesoReporte);
                        }*/

                        /***************************************** CODIGO PARA AVANCE **********************************************/
                        
                        $funcion->obtenerIdProceso($idPadreReporte,$idGpo,$idReporte);
                        $idGpoWeb = $idGpo;

                        $funcion->guardarAvanceActividad($idPadreReporte,$idGpo,$idReporte,$idProyecto);

                        /***************************************** CODIGO PARA AVANCE **********************************************/
                        
                        if($classReporte == "6"){
                            $funcion->InsertarAsistencia($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $turnoAsistencia, $idProyecto, $idGpo);
                        }

                        if($classReporte == "7"){
                            $funcion->updateAsistenciaHoraSalida($arrayIdsEmpleados, $fechaAsistencia, $horaAsistencia, $idPadreReporte);
                        }
                    }
                }else {
                    $funcion = new FuncionesCompartidas();
                    $funcion->obtenerIdProceso($idPadreReporte,$idGpo,$idReporte);

                    $id_nodoAnterior = $funcion->obtenerIdNodoAnterior($idGpo, $idProyecto);
                    //echo $id_nodoAnterior;
                    $funcion->modificarGantt($idPadreReporte, $idGpo, $idReporte, $idProyecto, $id_nodoAnterior);
                }
            } else {
                $statusReportesLlenados = false;
            }
        }

        $idAnteriorAndroid = $idGpoAndoid;
    }//fin 1 for

    if($statusReportesLlenados && $statusValores && $statusFotografia){
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Creación exitosa')
        );
    }else{
        print json_encode(
            array(
                'estado' => '2',
                'mensaje' => 'Creación fallida')
        );
    }
}

?>
