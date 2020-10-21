<?php
require "../vendor/autoload.php";

use Mail\Email;
use Mail\Templates\Minuta\Minuta;

$minuta = new Minuta([], "https://gemap.mx/conmex/mail/Templates/Minuta/logo.png");


$body = $minuta->getContent();


$email = new Email($minuta, "get-s.dev",  "soporte@get-s.dev", 'ch,11gI$TO}#', "soporte@get-s.dev");
$email->configure();
$email->addRecipients(["franciscoalejandrotorresortiz@gmail.com"]);
$email->send("Minuta de reunión");

echo $body;
