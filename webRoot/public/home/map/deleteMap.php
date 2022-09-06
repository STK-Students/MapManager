<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();

if (isset($_POST['input-map'])) {
    deleteMap($db);
} else {
    buildMapOptions($db);
}

function deleteMap(Database $db): void
{
    $serviceUUID = $_POST['input-map'];
    $db->removeMap($serviceUUID);
    header('Location: /public/home/home.php?result=success');
}

/**
 * Builds a dropdown with all services in the given group.
 * @param Database $db to use
 */
function buildMapOptions(Database $db): void
{
    $groupUUID = $_GET['uuid'];
    $maps = $db->getGeoServices($groupUUID);

    $options = [];
    for ($i = 0; $i < count($maps); $i++) {
        $map = $maps[$i];
        $options[$i] = "<option value='" . $map->getUUID() . "'>" . $map->getName() . "</option>";
    }
    echo json_encode($options);
}
