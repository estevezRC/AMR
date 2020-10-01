<?php

require 'Consultas.php';

$obtenerReportes = Consultas::obtenerIdsTipoReporte(32);
//$obtenerValores = Consultas::obtenerValoresConfiguracion(189);

//Consultas::insertaReporteNuevo(122, 'México-Irapuato');
//print_r($obtenerReportes);

foreach ($obtenerReportes as $ids){
    //echo $ids['gpo']."<br/>";

    //Consultas::insertaReporteNuevo($ids['gpo'], '');
    $valor = Consultas::VerificaExisteCampo($ids['gpo'],464);
    echo $ids['gpo']."--".$valor;

    if($valor==0){
        echo "inserta<br/>";
        Consultas::insertaReporteNuevo($ids['gpo'], 'Aprobada',464);
    }else{
        echo "no inserta<br/>";
    }

    /*if($ids['ubi']=="23" || $ids['ubi']=="24"){
        if($valor==0) {
            echo "------Modernización CNC" . " inserta<br/>";
            //Consultas::insertaReporteNuevo($ids['gpo'], 'Modernización CNC');
        }else{
            echo "------Modernización CNC" . " no inserta<br/>";
        }
    }else{
        if($valor==0) {
            echo "------México-Irapuato" . " inserta<br/>";
            //Consultas::insertaReporteNuevo($ids['gpo'], "México-Irapuato");
        }else{
            echo "------México-Irapuato" . " no inserta<br/>";
        }
    }*/

    /*if($ids['valor']=="" || $ids['valor']=="Abierta"){
        echo "actualza Abierto<br/>";
        Consultas::actualizaCampoReporte($ids['gpo'],"Abierto");
    }else if($ids['valor']=="Cerrada"){
        echo "actualiza Cerrado<br/>";
        Consultas::actualizaCampoReporte($ids['gpo'],"Cerrado");
    }else{
        echo "no actualiza<br/>";
    }*/
}

?>