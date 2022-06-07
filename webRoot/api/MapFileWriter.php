<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header("Location: http://localhost/templates/login/login.php");
    die("Sie müssen sich einloggen.");
}

$mapFileLoc = "../dependencies/MapFileParser";
$doctrineLoc = "../dependencies/Doctrine";
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

if (isset($_SESSION['map'])) {
    $map = unserialize($_SESSION['map']);
    echo $_SESSION['map'];
    $writer = new MapFileWriter($map);
    $writer->saveMapFile('./output.map');
} else {
    echo "no map";
}


class MapFileWriter
{
    private string $serializedMapFile;

    public function __construct($map)
    {
        $this->serializedMapFile = (new MapFile\Writer\Map())->write($map);
    }

    public function saveMapFile($path): void
    {
        $file = fopen($path, "w");
        fwrite($file, $this->serializedMapFile);
    }
}
