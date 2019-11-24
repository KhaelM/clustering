<?php
require('ftpFunction.php');

$ip = $_GET['ip'];
$conn = ftp_connect($ip);
$login = ftp_login($conn, "mooky", "azerty");
ftp_sync(".", $conn);
ftp_close($conn);

echo "Projet synchronisé.";