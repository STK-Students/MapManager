<html>
    <head>
        <title>PHP Datenübergabe</title>
    </head>
    <body>
        <h2>PHP Datenübergabe aus edit.html Test</h2>
    </body>
</html>

<?php

    if(isset($_POST['submit'])){

        $name = $_POST["name"];
        echo ("$name <br>\n");

        $status = $_POST["status"];
        echo ("$status <br>\n");

        $size = $_POST["size"];
        echo ("$size <br>\n");

        #$symbolset = $_POST["symbolset"];
        #echo ".$symbolset";

        $extent = $_POST["extent"];
        echo "$extent <br>\n";

        $units = $_POST["units"];
        echo "$units <br>\n";
        
        #$shapepath = $_POST["shapepath"];
        #echo ".$shapepath";<br>

        $imagecolor_red = $_POST["red"];
        echo "$red <br>\n";

        $imagecolor_green = $_POST["green"];
        echo "$green <br>\n";

        $imagecolor_blue = $_POST["blue"];
        echo "$blue <br>\n";

        #$fontset = $_POST["fontset"];
        #echo ".$fontset";

        #$imageurl = $_POST["Imageurl"];
        #echo ".$Imageurl";

        $layername = $_POST["layername"];
        echo "$layername <br>\n";

        $layertype = $_POST["layertype"];
        echo "$layertype <br>\n";

        $layerstatus = $_POST["layerstatus"];
        echo "$layerstatus <br>\n";

        $layerdata = $_POST["layerdata"];
        echo "$layerdata <br>\n";

    }

?>