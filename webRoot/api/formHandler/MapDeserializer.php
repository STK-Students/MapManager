<?php

use MapFile\Model\Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

/**
 * Converts a associative array (JSON) of strings to a map object.
 */
class MapDeserializer
{
    /**
     * An array of all valid arguments.
     */
    const validArguments = array('name', 'scaledenom', 'units', 'angle', 'size', 'resolution', 'maxsize', 'extent', 'layers', 'status', 'includedServices');

    public static function handleMap(Map $existingMap, $updateData): Map
    {
        $map = new Map();
        foreach (self::validArguments as $argument) {
            $value = $updateData[$argument];
            if (!isset($value)) {
                // Explicitly unset arguments that have not been defined by the user.
                $map->$argument = null;
            } else {
                // Some arguments require special conversion, e.g. because they need to be merged into a new data type.
                switch ($argument) {
                    default:
                        $map->$argument = $value;
                        break;
                    case 'size':
                        $map->size = array($value['x'], $value['y']);
                        break;
                    case 'extent':
                        $extent = array($value['minx'], $value['miny'], $value['maxx'], $value['maxy']);
                        $map->extent = array_map("floatval", $extent);
                        break;
                    case 'layers':
                        setLayersOnMap($map, $value);
                        break;
                    case 'includedServices':
                        $paths = [];
                        foreach ($value as $service) {
                            $paths[] = Database::getInstance()->getGeoService($service)->getPath();
                            //TODO: Relativvize path
                        }
                        $map->include = $paths;
                        break;
                }
            }
        }
        return $map;
    }
}

/**
 * Set's the given layers on the given map.
 * Layers with names that exist on the map will be preserved.
 * Layers not given in $layerData will be removed.
 * @param Map $map to edit
 * @param array $layerData that contains new layers
 * @return void
 */
function setLayersOnMap(Map $map, array $layerData): void
{
    $currentLayers = $map->layer;

    $sizeNewLayers = count($layerData);
    for ($i = 0; $i < $sizeNewLayers; $i++) {
        if ($layerData[$i]['name'] !== $currentLayers[$i]['name']) {
            $currentLayers[$i] = $layerData[$i];
        }
    }
    $sizeCurrentLayers = count($currentLayers);
    if ($sizeCurrentLayers > $sizeNewLayers) {
        $deletedLayers = $sizeCurrentLayers - $sizeNewLayers;
        for ($i = $sizeNewLayers; $i <= $sizeNewLayers + $deletedLayers; $i++) {
            unset($currentLayers[$i]);
        }
    }
    $map->layer = $currentLayers;
}
