<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    die("Sie müssen sich einloggen.");
}
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8"> <!--Ermöglicht einfache Eingabe von Sonderzeichen-->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="edit_style.css">
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/Bootstrap/js/formValidator.js" defer></script>

    <title>Dienst erstellen</title>

    <style>

        input:invalid:required {
            border: 2px dashed red;
        }

    </style>
</head>
<body>


<h1>Dienst erstellen</h1>
<div class="needs-validation"></div>
<div class="container-lg">
    <form name="Eingabe" class="needs-validation">
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
                    <option>Meter</option>
                    <option>Kilometer</option>
                    <option>Dezimalgrad</option>
                    <option>Feet</option>
                    <option>Inches</option>
                    <option>Miles</option>
                    <option>Seemeile</option>
                </select>
            </div>

            <div class="col-2">
                <label for="angle">Kartendrehung</label>
                <div class="input-group mb-3 has-validation">
                    <input type="text" class="form-control" id="angle" value="0" aria-describedby="angleHelpButton"
                           required>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#angleModal"><b>?</b></button>
                    <div class="invalid-feedback">Diese Angabe ist Pflicht.</div>
                </div>
            </div>

        </div><!--End Row 1-->

        <div class="row"><!--Start Row 2-->

            <div class="col-2 gy-1">
                <label for="size">Auflösung</label>
                <input type="text" class="form-control" id="size" placeholder="1920x1080" required>
                <div class="invalid-feedback">
                    Diese Angabe ist Pflicht.
                </div>
            </div>

            <div class="col-2 gy-1">
                <label for="maxsize">Maximale Auflösung</label>
                <input type="text" class="form-control" id="maxsize" placeholder="4096x4096" value="4096x4096">
            </div>

        </div><!--End Row 2-->

        <br>
        <button type="submit" id="submitAPIButton" class="btn btn-success">Dienst erstellen</button>
        <script>
            document.getElementById("submitAPIButton").addEventListener('click', async function () {
                if (formIsValid) {
                    await fetch(encodeURI('/api/formHandler/mapHandler.php?'));
                }
            });

            function formIsValid() {
                let name = document.getElementById('name').getAttribute('value');
                if (name == null || name === '') {
                    document.getElementById('name').
                }
            }
        </script>

    </form>

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
</div>
</body>
</html>

<?php

use MapFile\Model\Layer;
use MapFile\Model\Map;

$mapFileLoc = "../../dependencies/MapFileParser";
$doctrineLoc = "../../dependencies/Doctrine";
require_once "{$mapFileLoc}/Model/Map.php";
require_once "{$mapFileLoc}/Model/Layer.php";
require_once "{$mapFileLoc}/Writer/WriterInterface.php";
require_once "{$mapFileLoc}/Writer/Writer.php";
require_once "{$mapFileLoc}/Writer/Map.php";
require_once "{$mapFileLoc}/Writer/Projection.php";
require_once "{$mapFileLoc}/Writer/Layer.php";
require_once "{$doctrineLoc}/Common/Collections/Selectable.php";
require_once "{$doctrineLoc}/Common/Collections/Collection.php";
require_once "{$doctrineLoc}/Common/Collections/ArrayCollection.php";

require_once "../../MapFileWriter.php";

//
//$map = new Map();
//
//$map->name = $_POST["name"];
//$map->size = $_POST["size"];
//$map->maxsize = $_POST["maxsize"];
//$map->units = $_POST["units"];
//$map->scaledenom = $_POST["scaledenom"];
//
//
//$controller = new MapFileHandler($map);
//$controller->saveMapFile("../../output.map");
//echo $controller->serializedMapFile;

?>
