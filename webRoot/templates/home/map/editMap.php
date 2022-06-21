<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";
$db =  Database::getInstance();;
$map = (object) $db->getMap($_GET["uuid"]);
if (isset($_POST['submit_map_form'])) {
    $db->editMap($map->getUUID(), $_POST["input-name"], $_POST['input-description']);
    header('Location: /templates/home/home.php');
}

?>
<html>
<head>
    <title>Gruppe erstellen</title>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
            integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
            crossorigin="anonymous"></script>
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