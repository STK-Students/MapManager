<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();
$groupUUID = $_SESSION['currentGroup'];

if(isset($_POST['uuid'])){
    $userUUID = $_POST['uuid'];
    $db->removeUserFromGroup($groupUUID, $userUUID);
    header('Location: /public/home/home.php?result=success');
}