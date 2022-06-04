<html lang="de">
<head>
    <title>PHP Datenübergabe</title>
</head>
<body>
<h2>PHP Datenübergabe aus edit.html Test</h2>
</body>
</html>

<?php

use MapFile\Model\Layer;
use MapFile\Model\Map;

$mapFileLoc = "dependencies/MapFileParser";
$doctrineLoc = "dependencies/Doctrine";
require("{$mapFileLoc}/Model/Map.php");
require("{$mapFileLoc}/Model/Layer.php");
require("{$mapFileLoc}/Writer/WriterInterface.php");
require("{$mapFileLoc}/Writer/Writer.php");
require("{$mapFileLoc}/Writer/Map.php");
require("{$mapFileLoc}/Writer/Projection.php");
require("{$mapFileLoc}/Writer/Layer.php");
require "{$doctrineLoc}/Common/Collections/Selectable.php";
require "{$doctrineLoc}/Common/Collections/Collection.php";
require "{$doctrineLoc}/Common/Collections/ArrayCollection.php";


if (isset($_POST['submit'])) {

    $map = new Map();
    $map->name = $_POST["name"];
    $map->size = $_POST["size"];
    $map->maxsize = $_POST["maxsize"];
    $map->units = $_POST["units"];
    $map->scaledenom = $_POST["scaledenom"];

    $layer = new Layer();
    $layer->name = 'my-layer';
    $layer->type = 'POLYGON';
    $layer->status = 'ON';

    $map->layer->add($layer);


    $status = $_POST["status"];

    $size = $_POST["size"];

    #$symbolset = $_POST["symbolset"];

    $extent = $_POST["extent"];

    $units = $_POST["units"];

    #$shapepath = $_POST["shapepath"];

    $imagecolor_red = $_POST["red"];

    $imagecolor_green = $_POST["green"];

    $imagecolor_blue = $_POST["blue"];

    #$fontset = $_POST["fontset"];

    #$imageurl = $_POST["Imageurl"];

    $layername = $_POST["layername"];

    $layertype = $_POST["layertype"];

    $layerstatus = $_POST["layerstatus"];

    $layerdata = $_POST["layerdata"];

}

class MapFileParser
{

    public function __construct($map)
    {
        $mapFileLoc = "dependencies/MapFileParser";
        $doctrineLoc = "dependencies/Doctrine";
        require("{$mapFileLoc}/Writer/WriterInterface.php");
        require("{$mapFileLoc}/Writer/Writer.php");
        require("{$mapFileLoc}/Writer/Map.php");

        $mapfile = (new MapFile\Writer\Map())->write($map);
        $myfile = fopen("testfile.txt", "w");
        fwrite($myfile, $mapfile);
    }
}

?>