<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileWriter.php";

$db = Database::getInstance();;
$group = $_POST['group-uuid'];

$name = $_POST['input-name'];
$description = $_POST['input-description'];
$creationDate = date('Y-m-d');

$result = $db->addMap($name, $description, $creationDate, $group);
$generatedUUID = pg_fetch_result($result, 0, 0);
$_SESSION['currentMapUUID'] = $generatedUUID;
header('Location: /templates/forms/edit.php?uuid=' . $generatedUUID);
$w = (new MapFileWriter())->writeMapFile();

