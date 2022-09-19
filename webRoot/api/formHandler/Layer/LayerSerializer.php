<?php

use MapFile\Model\Layer;

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/LayerClass/LayerClassSerializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Style/StyleSerializer.php";

class LayerSerializer
{
    /**
     * Converts the layer object into a JSON format that can be understood by the JS for filling the forms.
     * @param Layer $class map to convert
     * @return array array for serialization into JSON for the website JS
     */
    static function layerToJSON(Layer $class): array
    {
        $json = [];
        foreach (get_object_vars($class) as $key => $value) {
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
                        foreach ($value as $class) {
                            $json['class'][] = LayerClassSerializer::layerClassToJSON($class);
                        }
                        break;
                }
            }
        }
        return $json;
    }
}