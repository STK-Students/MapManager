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
// data: {...}

// This is the content of the POST request submitted to this page
$serviceJSON = mapToJSON($map);
$servicePartUpdateJSON = json_decode(trim(file_get_contents('php://input')), true);
switch ($servicePartUpdateJSON['type']) {
    case 'map':
        foreach ($servicePartUpdateJSON['data'] as $key => $value) {
            switch ($key) {
                case 'layers':
                    break;
                default:
                    $serviceJSON[$key] = $value;

            }
            $serviceJSON[$key] = $value;
        }
        break;
    case 'layer':
        break;
}

$map = jsonToMap($map, $servicePartUpdateJSON);

$_SESSION['map'] = serialize($map);

$mapFilePath = MapFileHandler::getPath();
MapFileHandler::writeMapFile($mapFilePath);

print(json_encode($map));