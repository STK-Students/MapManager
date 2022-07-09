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
$currentGroup = $_SESSION['currentGroup'];
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
        echo "<script>$('#selectGroup').val(" . $selectedGroup->getUUID() . ");</script>";
    }
    if (isset($_GET['result'])) {
        if ($_GET['result'] == "success") {
            echo '<div class="alert alert-success alert-dismissible fade show shadow">
                <i class="bi bi-info-square"></i> Erfolgreich
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        } else if ($_GET['result'] == "failed") {
            echo '<div class="alert alert-danger alert-dismissible fade show shadow">
                    <i class="bi bi-exclamation-triangle"></i> Fehler
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                 </div>';
        }
        echo '<script>delay(3000).then(() => window.location.href = "http://localhost/templates/home/home.php");</script>'; // this reloads
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
                            data-bs-target="#deleteGroupModal">
                        Gruppe löschen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-secondary uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#editGroupModal">
                        Gruppe bearbeiten
                    </button>
                </div>
                <div class="col-11">
                    <h3>Mitglieder</h3>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-success uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                        Mitglieder hinzufügen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-danger uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#deleteUserModal">
                        Mitglieder entfernen
                    </button>
                </div>
                <div class="col-11">
                    <button type="button" class="btn btn-secondary uniform-buttons" data-bs-toggle="modal"
                            data-bs-target="#showUserModal">
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
            <form name="create_group_form" action="group/createGroup.php" method="post">
                <div class="modal-body">
                    <label for="groupNameCreate">Gruppenname</label>
                    <input type="text" class="form-control" id="groupNameCreate" name="groupNameCreate"
                           aria-describedby="groupNameCreate">
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
<div class="modal fade" id="deleteGroupModal" tabindex="-1" aria-labelledby="modalTitleDeleteGroup"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleDeleteGroup">Gruppe löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="remove_group_form" action="group/deleteGroup.php" method="post">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="remove_group_form_submit" class="btn btn-danger">Löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Group -->
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="modalTitleEditGroup"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleEditGroup">Gruppe bearbeiten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="remove_group_form" action="group/editGroup.php" method="post">
                <div class="modal-body">
                    <label for="groupName">Gruppenname</label>
                    <input type="text" class="form-control" id="groupNameEdit" name="groupNameEdit"
                           aria-describedby="groupNameEdit">
                    <script>
                        document.getElementById('groupNameEdit').value = document.getElementById('selectGroup').value;
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="edit_group_form_submit" class="btn btn-success">Bearbeiten</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="modalTitleAddUser"
     aria-hidden="true">
    <?php
    $inviteCode = "http://localhost/templates/home/home.php?inviteCode=" . $currentGroup;
    ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleAddUser">Mitarbeiter hinzufügen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="inviteCode">Einladungslink</label>
                <input type="text" class="form-control" id="inviteCode" name="inviteCode"
                       value="<?php echo $inviteCode ?>"
                       aria-describedby="inviteCode">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                <button type="button" onclick="copy()" class="btn btn-primary" data-bs-dismiss="modal">Kopieren
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Remove User-->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="modalTitledeleteUser"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleDeleteUser">Mitarbeiter löschen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <?php
                    $users = $db->getUsersFromGroup($currentGroup);
                    for ($i = 0; $i < count($users); $i++) {
                        $user = (object)$users[$i];
                        echo "<tr>";
                        echo "<td>" . $user->getFirstname() . "</td>";
                        echo "<td>" . $user->getLastname() . "</td>";
                        echo "<td><a class='btn btn-danger' href='employee/deleteEmployee.php'>Löschen</a></td>";
                        echo "</tr>";
                    }

                    ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
            </div>
        </div>
    </div>
</div>

<!-- Show User -->
<div class="modal fade" id="showUserModal" tabindex="-1" aria-labelledby="modalTitleshowUser"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleShowUser">Mitarbeiter anzeigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <?php
                    $users = $db->getUsersFromGroup($currentGroup);
                    for ($i = 0; $i < count($users); $i++) {
                        $user = (object)$users[$i];
                        echo "<tr>";
                        echo "<td>" . $user->getFirstname() . "</td>";
                        echo "<td>" . $user->getLastname() . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>