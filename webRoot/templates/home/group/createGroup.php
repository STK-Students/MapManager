<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db = Database::getInstance();
if(isset($_POST['submit-create-group'])){
    $name = $_POST['groupNameCreate'];
    try{
        $groupUUID = pg_fetch_result($db->addGroup($name), 0, 0);
        $userUUID = $_SESSION['authenticatedUser'];
        $db->addUserToGroup($groupUUID, $userUUID);
        header('Location: /templates/home/home.php?result=success&uuid=' . $groupUUID);
    } catch (Exception $e){

    }
}

?>

