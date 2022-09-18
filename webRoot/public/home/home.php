<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db = Database::getInstance();

/** Emulating SSO on local dev setup **/
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $_SERVER['REMOTE_USER'] = 'bauml';
}

/** Add new users to the DB */
$userID = $_SERVER['REMOTE_USER'];
$db->addUser($userID);

/** Invite System for adding members to group **/
if (isset($_GET["inviteCode"])) {
    $groupUUID = $_GET["inviteCode"];
    if (!$db->isUserInGroup($userID, $groupUUID)) {
        $db->addUserToGroup($groupUUID, $userID);
    }
}

$groups = $db->getGroupsFromUser($userID);
//$currentGroup = $_SESSION['currentGroup'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>MapManager</title>
    <link rel="icon" type="image/png" href="https://www.stadt-koeln.de/images/x22/logo-adler-stadt-koeln.png">
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/jQuery/jQuery.js"></script>
    <script src="GeoServiceTableBuilder.js"></script>
    <script src="home.js" defer></script>
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
                        foreach ($groups as $group) {
                            echo '<option class="dropdown-item" value="' . $group->getUUID() . '">' . $group->getName() . '</option>';
                        }
                        ?>
                    </select>
                </li>
                <?php
                echo '<script defer>
                        $("#selectGroup").val(\'' . $currentGroup . '\');
                    </script>';
                ?>
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
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="modalTitleCreateService"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleCreateService">Dienst erstellen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="create_service_form" action="./map/createMap.php" method="post">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12 gy-2">
                            <input type="text" class="form-control" name="input-name" id="inputCreateServiceName"
                                   placeholder="Name des Dienstes">
                        </div>
                        <div class="col-12 gy-2">
                            <textarea class="form-control" name="input-description" id="inputCreateServiceDescription"
                                      placeholder="Beschreibung des Dienstes"></textarea>
                        </div>
                    </div>
                    <input type="text" style="visibility: hidden" name="group-uuid" id="hiddenInputGroupUUID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="submit-create-map" name="Senden" class="btn btn-primary">Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="modalTitleEditService" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleEditService">Dienst bearbeiten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="edit_service_form" action="./map/editMap.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 gy-2">
                            <input style="visibility: hidden; position: fixed" type="text" class="form-control"
                                   name="input-uuid" id="inputEditServiceUUID"
                                   placeholder="UUID des Dienstes" readonly>
                        </div>
                        <div class="col-12 gy-2">
                            <input type="text" class="form-control" name="input-name" id="inputEditServiceName"
                                   placeholder="Name des Dienstes">
                        </div>
                        <div class="col-12 gy-2">
                            <textarea class="form-control" name="input-description" id="inputEditServiceDescription"
                                      placeholder="Beschreibung des Dienstes"></textarea>
                        </div>
                    </div>
                    <input type="text" style="visibility: hidden" name="group-uuid" id="hiddenInputGroupUUID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="submit-edit-map" value="Senden" class="btn btn-primary">Ok</button>
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
                <h5 class="modal-title" id="modalTitleCreateGroup">Gruppe erstellen</h5>
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
                    <button type="submit" value="Senden" name="submit-create-group" class="btn btn-success">Erstellen
                    </button>
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
            <div class="modal-body">
                <p>Wollen sie die aktuelle Gruppe wirklich löschen?</p>
                <form name="remove_group_form" action="group/deleteGroup.php" method="post">
            </div>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" name="edit_group_form_submit" class="btn btn-success">Bearbeiten</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#editGroupModal').on('show.bs.modal', function () {
        let name = $('#selectGroup option:selected').text();
        $('#groupNameEdit').val(name);
    });
</script>

<!-- Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="modalTitleAddUser"
     aria-hidden="true">
    <?php
    //TODO: localhost reference
    $inviteCode = "http://localhost/public/home/home.php?inviteCode=" . $currentGroup;
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
                        echo "<td>" . $user->getUUID() . "</td>";
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
                        $user = (object) $users[$i];
                        echo "<tr>";
                        echo "<td>" . $user->getUUID() . "</td>";
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

<!-- Toasts -->

<!-- Success -->

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
         aria-live="assertive"
         aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Aktion erfolgreich!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Failure -->

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="failureToast" class="toast align-items-center bg-danger border-0" role="alert" aria-live="assertive"
         aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Aktion fehlgeschlagen!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>


</body>
</html>
