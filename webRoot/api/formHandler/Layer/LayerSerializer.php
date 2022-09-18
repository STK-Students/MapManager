<?php

use MapFile\Model\Layer;

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Style/StyleSerializer.php";

class LayerSerializer
{
    /**
     * Converts the layer object into a JSON format that can be understood by the JS for filling the forms.
     * @param Layer $layer map to convert
     * @return array array for serialization into JSON for the website JS
     */
    static function layerToJSON(Layer $layer): array
    {
        $json = [];
        foreach (get_object_vars($layer) as $key => $value) {
            if (isset($value)) {
                switch ($key) {
                    default:
                        $json[$key] = $value;
                        break;
                    case 'connection':
                        $parts = explode(" ", $value);
                        foreach ($parts as $part) {
                            $keyValue = explode("=", $part);
                            $json[$key][$keyValue[0]] = $keyValue[1];
                        }
                        break;
                    case 'class':
                        foreach ($value as $layer) {
                            $json['class'][] = StyleSerializer::styleToJSON($layer);
                        }
                        break;
                }
            }
        }
        return $json;
    }
}