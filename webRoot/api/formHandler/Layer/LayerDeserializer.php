<?php

use Doctrine\Common\Collections\ArrayCollection;
use MapFile\Model\Composite;
use MapFile\Model\Layer;
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

class LayerDeserializer
{
    const validArguments = array('name', 'type', 'group', 'connectiontype', 'connection', 'data', 'template', 'tolerance', 'toleranceunits', 'maxscaledenom', 'opacity');

    public static function handleLayer(Layer $currentLayer, array $updateData): Layer
    {
        foreach (self::validArguments as $argument) {
            $value = $updateData[$argument];
            if (!isset($value)) {
                // Explicitly unset arguments that have not been defined by the user.
                $currentLayer->$argument = null;
            } else {
                // Some arguments require special conversion, e.g. because they need to be merged into a new data type.
                switch ($argument) {
                    default:
                        $currentLayer->$argument = $value;
                        break;
                    case 'connection':
                        $currentLayer->connection = "user={$value["user"]} password={$value["password"]} dbname={$value["dbname"]} host={$value["host"]} port={$value["port"]}";
                        break;
                    case 'styleClasses':
                        $addHandler = function ($data) {
                            LayerDeserializer::handleLayer(new LayerClass(), $data);
                        };
                        NestedAttributeUpdater::setNestedAttribute($currentLayer->class, $value, $addHandler);
                        break;
                }
            }
        }
        return $currentLayer;
    }
}



