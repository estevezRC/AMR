<?php
//Inicio la sesión
//COMPRUEBA QUE EL USUARIO ESTA AUTENTICADO
if (($_SESSION["autenticado"] != TRUE)&&(!isset($_SESSION["usuarionombre"]))) {
//si no existe, va a la página de autenticacion
    header("Location: login.php");
//salimos de este script
    exit();
}
?>