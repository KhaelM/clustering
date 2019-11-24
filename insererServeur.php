<?php
header('Content-type: application/json');
require_once("haproxyFunctions.php");

$nom = $_GET['nom'];
$ip = $_GET['ip'];
$port = $_GET['port'];
$serveur = $_GET['serveur'];

echo insererServeur($nom, $ip, $port, $serveur);