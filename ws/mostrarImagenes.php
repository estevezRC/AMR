<?php

require 'Consultas.php';

$fotografias = Consultas::obtenerImagenesUsuario(2);
//print_r($fotografias);

if(count($fotografias)>0){
    $i = 0;
    foreach ($fotografias as $fotos){
        $fechaFotografia = $fotos['fecha_Fotografia'];

        $f = new DateTime($fechaFotografia);
        $fechaFormat =  $f->format('d-m-Y');
        $fechaCarpeta = $f->format('Ym');

        echo '<img src="http://aasapp.mx/aasapp_DESARROLLO/img/reportes/'.$fechaCarpeta.'/'.$fotos['nombre_Fotografia'].'" height="100" width="120"/>';
        $i++;
        if($i%7 == 0) {
            echo '<br/>';
        }
    }
}else {
    echo 'Sin fotos';
}

?>