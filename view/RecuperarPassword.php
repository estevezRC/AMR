<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

$dominio = $_SERVER['SERVER_NAME'];
if ($dominio == 'creando.uno') {
    $dominio = 'https://creando.uno/reportech';
    $servername = "localhost";
    $username = "getit";
    $password = "wfTCy3#G(56";
    $dbname = "getit_reportech";
} else {
    $dominio = 'https://reportech.mx/reportech';
    $servername = "localhost";
    $username = "reportec_superad";
    $password = "Mh_&782fY?2*)/%";
    $dbname = "reportec_InnoDB";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cosmo/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../css/estilos.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" type="image/png" href="../img/reportech_logo.png"/>
    <title>ReporTech.mx</title>
</head>

<body id="index" style="background-color: #034667">
<div class="container-fluid" style="position: absolute; top: 20%">
    <div class="col-sm-4 offset-sm-4">
        <div class="card" style="min-width: 260px">
            <div class="card-header text-center">
                <!--                <h1><strong>ReporTech</strong> Sistema</h1>-->
                <img src="../img/reportech_large_logo.png" alt="" height="50px">
            </div>

            <!----------------------- ENVIO DE NOTIFICACION DE CORREO ----------------------------------------->
            <?php
            /* -------------------------------------- CONEXION ----------------------------------------------*/
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            /* ------------------------------ CONSULTAR DATOS DEL CORREO -------------------------------------*/
            if (isset($_POST['usuariocorreo'])) {
                $correo = htmlentities($_POST['usuariocorreo']);
                $query = "SELECT * FROM VW_getAllUsuarios WHERE correo_Usuario = '$correo'";
                $resultado = $conn->query($query);
                if (!$resultado) {
                    echo 'No se pudo ejecutar la consulta: ' . mysql_error();
                    exit;
                }
                $filas = $resultado->num_rows;
                if ($filas == 0) {
                    $mensaje = "No hay ninguna cuenta con el correo <b>" . $correo . "</b>";
                } else {
                    while ($filas = mysqli_fetch_assoc($resultado)) {
                        $licencia = $filas['id_Usuario'];
                    }
                    /* ------------------------------ ENVIAR NOTIFICACION DE CORREO -------------------------------------*/
                    $mail = new PHPMailer;
                    try {
                        //$mail->SMTPDebug = 2;                                       // Enable verbose debug output
                        $mail->isSMTP();                                            // Set mailer to use SMTP
                        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                        $mail->CharSet = 'UTF-8';
                        //$mail->Port       = 587;                                    // TCP port to connect to

                        if ($dominio == 'https://creando.uno/reportech'){
                            $mail->Host = 'creando.uno';  // Specify main and backup SMTP servers
                            $mail->Username = 'contacto@creando.uno';                     // SMTP username
                            $mail->Password = 'creando.mail_19';
                            $mail->setFrom('contacto@creando.uno','ReporTech');
                        } else {
                            $mail->Host = 'reportech.mx';  // Specify main and backup SMTP servers
                            $mail->Username = 'contacto@reportech.mx';                     // SMTP username
                            $mail->Password = 'reportech.mail_19';
                            $mail->setFrom('contacto@reportech.mx','ReporTech');
                        }

                        //Recipients
                        $mail->addAddress($correo, $correo);     // Add a recipient

                        // Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Recupera Contraseña';
                        $mail->Body = "Para recuperar tu contraseña da click en el siguiente enlace <br>" .
                            $dominio . "/view/RecuperarPassword.php?id=" . $licencia;
                        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                        $mail->send();
                        $mensaje = 'Correo de recuperación enviado a';
                        //echo 'Message has been sent';
                        //echo $mensaje;
                    } catch (Exception $e) {
                        $mensaje = 'No se pudo enviar el correo a';
                        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
                if ($mensaje == 'Correo de recuperación enviado a') { ?>
                    <div class="alert alert-success mt-3">
                        <strong>¡Exitoso!</strong> <?php echo $mensaje. '<b> ' . $correo . '</b>' ?>
                    </div>
                <?php } elseif ($mensaje == 'No se pudo enviar el correo a') { ?>
                    <div class="alert alert-danger mt-3">
                        <strong>¡Hay un problema!</strong> <?php echo $mensaje. '<b> ' . $correo . '</b>' ?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info mt-3">
                        <strong>¡Algo pasa!</strong> <?php echo $mensaje; ?>
                    </div>
                <?php }
            }

            /* ---------------------------------- CAMBIAR CONTRASEÑA --------------------------------------*/
            if (isset($_POST['pass1']) && isset($_POST['pass2']) && isset($_POST['id_Usuario'])) {
                $id_Usuario = $_POST['id_Usuario'];
                $password = $_POST['pass1'];
                $query = "UPDATE Usuarios SET password_Usuario = AES_ENCRYPT('$password', 'getitcom_2017') WHERE id_Usuario
            = $id_Usuario";
                $resultado = $conn->query($query);
                if (!$resultado) {
                    $mensaje = "
            <div class='text-center'>Error al actualizar los datos!</div>
            ";
                } else {
                    $mensaje = "
            <div class='text-center'>Se han actualizado los datos de su cuenta.</div>
            ";
                }
                echo $mensaje;
            }

            ?>
            <!----------------------------- FORMULARIO DE CORREO ------------------------------------------->
            <?php if (!isset($_POST['usuariocorreo']) && (!isset($_GET['id']))) { ?>
                <div class="card-body">
                    <form action="" method="POST" class="">
                        <div class="form-group">
                            <label for="usuariocorreo">Correo</label>
                            <input name="usuariocorreo" id="usuariocorreo" type="email" size="25" value=""
                                   placeholder="Correo de Recuperación" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="correo" value="Recuperar Contraseña"
                                   class="btn btn-block btn-warning">
                        </div>
                        <div class="form-group text-center">
                            <?php echo $_GET['mensaje']; ?>
                            <a href="<?php echo $dominio . '/Login.php' ?>">Regresar a ReporTech</a>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <!----------------------------- FORMULARIO DE PASSWORD ----------------------------------------->
            <?php if (isset($_GET['id']) && !isset($_POST['pass1']) && !isset($_POST['pass2']) && !isset($_POST['id_Usuario'])) { ?>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="pass1">Escriba su nueva contraseña</label>
                            <input type="password" name="pass1" class="form-control" id="pass1" onkeyup="verificar(2);"
                                   pattern="(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ].*"
                                   placeholder="Debe contener una letra mayúscula y un número"
                                   title="Contraseña(6-12 carácteres debe incluir 1 mayúscula y un número)">
                        </div>
                        <div class="form-group">
                            <label for="pass2">Repita su contraseña</label>
                            <input type="password" name="pass2" class="form-control" id="pass2"
                                   onkeyup="verificar(this);"
                                   placeholder="Debe contener una letra mayúscula y un número" required/>
                            <input type="hidden" name="id_Usuario" value="<?php echo $_GET['id']; ?>"
                                   class="form-control" id="id_Usuario"/>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="nuevo_registro" name="correo" value="Cambiar"
                                   class="btn btn-danger btn-block"
                                   disabled/>
                            <span style="color:#C00;" id="mensaje"><!-- mensaje de verificación --></span>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    //<![CDATA[
    function verificar(v) {

        let id_click = v.id;
        if (id_click == "pass1") {
            id_comparar = "pass2";
        } else {
            id_comparar = "pass1";
        }

        var p1 = document.getElementById(id_comparar);
        if (p1.value != v.value) {
            document.getElementById('mensaje').innerHTML = "¡Contraseñas no coinciden!";
            document.getElementById('nuevo_registro').disabled = true;
        } else {
            document.getElementById('mensaje').innerHTML = "";
            document.getElementById('nuevo_registro').disabled = false;
        }
    }

    //]]>
</script>
</html>
