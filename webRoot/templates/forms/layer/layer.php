<?php
$rowNumber = $_GET["rowNumber"];
$layerUUID = $_GET["layerUUID"];
$mapUUID = $_GET["mapUUID"];
?>
<html>
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
    <script src="layer.js"></script>
    <?php
    // Get Map Data
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileHandler.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/ServiceConverter.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

    if (isset($_SESSION['currentServiceUUID']) && $_SESSION['currentServiceUUID'] != $mapUUID || !isset($_SESSION['map'])) {
        $mapFilePath = Database::getInstance()->getGeoService($mapUUID)->getPath();
        $map = MapFileHandler::loadMapFromFile($mapFilePath);
        $_SESSION['currentServiceUUID'] = $mapUUID;
    } else {
        $map = unserialize($_SESSION['map']);
    }
    $json = mapToJSON($map);
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
                    <a class="nav-link active" href="/templates/forms/map/map.php?uuid=<?php echo $mapUUID; ?>">Zurück
                        zur Übersicht</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="wrapper">
    <form name="Eingabe" id='layerForm' class="needs-validation">
        <h1>Layer Einstellungen</h1>
        <div class="row"><!--Start Row 1-->
            <div class="col-4">
                <label for="name">Layer Name</label>
                <input type="text" class="form-control" id="name" placeholder="Layer Name"
                       aria-describedby="nameHelp" readonly>
                <small id="nameHelp" class="form-text text-muted">
                    Der Name des Layers
                </small>
            </div>
            <div class="col-4">
                <label for="name">Data</label>
                <input type="text" class="form-control" id="data" placeholder="Data"
                       aria-describedby="nameHelp">
                <small id="nameHelp" class="form-text text-muted">
                    Data
                </small>
            </div>
            <div class="col-4">
                <label for="name">Verbindungstyp</label>
                <input type="text" class="form-control" id="connectiontype" placeholder="Verbindungstyp"
                       aria-describedby="nameHelp">
                <small id="nameHelp" class="form-text text-muted">
                    Verbindungstyp
                </small>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <button type="button" id="submitAPIButton" class="btn btn-success">Speichern</button>
            </div>
        </div>
    </form>
</body>

</html>
