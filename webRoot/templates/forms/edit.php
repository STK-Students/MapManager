<!--
 Custom Forms Format:

 All forms on this page use a custom system for validation and submission to the backend.
 Make sure the ID's of the forms equal the attribute names of the MapFile-PHP-Library objects.
 This system uses the ID's of all <input> elements to determine if a single input belongs to a group of inputs.
 Such groups are serialized to nested objects inside a parent payload that also contains any inputs without groups.
 This structure makes the parsing in the backend easier.

 Use the format "<groupID>-<elementID>" to signal which inputs belong together.
 Make sure you NEVER assign a group name as the name of an unrelated input.
 -->


<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8"> <!--Ermöglicht einfache Eingabe von Sonderzeichen-->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="edit_style.css">
    <link rel="stylesheet" href="/.media/fontAndNavbar.css">
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/jQuery/jQuery.js"></script>
    <script src="formSubmitter.js" defer></script>
    <script src="formFiller.js"></script>
    <script src="edit.js" defer></script>
    <?php
    session_start();

    /**
     * Fills the forms on this page if there is already data for them.
     */
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/ServiceConverter.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

    if (!isset($_GET['uuid'])) {
        die("Diese Seite muss mit einer MapUUID aufgerufen werden!");
    }
    $mapUUID = $_GET['uuid'];

    if (isset($_SESSION['currentServiceUUID']) && $_SESSION['currentServiceUUID'] != $mapUUID || !isset($_SESSION['map'])) {
        $mapFilePath = Database::getInstance()->getOGCService($mapUUID)->getPath();
        $map = MapFileHandler::loadMapFromFile($mapFilePath);
        $_SESSION['currentServiceUUID'] = $mapUUID;
    } else {
        $map = unserialize($_SESSION['map']);
    }
    $json = mapToJSON($map);

    echo "<script type=\"text/javascript\" defer>fillForms(" . $json . ");</script>";
    ?>
    <title>Dienst bearbeiten</title>
</head>
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
                    <a class="nav-link active" href="/templates/home/home.php">Zurück zur Übersicht</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<h1>Dienst bearbeiten</h1>
<div class="container-lg">
    <form name="Eingabe" id='mapForm' class="needs-validation">
        <h2>Allgemeine Einstellungen</h2>

        <div class="row"><!--Start Row 1-->

            <div class="col-4">
                <label for="name">Kartenname</label>
                <input type="text" class="form-control" id="name" placeholder="Grundwasserqualität"
                       aria-describedby="nameHelp" required>
                <small id="nameHelp" class="form-text text-muted">
                    Präfix für die Karte, Maßstabsleiste und Legende.
                </small>
                <div class="invalid-feedback">
                    Diese Angabe ist Pflicht.
                </div>
            </div>

            <div class="col-2">
                <label for="scaledenom">Maßstab</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="basic-addon1">1:</span>
                    <input type="number" min="1" class="form-control" id="scaledenom" placeholder="10000" required>
                    <div class="invalid-feedback">
                        Diese Angabe ist Pflicht.
                    </div>
                </div>
            </div>

            <div class="col-2">
                <label for="units">Koordinateneinheit</label>
                <select class="form-select" id="units" required>
                    <option value="meters">Meter</option>
                    <option value="kilometers">Kilometer</option>
                    <option value="dd">Dezimalgrad</option>
                    <option value="feet">Feet</option>
                    <option value="inches">Inches</option>
                    <option value="miles">Miles</option>
                    <option value="nauticalmiles">Seemeile</option>
                </select>
            </div>

            <div class="col-2">
                <label for="angle">Kartendrehung</label>
                <div class="input-group mb-3 has-validation">
                    <input type="number" class="form-control" id="angle" value="0" aria-describedby="angleHelpButton"
                           required>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#angleModal"><b>?</b></button>
                    <div class="invalid-feedback">Diese Angabe ist Pflicht.</div>
                </div>
            </div>

        </div><!--End Row 1-->

        <div class="row"><!--Start Row 2-->

            <div class="col-2 gy-1">
                <label for="size-x">Auflösung</label>
                <div class="input-group has-validation">
                    <input type="number" class="form-control" id="size-x" placeholder="1920" required>
                    <span class="input-group-text" id="basic-addon2">x</span>
                    <input type="number" class="form-control" id="size-y" placeholder="1080" required>
                    <div class="invalid-feedback">
                        Diese Angabe ist Pflicht.
                    </div>
                </div>
            </div>

            <div class="col-2 gy-1">
                <label for="maxsize">Maximale Auflösung</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="maxsize" placeholder="4096" value="4096">
                    <span class="input-group-text" id="basic-addon2">Pixel</span>
                </div>
            </div>


            <div class="col-3 gy-1">
                <label for="extent-minx">Räumliche Ausdehnung</label>
                <div class="input-group has-validation">
                    <input type="number" class="form-control" id="extent-minx" aria-describedby="extentHelpminX"
                           placeholder="min. X " required>
                    <input type="number" class="form-control" id="extent-miny" aria-describedby="extentHelpminY"
                           placeholder="min. Y" required>
                    <input type="number" class="form-control" id="extent-maxx" aria-describedby="extentHelpmaxX"
                           placeholder="max. X" required>
                    <input type="number" class="form-control" id="extent-maxy" aria-describedby="extentHelpmaxY"
                           placeholder="max. Y" required>
                    <div class="invalid-feedback">
                        Diese Angabe ist Pflicht.
                    </div>
                </div>
            </div>

        </div><!--End Row 2-->
    </form>

    <br>
    <hr>
    <h3 style="display: inline-block">Ebenen</h3>
    <button style="margin-left: 15px; margin-bottom: 7px" data-bs-toggle="modal" data-bs-target="#addLayerModal"
            class="btn btn-outline-success">+
    </button>
    <table id="layerTable" class="table">
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


    <!-- Submit Button Code -->
    <br>
    <button type="button" id="submitAPIButton" class="btn btn-success">Speichern</button>

    <!-- INFORMATION MODALS -->

    <div class="modal modal-lg" id="angleModal" tabindex="-1" aria-labelledby="angleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="angleModalTitle">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    Winkel, der in Grad angegeben wird, um die Karte zu drehen. Standardwert ist 0. Die gerenderte Karte
                    wird im Uhrzeigersinn gedreht. Im Folgenden finden Sie wichtige Hinweise:
                    <br>
                    <br>

                    <ul>
                        <li>
                            Erfordert ein PROJECTION-Objekt, das auf der MAP-Ebene und für jedes LAYER-Objekt
                            spezifiziert
                            wird (auch wenn alle Layer in der gleichen Projektion sind).
                        </li>
                        <li>
                            Wenn Sie auch die Parameter ANGLE des LABEL-Objekts oder LABELANGLEITEM des LAYER-Objekts
                            verwenden,
                            sind diese Parameter relativ zur Ausrichtung der Karte (d.h. sie werden nach dem ANGLE des
                            MAP-Objekts
                            berechnet). Wenn Sie z. B. für die Karte einen ANGLE von 45 und für die Ebene LABELANGLEITEM
                            einen Wert
                            von 45 angegeben haben, erscheint die resultierende Beschriftung nicht gedreht (da die
                            resultierende
                            Karte um 45 Grad im Uhrzeigersinn und die Beschriftung um 45 Grad gegen den Uhrzeigersinn
                            gedreht ist).
                            Beachten Sie, dass ein fehlender ANGLE oder ein auf 0 gesetzter Wert bedeutet, dass die
                            Drehung der
                            Karte ignoriert wird. (Wenn Sie also einen relativen Winkel von Null zur Kartendrehung haben
                            wollen,
                            verwenden Sie einen Wert von fast Null, z. B. 0,0001)
                        </li>
                        <li>
                            Bei Verwendung des STYLE.ANGLE-Parameter eines persönlichen Symbols relativ zur
                            Ausrichtung der Karte (d.h. er wird nach dem ANGLE des MAP-Objekts berechnet). Wenn Sie z.B.
                            für die
                            Karte einen ANGLE-Wert von 45 angegeben haben und dann ein Symbol mit einem ANGLE-Wert von
                            45 verwenden,
                            erscheint die resultierende Beschriftung nicht gedreht (weil die resultierende Karte um 45
                            Grad im
                            Uhrzeigersinn und die Beschriftung um 45 Grad gegen den Uhrzeigersinn gedreht ist). Beachten
                            Sie, dass
                            ein fehlender ANGLE oder ein auf 0 gesetzter Wert bedeutet, dass die Drehung der Karte
                            ignoriert wird.
                            (Wenn Sie also einen relativen Winkel von Null gegenüber der Kartendrehung haben möchten,
                            verwenden Sie
                            einen Wert von fast Null, z. B. 0,0001)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Layout Modals -->
    <div class="modal fade" id="addLayerModal" tabindex="-1" aria-labelledby="layerModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="layerModal">Layer hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="layerName">Name der neuen Ebene</label>
                        <input type="text" class="form-control" id="layerName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" id="layerCreatorButton" class="btn btn-primary" data-bs-dismiss="modal">Erstellen</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>