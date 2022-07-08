<?php

use MapFile\Exception\FileException;
use MapFile\Exception\UnsupportedException;
use MapFile\Model\Map;
use MapFile\Parser\Map as MapParser;

$mapFileLoc = "../../dependencies/MapFileParser/";
$doctrineLoc = "../../dependencies/Doctrine";
require_once "$mapFileLoc/Exception/FileException.php";
require_once "$mapFileLoc/Parser/ParserInterface.php";
require_once "$mapFileLoc/Parser/Parser.php";
require_once "$mapFileLoc/Model/Map.php";
require_once "$mapFileLoc/Parser/Map.php";
require_once "$mapFileLoc/Model/Layer.php";
require_once "$doctrineLoc/Common/Collections/Selectable.php";
require_once "$doctrineLoc/Common/Collections/Collection.php";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

/**
 * Loads a mapfile.
 * @param $filePath string path to a mapfile relative to the webRoot
 * @return Map the loaded mapfile.
 */
function loadMapFromFile(string $filePath): Map
{
    try {
        return (new MapParser($filePath))->parse($filePath);
    } catch (UnsupportedException|FileException  $e) {
        die("Ein Fehler ist beim Laden des Mapfiles aufgetreten" . $e);
    }
}