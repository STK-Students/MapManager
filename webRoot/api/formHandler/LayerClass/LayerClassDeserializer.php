<?php

use MapFile\Model\Label;
use MapFile\Model\Style;
use MapFile\Model\LayerClass;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$mapFileLoc/Model/Label.php";
require_once "$mapFileLoc/Model/Style.php";
require_once "$mapFileLoc/Model/Composite.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/NestedAttributeUpdater.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Style/StyleDeserializer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Label/LabelDeserializer.php";

class LayerClassDeserializer
{
    const validArguments = array('name');

    public static function handleLayerClass(LayerClass $currentStyle, array $updateData): LayerClass
    {
        foreach (self::validArguments as $argument) {
            $value = $updateData[$argument];
            if (!isset($value)) {
                // Explicitly unset arguments that have not been defined by the user.
                $currentStyle->$argument = null;
            } else {
                // Some arguments require special conversion, e.g. because they need to be merged into a new data type.
                switch ($argument) {
                    default:
                        $currentStyle->$argument = $value;
                        break;
                    case 'style':
                        $addHandler = function ($data) {
                            StyleDeserializer::handleStyle(new Style(), $data);
                        };
                        NestedAttributeUpdater::setNestedAttribute($currentStyle->style, $value, $addHandler);
                        break;
                    case 'label':
                        $addHandler = function ($data) {
                            LabelDeserializer::handleLabel(new Label(), $data);
                        };
                        NestedAttributeUpdater::setNestedAttribute($currentStyle->label, $value, $addHandler);
                        break;
                }
            }
        }
        return $currentStyle;
    }
}



