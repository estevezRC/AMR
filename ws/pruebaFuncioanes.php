<?php
require 'Consultas.php';
require 'Funciones.php';
require '../lib/PHPMailer-master/PHPMailerAutoload.php';


echo $_SERVER["HTTP_HOST"];
echo '<br/>';
$url = $_SERVER["REQUEST_URI"];

echo $url;

$desarrollo = strpos($url,"aasapp_DESARROLLO");

if($desarrollo){
    echo "ok";
}

/*:::::::::::::::::: PRUEBA PARA INSERSION DE DATO EN VALORE_DEFAULT :::::::::::::::
 * $val_texto = "aaa";
$datosCampo = Consultas::getValoresCampo(498);

print_r($datosCampo);

$idCampo = $datosCampo[1];
$valoresCampo = $datosCampo[0];
$arryValores = explode("/",$valoresCampo);

$existe = "no";
for($z=0;$z<count($arryValores);$z++){
    echo '<br/>'.$val_texto."---".$arryValores[$z];
    if($arryValores[$z] == $val_texto){
        $existe = "si";
        echo '<br/>'.$existe;
    }
}

if($existe == "no"){
    echo "<br/>modifica<br/>";
    $valoresCampo = $valoresCampo."/".$val_texto;
    $actualiza = Consultas::actualizaValorDefault($idCampo,$valoresCampo);

    if($actualiza){
        echo 'actualiza<br/>';
    }
}

echo $valoresCampo;

/*:::::::::::::::::: PRUEBA PARA INSERSION DE DATO EN VALORE_DEFAULT :::::::::::::::*/

/*$idConf = 499;
$val_texto = "Jabonerasss";//"prueba insertada catalogo";

if(is_numeric($val_texto)) {
    echo '<br/>';
    echo var_export($val_texto, true) . " es numérico", PHP_EOL;
} else {
    echo '<br/>';
    echo var_export($val_texto, true) . " NO es numérico", PHP_EOL;
    $exiteConcepto = Consultas::getExisteConceptoCatalogo($val_texto);

    if(!$exiteConcepto){
        $datosCampo = Consultas::getValoresCampo($idConf);
        $idCampo = $datosCampo[1];
        $valoresCampo = $datosCampo[0];
        $arryValores = explode(",",$valoresCampo);
        $categoria = $arryValores[0];
        //echo $idCategoria;
        //print_r($arryValores);
        $insertaCatalogo = Consultas::InsertaCatalogoCategoria($val_texto,$categoria);
        if($insertaCatalogo){
            $idConceptoCatalogo = Consultas::getIdConceptoCatalogo($val_texto);
            $val_texto = $idConceptoCatalogo;
        }
        echo "<br/>Inserta ".$val_texto.'<br/>';
    }else{
        $idConceptoCatalogo = Consultas::getIdConceptoCatalogo($val_texto);
        $val_texto = $idConceptoCatalogo;
        echo "<br/>No inserta ".$val_texto.'<br/>';
    }

}*/

Funciones::EnviaCorreoIncidentesPruebas(37, 19601, "Vendedores ambulantes");

?>