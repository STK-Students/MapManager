<?php

use Doctrine\Common\Collections\ArrayCollection;
use MapFile\Model\Composite;
use MapFile\Model\Style;
use MapFile\Model\LayerClass;
use MapFile\Model\Map;

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$mapFileLoc/Model/Composite.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/NestedAttributeUpdater.php";

class LabelDeserializer
{
    const validArguments = array('name');

    public static function handleLabel(LayerClass $currentStyle, array $updateData): LayerClass
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



