<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();;

if (isset($_POST['input-map'])) {
    deleteMap($db);
} else {
    buildMapOptions($db);
}

function deleteMap(Database $db): void
{
    $mapUUID = $_POST['input-map'];
    $db->removeMap($mapUUID);
    header('Location: /templates/home/home.php');
}

/**
 * Builds a dropdown with all services in the given group.
 * @param Database $db to use
 */
function buildMapOptions(Database $db): void
{
    $groupUUID = $_GET['uuid'];
    $maps = $db->getMaps($groupUUID);

    $options = [];
    for ($i = 0; $i < count($maps); $i++) {
        $map = $maps[$i];
        $options[$i] = "<option value='" . $map->getUUID() . "'>" . $map->getName() . "</option>";
    }
    echo json_encode($options);
}

