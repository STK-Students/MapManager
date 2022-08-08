<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";

$db = Database::getInstance();;

$uuid = $_POST['input-uuid'];
$name = $_POST['input-name'];
$description = $_POST['input-description'];
echo $uuid;
echo $name;
echo $description;

if(isset($_POST["submit-edit-map"])){
    try {
        $result = $db->editMap($uuid, $name, $description);
        header('Location: /templates/home/home.php?result=success');
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: /templates/home/home.php?result=failure');
    }
}