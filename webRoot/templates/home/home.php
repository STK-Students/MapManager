<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
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
            $("#selectGroup").change(function () {
                var uuid = document.getElementById("selectGroup").value;
                if (uuid == "") {
                    document.getElementById("sidebar-content").style.visibility = "hidden";
                } else {
                    document.getElementById("sidebar-content").style.visibility = "visible";
                    let groupData = fetch('http://localhost/api.php?getGroup=' + uuid)
                        .then(response => response.json());
                    document.getElementById("main-title").innerText = groupData.name;
                    document.getElementById("add-employee").href = ""
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
                            $item = (object) $groups[$i];
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
        <div class="maps"></div>
        <div class="sidebar">
            <ul class="sidebar-content" id="sidebar-content">
                <li class="sidebar-item"><h4>Mitarbeiter</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="#" id="add-employee">Mitarbeiter hinzufügen</a></li>
                        <li class="sidebar-subitem"><a href="#" id="remove-employee">Mitarbeiter entfernen</a></li>
                        <li class="sidebar-subitem"><a href="#" id="show-employees">Mitarbeiter der Gruppe anzeigen</a></li>
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