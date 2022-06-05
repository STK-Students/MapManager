<?php

require("database.php");

$db = new Database("Postgres", "webDevDB", "postgres", "postgres");

if(isset($_GET['getGroup'])){
    $groupUUID = $_GET['getGroup'];
    $group = $db->getGroup($groupUUID);
    $response = Array("uuid" => $group->getUUID(), "name" => $group->getName());
    print(json_encode($response));
}


?>
