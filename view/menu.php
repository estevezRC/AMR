<nav class="navbar navbar-expand-xl navbar-light bg-gradient-light fixed-top p-2">
    <?php if (isset($_SESSION[ID_PROYECTO_SUPERVISOR])) { ?>
        <a class="navbar-brand" href="index.php?controller=Graficas&action=index">
            <img src="./img/SUP_Digital_Web_Desktop-02.png" height="35px"
                 style="display: inline; margin-top: -5px;" alt="LogoSupervisor">
        </a>
    <?php } ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupervisor"
            aria-controls="navbarSupervisor" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse font-weight-bold" id="navbarSupervisor">
        <ul class="navbar-nav mr-auto" id="ulNavbarDer">
            <!-- Left nav Menu AAS -->
            <?php if (isset($_SESSION[ID_PROYECTO_SUPERVISOR])) {
                if (getAccess(1, $decimal)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class=" fa fa-paste"></i>
                            Registros
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=SeguimientosReporte&action=index&tipo=0,1">
                                    <i class="fa fa-list"></i> Reportes
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=SeguimientosReporte&action=index&tipo=reportesIncidencia">
                                    <i class="fa fa-exclamation-triangle"></i> Incidentes
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=SeguimientosReporte&action=index&tipo=2">
                                    <i class="fa fa-map-marker"></i> Ubicaciones
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=SeguimientosReporte&action=index&tipo=3">
                                    <i class="fa fa-list-alt"></i> Inventario
                                </a>
                            </li>

                            <!--<li class="dropdown">
                                <?php /*if (empty($datosDocBim[0]) && empty($datosDocBim[1])) { */?>
                                    <a href="#" id="newElementoDocBim" class="dropdown-item dropdown-toggle">
                                        <i class="fa fa-file-o"> </i> BIM <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" id="codigosDocBimMenu1">
                                        <li><a href='#' class='dropdown-item'> Sin Códigos </a></li>
                                    </ul>
                                <?php /*} elseif (empty($datosDocBim[0])) { */?>
                                    <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?php /*echo $datosDocBim[1]; */?>&tipo_Reporte=5"
                                       id="newElementoDocBim" class="dropdown-item dropdown-toggle">
                                        <i class="fa fa-file-o"> </i> BIM <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" id="codigosDocBimMenu1">
                                        <li><a href='#' class='dropdown-item'> Sin Códigos </a></li>
                                    </ul>

                                <?php /*} else { */?>

                                    <a href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?php /*echo $datosDocBim[1]; */?>&tipo_Reporte=5"
                                       id="newElementoDocBim" class="dropdown-item dropdown-toggle">
                                        <i class="fa fa-file-o"> </i> BIM <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <?php /*foreach ($datosDocBim[0] as $x => $datos) { */?>
                                            <li class="dropdown">
                                                <a target='_blank' class='dropdown-item dropdown-toggle'
                                                   style='overflow-wrap: break-word;'
                                                   href="index.php?controller=ReportesLlenados&action=visualizarPlanos&id_Reporte=<?php /*echo $datosDocBim[0][$x]->id_Reporte; */?>&titulo_Reporte=<?php /*echo $datosDocBim[0][$x]->titulo_Reporte; */?>">
                                                    <?php /*echo $datosDocBim[0][$x]->titulo_Reporte */?>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="index.php?controller=LlenadosReporte&action=mostrarreportenuevo&Id_Reporte=<?php /*echo $datosDocBim[0][$x]->id_Reporte; */?>&tipo_Reporte=5&codigo=<?php /*echo $datosDocBim[0][0]->titulo_Reporte; */?>">
                                                            Nuevo </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="index.php?controller=SeguimientosReporte&action=index&tipo=5&codigo=<?php /*echo $datosDocBim[0][$x]->titulo_Reporte; */?>">
                                                            Historial </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <?php /*} */?>
                                    </ul>
                                <?php /*} */?>
                            </li>-->

                            <!--<li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-file-image-o" aria-hidden="true"></i> Gestor documental
                                </a>
                            </li>-->

                        </ul>
                    </li>
                <?php }
            } ?>

            <!------------------------------------------------ MENU HERRAMIENTAS ------------------------------------------------->
            <?php if (isset($_SESSION[ID_PROYECTO_SUPERVISOR])) {
                if (getAccess(1, $decimal)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fa fa-wrench"></i> Herramientas
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="index.php?controller=Principal&action=busqueda">
                                    <i class="fa fa-search"></i>
                                    Búsqueda
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Graficas&action=index">
                                    <i class="fa fa-bar-chart"></i>
                                    Dashboard
                                </a>
                            </li>

                            <!-- <li>
                                <?php
                            /*switch ($_SESSION[ID_PROYECTO_SUPERVISOR]) {
                                     case 0:
                                         $id_Gpo_Valores = 1;
                                         break;
                                 }
                                 */ ?>

                                <a class="dropdown-item"
                                   href="index.php?controller=Graficas&action=avances&id_Gpo_Valores=<?php /*echo $id_Gpo_Valores */ ?>">
                                    <i class="fas fa-chart-pie"></i>
                                    Avances
                                </a>
                            </li>-->

                            <?php /*if (!$existeGantt) { */ ?><!--
                                <li>
                                    <a class="dropdown-item"
                                       href="index.php?controller=Graficas&action=diagrama&id_Gpo_Valores=<?php /*echo $id_Gpo_Valores */ ?>">
                                        <i class="fas fa-project-diagram"></i>
                                        Diagrama de Árbol
                                    </a>
                                </li>
                            <?php /*} else {
                                if ($_SESSION[ID_PERFIL_USER_SUPERVISOR] == 1) { */ ?>
                                    <li class="dropdown">
                                        <a class="dropdown-item dropdown-toggle"
                                           href="index.php?controller=Gantt&action=index">
                                            <i class="fas fa-chart-line"></i> Diagrama de Gantt </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="index.php?controller=Gantt&action=nuevo">
                                                    Nuevo
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?/* } else { */ ?>
                                    <li>
                                        <a class="dropdown-item" href="index.php?controller=Gantt&action=index">
                                            <i class="fas fa-chart-line"></i> Diagrama de Gantt </a>
                                        </a>
                                    </li>

                                --><?php /*}
                            } */ ?>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Graficas&action=mapa">
                                    <i class="fa fa-map"></i>
                                    Mapa de ubicaciones
                                </a>
                            </li>

                            <?php if (getAccess(1024, $decimal)) { ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=Graficas&action=mapaReportes">
                                        <!-- data-toggle="modal" data-target="#myModalMapaReportes" onclick="obtenerUsuariosLLenadosReportes()" -->
                                        <i class="fas fa-map-marked-alt"></i>
                                        Mapa de Reportes
                                    </a>
                                </li>
                            <?php } ?>

                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=SeguimientosReporte&action=index&tipo=papelera">
                                    <i class="fas fa-trash-restore-alt"></i> Papelera
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Bitacoras&action=index">
                                    <i class="fa fa-list-alt"></i> Bitacoras
                                </a>
                            </li>

                        </ul>
                    </li>
                <?php }
            } ?>

            <!------------------------------------------------ MENU CONFIGURACION ------------------------------------------------->
            <?php if (isset($_SESSION[ID_PROYECTO_SUPERVISOR])) {
                if (getAccess(2, $decimal)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fa fa-cog"></i> Configuración
                        </a>
                        <ul class="dropdown-menu">

                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle" href="#">
                                    <i class="fa fa-cogs"></i> Elementos <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="dropdown-item" href="index.php?controller=Campos&action=index">
                                            Campos
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="index.php?controller=Reportes&action=index">
                                            Plantillas
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="index.php?controller=Procesos&action=index">
                                            Procesos
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                           href="index.php?controller=EstructuraProcesos&action=index">
                                            Estructura Procesos
                                        </a>
                                    </li>

                                </ul>
                            </li>


                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle"
                                   href="index.php?controller=Usuarios&action=index">
                                    <i class="fa fa-users"></i> Usuarios <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="dropdown-item" href="index.php?controller=Perfiles&action=index">
                                            Perfiles
                                        </a>
                                    </li>

                                    <?php if (isset($_SESSION[ID_PROYECTO_SUPERVISOR])) {
                                        if (getAccess(128, $decimal)) { ?>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="index.php?controller=Recursos&action=index">
                                                    Recursos
                                                </a>
                                            </li>
                                        <?php }
                                    } ?>

                                </ul>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Participantes&action=index">
                                    <i class="fas fa-user-friends"></i> Participantes
                                </a>
                            </li>

                            <!--<li class="dropdown">
                                <a class="dropdown-item dropdown-toggle"
                                   href="index.php?controller=Empleados&action=index">
                                    <i class="fa fa-users-cog"></i> Empleados </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item"
                                           href="index.php?controller=Empleados&action=registro">
                                            Nuevo
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=Asistencia&action=index">
                                    <i class="fas fa-address-book"></i>
                                    Control de Personal
                                </a>
                            </li>-->

                            <li>
                                <a class="dropdown-item"
                                   href="index.php?controller=MatrizComunicacion&action=index">
                                    <i class="fa fa-volume-control-phone"></i>
                                    Matriz de Comunicación
                                </a>
                            </li>

                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle"
                                   href="index.php?controller=Proyectos&action=index">
                                    <i class="fa fa-briefcase"></i> Proyectos </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item"
                                           href="index.php?controller=UsuariosProyectos&action=index">
                                            Asignar Usuarios a Proyectos
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Areas&action=index">
                                    <i class="fa fa-sitemap"></i>
                                    Áreas
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="index.php?controller=Empresas&action=index">
                                    <i class="fa fa-building"></i>
                                    Empresas
                                </a>
                            </li>

                            <!--<li>
                                <a class="dropdown-item" href="index.php?controller=Cat_Dispositivos&action=index">
                                    <i class="fa fa-mobile"></i>
                                    Dispositivos
                                </a>
                            </li>-->

                        </ul>
                    </li>
                <?php }
            } ?>

        </ul>

        <!-- Right nav Nombre del Proyecto-->
        <ul class="nav navbar-nav navbar-right" id="navderecha">
            <li class="nav-item">
                <!--<a class=nav-link" href="#" id="myButton" onclick="MostrarDatosProyectos()">
                        <i class="fa fa-briefcase"></i>&nbsp;
                        <?php /*echo $_SESSION[NOMBRE_PROYECTO] */ ?>
                    </a>-->

                <a class="nav-link" href="#" data-toggle="modal" data-target="#myModalProyectos"
                   onclick="MostrarDatosProyectos(<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR] ?>)">
                    <i class="fa fa-briefcase"></i>&nbsp;
                    <?php echo $_SESSION[NOMBRE_PROYECTO] ?>
                </a>
            </li>

            <!------------------------------------------------ Notificaciones ------------------------------------------------->

            <li class="nav-item dropdown notificaciones" id="notificaciones">
                <a class="nav-link dropdown-toggle link-notificaciones" href="#">
                    <i class="fa fa-bell">
                        <span id="numero1" class="badge badge-danger badge-notificaciones d-none"></span>
                    </i></a>
                <ul class="dropdown-menu" id="contenido1">
                </ul>
            </li>

            <!---------------------------------------- MENU USUARIO --------------------------------------------->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#"><i class="fa fa-user-circle-o"></i>
                </a>
                <ul class="dropdown-menu">

                    <li>
                        <a class="dropdown-item" href="index.php?controller=Usuarios&action=perfil">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <?php echo utf8_decode($_SESSION[NOMBRE_USUARIO_SUPERVISOR] . " " . $_SESSION[APELLIDO_USUARIO_SUPERVISOR]) ?>
                        </a>
                    </li>

                    <li>
                        <!--index.php?controller=Reportes&action=ayuda-->
                        <a class="dropdown-item" href="index.php?controller=Reportes&action=ayuda">
                            <i class="fa fa-info-circle"></i> Ayuda </span> </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?php echo $helper->url("Usuarios", "salir") ?>">
                            <i class="fa fa-sign-out"></i> Salir </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
</nav><!--/.container -->
