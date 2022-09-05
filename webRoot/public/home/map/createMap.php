<?php

use MapFile\Model\Map;

session_start();

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";

require_once("$mapFileLoc/Model/Map.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";

$db = Database::getInstance();;
$group = $_POST['group-uuid'];

$name = $_POST['input-name'];
$description = $_POST['input-description'];
$creationDate = date('Y-m-d');

if(isset($_POST["submit-create-map"])){
    try {
        $result = $db->addMap($name, $description, $creationDate, $group);
        $generatedUUID = pg_fetch_result($result, 0, 0);
        $map = new Map();
        $map->status = "ON";
        MapFileHandler::writeMapFile($map, $generatedUUID);
        header('Location: /public/forms/map/map.php?serviceUUID=' . $generatedUUID);
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: /public/home/home.php?result=failure');
    }
}