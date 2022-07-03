<?php
session_start();

require  $_SERVER['DOCUMENT_ROOT'] . "api/database.php";

$db =  Database::getInstance();;
$groupUUID = $_GET['uuid'];
$maps = $db->getMaps($groupUUID);
if(isset($_POST['submit_map_form'])){
    try{
        $mapUUID = $_POST['input-map'];
        $db->removeMap($mapUUID);
        header('Location: /templates/home/home.php');
    } catch (Exception $e){
        echo $e->getMessage();
    }
}

?>
<html lang="de">
<head>
    <title>Gruppe löschen</title>
    <link rel="stylesheet" href="../../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../dependencies/jQuery/jQuery.js"></script>
    <style>
        body{
            background-color: #DC3545;
        }
        .main{
            position: relative;
            background-color: white;
            width: 500px;
            height: 250px;
            margin: 250px auto 0px auto;
            border-radius: 4px;
            text-align: center;
            display: block;
        }
        .main h3{
            position: relative;
            top: 10px;
            text-align: center;
        }
        .main button{
            margin-top: 20px;
        }
        .main p {
            margin-top: 25px;
        }
        .main b {
            color: #DC3545;
        }
        .dropdown {
            height: 30px;
            border: 1px solid black;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="main">
    <h3>Gruppe löschen</h3>
    <form name="remove_map_form" method="post">
        <select name="input-map" class="dropdown">
            <?php
                for($i = 0; $i < count($maps); $i++){
                    $map = (object) $maps[$i];
                    echo "<option value='" .$map->getUUID(). "'>" .$map->getName(). "</option>";
                }
            ?>
        </select>
        <br/>
        <button type="submit" name="submit_map_form" class="btn btn-danger">Löschen</button>
    </form>
</div>
</body>
</html>

?>