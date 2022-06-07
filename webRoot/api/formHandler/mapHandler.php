<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    die("Sie müssen sich einloggen.");
}

use MapFile\Model\Map as Map;

$mapFileLoc = "../../dependencies/MapFileParser";
$doctrineLoc = "../../dependencies/Doctrine";
require("{$mapFileLoc}/Model/Map.php");
require("{$mapFileLoc}/Model/Layer.php");
require "{$doctrineLoc}/Common/Collections/Selectable.php";
require "{$doctrineLoc}/Common/Collections/Collection.php";
require "{$doctrineLoc}/Common/Collections/ArrayCollection.php";

$map = isset($_SESSION['map']) ? unserialize($_SESSION['map']) : new Map();

$validArguments = array('name', 'scaledenom', 'angle', 'size', 'maxsize', 'sizeX', 'sizeY');
foreach ($validArguments as $argument) {
    $value = $_GET[$argument];
    if (isset($value)) {

        // Handle edge cases first
        if ($argument == 'sizeX') {
            $max = array($value);
            echo $max[0];
        }
        if ($argument == 'sizeY') {
            $max[] = $value;
            echo $max[1];
            $map->size = $max;
        }

        // Handle all other cases
        $map->$argument = $_GET[$argument];
    }
}

$_SESSION['map'] = serialize($map);
print(json_encode($map));