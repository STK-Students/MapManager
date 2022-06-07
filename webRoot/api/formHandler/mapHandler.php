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

$validArguments = array('name', 'scaledenom', 'angle', 'size', 'maxsize');
foreach ($validArguments as $argument) {
    if (isset($_GET[$argument])) {
        $map->$argument = $_GET[$argument];
    }
}

$_SESSION['map'] = serialize($map);
print(json_encode($map));