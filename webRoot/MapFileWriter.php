<?php
class MapFileWriter
{
    public string $serializedMapFile;

    public function __construct($map)
    {
        $mapFileLoc = "dependencies/MapFileParser";
        $doctrineLoc = "dependencies/Doctrine";
        require_once("{$mapFileLoc}/Writer/WriterInterface.php");
        require_once("{$mapFileLoc}/Writer/Writer.php");
        require_once("{$mapFileLoc}/Writer/Map.php");

        $this->serializedMapFile = (new MapFile\Writer\Map())->write($map);

    }

    public function saveMapFile($path): void
    {
        $myfile = fopen($path, "w");
        fwrite($myfile, $this->serializedMapFile);
    }
}
