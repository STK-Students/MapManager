<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

/** Invite System for adding members to group **/
$db = Database::getInstance();

if (isset($_SESSION["authenticatedUser"])) {
    $userUUID = $_SESSION["authenticatedUser"];
    if (isset($_GET["inviteCode"])) {
        $groupUUID = $_GET["inviteCode"];
        if (!$db->isUserInGroup($userUUID, $groupUUID)) {
            $db->addUserToGroup($groupUUID, $userUUID);
        }
    }
} else {
    header("Location: /templates/auth/login.php");
}
$groups = $db->getGroupsFromUser($_SESSION['authenticatedUser']);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Home</title>
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/jQuery/jQuery.js"></script>
    <script src="OGCServiceTableBuilder.js"></script>
    <?php
    if (isset($_GET['uuid'])) {
        $selectedGroup = $db->getGroup($_GET['uuid']);
        echo "<script>$('#selectGroup').val(". $selectedGroup->getUUID() .");</script>";
    }
    ?>
    <link rel="stylesheet" href="home_style.css">
    <link rel="stylesheet" href="/.media/fontAndNavbar.css">
</head>

<body>
<input type="text" id="hiddenGroupUUID" style="visibility: hidden; position: absolute">
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
                    <select class="dropdown" id="selectGroup">
                        <option value="">Gruppe wählen</option>
                        <?php
                        for ($i = 0; $i < count($groups); $i++) {
                            $item = (object)$groups[$i];
                            echo '<option class="dropdown-item" value="' . $item->getUUID() . '">' . $item->getName() . '</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-danger uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#createGroupModal" id="createGroup">
                        Gruppe erstellen
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div>
    <div class="title"><h2 id="main-title"></h2></div>
    <div class="content">
        <div class="maps" id="maps">
            <table id="table-maps" class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Beschreibung</th>
                    <th>Erstellungsdatum</th>
                    <th>Öffnen</th>
                    <th>Bearbeiten</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="container sidebar">
            <div class="row gy-2">
                <div class="col-11">
                    <h3>Dienste</h3>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-success uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#createServiceModal" id="createService">
                        Dienst erstellen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-danger uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#deleteServiceModal">
                        Dienst löschen
                    </button>
                </div>
                <div class="col-11">
                    <h3>Gruppe</h3>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-danger uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        Gruppe löschen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-secondary uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        Gruppe bearbeiten
                    </button>
                </div>
                <div class="col-11">
                    <h3>Mitglieder</h3>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-success uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        Mitglieder hinzufügen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-danger uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        Mitglieder entfernen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-secondary uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                        Mitglieder anzeigen
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Dialogs -->

<!-- Create Service -->
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Dienst erstellen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="create_group_form" action="./map/createMap.php" method="post">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12 gy-2">
                            <input type="text" class="form-control" name="input-name" id="inputServiceName"
                                   placeholder="Name des Dienstes">
                        </div>
                        <div class="col-12 gy-2">
                            <textarea class="form-control" name="input-description" id="inputServiceDescription"
                                      placeholder="Beschreibung des Dienstes"></textarea>
                        </div>
                    </div>
                    <input type="text" style="visibility: hidden" name="group-uuid" id="hiddenInputGroupUUID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Remove Service -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="modalTitleDeleteService"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleDeleteService">Dienst löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="remove_map_form" action="map/deleteMap.php" method="post">
                <div class="modal-body">
                    <select name="input-map" id="deleteServiceList" class="dropdown">
                        <script>
                            $("#deleteServiceModal").on('show.bs.modal', function () {
                                $.ajax("./map/deleteMap.php?uuid=" + $("#hiddenGroupUUID").val()).done(function (data) {
                                    let options = JSON.parse(data);
                                    for (const key in options) {
                                        $("#deleteServiceList").append(options[key]);
                                    }
                                });
                            });
                        </script>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Group -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="modalTitleCreateGroup"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleDeleteService">Gruppe erstellen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="remove_map_form" action="group/createGroup.php" method="post">
                <div class="modal-body">
                    <label for="groupName">Gruppenname</label>
                    <input type="text" class="form-control" id="groupName" name="groupName"
                           aria-describedby="groupName">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="submit-create-group" class="btn btn-success">Erstellen</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Remove Group -->

<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="modalTitleDeleteService"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleDeleteService">Gruppe löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="remove_map_form" action="map/deleteGroup.php" method="post">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Group -->
<!-- Add User -->
<!-- Remove User-->
<!-- Show User -->


</body>
</html>