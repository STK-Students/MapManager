<?php

use MapFile\Model\Layer;
use MapFile\Model\LayerClass;
use MapFile\Model\Style;

require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Style.php";

class StyleSerializer
{
    /**
     * Converts the styleClass object into a JSON format that can be understood by the JS for filling the forms.
     * @param LayerClass $style map to convert
     * @return array JSON for the website JS
     */
    static function styleToJSON(Style $style): array
    {
        $json = [];
        foreach (get_object_vars($style) as $key => $value) {
            if (isset($value)) {
                switch ($key) {
                    default:
                        $json[$key] = $value;
                        break;
                }
            }
        }
        return $json;
    }
}