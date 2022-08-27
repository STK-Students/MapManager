<?php
session_start();

use MapFile\Model\Map;

require_once "../ServiceConverter.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Map.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Layer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/Selectable.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/Collection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/ArrayCollection.php";

/** Writes the new map state into the session **/

$map = isset($_SESSION['map']) ? unserialize($_SESSION['map']) : new Map();

// Payload Format
// type: map | layer
// context: e.g. layerID
// data: {...}

// This is the content of the POST request submitted to this page
$serviceUpdateJSON = json_decode(trim(file_get_contents('php://input')), true);

$updateData = $serviceUpdateJSON['data'];
switch ($serviceUpdateJSON['type']) {
    case 'map':
        MapDeserializer::handleMap($map, $updateData);
        break;
    case 'layer':
        LayerDeserializer::handleLayer($map, $updateData);
        break;
}
$_SESSION['map'] = serialize($map);

$mapFilePath = MapFileHandler::getPath();
MapFileHandler::writeMapFile($mapFilePath);

print(json_encode($map));

