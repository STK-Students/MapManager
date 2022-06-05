<?php
require("../../database.php");

$db = new Database("Postgres", "webDevDB", "postgres", "postgres");
$groups = $db->getGroups();
?>

<html>
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="home_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#selectGroup").change(function () {
                var uuid = document.getElementById("selectGroup").value;
                if (uuid == "") {
                    document.getElementById("sidebar-content").style.visibility = "hidden";
                } else {
                    document.getElementById("sidebar-content").style.visibility = "visible";
                    getGroup(uuid);
                }

            });
        });

        function getGroup(groupUUID){
            var data = null;
            let xhttp = new XMLHttpRequest();
            xhttp.open("GET", "../../api.php" + "?getGroup=" + groupUUID, true);
            xhttp.send();
            xhttp.onload = function() {
                if(xhttp.status == 200){
                    data = JSON.parse(this.response)
                    document.getElementById("main-title").innerText = data.name;
                }
            }
        }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
            integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
            crossorigin="anonymous"></script>
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
                        for ($i = 0; $i < count($groups); $i = $i + 1) {
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
        <div class="maps"></div>
        <div class="sidebar">
            <ul class="sidebar-content" id="sidebar-content">
                <li class="sidebar-item"><h4>Mitarbeiter</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="#">Mitarbeiter hinzufügen</a></li>
                        <li class="sidebar-subitem"><a href="#">Mitarbeiter entfernen</a></li>
                        <li class="sidebar-subitem"><a href="#">Mitarbeiter der Gruppe anzeigen</a></li>
                    </ul>
                </li>
                <li class="sidebar-item"><h4>Gruppe</h4>
                    <ul>
                        <li class="sidebar-subitem"><a href="#">Gruppe bearbeiten</a></li>
                        <li class="sidebar-subitem"><a href="#">Gruppe löschen</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>