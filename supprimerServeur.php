<?php
header('Content-type: application/json');
require_once('haproxyFunctions.php');

$indice = $_GET['indice'];
$serveur = $_GET['serveur'];

echo supprimerServeur($indice, $serveur);