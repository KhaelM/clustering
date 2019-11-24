<?php
header('Content-type: application/json');
require_once("haproxyFunctions.php");

$ip = $_GET['ip'];
$port = $_GET['port'];
$serveur = $_GET['serveur'];

echo modifierHaproxy($ip, $port, $serveur);

?>