<?php

use MapFile\Model\Map as Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

function jsonToMap(Map $map, array $json): Map
{

    /**
     * An array of all valid arguments. Required so the value of the
     * $_SESSION variable cannot be set freely by the user.
     */
    $validArguments = array('name', 'scaledenom', 'angle', 'size', 'maxsize', 'extent');

    foreach ($validArguments as $argument) {
        $value = $json[$argument];
        if (isset($value)) {

            // Handle edge cases first
            switch ($argument) {
                case 'size':
                    $map->size = array($value['x'], $value['y']);
                    break;
                case 'extent':
                    $extent = array($value['minx'], $value['miny'], $value['maxx'], $value['maxy']);
                    $map->extent = array_map("floatval", $extent);
                    break;
            }

            // Handle all other cases
            $map->$argument = $json[$argument];
        } else {
            $map->$argument = null;
        }
    }
    return $map;
}

function mapToJSON(Map $map): string
{
    $json = [];
    foreach (get_object_vars($map) as $key=>$value) {
        if (isset($value)) {
            $json[$key] = $value;
        }
    }
    return json_encode($json);
}