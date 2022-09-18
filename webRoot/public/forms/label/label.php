<html lang="de">
<header>
    <title>Layer Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="label.css">
    <link rel="stylesheet" href="/.media/fontAndNavbar.css">
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <script src="../formSubmitter.js" defer></script>
    <script src="../formFiller.js" defer></script>
    <script src="../TableBuilder.js" defer></script>
    <script src="label.js"></script>
    <?php

    use MapFile\Model\Label;

    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Label/LabelSerializer.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Label/LabelDeserializer.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Label.php";

    if (!isset($_GET['serviceUUID'])) {
        echo "Diese Seite können Sie nicht direkt aufrufen.";
        echo "<br> Bitte rufen sie die <a href='/public/home/home.php'>Homepage</a> auf.";
        die;
    }

    $map = MapFileHandler::loadMapByUUID($_GET['serviceUUID']);

    $layerIndex = $_GET['layerIndex'];
    $classIndex = $_GET['layerClassIndex'];
    $labelIndex = $_GET['layerClassIndex'];
    $labels = $map->layer->get($layerIndex)->class->get($classIndex);
    $label =  $labels->get($labelIndex);
    if ($label == null) {
        $label = new Label();
    }
    $json = json_encode(LabelSerializer::labelToJSON($label));
    echo "<script type=\"text/javascript\" defer>phpHook(" . $json . ");</script>";
    ?>
</header>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="/.media/stadt-köln-logo.svg" alt="" height="50">
            <p class="kölnFontBold navbarSubText">MapManager</p>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php
                    echo '<a href="/public/forms/map/map.php?serviceUUID=' . $_GET['serviceUUID'] . '" class="btn
                        btn-outline-secondary nav-link active"
                        style="margin-top:10px; margin-left:20px">Zurück
                        zu den Dienst-Einstellungen</a>';
                    ?>

                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<h1>Styles</h1>
<div class="container-lg">
    <form name="Eingabe" id='layerForm' class="needs-validation">
        <br>
        <h3>Generelle Einstellungen</h3>

        <!--Start Row 1-->

        <div class="row">
            <div class="col-4 gy-1">
                <label for="name">Technischer Name</label>
                <input type="text" class="form-control" id="name" placeholder="Layer Name"
                       aria-describedby="nameHelp">
            </div>
            <div class="col-2 gy-1">
                <label for="type">Typ</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="POINT">Punkt</option>
                    <option value="LINE">Linie</option>
                    <option value="POLYGON">Polygon</option>
                </select>
            </div>
        </div>
        <br>

    <div class="modal modal-lg" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="angleModalTitle">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <p> Der Befehl muss die Form "&ltSpaltenname&gt from &ltTabellenname&gt" haben, wobei
                        "&ltSpaltenname&gt" der Name der Spalte ist,
                        die die Geometrieobjekte enthält, und "&ltTabellenname&gt" der Name der Tabelle ist, aus der
                        die Geometriedaten gelesen werden. SELECT ist als erstes Wort des Befehls nicht notwendig.</p>
                    Beispiel:<br>
                    <code>the_geom from (select id_nr as gid, the_geom, bwnr, teil_bwnr, tw_name FROM public.bauwerke)
                        as foo using unique gid using srid=25832</code>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-lg" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupModalTitle">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    Name einer Gruppe, zu der diese Ebene gehört. Der Gruppenname kann dann als regulärer Ebenenname in
                    den Vorlagendateien referenziert werden, wodurch Dinge wie das Ein- und Ausschalten einer Gruppe von
                    Ebenen auf einmal möglich sind.
                </div>
            </div>
        </div>
    </div>

</body>
</html>