<?php
session_start();

require("../../../database.php");

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
<html>
<head>
    <title>Gruppe löschen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
            integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT"
            crossorigin="anonymous"></script>
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