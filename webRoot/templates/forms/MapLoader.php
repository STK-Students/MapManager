<?php

use MapFile\Exception\FileException;
use MapFile\Exception\UnsupportedException;
use MapFile\Model\Map;
use MapFile\Parser\Map as MapParser;

$mapFileLoc = "../../dependencies/MapFileParser/";
$doctrineLoc = "../../dependencies/Doctrine";
require "$mapFileLoc/Exception/FileException.php";
require "$mapFileLoc/Parser/ParserInterface.php";
require "$mapFileLoc/Parser/Parser.php";
require "$mapFileLoc/Model/Map.php";
require "$mapFileLoc/Parser/Map.php";
require "$mapFileLoc/Model/Layer.php";
require "$doctrineLoc/Common/Collections/Selectable.php";
require "$doctrineLoc/Common/Collections/Collection.php";
require "$doctrineLoc/Common/Collections/ArrayCollection.php";

/**
 * Loads a mapfile.
 * @param $filePath string path to a mapfile relative to the webRoot
 * @return Map the loaded mapfile.
 */
function loadMapFromFile(string $filePath): Map
{
    try {
        return (new MapParser($filePath))->parse($filePath);
    } catch (UnsupportedException $e) {
        die("Ein Fehler ist beim Laden des Mapfiles aufgetreten" . $e);
    }
}