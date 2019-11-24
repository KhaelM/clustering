<?php
// header('Content-Type: text/plain');
require_once('haproxyFunctions.php');

$apacheServers = array();
$mysqlServers = array();
$haProxyServerIp = "";
$haProxyPort = "";

// $mysql = new Computer("michael", "192.168.88.100", "80");


// insertComputer($mysql, $fileLocation, "mysql");
parseHaproxyInformation($apacheServers, $mysqlServers, $haProxyServerIp, $haProxyPort);
// print_r($apacheServers);
// print_r($mysqlServers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HA Proxy configuration</title>
</head>
<body>
    <fieldset>
        <legend>Haproxy</legend>
        Ip <input id="haproxyIp" type="text" value="<?= $haProxyServerIp ?>">
        Port <input id="haproxyPort" type="text" value="<?= $haProxyPort ?>">
        <select id="haproxyServeur" name="serveur">
            <option value="Apache">Apache</option>
            <option value="Mysql">Mysql</option>
        </select>
        <button onclick="modifierHaproxy()">Modifier</button>
        <div id="haproxyMessage"></div>
    </fieldset>
    
    <fieldset>
        <legend>Liste des serveurs</legend>
        <table border="1" style="border-collapse: collapse">
            <thead>
                <tr>
                    <th colspan="3">Apache</th>
                </tr>
                <tr>
                    <th>Nom</th>
                    <th>Adresse ip</th>
                    <th>Port</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i < count($apacheServers); $i++) { ?>
                    <tr class="serveurApache">
                        <td><input type="text" value="<?= $apacheServers[$i]->getName() ?>" ></td>
                        <td style="text-align: right"><input type="text" value="<?= $apacheServers[$i]->getIpAddress() ?>"></td>
                        <td style="text-align: right"><input type="text" value="<?= $apacheServers[$i]->getPort() ?>"></td>
                        <td><button onclick="modifierServeur('Apache',<?= $i ?>)">Modifier</button></td>
                        <td><button onclick="supprimerServeur('Apache',<?= $i ?>)">Supprimer</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div id="ApacheMessage"></div>
        
        <table border="1" style="border-collapse: collapse; margin-top: 10px">
            <thead>
                <tr>
                    <th colspan="3">Mysql</th>
                </tr>
                <tr>
                    <th>Nom</th>
                    <th>Adresse ip</th>
                    <th>Port</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i < count($mysqlServers); $i++) { ?>
                    <tr class="serveurMysql">
                        <td><input type="text" value="<?= $mysqlServers[$i]->getName() ?>" ></td>
                        <td style="text-align: right"><input type="text" value="<?= $mysqlServers[$i]->getIpAddress() ?>"></td>
                        <td style="text-align: right"><input type="text" value="<?= $mysqlServers[$i]->getPort() ?>"></td>
                        <td><button onclick="modifierServeur('Mysql',<?= $i ?>)">Modifier</button></td>
                        <td><button onclick="supprimerServeur('Mysql',<?= $i ?>)">Supprimer</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div id="MysqlMessage"></div>
    </fieldset>
    <fieldset>
        <legend>Ajout serveur</legend>
        <label  for="name">Nom</label>
        <input id="nom_serveur" type="text" name="nom">
        <label for="ip">Ip</label>
        <input id="ip_serveur" type="text" name="ip">
        <label for="port">Port</label>
        <input id="port_serveur" type="text" name="port">
        <select id="serveur" name="serveur">
            <option value="Apache">Apache</option>
            <option value="Mysql">Mysql</option>
        </select>
        <button onclick="ajouterServeur()">Ajouter</button>
        <div id="ajoutMessage"></div>
    </fieldset>
    <script src="jquery-3.4.1.min.js"></script>
    <script>
        var delay = 1000;
        
        function supprimerServeur(serveur, indice) {
            $.ajax({
                url: "supprimerServeur.php",
                method: 'GET',
                dataType: "json",
                data: `serveur=${serveur}&indice=${indice}`,
                success: function (result) {
                    if(result.status == "success") {
                        $(`#${serveur}Message`).css("color", "green");
                    } else {
                        $(`#${serveur}Message`).css("color", "red");
                    }
                    $(`.serveur${serveur}`).eq(indice).remove();
                    $(`#${serveur}Message`).html(result.message);
                    $(`#${serveur}Message`).show().delay(delay).fadeOut();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function ajouterServeur() {
            $.ajax({
                url: "insererServeur.php",
                method: 'GET',
                dataType: "json",
                data: `serveur=${$("#serveur").val()}&nom=${$("#nom_serveur").val()}&ip=${$("#ip_serveur").val()}&port=${$("#port_serveur").val()}`,
                success: function (result) {
                    console.log(result);
                    if(result.status == "success") {
                        $("#ajoutMessage").css("color", "green");
                    } else {
                        $("#ajoutMessage").css("color", "red");
                    }
                    $("#ajoutMessage").html(result.message);
                    $("#ajoutMessage").show().delay(delay).fadeOut();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function modifierHaproxy() {
            $.ajax({
                url: "modifierHaproxy.php",
                method: 'GET',
                dataType: "json",
                data: `serveur=${$("#haproxyServeur").val()}&ip=${$("#haproxyIp").val()}&port=${$("#haproxyPort").val()}`,
                success: function (result) {
                    if(result.status == "success") {
                        $("#haproxyMessage").css("color", "green");
                    } else {
                        $("#haproxyMessage").css("color", "red");
                    }
                    $("#haproxyMessage").html(result.message);
                    $("#haproxyMessage").show().delay(delay).fadeOut();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function modifierServeur(serveur, indice) {
            var nom = $(`.serveur${serveur}`).eq(indice).children().eq(0).children().eq(0).val();
            var ip = $(`.serveur${serveur}`).eq(indice).children().eq(1).children().eq(0).val();
            var port = $(`.serveur${serveur}`).eq(indice).children().eq(2).children().eq(0).val();
            
            $.ajax({
                url: "modifierServeur.php",
                method: 'GET',
                dataType: "json",
                data: `serveur=${serveur}&ip=${ip}&port=${port}&nom=${nom}&indice=${indice}`,
                success: function (result) {
                    if(result.status == "success") {
                        $(`#${serveur}Message`).css("color", "green");
                    } else {
                        $(`#${serveur}Message`).css("color", "red");
                    }
                    $(`#${serveur}Message`).html(result.message);
                    $(`#${serveur}Message`).show().delay(delay).fadeOut();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>