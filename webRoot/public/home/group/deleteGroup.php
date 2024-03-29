<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();
$groupUUID = $_SESSION['currentGroup'];
if (isset($_POST['remove_group_form_submit'])) {
    try {
        $db->removeUsersFromGroup($groupUUID);
        $db->removeGroup($groupUUID);
        unset($_SESSION['currentGroup']);
        header('Location: /public/home/home.php?result=success');
    } catch (Exception $e) {
        header('Location: /public/home/home.php?result=failure');
    }
}
