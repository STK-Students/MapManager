<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db = Database::getInstance();;
$group = $_GET['uuid'];

if (isset($_POST['submit_group_form'])) {
    $name = $_POST['input-name'];
    $description = $_POST['input-description'];
    $creationDate = date('Y-m-d');
    try {
        $result = $db->addMap($name, $description, $creationDate, $group);
        $generatedUUID = pg_fetch_result($result, 0, 0);
        $_SESSION['currentMapUUID'] = $generatedUUID;
        //header('Location: /templates/forms/edit.php');
        require $_SERVER['DOCUMENT_ROOT'] . "/api/MapFileWriter.php";
    } catch (Exception $e) {
        print($e->getMessage());
    }
}

?>
<html>
<head>
    <title>Dienst erstellen</title>
    <title>Home</title>
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <style>
        body {
            background-color: #DC3545;
        }

        .main {
            position: relative;
            background-color: white;
            width: 500px;
            height: 250px;
            margin: 250px auto 0px auto;
            border-radius: 4px;
            text-align: center;
        }

        .main h3 {
            position: relative;
            top: 10px;
            text-align: center;
        }

        .form-group {
            width: 80%;
            margin: 30px auto 0px auto;
        }

        .main button {
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="main">
    <h3>Dienst erstellen</h3>
    <div class="container overflow-hidden">
        <form name="create_group_form" method="post">
            <div class="form-group">
                <div class="row gy-4">
                    <input type="text" class="form-control" name="input-name" id="inputGroupName"
                           placeholder="Name eingeben">
                </div>
                <div class="row gy-4">
                    <input type="text" class="form-control" name="input-description" id="inputGroupDescription"
                           placeholder="Beschreibung eingeben">
                </div>
            </div>
            <button type="submit" name="submit_group_form" class="btn btn-danger">Submit</button>
        </form>
    </div>
</div>
</body>
</html>

