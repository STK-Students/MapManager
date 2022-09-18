<html lang="de">
<header>
    <title>Layer Style</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Bootstrap responsive design meta tag-->
    <link rel="stylesheet" href="layerClass.css">
    <link rel="stylesheet" href="/.media/fontAndNavbar.css">
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <script src="../formSubmitter.js" defer></script>
    <script src="../formFiller.js" defer></script>
    <script src="../TableBuilder.js" defer></script>
    <script src="layerClass.js"></script>
    <?php

    use MapFile\Model\LayerClass;

    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/LayerClass/LayerClassSerializer.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/formHandler/LayerClass/LayerClassDeserializer.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/dependencies/MapFileParser/Model/LayerClass.php";

    if (!isset($_GET['serviceUUID'])) {
        echo "Diese Seite können Sie nicht direkt aufrufen.";
        echo "<br> Bitte rufen sie die <a href='/public/home/home.php'>Homepage</a> auf.";
        die;
    }

    $map = MapFileHandler::loadMapByUUID($_GET['serviceUUID']);

    $layerIndex = $_GET['layerIndex'];
    $classIndex = $_GET['layerClassIndex'];
    $layerClass = $map->layer->get($layerIndex)->class->get($classIndex);
    if ($layerClass == null) {
        $layerClass = new LayerClass();
    }
    $json = json_encode(LayerClassSerializer::layerClassToJSON($layerClass));
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
                    echo '<a href="/public/forms/layer/layer.php?serviceUUID=' . $_GET['serviceUUID'] . '&rowNumber=' .
                        $_GET['layerIndex'] . '" class="btn btn-outline-secondary nav-link active" style="margin-top:10px; margin-left:20px">Zurück zu den Layer-Einstellungen</a>';
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<h1>Darstellung des Layers bearbeiten</h1>
<div class="container-lg">
    <br>

    <form name="Eingabe" id='mapForm' class="needs-validation">
        <div class="row">
            <div class="col-2">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" aria-describedby="name" required>
            </div>
        </div>
    </form>
    <br>

    <!-- Label Table -->
    <h3 style="display: inline-block">Label</h3>
    <button id="labelCreatorButton" style="margin-left: 15px; margin-bottom: 7px" data-bs-toggle="modal" data-bs-target="#addLabelModal"
            class="btn btn-outline-success">+
    </button>
    <table id="labelTable" class="table">
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
    <br>
    <br>
    <!-- Style Table -->
    <h3 style="display: inline-block">Style</h3>
    <button id="styleCreatorButton" style="margin-left: 15px; margin-bottom: 7px" data-bs-toggle="modal" data-bs-target="#addStyleModal"
            class="btn btn-outline-success">+
    </button>
    <table id="styleTable" class="table">
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

    <!-- Label Table -->
    <div class="modal fade" id="addLabelModal" tabindex="-1" aria-labelledby="addClassModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModal">Label hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="labelName">Name des Labels</label>
                        <input type="text" class="form-control" id="labelName">
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

    <!-- Style Table -->
    <div class="modal fade" id="addStyleModal" tabindex="-1" aria-labelledby="addClassModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModal">Style hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="styleName">Name des Styles</label>
                        <input type="text" class="form-control" id="styleName">
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

</div>

</body>
</html>
