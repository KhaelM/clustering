<?php
session_start();
require_once('serverNames.php');

$link = new mysqli('192.168.88.15:3307', 'haproxy_root', 'haproxy', 'ninja');

$query = "SHOW VARIABLES LIKE 'server_id'";
$result = $link->query($query);
$mysqlServerId = (int) $result->fetch_assoc()['Value'];
$insertionDone = FALSE;

if(isset($_POST['ninjaName'])) {
    
    $sql = "INSERT INTO Ninja (nom) VALUES('".$_POST['ninjaName']. "')";
    if($link->query($sql) === TRUE)
        $insertionDone = TRUE;
}
$link->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Résultats insertion</title>
</head>
<body>
    <fieldset>
        <legend><h2>Information sur les serveurs</h2></legend>
        <div>IP Serveur Apache: <span style="color:green"><?= $_SERVER['SERVER_ADDR']; ?></span> (<?= $apacheServers[$_SERVER['SERVER_ADDR']]; ?>)</div>
        <div>ID Serveur Mysql: <span style="color:brown"><?= $mysqlServerId  ?></span> (<?= $mysqlServers[$mysqlServerId] ?>)</div>
    </fieldset>
    <a href="stats.php">Stats</a>
    <?php if($insertionDone) { ?>
        <p style="color:green">Insertion réussie</p>
    <?php
    } 
    ?>
</body>
</html>