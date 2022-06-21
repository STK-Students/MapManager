<?php

use MapFile\Exception\FileException;
use MapFile\Exception\UnsupportedException;
use MapFile\Parser\Map as MapParser;

$mapFileLoc = "../../dependencies/MapFileParser";
$doctrineLoc = "../../dependencies/Doctrine";
require("{$mapFileLoc}/Exception/FileException.php");
require("{$mapFileLoc}/Parser/ParserInterface.php");
require("{$mapFileLoc}/Parser/Parser.php");
require("{$mapFileLoc}/Model/Map.php");
require("{$mapFileLoc}/Parser/Map.php");
require("{$mapFileLoc}/Model/Layer.php");
require "{$doctrineLoc}/Common/Collections/Selectable.php";
require "{$doctrineLoc}/Common/Collections/Collection.php";
require "{$doctrineLoc}/Common/Collections/ArrayCollection.php";

/**
 * Loads a map into the user's session.
 * @param $file string path to a mapfile relative to the webRoot
 * @return void
 */
function loadMapFile(string $file) {
    $file = "../../" . $file;
    try {
        $map = (new MapParser($file))->parse($file);
        $_SESSION['map'] = serialize($map);
    } catch (UnsupportedException|FileException $e) {
        echo "Ein Fehler ist beim Laden des Mapfiles aufgetreten" . $e;
    }
}
