<?php

use Doctrine\Common\Collections\ArrayCollection;
use MapFile\Model\Layer;
use MapFile\Model\Map as Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

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
    $validArguments = array('name', 'scaledenom', 'units', 'angle', 'size', 'resolution', 'maxsize', 'extent', 'layers', 'status', 'includedServices');
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
                case 'layers':
                    convertLayerToObject($map, $value);
                    break;
                case 'includedServices':
                    $paths = [];
                    foreach ($value as $service) {
                        $paths[] = Database::getInstance()->getGeoService($service)->getPath();
                        //TODO: Relativvize path
                    }
                    $map->include = $paths;
                    break;
                default:
                    $map->$argument = $value;
            }
        }
    }
    return $map;
}

function convertLayerToObject($map, $layerData)
{
    $layers = array();

    foreach ($layerData as $layer) {
        $parsedLayer = new Layer();
        foreach ($layer as $key => $value) {
            switch ($key) {
                default:
                    $parsedLayer->$key = $value;
            }
        }
        $layers[] = $parsedLayer;
    }
    $map->layer = new ArrayCollection($layers);
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
                case 'layer':
                    convertLayerToJSON($json, $value);
                default:
                    $json[$key] = $value;
            }
        }
    }
    return json_encode($json);
}

function convertLayerToJSON(&$json, $layers)
{
    foreach ($layers as $layer) {
        $layerJson = [];
        foreach (get_object_vars($layer) as $key => $value) {
            $layerJson[$key] = $value;
        }
        $json['layers'][] = $layerJson;
    }
}