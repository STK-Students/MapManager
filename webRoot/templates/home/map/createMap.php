<?php
session_start();
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
        $_SESSION['currentServiceUUID'] = $generatedUUID;
        MapFileHandler::writeMapFile(MapFileHandler::getPath());
        header('Location: /templates/forms/map/map.php?uuid=' . $generatedUUID);
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: /templates/);
    }
}