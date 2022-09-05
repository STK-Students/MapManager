<?php

use MapFile\Model\Layer;
use MapFile\Model\LayerClass;

class LayerClassSerializer
{
    /**
     * Converts the styleClass object into a JSON format that can be understood by the JS for filling the forms.
     * @param LayerClass $style map to convert
     * @return string JSON for the website JS
     */
    static function layerClassToJSON(LayerClass $style): string
    {
        $json = [];
        foreach (get_object_vars($style) as $key => $value) {
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
                }
            }
        }
        return json_encode($json);
    }
}