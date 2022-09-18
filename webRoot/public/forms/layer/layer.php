<html lang="de">
<header>
    <title>Layer Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="layer.css">
    <link rel="stylesheet" href="/.media/fontAndNavbar.css">
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <script src="../formSubmitter.js" defer></script>
    <script src="../formFiller.js" defer></script>
    <script src="../TableBuilder.js" defer></script>
    <script src="layer.js"></script>
    <?php

    use MapFile\Model\Layer;

    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Layer/LayerSerializer.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/Layer/LayerDeserializer.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/Layer.php";

    if (!isset($_GET['serviceUUID'])) {
        echo "Diese Seite können Sie nicht direkt aufrufen.";
        echo "<br> Bitte rufen sie die <a href='/public/home/home.php'>Homepage</a> auf.";
        die;
    }

    $map = MapFileHandler::loadMapByUUID($_GET['serviceUUID']);

    $layerIndex = $_GET['rowNumber'];
    $layer = $map->layer->get($layerIndex);
    if ($layer == null) {
        $layer = new Layer();
    }
    $json = json_encode(LayerSerializer::layerToJSON($layer));
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
<h1>Ebeneneinstellungen</h1>
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

        <div class="row">
            <div class='col-3 gy-1'>
                <label for='template'>Vorlagendatei</label>
                <select class='form-select' name='template' id='template'>
                    <option value="">Keine</option>
                    <?php
                    $templatePath = Config::getConfig()['directories']['templates'];
                    chdir("../../../");
                    $templatePath = realpath($templatePath);
                    $files = scandir($templatePath);
                    $files = array_diff($files, array('.', '..'));
                    foreach ($files as $file) {
                        echo "<option value='" . $file . "'>" . $file . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-2 gy-1">
                <label for="group">Gruppe</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="group">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#groupModal"><b>?</b></button>
                </div>
            </div>

            <div class="col-3">
                <label class="form-label" for="tolerance">Genauigkeit</label>
                <div class="input-group has-validation">
                    <input type="number" class="form-control" id="tolerance">
                    <span class="input-group-text">in</span>
                    <select class="form-control form-select" id="toleranceunits" required>
                        <option value="PIXELS">Pixel</option>
                        <option value="METERS">Meter</option>
                        <option value="KILOMETERS">Kilometer</option>
                        <option value="DD">Dezimalgrad</option>
                        <option value="FEET">Feet</option>
                        <option value="INCHES">Inches</option>
                        <option value="MILES">Miles</option>
                        <option value="NAUTICALMILES">Seemeile</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <label for="opacity">Transparenz</label>
                <input id="opacity" type="number" class="form-control">
            </div>
            <div class="col-2">
                <label for="maxscaledenom">Minimaler Maßstab</label>
                <input id="maxscaledenom" type="number" class="form-control">
            </div>
        </div>
        <br>
        <h3>Datenquelle</h3>
        <!--Start Row 2-->
        <div class="row">
            <div class="col-3 gy-3">
                <label for="connectiontype">Verbindungstyp</label>
                <select class="form-select" name="connectiontype" id="connectiontype">
                    <option value="POSTGIS">PostgreSQL Datenbank</option>
                </select>
            </div>
            <div class="col-3 gy-3">
                <label for="connection-host">Datenbankserver-IP / Hostname</label>
                <input type="text" class="form-control" id="connection-host" required>
            </div>
            <div class="col-2 gy-3">
                <label for="connection-port">Datenbankserver-Port</label>
                <input type="number" class="form-control" id="connection-port" required>
            </div>
        </div>
        <!-- Start Row 3-->
        <div class="row">
            <div class="col-4 gy-1">
                <label for="connection-dbname">Datenbank-Name</label>
                <input type="text" class="form-control" id="connection-dbname" required>
            </div>
            <div class="col-3 gy-1">
                <label for="connection-user">Datenbank-Nutzername</label>
                <input type="text" class="form-control" id="connection-user" required>
            </div>
            <div class="col-4 gy-1">
                <label for="connection-password">Datenbank-Passwort</label>
                <input type="text" class="form-control" id="connection-password" required>
            </div>
        </div>
        <!--Start Row 3-->
        <div class="row">
            <div class="col-12 gy-1">
                <label for="data">SQL Befehl</label>
                <div class="input-group mb-3 has-validation">
                    <input type="text" class="form-control" id="data">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#dataModal"><b>?</b></button>
                    <div class="invalid-feedback">Diese Angabe ist Pflicht.</div>
                </div>
            </div>
        </div>
    </form>

    <br>
    <!-- CLASS Style Table -->
    <h3 style="display: inline-block">Style-Klassen</h3>
    <button style="margin-left: 15px; margin-bottom: 7px" data-bs-toggle="modal" data-bs-target="#addClassModal"
            class="btn btn-outline-success">+
    </button>
    <table id="classTable" class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Aktionen</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Modals -->

    <!-- CLASS Style Table -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModal">Style-Klasse hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="className">Name der Style-Klasse</label>
                        <input type="text" class="form-control" id="className">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" id="classCreatorButton" class="btn btn-primary" data-bs-dismiss="modal">
                        Erstellen
                    </button>
                </div>
            </div>
        </div>
    </div>

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
