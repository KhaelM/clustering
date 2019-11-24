<?php
require_once('Computer.php');

$fileLocation = "/etc/haproxy/";
$fileName = 'haproxy.cfg';

function parseHaproxyInformation(&$apacheServers, &$mysqlServers, &$haproxyIp, &$haproxyPort)
{
    $fileName = 'haproxy.cfg';
    global $fileLocation;

    try {
        if (!file_exists($fileLocation . $fileName)) {
            throw new Exception("Fichier non trouvé");
        }

        $fileAsArray = file($fileLocation . $fileName, FILE_IGNORE_NEW_LINES);

        $matches = array();
        $index = 0;
        $currentLine = "";
        while ($currentLine != "frontend Local_Server") {
            $currentLine = $fileAsArray[$index++];
        }
        preg_match("#(((\d{1,3}.){3})(\d{1,3})):((\d+))#", $fileAsArray[$index], $matches);
        $haproxyIp = $matches[1];
        $haproxyPort = $matches[5];

        while ($currentLine != "#apache_servers_start") {
            $currentLine = $fileAsArray[$index++];
        }

        while ($currentLine != "#apache_servers_end") {
            if (preg_match("#server (\w+) (((\d{1,3}.){3})(\d{1,3})):((\d+))#", $currentLine, $matches)) {
                array_push($apacheServers, new Computer($matches[1], $matches[2], $matches[6]));
            }
            $currentLine = $fileAsArray[$index++];
        }

        while ($currentLine != "#mysql_servers_start") {
            $currentLine = $fileAsArray[$index++];
        }
        while ($currentLine != "#mysql_servers_end") {
            if (preg_match("#server (\w+) (((\d{1,3}.){3})(\d{1,3})):((\d+))#", $currentLine, $matches)) {
                array_push($mysqlServers, new Computer($matches[1], $matches[2], $matches[6]));
            }
            $currentLine = $fileAsArray[$index++];
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function insererServeur($nom, $ip, $port, $serveur)
{
    $status = "";
    global $fileName;
    global $fileLocation; 

    if (empty($nom) || empty($ip) || empty($port)) {
        $status = "error";
        $message = "Un nom, une adresse ip et un port sont requis.";
    } else {
        $lines = file($fileLocation . $fileName, FILE_IGNORE_NEW_LINES);
        $index = 0;
        $currentLine = "";
        while ($currentLine != "#" . strtolower($serveur) . "_servers_end") {
            $currentLine = $lines[$index++];
        }
        array_splice($lines, $index - 1, 0, sprintf("\tserver %s %s:%s", $nom, $ip, $port));
        file_put_contents($fileLocation . $fileName, join("\n", $lines));
        $status = "success";
        $message = "Serveur $serveur ajouté.";
    }

    $arr = array('status' => $status, 'message' => $message);
    return json_encode($arr);
}

function modifierServeur($server, $indice, $nom, $ip, $port)
{
    $status = "";

    if (empty($nom) || empty($ip) || empty($port)) {
        $status = "error";
        $message = "Un nom, une adresse ip et un port sont requis.";
    } else {
        $lineReference = "#" . strtolower($server) . "_servers_start";

        global $fileLocation;
        global $fileName;

        $fileAsArray = file($fileLocation . $fileName, FILE_IGNORE_NEW_LINES);

        $index = 0;
        $currentLine = "";
        while ($currentLine != $lineReference) {
            $currentLine = $fileAsArray[$index++];
        }
        $fileAsArray[$index + $indice] = "\tserver $nom $ip:$port";

        file_put_contents($fileLocation . $fileName, join("\n", $fileAsArray));
        $status = "success";
        $message = "Informations mis à jour";
    }

    $arr = array('status' => $status, 'message' => $message);
    return json_encode($arr);
}

function modifierHaproxy($ip, $port, $serveur)
{
    $status = "";

    if (empty($ip) || empty($port)) {
        $status = "error";
        $message = "Une adresse ip et un port sont requis.";
    } else {
        global $fileLocation;
        global $fileName;

        $fileAsArray = file($fileLocation . $fileName, FILE_IGNORE_NEW_LINES);

        $lineReference = $serveur == "Apache" ? "frontend Local_Server" : "listen mysql-cluster"; 

        $index = 0;
        $currentLine = "";
        while ($currentLine != $lineReference) {
            $currentLine = $fileAsArray[$index++];
        }
        $fileAsArray[$index] = "\tbind $ip:$port";


        file_put_contents($fileLocation . $fileName, join("\n", $fileAsArray));
        $status = "success";
        $message = "Informations mis à jour";
    }

    $arr = array('status' => $status, 'message' => $message);
    
    return json_encode($arr);
}

function supprimerServeur($indice, $serveur)
{
    $status = "";
    global $fileName;
    global $fileLocation; 

    $lines = file($fileLocation . $fileName, FILE_IGNORE_NEW_LINES);
    $index = 0;
    $currentLine = "";
    while ($currentLine != "#" . strtolower($serveur) . "_servers_start") {
        $currentLine = $lines[$index++];
    }
    array_splice($lines, $index + $indice, 1);
    file_put_contents($fileLocation . $fileName, join("\n", $lines));
    $status = "success";
    $message = "Serveur $serveur supprimé.";

    $arr = array('status' => $status, 'message' => $message);
    return json_encode($arr);
}