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

/**
 * An array of all valid arguments. Required so the value of the
 * $_SESSION variable cannot be set freely by the user.
 */
$validArguments = array('name', 'scaledenom', 'angle', 'size', 'maxsize', 'extent');

$map = isset($_SESSION['map']) ? unserialize($_SESSION['map']) : new Map();
// This is the content of the POST request submitted to this page
$data = json_decode(trim(file_get_contents('php://input')), true);

foreach ($validArguments as $argument) {
    $value = $data[$argument];
    if (isset($value)) {

        // Handle edge cases first
        switch ($argument) {
            case 'size':
                $map->size = array($value['x'], $value['y']);
                break;
            case 'extent':
                $extent = array($value['minx'], $value['miny'], $value['maxx'], $value['maxy']);
                $map->extent = array_map("floatval", $extent);
                break;
        }

        // Handle all other cases
        $map->$argument = $data[$argument];
    }
}

$_SESSION['map'] = serialize($map);
print(json_encode($map));