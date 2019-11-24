<?php
session_start();

require_once('serverNames.php');

if(!isset($_SESSION['username']))
    $_SESSION['username'] = $_POST['username'];

$link = new mysqli('192.168.88.15:3307', 'haproxy_root', 'haproxy', 'ninja');
$query = "SHOW VARIABLES LIKE 'server_id'";
$result = $link->query($query);
$mysqlServerId = (int) $result->fetch_assoc()['Value'];

$ninjaQuery = "SELECT * FROM Ninja";
$ninjaResult = $link->query($ninjaQuery);

$link->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stats</title>
</head>
<body>
    <h1>Bienvenue <span><?= $_SESSION['username'] ?></span></h1>
    <form action="disconnect.php" method="get">
        <button type="submit">Détruire session</button>
    </form>
    <fieldset>
        <legend><h2>Information sur les serveurs</h2></legend>
        <div>IP Serveur Apache: <span style="color:green"><?= $_SERVER['SERVER_ADDR']; ?></span> (<?= $apacheServers[$_SERVER['SERVER_ADDR']]; ?>)</div>
        <div>ID Serveur Mysql: <span style="color:brown"><?= $mysqlServerId  ?></span> (<?= $mysqlServers[$mysqlServerId] ?>)</div>
    </fieldset>
    <fieldset>
        <legend><h2>Données de test</h2></legend>
        <form action="insert.php" method="POST">
            <input type="text" name="ninjaName" placeholder="Choisissez un nom de ninja">
            <button type="submit">Insérer</button>
        </form>
    </fieldset>
    <fieldset>
        <legend><h2>Table 'Ninja' du serveur avec l'id <span style="color:brown"><?= $mysqlServerId ?></span></h2></legend>
        <table border="1" style="border-collapse:collapse">
            <thead>
                <tr>
                    <th>Id Ninja</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($ninja = $ninjaResult->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?= $ninja["idNinja"] ?></td>
                            <td><?= $ninja["nom"] ?></td>
                        </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </fieldset>
</body>
</html>