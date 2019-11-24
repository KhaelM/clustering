<?php
header('Content-type: application/json');
require_once('haproxyFunctions.php');

$server = $_GET['serveur'];
$indice = (int) $_GET['indice'];
$nom = $_GET['nom'];
$ip = $_GET['ip'];
$port = $_GET['port'];

echo modifierServeur($server, $indice, $nom, $ip, $port);
?>