<?php

require("database.php");

$db = new Database("Postgres", "webDevDB", "postgres", "postgres");

if (isset($_GET['getGroup'])) {
    $groupUUID = $_GET['getGroup'];
    $group = $db->getGroup($groupUUID);
    $response = array("uuid" => $group->getUUID(), "name" => $group->getName());
    print(json_encode($response));
} if(isset($_GET['getMaps'])){
    $groupUUID = $_GET['getMaps'];
    $maps = array();
    $response = $db->getMaps($groupUUID);
    for($i = 0; $i < count($response); $i++){
        $map = (object) $response[$i];
        $maps[] = array("uuid" => $map->getUUID(), "name" => $map->getName(), "description" => $map->getDescription(), "creationDate" => $map->getCreationDate(), "groupUUID" => $map->getGroupUUID());
    }
    print(json_encode($maps));
}
