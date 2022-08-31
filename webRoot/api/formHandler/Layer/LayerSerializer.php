<?php

use MapFile\Model\Layer;

class LayerSerializer
{
    /**
     * Converts the layer object into a JSON format that can be understood by the JS for filling the forms.
     * @param Layer $layer map to convert
     * @return string JSON for the website JS
     */
    static function layerToJSON(Layer $layer): string
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
                }
            }
        }
        return json_encode($json);
    }
}