<?php

use MapFile\Model\Map as Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

/**
 * Set's the given values from the JSON in the given map object.
 * @param Map $map to edit
 * @param array $json to get the data from
 * @return Map edited map
 */
function jsonToMap(Map $map, array $json): Map
{

    /**
     * An array of all valid arguments.
     */
    $validArguments = array('name', 'scaledenom', 'units', 'angle', 'size', 'maxsize', 'extent');
    foreach ($validArguments as $argument) {
        $value = $json[$argument];
        if (!isset($value)) {
            // Explicitly unset arguments that have not been defined by the user.
            $map->$argument = null;
        } else {
            // Some arguments require special conversion, e.g. because they need to be merged into a new data type.
            switch ($argument) {
                case 'size':
                    $map->size = array($value['x'], $value['y']);
                    break;
                case 'extent':
                    $extent = array($value['minx'], $value['miny'], $value['maxx'], $value['maxy']);
                    $map->extent = array_map("floatval", $extent);
                    break;
                default:
                    $map->$argument = $value;
            }
        }
    }
    return $map;
}

/**
 * Converts the map object into a JSON format that can be understood by the JS for filling the forms.
 * @param Map $map map to convert
 * @return string JSON for the website JS
 */
function mapToJSON(Map $map): string
{
    $json = [];
    foreach (get_object_vars($map) as $key => $value) {
        if (isset($value)) {
            switch ($key) {
                case 'size':
                    $json['size'] = array("x" => $value[0], "y" => $value[1]);
                    break;
                case 'extent':
                    $json['extent'] = array("minx" => $value[0], "miny" => $value[1], "maxx" => $value[2], "maxy" => $value[3]);
                    break;
                default:
                    $json[$key] = $value;
            }
        }
    }
    return json_encode($json);
}