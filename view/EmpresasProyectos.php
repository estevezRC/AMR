<script src="js/tabla.js"></script>
<?php
/*--- ACCION INDEX: MUESTRA TODOS LOS PROYECTOS ---*/
    /*--- ACCION CREAR---*/
    if ($modificar == NULL){
        ?>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Relación empresa proyecto</h4>
                        <form action="<?php echo $helper->url("Empresas_Proyectos", "guardarnuevo"); ?>" method="post"  class="form-horizontal">
                            <label class="control-label">Empresa:</label><br>
                            <select name="id_Empresa" class="form-control"/>
                            <?php
                            foreach($allempresas as $empresa) { ?>
                                <option name ="<?php echo $empresa->nombre_Empresa; ?>" id ="<?php echo $empresa->id_Empresa; ?>"
                                        value ="<?php echo $empresa->id_Empresa; ?>"><?php echo $empresa->nombre_Empresa; ?></option>
                            <?php }?>
                            </select>
                            <label class="control-label">Proyecto:</label><br>
                            <select name="id_Proyecto" class="form-control"/>
                            <?php
                            foreach($allproyectos as $proyecto) { ?>
                                <option name ="<?php echo $proyecto->nombre_Proyecto; ?>" id ="<?php echo $proyecto->id_Proyecto; ?>"
                                        value ="<?php echo $proyecto->id_Proyecto; ?>"><?php echo $proyecto->nombre_Proyecto; ?></option>
                            <?php }?>
                            </select>
                            <br>
                            <button type="submit" value="nuevo proyecto" class="btn btn-w-m btn-danger"/>Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    /*--- ACCION MODIFICAR---*/
    if ($modificar == 1){
        //var_dump($allareas);
        ?>
        <div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" onclick="location.href='index.php?controller=Empresas_Proyectos&action=index';" data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modificar relación empresa proyecto</h4>
                        <form action="<?php echo $helper->url("Empresas_Proyectos", "guardarmodificacion");?>" method="post" class="form-horizontal">
                            <input type="hidden" name="id_Empresas_Proyectos" value = "<?php echo $datosempresaproyecto->id_Empresas_Proyectos; ?>" />
                            <label class="control-label">Empresa:</label><br>
                            <select name="id_Empresa" class="form-control"/>
                            <option value = "<?php echo $datosempresaproyecto->id_Empresa; ?>"><?php echo $datosempresaproyecto->nombre_Empresa; ?></option>
                            <?php
                            foreach($allempresas as $empresa) {
                                if($empresa->id_Empresa!=$datosempresaproyecto->id_Empresa){
                                ?>
                                <option name ="<?php echo $empresa->nombre_Empresa; ?>" id ="<?php echo $empresa->id_Empresa; ?>"
                                        value ="<?php echo $empresa->id_Empresa; ?>"><?php echo $empresa->nombre_Empresa; ?></option>
                            <?php }}?>
                            </select>
                            <label class="control-label">Proyecto:</label><br>
                            <select name="id_Proyecto" class="form-control"/>
                            <option value = "<?php echo $datosempresaproyecto->id_Proyecto; ?>"><?php echo $datosempresaproyecto->nombre_Proyecto; ?></option>
                            <?php
                            foreach($allproyectos as $proyecto) {
                                if($proyecto->id_Proyecto!=$datosempresaproyecto->id_Proyecto){
                                ?>
                                <option name ="<?php echo $proyecto->nombre_Proyecto; ?>" id ="<?php echo $proyecto->id_Proyecto; ?>"
                                        value ="<?php echo $proyecto->id_Proyecto; ?>"><?php echo $proyecto->nombre_Proyecto; ?></option>
                            <?php }}?>
                            </select>
                            <br>
                            <button type="submit" value="Guardar" class="btn btn-w-m btn-danger"/>Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    ?>
    <div class="col-lg-12">
    <div class="ibox float-e-margins">
        <?php if(($action=="index")||($action=="modificar")){ ?>
    <div class="col-sm-12 ibox-title">
        <div class="col-sm-10">
            <h3>
                <div class="col-sm-11">
                    <?php echo $mensaje;?>
                </div>
                <div class="col-sm-1" style="text-align:right;">
                        <a href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                </div>
            </h3></div>


    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <table id="example" class="display" style="font-size:12px;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Empresa</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //var_dump($allproyectos);
                    if (is_array($allempresasproyectos) || is_object($allempresasproyectos))
                    {

                        foreach($allempresasproyectos as $empresaproyecto) {
                            ?>
                            <tr>
                            <td><?php echo $empresaproyecto->id_Empresas_Proyectos; ?></td>
                            <td><?php echo $empresaproyecto->nombre_Proyecto; ?></td>
                            <td><?php echo $empresaproyecto->nombre_Empresa; ?></td>
                            <td>
     <a href="index.php?controller=Empresas_Proyectos&action=modificar&id_Empresas_Proyectos=<?php echo $empresaproyecto->id_Empresas_Proyectos; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
     <a href="index.php?controller=Empresas_Proyectos&action=borrar&id_Empresas_Proyectos=<?php echo $empresaproyecto->id_Empresas_Proyectos; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        <?php } ?>
                        </tr>

                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>