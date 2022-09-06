<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
require $_SERVER['DOCUMENT_ROOT'] . "/api/GUIDGenerator.php";
$db = Database::getInstance();
if (isset($_POST['submit-create-group'])) {
    $name = $_POST['groupNameCreate'];
    try {
        $groupUUID = GUIDGenerator::getGUIDv4();
        $db->addGroup($name, $groupUUID);
        $db->addUserToGroup($groupUUID, $_SERVER['REMOTE_USER']);
        header('Location: /public/home/home.php?result=success&uuid=' . $groupUUID);
    } catch (Exception $e) {
        header('Location: /public/home/home.php?result=failure');
    }
}
