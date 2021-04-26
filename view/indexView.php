<!--<!doctype html>-->
<!doctype html>

<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Supervisor.uno</title>

    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="img/favicon.ico">

    <!-- ESTILOS MENU-->
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="./css/smartmenu_bs4.css" rel="stylesheet">

    <link href="./css/pace-theme-minimal.css" rel="stylesheet">
    <link href="./css/animate.css" rel="stylesheet">


    <link rel="stylesheet" href="./css/select/select.min.css">
    <link href="./css/estilos.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link href="./css/datatable/select.min.css" rel="stylesheet">

    <!---------------------------- APARTADO DE ARCHIVOS Y LIBRERIAS  JAVASCRIPT --------------------------------------->

    <!-- Font Awesome CSS -->
    <script src="https://kit.fontawesome.com/3778e1f4b1.js"></script>
    <!--    <script src="https://use.fontawesome.com/07bcf2a23d.js"></script>-->


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="./vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

    <script src="js/pace.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>


    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="https://vadikom.github.io/smartmenus/src/jquery.smartmenus.js"></script>

    <!-- SmartMenus jQuery Bootstrap Addon -->
    <script src="./js/jquery.smartmenus.bootstrap-4.js"></script>

    <!------------------------ VENTANA DE PROYECTOS ------------------->
    <?php if (!isset($_SESSION[ID_PROYECTO_SUPERVISOR]) && isset($_SESSION[AUTENTICADO_SUPERVISOR])) { ?>
        <script>
            $(document).ready(function () {
                $('#modalInicial').modal('show');

                $("#myButton").click(function () {
                    alert("x");
                    $('#loadFile').show();
                });

                $('#modalInicial').on('shown.bs.modal', function (e) {
                    $('body').addClass("blur");
                }).on('hide.bs.modal', function (e) {
                    $('body').removeClass("blur");
                });
            });

        </script>
    <?php } ?>


    <script>
        function MostrarDatosProyectos(idProyecto) {
            //console.log(idProyecto);
            $.ajax({
                url: "./index.php?controller=Principal&action=getDatosProyectos",
                type: 'POST',
                success: function (response) {
                    var proyectos = $.parseJSON(response);
                    //console.log(proyectos);

                    var $secondChoice = $("#id_ProyectoM");
                    $secondChoice.empty();
                    x = 0;
                    $.each(proyectos, function () {
                        if (idProyecto == proyectos[x]['id_Proyecto'])
                            $secondChoice.append("<option value='" + proyectos[x]['id_Proyecto'] + "' name='" + proyectos[x]['nombre_Proyecto'] + "' selected>" + proyectos[x]['nombre_Proyecto'] + "</option>");
                        else
                            $secondChoice.append("<option value='" + proyectos[x]['id_Proyecto'] + "' name='" + proyectos[x]['nombre_Proyecto'] + "'>" + proyectos[x]['nombre_Proyecto'] + "</option>");

                        x++;
                    });
                }
            });
        }

        function obtenerUsuariosLLenadosReportes() {
            $.ajax({
                url: "./index.php?controller=Graficas&action=getAllUserLlenadosReportes",
                type: 'POST',
                success: function (response) {
                    let usuarios = $.parseJSON(response);
                    console.log(usuarios);

                    var $secondChoice = $("#id_UsuarioReportes");
                    $secondChoice.empty();

                    if (usuarios.length == 0) {
                        $secondChoice.append("<option value='0'> Ningun usuario ha realizado reportes </option>");
                        $("#btnBuscarMapaReportes").attr("disabled", true);

                    } else {
                        x = 0;
                        $.each(usuarios, function () {
                            $secondChoice.append("<option value='" + usuarios[x]['id_Usuario'] + "'>" + usuarios[x]['nombre_Usuario'] + " " + usuarios[x]['apellido_Usuario'] + "</option>");
                            x++;
                        });
                    }
                }
            });
        }

        function cargaFechafin() {
            if (document.getElementById("fechafinMapa").value == "") {
                fecha = document.getElementById("fechainicioMapa").value;
                document.getElementById("fechafinMapa").value = fecha
            }
        }

    </script>

</head>

<!-- ****************************************** MODAL INICAL DE PROYECTOS ****************************************** -->
<div class="modal fade" id="modalInicial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-2 bg-gradient-secondary text-white">
                <h5 class="modal-title w-100 text-center font-weight-bold" id="exampleModalLabel">
                    Selecciona Proyecto
                </h5>
            </div>
            <form action="index.php?controller=SeguimientosReporte&action=index&tipo=0,1&logout=true" method="post"
                  name="elegir_proyecto">
                <div class="modal-body">
                    <select name="id_Proyecto" id="id_Proyecto" class="custom-select">
                        <?php foreach ($_SESSION[PROYECTOS_SUPERVISOR] as $proyecto) { ?>
                            <option name="<?php echo $proyecto->nombre_Proyecto; ?>" id="opcion"
                                    value="<?php echo $proyecto->id_Proyecto; ?>"><?php echo $proyecto->nombre_Proyecto; ?></option>
                        <?php } ?>
                    </select>
                    <input id="nombre_Proyecto_Actual" name="nombre_Proyecto_Actual" type="hidden">
                </div>
                <div class="modal-footer">
                    <button type="submit" value="submit" class="btn btn-w-m btn-danger btn-block"
                            id="select_proyecto" name="select_proyecto">
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- *************************************** MODAL PARA CAMBIAR DE PROYECTOS *************************************** -->
<div class="modal fade" id="myModalProyectos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-2 bg-gradient-secondary text-white">
                <h5 class="modal-title w-100 text-center font-weight-bold" id="exampleModalLabel">
                    Selecciona Proyecto
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="index.php?controller=SeguimientosReporte&action=index&tipo=0,1" method="post"
                      name="elegir_proyecto" class="form-horizontal">

                    <div class="form-group">
                        <select name="id_Proyecto" id="id_ProyectoM" class="custom-select"> </select>
                    </div>

                    <input id="nombre_Proyecto_ActualModal" name="nombre_Proyecto_Actual" type="hidden"/>
                    <?php $url = explode("/", $_SERVER["REQUEST_URI"]); ?>
                    <input type="hidden" class="form-control" name="pageUrl" id="pageUrl" value="<?= $url[3] ?>">

                    <div class="form-group">
                        <button type="submit" value="submit" class="btn btn-w-m btn-danger btn-block"
                                id="select_proyectoModal" name="select_proyecto">
                            Seleccionar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--************* MODAL PARA FILTRAR DATOS DE REPORTES POR USUARIO, FECHAS EN MAPA DE REPORTES *********************-->
<div class="modal fade" id="myModalMapaReportes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="text-align: center"> Seleccionar </h4>
                <br>
                <form action="index.php?controller=Graficas&action=mapaReportes" method="post"
                      name="elegir_proyecto" class="form-horizontal">

                    <label class="control-label">Usuario</label>
                    <select name="id_UsuarioReportes" id="id_UsuarioReportes" class="form-control">

                    </select>

                    <br>
                    <div id="divfechainicioMapa">
                        <?php $fechainicio = date("Y-m-d"); ?>
                        <label class="control-label">Fecha incio</label>
                        <input id="fechainicioMapa" type="date" name="fechainicio" class="form-control"
                               onchange="cargaFechafin()">
                    </div>

                    <br>
                    <div id="divfechafinMapa">
                        <?php $fechafin = date("Y-m-d"); ?>
                        <label class="control-label">Fecha fin</label>
                        <input id="fechafinMapa" type="date" name="fechafin" class="form-control">
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" value="submit" class="btn btn-w-m btn-danger btn-block"
                                    id="btnBuscarMapaReportes">
                                Buscar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- <body>-->
<?php

$id_Proyecto = $_SESSION[ID_PROYECTO_SUPERVISOR];
if (empty($id_Proyecto))
    $id_Proyecto = 1;

$datosDocBim = $this->getDatosCodigosLlenadosDocBim($id_Proyecto);

$existeGantt = $this->existeGantt($id_Proyecto);

//print_r($datosDocBim);
//echo $datosDocBim[0][0]->id_Reporte;

/* ----------------------------------------- PERMISOS DE USUARIOS ------------------------------------------------ */
$permisos = $_SESSION[PERMISOS_MENU];

if (is_array($permisos) || is_object($permisos)) {
    foreach ($permisos as $permiso) {
        $binario = $binario . $permiso->consultar_Permiso;
    }
}
$binario = strrev($binario);
$decimal = bindec($binario);

function getAccess($bit1, $bit2)
{
    return (((int)$bit1 & (int)$bit2) == 0) ? false : true;
}

$dominio = $_SERVER["HTTP_HOST"];
$url = $_SERVER["REQUEST_URI"];

/* ------------------------ OBTIENES VARIABLES DE URL DE CONTROLADOR Y ACCION ------------------------------------ */
if (empty($_GET['controller'])) {
    $controller_vistas = "";
} else {
    $controller_vistas = $_GET['controller'];
}
if (empty($_GET['action'])) {
    $action = "";
} else {
    $action = $_GET['action'];
}
/* ------------------------------ VALIDA QUE EL USUARIO NO ESTE LOGUEADO ----------------------------------------- */
?>
<body>

<!-- MENU NUEVO-->
<!-- START NAVBAR -->

<?php
include 'menu.php';
?>

<?php
/* ----------------------------------------------------- PRINCIPAL ---------------------------------------------- */
$_SESSION[TIME_SUPERVISOR] = time();
/* PRINCIPAL - INICIO */
if ($controller_vistas == "Principal") {
    include 'Principal.php';

}
/* PRINCIPAL - GRAFICAS */

if ($controller_vistas == "Graficas") {
    include 'Graficas.php';
}


if ($controller_vistas == "Elementos") {
    include 'Elementos.php';
}

if ($controller_vistas == "Plantilla") {
    include 'Plantilla.php';
}

if ($controller_vistas == "Asistencia") {
    include 'Asistencia.php';
}

/* ----------------------------------------------------- USUARIOS ---------------------------------------------- */
/* USUARIOS */
//if(($controller_vistas == "Usuarios")&&($_SESSION['Usuarios']>=1000)){
if ($controller_vistas == "Usuarios") {
    include 'Usuarios.php';
}

/* PARTICIPANTES */
if ($controller_vistas == "Participantes") {
    include 'Participantes.php';
}

/* EMPLEADOS */
if ($controller_vistas == "Empleados") {
    include 'Empleados.php';
}
/* USUARIOS */
if ($controller_vistas == "Empresas") {
    include 'Empresas.php';
}
/* PROYECTOS */
if ($controller_vistas == "Proyectos") {
    include 'Proyectos.php';
}
/* PUESTOS */
if ($controller_vistas == "Perfiles") {
    include 'Perfiles.php';
}
/* AREAS */
if ($controller_vistas == "Areas") {
    include 'Areas.php';
}


/* PERMISOS */
if ($controller_vistas == "Permisos") {
    include 'Permisos.php';
}
/* RECURSOS */
if ($controller_vistas == "Recursos") {
    include 'Recursos.php';
}
/* MATRIZ */
if ($controller_vistas == "MatrizComunicacion") {
    include 'MatrizComunicacion.php';
}
/* ESTATUS */
if ($controller_vistas == "Cat_Status") {
    include 'Cat_Status.php';
}
/* MODULOS */
if ($controller_vistas == "Cat_Modulos") {
    include 'Cat_Modulos.php';
}
/* TAREAS */
if ($controller_vistas == "Tareas_Proyecto") {
    include 'Tareas_Proyecto.php';
}
/* PARTICIPANTES */
if ($controller_vistas == "Participantes_Proyecto") {
    include 'Participantes_Proyecto.php';
}
/* ROL */
if ($controller_vistas == "Rol_Participantes") {
    include 'Rol_Participantes.php';
}
/* DISPOSITIVOS */
if ($controller_vistas == "Cat_Dispositivos") {
    include 'Cat_Dispositivos.php';
}

/* FOTOGRAFIAS */
if ($controller_vistas == "Fotografias") {
    include 'Fotografias.php';
}

/* BITACORA */
if ($controller_vistas == "Bitacoras") {
    include 'Bitacora.php';
}

/* DOCUMENTOS */
if ($controller_vistas == "Documentos") {
    include 'Documento.php';
}

/* ----------------------------------------------------- REPORTES --------------------------------------------------- */
/* REPORTES */
if ($controller_vistas == "Reportes") {
    include 'Reportes.php';
}
/* CAMPOS */
if ($controller_vistas == "Campos") {
    include 'Campos.php';
}

/* CAMPOS */
if ($controller_vistas == "Gantt") {
    include 'Gantt.php';
}

/********** PROCESOS *******************************/
if ($controller_vistas == "Procesos") {
    include 'Procesos.php';
}

if ($controller_vistas == "EstructuraProcesos") {
    include 'EstructuraProcesos.php';
}


/* CAMPOS REPORTES */
if ($controller_vistas == "CamposReporte") {
    include 'CamposReportes.php';
}
/* LENADO REPORTES */
if ($controller_vistas == "LlenadosReporte") {
    include 'LlenadosReportes.php';
}
/* REPORTES LLENADOS */
if ($controller_vistas == "ReportesLlenados") {
    include 'ReportesLlenados.php';
}
/* SEGUIMIENTO REPORTES */
if ($controller_vistas == "SeguimientosReporte") {
    include 'SeguimientosReportes.php';
}


/* ------------------------------------- PROYECTOS -----------------------------*/
/* PROYECTOS - INICIO */
if ($controller_vistas == "UsuariosProyectos") {
    include 'UsuariosProyectos.php';
}

/* ------------------------------ EMPRESAS PROYECTOS ---------------------------*/
/* EMPRESAS PROYECTOS - INICIO */
if ($controller_vistas == "Empresas_Proyectos") {
    include 'EmpresasProyectos.php';
}


if ($controller_vistas == "Estadisticas") {
    include 'Estadisticas.php';
}

/* ------------------------------ MAPAS ---------------------------*/
/* MAPAS */
if ($controller_vistas == "Mapas") {
    include 'Mapas.php';
}

?>

<script>

    $(document).ready(function () {
        $("#myBtn").click(function () {
            $("#myModalProyecto").modal("toggle");
        });
    });

    $(document).ready(function () {
        $("#myBtn2").click(function () {
            $("#myModalProyecto").modal("toggle");
        });
    });

</script>

<script>
    $(document).ready(function () {

        $("#id_Proyecto").change(function () {
            nombre = $('#id_Proyecto option:selected').text();
            $('#nombre_Proyecto_Actual').val(nombre);
            //alert(nombre);
        });

        consulta1();

        $('.modal-viejo').modal('show');

        /*$('#myModalPwdUser').on('hidden.bs.modal', () => {
            location.href = 'index.php?controller=Usuarios&action=index';
        });*/
    });
</script>


<script>

    function consulta1() {
        $.ajax({
            url: "./index.php?controller=Notificaciones&action=obtenerNotificaciones",
            type: 'POST',
            success: function (response) {
                var notificaciones = $.parseJSON(response);
                //console.log(notificaciones);
                var contenido1 = document.getElementById('contenido1');
                var numero1 = document.getElementById('numero1');
                var contadorNotificaciones = 0;
                var notificacionesActivas = 0;
                var notificacionesInactivas = 0;
                $('#contenido1').children().remove();

                for (var n = 0; n < notificaciones.length; n++) {
                    if (notificaciones[n].id_status == 1)
                        notificacionesActivas++;
                    else
                        notificacionesInactivas++;
                }

                if (notificaciones.length == 0) {
                    //$('#numero1').hide();
                    $('#contenido1').append("<li><a class='dropdown-item' href='#' style='font-size: 16px;'> <i class='fa fa-ban'> </i> No hay Notificaciones </a></li>");
                } else {
                    if (notificacionesActivas >= 10) {

                        if (notificacionesActivas > 20) {

                            for (var a = 0; a < 20; a++) {
                                //console.log('id_Status: ' + notificaciones[i].id_status);
                                if (notificaciones[a].id_status == 1) {
                                    contenido1.innerHTML += "<li style='background-color: #80808078'> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;' " +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[a].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[a].id_Reporte
                                        + "&id_notificacion=" + notificaciones[a].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[a].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[a].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[a].nombre_usuario_notifico + " " + notificaciones[a].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[a].Descripcion + " " + "<b>" + notificaciones[a].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[a].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[a].nombre_Proyecto +
                                        "</a> </li>";
                                    contadorNotificaciones++;
                                    numero1.innerHTML = contadorNotificaciones + '+';
                                    //$('#numero1').show();
                                    $('#numero1').removeClass('d-none');
                                }
                            }

                        } else {

                            for (var a = 0; a < notificaciones.length; a++) {
                                //console.log('id_Status: ' + notificaciones[i].id_status);
                                if (notificaciones[a].id_status == 1) {
                                    contenido1.innerHTML += "<li style='background-color: #80808078'> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;' " +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[a].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[a].id_Reporte
                                        + "&id_notificacion=" + notificaciones[a].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[a].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[a].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[a].nombre_usuario_notifico + " " + notificaciones[a].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[a].Descripcion + " " + "<b>" + notificaciones[a].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[a].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[a].nombre_Proyecto +
                                        "</a> </li>";
                                    contadorNotificaciones++;
                                    numero1.innerHTML = contadorNotificaciones;
                                    //$('#numero1').show();
                                    $('#numero1').removeClass('d-none');
                                }
                            }
                        }

                    } else {

                        if (notificacionesActivas != 0) {
                            $('#numero1').removeClass('d-none');
                            //console.log('i < notificacionesActivas y b = notificacionesActivas <  notificaciones.length');
                            for (var i = 0; i < notificacionesActivas; i++) {
                                contenido1.innerHTML += "<li style='background-color: #80808078'> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;'" +
                                    "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                    + notificaciones[i].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[i].id_Reporte
                                    + "&id_notificacion=" + notificaciones[i].id_notificacion
                                    + "&id_Proyecto=" + notificaciones[i].id_Proyecto + "'>" +
                                    "<i class='" + notificaciones[i].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                    "<b>" + notificaciones[i].nombre_usuario_notifico + " " + notificaciones[i].apellido_usuario_notifico + "</b>" +
                                    "<br>" + " " + notificaciones[i].Descripcion + " " + "<b>" + notificaciones[i].titulo_Reporte + "</b>" +
                                    "<br>" + notificaciones[i].Fecha +
                                    "<br> <b> Proyecto: </b> " + notificaciones[i].nombre_Proyecto +
                                    "</a> </li>";
                                contadorNotificaciones++;
                                numero1.innerHTML = contadorNotificaciones;
                                //$('#numero1').show();
                            }

                            if (notificaciones.length > 10) {
                                //console.log('b > 10');
                                for (var b = notificacionesActivas; b < 10; b++) {
                                    contenido1.innerHTML += "<li> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;'" +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[b].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[b].id_Reporte
                                        + "&id_notificacion=" + notificaciones[b].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[b].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[b].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[b].nombre_usuario_notifico + " " + notificaciones[b].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[b].Descripcion + " " + "<b>" + notificaciones[b].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[b].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[b].nombre_Proyecto +
                                        "</a> </li>";
                                }
                            } else {
                                for (var b = notificacionesActivas; b < notificaciones.length; b++) {
                                    contenido1.innerHTML += "<li> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;'" +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[b].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[b].id_Reporte
                                        + "&id_notificacion=" + notificaciones[b].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[b].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[b].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[b].nombre_usuario_notifico + " " + notificaciones[b].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[b].Descripcion + " " + "<b>" + notificaciones[b].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[b].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[b].nombre_Proyecto +
                                        "</a> </li>";
                                }
                            }

                        } else {
                            $('#numero1').addClass('d-none');
                            //console.log('Todas Inactivas: ' + notificacionesInactivas);
                            if (notificacionesInactivas > 10) {
                                for (var b = 0; b < 10; b++) {
                                    contenido1.innerHTML += "<li> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;'" +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[b].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[b].id_Reporte
                                        + "&id_notificacion=" + notificaciones[b].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[b].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[b].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[b].nombre_usuario_notifico + " " + notificaciones[b].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[b].Descripcion + " " + "<b>" + notificaciones[b].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[b].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[b].nombre_Proyecto +
                                        "</a> </li>";
                                }
                            } else {
                                for (var b = 0; b < notificacionesInactivas; b++) {
                                    contenido1.innerHTML += "<li> <a class='dropdown-item' style='border-bottom: 1px solid; padding-bottom: 1em; padding-top: 1em;'" +
                                        "href='index.php?controller=ReportesLlenados&action=verreportellenado&id_Gpo_Valores_Reporte="
                                        + notificaciones[b].id_Gpo_Valores_Notificacion + "&Id_Reporte=" + notificaciones[b].id_Reporte
                                        + "&id_notificacion=" + notificaciones[b].id_notificacion
                                        + "&id_Proyecto=" + notificaciones[b].id_Proyecto + "'>" +
                                        "<i class='" + notificaciones[b].classimg + "' style='font-size: 22px;'>  </i>" + "  " +
                                        "<b>" + notificaciones[b].nombre_usuario_notifico + " " + notificaciones[b].apellido_usuario_notifico + "</b>" +
                                        "<br>" + " " + notificaciones[b].Descripcion + " " + "<b>" + notificaciones[b].titulo_Reporte + "</b>" +
                                        "<br>" + notificaciones[b].Fecha +
                                        "<br> <b> Proyecto: </b> " + notificaciones[b].nombre_Proyecto +
                                        "</a> </li>";
                                }
                            }
                        }
                    }
                    //contenido1.innerHTML += "<li class='text-center'> <a> Ver todas las Notificaciones </a> </li>";
                }
            }
        });
    }

    //60000
    setInterval(consulta1, 60000);

</script>


<script>
    function send(elem) {
        var currentId = $(elem).attr("id");
        switch (currentId) {
            case 'nuevoReporte':
                if ($("#controller").val() != '') {
                    var action = "index.php?controller=" + $("#controller").val() + "&action=" + $("#action").val();

                    if ($("#operacion").val() != 'undefined') {
                        action = action + "&operacion=" + $("#operacion").val();
                    }
                    if ($("#plaza").val() != 'undefined') {
                        action = action + "&plaza=" + $("#plaza").val();
                    }
                    if ($("select[name='year']").val() != 'undefined') {
                        action = action + "&year=" + $("select[name='year']").val();
                    }
                    if ($("input[name='mes']").val() != 'undefined') {
                        action = action + "&mes=" + $("input[name='mes']").val();
                    }
                    if ($("input[name='fechainicio']").val() != 'undefined') {
                        action = action + "&fechainicio=" + $("input[name='fechainicio']").val();
                    }
                    if ($("input[name='fechafin']").val() != 'undefined') {
                        action = action + "&fechafin=" + $("input[name='fechafin']").val();
                    }
                    document.getElementById("formdescargables").action = action;
                }

                break;
            case 'btnBuscar':
                document.getElementById("formBusqueda").action = "index.php?controller=SeguimientosReporte&action=busqueda&id_Reporte=" + $("#id_Reporte").val() + "&fecha_Inicio=" + $("#fechainicio").val() + "&fecha_Final=" + $("#fechafinal").val() + "&palabras_clave=" + $("#palabras_clave").val() + "&identificador_reporte=" + $("#identificador_reporte").val();
                break;
            case 'btnLlenadosReporte':
                document.getElementById("formLlenadosReporte").action = "index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=" + $("#Id_Reporte").val();
                break;
        }
    }

    function cerrar(elem) {
        document.getElementById("divmes").style.display = "none";
        document.getElementById("sincampo").style.display = "none";
        document.getElementById("divfechayear").style.display = "none";
        document.getElementById("divfechainicio").style.display = "none";
        document.getElementById("divfechafin").style.display = "none";
        document.getElementById("divplaza").style.display = "none";
        document.getElementById("divoperacion").style.display = "none";
    }
</script>

<!--******************************************** CSS de alrtyfy *****************************************************-->
<link rel="stylesheet" href="./css/alertify/alertify.min.css">
<!--   <link rel="stylesheet" href="./css/alertify/default.min.css">
   <link rel="stylesheet" href="./css/alertify/semantic.min.css">-->
<link rel="stylesheet" href="./css/alertify/bootstrap.min.css">
<!--******************************************** CSS de alrtyfy *****************************************************-->

<!--***************************************** JS de alrtyfy *********************************************************-->
<script src="./js/alertify.min.js"></script>
<script type="text/javascript">
    //override defaults
    alertify.defaults.transition = "slide";
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.defaults.theme.input = "form-control";
</script>
<!--***************************************** JS de alrtyfy *********************************************************-->

<script src="./js/select2.min.js"></script>
</body>
</html>

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     style="margin-top:0%;" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onClick="cerrar(this)"><span aria-hidden="true">x</span></button>
                <h4 class="modal-title" id="myModalLabel">Seleccione campos</h4>
                <form action="" method="post" class="form-horizontal" id="formdescargables">

                    <div id="divoperacion" style="display:none;">
                        <label class="control-label">Seleccione operación</label>
                        <select id="operacion" name="operacion" class="form-control"/>
                        <option name="AFORO" id="1" value="1">AFORO</option>
                        <option name="INGRESO" id="2" value="2">INGRESO</option>
                        </select>
                    </div>

                    <div id="divplaza" style="display:none;">
                        <label class="control-label">Seleccione plaza</label>
                        <select id="plaza" name="plaza" class="form-control"/>
                        <option name="TODAS" id="id_Plaza" value="id_Plaza">TODAS</option>
                        <option name="ATLACOMULCO" id="1" value="1">ATLACOMULCO</option>
                        <option name="CONTEPEC" id="2" value="2">CONTEPEC</option>
                        <?php /*
                        $x = 1;
                        foreach ($allplazas as $plaza) {
                            ?>
                            <option name="<?php echo $plaza->titulo_Reporte; ?>"
                                    id="<?php echo $plaza->id_Gpo_Valores_Reporte; ?>"
                                    value="<?php echo $x; ?>"><?php echo $plaza->titulo_Reporte; ?></option>
                            <?php
                            $x++;
                        } */ ?>
                        </select>
                    </div>

                    <div id="divfechayear" style="display:none;">
                        <label class="control-label">Año</label>
                        <select name="year" class="form-control">
                            <?php
                            $year = date("Y");
                            for ($i = $year; $i >= 2012; $i--) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div id="divmes" style="display:none;">
                        <?php $month = date("Y-m"); ?>
                        <label class="control-label">Seleccione mes</label>
                        <input id="mes" type="month" value="<?php echo $month; ?>" name="mes"
                               class="form-control">
                    </div>

                    <div id="divfechainicio" style="display:none;">
                        <?php $fechainicio = date("Y-m-d"); ?>
                        <label class="control-label">Fecha incio</label>
                        <input id="fechainicio" type="date" name="fechainicio"
                               value="<?php echo $fechainicio; ?>" class="form-control">
                    </div>

                    <div id="divfechafin" style="display:none;">
                        <?php $fechafin = date("Y-m-d"); ?>
                        <label class="control-label">Fecha fin</label>
                        <input id="fechafin" type="date" name="fechafin" value="<?php echo $fechafin; ?>"
                               class="form-control">
                    </div>

                    <div id="sincampo" style="display:none;">
                        <label class="control-label">Este reporte no requiere campos.</label>
                        <br/>
                    </div>
                    <input type="hidden" value="" id="controller" name="controller">
                    <input type="hidden" value="" id="action" name="action">
                    <br/>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" value="nuevo reporte" class="btn btn-w-m btn-danger" id="nuevoReporte"
                                    onclick="send(this)" align="center">
                                Generar reporte
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.oncontextmenu = () => {
        return false;
    }

    function popover(idModal) {
        $('#' + idModal).modal('show');
        $('[data-toggle=popover]').popover('hide');
    }

    /*$('#my-btn').click(function () {
        $('#addChangeModal').modal('show');
        $('[data-toggle=popover]').popover('hide');
    });*/

    $("[data-toggle=popover]").popover();
</script>
