<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header("Location: http://localhost/templates/login/login.php");
    die("Sie müssen sich einloggen.");
}
require("../../database.php");

$db = new Database("Postgres", "webDevDB", "postgres", "postgres");
$groups = $db->getGroups();
?>

<html lang="de">
<head>
    <title>Home</title>
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/jQuery/jQuery.js"></script>
    <link rel="stylesheet" href="home_style.css">
    <script>
        $(document).ready(function () {
            $("#selectGroup").change(async function () {
                var uuid = document.getElementById("selectGroup").value;
                if (uuid == "") {
                    document.getElementById("sidebar-content").style.visibility = "hidden";
                    document.getElementById("table-maps").style.visibility = "hidden";
                } else {
                    document.getElementById("sidebar-content").style.visibility = "visible";
                    await fetch('http://localhost/api.php?getGroup=' + uuid)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById("main-title").innerText = data.name;
                            document.getElementById("edit-group").href = "/templates/home/groups/editGroup.php?mode=edit&uuid=" + data.uuid;
                            document.getElementById("remove-group").href = "/templates/home/groups/removeGroup.php?uuid=" + data.uuid;
                            document.getElementById("add-map").href = "/templates/home/map/createMap.php?uuid=" + data.uuid;
                            document.getElementById("remove-map").href = "/templates/home/map/removeMap.php?uuid=" + data.uuid;
                        });
                    await fetch('http://localhost/api.php?getMaps=' + uuid)
                        .then(response => response.json())
                        .then(data => {
                            const table = document.getElementById("table-maps");
                            table.style.visibility = "visible";
                            for (const item in data) {
                                const newTR = document.createElement("tr");

                                const nameTD = document.createElement("td");
                                const descriptionTD = document.createElement("td");
                                const creationDateTD = document.createElement("td");
                                const openTD = document.createElement("td");
                                const editTD = document.createElement("td");
                                const openLink = document.createElement("a");
                                openLink.href = "/templates/forms/edit.php";
                                const editLink = document.createElement("a");
                                editLink.href = "/templates/home/map/editMap.php?uuid=" + data[item].uuid;

                                nameTD.innerText = data[item].name;
                                descriptionTD.innerText = data[item].description;
                                creationDateTD.innerText = data[item].creationDate;
                                openLink.innerText = "Öffnen";
                                editLink.innerText = "Bearbeiten";
                                openTD.appendChild(openLink);
                                editTD.appendChild(editLink);

                                newTR.appendChild(nameTD);
                                newTR.appendChild(descriptionTD);
                                newTR.appendChild(creationDateTD);
                                newTR.appendChild(openTD);
                                newTR.appendChild(editTD);

                                table.appendChild(newTR);
                            }
                        });
                }

            });
        });


    </script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Map Manager</a>
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
                    <a class="nav-link" href="groups/editGroup.php?mode=create">Gruppe erstellen</a>
                </li>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-light" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<div class="main">
    <div class="title"><h2 id="main-title"></h2></div>
    <div class="content">
        <div class="maps" id="maps">
            <table id="table-maps">
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
        <div class="sidebar">
            <ul class="sidebar-content" id="sidebar-content">
                <li class="sidebar-item"><h4>Dienste</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="" id="add-map">Dienst hinzufügen</a></li>
                        <li class="sidebar-subitem"><a href="#" id="remove-map">Dienst entfernen</a></li>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item"><h4>Mitarbeiter</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="#" id="add-employee">Mitarbeiter hinzufügen</a></li>
                        <li class="sidebar-subitem"><a href="#" id="remove-employee">Mitarbeiter entfernen</a></li>
                        <li class="sidebar-subitem"><a href="#" id="show-employees">Mitarbeiter der Gruppe anzeigen</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item"><h4>Gruppe</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="#" id="edit-group">Gruppe bearbeiten</a></li>
                        <li class="sidebar-subitem"><a href="#" id="remove-group">Gruppe löschen</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>