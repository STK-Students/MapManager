<?php
session_start();

$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once("{$mapFileLoc}/Writer/WriterInterface.php");
require_once("{$mapFileLoc}/Writer/Writer.php");
require_once("{$mapFileLoc}/Writer/Map.php");
require_once("{$mapFileLoc}/Writer/Symbol.php");
require_once("{$mapFileLoc}/Writer/Layer.php");
require_once("{$mapFileLoc}/Model/Layer.php");
require_once("{$mapFileLoc}/Model/Symbol.php");
require_once("{$mapFileLoc}/Model/Map.php");
require_once("{$doctrineLoc}/Common/Collections/Selectable.php");
require_once("{$doctrineLoc}/Common/Collections/Collection.php");
require_once("{$doctrineLoc}/Common/Collections/ArrayCollection.php");

$mapUUID = $_SESSION['currentMapUUID'];
if (isset($_SESSION['map'])) {
    $map = unserialize($_SESSION['map']);
} else {
    $map = '';
}

$mapFilePath =  Database::getInstance()->getOGCService($mapUUID)->getMapFilePath();
mkdir($mapFilePath, 0555, true);
$file = fopen($mapFilePath, "w");
fwrite($file, (new MapFile\Writer\Map())->write($map));
