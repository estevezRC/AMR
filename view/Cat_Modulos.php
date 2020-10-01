<script>
        $(document).ready(function() {
            $('#dataTables-example').dataTable({
                responsive: true,
                "dom": 'T<"clear">lfrtip',
				"iTotalDisplayRecords":25,						  
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "order": [[ 0, "desc" ]],
                "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ "_all" ] }
                ]
            });

            /* Init DataTables */
            var oTable = $('#editable').dataTable();

            /* Apply the jEditable handlers to the table */
            oTable.$('td').editable( 'http://webapplayers.com/example_ajax.php', {
                "callback": function( sValue, y ) {
                    var aPos = oTable.fnGetPosition( this );
                    oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                },
                "submitdata": function ( value, settings ) {
                    return {
                        "row_id": this.parentNode.getAttribute('id'),
                        "column": oTable.fnGetPosition( this )[2]
                    };
                },

                "width": "90%",
                "height": "100%"
            } );


        });

        function fnClickAddRow() {
            $('#editable').dataTable().fnAddData( [
                "Custom row",
                "New row",
                "New row",
                "New row",
                "New row" ] );

        }
</script>
<style>
    body.DTTT_Print {
        background: #fff;

    }
    .DTTT_Print #page-wrapper {
        margin: 0;
        background:#fff;
    }

    button.DTTT_button, div.DTTT_button, a.DTTT_button {
        border: 1px solid #e7eaec;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }
    button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
        border: 1px solid #d2d2d2;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }

    .dataTables_filter label {
        margin-right: 5px;
        font-weight: normal;
    }
    .dataTables_filter input {
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #cccccc;
        padding: 0 0px 0 0px;

    }
</style>
<?php
if(($action=="index")||($action=="modificar")){ 
if (empty($_GET['mensaje'])) { $mensaje="";} else { $mensaje=$_GET['mensaje'];}
?>
<?php
    /*--------------------------------------- ACCION INDEX: CREAR NUEVO MODULO ----------------------------------------*/
if ($modificar == NULL){?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Crear nuevo Módulo</h4>
                <form action="<?php echo $helper->url("Cat_Modulos", "guardarnuevo"); ?>" method="post" class="form-horizontal">
                <input type="hidden" name="id_Proyecto" value = "<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>" />
                <label class="control-label">Nombre:</label><input type="text" name="Nombre" class="form-control"/>
                <br>
                <button type="submit" value="nuevo modulo" class="btn btn-success"/>Nuevo Módulo</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    /*--------------------------------------- ACCION MODIFICAR: MODIFICAR MODULO --------------------------------------*/
}if ($modificar == 1){
    ?>
<div class="modal show" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" onclick="location.href='index.php?controller=Cat_Modulos&action=index';" data-dismiss="modal2" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modificar Módulo </h4>
                <form action="<?php echo $helper->url("Cat_Modulos", "guardarmodificacion");?>" method="post" class="form-horizontal">
                <input type="hidden" name="id_Proyecto" value = "<?php echo $_SESSION[ID_PROYECTO_SUPERVISOR]; ?>" />
                <input type="hidden" name="Id_Modulo" value = "<?php echo $datosmodulo->Id_Modulo; ?>" />
                <label class="control-label"> Nombre:</label><input type="text" name="Nombre" value = "<?php echo $datosmodulo->Nombre; ?>" class="form-control" />
                <br>
                <button type="submit" value="Guardar" class="btn btn-success"/>Guardar</button>
    </form>
    </div>
<?php
}
    /*-------------------------------------------------- TABLA MODULOS ------------------------------------------------*/
?>
<div class="col-lg-12">
  <div class="ibox float-e-margins">
      <div class="col-sm-12 ibox-title">
          <div class="col-sm-10">
              <h3>Módulos<br> <?php echo $mensaje;?></h3>
              <button type="button" class="btn btn-danger dim btn-large-dim pull-right" data-toggle="modal" data-target="#myModal">+ Nuevo Módulo</button>
          </div>
      </div>
      <div class="ibox-content">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <table class="table-striped" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
        //var_dump($allmodulos);
        if (is_array($allmodulos) || is_object($allmodulos))
        {
            foreach($allmodulos as $modulo) {
                ?>
                <tr>
                        <td><?php echo $modulo->Id_Modulo; ?></td>
                        <td><?php echo $modulo->Nombre; ?></td>
                        <td><a href="index.php?controller=Cat_Modulos&action=modificar&Id_Modulo=<?php echo $modulo->Id_Modulo; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;
                        <a href="index.php?controller=Cat_Modulos&action=borrar&Id_Modulo=<?php echo $modulo->Id_Modulo; ?>"><i class="fa fa-search" aria-hidden="true"></i></a><br/>
                        <!--<a href="index.php?controller=Campos&action=activar&id_Campo_Reporte=<?php echo $campo->id_Campo_Reporte; ?>">Activar</a>-->
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
    </div>
</div>
<?php }
    ?>
