<?php
$to = "ljuarez@getitcompany.com";
$subject = "From the Corn job";

$message = "
<html>
<head>
<title>From the Corn job</title>
</head>
<body>
<p>Hola este es un mensaje en el corn job AASAPP</p>
</body>
</html>
";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From: <webmaster@getitcompany.com>" . "\r\n";

mail($to,$subject,$message,$headers);

//echo "Envia daotos";

?>