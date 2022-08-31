<?php
session_start();

use MapFile\Model\Layer;
use MapFile\Model\Map;

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Map/MapDeserializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Map/MapSerializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Layer/LayerDeserializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Layer/LayerSerializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Map.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Layer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/Selectable.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/Collection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine/Common/Collections/ArrayCollection.php";

/** Writes the new map state into the session **/


// Payload Format
// uuid: serviceUUID
// type: map | layer
// context: e.g. layerID
// data: {...}

// This is the content of the POST request submitted to this page
$serviceUpdateJSON = json_decode(trim(file_get_contents('php://input')), true);
$map = MapFileHandler::loadMapByUUID($serviceUpdateJSON['uuid']);
$updateData = $serviceUpdateJSON['data'];
switch ($serviceUpdateJSON['type']) {
    case 'map':
        $map = MapDeserializer::handleMap($map, $updateData);
        break;
    case 'layer':
        $layer = $map->layer->get($updateData['layerIndex']);
        if ($layer == null) {
            $layer = new Layer();
        }
        $newLayer = LayerDeserializer::handleLayer($layer, $updateData);
        $map->layer->set($updateData['layerIndex'], $newLayer);
        break;
}
MapFileHandler::writeMapFile($map, $serviceUpdateJSON['uuid']);
print(json_encode($map));

