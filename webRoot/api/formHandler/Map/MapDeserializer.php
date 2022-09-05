<?php

use Doctrine\Common\Collections\ArrayCollection;
use MapFile\Model\Layer;
use MapFile\Model\Style;
use MapFile\Model\Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/NestedAttributeUpdater.php";

/**
 * Converts an associative array (JSON) of strings to a map object.
 */
class MapDeserializer
{
    /**
     * An array of all valid arguments.
     */
    const validArguments = array('name', 'scaledenom', 'units', 'angle', 'size', 'resolution', 'maxsize', 'extent', 'layers', 'status', 'include');

    public static function handleMap(Map $currentMap, $updateData): Map
    {
        foreach (self::validArguments as $argument) {
            $value = $updateData[$argument];
            if (!isset($value)) {
                // Explicitly unset arguments that have not been defined by the user.
                $currentMap->$argument = null;
            } else {
                // Some arguments require special conversion, e.g. because they need to be merged into a new data type.
                switch ($argument) {
                    default:
                        $currentMap->$argument = $value;
                        break;
                    case 'size':
                        $currentMap->size = array($value['x'], $value['y']);
                        break;
                    case 'extent':
                        $extent = array($value['minx'], $value['miny'], $value['maxx'], $value['maxy']);
                        $currentMap->extent = array_map("floatval", $extent);
                        break;
                    case 'layers':
                        $addHandler = function ($data) {
                            LayerDeserializer::handleLayer(new Layer(), $data);
                        };
                        NestedAttributeUpdater::setNestedAttribute($currentMap->layer, $value, $addHandler);
                        break;
                    case 'status':
                        $currentMap->status = $value == 1 ? "ON" : "OFF";
                        break;
                    case 'include':
                        $paths = [];
                        chdir("../../../");
                        foreach ($value as $service) {
                            $cwd = getcwd() == "/" ? "" : getcwd();
                            $paths[] = $cwd . Database::getInstance()->getGeoService($service)->getPath();
                        }
                        $currentMap->include = $paths;
                        break;
                }
            }
        }
        return $currentMap;
    }
}