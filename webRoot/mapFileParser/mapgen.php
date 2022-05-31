<html>
<body>
<p>MapOutput</p>
</body>
</html>
<?php

$mapFileLoc = "../dependencies/MapFileParser";
$doctrineLoc = "../dependencies/Doctrine";
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

use MapFile\Model\Layer;
use MapFile\Model\Map;

$map = new Map();
$map->name = 'Erste Test Map';
$map->projection = "EPSG:2856";

$layer = new Layer();
$layer->name = 'my-layer';
$layer->type = 'POLYGON';
$layer->status = 'ON';

$map->layer->add($layer);

$mapfile = (new MapFile\Writer\Map())->write($map);
echo nl2br($mapfile);
?>
