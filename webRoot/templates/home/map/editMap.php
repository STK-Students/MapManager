<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db =  Database::getInstance();;
$map = (object) $db->getOGCService($_GET["uuid"]);
if (isset($_POST['submit_map_form'])) {
    $db->editMap($map->getUUID(), $_POST["input-name"], $_POST['input-description']);
    header('Location: /templates/home/home.php');
}

?>
<html lang="de">
<head>
    <title>Gruppe erstellen</title>
    <title>Home</title>
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <style>
        body {
            background-color: #DC3545;
        }
        input {
            width: 400px;
            margin-bottom: 15px;
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
    <h3>Gruppe erstellen</h3>
    <form name="create_map_form" method="post">
        <div class="form-group">
            <?php
                echo '<input type="text" value="'. $map->getName() .'" name="input-name"/>';
                echo '<br/>';
                echo '<input type="text" value="'. $map->getDescription() .'" name="input-description"/>';
            ?>
        </div>
        <button type="submit" name="submit_map_form" class="btn btn-danger">Submit</button>
    </form>
</div>
</body>
</html>