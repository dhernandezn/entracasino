<?php
require_once("validar.php");

$cons = new Consultas();
$ver = $cons -> consultaApiRegcheq('91884828','natural','A4CF182C007DB3F9009B9666');
echo $ver;

?>