<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/api/database.php";

$db =  Database::getInstance();;
$groupUUID = $_GET['uuid'];
$group = $db->getGroup($groupUUID);

if(isset($_POST['submit_group_form'])){
    try{
        $db->removeGroup($group->getUUID());
        header('Location: /templates/home/home.php');
    } catch (Exception $e){
        echo $e->getMessage();
    }
}

?>
<html>
<head>
    <title>Gruppe löschen</title>
    <link rel="stylesheet" href="../../dependencies/Bootstrap/css/bootstrap.min.css">
    <script src="../../dependencies/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dependencies/jQuery/jQuery.js"></script>
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
    </style>
</head>
<body>
    <div class="main">
        <h3>Gruppe löschen</h3>
        <p>Möchten Sie die Gruppe <b><?php echo $group->getName(); ?></b> wirklich löschen?</p>
        <form name="remove_group_form" method="post">
            <button type="submit" name="submit_group_form" class="btn btn-danger">Löschen</button>
        </form>
    </div>
</body>
</html>

?>