<?php

$mapFileLoc = "../../dependencies/MapFileParser";
$doctrineLoc = "../../dependencies/Doctrine";
require("{$mapFileLoc}/Model/Map.php");
require("{$mapFileLoc}/Model/Layer.php");
require "{$doctrineLoc}/Common/Collections/Selectable.php";
require "{$doctrineLoc}/Common/Collections/Collection.php";
require "{$doctrineLoc}/Common/Collections/ArrayCollection.php";

/** Writes the new map state into the session **/

$map = isset($_SESSION['map']) ? unserialize($_SESSION['map']) : new Map();

// This is the content of the POST request submitted to this page
$json = json_decode(trim(file_get_contents('php://input')), true);
$map = jsonToMap($map, $json);
$_SESSION['map'] = serialize($map);

print(json_encode($map));