<?php

use MapFile\Model\Map;
use MapFile\Parser\Map as MapParser;
use MapFile\Exception\FileException;
use MapFile\Exception\UnsupportedException;

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$mapFileLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser";
$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once("$mapFileLoc/Writer/WriterInterface.php");
require_once("$mapFileLoc/Writer/Writer.php");
require_once("$mapFileLoc/Writer/Map.php");
require_once("$mapFileLoc/Writer/Symbol.php");
require_once("$mapFileLoc/Writer/Layer.php");
require_once("$mapFileLoc/Model/Layer.php");
require_once("$mapFileLoc/Model/Symbol.php");
require_once("$mapFileLoc/Model/Map.php");
require_once "$mapFileLoc/Parser/ParserInterface.php";
require_once "$mapFileLoc/Parser/Parser.php";
require_once "$mapFileLoc/Parser/Map.php";
require_once "$mapFileLoc/Exception/FileException.php";
require_once("$doctrineLoc/Common/Collections/Selectable.php");
require_once("$doctrineLoc/Common/Collections/Collection.php");
require_once("$doctrineLoc/Common/Collections/ArrayCollection.php");

class MapFileHandler
{
    /**
     * Writes the service with the currentServiceUUID to a MapFile.
     * @param $map which is going to be saved
     * @param string $uuid serviceUUID
     * @return void
     */
    static function writeMapFile($map, string $uuid): void
    {
        if ($map === null) {
            $map = '';
        }
        $path = self::getPath($uuid);
        $file = fopen($path, "w");
        fwrite($file, (new MapFile\Writer\Map())->write($map));
    }


    /**
     * Loads a Map.
     * @param string $uuid UUID of the service that represents the map
     * @return Map
     */
    static function loadMapByUUID(string $uuid): Map
    {
        $mapFilePath = Database::getInstance()->getGeoService($uuid)->getPath();
        return self::loadMapFromFile($mapFilePath);
    }

    /**
     * Loads a Map.
     * @param $filePath string path to a mapfile relative to the webRoot
     * @return Map the mapfile as a Map.
     */
    static function loadMapFromFile(string $filePath): Map
    {
        try {
            return (new MapParser($filePath))->parse($filePath);
        } catch (UnsupportedException|FileException  $e) {
            error_log($e->getMessage());
            die("Ein Fehler ist beim Laden des Mapfiles aufgetreten" . $e);
        }
    }

    /**
     * Deletes the MapFile with the given path
     * @param string $path the file to delete
     * @return void
     */
    static function deleteMapFile(string $path)
    {
        unlink($path);
    }

    /**
     * Gets the path of the current service's MapFile.
     * The current service is determined through the session variable 'currentServiceUUID'.
     * @param string $mapUUID UUID of the service that represents the map
     * @return string path to the MapFile
     */
    private static function getPath(string $mapUUID): string
    {
        $service = Database::getInstance()->getGeoService($mapUUID);
        $groupPath = $service->getGroupPath();
        $mapFilePath = $service->getPath();

        if (!file_exists($groupPath)) {
            mkdir($groupPath);
        }
        return $mapFilePath;
    }
}
