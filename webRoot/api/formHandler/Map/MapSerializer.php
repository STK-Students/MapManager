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

class MapSerializer
{

    /**
     * Converts the map object into a JSON format that can be understood by the JS for filling the forms.
     * @param Map $map map to convert
     * @return string JSON for the website JS
     */
    static function mapToJSON(Map $map): string
    {
        $json = [];
        foreach (get_object_vars($map) as $key => $value) {
            if (isset($value)) {
                switch ($key) {
                    default:
                        $json[$key] = $value;
                        break;
                    case 'size':
                        $json['size'] = array("x" => $value[0], "y" => $value[1]);
                        break;
                    case 'extent':
                        $json['extent'] = array("minx" => $value[0], "miny" => $value[1], "maxx" => $value[2], "maxy" => $value[3]);
                        break;
                    case 'layer':
                        self::convertLayerToJSON($json, $value);
                        break;
                    case 'status':
                        $json['status'] = $value == "ON" ? 1 : 0;
                        break;
                    case 'include':
                        $json['include'] = [];
                        foreach ($value as $service) {
                            $pathParts = explode("/", $service);
                            $length = count($pathParts);
                            $fileName = $pathParts[$length - 1];
                            $serviceUUID = explode(".", $fileName);
                            $json['include'][] = $serviceUUID[0];
                        }
                        break;
                }
            }
        }
        return json_encode($json);
    }

    static function convertLayerToJSON(&$json, $layers)
    {
        foreach ($layers as $layer) {
            $layerJson = [];
            foreach (get_object_vars($layer) as $key => $value) {
                $layerJson[$key] = $value;
            }
            $json['layers'][] = $layerJson;
        }
    }
}