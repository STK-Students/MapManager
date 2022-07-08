<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();;
$groupUUID = $_SESSION['currentGroup'];
$name = $_POST['groupName'];
if (isset($_POST['edit_group_form_submit'])) {
    try {
        $db->editGroup($groupUUID, $name);
        header('Location: /templates/home/home.php?result=success');
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

